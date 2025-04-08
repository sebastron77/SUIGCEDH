<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Reunión de Vinculación';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) {
	page_require_level(2);
}
if ($nivel_user == 6) {
	page_require_level_exacto(6);
}

if ($nivel_user == 17) {
    page_require_level_exacto(17);
}
if ($nivel_user == 24) {
	page_require_level_exacto(24);
}

if ($nivel_user == 36) {
	page_require_level_exacto(36);
}

if ($nivel_user > 2 && $nivel_user < 6) :
	redirect('home.php');
endif;
if ($nivel_user > 6 && $nivel_user < 24) :
	redirect('home.php');
endif;
if ($nivel_user > 24 && $nivel_user < 36) :
	redirect('home.php');
endif;
if ($nivel_user > 36 && $nivel_user < 53) :
	redirect('home.php');
endif;

$inticadores_pat = find_all_pat_area(39, 'reuniones_vinculacion');
$a_reunion_vinculacion = find_by_id('reuniones_vinculacion', (int)$_GET['id'], 'id_reuniones_vinculacion');
$rel_reuniones_vinculacion_asistentes = find_by_participantesReunVin($a_reunion_vinculacion['id_reuniones_vinculacion']);
$tipo = $a_reunion_vinculacion['area'];
$area = isset($_GET['a']) ? $_GET['a'] : '0';
?>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['edit_reuniones_vinculacion'])) {
	if (empty($errors)) {
		$id = (int)$a_reunion_vinculacion['id_reuniones_vinculacion'];
		$nombre_reunion   = remove_junk($db->escape($_POST['nombre_reunion']));
		$fecha_reunion   = remove_junk($db->escape($_POST['fecha_reunion']));
		$lugar_reunion   = remove_junk(($db->escape($_POST['lugar_reunion'])));
		$modalidad   = remove_junk($db->escape($_POST['modalidad']));
		$numero_asistentes   = remove_junk($db->escape($_POST['numero_asistentes']));
		$alcances_acuerdos   = remove_junk(($db->escape($_POST['alcances_acuerdos'])));
		$observaciones   = remove_junk($db->escape($_POST['observaciones']));
		$id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));
		if ($id_indicadores_pat == '') {
            $id_indicadores_pat = 0;
        } else {
            $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));
        }
		$nombre_participante = $_POST['nombre_participante'];
		$procedencia_participante = $_POST['procedencia_participante'];

		$folio_carpeta = str_replace("/", "-", $a_reunion_vinculacion['folio']);
		$carpeta = 'uploads/reuniones_vinculacion/' . $folio_carpeta;

		if (!is_dir($carpeta)) {
			mkdir($carpeta, 0777, true);
		}

		$lista_asistencia = $_FILES['lista_asistencia']['name'];
		$size = $_FILES['lista_asistencia']['size'];
		$type = $_FILES['lista_asistencia']['type'];
		$temp = $_FILES['lista_asistencia']['tmp_name'];

		$evidencia_reunion = $_FILES['evidencia_reunion']['name'];
        $sizeE = $_FILES['evidencia_reunion']['size'];
        $typeE = $_FILES['evidencia_reunion']['type'];
        $tempE = $_FILES['evidencia_reunion']['tmp_name'];

		$move =  move_uploaded_file($temp, $carpeta . "/" . $lista_asistencia);
		$moveE =  move_uploaded_file($tempE, $carpeta . "/" . $evidencia_reunion);

		$source = 'uploads/index.php';

		if (copy($source, $carpeta . '/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}

		$query = "DELETE FROM rel_reuniones_vinculacion_asistentes WHERE id_reuniones_vinculacion =" . $id;
		$db->query($query);

		for ($i = 0; $i < sizeof($nombre_participante); $i = $i + 1) {
			if ($nombre_participante[$i] !== '') {
				$query = "INSERT INTO rel_reuniones_vinculacion_asistentes (id_reuniones_vinculacion, nombre_participante,procedencia_participante) VALUES($id, '$nombre_participante[$i]','$procedencia_participante[$i]');";
				if ($db->query($query)) {
					//echo "<p>Denuncia-denunciado insertada con éxito</p>";		
				}
			}
		}

		$sql = "UPDATE reuniones_vinculacion SET 
					nombre_reunion='{$nombre_reunion}', 
					fecha_reunion='{$fecha_reunion}', 
					lugar_reunion='{$lugar_reunion}', 
					modalidad='{$modalidad}',
					numero_asistentes='{$numero_asistentes}', 
					alcances_acuerdos='{$alcances_acuerdos}', 
					observaciones='{$observaciones}',  
					id_indicadores_pat='{$id_indicadores_pat}' ";
		if ($lista_asistencia != '') {
			$sql .= "	,lista_asistencia ='{$lista_asistencia}'";
		}
		if ($evidencia_reunion != '') {
			$sql .= "	,evidencia_reunion ='{$evidencia_reunion}'";
		}
		$sql .= "	WHERE id_reuniones_vinculacion='{$db->escape($id)}'";
		$result = $db->query($sql);

		$session->msg('s', "Información sonbre Reunión de Vinculación fue actualizada con exito.");
		insertAccion($user['id_user'], '"' . $user['username'] . '" editó la Reunión de Vinculación, Folio: ' . $a_reunion_vinculacion['folio'] . '.', 2);
		redirect('reuniones_vinculacion.php?t=' . $tipo.'&a='.$area, false);
	} else {
		$session->msg("d", $errors);
		redirect('reuniones_vinculacion.php?t=' . $tipo.'&a='.$area, false);
	}
}
?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#addRow").click(function() {
			var html = '';
			html += '<div id="inputFormRow">';
			html += '	<div class="col-md-4">';
			html += '					<div class="form-group">';
			html += '						<input type="text" class="form-control" name="nombre_participante[]" >';
			html += '					</div>';
			html += '				</div>';
			html += '	<div class="col-md-4">';
			html += '					<div class="form-group">';
			html += '						<input type="text" class="form-control" name="procedencia_participante[]" >';
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
				<span>Editar Reunión de Vinculación <?php echo $a_reunion_vinculacion['folio'] ?></span>
			</strong>
		</div>
		<div class="panel-body">
			<form method="post" action="edit_reuniones_vinculacion.php?id=<?php echo (int)$a_reunion_vinculacion['id_reuniones_vinculacion']; ?>&a=<?php echo $area?>" enctype="multipart/form-data">
				<div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label for="fecha_reunion">Fecha de la Reunión</label><br>
							<input type="date" class="form-control" name="fecha_reunion" value="<?php echo remove_junk(($a_reunion_vinculacion['fecha_reunion'])); ?>" required>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="nombre_reunion">Nombre de la Reunión</label>
							<input type="text" class="form-control" name="nombre_reunion" value="<?php echo remove_junk(($a_reunion_vinculacion['nombre_reunion'])); ?>" required>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="lugar_reunion">Lugar de la Reunión</label>
							<input type="text" class="form-control" name="lugar_reunion" value="<?php echo remove_junk(($a_reunion_vinculacion['lugar_reunion'])); ?>" required>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="modalidad">Modalidad</label>
							<select class="form-control" name="modalidad" required>
								<option value="">Escoge una opción</option>
								<option <?php if ($a_reunion_vinculacion['modalidad'] === 'Presencial') echo 'selected="selected"'; ?> value="Presencial">Presencial</option>
								<option <?php if ($a_reunion_vinculacion['modalidad'] === 'En línea') echo 'selected="selected"'; ?> value="En línea">En línea</option>
								<option <?php if ($a_reunion_vinculacion['modalidad'] === 'Híbrido') echo 'selected="selected"'; ?> value="Híbrido">Híbrido</option>>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="numero_asistentes">No. de asistentes</label>
							<input type="number" min="1" class="form-control" max="1000" name="numero_asistentes" value="<?php echo remove_junk(($a_reunion_vinculacion['numero_asistentes'])); ?>" required>
						</div>
					</div>
					<?php if ($tipo == 'c'): ?>
						<div class="col-md-4">
							<div class="form-group">
								<label for="id_indicadores_pat">Definición del Indicador</label>
								<select class="form-control form-select" name="id_indicadores_pat">
									<option value="0">Selecciona Indicador</option>
									<?php foreach ($inticadores_pat as $datos) : ?>
										<option <?php if ($a_reunion_vinculacion['id_indicadores_pat'] === $datos['id_indicadores_pat']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					<?php endif; ?>
					<div class="col-md-4">
						<div class="form-group">
							<span>
								<label for="lista_asistencia">Lista de Asistencia</label>
								<input id="lista_asistencia" type="file" accept="application/pdf" class="form-control" name="lista_asistencia">
								<label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($a_reunion_vinculacion['lista_asistencia']); ?><?php ?></label>
							</span>
						</div>
					</div>					
					<div class="col-md-4">
                        <div class="form-group">
                            <span>
                                <label for="evidencia_reunion">Evidencia de la Reunion(archivo PDF)</label>
                                <input id="evidencia_reunion" type="file" accept="application/pdf" class="form-control" name="evidencia_reunion" >
								<label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo ($a_reunion_vinculacion['evidencia_reunion']); ?><?php ?></label>
                            </span>
                        </div>
                    </div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="alcances_acuerdos">Avances y Acuerdos</label>
							<textarea class="form-control" name="alcances_acuerdos" id="alcances_acuerdos" cols="10" rows="5"><?php echo remove_junk(($a_reunion_vinculacion['alcances_acuerdos'])); ?></textarea>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="observaciones">Observaciones</label>
							<textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="5"><?php echo remove_junk(($a_reunion_vinculacion['observaciones'])); ?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<table style="color:#3a3d44; margin-top: -10px; page-break-after:always;">
						<tr>
							<td style="width: 50%;">
								<div class="panel-heading">
									<strong>
										<span class="glyphicon glyphicon-option-vertical"></span>
										<span> Miembros/ Participantes</span>
									</strong>
								</div>
							</td>
						</tr>
						<tr>
							<td style="width: 50%;">
								<div style="width: 100%;">
									<div class="col-md-4">
										<div class="form-group">
											<label for="nombre_participante">Nombre Asistente</label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="procedencia_participante">Institución de Procedencia</label>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">

										</div>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td style="width: 50%;">
								<?php $i = 1;
								foreach ($rel_reuniones_vinculacion_asistentes as $general) : ?>
									<div id="inputFormRow" style="width: 100%;">
										<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<input type="text" class="form-control" name="nombre_participante[]" value="<?php echo remove_junk(($general['nombre_participante'])); ?>" required>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="text" class="form-control" name="procedencia_participante[]" value="<?php echo remove_junk(($general['procedencia_participante'])); ?>" required>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<?php if ($i == 1) { ?>

													<button type="button" class="btn btn-success" id="addRow" name="addRow">
														<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
															<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
															<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
														</svg>
													</button>
												<?php } else { ?>
													<button type="button" class="btn btn-outline-danger" id="removeRow">
														<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">
															<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
															<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>
														</svg>
													</button>
												<?php } ?>
											</div>
										</div>
										</div>
									</div>
								<?php $i++;
								endforeach;
								?>
								<br>
								<div class="row" id="newRow" style="width: 100%;">
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="form-group clearfix">
					<a href="reuniones_vinculacion.php?t=<?php echo $tipo ?>&a=<?php echo $area ?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
						Regresar
					</a>
					<button type="submit" name="edit_reuniones_vinculacion" class="btn btn-primary" value="subir">Guardar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php include_once('layouts/footer.php'); ?>