<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Correspondencia - Oficialia de partes';
require_once('includes/load.php');

$user = current_user();
$detalle = $user['id_user'];
$nivel = $user['user_level'];
$areas = find_all('area');
$id_folio = last_id_folios();
$id_correspondencia = last_id_correspondencia();
$area_ingreso = isset($_GET['a']) ? $_GET['a'] : '0';

?>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_correspondencia'])) {

    $req_fields = array('fecha_recibido', 'nombre_remitente', 'asunto', 'medio_recepcion');
    validate_fields($req_fields);

    if (empty($errors)) {
        $fecha_recibido   = remove_junk($db->escape($_POST['fecha_recibido']));
        $num_oficio_recepcion   = remove_junk($db->escape($_POST['num_oficio_recepcion']));
        $medio_recepcion   = remove_junk(($db->escape($_POST['medio_recepcion'])));
        $asunto   = remove_junk(($db->escape($_POST['asunto'])));
		
        $nombre_institucion   = remove_junk($db->escape($_POST['nombre_institucion']));
        $nombre_remitente   = remove_junk($db->escape($_POST['nombre_remitente']));
        $cargo_funcionario   = remove_junk($db->escape($_POST['cargo_funcionario']));
		
        
		$oficio_interno   = remove_junk(($db->escape($_POST['oficio_interno'])));
        $fecha_en_que_se_turna   = remove_junk(($db->escape($_POST['fecha_en_que_se_turna'])));        
		$medio_entrega   = remove_junk(($db->escape($_POST['medio_entrega'])));
        $id_area_turnada   = remove_junk(($db->escape($_POST['id_area_turnada'])));
        $fecha_espera_respuesta   = remove_junk(($db->escape($_POST['fecha_espera_respuesta'])));
        $tipo_tramite   = remove_junk(($db->escape($_POST['tipo_tramite'])));
        $observaciones   = remove_junk(($db->escape($_POST['observaciones'])));
		$envio_area = isset($_POST['envio_area']) ? 1 : 0;
		
//Suma el valor del id anterior + 1, para generar ese id para el nuevo resguardo
        //La variable $no_folio sirve para el numero de folio
        if (count($id_correspondencia) == 0) {
            $nuevo_id_convenio = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_correspondencia as $nuevo) {
                $nuevo_id_convenio = (int) $nuevo['id_correspondencia'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['id_correspondencia'] + 1);
            }
        }

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int) $nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int) $nuevo['contador'] + 1);
            }
        }
        //Se crea el número de folio
        $year = date("Y");
        // Se crea el folio de canalizacion
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-COR';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-COR';
        $carpeta = 'uploads/correspondencia/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

		
		$name_recibido = $_FILES['oficio_recibido']['name'];
        $size_recibido = $_FILES['oficio_recibido']['size'];
        $type_recibido = $_FILES['oficio_recibido']['type'];
        $temp_recibido = $_FILES['oficio_recibido']['tmp_name'];
		
		$name_enviado = $_FILES['oficio_enviado']['name'];
        $size_enviado = $_FILES['oficio_enviado']['size'];
        $type_enviado = $_FILES['oficio_enviado']['type'];
        $temp_enviado = $_FILES['oficio_enviado']['tmp_name'];

        $move =  move_uploaded_file($temp_recibido, $carpeta . "/" . $name_recibido);
        $move2 =  move_uploaded_file($temp_enviado, $carpeta . "/" . $name_enviado);

