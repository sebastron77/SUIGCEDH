<?php
$page_title = 'Editar Sistemas y Herramientas Informáticos';
require_once('includes/load.php');

$user = current_user();
$id_folio = last_id_folios();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];
$inticadores_pat = find_all_pat(1);
$e_detalles = find_by_id('herramientas_sistemas', (int)$_GET['id'], 'id_herramientas_sistemas');
if ($nivel_user == 1) {
    page_require_level_exacto(1);
}

if ($nivel_user == 50) {
    page_require_level_exacto(13);
}
?>
<?php
if (isset($_POST['edit_herramientas_sistemas'])) {
	if (empty($errors)) {
	$id = (int)$e_detalles['id_herramientas_sistemas'];
    $fecha_inicio_operacion = remove_junk($db->escape($_POST['fecha_inicio_operacion']));
    $nombre_aplicativo = remove_junk($db->escape($_POST['nombre_aplicativo']));
    $descripcion_aplicativo = remove_junk($db->escape($_POST['descripcion_aplicativo']));
    $status = remove_junk($db->escape($_POST['status']));
    $id_indicadores_pat = remove_junk($db->escape($_POST['id_indicadores_pat']));
	       
        $sql = "UPDATE herramientas_sistemas SET 
				fecha_inicio_operacion='{$fecha_inicio_operacion}', 
				nombre_aplicativo='{$nombre_aplicativo}', 
				descripcion_aplicativo='{$descripcion_aplicativo}', 
				status='{$status}', 
				id_indicadores_pat='{$id_indicadores_pat}'
                WHERE id_herramientas_sistemas='{$db->escape($id)}'";


        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó la Sistemas y Herramientas Informáticos del área de sistemas de Folio: -' . $e_detalles['folio'], 2);
            $session->msg('s', " La  Acción Relevante del área de sistemas  ha sido actualizada con éxito.");
            redirect('herramientas_sistemas.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron cambios a la información.'.$sql);
            redirect('edit_herramientas_sistemas.php?id=' . (int)$e_detalles['id_herramientas_sistemas'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('herramientas_sistemas.php', false);
    }

}
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar SISTEMAS Y HERRAMIENTAS INFORMÁTICOS <?php echo $e_detalles['folio']; ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_herramientas_sistemas.php?id=<?php echo (int)$e_detalles['id_herramientas_sistemas']; ?>" enctype="multipart/form-data">
                <div class="row">
				
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_inicio_operacion">Fecha de Inicio de Operaciones</label>
                            <input type="date" class="form-control" name="fecha_inicio_operacion" value="<?php echo ucwords($e_detalles['fecha_inicio_operacion']); ?>" required>
                        </div>
                    </div>
					
					   <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_aplicativo">Nombre Sistema</label>
                            <input type="text" class="form-control" name="nombre_aplicativo" value="<?php echo ucwords($e_detalles['nombre_aplicativo']); ?>" required>
                        </div>
                    </div>
					
					 <div class="col-md-5">
                        <div class="form-group">
                            <label for="descripcion_aplicativo">Concepto y Utilidad</label>
                            <textarea class="form-control" name="descripcion_aplicativo" cols="10" rows="3"><?php echo ucwords($e_detalles['descripcion_aplicativo']); ?></textarea>
                        </div>
                    </div>
					
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Estado que se encuentra</label>
                            <select class="form-control form-select" name="status" required>
                                <option value="">Escoge una opción</option>                                
                                    <option <?php if ($e_detalles['status'] == 'Vigente') echo 'selected="selected"'; ?> value="Vigente">Vigente</option>
                                    <option <?php if ($e_detalles['status'] == 'Histório') echo 'selected="selected"'; ?> value="Histório">Histório</option>
                            </select>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" required>
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  <?php if ($e_detalles['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                </div>

        <div class="form-group clearfix">
            <a href="herramientas_sistemas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="edit_herramientas_sistemas" class="btn btn-info">Guardar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>