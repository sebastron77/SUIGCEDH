<?php
$page_title = 'Agregar Hecho Vulnerado';
require_once('includes/load.php');
$user = current_user();
page_require_level(1);
?>

<?php
if (isset($_POST['add'])) {

    $req_fields = array('descripcion');
    validate_fields($req_fields);
    if (empty($errors)) {
        $name = remove_junk($db->escape(($_POST['descripcion'])));
        $estatus = remove_junk($db->escape($_POST['estatus']));

        $query  = "INSERT INTO cat_hecho_vuln (";
        $query .= "descripcion, estatus";
        $query .= ") VALUES (";
        $query .= " '{$name}', '{$estatus}'";
        $query .= ")";
        if ($db->query($query)) {
            //sucess
            $session->msg('s', "Hecho Vulnerado creado! ");
			insertAccion($user['id_user'],'"'.$user['username'].'" creo el Hecho Vulnerado -'.$name.'-.',1);
            redirect('add_hechoVul.php', false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se pudo dar de alta el Hecho Vulnerado!');
            redirect('add_hechoVul.php', false);
        }
		        
    } else {
        $session->msg("d", $errors);
        redirect('add_hechoVul.php', false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8'); include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Agregar Hecho Vulnerado</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="add_hechoVul.php" class="clearfix">
        <div class="form-group">
            <label for="area-name" class="control-label">Nombre del Hecho Vulnerado</label>
            <input type="name" class="form-control" name="descripcion" required>            
        
            <div class="form-group">
                <label for="estatus">Estatus</label>
                <select class="form-control" name="estatus">                    
					<option value="1">Activo</option>
					<option value="0">Inactivo</option>
            </select>
                </select>
            </div>
        <div class="form-group clearfix">
            <a href="cat_hecho_vulnerado.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="add" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>