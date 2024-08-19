<?php
$page_title = 'Editar Género';
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
$e_comunidad = find_by_id('cat_genero', (int)$_GET['id'], 'id_cat_gen');
if (!$e_comunidad) {
    $session->msg("d", "El Género no existe, verifique el ID.");
    redirect('cat_genero.php');
}
?>
<?php
if (isset($_POST['update'])) {

    $req_fields = array('descripcion');
    validate_fields($req_fields);
    if (empty($errors)) {
        $name = remove_junk($db->escape(($_POST['descripcion'])));
		
        $estatus = $_POST['estatus'];

        $query  = "UPDATE cat_genero SET ";
        $query .= "descripcion='{$name}' ";
        $query .= "WHERE id_cat_gen='{$db->escape($e_comunidad['id_cat_gen'])}'";
		 
		$result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Género ha actualizado! '".($name)."'");
			insertAccion($user['id_user'],'"'.$user['username'].'" edito el Género '.$name.'(id:'.(int)$e_comunidad['id_cat_gen'].').',2);
            redirect('edit_genero.php?id=' . (int)$e_comunidad['id_cat_gen'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado la Género, debido a que no hay cambios registrados en la descripción!');
            redirect('edit_genero.php?id=' . (int)$e_comunidad['id_cat_gen'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_genero.php?id=' . (int)$e_comunidad['id_cat_gen'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8'); include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Editar Género</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="edit_genero.php?id=<?php echo (int)$e_comunidad['id_cat_gen']; ?>" class="clearfix">
        <div class="form-group">
            <label for="area-name" class="control-label">Nombre del Género</label>
            <input type="name" class="form-control" name="descripcion" value="<?php echo ucwords($e_comunidad['descripcion']); ?>">            
        
        </div>
        <div class="form-group clearfix">
            <a href="cat_genero.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>