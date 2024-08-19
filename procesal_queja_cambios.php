<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Calificación de Queja';
require_once('includes/load.php');
?>
<?php
$e_detalle = find_by_id_queja((int) $_GET['id']);

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];


$cat_medios_pres = find_all_medio_pres();
$cat_autoridades = find_all_aut_res();
$cat_quejosos = find_all_quejosos();
$users = find_all('users');
$area = find_all_areas_quejas();
$cat_municipios = find_all_cat_municipios();
$cat_derecho_vuln = find_all_derecho_vuln();
$cat_derecho_gral = find_all_derecho_gral();
$cat_est_procesal = find_all('cat_est_procesal');

$derecho_vulnerado = find_by_violentados('rel_queja_der_vuln', 'cat_der_vuln', $e_detalle['id_queja_date'],0);
$derecho_general = find_by_violentados('rel_queja_der_gral', 'cat_derecho_general', $e_detalle['id_queja_date'],0);

$existe=0;


if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    redirect('home.php');
}
if ($nivel == 4) {
    redirect('home.php');
}
if ($nivel == 5) {
    page_require_level(5);
}
if ($nivel == 6) {
    redirect('home.php');
}
if ($nivel == 7) {
    page_require_level(7);
}
?>
<?php
if (isset($_POST['procesal_queja'])) {

    if (empty($errors)) {
        $id = (int) $e_detalle['id_queja_date'];
       
        
        $id_cat_der_vuln = $_POST['id_cat_derecho_vuln'];
        $id_cat_derecho_general = $_POST['id_cat_derecho_general'];
		$reclasificacion = $_POST['reclasificacion'] == 'on' ? 1 : 0;
		

        $folio_editar = $e_detalle['folio_queja'];

       if($reclasificacion === 0){
		/*for ($i = 0; $i < sizeof($id_cat_derecho_general); $i = $i + 1) {
			if($id_cat_derecho_general[$i] > 0){
				$query = "INSERT INTO rel_queja_der_gral (id_queja_date, id_cat_derecho_general) VALUES($id, $id_cat_derecho_general[$i]);";			        
				if ($db->query($query)) {
					//echo "<p>Denuncia-denunciado insertada con éxito</p>";		
				 }
			}
		}
		for ($i = 0; $i < sizeof($id_cat_der_vuln); $i = $i + 1) {
			if($id_cat_der_vuln[$i] > 0){
				$query = "INSERT INTO rel_queja_der_vuln (id_queja_date, id_cat_der_vuln) VALUES($id, $id_cat_der_vuln[$i]);";       
				if ($db->query($query)) {
					//echo "<p>Denuncia-denunciado insertada con éxito</p>";		
				 }
			}
		}*/
	
	 $session->msg('s', " Los datos de los acuerdos se han sido agregado con éxito.");
        insertAccion($user['id_user'], '\"' . $user['username'] . '\" actualizó los Derechos Presuntamente Violentados del expediene ' . $folio_editar . '.', 2);
        redirect('procesal_queja.php?id=' . (int) $e_detalle['id_queja_date'], false);
	   }else{
		   $observaciones_reclasificacion = $_POST['observaciones_reclasificacion'];
		    $dbh = new PDO('mysql:host=localhost; dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "INSERT INTO rel_queja_reclasificacion (id_queja_date,fecha_reclasificacion,observaciones_reclasificacion) 
                    VALUES ('{$id}',NOW(),'{$observaciones_reclasificacion}')";
        //------------------BUSCA EL ID INSERTADO------------------        
        $dbh->exec($query);
        $id_rel_queja_reclasificacion = $dbh->lastInsertId();
		   
		for ($i = 0; $i < sizeof($id_cat_derecho_general); $i = $i + 1) {
			if($id_cat_derecho_general[$i] > 0){
				$query = "INSERT INTO rel_queja_der_gral (id_queja_date, id_cat_derecho_general,id_rel_queja_reclasificacion) VALUES($id, $id_cat_derecho_general[$i],$id_rel_queja_reclasificacion);";			        
				if ($db->query($query)) {
					//echo "<p>Denuncia-denunciado insertada con éxito</p>";		
				 }
			}
		}
		for ($i = 0; $i < sizeof($id_cat_der_vuln); $i = $i + 1) {
			if($id_cat_der_vuln[$i] > 0){
				$query = "INSERT INTO rel_queja_der_vuln (id_queja_date, id_cat_der_vuln,id_rel_queja_reclasificacion) VALUES($id, $id_cat_der_vuln[$i],$id_rel_queja_reclasificacion);";       
				if ($db->query($query)) {
					//echo "<p>Denuncia-denunciado insertada con éxito</p>";		
				 }
			}
		}   
		   
		$session->msg('s', " Es una reclasificacion.".$id_rel_queja_reclasificacion);
        redirect('procesal_queja.php?id=' . (int) $e_detalle['id_queja_date'], false);
	   }
        
    } else {
        $session->msg("d", $errors);
        redirect('procesal_queja.php?id=' . (int) $e_detalle['id_queja_date'], false);
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
				html += '						<select class="form-control" name="id_cat_derecho_general[]" id="id_cat_derecho_general">';
				html += '							<option value="">Seleccione el Derecho General</option>';
							<?php foreach ($cat_derecho_gral as $derecho_gral) : ?>
										html += '<option value="<?php echo $derecho_gral['id_cat_derecho_general']; ?>"><?php echo ucwords($derecho_gral['descripcion']); ?></option>';
							 <?php endforeach; ?>
				html += '						</select>';
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
		
		$("#addRow2").click(function() {	
			var html = '';
				html += '<div id="inputFormRow2">';				
				html += '	<div class="col-md-7">';
				html += '					<div class="form-group">';
				html += '						<select class="form-control" name="id_cat_derecho_vuln[]" id="id_cat_derecho_vuln">';
				html += '							<option value="">Seleccione el Derecho Violentado</option>';
							<?php foreach ($cat_derecho_vuln as $derecho_vul) : ?>
										html += '<option value="<?php echo $derecho_vul['id_cat_der_vuln']; ?>"><?php echo ucwords($derecho_vul['descripcion']); ?></option>';
							 <?php endforeach; ?>
				html += '						</select>';
				html += '					</div>';
				html += '				</div>';
				html += '	<div class="col-md-4">';
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
			
			$("#reclasificacion").change(function(){
			  if($("#reclasificacion"). prop("checked") == true){
				alert("Activado");
			  }

			})
	
	});
	
	
</script>
</script>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Queja
                    <?php echo $e_detalle['folio_queja']; ?>
                </span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="procesal_queja.php?id=<?php echo (int) $e_detalle['id_queja_date']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_aut">Autoridad Responsable</label>
                            <input type="text" class="form-control" name="id_cat_aut" value="<?php echo remove_junk($e_detalle['nombre_autoridad']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_quejoso">Nombre del Quejoso</label>
                            <input type="text" class="form-control" name="id_cat_quejoso" value="<?php echo remove_junk($e_detalle['nombre_quejoso'] . " " . $e_detalle['paterno_quejoso'] . " " . $e_detalle['materno_quejoso']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area_asignada">Área a la que se asignó la queja</label>
                            <input type="text" class="form-control" name="id_user_asignado" value="<?php foreach ($area as $a) {
                                                                                                        if ($a['id_area'] === $e_detalle['id_area_asignada'])
                                                                                                            echo $a['nombre_area'];
                                                                                                    } ?>" readonly>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_mun">Municipio</label>
                            <input type="text" class="form-control" name="id_cat_mun" value="<?php foreach ($cat_municipios as $municipio) {
                                                                                                    if ($municipio['id_cat_mun'] === $e_detalle['id_cat_mun'])
                                                                                                        echo ucwords($municipio['descripcion']);
                                                                                                } ?>" readonly>
                        </div>
                    </div>
                </div>

                <hr style="height: 1px; background-color: #370494; opacity: 1;">
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7263F0" width="25px" height="25px" viewBox="0 0 24 24" style="margin-top:-0.3%;">
                        <title>arrow-right-circle</title>
                        <path d="M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M6,13H14L10.5,16.5L11.92,17.92L17.84,12L11.92,6.08L10.5,7.5L14,11H6V13Z" />
                    </svg>
                    <span style="font-size: 20px; color: #7263F0">Derechos Presuntamente Violentados</span>
                </strong>
                <div class="row">
				<table style="color:#3a3d44; margin-top: -10px; page-break-after:always;" border=1>
					<tr>
						<td style="width: 50%;"><br><label for="derecho_general">Derecho general <span style="color:red; font-weight:bold;">*</span></label> </td>
						<td style="width: 50%;"><br><label for="derecho_violentado">Derecho violentado <span style="color:red; font-weight:bold;">*</span></label> </td>
					</tr>
										
					<tr>
						<td style="width: 50%;">
					<?php $i=1; foreach ($derecho_general as $general) : ?>
							<div id="inputFormRow" style="width: 100%;">						
								<div class="col-md-7">
									<div class="form-group">									
										<li><?php foreach ($cat_derecho_gral as $datos) {
													if ($datos['id_cat_derecho_general'] === $general['id_cat_derecho_general'])
                                                            echo ucwords($datos['descripcion']);
											} ?>		
										</li>
									</div>
								</div>								
							</div>
							<?php $existe=1; $i++; endforeach; 
								if($i==1){?>
								<div id="inputFormRow" style="width: 100%;">						
								<div class="col-md-7">
									<div class="form-group">
										
										<select class="form-control" name="id_cat_derecho_general[]" id="id_cat_derecho_general" required>
											<option value="">Seleccione el Derecho General</option>
											<?php foreach ($cat_derecho_gral as $datos) : ?>
												<option  value="<?php echo $datos['id_cat_derecho_general']; ?>" >
													<?php echo ucwords($datos['descripcion']); ?></option>
											<?php endforeach; ?>
										</select>
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
								<?php }?>
							
						</td>
						<td style="width: 50%;">
						<?php $i=1; foreach ($derecho_vulnerado as $general) : ?>
							<div id="inputFormRow2">						
								<div class="col-md-7">
									<div class="form-group">
										<li><?php foreach ($cat_derecho_vuln as $datos) {
													if ($datos['id_cat_der_vuln'] === $general['id_cat_der_vuln'])
                                                            echo ucwords($datos['descripcion']);
											} ?>		
										</li>
										
										
									</div>
								</div>							
							</div>
							<?php $existe=1; $i++; endforeach; if($i==1){?>
							<div id="inputFormRow2">						
								<div class="col-md-7">
									<div class="form-group">										
										<select class="form-control" name="id_cat_derecho_vuln[]" id="id_cat_derecho_vuln" required>
											<option value="">Seleccione el Derecho Violentado</option>
											<?php foreach ($cat_derecho_vuln as $derecho_vuln) : ?>
												<option value="<?php echo $derecho_vuln['id_cat_der_vuln']; ?>" >

													<?php echo ucwords($derecho_vuln['descripcion']); ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<?php if($i==1){?>
								<div class="col-md-4">
									<div class="form-group">
									<button type="button" class="btn btn-success" id="addRow2" name="addRow2" >
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
										  <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
										  <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
										</svg>
									</button>										
									</div>
								</div>	
								<?php }else{ ?>
									<div class="col-md-4">
										<button type="button" class="btn btn-outline-danger" id="removeRow2" > 
											<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">
												<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
												<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>
											</svg>
										</button>		
									</div>
								<?php } ?>
							</div>
							<br>
							<div class="row" id="newRow2" style="width: 100%;">
							</div>	
							<?php }?>							
						</td>
					</tr>
				</div>

				</table>
                    <br><br><br><br>
                </div>

<?php if($existe == 1){ ?>    

						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="reclasificacion">¿Existe una Reclasificación?</label><br>
									<label class="switch" style="float:left;">
										<div class="row">
											<input type="checkbox" id="reclasificacion" name="reclasificacion" >
											<span class="slider round"></span>
											<div>
												<p style="margin-left: 150%; margin-top: -3%; font-size: 14px;">No/Sí</p>
											</div>
										</div>
									</label>
								</div>
							</div>
						</div>
						<br>

<?php } ?>   
<div class="row" id="reclasi">
		<table style="color:#3a3d44; margin-top: -10px; page-break-after:always;" id="reclasi">
					<tr>
						<td style="width: 100%;" colspan="2">
								<div class="col-md-7">
								<div class="form-group">
									<label for="observaciones_reclasificacion">Observaciones de Reclasificación</label>
									<textarea class="form-control" name="observaciones_reclasificacion" id="observaciones_reclasificacion" cols="10" rows="3" required></textarea>
								</div>
							</div>
						</td>
					</tr>	
					<tr>
						<td style="width: 50%;"><br><label for="derecho_general">Derecho general <span style="color:red; font-weight:bold;">*</span></label> </td>
						<td style="width: 50%;"><br><label for="derecho_violentado">Derecho violentado <span style="color:red; font-weight:bold;">*</span></label> </td>
					</tr>
										
					<tr>
						<td style="width: 50%;">
					
								<div id="inputFormRow" style="width: 100%;">						
								<div class="col-md-7">
									<div class="form-group">
										
										<select class="form-control" name="id_cat_derecho_general[]" id="id_cat_derecho_general" >
											<option value="">Seleccione el Derecho General</option>
											<?php foreach ($cat_derecho_gral as $datos) : ?>
												<option  value="<?php echo $datos['id_cat_derecho_general']; ?>" >
													<?php echo ucwords($datos['descripcion']); ?></option>
											<?php endforeach; ?>
										</select>
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
					
							<div id="inputFormRow2">						
								<div class="col-md-7">
									<div class="form-group">										
										<select class="form-control" name="id_cat_derecho_vuln[]" id="id_cat_derecho_vuln" >
											<option value="">Seleccione el Derecho Violentado</option>
											<?php foreach ($cat_derecho_vuln as $derecho_vuln) : ?>
												<option value="<?php echo $derecho_vuln['id_cat_der_vuln']; ?>" >

													<?php echo ucwords($derecho_vuln['descripcion']); ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>
								<div class="col-md-4">
									<div class="form-group">
									<button type="button" class="btn btn-success" id="addRow2" name="addRow2" >
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
										  <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
										  <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
										</svg>
									</button>										
									</div>
								</div>	
							
							<br>
							<div class="row" id="newRow2" style="width: 100%;">
							</div>	
						</td>
					</tr>
				</div>

				</table>			
</div> 				
                <div class="form-group clearfix">
                    <a href="quejas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="procesal_queja" class="btn btn-primary" value="subir">Guardar</button>
                </div>
        </div>
        </form>
    </div>
</div>
</div>
<script>
</script>
<?php include_once('layouts/footer.php'); ?>