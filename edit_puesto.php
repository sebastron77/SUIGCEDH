<?php
$page_title = 'Editar Puesto';
require_once('includes/load.php');

$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}
if ($nivel_user == 2) {
    page_require_level_exacto(2);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
}
if ($nivel_user > 2 && $nivel_user < 14) :
    redirect('home.php');
endif;
if ($nivel_user > 14) :
    redirect('home.php');
endif;
?>
<?php
$e_puestos = find_by_id('cat_puestos', (int)$_GET['id'], 'id_cat_puestos');
if (!$e_puestos) {
    $session->msg("d", "La comunidad indígena no existe, verifique el ID.");
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

        $query  = "UPDATE cat_puestos SET ";
        $query .= "descripcion='{$name}' ";
        $query .= "WHERE id_cat_puestos='{$db->escape($e_puestos['id_cat_puestos'])}'";
		 
		$result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "El Puesto ha sido actualizado! '".($name)."'");
			insertAccion($user['id_user'],'"'.$user['username'].'" edito el puesto '.$name.'(id:'.(int)$e_puestos['id_cat_puestos'].').',2);
            redirect('edit_puesto.php?id=' . (int)$e_puestos['id_cat_puestos'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el área!');
            redirect('edit_puesto.php?id=' . (int)$e_puestos['id_cat_puestos'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_puesto.php?id=' . (int)$e_puestos['id_cat_puestos'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8'); include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Editar Puesto</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="edit_puesto.php?id=<?php echo (int)$e_puestos['id_cat_puestos']; ?>" class="clearfix">
        <div class="form-group">
            <label for="area-name" class="control-label">Nombre del Puesto</label>
            <input type="name" class="form-control" name="descripcion" value="<?php echo ucwords($e_puestos['descripcion']); ?>">            
        
        </div>
        <div class="form-group clearfix">
            <a href="cat_puestos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>