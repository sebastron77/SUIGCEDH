
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">

<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Correspondencia Generada por Presidencia';
require_once('includes/load.php');
$user = current_user();
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
if (isset($_POST['add_mail_emitida_presidencia'])) {
	$req_fields = array('tipo_mail', 'no_oficio', 'fecha_emision');
    validate_fields($req_fields);
	
    if (empty($errors)) {
		$tipo_mail = remove_junk($db->escape($_POST['tipo_mail']));
		$no_oficio   = remove_junk($db->escape($_POST['no_oficio']));
        $fecha_emision   = remove_junk($db->escape($_POST['fecha_emision']));
        $asunto   = remove_junk($db->escape($_POST['asunto']));
        $medio_envio   = remove_junk($db->escape($_POST['medio_envio']));
        $tipo_tramite   = remove_junk($db->escape($_POST['tipo_tramite']));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));
        $year = date("Y");
		
		 if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int) $nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int) $nuevo['contador'] + 1);
            }
        }

        // Se crea el folio 
		$folio = 'CEDH/' . $no_folio1 . '/' . $year . '-ECOR';
		$folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-ECOR';
		
		$name_oficio = $_FILES['oficio_enviado']['name'];
        $size_oficio = $_FILES['oficio_enviado']['size'];
        $type_oficio = $_FILES['oficio_enviado']['type'];
        $temp_oficio = $_FILES['oficio_enviado']['tmp_name'];
		$source = 'uploads/index.php';
		
		if((int)$tipo_mail ==1 ){///oficio interno
				$se_turna_a_area   = remove_junk($db->escape($_POST['se_turna_a_area']));
				$fecha_en_que_se_turna   = remove_junk($db->escape($_POST['fecha_en_que_se_turna']));
				$fecha_espera_respuesta   = remove_junk($db->escape($_POST['fecha_espera_respuesta']));
				$carpeta = 'uploads/correspondencia_interna/' . $folio_carpeta;
				 if (!is_dir($carpeta)) {
					mkdir($carpeta, 0777, true);
				}
				$move =  move_uploaded_file($temp_oficio, $carpeta . "/" . $name_oficio);
				copy($source, $carpeta.'/index.php') ;
								
				$query = "INSERT INTO envio_correspondencia (";
				$query .= "folio,no_oficio,fecha_emision,asunto,medio_envio,se_turna_a_area,tipo_tramite,observaciones,area_creacion,fecha_creacion,user_creador,fecha_en_que_se_turna";
				if ($name_oficio != ''){
					$query .= ", oficio_enviado ";
				}	
				if ($fecha_espera_respuesta != ''){
					$query .= ", fecha_espera_respuesta ";
				}	
				$query .= ") VALUES (";
				$query .= " '{$folio}','{$no_oficio}','{$fecha_emision}','{$asunto}','{$medio_envio}','{$se_turna_a_area}','{$tipo_tramite}','{$observaciones}',2,NOW(),'{$id_user}','{$fecha_en_que_se_turna}'";
				if ($name_oficio != ''){
					$query .= ", '{$name_oficio}' ";
				}
				if ($fecha_espera_respuesta != ''){
					$query .= ",'{$fecha_espera_respuesta}' ";
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
					redirect('correspondencia_emitida_presidencia.php?a=2', false);
				} else {
					//failed
					$session->msg('d', ' No se pudo agregar la correspondencia interna.');
					redirect('add_mail_emitida_presidencia.php?a=2', false);
				}
			
		} else if((int)$tipo_mail ==2 ){///oficio externo
				$nombre_destinatario   = remove_junk($db->escape($_POST['nombre_destinatario']));
				$nombre_institucion   = remove_junk($db->escape($_POST['nombre_institucion']));
				$cargo_destinatario   = remove_junk($db->escape($_POST['cargo_destinatario']));
				$carpeta = 'uploads/correspondencia_externa/' . $folio_carpeta;
				$move =  move_uploaded_file($temp_oficio, $carpeta . "/" . $name_oficio);
				 if (!is_dir($carpeta)) {
					mkdir($carpeta, 0777, true);
				}
				copy($source, $carpeta.'/index.php') ;
				
				$query = "INSERT INTO correspondencia_externa (";
				$query .= "folio,num_oficio,fecha_oficio,asunto,medio_entrega,tipo_accion,nombre_institucion,cargo_destinatario,nombre_destinatario,observaciones,id_area_creacion,fecha_creacion,id_user_creador";
				if ($name_oficio != ''){
					$query .= ", oficio_enviado ";
				}				
				$query .= ") VALUES (";
				$query .= " '{$folio}','{$no_oficio}','{$fecha_emision}','{$asunto}','{$medio_envio}','{$tipo_tramite}','{$nombre_institucion}','{$cargo_destinatario}','{$nombre_destinatario}','{$observaciones}',2,NOW() ,'{$id_user}'";
				if ($name_oficio != ''){
					$query .= ", '{$name_oficio}' ";
				}
				$query .= ")";
		
				$query2 = "INSERT INTO folios (";
					$query2 .= "folio, contador";
					$query2 .= ") VALUES (";
					$query2 .= " '{$folio}','{$no_folio1}'";
					$query2 .= ")";

				if ($db->query($query) && $db->query($query2)) {
					//sucess
					$session->msg('s', " La correspondencia externa enviada ha sido agregada con éxito.");
					insertAccion($user['id_user'],'"'.$user['username'].'" agrego la Correspondencia Externa Enviada. Folio:'.$folio,1);
					redirect('correspondencia_emitida_presidencia.php?a=2', false);
				} else {
					//failed
					$session->msg('d', ' No se pudo agregar la correspondencia interna.');
					redirect('add_mail_emitida_presidencia.php?a=2', false);
				}
		}
       
    } else {
        $session->msg("d", $errors);
        redirect('add_mail_emitida_presidencia.phpa?a=2', false);
    }
}
?>
<script>

