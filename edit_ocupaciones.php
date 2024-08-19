<?php
$page_title = 'Editar Ocupación';
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}

if ($nivel_user == 50) {
    page_require_level_exacto(50);
}
?>
<?php
$e_comunidad = find_by_id('cat_ocupaciones', (int)$_GET['id'], 'id_cat_ocup');
if (!$e_comunidad) {
    $session->msg("d", "La Ocupación no existe, verifique el ID.");
    redirect('cat_comunidad.php');
}
?>
<?php
if (isset($_POST['update'])) {

    $req_fields = array('descripcion');
    validate_fields($req_fields);
    if (empty($errors)) {
        $name = remove_junk($db->escape(($_POST['descripcion'])));
		
        $estatus = $_POST['estatus'];

        $query  = "UPDATE cat_ocupaciones SET ";
        $query .= "descripcion='{$name}' ";
        $query .= "WHERE id_cat_ocup='{$db->escape($e_comunidad['id_cat_ocup'])}'";
		 
		$result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Comunidad Indígena ha actualizada! '".($name)."'");
			insertAccion($user['id_user'],'"'.$user['username'].'" edito la comunidad indígena '.$name.'(id:'.(int)$e_comunidad['id_cat_ocup'].').',2);
            redirect('edit_ocupaciones.php?id=' . (int)$e_comunidad['id_cat_ocup'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el área!');
            redirect('edit_ocupaciones.php?id=' . (int)$e_comunidad['id_cat_ocup'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_ocupaciones.php?id=' . (int)$e_comunidad['id_cat_ocup'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8'); include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Editar Ocupación</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="edit_ocupaciones.php?id=<?php echo (int)$e_comunidad['id_cat_ocup']; ?>" class="clearfix">
        <div class="form-group">
            <label for="area-name" class="control-label">Nombre de la Ocupación</label>
            <input type="name" class="form-control" name="descripcion" value="<?php echo ucwords($e_comunidad['descripcion']); ?>">            
        
        </div>
        <div class="form-group clearfix">
            <a href="cat_ocupaciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>