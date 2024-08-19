<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar';
require_once('includes/load.php');
$user = current_user();
$detalle = $user['id_user'];
$id_folio = last_id_folios();
$nivel_user = $user['user_level'];
$cat_ejes = find_all('cat_ejes_estrategicos');
$cat_agendas = find_all('cat_agendas');


$e_detalles = find_by_id('entregables', (int)$_GET['id'], 'id_entregables');
if (!$e_detalles) {
    $session->msg("d", "id de entregable no encontrado.");
    redirect('agenda_entregables.php');
}


if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 17) {
    page_require_level_exacto(17);
}
if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 17) :
    redirect('home.php');
endif;

if ($nivel_user > 17 && $nivel_user < 21) :
    redirect('home.php');
endif;

$inticadores_pat = find_all_pat(18);

?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_agenda_entregable'])) {
	 $req_fields = array('tipo_estregable', 'nombre_entragable', 'id_cat_ejes_estrategicos', 'id_cat_agendas');
    validate_fields($req_fields);
	if (empty($errors)) {
		$id = (int)$e_detalles['id_entregables'];
        $tipo_estregable   = remove_junk($db->escape($_POST['tipo_estregable']));
        $nombre_entragable   = remove_junk($db->escape($_POST['nombre_entragable']));
        $id_cat_ejes_estrategicos   = remove_junk($db->escape($_POST['id_cat_ejes_estrategicos']));
        $id_cat_agendas   = remove_junk($db->escape($_POST['id_cat_agendas']));
        $descripcion   = remove_junk($db->escape($_POST['descripcion']));
        $liga_acceso   = remove_junk($db->escape($_POST['liga_acceso']));
        $no_isbn   = remove_junk($db->escape($_POST['no_isbn']));
        $fecha_presentacion   = remove_junk($db->escape($_POST['fecha_presentacion']));
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));
				
		
		$query = "UPDATE entregables SET ";
		$query .= "tipo_estregable = '{$tipo_estregable}',
				   id_cat_ejes_estrategicos = '{$id_cat_ejes_estrategicos}',
				   id_cat_agendas = '{$id_cat_agendas}',
				   descripcion = '{$descripcion}',
				   liga_acceso = '{$liga_acceso}',
				   fecha_presentacion = '{$fecha_presentacion}',
				   id_indicadores_pat = '{$id_indicadores_pat}',
				   no_isbn = '{$no_isbn}' ";			
		$query .= "WHERE id_entregables = {$db->escape($id)}";	
		
		 $result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Información Actualizada ");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó el Enttregable('.$id.'), con Folio:' . $e_detalles['folio'] . '.', 2);
            redirect('agenda_entregables.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se detectaron cambios a la información.');
            redirect('edit_agenda_entregable.php?id=' . (int)$e_detalles['id_entregables'], false);
        }
		
	}else {
        $session->msg("d", ' No se pudo agregar el registros.'.$errors);
        redirect('edit_agenda_entregable.php?id=' . (int)$e_detalles['id_entregables'], false);
    }

}
?>
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar Entregable</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_agenda_entregable.php?id=<?php echo (int)$e_detalles['id_entregables']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo">Tipo Entregable</label>
                            <select class="form-control" name="tipo_estregable" required>
                                <option value="Escoge una opción">Escoge una opción</option>
                                <option value="Interno" <?php if ($e_detalles['tipo_estregable'] === 'Interno') echo 'selected="selected"'; ?>>Interno</option>
                                <option value="Externo" <?php if ($e_detalles['tipo_estregable'] === 'Externo') echo 'selected="selected"'; ?>>Externo</option>
                            </select>
                        </div>
                    </div>
						<div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_presentacion">Fecha de Publicación<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_presentacion" value="<?php echo ($e_detalles['fecha_presentacion']); ?>" required>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" name="nombre_entragable" value="<?php echo remove_junk($e_detalles['nombre_entragable']); ?>" required>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="isbn">ISBN</label>
                            <input type="text" class="form-control" name="no_isbn" value="<?php echo remove_junk($e_detalles['no_isbn']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="eje">Eje Estratégico</label>
                            <select class="form-control" name="id_cat_ejes_estrategicos" required>
                                <option value="Escoge una opción">Escoge una opción</option>
								<?php foreach ($cat_ejes as $ejes) : ?>
                                    <option <?php if ($e_detalles['id_cat_ejes_estrategicos'] === $ejes['id_cat_ejes_estrategicos']) echo 'selected="selected"'; ?> value="<?php echo $ejes['id_cat_ejes_estrategicos']; ?>"><?php echo ucwords($ejes['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="agenda">Agenda</label>
                            <select class="form-control" name="id_cat_agendas" required>
                                <option value="Escoge una opción">Escoge una opción</option>
								<?php foreach ($cat_agendas as $agendas) : ?>
                                    <option <?php if ($e_detalles['id_cat_agendas'] === $agendas['id_cat_agendas']) echo 'selected="selected"'; ?> value="<?php echo $agendas['id_cat_agendas']; ?>"><?php echo ucwords($agendas['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" >
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  <?php if ($e_detalles['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                        </div>
					
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
							<textarea class="form-control" name="descripcion" cols="10" rows="4" ><?php echo remove_junk($e_detalles['descripcion']); ?></textarea>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="acceso">Hipervínculo de Publicación </label>
							<textarea class="form-control" name="liga_acceso" cols="10" rows="4" ><?php echo remove_junk($e_detalles['liga_acceso']); ?></textarea>
                        </div>
                    </div>

                    
                </div>
                <div class="form-group clearfix">
                    <a href="agenda_entregables.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_agenda_entregable" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>