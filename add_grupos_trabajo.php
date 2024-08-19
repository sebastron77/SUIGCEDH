<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar MESAS, COMITÉS Y GRUPOS DE TRABAJO de ST';
require_once('includes/load.php');
$id_folio = last_id_folios_general();
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

$area_user = area_usuario2($id_user);
$tipo_accion = find_all('cat_tipo_accion');
$areas_cedh = find_all('area');
$area = isset($_GET['a']) ? $_GET['a'] : '0';
$inticadores_pat = find_all_pat(3);
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_grupos_trabajo'])) {

    if (empty($errors)) {
        $id_cat_tipo_accion   = remove_junk($db->escape($_POST['id_cat_tipo_accion']));
        $nombre_grupo   = remove_junk($db->escape($_POST['nombre_grupo']));
        $numero_sesion   = remove_junk(($db->escape($_POST['numero_sesion'])));
        $fecha_sesion   = remove_junk($db->escape($_POST['fecha_sesion']));
        $lugar_sesion   = remove_junk($db->escape($_POST['lugar_sesion']));
        $no_asistentes   = remove_junk(($db->escape($_POST['no_asistentes']))); 
        $modalidad   = remove_junk($db->escape($_POST['modalidad']));
        $alcances_acuerdos   = remove_junk($db->escape($_POST['alcances_acuerdos']));
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));

		$nombre_participante = $_POST['nombre_participante'];	

        if (count($id_folio) == 0) {
            $nuevo_id_queja = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_queja = (int) $nuevo['contador'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['contador'] + 1);
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
        // Se crea el folio de capacitacion
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-MCGT';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-MCGT';
        $carpeta = 'uploads/grupos_trabajo/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['documento']['name'];
        $size = $_FILES['documento']['size'];
        $type = $_FILES['documento']['type'];
        $temp = $_FILES['documento']['tmp_name'];

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
		/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}


        $dbh1 = new PDO('mysql:host=localhost;dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');	
		
            $query = "INSERT INTO grupos_trabajo (";
            $query .= "folio,id_cat_tipo_accion,nombre_grupo,fecha_sesion,lugar_sesion,numero_sesion,modalidad,no_asistentes,documento,alcances_acuerdos,id_indicadores_pat,observaciones,user_creador,fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$id_cat_tipo_accion}','{$nombre_grupo}','{$fecha_sesion}','{$lugar_sesion}','{$numero_sesion}','{$modalidad}','{$no_asistentes}','{$name}','{$alcances_acuerdos}','{$id_indicadores_pat}','{$observaciones}','{$id_user}',NOW()";
            $query .= ")";
		if ($dbh1->query($query)){
			$id_grupos_trabajo = $dbh1->lastInsertId();
			
		}		
		
            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";
			
		
        for ($i = 0; $i < sizeof($nombre_participante); $i = $i + 1) {
			if($nombre_participante[$i] !== ''){
				$query = "INSERT INTO rel_grupos_trabajo_participantes (id_grupos_trabajo, nombre_participante) VALUES($id_grupos_trabajo, '$nombre_participante[$i]');";       
				if ($db->query($query)) {
					//echo "<p>Denuncia-denunciado insertada con éxito</p>";		
				 }
			}
		}
	
		
            //success
            $session->msg('s', " La Actividad Secretaría Técnica ha sido agregada con éxito. Folio ".$folio);
            insertAccion($user['id_user'], '"'.$user['username'].'" agregó una Actividad Secretaría Técnica. Folio '.$folio.' .', 1);
            redirect('grupos_trabajo.php', false);        
    } else {
        $session->msg("d", $errors);
        redirect('add_grupos_trabajo.php', false);
    }
}
?>

