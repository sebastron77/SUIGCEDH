

<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Correspondencia Generada por Presidencia';
require_once('includes/load.php');
$user = current_user();

$nivel = $user['user_level'];
$id_user = $user['id_user'];
$areas = find_all('area');

$tipo_mail = isset($_GET['t']) ? $_GET['t'] : '0';
if($tipo_mail > 0){
	if($tipo_mail == 1){//interna
		$e_correspondencia = find_by_id_env_correspondencia((int)$_GET['id']);
		$id_correspondencia = $e_correspondencia['id_env_corresp'];
	} else if($tipo_mail == 2){//externa
		$e_correspondencia = find_by_id_env_correspondenciaExter((int)$_GET['id']);
		$id_correspondencia = $e_correspondencia['id_correspondencia_externa'];
	}
}
?>

<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_mail_emitida_presidencia'])) {
	
    if (empty($errors)) {
			
		if((int)$tipo_mail ==1 ){///oficio interno
				$fecha_emision   = remove_junk($db->escape($_POST['fecha_emision']));
				$no_oficio   = remove_junk($db->escape($_POST['no_oficio']));
				$asunto   = remove_junk(($db->escape($_POST['asunto'])));
				$medio_envio   = remove_junk(($db->escape($_POST['medio_envio'])));
				$se_turna_a_area   = remove_junk(($db->escape($_POST['se_turna_a_area'])));
				$fecha_en_que_se_turna   = remove_junk(($db->escape($_POST['fecha_en_que_se_turna'])));
				$fecha_espera_respuesta   = remove_junk(($db->escape($_POST['fecha_espera_respuesta'])));
				$tipo_tramite   = remove_junk(($db->escape($_POST['tipo_tramite'])));
				$observaciones   = remove_junk(($db->escape($_POST['observaciones'])));

				$folio_editar = $e_correspondencia['folio'];
				$resultado = str_replace("/", "-", $folio_editar);
				$carpeta = 'uploads/correspondencia_interna/' . $resultado;

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
				if ($name != '') {
					$sql = "UPDATE envio_correspondencia SET fecha_emision='{$fecha_emision}',asunto='{$asunto}',medio_envio='{$medio_envio}',se_turna_a_area='{$se_turna_a_area}',
					fecha_en_que_se_turna='{$fecha_en_que_se_turna}',fecha_espera_respuesta='{$fecha_espera_respuesta}',no_oficio='{$no_oficio}', 
					tipo_tramite='{$tipo_tramite}',oficio_enviado='{$name}',observaciones='{$observaciones}' WHERE id_env_corresp='{$db->escape($id)}'";
				}
				if ($name == '') {
					$sql = "UPDATE envio_correspondencia SET fecha_emision='{$fecha_emision}',asunto='{$asunto}',medio_envio='{$medio_envio}',se_turna_a_area='{$se_turna_a_area}',
					fecha_en_que_se_turna='{$fecha_en_que_se_turna}',fecha_espera_respuesta='{$fecha_espera_respuesta}',no_oficio='{$no_oficio}', 
					tipo_tramite='{$tipo_tramite}',observaciones='{$observaciones}' WHERE id_env_corresp='{$id_correspondencia}'";
				}
				$result = $db->query($sql);
				if ($result && $db->affected_rows() === 1) {
					//sucess
					$session->msg('s', " La correspondencia ha sido editada con éxito.");
					redirect('correspondencia_emitida_presidencia.php?a=2', false);
				} else {
					//failed
					$session->msg('d', ' No se pudo editar la correspondencia.');
					redirect('correspondencia_emitida_presidencia.php?a=2', false);
				}
			
		} else if((int)$tipo_mail ==2 ){///oficio externo
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
				$sql .=" WHERE id_correspondencia_externa='{$id_correspondencia}'";
			   echo $sql;
				$result = $db->query($sql);
				if ($result && $db->affected_rows() === 1) {
					//sucess
					$session->msg('s', " La correspondencia ha sido editada con éxito.");
					insertAccion($user['id_user'],'"'.$user['username'].'" editó la Correspondencia Externa Enviada. Folio:'.$folio_editar,2);
					redirect('correspondencia_emitida_presidencia.php?a=2', false);
				} else {
					//failed
					$session->msg('d', ' No se pudo editar la correspondencia.');
					redirect('correspondencia_emitida_presidencia.php?a=2', false);
				}
		}
       
    } else {
        $session->msg("d", $errors);
        redirect('add_mail_emitida_presidencia.phpa?a=2&t='.$tipo_mail, false);
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
                <span>Editar Correspondencia</span>

            </strong>
        </div>
		
        <div class="panel-body">
            <form method="post" action="edit_mail_emitida_presidencia.php?id=<?php echo $id_correspondencia?>&t=<?php echo $tipo_mail?>" enctype="multipart/form-data">                
				<div class="row">
				<div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_mail">Tipo Correspondencia</label>
                            <select class="form-control" name="tipo_mail" id="tipo_mail" disabled>
                                <option value="">Escoge una opción</option>
                                <option value="1" <?php if ('1' === $tipo_mail) echo 'selected="selected"'; ?>>Interna</option>
                                <option value="2" <?php if ('2' === $tipo_mail) echo 'selected="selected"'; ?>>Externa</option>
                            </select>
                        </div>
                    </div>
<?php if($tipo_mail == 1){//interna?>  
				<div class="col-md-3">
                        <div class="form-group">
                            <label for="asunto">No. Oficio</label>
                            <input type="text" class="form-control" name="no_oficio" value="<?php echo remove_junk($e_correspondencia['no_oficio']); ?>"  required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_emision">Fecha de Emisión</label>
                            <input type="date" class="form-control" name="fecha_emision" value="<?php echo remove_junk($e_correspondencia['fecha_emision']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="asunto">Asunto</label>
                            <input type="text" class="form-control" name="asunto" value="<?php echo remove_junk($e_correspondencia['asunto']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="medio_envio">Medio de Envío</label>
                            <select class="form-control" name="medio_envio">
                                <option <?php if ($e_correspondencia['medio_envio'] === 'Correo') echo 'selected="selected"'; ?> value="Correo">Correo</option>
                                <option <?php if ($e_correspondencia['medio_envio'] === 'Mediante Oficio') echo 'selected="selected"'; ?> value="Mediante Oficio">Mediante Oficio</option>
                                <option <?php if ($e_correspondencia['medio_envio'] === 'Oficialia de partes') echo 'selected="selected"'; ?> value="Oficialia de partes">Oficialia de partes</option>
                                <option <?php if ($e_correspondencia['medio_envio'] === 'Paquetería') echo 'selected="selected"'; ?> value="Paquetería">Paquetería</option>
                                <option <?php if ($e_correspondencia['medio_envio'] === 'Fax') echo 'selected="selected"'; ?> value="Fax">Fax</option>
                                <option <?php if ($e_correspondencia['medio_envio'] === 'WhatsApp') echo 'selected="selected"'; ?> value="WhatsApp">WhatsApp</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="se_turna_a_area">Se turna a Área</label>
                            <select class="form-control" name="se_turna_a_area">
                                <?php foreach ($areas as $area) : ?>
                                    <option <?php if ($area['id_area'] === $e_correspondencia['se_turna_a_area']) echo 'selected="selected"'; ?> value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
               
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_en_que_se_turna">Fecha en que se turna oficio</label>
                            <input type="date" class="form-control" value="<?php echo remove_junk($e_correspondencia['fecha_en_que_se_turna']); ?>" name="fecha_en_que_se_turna" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_espera_respuesta">Fecha en que se espera respuesta</label>
                            <input type="date" class="form-control" value="<?php echo remove_junk($e_correspondencia['fecha_espera_respuesta']); ?>" name="fecha_espera_respuesta" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_tramite">Tipo de Trámite</label><br>
                            <select class="form-control" name="tipo_tramite">
                                <option <?php if ($e_correspondencia['tipo_tramite'] === 'Respuesta') echo 'selected="selected"'; ?> value="Respuesta">Respuesta</option>
                                <option <?php if ($e_correspondencia['tipo_tramite'] === 'Conocimiento') echo 'selected="selected"'; ?> value="Conocimiento">Conocimiento</option>
                                <option <?php if ($e_correspondencia['tipo_tramite'] === 'Circular') echo 'selected="selected"'; ?> value="Circular">Circular</option>
                                <option <?php if ($e_correspondencia['tipo_tramite'] === 'Invitación') echo 'selected="selected"'; ?> value="Invitación">Invitación</option>
                                <option <?php if ($e_correspondencia['tipo_tramite'] === 'Solicitud') echo 'selected="selected"'; ?> value="Circular">Solicitud</option>
                                <option <?php if ($e_correspondencia['tipo_tramite'] === 'Trámite de Queja') echo 'selected="selected"'; ?> value="Circular">Trámite de Queja</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="oficio_enviado">Oficio enviado</label>
                            <input type="file" accept="application/pdf" class="form-control" name="oficio_enviado" value="uploads/envio_correspondencia/<?php echo $e_correspondencia['oficio_enviado']; ?>" id="oficio_enviado">
                            <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_correspondencia['oficio_enviado']); ?><?php ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" value="<?php echo remove_junk($e_correspondencia['observaciones']); ?>" name="observaciones" id="observaciones" cols="10" rows="5"><?php echo remove_junk($e_correspondencia['observaciones']); ?></textarea>
                        </div>
                    </div>
                </div>              
<?php } else if($tipo_mail == 2){//externa?>  
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
<?php }?>                

                
                <div class="form-group clearfix">
                    <a href="correspondencia_emitida_presidencia.php?a=2" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_mail_emitida_presidencia" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>