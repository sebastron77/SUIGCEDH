
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Datos Firma Documento';
require_once('../includes/load.php');

header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_cursosdip'])) {
    

    if (empty($errors)) {
        
      
    } else {
        $session->msg("d", $errors);
        redirect('form_datos.php', false);
    }
}
?>
<?php header('Content-type: text/html; charset=utf-8');
include_once('../layouts/header.php'); ?>
<script type="text/javascript">	

</script>


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