<script type="text/javascript">	
		
	$(document).ready(function() {
		
		
		$("#addRow").click(function() {	
			var html = '';
				html += '<div id="inputFormRow">';				
				html += '	<div class="col-md-7">';
				html += '					<div class="form-group">';
				html += '						<input type="text" class="form-control" name="nombre_participante[]" >';
				html += '					</div>';
				html += '				</div>';
				html += '	<div class="col-md-4">';
				html += '	<button type="button" class="btn btn-outline-danger" id="removeRow" > ';
				html += '   	<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">';
				html += '			<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>';
				html += '			<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>';
				html += '		</svg>';
				html += '  	</button>';			
				html += '	</div> <br><br>';
				html += '</div> ';

				$('#newRow').append(html);
		});
		
		
		
		$(document).on('click', '#removeRow', function() {
				$(this).closest('#inputFormRow').remove();
			});
			
	
	
	});
	
	
</script>
<?php 
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Actividad Secretaría Técnica</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_grupos_trabajo.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_tipo_accion">Tipo de actividad</label>
                            <select class="form-control" name="id_cat_tipo_accion" required>
                                <option value="">Escoge una opción</option>
 <?php foreach ($tipo_accion as $tipo_accion) : ?>
                                    <option  value="<?php echo $tipo_accion['id_cat_tipo_accion']; ?>"><?php echo ucwords($tipo_accion['descripcion']); ?></option>
                                <?php endforeach; ?>								
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_grupo">Nombre del actividad</label>
                            <input type="text" class="form-control" name="nombre_grupo" required>
                        </div>
                    </div>
					 <div class="col-md-2">
                        <div class="form-group">
                            <label for="numero_sesion">No. de Sesión</label>
                            <input type="number" min="1" class="form-control" max="1000" name="numero_sesion" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_sesion">Fecha</label><br>
                            <input type="date" class="form-control" name="fecha_sesion" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="lugar_sesion">Lugar</label>
                            <input type="text" class="form-control" name="lugar_sesion" required>
                        </div>
                    </div>
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="no_asistentes">No. de asistentes</label>
                            <input type="number" min="1" class="form-control" max="1000" name="no_asistentes" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="modalidad">Modalidad</label>
                            <select class="form-control" name="modalidad" required>
                                <option value="">Escoge una opción</option>
                                <option value="Presencial">Presencial</option>
                                <option value="En línea">En línea</option>
                                <option value="Híbrido">Híbrido</option>
                            </select>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" required>
                                <option value="">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
<div class="col-md-4">
                        <div class="form-group">
                            <span>
                                <label for="documento">Invitación</label>
                                <input id="documento" type="file" accept="application/pdf" class="form-control" name="documento" required>
                            </span>
                        </div>
                    </div>					
                </div>

      

                <div class="row">              
	<div class="col-md-4">
                        <div class="form-group">
                            <label for="alcances_acuerdos">Avances y Acuerdos</label>
                            <textarea class="form-control" name="alcances_acuerdos" id="alcances_acuerdos" cols="10" rows="5"></textarea>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="5"></textarea>
                        </div>
                    </div>
				</div>

      

                <div class="row">
					 
					<table style="color:#3a3d44; margin-top: -10px; page-break-after:always;" >
						<tr>
							<td style="width: 50%;">
								<div class="panel-heading">
								<strong>
									<span class="glyphicon glyphicon-option-vertical"></span>
									<span> Miembros/ participantes</span>
								</strong>
							</div>
						</td>
						<td style="width: 50%;">
								
						</td>
							
						</tr>						
						<tr>
							<td style="width: 50%;">
							<div id="inputFormRow" style="width: 100%;">						
								<div class="col-md-7">
									<div class="form-group">
									<input type="text" class="form-control" name="nombre_participante[]" required>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
									<button type="button" class="btn btn-success" id="addRow" name="addRow" >
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
										  <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
										  <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
										</svg>
									</button>										
									</div>
								</div>	
							</div>
							<br>
							<div class="row" id="newRow" style="width: 100%;">
							</div>							
							
						</td>
						<td style="width: 50%;">
						
												
						</td>
						</tr>
					</table>
				</div> 	

                <div class="form-group clearfix">
                    <a href="grupos_trabajo.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_grupos_trabajo" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>