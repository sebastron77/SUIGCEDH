<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Curso/Diplomado';
require_once('includes/load.php');


$e_curso = find_by_id('cursos_diplomados',(int)$_GET['id'],'id_cursos_diplomados');
$cat_ejes = find_all('cat_ejes_estrategicos');
$cat_agendas = find_all('cat_agendas');
$areas_all = find_all('area');
$cat_modalidad = find_all('cat_modalidad');
$cat_publico_objetivo = find_all('cat_publico_objetivo');
$cat_grupos_vuln = find_all('cat_grupos_vuln');
$cat_tipo_actividad = find_all('cat_tipo_actividad');
$cat_categoria_actividad = find_all('cat_categoria_actividad');
$rel_cursos_publico = find_all_elemcursos((int)$_GET['id'],'id_cursos_diplomados','cat_publico_objetivo','rel_cursos_publico','id_cat_publico_objetivo');
$rel_cursos_grupos_vulnerables = find_all_elemcursos((int)$_GET['id'],'id_cursos_diplomados','cat_grupos_vuln','rel_cursos_grupos_vulnerables','id_cat_grupo_vuln');
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_cursosdip'])) {

    $req_fields = array('id_cat_ejes_estrategicos', 'id_cat_agendas', 'nombre_curso', 'fecha_apertura', 'duracion_horas', 'id_area_responsable',  'id_cat_modalidad');
    validate_fields($req_fields);

    if (empty($errors)) {
		$id_cursos_diplomados = (int)$e_curso['id_cursos_diplomados'];
        $id_cat_ejes_estrategicos   = remove_junk($db->escape($_POST['id_cat_ejes_estrategicos']));
        $id_cat_agendas   = remove_junk($db->escape($_POST['id_cat_agendas']));
        $nombre_curso   = remove_junk($db->escape($_POST['nombre_curso']));
        $objetivo   = remove_junk($db->escape($_POST['objetivo']));
        $descripcion   = remove_junk($db->escape($_POST['descripcion']));
        $fecha_apertura   = remove_junk(($db->escape($_POST['fecha_apertura'])));
        $duracion_horas   = remove_junk(($db->escape($_POST['duracion_horas'])));
        $liga_acceso   = remove_junk(($db->escape($_POST['liga_acceso'])));
        $id_area_responsable   = remove_junk(($db->escape($_POST['id_area_responsable'])));
        $nombre_responsable   = remove_junk(($db->escape($_POST['nombre_responsable'])));
        $id_cat_modalidad   = remove_junk(($db->escape($_POST['id_cat_modalidad'])));
        $observaciones   = remove_junk(($db->escape($_POST['observaciones'])));
        $id_cat_tipo_actividad   = remove_junk(($db->escape($_POST['id_cat_tipo_actividad'])));
        $id_cat_categoria_actividad   = remove_junk(($db->escape($_POST['id_cat_categoria_actividad'])));
		
		$id_cat_publico_objetivo = $_POST['id_cat_publico_objetivo'];
		$id_cat_grupo_vuln = $_POST['id_cat_grupo_vuln'];

        $carpeta = 'uploads/cursosdiplomados/' . str_replace("/", "-", $e_curso['folio']);
		
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
		
        
      $sql = "UPDATE cursos_diplomados SET 
	  id_cat_ejes_estrategicos='{$id_cat_ejes_estrategicos}', 
	  id_cat_agendas='{$id_cat_agendas}', 
	  nombre_curso='{$nombre_curso}', 
	  objetivo='{$objetivo}',
	  descripcion='{$descripcion}', 
	  fecha_apertura='{$fecha_apertura}', 
	  duracion_horas='{$duracion_horas}', 
	  liga_acceso='{$liga_acceso}', 
	  id_area_responsable='{$id_area_responsable}', 
	  nombre_responsable='{$nombre_responsable}', 
	  id_cat_modalidad='{$id_cat_modalidad}', 
	  observaciones='{$observaciones}', 
	  id_cat_tipo_actividad='{$id_cat_tipo_actividad}', 
	  id_cat_categoria_actividad='{$id_cat_categoria_actividad}'";
	  if ($fecha_tecnica != '') $sql .=",fecha_tecnica='{$fecha_tecnica}' ";
	  if ($expediente_tecnico != '') $sql .=",expediente_tecnico='{$expediente_tecnico}'  ";	  
	  $sql .="WHERE id_cursos_diplomados='{$db->escape($id_cursos_diplomados)}';";
			
		$result = $db->query($sql);
        if ($result ) {
            $session->msg('s', "Información Actualizada ");
			insertAccion($user['id_user'], '"' . $user['username'] . '" editó el Curso/Diplomado, Folio: ' . $e_curso['folio'] . '.', 2);
			
			// sql to delete a record
			$sqldel = "DELETE FROM rel_cursos_publico WHERE id_cursos_diplomados='{$db->escape($id_cursos_diplomados)}';";
			$db->query($sqldel) ;
			
			$sqldel = "DELETE FROM rel_cursos_grupos_vulnerables WHERE id_cursos_diplomados='{$db->escape($id_cursos_diplomados)}';";
			$db->query($sqldel) ;
			
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
			
			
            redirect('cursos_diplomados.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos.');
            redirect('edit_cursosdip.php?id=' . (int)$e_curso['id_cursos_diplomados'], false);
        }
        
    } else {
        $session->msg("d", $errors);
        redirect('edit_cursosdip.php=id='.(int)$e_curso['id_cursos_diplomados'], false);
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
                <span>Editar Curso/ Diplomado <?php echo $e_curso['folio']; ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_cursosdip.php?id=<?php  echo (int)$e_curso['id_cursos_diplomados'];?>" enctype="multipart/form-data">
                <div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label for="id_cat_ejes_estrategicos">Eje Estrategico</label>
							<select class="form-control" name="id_cat_ejes_estrategicos" required>
									<option value="">Escoge una opción</option>
									<?php foreach ($cat_ejes as $datos) : ?>
										<option <?php if ($e_curso['id_cat_ejes_estrategicos'] === $datos['id_cat_ejes_estrategicos']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_cat_ejes_estrategicos']; ?>"><?php echo $datos['descripcion']; ?></option>
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
										<option <?php if ($e_curso['id_cat_agendas'] === $datos['id_cat_agendas']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_cat_agendas']; ?>"><?php echo $datos['descripcion']; ?></option>
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
										<option  <?php if ($e_curso['id_cat_tipo_actividad'] === $datos['id_cat_tipo_actividad']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_cat_tipo_actividad']; ?>"><?php echo $datos['descripcion']; ?></option>
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
										<option <?php if ($e_curso['id_cat_categoria_actividad'] === $datos['id_cat_categoria_actividad']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_cat_categoria_actividad']; ?>"><?php echo $datos['descripcion']; ?></option>
									<?php endforeach; ?>
								</select>
							
						</div>
					</div>					
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_curso">Nombre Curso/Diplomado</label>
                            <input type="text" class="form-control" name="nombre_curso" placeholder="Nombre Curso/Diplomado" value="<?php echo $e_curso['nombre_curso']; ?>" required>
                        </div>
                    </div>
				</div>
				 <div class="row">				
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_apertura">Fecha Apertura</label><br>
                            <input type="date" class="form-control" name="fecha_apertura" value="<?php echo $e_curso['fecha_apertura']; ?>" required>
                        </div>
                    </div>
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="duracion_horas">Duración en Horas</label>
                            <input type="number"  class="form-control" max="500" name="duracion_horas"  value="<?php echo $e_curso['duracion_horas']; ?>" required>
                        </div>
                    </div>
					  <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_modalidad">Modalidad</label>
                            <select class="form-control" name="id_cat_modalidad" required>
									<option value="">Escoge una opción</option>
									<?php foreach ($cat_modalidad as $datos) : ?>
										<option <?php if ($e_curso['id_cat_modalidad'] === $datos['id_cat_modalidad']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_cat_modalidad']; ?>"><?php echo $datos['descripcion']; ?></option>
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
										<option <?php if ($e_curso['id_area_responsable'] === $datos['id_area']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_area']; ?>"><?php echo $datos['nombre_area']; ?></option>
									<?php endforeach; ?>
								</select>
							
						</div>
					</div>	
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_responsable">Nombre del Responsable</label>
                            <input type="text" class="form-control" name="nombre_responsable" value="<?php echo $e_curso['nombre_responsable']; ?>"  required>
                        </div>
                    </div>
				</div>
				
                <div class="row">
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="liga_acceso">Liga Acceso</label>
                            <input type="text" class="form-control" name="liga_acceso" value="<?php echo $e_curso['liga_acceso']; ?>" >
                        </div>
                    </div>
					
                     <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_tecnica">Ficha Técnica</label>
                            <input type="file" accept="application/pdf" class="form-control" name="fecha_tecnica" id="fecha_tecnica" >
							<label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                <?php echo remove_junk($e_curso['fecha_tecnica']); ?>
                            </label>
                        </div>
                    </div>
				 
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="expediente_tecnico">Expediente Técnico</label>
                            <input type="file" accept="application/pdf" class="form-control" name="expediente_tecnico" id="expediente_tecnico" >
							<label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                <?php echo remove_junk($e_curso['expediente_tecnico']); ?>
                            </label>
                        </div>
                    </div>
				</div>
				
                <div class="row">				
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="objetivo">Objetivo</label>
                            <textarea class="form-control" name="objetivo" cols="10" rows="5"><?php echo $e_curso['objetivo']; ?></textarea>
                        </div>
                    </div>
					 <div class="col-md-4">
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" name="descripcion" cols="10" rows="5"><?php echo $e_curso['descripcion']; ?></textarea>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" cols="10" rows="5"><?php echo $e_curso['observaciones']; ?></textarea>
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
							<td style="width: 50%; <?php echo (!$rel_cursos_grupos_vulnerables? "display:none;":"display:block;")?>" id="gv1">
								<h3 style="font-weight:bold; " >
									<span class="material-symbols-outlined">checklist</span>
									Grupo Vulnerable
								</h3>
							</td>
						</tr>
						
						<tr>
							<td style="width: 50%;">
										<?php $i=1; foreach ($rel_cursos_publico as $elementos) : ?>
								<div id="inputFormRow" style="width: 100%;">
											<div class="col-md-7">
												<div class="form-group">
													<select class="form-control" name="id_cat_publico_objetivo[]" onchange="showInp(this.value)">
															<option value="">Escoge una opción</option>
															<?php foreach ($cat_publico_objetivo as $datos) : ?>
																<option <?php if ($elementos['id_cat_publico_objetivo'] === $datos['id_cat_publico_objetivo']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_cat_publico_objetivo']; ?>"><?php echo ($datos['descripcion']); ?></option>
															<?php endforeach; ?>
														</select>
													
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
												<?php  if($i==1){ ?>
												<button type="button" class="btn btn-success" id="addRow" name="addRow" >
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
													  <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
													  <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
													</svg>
												</button>
												<?php  }else{ ?>
												<button type="button" class="btn btn-outline-danger" id="removeRow" >
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">
														<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
														<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>
													</svg>
												</button>
												<?php  } ?>
													
												</div>
											</div>	
												
								</div>
										<?php $i .= 1; endforeach; ?>
								
								<br>
								<div class="row" id="newRow" style="width: 100%;">
								</div>	

							</td>
							<td style="width: 50%; <?php echo (!$rel_cursos_grupos_vulnerables? "display:none;":"display:block;")?>" id="gv2">
<?php $i=1; foreach ($rel_cursos_grupos_vulnerables as $elementos) : ?>								
								<div id="inputFormRow2" style="width: 100%;">
									<div class="col-md-7">
										<div class="form-group">
											<select class="form-control" name="id_cat_grupo_vuln[]">
													<option value="">Escoge una opción</option>
													<?php foreach ($cat_grupos_vuln as $datos) : ?>
														<option <?php if ($elementos['id_cat_grupo_vuln'] === $datos['id_cat_grupo_vuln']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_cat_grupo_vuln']; ?>"><?php echo ($datos['descripcion']); ?></option>
													<?php endforeach; ?>
												</select>
											
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
										<?php  if($i==1){ ?>
										<button type="button" class="btn btn-success" id="addRow2" name="addRow2" >
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
											  <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
											  <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
											</svg>
										</button>
										<?php  }else{ ?>
												<button type="button" class="btn btn-outline-danger" id="removeRow2" >
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">
														<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
														<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>
													</svg>
												</button>
												<?php  } ?>
											
										</div>
									</div>	
								</div>
<?php $i .= 1; endforeach; ?>									
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
                    <button type="submit" name="edit_cursosdip" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>