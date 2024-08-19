<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Correspondencia Externa';
require_once('includes/load.php');
$user = current_user();
$detalle = $user['id_user'];
$e_correspondencia = find_by_id_env_correspondenciaExter((int)$_GET['id']);

$user = current_user();
$id_user = $user['id_user'];
?>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['edit_env_correspondencia'])) {

    $req_fields = array('num_oficio','fecha_oficio', 'asunto', 'medio_entrega');
    validate_fields($req_fields);

    if (empty($errors)) {
        $id = (int)$e_correspondencia['id_correspondencia_externa'];
        $fecha_oficio   = remove_junk($db->escape($_POST['fecha_oficio']));
        $num_oficio   = remove_junk($db->escape($_POST['num_oficio']));
        $asunto   = remove_junk($db->escape($_POST['asunto']));
        $nombre_destinatario   = remove_junk($db->escape($_POST['nombre_destinatario']));
        $nombre_institucion   = remove_junk($db->escape($_POST['nombre_institucion']));
        $cargo_destinatario   = remove_junk($db->escape($_POST['cargo_destinatario']));
        $medio_entrega   = remove_junk($db->escape($_POST['medio_entrega']));
        $tipo_accion   = remove_junk($db->escape($_POST['tipo_accion']));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));

        $folio_editar = $e_correspondencia['folio'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/correspondencia_externa/' . $resultado;

        $name = $_FILES['oficio_enviado']['name'];
        $size = $_FILES['oficio_enviado']['size'];
        $type = $_FILES['oficio_enviado']['type'];
        $temp = $_FILES['oficio_enviado']['tmp_name'];

        if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else {
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }
		
		$sql = "UPDATE correspondencia_externa SET num_oficio='{$num_oficio}',fecha_oficio='{$fecha_oficio}',nombre_destinatario='{$nombre_destinatario}',nombre_institucion='{$nombre_institucion}',
		cargo_destinatario='{$cargo_destinatario}',asunto='{$asunto}',medio_entrega='{$medio_entrega}', 
		tipo_accion='{$tipo_accion}', observaciones='{$observaciones}' ";
        if ($name != '') {
		$sql .=" ,oficio_enviado='{$name}' ";
        }
		$sql .=" WHERE id_correspondencia_externa='{$db->escape($id)}'";
       echo $sql;
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', " La correspondencia ha sido editada con éxito.");
			insertAccion($user['id_user'],'"'.$user['username'].'" editó la Correspondencia Externa Enviada. Folio:'.$folio_editar,2);
            redirect('env_correspondencia_externa.php?a='.$e_correspondencia['id_area_creacion'], false);
        } else {
            //failed
            $session->msg('d', ' No se pudo editar la correspondencia.');
            redirect('edit_env_correspondencia_externa.php?id=' . (int)$e_correspondencia['id_correspondencia_externa'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_env_correspondencia_externa.php?id=' . (int)$e_correspondencia['id_correspondencia_externa'], false);
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
                <span>Editar correspondencia <?php echo $e_correspondencia['folio']?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_env_correspondencia_externa.php?id=<?php echo (int)$e_correspondencia['id_correspondencia_externa']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="num_oficio">No. Oficio</label>
                            <input type="text" class="form-control" name="num_oficio" value="<?php echo $e_correspondencia['num_oficio']?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_oficio">Fecha de Emisión de Oficio                                
                            </label>
                            <input type="date" class="form-control" name="fecha_oficio" value="<?php echo $e_correspondencia['fecha_oficio']?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="asunto">Asunto</label>
                            <input type="text" class="form-control" name="asunto" value="<?php echo $e_correspondencia['asunto']?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="medio_entrega">Medio de Envío</label>
                            <select class="form-control" name="medio_entrega" required>
                                <option value="">Escoge una opción</option>
                                <option <?php if ($e_correspondencia['medio_entrega'] === 'Correo') echo 'selected="selected"'; ?> value="Correo">Correo</option>
                                <option <?php if ($e_correspondencia['medio_entrega'] === 'Mediante Oficio') echo 'selected="selected"'; ?> value="Mediante Oficio">Mediante Oficio</option>
                                <option <?php if ($e_correspondencia['medio_entrega'] === 'Paquetería') echo 'selected="selected"'; ?> value="Paquetería">Paquetería</option>
                                <option <?php if ($e_correspondencia['medio_entrega'] === 'WhatsApp') echo 'selected="selected"'; ?> value="WhatsApp">WhatsApp</option>
                            </select>
                        </div>
                    </div>
					 <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_accion">Tipo de Trámite</label><br>
                            <select class="form-control" name="tipo_accion" required>
                                <option value="">Escoge una opción</option>
								<option <?php if ($e_correspondencia['tipo_accion'] === 'Respuesta') echo 'selected="selected"'; ?> value="Respuesta">Respuesta</option>
                                <option <?php if ($e_correspondencia['tipo_accion'] === 'Conocimiento') echo 'selected="selected"'; ?> value="Conocimiento">Conocimiento</option>
                                <option <?php if ($e_correspondencia['tipo_accion'] === 'Circular') echo 'selected="selected"'; ?> value="Circular">Circular</option>
                                <option <?php if ($e_correspondencia['tipo_accion'] === 'Invitación') echo 'selected="selected"'; ?> value="Invitación">Invitación</option>
                                <option <?php if ($e_correspondencia['tipo_accion'] === 'Solicitud') echo 'selected="selected"'; ?> value="Circular">Solicitud</option>
                                <option <?php if ($e_correspondencia['tipo_accion'] === 'Trámite de Queja') echo 'selected="selected"'; ?> value="Circular">Trámite de Queja</option>
                            </select>
                        </div>
                    </div>
                </div>
				 <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="oficio_enviado">Adjuntar Oficio en Digital</label>
                            <input type="file" accept="application/pdf" class="form-control" name="oficio_enviado" id="oficio_enviado" >
							<label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_correspondencia['oficio_enviado']); ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="5"><?php echo $e_correspondencia['observaciones']?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
				 <div class="panel-heading">
						<strong>
							<span class="glyphicon glyphicon-th"></span>
							<span> Datos Destinatario</span>
						</strong>
					</div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_institucion">Nombre de la Institución</label>
                           <input type="text" class="form-control" name="nombre_institucion" value="<?php echo $e_correspondencia['nombre_institucion']?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_destinatario">Nombre del Destinatario</label>
                            <input type="text" class="form-control" name="nombre_destinatario" value="<?php echo $e_correspondencia['nombre_destinatario']?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cargo_destinatario">Cargo del Destinatario</label>
                            <input type="text" class="form-control" name="cargo_destinatario" value="<?php echo $e_correspondencia['cargo_destinatario']?>" required>
                        </div>
                    </div>
                   
                </div>
                <div class="form-group clearfix">
                    <a href="env_correspondencia_externa.php?a=<?php echo $e_correspondencia['id_area_creacion']?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_env_correspondencia" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>