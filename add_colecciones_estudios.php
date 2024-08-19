<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Colección';
require_once('includes/load.php');

$id_table = last_id_table('colecciones_estudios','id_colecciones_estudios');
$id_folio = last_id_folios();
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$areas_all = find_all('area');
$inticadores_pat = find_all_pat_area(35,'colecciones_estudios');

?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_colecciones_estudios'])) {
    

    if (empty($errors)) {
        $nombre_coleccion   = remove_junk($db->escape($_POST['nombre_coleccion']));
        $fecha_publicacion   = remove_junk($db->escape($_POST['fecha_publicacion']));
        $id_area_responsable   = remove_junk($db->escape($_POST['id_area_responsable']));
        $nombre_responsable   = remove_junk($db->escape($_POST['nombre_responsable']));
        $tipo_publicacion   = remove_junk($db->escape($_POST['tipo_publicacion']));
        $temporalidad_proyecto   = remove_junk($db->escape($_POST['temporalidad_proyecto']));
        $hipervinculo_proyecto   = remove_junk(($db->escape($_POST['hipervinculo_proyecto'])));        
        $observaciones   = remove_junk(($db->escape($_POST['observaciones'])));
        $id_indicadores_pat   = remove_junk(($db->escape($_POST['id_indicadores_pat'])));

        if (count($id_table) == 0) {
            $nuevo_id_ori_canal = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_table as $nuevo) {
                $nuevo_id_ori_canal = (int) $nuevo['id_colecciones_estudios'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['id_colecciones_estudios'] + 1);
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
        $folio = 'CEDH/' . $no_folio . '/' . $year . '-COLES';
	   
            $query = "INSERT INTO colecciones_estudios (";
            $query .= "folio,nombre_coleccion,fecha_publicacion,id_area_responsable,nombre_responsable,tipo_publicacion,temporalidad_proyecto,hipervinculo_proyecto,observaciones,id_user_creador,fecha_creacion";		
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$nombre_coleccion}','{$fecha_publicacion}','{$id_area_responsable}','{$nombre_responsable}','{$tipo_publicacion}','{$temporalidad_proyecto}','{$hipervinculo_proyecto}','{$observaciones}','{$id_user}',NOW()";			
            $query .= ")";
//echo $query;
            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio}'";
            $query2 .= ")";
			
    if ($db->query($query) && $db->query($query2)) {
				//sucess
				$session->msg('s', "La Colección de Estudios ha sido agregada con éxito.");
				insertAccion($user['id_user'], '"' . $user['username'] . '" agregó La Colección de Estudios, Folio: ' . $folio . '.', 1);
				redirect('colecciones_estudios.php', false);
			
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar La Colección de Estudios.');
            redirect('add_colecciones_estudios.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_colecciones_estudios.php', false);
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
                <span>Agregar Coleción de Estudios</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_colecciones_estudios.php" enctype="multipart/form-data">
                <div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label for="tipo_publicacion">Tipo Publicación <span style="color:red;font-weight:bold">*</span></label></label>
							<select class="form-control" name="tipo_publicacion" required>
									<option value="">Escoge una opción</option>								
										<option value="Revista">Revista</option>
										<option value="Libro">Libro</option>
								</select>
							
						</div>
					</div>				
					<div class="col-md-2">
						<div class="form-group">
							<label for="temporalidad_proyecto">Temporalidad de Proyecto <span style="color:red;font-weight:bold">*</span></label></label>
							<select class="form-control" name="temporalidad_proyecto" required>
									<option value="">Escoge una opción</option>					
										<option value="Mensual">Mensual</option>
										<option value="Bimensual">Bimensual</option>
										<option value="Trimestral">Trimestral</option>
										<option value="Semestral">Semestral</option>
										<option value="Anual">Anual</option>
										<option value="Sexenal">Sexenal</option>
								</select>
							
						</div>
					</div>
 <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_publicacion">Fecha de Publicación<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_publicacion" required>
                        </div>
                    </div>					
								
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_coleccion">Nombre Colección</label>
                            <input type="text" class="form-control" name="nombre_coleccion" placeholder="Nombre Colección" required>
                        </div>
                    </div>
												
					<div class="col-md-4">
						<div class="form-group">
							<label for="id_area_responsable">Área Responsable <span style="color:red;font-weight:bold">*</span></label></label>
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
                            <label for="nombre_responsable">Nombre del Responsable <span style="color:red;font-weight:bold">*</span></label></label>
                            <input type="text" class="form-control" name="nombre_responsable" required>
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
			
                <div class="row">									
					<div class="col-md-6">
                        <div class="form-group">
                            <label for="hipervinculo_proyecto">Hipervinculo al Documento</label>
                            <textarea class="form-control" name="hipervinculo_proyecto" cols="10" rows="2"></textarea>
                        </div>
                    </div>
					<div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" cols="10" rows="3"></textarea>
                        </div>
                    </div>
				</div>
               				
					
                <div class="form-group clearfix">
                    <a href="colecciones_estudios.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_colecciones_estudios" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>