/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}

        $query = "INSERT INTO correspondencia (";
        $query .= "folio,fecha_recibido,num_oficio_recepcion,medio_recepcion,asunto,nombre_remitente,nombre_institucion,cargo_funcionario,oficio,
					oficio_interno,fecha_en_que_se_turna,asunto_interno,medio_entrega,id_area_turnada,	oficio_enviado,				
                   tipo_tramite,observaciones,envio_area,area_creacion,id_user_creador,fecha_creacion";
		   if ($fecha_espera_respuesta != ''){
			$query .= ", fecha_espera_respuesta ";
			}
        $query .= ") VALUES (";
        $query .= " '{$folio}','{$fecha_recibido}','{$num_oficio_recepcion}','{$medio_recepcion}','{$asunto}','{$nombre_remitente}','{$nombre_institucion}','{$cargo_funcionario}','{$name_recibido}',
					'{$oficio_interno}','{$fecha_en_que_se_turna}','{$asunto}','{$medio_entrega}','{$id_area_turnada}','{$name_enviado}',
                    '{$tipo_tramite}','{$observaciones}',{$envio_area},'{$area_ingreso}',{$detalle},Now()";
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
			insertAccion($user['id_user'],'"'.$user['username'].'" dió de Alta la correspondencia de Folio: -'.$folio.'-  correspondiente al No. Ocidio de Recepción -'.$num_oficio_recepcion.'-.',1);
			if($envio_area === 1){
				$query3 = "INSERT INTO envio_correspondencia (";
				$query3 .= "folio,no_oficio,fecha_emision,asunto,medio_envio,se_turna_a_area,tipo_tramite,observaciones,area_creacion,fecha_creacion,user_creador,fecha_en_que_se_turna,envio_oficialia";
				if ($name_enviado != ''){
					$query3 .= ", oficio_enviado ";
				}
				if ($fecha_espera_respuesta != ''){
					$query3 .= ", fecha_espera_respuesta ";
					}				
				$query3 .= ") VALUES (";
				$query3 .= " '{$folio}','{$oficio_interno}','{$fecha_en_que_se_turna}','{$asunto}','{$medio_entrega}','{$id_area_turnada}','{$tipo_tramite}','{$observaciones}','{$area_ingreso}',NOW(),'{$detalle}','{$fecha_en_que_se_turna}',1";
				if ($name_enviado != ''){
					$query3 .= ", '{$name_enviado}' ";
				}
				 if ($fecha_espera_respuesta != ''){
			$query3 .= ", fecha_espera_respuesta ";
			}
				$query3 .= ")";
				 if ($db->query($query3)) {
					//sucess				
					insertAccion($user['id_user'],'"'.$user['username'].'" agrego la Correspondencia Interna Enviada. Folio:'.$folio,1);
				} 
			}
            $session->msg('s', " La correspondencia ha sido agregada con éxito.");
            redirect('correspondencia.php?a='.$area_ingreso, false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la correspondencia.');
            redirect('add_correspondencia.php?a='.$area_ingreso, false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_correspondencia.php?a='.$area_ingreso, false);
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
                <span>Agregar correspondencia - Oficialia de partes</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_correspondencia.php?a=<?php echo $area_ingreso?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_recibido">Fecha de Recepción <span style="color:red;font-weight:bold">*</span></label>
                            <input type="date" class="form-control" name="fecha_recibido" required >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="num_oficio_recepcion">Número de Oficio de Recepción</label>
                            <input type="text" class="form-control" name="num_oficio_recepcion">
                        </div>
                    </div>
					 <div class="col-md-2">
                        <div class="form-group">
                            <label for="medio_recepcion">Medio de Recepción  <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="medio_recepcion" required>
                                <option value="">Escoge una opción</option>
                                <option value="Correo">Correo</option>
                                <option value="Mediante Oficio">Mediante Oficio</option>
                                <option value="Oficialia de partes">Oficialia de partes</option>
                                <option value="Paquetería">Paquetería</option>
                                <option value="Fax">Fax</option>
                                <option value="WhatsApp">WhatsApp</option>
                            </select>
                        </div>
                    </div>
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="asunto">Asunto <span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="asunto" required>
                        </div>
                    </div>
                     <div class="col-md-3">
                        <div class="form-group">
                            <label for="oficio_recibido">Adjuntar Oficio en Digital</label>
                            <input type="file" accept="application/pdf" class="form-control" name="oficio_recibido" id="oficio_recibido" >
                        </div>
                    </div>
                </div>
				<div class="row">
                    <h3 style="margin-top: 1%; font-weight:bold;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#3a3d44" height="32px" width="32px" viewBox="0 0 24 24">
                            <path d="M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm4-2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5ZM9 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 9 8Zm1 2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5Z" />
                            <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2ZM1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H8.96c.026-.163.04-.33.04-.5C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1.006 1.006 0 0 1 1 12V4Z" />
                        </svg>
                        Datos Remitente
                    </h3>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_institucion">Nombre de Institución</label>
                            <input type="text" class="form-control" name="nombre_institucion">
                        </div>
                    </div>
                   <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_remitente">Nombre de Remitente <span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="nombre_remitente" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cargo_funcionario">Cargo de Funcionario</label>
                            <input type="text" class="form-control" name="cargo_funcionario">
                        </div>
                    </div>
                   
                </div>

                <br>
				<div class="row">
				<h3 style="margin-top: 1%; font-weight:bold;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#3a3d44" height="32px" width="32px" viewBox="0 0 24 24">
					  <path d="m8.335 6.982.8 1.386a.25.25 0 0 0 .451-.039l1.06-2.882a.25.25 0 0 0-.192-.333l-3.026-.523a.25.25 0 0 0-.26.371l.667 1.154-.621.373A2.5 2.5 0 0 0 6 8.632V11h1V8.632a1.5 1.5 0 0 1 .728-1.286z"/>
					  <path fill-rule="evenodd" d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134Z"/>
					</svg>
						Seguimiento a Turno
                    </h3>
                </div>
				
<hr>
                <div class="row">
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="oficio_interno">Número de Oficio de Interno</label>
                            <input type="text" class="form-control" name="oficio_interno">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_en_que_se_turna">Fecha en que se turna oficio</label>
                            <input type="date" class="form-control" name="fecha_en_que_se_turna">
                        </div>
                    </div>
					
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="medio_entrega">Medio de Entrega</label><br>
                            <select class="form-control" name="medio_entrega">
                                <option value="Escoge una opción">Escoge una opción</option>
                                <option value="Correo">Correo</option>
                                <option value="Mediante Oficio">Mediante Oficio</option>
                                <option value="Paquetería">Paquetería</option>
                                <option value="WhatsApp">WhatsApp</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area_turnada">Área a la que se turna</label>
                            <select class="form-control" name="id_area_turnada">
							<option value="">Escoge una opción</option>
                                <?php foreach ($areas as $area) : ?>
                                    <option value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_espera_respuesta">Fecha en que se espera respuesta</label>
                            <input type="date" class="form-control" name="fecha_espera_respuesta">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_tramite">Tipo de Trámite</label><br>
                            <select class="form-control" name="tipo_tramite">
                                <option value="Escoge una opción">Escoge una opción</option>
                                <option value="Respuesta">Respuesta</option>
                                <option value="Conocimiento">Conocimiento</option>
                                <option value="Circular">Circular</option>
                                <option value="Invitación">Invitación</option>
                                <option value="Trámite de Queja">Trámite de Queja</option>
                            </select>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="oficio_enviado">Adjuntar Oficio en Digital</label>
                            <input type="file" accept="application/pdf" class="form-control" name="oficio_enviado" id="oficio_enviado" >
                        </div>
                    </div>
					 <div class="col-md-2">
                        <div class="form-group">
                            <label for="envio_area">¿Se envia a Área?</label><br>
                            <label class="switch" style="float:left;">
                                <div class="row">
                                    <input type="checkbox" id="envio_area" name="envio_area" >
                                    <span class="slider round"></span>
                                    <div>
                                        <p style="margin-left: 150%; margin-top: -3%; font-size: 14px;">No/Sí</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="5"></textarea>
                        </div>
                    </div>
					
                </div>
                <div class="form-group clearfix">
                    <a href="correspondencia.php?a=<?php echo $area_ingreso?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_correspondencia" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>