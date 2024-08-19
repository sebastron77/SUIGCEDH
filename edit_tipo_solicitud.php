<?php
$page_title = 'Editar Tipo Diseños';
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 10) {
    page_require_level_exacto(10);
}
if ($nivel_user > 2 && $nivel_user < 10) :
    redirect('home.php');
endif;
?>
<?php
$e_detalles = find_by_id('cat_tipo_solicitud', (int)$_GET['id'], 'id_cat_tipo_solicitud');
if (!$e_detalles) {
    $session->msg("d", "La discapacidad no existe, verifique el ID.");
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

        $query  = "UPDATE cat_tipo_solicitud SET ";
        $query .= "descripcion='{$name}' ";
        $query .= "WHERE id_cat_tipo_solicitud='{$db->escape($e_detalles['id_cat_tipo_solicitud'])}'";
		 
		$result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Tipo de Solicitud ha actualizada! '".($name)."'");
			insertAccion($user['id_user'],'"'.$user['username'].'" edito el tipo de Solicitud '.$name.'(id:'.(int)$e_detalles['id_cat_tipo_solicitud'].').',2);
            redirect('edit_tipo_solicitud.php?id=' . (int)$e_detalles['id_cat_tipo_solicitud'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el área!');
            redirect('edit_tipo_solicitud.php?id=' . (int)$e_detalles['id_cat_tipo_solicitud'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_tipo_solicitud.php?id=' . (int)$e_detalles['id_cat_tipo_solicitud'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8'); include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Editar Tipo de Diseño</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="edit_tipo_solicitud.php?id=<?php echo (int)$e_detalles['id_cat_tipo_solicitud']; ?>" class="clearfix">
        <div class="form-group">
            <label for="area-name" class="control-label">Nombre del Tipo de Solicitud</label>
            <input type="name" class="form-control" name="descripcion" value="<?php echo ucwords($e_detalles['descripcion']); ?>">            
        
        </div>
        <div class="form-group clearfix">
            <a href="cat_tipo_solicitud.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>