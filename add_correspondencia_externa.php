<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Correspondencia Externa';
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
        $asunto   = remove_junk(($db->escape($_POST['asunto'])));
        $medio_recepcion   = remove_junk(($db->escape($_POST['medio_recepcion'])));
        $observaciones   = remove_junk(($db->escape($_POST['observaciones'])));
        $nombre_institucion   = remove_junk($db->escape($_POST['nombre_institucion']));
        $nombre_remitente   = remove_junk($db->escape($_POST['nombre_remitente']));
        $cargo_funcionario   = remove_junk($db->escape($_POST['cargo_funcionario']));
			      
		
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
		 

        $name = $_FILES['oficio_recibido']['name'];
        $size = $_FILES['oficio_recibido']['size'];
        $type = $_FILES['oficio_recibido']['type'];
        $temp = $_FILES['oficio_recibido']['tmp_name'];

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
		/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}




        $query = "INSERT INTO correspondencia (";
        $query .= "folio,fecha_recibido,num_oficio_recepcion,asunto,medio_recepcion,oficio, observaciones, nombre_institucion,nombre_remitente,cargo_funcionario,id_area_turnada,area_creacion,id_user_creador,fecha_creacion";
        $query .= ") VALUES (";
        $query .= " '{$folio}','{$fecha_recibido}','{$num_oficio_recepcion}','{$asunto}','{$medio_recepcion}','{$name}','{$observaciones}','{$nombre_institucion}','{$nombre_remitente}','{$cargo_funcionario}','{$area_ingreso}',
                    '{$area_ingreso}',{$detalle},Now()";
        $query .= ")";
echo $query;
           $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";

        if ($db->query($query) && $db->query($query2)) {
            //sucess
			insertAccion($user['id_user'],'"'.$user['username'].'" dió de Alta la correspondencia Recibida Externade Folio: -'.$folio.'-  correspondiente al No. Ocidio de Recepción -'.$num_oficio_recepcion.'-.',1);
            $session->msg('s', " La correspondencia ha sido agregada con éxito.");
            redirect('correspondencia_recibida_externa.php?a='.$area_ingreso, false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la correspondencia.');
            redirect('correspondencia_recibida_externa.php?a='.$area_ingreso, false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('correspondencia_recibida_externa.php?a='.$area_ingreso, false);
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
                <span>Agregar Correspondencia Recibida Externa</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_correspondencia_externa.php?a=<?php echo $area_ingreso?>" enctype="multipart/form-data">
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
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="oficio_recibido">Adjuntar Oficio en Digital</label>
                            <input type="file" accept="application/pdf" class="form-control" name="oficio_recibido" id="oficio_recibido" required>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="3"></textarea>
                        </div>
                    </div>
					
					<div class="panel-heading">
						<strong>
							<span class="glyphicon glyphicon-th"></span>
							<span> Datos Remitente</span>
						</strong>
					</div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_institucion">Nombre de Institución</label>
                            <input type="text" class="form-control" name="nombre_institucion">
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
                            <label for="cargo_funcionario">Cargo de Funcionario</label>
                            <input type="text" class="form-control" name="cargo_funcionario">
                        </div>
                    </div>
                </div>

                <br>
                 
                <div class="form-group clearfix">
                    <a href="correspondencia_recibida_externa.php?a=<?php echo $area_ingreso?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_correspondencia" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>