<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Curso/Diplomado';
require_once('includes/load.php');


$e_certificacion = find_by_id('certificaciones',(int)$_GET['id'],'id_certificaciones');
$cat_ejes = find_all('cat_ejes_estrategicos');
$cat_agendas = find_all('cat_agendas');
$areas_all = find_all('area');
$cat_publico_objetivo = find_all('cat_publico_objetivo');
$cat_grupos_vuln = find_all('cat_grupos_vuln');
$rel_cursos_publico = find_all_elemcursos((int)$_GET['id'],'id_certificaciones','cat_publico_objetivo','rel_cetificaciones_publico','id_cat_publico_objetivo');
$rel_cursos_grupos_vulnerables = find_all_elemcursos((int)$_GET['id'],'id_certificaciones','cat_grupos_vuln','rel_certificaciones_grupos_vulnerables','id_cat_grupo_vuln');
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_certificaciones'])) {

    $req_fields = array('id_cat_ejes_estrategicos', 'id_cat_agendas', 'nombre_certificacion', 'fecha_inicio_proceso',  'id_area_responsable');
    validate_fields($req_fields);

    if (empty($errors)) {
		$id_certificaciones = (int)$e_certificacion['id_certificaciones'];
		$id_cat_ejes_estrategicos   = remove_junk($db->escape($_POST['id_cat_ejes_estrategicos']));
        $id_cat_agendas   = remove_junk($db->escape($_POST['id_cat_agendas']));
        $nombre_certificacion   = remove_junk($db->escape($_POST['nombre_certificacion']));
        $objetivo_certificacion   = remove_junk($db->escape($_POST['objetivo_certificacion']));
        $emisor_certificacion   = remove_junk($db->escape($_POST['emisor_certificacion']));
        $contacto_certificacion   = remove_junk($db->escape($_POST['contacto_certificacion']));
        $fecha_inicio_proceso   = remove_junk(($db->escape($_POST['fecha_inicio_proceso'])));
        $id_area_responsable   = remove_junk(($db->escape($_POST['id_area_responsable'])));
        $nombre_responsable   = remove_junk(($db->escape($_POST['nombre_responsable'])));
        $observaciones   = remove_junk(($db->escape($_POST['observaciones'])));
		
		$id_cat_publico_objetivo = $_POST['id_cat_publico_objetivo'];
		$id_cat_grupo_vuln = $_POST['id_cat_grupo_vuln'];

        $carpeta = 'uploads/certificaciones/' . str_replace("/", "-", $e_certificacion['folio']);
		
		if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $ficha_tecnica = $_FILES['ficha_tecnica']['name'];
        $size_ft = $_FILES['ficha_tecnica']['size'];
        $type_ft = $_FILES['ficha_tecnica']['type'];
        $temp_ft = $_FILES['ficha_tecnica']['tmp_name'];    

        $move_ft =  move_uploaded_file($temp_ft, $carpeta . "/" . $ficha_tecnica);
		
        $expediente_tecnico = $_FILES['expediente_tecnico']['name'];
        $size_et = $_FILES['expediente_tecnico']['size'];
        $type_et = $_FILES['expediente_tecnico']['type'];
        $temp_et = $_FILES['expediente_tecnico']['tmp_name'];    

        $move_et =  move_uploaded_file($temp_et, $carpeta . "/" . $expediente_tecnico);
		
        
      $sql = "UPDATE certificaciones SET 
	  id_cat_ejes_estrategicos='{$id_cat_ejes_estrategicos}', 
	  id_cat_agendas='{$id_cat_agendas}', 
	  nombre_certificacion='{$nombre_certificacion}', 
	  objetivo_certificacion='{$objetivo_certificacion}',
	  emisor_certificacion='{$emisor_certificacion}',  
	  contacto_certificacion='{$contacto_certificacion}', 
	  fecha_inicio_proceso='{$fecha_inicio_proceso}', 
	  id_area_responsable='{$id_area_responsable}', 
	  nombre_responsable='{$nombre_responsable}', 
	  observaciones='{$observaciones}' ";
	  if ($ficha_tecnica != '') $sql .=",ficha_tecnica='{$ficha_tecnica}' ";
	  if ($expediente_tecnico != '') $sql .=",expediente_tecnico='{$expediente_tecnico}'  ";	  
	  $sql .="WHERE id_certificaciones='{$db->escape($id_certificaciones)}';";
			
		$result = $db->query($sql);
        if ($result ) {
            $session->msg('s', "Información Actualizada ");
			insertAccion($user['id_user'], '"' . $user['username'] . '" editó la Certificación, Folio: ' . $e_certificacion['folio'] . '.', 2);
			
			// sql to delete a record
			$sqldel = "DELETE FROM rel_cetificaciones_publico WHERE id_certificaciones='{$db->escape($id_certificaciones)}';";
			$db->query($sqldel) ;
			
			$sqldel = "DELETE FROM rel_certificaciones_grupos_vulnerables WHERE id_certificaciones='{$db->escape($id_certificaciones)}';";
			$db->query($sqldel) ;
			
			for ($i = 0; $i < sizeof($id_cat_publico_objetivo); $i = $i + 1) {
					if($id_cat_publico_objetivo[$i] !== '' && $id_cat_publico_objetivo[$i] > 0){
						$queryInsert4 = "INSERT INTO rel_cetificaciones_publico (id_certificaciones,id_cat_publico_objetivo) VALUES('$id_certificaciones','$id_cat_publico_objetivo[$i]')";
						$db->query($queryInsert4);
						
						if($id_cat_publico_objetivo[$i] == 4){
							for ($i = 0; $i < sizeof($id_cat_grupo_vuln); $i = $i + 1) {
								if($id_cat_grupo_vuln[$i] !== '' && $id_cat_grupo_vuln[$i] > 0){
									$queryInsert5 = "INSERT INTO rel_certificaciones_grupos_vulnerables (id_certificaciones,id_cat_grupo_vuln) VALUES('$id_certificaciones','$id_cat_grupo_vuln[$i]')";
									$db->query($queryInsert5);									
								}
							}	
						}
					}
				}
			
			
            redirect('certificaciones.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos.');
            redirect('edit_certificaciones.php?id=' . (int)$e_certificacion['id_certificaciones'], false);
        }
        
    } else {
        $session->msg("d", $errors);
        redirect('edit_certificaciones.php=id='.(int)$e_certificacion['id_certificaciones'], false);
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
                <span>Editar Curso/ Diplomado <?php echo $e_certificacion['folio']; ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_certificaciones.php?id=<?php  echo (int)$e_certificacion['id_certificaciones'];?>" enctype="multipart/form-data">
                <div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label for="id_cat_ejes_estrategicos">Eje Estrategico</label>
							<select class="form-control" name="id_cat_ejes_estrategicos" required>
									<option value="">Escoge una opción</option>
									<?php foreach ($cat_ejes as $datos) : ?>
										<option <?php if ($e_certificacion['id_cat_ejes_estrategicos'] === $datos['id_cat_ejes_estrategicos']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_cat_ejes_estrategicos']; ?>"><?php echo $datos['descripcion']; ?></option>
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
										<option <?php if ($e_certificacion['id_cat_agendas'] === $datos['id_cat_agendas']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_cat_agendas']; ?>"><?php echo $datos['descripcion']; ?></option>
									<?php endforeach; ?>
								</select>
							
						</div>
					</div>	
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_certificacion">Nombre Certificado</label>
                            <input type="text" class="form-control" name="nombre_certificacion" placeholder="Nombre Certificado" value="<?php  echo $e_certificacion['nombre_certificacion'];?>" required>
                        </div>
                    </div>
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_inicio_proceso">Fecha de Inicio de la Certificación</label><br>
                            <input type="date" class="form-control" name="fecha_inicio_proceso" value="<?php  echo $e_certificacion['fecha_inicio_proceso'];?>" required>
                        </div>
                    </div>				
					
					
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="emisor_certificacion">Nombre de la Institución o dependencia que emite la certificación</label>
                            <input type="text" class="form-control" name="emisor_certificacion" value="<?php  echo $e_certificacion['emisor_certificacion'];?>" required>
                        </div>
                    </div>
				</div>
				
				<div class="row">				
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="contacto_certificacion">Contacto de la Institución o dependencia certificcadora</label>
                            <input type="text" class="form-control" name="contacto_certificacion" value="<?php  echo $e_certificacion['contacto_certificacion'];?>" required>
                        </div>
                    </div>
					
					
					<div class="col-md-3">
						<div class="form-group">
							<label for="id_area_responsable">Área Responsable</label>
							<select class="form-control" name="id_area_responsable" required>
									<option value="">Escoge una opción</option>
									<?php foreach ($areas_all as $datos) : ?>
										<option <?php if ($datos['id_area'] === $e_certificacion['id_area_responsable']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_area']; ?>"><?php echo $datos['nombre_area']; ?></option>
									<?php endforeach; ?>
								</select>
							
						</div>
					</div>	
					<div class="col-md-5">
                        <div class="form-group">
                            <label for="nombre_responsable">Nombre del Responsable</label>
                            <input type="text" class="form-control" name="nombre_responsable" value="<?php  echo $e_certificacion['nombre_certificacion'];?>" required>
                        </div>
                    </div>
					
				</div>
				
                <div class="row">	
                     <div class="col-md-2">
                        <div class="form-group">
                            <label for="ficha_tecnica">Ficha Técnica</label>
                            <input type="file" accept="application/pdf" class="form-control" name="ficha_tecnica" id="ficha_tecnica" >
							<label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                <?php echo remove_junk($e_certificacion['ficha_tecnica']); ?>
                            </label>
                        </div>
                    </div>
				 
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="expediente_tecnico">Expediente Técnico</label>
                            <input type="file" accept="application/pdf" class="form-control" name="expediente_tecnico" id="expediente_tecnico" >
							<label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                <?php echo remove_junk($e_certificacion['expediente_tecnico']); ?>
                            </label>
                        </div>
                    </div>
				<div class="col-md-4">
                        <div class="form-group">
                            <label for="objetivo">Objetivo Certificado</label>
                            <textarea class="form-control" name="objetivo_certificacion" cols="10" rows="3"><?php  echo $e_certificacion['objetivo_certificacion'];?> </textarea>
                        </div>
                    </div>
					
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" cols="10" rows="3"><?php  echo $e_certificacion['observaciones'];?> </textarea>
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
                    <a href="certificaciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_certificaciones" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>