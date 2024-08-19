<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Curso/Diplomado';
require_once('includes/load.php');

$id_table = last_id_table('cursos_diplomados','id_cursos_diplomados');
$id_folio = last_id_folios();
$cat_ejes = find_all('cat_ejes_estrategicos');
$cat_agendas = find_all('cat_agendas');
$areas_all = find_all('area');
$cat_modalidad = find_all('cat_modalidad');
$cat_publico_objetivo = find_all('cat_publico_objetivo');
$cat_grupos_vuln = find_all('cat_grupos_vuln');
$cat_tipo_actividad = find_all('cat_tipo_actividad');
$cat_categoria_actividad = find_all('cat_categoria_actividad');
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_cursosdip'])) {

    $req_fields = array('id_cat_ejes_estrategicos', 'id_cat_agendas', 'nombre_curso', 'fecha_apertura', 'duracion_horas', 'id_area_responsable',  'id_cat_modalidad');
    validate_fields($req_fields);

    if (empty($errors)) {
        $id_cat_ejes_estrategicos   = remove_junk($db->escape($_POST['id_cat_ejes_estrategicos']));
        $id_cat_agendas   = remove_junk($db->escape($_POST['id_cat_agendas']));
        $nombre_curso   = remove_junk($db->escape($_POST['nombre_curso']));
        $objetivo   = remove_junk($db->escape($_POST['objetivo']));
        $descripcion   = remove_junk($db->escape($_POST['descripcion']));
        $fecha_apertura   = remove_junk(($db->escape($_POST['fecha_apertura'])));
        $duracion_horas   = remove_junk(($db->escape($_POST['duracion_horas'])));
        $id_area_responsable   = remove_junk(($db->escape($_POST['id_area_responsable'])));
        $nombre_responsable   = remove_junk(($db->escape($_POST['nombre_responsable'])));
        $id_cat_modalidad   = remove_junk(($db->escape($_POST['id_cat_modalidad'])));
        $observaciones   = remove_junk(($db->escape($_POST['observaciones'])));
        $liga_acceso   = remove_junk(($db->escape($_POST['liga_acceso'])));
        $id_cat_tipo_actividad   = remove_junk(($db->escape($_POST['id_cat_tipo_actividad'])));
        $id_cat_categoria_actividad   = remove_junk(($db->escape($_POST['id_cat_categoria_actividad'])));
		
		$id_cat_publico_objetivo = $_POST['id_cat_publico_objetivo'];
		$id_cat_grupo_vuln = $_POST['id_cat_grupo_vuln'];

        if (count($id_table) == 0) {
            $nuevo_id_ori_canal = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_table as $nuevo) {
                $nuevo_id_ori_canal = (int) $nuevo['id_cursos_diplomados'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['id_cursos_diplomados'] + 1);
            }
        }

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int) $nuevo['contador'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['contador'] + 1);
            }
        }
		
		$year = date("Y");
        $folio = 'CEDH/' . $no_folio . '/' . $year . '-EDU';
		$folio_carpeta = 'CEDH-' . $no_folio . '-' . $year . '-EDU';
		$carpeta = 'uploads/cursosdiplomados/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $fecha_tecnica = $_FILES['fecha_tecnica']['name'];
        $size_ft = $_FILES['fecha_tecnica']['size'];
        $type_ft = $_FILES['fecha_tecnica']['type'];
        $temp_ft = $_FILES['fecha_tecnica']['tmp_name'];    

        $move_ft =  move_uploaded_file($temp_ft, $carpeta . "/" . $fecha_tecnica);
		
        $expediente_tecnico = $_FILES['expediente_tecnico']['name'];
        $size_et = $_FILES['expediente_tecnico']['size'];
        $type_et = $_FILES['expediente_tecnico']['type'];
        $temp_et = $_FILES['expediente_tecnico']['tmp_name'];    

        $move_et =  move_uploaded_file($temp_et, $carpeta . "/" . $expediente_tecnico);
		
		/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}

        
       $dbh = new PDO('mysql:host=localhost;dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');
	   
            $query = "INSERT INTO cursos_diplomados (";
            $query .= "folio,id_cat_ejes_estrategicos,id_cat_agendas,nombre_curso,objetivo,descripcion,fecha_apertura,id_cat_categoria_actividad,
						duracion_horas,id_area_responsable,nombre_responsable,id_cat_modalidad,observaciones,liga_acceso,id_cat_tipo_actividad,user_creador,fecha_creacion";
			if($move_ft){$query .= ",fecha_tecnica";}
			if($move_et){$query .= ",expediente_tecnico";}
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$id_cat_ejes_estrategicos}','{$id_cat_agendas}','{$nombre_curso}','{$objetivo}','{$descripcion}','{$fecha_apertura}','{$id_cat_categoria_actividad}',
					'{$duracion_horas}','{$id_area_responsable}','{$nombre_responsable}','{$id_cat_modalidad}','{$observaciones}','{$liga_acceso}','{$id_cat_tipo_actividad}','{$id_user}',NOW()";
			if($move_ft){$query .= ",'{$fecha_tecnica}'";}
			if($move_et){$query .= ",'{$expediente_tecnico}'";}
            $query .= ")";
