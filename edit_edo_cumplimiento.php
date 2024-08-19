<?php
$page_title = 'Editar Estado de Cumplimiento';
require_once('includes/load.php');
$user = current_user();
page_require_level(1);
?>
<?php
$a_catalogo = find_by_id('cat_edo_cumpl', (int)$_GET['id'], 'id_cat_edo_cumpl');
if (!$a_catalogo) {
    $session->msg("d", "El Estado de Cumplimiento no existe, verifique el ID.");
    redirect('cat_edo_cumplimiento.php');
}
?>
<?php
if (isset($_POST['update'])) {

    $req_fields = array('descripcion');
    validate_fields($req_fields);
    if (empty($errors)) {
        $name = remove_junk($db->escape(($_POST['descripcion'])));
		
        $estatus = $_POST['estatus'];

        $query  = "UPDATE cat_edo_cumpl SET ";
        $query .= "descripcion='{$name}' ";
        $query .= "WHERE id_cat_edo_cumpl='{$db->escape($a_catalogo['id_cat_edo_cumpl'])}'";
		 
		$result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Estado de Cumplimiento actualizado! '".($name)."'");
			insertAccion($user['id_user'],'"'.$user['username'].'" edito el Estado de Cumplimiento '.$name.'(id:'.(int)$a_catalogo['id_cat_edo_cumpl'].').',2);
            redirect('edit_edo_cumplimiento.php?id=' . (int)$a_catalogo['id_cat_edo_cumpl'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el Estado de Cumplimiento, debido a que no hay cambios registrados en la descripción!');
            redirect('edit_edo_cumplimiento.php?id=' . (int)$a_catalogo['id_cat_edo_cumpl'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_edo_cumplimiento.php?id=' . (int)$a_catalogo['id_cat_edo_cumpl'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8'); include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Editar Escolaridad</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="edit_edo_cumplimiento.php?id=<?php echo (int)$a_catalogo['id_cat_edo_cumpl']; ?>" class="clearfix">
        <div class="form-group">
            <label for="area-name" class="control-label">Nombre del Estado de Cumplimiento</label>
            <input type="name" class="form-control" name="descripcion" value="<?php echo ucwords($a_catalogo['descripcion']); ?>">            
        
        </div>
        <div class="form-group clearfix">
            <a href="cat_edo_cumplimiento.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>