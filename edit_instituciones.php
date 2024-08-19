<?php
$page_title = 'Editar Institución de Procedencia';
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
$e_instituciones = find_by_id('cat_instituciones', (int)$_GET['id'], 'id_cat_instituciones');
if (!$e_instituciones) {
    $session->msg("d", "La Institución de Procedencia no existe, verifique el ID.");
    redirect('cat_carreras_ss.php');
}
?>
<?php
if (isset($_POST['update'])) {

    $req_fields = array('descripcion');
    validate_fields($req_fields);
    if (empty($errors)) {
        $name = remove_junk($db->escape(($_POST['descripcion'])));
		
        $estatus = $_POST['estatus'];

        $query  = "UPDATE cat_instituciones SET ";
        $query .= "descripcion='{$name}' ";
        $query .= "WHERE id_cat_instituciones='{$db->escape($e_instituciones['id_cat_instituciones'])}'";
		 
		$result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Institución de Procedencia ha actualizada! '".($name)."'");
			insertAccion($user['id_user'],'"'.$user['username'].'" edito la Institución de Procedencia '.$name.'(id:'.(int)$e_instituciones['id_cat_instituciones'].').',2);
            redirect('edit_instituciones.php?id=' . (int)$e_instituciones['id_cat_instituciones'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado la Institución de Procedencia, debido a que no hay cambios registrados en la descripción!');
            redirect('edit_instituciones.php?id=' . (int)$e_instituciones['id_cat_instituciones'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_instituciones.php?id=' . (int)$e_instituciones['id_cat_instituciones'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8'); include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Editar Institución de Procedencia</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="edit_instituciones.php?id=<?php echo (int)$e_instituciones['id_cat_instituciones']; ?>" class="clearfix">
        <div class="form-group">
            <label for="area-name" class="control-label">Nombre de la Institución de Procedencia</label>
            <input type="name" class="form-control" name="descripcion" value="<?php echo ucwords($e_instituciones['descripcion']); ?>">            
        
        </div>
        <div class="form-group clearfix">
            <a href="cat_instituciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>