//echo $query;
            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio}'";
            $query2 .= ")";
						
       if ($db->query($query) && $db->query($query2)) {
			$id_cursos_diplomados = $dbh->lastInsertId();
			
				
				for ($i = 0; $i < sizeof($id_cat_publico_objetivo); $i = $i + 1) {
					if($id_cat_publico_objetivo[$i] !== '' && $id_cat_publico_objetivo[$i] > 0){
						$queryInsert4 = "INSERT INTO rel_cursos_publico (id_cursos_diplomados,id_cat_publico_objetivo) VALUES('$id_cursos_diplomados','$id_cat_publico_objetivo[$i]')";
						$db->query($queryInsert4);
						
						if($id_cat_publico_objetivo[$i] == 4){
							for ($i = 0; $i < sizeof($id_cat_grupo_vuln); $i = $i + 1) {
								if($id_cat_grupo_vuln[$i] !== '' && $id_cat_grupo_vuln[$i] > 0){
									$queryInsert5 = "INSERT INTO rel_cursos_grupos_vulnerables (id_cursos_diplomados,id_cat_grupo_vuln) VALUES('$id_cursos_diplomados','$id_cat_grupo_vuln[$i]')";
									$db->query($queryInsert5);									
								}
							}	
						}
					}
				}
				//sucess
				$session->msg('s', " El Curso Diplomado ha sido agregada con éxito.");
				insertAccion($user['id_user'], '"' . $user['username'] . '" agregó el Curso/Diplomado, Folio: ' . $folio . '.', 1);
				redirect('cursos_diplomados.php', false);
			
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la capacitación.');
            redirect('add_cursosdip.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_cursosdip.php', false);
    }
}
?>

