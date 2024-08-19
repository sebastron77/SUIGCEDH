<?php
$page_title = 'Editar Estado Procesal';
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
$a_catalogo = find_by_id('cat_est_procesal', (int)$_GET['id'], 'id_cat_est_procesal');
if (!$a_catalogo) {
    $session->msg("d", "El Estado Procesal no existe, verifique el ID.");
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

        $query  = "UPDATE cat_est_procesal SET ";
        $query .= "descripcion='{$name}' ";
        $query .= "WHERE id_cat_est_procesal='{$db->escape($a_catalogo['id_cat_est_procesal'])}'";
		 
		$result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Estado Procesal actualizado! '".($name)."'");
			insertAccion($user['id_user'],'"'.$user['username'].'" edito el Estado Procesal '.$name.'(id:'.(int)$a_catalogo['id_cat_est_procesal'].').',2);
            redirect('edit_edo_procesal.php?id=' . (int)$a_catalogo['id_cat_est_procesal'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el Estado Procesal, debido a que no hay cambios registrados en la descripción!');
            redirect('edit_edo_procesal.php?id=' . (int)$a_catalogo['id_cat_est_procesal'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_edo_procesal.php?id=' . (int)$a_catalogo['id_cat_est_procesal'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8'); include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Editar Escolaridad</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="edit_edo_procesal.php?id=<?php echo (int)$a_catalogo['id_cat_est_procesal']; ?>" class="clearfix">
        <div class="form-group">
            <label for="area-name" class="control-label">Nombre del Estado Procesal</label>
            <input type="name" class="form-control" name="descripcion" value="<?php echo ucwords($a_catalogo['descripcion']); ?>">            
        
        </div>
        <div class="form-group clearfix">
            <a href="cat_edo_procesal.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>