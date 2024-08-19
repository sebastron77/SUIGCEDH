
<?php
$page_title = 'Editar Evento';
require_once('includes/load.php');
?>
<?php
$e_evento = find_by_id('eventos', (int)$_GET['id'], 'id_evento');
if (!$e_evento) {
    $session->msg("d", "id de evento no encontrado.");
    redirect('eventos.php');
}
$user = current_user();
$nivel = $user['user_level'];

$id_user = $user['id_user'];
$inticadores_pat = find_all_pat_area($e_evento['area_creacion'],'eventos');
?>

<?php
if (isset($_POST['edit_evento'])) {
    $req_fields = array('nombre_evento', 'tipo_evento', 'quien_solicita', 'fecha', 'hora', 'lugar', 'no_asistentes', 'modalidad', 'depto_org', 'quien_asiste');
    validate_fields($req_fields);
    if (empty($errors)) {
        $id = (int)$e_evento['id_evento'];
        $nombre   = remove_junk($db->escape($_POST['nombre_evento']));
        $solicita   = remove_junk($db->escape($_POST['quien_solicita']));
        $tipo_evento   = remove_junk($db->escape($_POST['tipo_evento']));
        $fecha   = remove_junk($db->escape($_POST['fecha']));
        $hora   = remove_junk($db->escape($_POST['hora']));
        $lugar   = remove_junk(($db->escape($_POST['lugar'])));
        $asistentes   = remove_junk(($db->escape($_POST['no_asistentes'])));
        $modalidad   = remove_junk($db->escape($_POST['modalidad']));
        $depto   = remove_junk($db->escape($_POST['depto_org']));
        $quien_asiste   = remove_junk($db->escape($_POST['quien_asiste']));
        $invitacion   = remove_junk($db->escape($_POST['invitacion']));
        $constancia   = remove_junk($db->escape($_POST['constancia']));
		$id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));

        $folio_editar = $e_evento['folio'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/eventos/invitaciones/' . $resultado;

        $name = $_FILES['invitacion']['name'];
        $size = $_FILES['invitacion']['size'];
        $type = $_FILES['invitacion']['type'];
        $temp = $_FILES['invitacion']['tmp_name'];

        //Verificamos que exista la carpeta y si sí, guardamos el pdf
        if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else{
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }

        if ($name != '') {
            $sql = "UPDATE eventos SET nombre_evento='{$nombre}', tipo_evento='{$tipo_evento}', quien_solicita='{$solicita}', fecha='{$fecha}', hora='{$hora}', lugar='{$lugar}', 
			no_asistentes='{$asistentes}', modalidad='{$modalidad}', depto_org='{$depto}', quien_asiste='{$quien_asiste}', invitacion='{$name}', constancia='{$constancia}' ";
			if($inticadores_pat){$sql .= ",id_indicadores_pat= {$id_indicadores_pat} ";}	
			$sql .= " WHERE id_evento='{$db->escape($id)}'";
        }
        if ($name == '') {
            $sql = "UPDATE eventos SET nombre_evento='{$nombre}', tipo_evento='{$tipo_evento}', quien_solicita='{$solicita}', fecha='{$fecha}', hora='{$hora}', lugar='{$lugar}', 
			no_asistentes='{$asistentes}', modalidad='{$modalidad}', depto_org='{$depto}', quien_asiste='{$quien_asiste}', constancia='{$constancia}' ";
if($inticadores_pat){$sql .= ",id_indicadores_pat= {$id_indicadores_pat} ";}				
			$sql .= " WHERE id_evento='{$db->escape($id)}'";
        }
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Información Actualizada ");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó evento, Folio: ' . $folio_editar . '.', 2);
            redirect('eventos.php?a='.$e_evento['area_creacion'], false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos.');
            redirect('edit_evento.php?id=' . (int)$e_evento['id_evento'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_evento.php?id=' . (int)$e_evento['id_evento'], false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar capacitación <?php echo $e_evento['folio']; ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_evento.php?id=<?php echo (int)$e_evento['id_evento']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_evento">Nombre del Evento</label>
                            <input type="text" class="form-control" name="nombre_evento" value="<?php echo remove_junk($e_evento['nombre_evento']); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_evento">Tipo de evento</label>
                            <select class="form-control" name="tipo_evento">	                                
                                <option value="Foro" <?php if ($e_evento['tipo_evento'] === 'Foro') echo 'selected="selected"'; ?>>Foro</option>
								<option value="Rueda de Prensa" <?php if ($e_evento['tipo_evento'] === 'Rueda de Prensa') echo 'selected="selected"'; ?>>Rueda de Prensa</option>
                                <option value="Representación" <?php if ($e_evento['tipo_evento'] === 'Representación') echo 'selected="selected"'; ?>>Representación</option>
                                <option value="Mesa de Diálogo" <?php if ($e_evento['tipo_evento'] === 'Mesa de Diálogo') echo 'selected="selected"'; ?>>Mesa de Diálogo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quien_solicita">¿Quién lo solicita?</label>
                            <input type="text" class="form-control" name="quien_solicita" placeholder="Nombre Completo" value="<?php echo remove_junk(($e_evento['quien_solicita'])); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha</label><br>
                            <input type="date" class="form-control" name="fecha" value="<?php echo remove_junk($e_evento['fecha']); ?>">
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="hora">Hora</label><br>
                            <input type="time" class="form-control" name="hora" value="<?php echo remove_junk($e_evento['hora']); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="lugar">Lugar</label>
                            <input type="text" class="form-control" name="lugar" value="<?php echo remove_junk($e_evento['lugar']); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="no_asistentes">No. de asistentes</label>
                            <input type="number" min="1" max="1000" class="form-control" name="no_asistentes" value="<?php echo remove_junk($e_evento['no_asistentes']); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="modalidad">Modalidad</label>
                            <select class="form-control" name="modalidad">
                                <option value="Presencial" <?php if ($e_evento['modalidad'] === 'Presencial') echo 'selected="selected"'; ?>>Presencial</option>
                                <option value="En línea" <?php if ($e_evento['modalidad'] === 'En línea') echo 'selected="selected"'; ?>>En línea</option>
                                <option value="Híbrido" <?php if ($e_evento['modalidad'] === 'Híbrido') echo 'selected="selected"'; ?>>Híbrido</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="depto_org">Departamento/Organización</label>
                            <input type="text" class="form-control" name="depto_org" value="<?php echo remove_junk(($e_evento['depto_org'])); ?>">
                        </div>
                    </div>
                </div>
                <div class="row">                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quien_asiste">¿Quién asiste? (separado por comas)</label>
                            <textarea name="quien_asiste" class="form-control" id="quien_asiste" cols="30" rows="10"  value="<?php echo remove_junk(($e_evento['quien_asiste'])); ?>"><?php echo remove_junk(($e_evento['quien_asiste'])); ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="invitacion">Invitación</label>
                            <input type="file" accept="application/pdf" class="form-control" name="invitacion" id="invitacion" value="uploads/eventos/invitacion/<?php echo $e_evento['invitacion']; ?>">
                            <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_evento['invitacion']); ?><?php ?></label>
                        </div>
                    </div>
					<?php if($inticadores_pat){?>
                   <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" >
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option <?php if ($e_evento['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?>  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<?php }?>
                </div>
                <div class="form-group clearfix">
                    <a href="eventos.php?a=<?php echo $e_evento['area_creacion']?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_evento" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>