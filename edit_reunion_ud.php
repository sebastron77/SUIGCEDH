<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Editar Actividad Especial';
require_once('includes/load.php');

$reunion = find_by_id('reuniones_trabajo_ud', (int)$_GET['id'], 'id_reuniones_trabajo_ud');
$a_asistentes = find_by_asistenes( (int)$reunion['id_reuniones_trabajo_ud']);
$cat_genero = find_all('cat_genero');

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) :
    page_require_level(2);
endif;
if ($nivel_user == 7) :
    page_require_level_exacto(7);
endif;
if ($nivel_user == 12) :
    page_require_level_exacto(12);
endif;
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 12) :
    redirect('home.php');
endif;
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_actividad'])) {

    if (empty($errors)) {
		$id = (int)$reunion['id_reuniones_trabajo_ud'];
        $fecha_reunion = remove_junk($db->escape($_POST['fecha_reunion']));
		$hora_reunion   = remove_junk($db->escape($_POST['hora_reunion']));
        $lugar_reunion = remove_junk($db->escape($_POST['lugar_reunion']));
        $quien_atendio = remove_junk($db->escape($_POST['quien_atendio']));
        $no_asistentes = remove_junk($db->escape($_POST['no_asistentes']));
        $acciones_realizar = remove_junk($db->escape($_POST['acciones_realizar']));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
		
		$nombre_participante = $_POST['nombre_participante'];
        $id_cat_gen = $_POST['id_cat_gen'];
        $institucion_participante = $_POST['institucion_participante'];
		
		 $sql = "UPDATE reuniones_trabajo_ud SET 
			fecha_reunion='{$fecha_reunion}', 
			hora_reunion='{$hora_reunion}', 
			lugar_reunion='{$lugar_reunion}', 
			quien_atendio='{$quien_atendio}', 
			no_asistentes='{$no_asistentes}', 
			acciones_realizar='{$acciones_realizar}', 
			observaciones='{$observaciones}'			
			WHERE id_reuniones_trabajo_ud='{$db->escape($id)}'";
			
          
			$result = $db->query($sql);
				if ($result && $db->affected_rows() === 1) {
				insertAccion($user['id_user'], '"' . $user['username'] . '" edito la Reunion de Trabajo de la Unidad de Desaparecidos.Folio: -' . $reunion['folio'], 2);
			}
			$query = "DELETE FROM rel_reuniones_instituciones_ud WHERE id_reuniones_trabajo_ud =" . $id;
			$result = $db->query($query);
        
			for ($i = 0; $i < sizeof($nombre_participante); $i = $i + 1) {
				if($nombre_participante[$i] != ''){
					$queryInsert = "INSERT INTO rel_reuniones_instituciones_ud (id_reuniones_trabajo_ud,nombre_participante,id_cat_gen,institucion_participante) VALUES('$id','$nombre_participante[$i]','$id_cat_gen[$i]','$institucion_participante[$i]')";
					if ($db->query($queryInsert)) {
							insertAccion($user['id_user'], '"'.$user['username'].'" agregó una Institución participante en Reunion de Trabajo de la Unidad de Desaparecidos del Folio: '.$reunion['folio'].'- Institusión '.$institucion_participante[$i].',', 1);
					} else {
						//echo 'falla';
					}
				}		
			}         
				$session->msg('s', " La Reunion de Trabajo de la Unidad de Desaparecidos con folio '" . $reunion['folio'] . "' ha sido acuatizado con éxito.");
				redirect('reuniones_ud.php', false);
       
    } else {
        $session->msg("d", $errors);
        redirect('reuniones_ud.php', false);
    }
}
?>
<script type="text/javascript">	
		
	$(document).ready(function() {
		
		
		$("#addRow").click(function() {	
			var html = '';
				html += '<div id="inputFormRow">';
				html += '	<div class="col-md-3">';
				html += '		<input class="form-control" style="font-size:15px;" name="nombre_participante[]" type="text">';
				html += '	</div>';
				html += '	<div class="col-md-2">';
				html += '		<select class="form-control" name="id_cat_gen[]">';
                html += '                <option value="">Escoge una opción</option>';
                                <?php foreach ($cat_genero as $genero) : ?>
                html += '                   <option value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>';
                                <?php endforeach; ?>
                html += '            </select>';
				html += '	</div>';
				html += '	<div class="col-md-3">';
				html += '		<input type="text" class="form-control" name="institucion_participante[]" >';
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
		
		
		$(document).on('click', '#removeRow', function() {
				$(this).closest('#inputFormRow').remove();
			});
	
	});
	
	
</script>
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar Reunión de Trabajo <?php echo ucwords($reunion['folio']); ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_reunion_ud.php?id=<?php echo (int)$reunion['id_reuniones_trabajo_ud']; ?>" >
               <div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label for="fecha">Fecha</label><br>
						<input type="date" class="form-control" name="fecha_reunion" value="<?php echo ucwords($reunion['fecha_reunion']); ?>" required>
					</div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="hora">Hora</label><br>
                            <input type="time" class="form-control" name="hora_reunion" value="<?php echo ucwords($reunion['hora_reunion']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="lugar">Lugar</label>
                            <input type="text" class="form-control" name="lugar_reunion" value="<?php echo ucwords($reunion['lugar_reunion']); ?>" required>
                        </div>
                    </div>         
					<div class="col-md-3">
						<div class="form-group">
							<label for="quien_atendio">¿Quién Atendió?</label>
							<input type="text" class="form-control" name="quien_atendio" value="<?php echo ucwords($reunion['quien_atendio']); ?>" required>
						</div>
					</div>
				</div>
                           
				 <div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="no_asistentes">No.Personas Asistentes</label>
							<input type="number" class="form-control" min="1" max="1300" maxlength="4" name="no_asistentes" value="<?php echo ucwords($reunion['no_asistentes']); ?>" required >
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="form-group">
							<label for="acciones">Acciones a Realizar Derivadas de la Reunión</label>
							<textarea class="form-control" name="acciones_realizar" cols="8" rows="4"><?php echo ucwords($reunion['acciones_realizar']); ?></textarea>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="observaciones">Observaciones</label>
							<textarea class="form-control" name="observaciones" cols="8" rows="4"><?php echo ucwords($reunion['observaciones']); ?></textarea>
						</div>
					</div>
				</div>
				<div class="panel-heading">
					<strong>
						<span class="glyphicon glyphicon-user"></span>
						<span>Asistentes a la Reunion</span>
					</strong>
				</div>
		
		
				<div class="row">
		<div id="inputFormRow">
			<div class="col-md-3">
					<div class="form-group">
                            <label for="nombre_participante">Nombre</label>
					</div>
				</div>
				 <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_gen">Género</label>
                        </div>
                    </div>
				<div class="col-md-3">
					<div class="form-group">
                            <label for="institucion_participante">Nombre de la Institución</label>					
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
		</div>
		<?php $num=1;foreach ($a_asistentes as $detalle) : ?>
		<div class="row">
		<div id="inputFormRow">
				<div class="col-md-3">
					<div class="form-group">
						<input type="text" class="form-control" name="nombre_participante[]"  value="<?php echo ucwords($detalle['nombre_participante']); ?>">						
					</div>
				</div>
				<div class="col-md-2">
						<div class="form-group">
							<select class="form-control" name="id_cat_gen[]">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_genero as $genero) : ?>
                                    <option <?php if ($detalle['id_cat_gen'] === $genero['id_cat_gen']) echo 'selected="selected"'; ?>  value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>							
						</div>
					</div>
				<div class="col-md-3">
					<div class="form-group">
						<input type="text" class="form-control" name="institucion_participante[]"  value="<?php echo ucwords($detalle['institucion_participante']); ?>">						
					</div>
				</div>
				<div class="col-md-2">
				<button type="button" class="btn btn-outline-danger" id="removeRow" > 
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">
				<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
				<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>
				</svg>
					</button>		
				</div>
		</div>
		</div>
			<?php $num ++; endforeach; 
			if($num==1){?>
			
			<div class="row">
				<div id="inputFormRow">
					<div class="col-md-3">
						<div class="form-group">
							<input type="text" class="form-control" name="nombre_participante[]" >						
						</div>
					</div>
					 <div class="col-md-2">
							<div class="form-group">
								<select class="form-control" name="id_cat_gen[]">
									<option value="">Escoge una opción</option>
									<?php foreach ($cat_genero as $genero) : ?>
										<option value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					<div class="col-md-3">
						<div class="form-group">
							<input type="text" class="form-control" name="institucion_participante[]" >						
						</div>
					</div>
					
					<div class="col-md-4">
						
					</div>	
				</div>
		</div>
<?php
			}
?>				
		
		<div class="row" id="newRow">
		</div>	
		<br><br>		
					
                                                  
               
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="reuniones_ud.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="edit_actividad" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>