function showMe(id) {
var name_div2="externo";
var name_div1="interno";
	
	
if(id==1){	
	$("#"+name_div1).show();
	$("#"+name_div2).hide();
	
		document.querySelector('#se_turna_a_area').required = true;
		document.querySelector('#fecha_en_que_se_turna').required = true;
		
		document.querySelector('#nombre_institucion').required = false;
		document.querySelector('#nombre_destinatario').required = false;
	}else{		 
		//alert("Colectivo");
		$("#"+name_div2).show();
		$("#"+name_div1).hide();
		
		document.querySelector('#nombre_institucion').required = true;
		document.querySelector('#nombre_destinatario').required = true;
	
		document.querySelector('#se_turna_a_area').required = false;
		document.querySelector('#fecha_en_que_se_turna').required = false;
	}
       
}
</script>	
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Correspondencia</span>

            </strong>
        </div>
		
        <div class="panel-body">
            <form method="post" action="add_mail_emitida_presidencia.php?a=2" enctype="multipart/form-data">
                <div class="row">
				<div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_mail">Tipo Correspondencia</label>
                            <select class="form-control" name="tipo_mail" id="tipo_mail" required onchange="showMe(this.value);">
                                <option value="">Escoge una opción</option>
                                <option value="1">Interna</option>
                                <option value="2">Externa</option>
                            </select>
                        </div>
                    </div>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="asunto">Asunto</label>
                            <input type="text" class="form-control" name="asunto" required>
                        </div>
                    </div>
					 </div>
                <div class="row">
                    <div class="col-md-2">
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
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="oficio_enviado">Adjuntar Oficio en Digital</label>
                            <input type="file" accept="application/pdf" class="form-control" name="oficio_enviado" id="oficio_enviado" required>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="5"></textarea>
                        </div>
                    </div>
                </div>
				
                <div id="externo" style="display: none;">
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
							   <input type="text" class="form-control" name="nombre_institucion" id="nombre_institucion" >
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="nombre_destinatario">Nombre del Destinatario</label>
								<input type="text" class="form-control" name="nombre_destinatario"  id="nombre_destinatario" >
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="cargo_destinatario">Cargo del Destinatario</label>
								<input type="text" class="form-control" name="cargo_destinatario"  id="cargo_destinatario" >
							</div>
						</div>
					   
					</div>
                </div>
				
				
				<div id="interno" style="display: none;">
				
					<div class="row">
					<div class="panel-heading">
							<strong>
								<span class="glyphicon glyphicon-th"></span>
								<span> Datos Área Interna</span>
							</strong>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="se_turna_a_area">Área a la que se turna</label>
								<select class="form-control" name="se_turna_a_area" id="se_turna_a_area" >
								<option value="">Escoge una opción</option>
									<?php foreach ($areas as $area) : ?>
										<option value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="fecha_en_que_se_turna">Fecha en que destinatario recibió el oficio</label>
								<input type="date" class="form-control" name="fecha_en_que_se_turna" id="fecha_en_que_se_turna" >
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="fecha_espera_respuesta">Fecha en que se espera respuesta</label>
								<input type="date" class="form-control" name="fecha_espera_respuesta" id="se_turna_a_area" >
							</div>
						</div>
						
					</div>
                </div>

                
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