<script type="text/javascript">	
		
	$(document).ready(function() {
		
		
		$("#addRow").click(function() {	
			var html = '';
				html += '<div id="inputFormRow">';				
				html += '	<div class="col-md-7">';
				html += '		<select class="form-control" name="id_cat_publico_objetivo[]" onchange="showInp(this.value)">';
                html += '                <option value="">Escoge una opción</option>';
                               <?php foreach ($cat_publico_objetivo as $datos) : ?>
                html += '                   <option value="<?php echo $datos['id_cat_publico_objetivo']; ?>"><?php echo ($datos['descripcion']); ?></option>';
                               <?php endforeach; ?>
                html += '            </select>';
				html += '	</div>';
				html += '	<div class="col-md-2">';
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
		
		$("#addRow2").click(function() {	
			var html = '';
				html += '<div id="inputFormRow2">';				
				html += '	<div class="col-md-7">';
				html += '		<select class="form-control" name="id_cat_grupo_vuln[]">';
                html += '                <option value="">Escoge una opción</option>';
                               <?php foreach ($cat_grupos_vuln as $datos) : ?>
                html += '                   <option value="<?php echo $datos['id_cat_grupo_vuln']; ?>"><?php echo ($datos['descripcion']); ?></option>';
                               <?php endforeach; ?>
                html += '            </select>';
				html += '	</div>';
				html += '	<div class="col-md-2">';
				html += '	<button type="button" class="btn btn-outline-danger" id="removeRow2" > ';
				html += '   	<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">';
				html += '			<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>';
				html += '			<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>';
				html += '		</svg>';
				html += '  	</button>';			
				html += '	</div> <br><br>';
				html += '</div> ';

				$('#newRow2').append(html);
		});
		
		
		$(document).on('click', '#removeRow', function() {
				$(this).closest('#inputFormRow').remove();
			});
			
		$(document).on('click', '#removeRow2', function() {
			$(this).closest('#inputFormRow2').remove();
		});


		
	
	});
	
	function showInp(valor) {
		//alert(valor);
		if(valor == 4){
			document.getElementById('gv1').style.display = 'block';
			document.getElementById('gv2').style.display = 'block';
			}else{
			//document.getElementById('gv1').style.display = 'none';
			//document.getElementById('gv2').style.display = 'none';
			}
       
	}
	
	
</script>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Curso/ Diplomado</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_cursosdip.php?" enctype="multipart/form-data">
                <div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label for="id_cat_ejes_estrategicos">Eje Estrategico</label>
							<select class="form-control" name="id_cat_ejes_estrategicos" required>
									<option value="">Escoge una opción</option>
									<?php foreach ($cat_ejes as $datos) : ?>
										<option value="<?php echo $datos['id_cat_ejes_estrategicos']; ?>"><?php echo $datos['descripcion']; ?></option>
									<?php endforeach; ?>
								</select>
							
						</div>
					</div>				
					<div class="col-md-2">
						<div class="form-group">
							<label for="id_cat_agendas">Agenda</label>
							<select class="form-control" name="id_cat_agendas" required>
									<option value="">Escoge una opción</option>
									<?php foreach ($cat_agendas as $datos) : ?>
										<option value="<?php echo $datos['id_cat_agendas']; ?>"><?php echo $datos['descripcion']; ?></option>
									<?php endforeach; ?>
								</select>
							
						</div>
					</div>	
					<div class="col-md-2">
						<div class="form-group">
							<label for="id_cat_tipo_actividad">Tipo Actividad</label>
							<select class="form-control" name="id_cat_tipo_actividad" required>
									<option value="">Escoge una opción</option>
									<?php foreach ($cat_tipo_actividad as $datos) : ?>
										<option value="<?php echo $datos['id_cat_tipo_actividad']; ?>"><?php echo $datos['descripcion']; ?></option>
									<?php endforeach; ?>
								</select>
							
						</div>
					</div>	
					<div class="col-md-2">
						<div class="form-group">
							<label for="id_cat_categoria_actividad">Categoría</label>
							<select class="form-control" name="id_cat_categoria_actividad" required>
									<option value="">Escoge una opción</option>
									<?php foreach ($cat_categoria_actividad as $datos) : ?>
										<option value="<?php echo $datos['id_cat_categoria_actividad']; ?>"><?php echo $datos['descripcion']; ?></option>
									<?php endforeach; ?>
								</select>
							
						</div>
					</div>					
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_curso">Nombre Curso/Diplomado</label>
                            <input type="text" class="form-control" name="nombre_curso" placeholder="Nombre Curso/Diplomado" required>
                        </div>
                    </div>
				</div>
				 <div class="row">				
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_apertura">Fecha Apertura</label><br>
                            <input type="date" class="form-control" name="fecha_apertura" required>
                        </div>
                    </div>
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="duracion_horas">Duración en Horas</label>
                            <input type="number"  class="form-control" max="500" name="duracion_horas" value="0" required>
                        </div>
                    </div>
					  <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_modalidad">Modalidad</label>
                            <select class="form-control" name="id_cat_modalidad" required>
									<option value="">Escoge una opción</option>
									<?php foreach ($cat_modalidad as $datos) : ?>
										<option value="<?php echo $datos['id_cat_modalidad']; ?>"><?php echo $datos['descripcion']; ?></option>
									<?php endforeach; ?>
								</select>
                        </div>
                    </div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="id_area_responsable">Área Responsable</label>
							<select class="form-control" name="id_area_responsable" required>
									<option value="">Escoge una opción</option>
									<?php foreach ($areas_all as $datos) : ?>
										<option value="<?php echo $datos['id_area']; ?>"><?php echo $datos['nombre_area']; ?></option>
									<?php endforeach; ?>
								</select>
							
						</div>
					</div>	
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_responsable">Nombre del Responsable</label>
                            <input type="text" class="form-control" name="nombre_responsable" required>
                        </div>
                    </div>
				</div>
				
                <div class="row">
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="liga_acceso">Liga Acceso</label>
                            <input type="text" class="form-control" name="liga_acceso">
                        </div>
                    </div>
					
                     <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_tecnica">Ficha Técnica</label>
                            <input type="file" accept="application/pdf" class="form-control" name="fecha_tecnica" id="fecha_tecnica" required>
                        </div>
                    </div>
				 
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="expediente_tecnico">Expediente Técnico</label>
                            <input type="file" accept="application/pdf" class="form-control" name="expediente_tecnico" id="expediente_tecnico" required>
                        </div>
                    </div>
				</div>
				
                <div class="row">				
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="objetivo">Objetivo</label>
                            <textarea class="form-control" name="objetivo" cols="10" rows="5"></textarea>
                        </div>
                    </div>
					 <div class="col-md-4">
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" name="descripcion" cols="10" rows="5"></textarea>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" cols="10" rows="5"></textarea>
                        </div>
                    </div>
				</div>
               
				
				<div class="row">
					<table style="color:#3a3d44; margin-top: -10px; page-break-after:always;" >
						<tr>
							<td style="width: 50%;">
								 <h3 style="font-weight:bold;">
									<span class="material-symbols-outlined">checklist</span>
									Público Objetivo
								</h3>
							</td>
							<td style="width: 50%; display:none" id="gv1">
								<h3 style="font-weight:bold; " >
									<span class="material-symbols-outlined">checklist</span>
									Grupo Vulnerable
								</h3>
							</td>
						</tr>
						
						<tr>
							<td style="width: 50%;">
								<div id="inputFormRow" style="width: 100%;">	
									<div class="col-md-7">
										<div class="form-group">
											<select class="form-control" name="id_cat_publico_objetivo[]" onchange="showInp(this.value)">
													<option value="">Escoge una opción</option>
													<?php foreach ($cat_publico_objetivo as $datos) : ?>
														<option value="<?php echo $datos['id_cat_publico_objetivo']; ?>"><?php echo ($datos['descripcion']); ?></option>
													<?php endforeach; ?>
												</select>
											
										</div>
									</div>
									<div class="col-md-2">
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
							<td style="width: 50%; display:none" id="gv2">
								<div id="inputFormRow2" style="width: 100%;">			
									<div class="col-md-7">
										<div class="form-group">
											<select class="form-control" name="id_cat_grupo_vuln[]">
													<option value="">Escoge una opción</option>
													<?php foreach ($cat_grupos_vuln as $datos) : ?>
														<option value="<?php echo $datos['id_cat_grupo_vuln']; ?>"><?php echo ($datos['descripcion']); ?></option>
													<?php endforeach; ?>
												</select>
											
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
										<button type="button" class="btn btn-success" id="addRow2" name="addRow2" >
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
											  <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
											  <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
											</svg>
										</button>
											
										</div>
									</div>	
								</div>
								<br>
								<div class="row" id="newRow2" style="width: 100%;">
								</div>
							</td>
						</tr>						
					</table>
				</div>
					
                <div class="form-group clearfix">
                    <a href="cursos_diplomados.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_cursosdip" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>