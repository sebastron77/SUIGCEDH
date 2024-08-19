<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Correspondencia';
require_once('includes/load.php');
$user = current_user();
$detalle = $user['id_user'];
$e_correspondencia = find_by_id_env_correspondencia((int)$_GET['id']);
$id_folio = last_id_folios();
// page_require_level(2);
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$areas = find_all('area');
?>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['edit_env_correspondencia'])) {

    $req_fields = array('fecha_emision');
    validate_fields($req_fields);

    if (empty($errors)) {
        $id = (int)$e_correspondencia['id_env_corresp'];
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
			tipo_tramite='{$tipo_tramite}',observaciones='{$observaciones}' WHERE id_env_corresp='{$db->escape($id)}'";
        }
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', " La correspondencia ha sido editada con éxito.");
            redirect('env_correspondencia.php?a='.$e_correspondencia['area_creacion'], false);
        } else {
            //failed
            $session->msg('d', ' No se pudo editar la correspondencia.');
            redirect('edit_env_correspondencia.php?id=' . (int)$e_correspondencia['id_env_corresp'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_env_correspondencia.php?id=' . (int)$e_correspondencia['id_env_corresp'], false);
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
            <form method="post" action="edit_env_correspondencia.php?id=<?php echo (int)$e_correspondencia['id_env_corresp']; ?>" enctype="multipart/form-data">
                <div class="row">
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
                </div>
                <div class="row">
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
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" value="<?php echo remove_junk($e_correspondencia['observaciones']); ?>" name="observaciones" id="observaciones" cols="10" rows="5"><?php echo remove_junk($e_correspondencia['observaciones']); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="env_correspondencia.php?a=<?php echo $e_correspondencia['area_creacion']?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_env_correspondencia" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>