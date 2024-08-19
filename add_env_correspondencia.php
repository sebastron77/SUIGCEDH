
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">

<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Correspondencia Interna';
require_once('includes/load.php');
$user = current_user();
$detalle = $user['id_user'];
$id_ori_canal = last_id_oricanal();
$id_folio = last_id_folios_general();
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$areas = find_all('area');
/*$area_user = area_usuario2($id_user);
$area = $area_user['id_area'];*/

$area_ingreso = isset($_GET['a']) ? $_GET['a'] : '0';
?>

<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_env_correspondencia'])) {

    $req_fields = array('no_oficio','fecha_emision', 'asunto', 'medio_envio');
    validate_fields($req_fields);

    if (empty($errors)) {
        $fecha_emision   = remove_junk($db->escape($_POST['fecha_emision']));
        $no_oficio   = remove_junk($db->escape($_POST['no_oficio']));
        $asunto   = remove_junk($db->escape($_POST['asunto']));
        $medio_envio   = remove_junk($db->escape($_POST['medio_envio']));
        $se_turna_a_area   = remove_junk($db->escape($_POST['se_turna_a_area']));
        $fecha_en_que_se_turna   = remove_junk($db->escape($_POST['fecha_en_que_se_turna']));
        $fecha_espera_respuesta   = remove_junk($db->escape($_POST['fecha_espera_respuesta']));
        $tipo_tramite   = remove_junk($db->escape($_POST['tipo_tramite']));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));
        date_default_timezone_set('America/Mexico_City');
        $creacion = date('Y-m-d');

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                //$nuevo_id_folio = (int)$nuevo['id'] + 1;
                //$no_folio1 = sprintf('%04d', (int)$nuevo['id'] + 1);
                $nuevo_id_folio = (int) $nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int) $nuevo['contador'] + 1);
            }
        }

        //Se crea el número de folio
        $year = date("Y");
        // Se crea el folio orientacion
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-ECOR';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-ECOR';
        $carpeta = 'uploads/correspondencia_interna/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['oficio_enviado']['name'];
        $size = $_FILES['oficio_enviado']['size'];
        $type = $_FILES['oficio_enviado']['type'];
        $temp = $_FILES['oficio_enviado']['tmp_name'];

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
		/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}

		
		$query = "INSERT INTO envio_correspondencia (";
		$query .= "folio,no_oficio,fecha_emision,asunto,medio_envio,se_turna_a_area,tipo_tramite,observaciones,area_creacion,fecha_creacion,user_creador,fecha_en_que_se_turna,fecha_espera_respuesta";
		if ($name != ''){
			$query .= ", oficio_enviado ";
		}	
		$query .= ") VALUES (";
		$query .= " '{$folio}','{$no_oficio}','{$fecha_emision}','{$asunto}','{$medio_envio}','{$se_turna_a_area}','{$tipo_tramite}','{$observaciones}','{$area_ingreso}','{$creacion}','{$id_user}','{$fecha_en_que_se_turna}','{$fecha_espera_respuesta}'";
		if ($name != ''){
			$query .= ", '{$name}' ";
		}
		$query .= ")";

		$query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";

        if ($db->query($query) && $db->query($query2)) {
            //sucess
            $session->msg('s', " La correspondencia interna ha sido agregada con éxito.");
			insertAccion($user['id_user'],'"'.$user['username'].'" agrego la Correspondencia Interna Enviada. Folio:'.$folio,1);
            redirect('env_correspondencia.php?a='.$area_ingreso, false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la correspondencia interna.');
            redirect('add_env_correspondencia.php?a={$area_ingreso}', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_env_correspondencia.phpa?a={$area_ingreso}', false);
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
                <span>Agregar correspondencia interna</span>

            </strong>
        </div>
		
        <div class="panel-body">
            <form method="post" action="add_env_correspondencia.php?a=<?php echo $area_ingreso?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="asunto">No. Oficio</label>
                            <input type="text" class="form-control" name="no_oficio" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_emision">Fecha de Emisión de Oficio                                
                            </label>
                            <input type="date" class="form-control" name="fecha_emision" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="asunto">Asunto</label>
                            <input type="text" class="form-control" name="asunto" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="medio_envio">Medio de Envío</label>
                            <select class="form-control" name="medio_envio" required>
                                <option value="">Escoge una opción</option>
                                <option value="Correo">Correo</option>
                                <option value="Mediante Oficio">Mediante Oficio</option>
                                <option value="Paquetería">Paquetería</option>
                                <option value="WhatsApp">WhatsApp</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="se_turna_a_area">Área a la que se turna</label>
                            <select class="form-control" name="se_turna_a_area">
                                <?php foreach ($areas as $area) : ?>
                                    <option value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_en_que_se_turna">Fecha en que destinatario recibió el oficio</label>
                            <input type="date" class="form-control" name="fecha_en_que_se_turna" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_espera_respuesta">Fecha en que se espera respuesta</label>
                            <input type="date" class="form-control" name="fecha_espera_respuesta" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_tramite">Tipo de Trámite</label><br>
                            <select class="form-control" name="tipo_tramite" required>
                                <option value="">Escoge una opción</option>
                                <option value="Respuesta">Respuesta</option>
                                <option value="Conocimiento">Conocimiento</option>
                                <option value="Circular">Circular</option>
								<option value="Invitación">Invitación</option>
                                <option value="Solicitud">Solicitud</option>
                                <option value="Trámite de Queja">Trámite de Queja</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="oficio_enviado">Adjuntar Oficio en Digital</label>
                            <input type="file" accept="application/pdf" class="form-control" name="oficio_enviado" id="oficio_enviado" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="env_correspondencia.php?a=<?php echo $area_ingreso?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_env_correspondencia" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>