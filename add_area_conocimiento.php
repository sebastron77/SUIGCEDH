<?php

$page_title = 'Agregar Área del Conocimiento';
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
if (isset($_POST['add'])) {

    $req_fields = array('descripcion');
    validate_fields($req_fields);
    if (empty($errors)) {
        $name = remove_junk($db->escape(($_POST['descripcion'])));
        $estatus = remove_junk($db->escape($_POST['estatus']));

        $query  = "INSERT INTO cat_area_conocimiento (";
        $query .= "descripcion, estatus";
        $query .= ") VALUES (";
        $query .= " '{$name}', '{$estatus}'";
        $query .= ")";
        if ($db->query($query)) {
            //sucess
            $session->msg('s', "Comunidad Indígena creada! ");
			insertAccion($user['id_user'],'"'.$user['username'].'" creo el área del conocimiento -'.$name.'-.',1);
            redirect('add_area_conocimiento.php', false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se pudo dar de alta el área del conocimiento!');
            redirect('add_area_conocimiento.php', false);
        }
		        
    } else {
        $session->msg("d", $errors);
        redirect('add_area_conocimiento.php', false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8'); include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Agregar Área del Conocimiento</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="add_area_conocimiento.php" class="clearfix">
        <div class="form-group">
            <label for="area-name" class="control-label">Nombre del Área de Conocimiento</label>
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
            <a href="cat_area_conocimiento.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="add" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>