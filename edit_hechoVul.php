<?php
$page_title = 'Editar Hecho Vulnerado';
require_once('includes/load.php');
$user = current_user();
page_require_level(1);
?>
<?php
$a_catalogo = find_by_id('cat_hecho_vuln', (int)$_GET['id'], 'id_cat_hecho_vuln');
if (!$a_catalogo) {
    $session->msg("d", "El Hecho Vulnerado no existe, verifique el ID.");
    redirect('cat_hecho_vulnerado.php');
}
?>
<?php
if (isset($_POST['update'])) {

    $req_fields = array('descripcion');
    validate_fields($req_fields);
    if (empty($errors)) {
        $name = remove_junk($db->escape(($_POST['descripcion'])));
		
        $estatus = $_POST['estatus'];

        $query  = "UPDATE cat_hecho_vuln SET ";
        $query .= "descripcion='{$name}' ";
        $query .= "WHERE id_cat_hecho_vuln='{$db->escape($a_catalogo['id_cat_hecho_vuln'])}'";
		 
		$result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Hecho Vulnerado actualizado! '".($name)."'");
			insertAccion($user['id_user'],'"'.$user['username'].'" edito el Hecho Vulnerado '.$name.'(id:'.(int)$a_catalogo['id_cat_hecho_vuln'].').',2);
            redirect('edit_hechoVul.php?id=' . (int)$a_catalogo['id_cat_hecho_vuln'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el Hecho Vulnerado!');
            redirect('edit_hechoVul.php?id=' . (int)$a_catalogo['id_cat_hecho_vuln'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_hechoVul.php?id=' . (int)$a_catalogo['id_cat_hecho_vuln'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8'); include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Editar Hecho Vulnerado</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="edit_hechoVul.php?id=<?php echo (int)$a_catalogo['id_cat_hecho_vuln']; ?>" class="clearfix">
        <div class="form-group">
            <label for="area-name" class="control-label">Nombre del Hecho Vulnerado</label>
            <input type="name" class="form-control" name="descripcion" value="<?php echo ucwords($a_catalogo['descripcion']); ?>">            
        
        </div>
        <div class="form-group clearfix">
            <a href="cat_hecho_vulnerado.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>