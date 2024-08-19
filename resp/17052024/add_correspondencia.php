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
        $nombre_remitente   = remove_junk($db->escape($_POST['nombre_remitente']));
        $nombre_institucion   = remove_junk($db->escape($_POST['nombre_institucion']));
        $cargo_funcionario   = remove_junk($db->escape($_POST['cargo_funcionario']));
        $asunto   = remove_junk(($db->escape($_POST['asunto'])));
        $medio_recepcion   = remove_junk(($db->escape($_POST['medio_recepcion'])));
        $medio_entrega   = remove_junk(($db->escape($_POST['medio_entrega'])));
        $id_area_turnada   = remove_junk(($db->escape($_POST['id_area_turnada'])));
        $fecha_en_que_se_turna   = remove_junk(($db->escape($_POST['fecha_en_que_se_turna'])));
        $fecha_espera_respuesta   = remove_junk(($db->escape($_POST['fecha_espera_respuesta'])));
        $tipo_tramite   = remove_junk(($db->escape($_POST['tipo_tramite'])));
        $observaciones   = remove_junk(($db->escape($_POST['observaciones'])));
		
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

/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}


        $query = "INSERT INTO correspondencia (";
        $query .= "folio,fecha_recibido,num_oficio_recepcion,nombre_remitente,nombre_institucion,cargo_funcionario,asunto,medio_recepcion,medio_entrega,id_area_turnada,
                   fecha_en_que_se_turna,fecha_espera_respuesta,tipo_tramite,observaciones,area_creacion,id_user_creador,fecha_creacion";
        $query .= ") VALUES (";
        $query .= " '{$folio}','{$fecha_recibido}','{$num_oficio_recepcion}','{$nombre_remitente}','{$nombre_institucion}','{$cargo_funcionario}','{$asunto}','{$medio_recepcion}',
                    '{$medio_entrega}','{$id_area_turnada}','{$fecha_en_que_se_turna}','{$fecha_espera_respuesta}','{$tipo_tramite}','{$observaciones}','{$area_ingreso}',{$detalle},Now()";
        $query .= ")";
echo $query;
           $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";

        if ($db->query($query) && $db->query($query2)) {
            //sucess
			insertAccion($user['id_user'],'"'.$user['username'].'" dió de Alta la correspondencia de Folio: -'.$folio.'-  correspondiente al No. Ocidio de Recepción -'.$num_oficio_recepcion.'-.',1);
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_recibido">Fecha de Recepción</label>
                            <input type="date" class="form-control" name="fecha_recibido" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="num_oficio_recepcion">Número de Oficio de Recepción</label>
                            <input type="text" class="form-control" name="num_oficio_recepcion">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_remitente">Nombre de Remitente</label>
                            <input type="text" class="form-control" name="nombre_remitente" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_institucion">Nombre de Institución</label>
                            <input type="text" class="form-control" name="nombre_institucion">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cargo_funcionario">Cargo de Funcionario</label>
                            <input type="text" class="form-control" name="cargo_funcionario">
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
                            <label for="medio_recepcion">Medio de Recepción</label>
                            <select class="form-control" name="medio_recepcion">
                                <option value="Escoge una opción">Escoge una opción</option>
                                <option value="Correo">Correo</option>
                                <option value="Mediante Oficio">Mediante Oficio</option>
                                <option value="Oficialia de partes">Oficialia de partes</option>
                                <option value="Paquetería">Paquetería</option>
                                <option value="Fax">Fax</option>
                                <option value="WhatsApp">WhatsApp</option>
                            </select>
                        </div>
                    </div>
                </div>

                <br>
                 <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Seguimiento a Turno</span>
            </strong>
        </div>

                <div class="row">
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
                            <label for="fecha_en_que_se_turna">Fecha en que se turna oficio</label>
                            <input type="date" class="form-control" name="fecha_en_que_se_turna">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_espera_respuesta">Fecha en que se espera respuesta</label>
                            <input type="date" class="form-control" name="fecha_espera_respuesta">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_tramite">Tipo de Trámite</label><br>
                            <select class="form-control" name="tipo_tramite">
                                <option value="Escoge una opción">Escoge una opción</option>
                                <option value="Respuesta">Respuesta</option>
                                <option value="Conocimiento">Conocimiento</option>
                                <option value="Circular">Circular</option>
                            </select>
                        </div>
                    </div>
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