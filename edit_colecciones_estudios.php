<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Colección';
require_once('includes/load.php');

$e_detalle = find_by_id('colecciones_estudios', (int)$_GET['id'], 'id_colecciones_estudios');
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$areas_all = find_all('area');
$inticadores_pat = find_all_pat_area(35,'colecciones_estudios');

?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_colecciones_estudios'])) {
    

    if (empty($errors)) {
		$id_colecciones_estudios = $e_detalle['id_colecciones_estudios'];
        $nombre_coleccion   = remove_junk($db->escape($_POST['nombre_coleccion']));
        $id_area_responsable   = remove_junk($db->escape($_POST['id_area_responsable']));
        $nombre_responsable   = remove_junk($db->escape($_POST['nombre_responsable']));
        $tipo_publicacion   = remove_junk($db->escape($_POST['tipo_publicacion']));
        $temporalidad_proyecto   = remove_junk($db->escape($_POST['temporalidad_proyecto']));
        $hipervinculo_proyecto   = remove_junk(($db->escape($_POST['hipervinculo_proyecto'])));        
        $observaciones   = remove_junk(($db->escape($_POST['observaciones'])));
        $id_indicadores_pat   = remove_junk(($db->escape($_POST['id_indicadores_pat'])));
        $fecha_publicacion   = remove_junk(($db->escape($_POST['fecha_publicacion'])));

       	   
            $query = "UPDATE colecciones_estudios SET ";
            $query .= "nombre_coleccion='{$nombre_coleccion}',
			fecha_publicacion='{$fecha_publicacion}',
			id_indicadores_pat='{$id_indicadores_pat}',
			id_area_responsable='{$id_area_responsable}',
			nombre_responsable='{$nombre_responsable}',
			tipo_publicacion='{$tipo_publicacion}',
			temporalidad_proyecto='{$temporalidad_proyecto}',
			hipervinculo_proyecto='{$hipervinculo_proyecto}',
			observaciones='{$observaciones}' 
			WHERE id_colecciones_estudios=".$id_colecciones_estudios;		
            

    $result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
				//sucess
				$session->msg('s', "La Colección de Estudios ha sido actualizada con éxito.");
				insertAccion($user['id_user'], '"' . $user['username'] . '" edito La Colección de Estudios, Folio: ' . $folio . '.', 1);
				redirect('colecciones_estudios.php', false);
			
        } else {
            //failed
            $session->msg('d', ' No se pudo editar La Colección de Estudios.');
            redirect('edit_colecciones_estudios.php?id='.(int)$id_colecciones_estudios, false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_colecciones_estudios.php?id='.(int)$id_colecciones_estudios, false);
    }
}
?>

<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar Coleción de Estudios -<?php echo remove_junk($e_detalle['fecha_publicacion']); ?>-</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_colecciones_estudios.php?id=<?php echo (int)$e_detalle['id_colecciones_estudios']; ?>" enctype="multipart/form-data">
                <div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label for="tipo_publicacion">Tipo Publicación</label>
							<select class="form-control" name="tipo_publicacion" required>
									<option value="">Escoge una opción</option>								
										<option <?php if ($e_detalle['tipo_publicacion'] == 'Revista') echo 'selected="selected"'; ?> value="Revista">Revista</option>
										<option <?php if ($e_detalle['tipo_publicacion'] == 'Libro') echo 'selected="selected"'; ?>   value="Libro">Libro</option>
								</select>
							
						</div>
					</div>				
					<div class="col-md-2">
						<div class="form-group">
							<label for="temporalidad_proyecto">Temporalidad de Proyecto</label>
							<select class="form-control" name="temporalidad_proyecto" required>
									<option value="">Escoge una opción</option>					
										<option <?php if ($e_detalle['temporalidad_proyecto'] == 'Mensual') echo 'selected="selected"'; ?> value="Mensual">Mensual</option>
										<option <?php if ($e_detalle['temporalidad_proyecto'] == 'Bimensual') echo 'selected="selected"'; ?> value="Bimensual">Bimensual</option>
										<option <?php if ($e_detalle['temporalidad_proyecto'] == 'Trimestral') echo 'selected="selected"'; ?> value="Trimestral">Trimestral</option>
										<option <?php if ($e_detalle['temporalidad_proyecto'] == 'Semestral') echo 'selected="selected"'; ?> value="Semestral">Semestral</option>
										<option <?php if ($e_detalle['temporalidad_proyecto'] == 'Anual') echo 'selected="selected"'; ?> value="Anual">Anual</option>
										<option <?php if ($e_detalle['temporalidad_proyecto'] == 'Sexenal') echo 'selected="selected"'; ?> value="Sexenal">Sexenal</option>
								</select>
							
						</div>
					</div>						
						<div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_publicacion">Fecha de Publicación<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_publicacion" value="<?php echo remove_junk($e_detalle['fecha_publicacion']); ?>" required>
                        </div>
                    </div>							
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_coleccion">Nombre Colección</label>
                            <input type="text" class="form-control" name="nombre_coleccion" placeholder="Nombre Colección" value="<?php echo remove_junk($e_detalle['nombre_coleccion']); ?>" required>
                        </div>
                    </div>
												
					<div class="col-md-4">
						<div class="form-group">
							<label for="id_area_responsable">Área Responsable</label>
							<select class="form-control" name="id_area_responsable" required>
									<option value="">Escoge una opción</option>
									<?php foreach ($areas_all as $datos) : ?>
										<option <?php if ($e_detalle['id_area_responsable'] === $datos['id_area']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_area']; ?>"><?php echo $datos['nombre_area']; ?></option>
									<?php endforeach; ?>
								</select>
							
						</div>
					</div>	
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_responsable">Nombre del Responsable</label>
                            <input type="text" class="form-control" name="nombre_responsable" value="<?php echo remove_junk($e_detalle['nombre_responsable']); ?>" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" >
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option <?php if ($e_detalle['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?>  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
				</div>
			
                <div class="row">									
					<div class="col-md-6">
                        <div class="form-group">
                            <label for="hipervinculo_proyecto">Hipervinculo al Documento</label>
                            <textarea class="form-control" name="hipervinculo_proyecto" cols="10" rows="2"><?php echo remove_junk($e_detalle['hipervinculo_proyecto']); ?> </textarea>
                        </div>
                    </div>
					<div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" cols="10" rows="3"><?php echo remove_junk($e_detalle['observaciones']); ?> </textarea>
                        </div>
                    </div>
				</div>
               				
					
                <div class="form-group clearfix">
                    <a href="colecciones_estudios.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_colecciones_estudios" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>