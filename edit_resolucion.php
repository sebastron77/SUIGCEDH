<?php
$page_title = 'Editar Tipo Resolución';
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
$a_catalogo = find_by_id('cat_tipo_res', (int)$_GET['id'], 'id_cat_tipo_res');
if (!$a_catalogo) {
    $session->msg("d", "El Tipo de Resolución no existe, verifique el ID.");
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

        $query  = "UPDATE cat_tipo_res SET ";
        $query .= "descripcion='{$name}' ";
        $query .= "WHERE id_cat_tipo_res='{$db->escape($a_catalogo['id_cat_tipo_res'])}'";
		 
		$result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Tipo de Resolución sea actualizado! '".($name)."'");
			insertAccion($user['id_user'],'"'.$user['username'].'" edito el Tipo de Resolución '.$name.'(id:'.(int)$a_catalogo['id_cat_tipo_res'].').',2);
            redirect('edit_resolucion.php?id=' . (int)$a_catalogo['id_cat_tipo_res'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el área!');
            redirect('edit_resolucion.php?id=' . (int)$a_catalogo['id_cat_tipo_res'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_resolucion.php?id=' . (int)$a_catalogo['id_cat_tipo_res'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8'); include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Editar Tipo Resolución</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="edit_resolucion.php?id=<?php echo (int)$a_catalogo['id_cat_tipo_res']; ?>" class="clearfix">
        <div class="form-group">
            <label for="area-name" class="control-label">Nombre del Tipo Resolución</label>
            <input type="name" class="form-control" name="descripcion" value="<?php echo ucwords($a_catalogo['descripcion']); ?>">            
        
        </div>
        <div class="form-group clearfix">
            <a href="cat_tipo_resolucion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>