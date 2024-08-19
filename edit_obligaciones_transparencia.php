<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Recepción de Obligaciones de Transparencia';
require_once('includes/load.php');


$solicitud = find_by_id('obligaciones_transparencia', (int)$_GET['id'], 'id_obligaciones_transparencia');
$cat_genero = find_all('cat_genero');
$cat_medio_presentacion = find_all('cat_medio_pres_ut');
$cat_tipo_solicitud = find_all('cat_tipo_solicitud');

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 10) {
    page_require_level_exacto(10);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
	
endif;if ($nivel_user > 7 && $nivel_user < 10) :
    redirect('home.php');
endif;
if ($nivel_user > 10) :
    redirect('home.php');
endif;
$inticadores_pat = find_all_pat(13);
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_obligaciones_transparencia'])) {

    if (empty($errors)) {
		
		$id = (int)$solicitud['id_obligaciones_transparencia'];
         $ejercicio = remove_junk($db->escape($_POST['ejercicio']));
        $trimestre_revisado   = remove_junk($db->escape($_POST['trimestre_revisado']));			
        $fecha_recepcion   = remove_junk($db->escape($_POST['fecha_recepcion']));			
        $total_formatos   = remove_junk($db->escape($_POST['total_formatos']));			
        $ficha_recepcion   = remove_junk($db->escape($_POST['ficha_recepcion']));			
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));			
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));			
        
		$carpeta = 'uploads/obligaciones_transparencia/' . str_replace("/", "-", $solicitud['folio']);
        
		$name = $_FILES['ficha_recepcion']['name'];
        $size = $_FILES['ficha_recepcion']['size'];
        $type = $_FILES['ficha_recepcion']['type'];
        $temp = $_FILES['ficha_recepcion']['tmp_name'];

		if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else{
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }
		
            $sql = "UPDATE obligaciones_transparencia SET 
			ejercicio='{$ejercicio}', 
			trimestre_revisado='{$trimestre_revisado}', 
			fecha_recepcion='{$fecha_recepcion}', 
			total_formatos='{$total_formatos}',  
			observaciones='{$observaciones}', 
			id_indicadores_pat='{$id_indicadores_pat}'";
			if ($name != '') {
				$sql .= ", ficha_recepcion='{$name}' ";
			}
			 $sql .="WHERE id_obligaciones_transparencia='{$db->escape($id)}'";
			
			
			$result = $db->query($sql);
				if ($result && $db->affected_rows() === 1) {
				insertAccion($user['id_user'], '"' . $user['username'] . '" edito la Recepción de Obligaciones de Transparencia('.$id.') de Folio: -' . $solicitud['folio'], 2);
				$session->msg('s', " La Recepción de Obligaciones de Transparencia con folio '" . $solicitud['folio_solicitud'] . "' ha sido acuatizado con éxito.");
				redirect('obligaciones_transparencia.php', false);
			} else {
				$session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron canmbios a la informacion.');
				redirect('edit_obligaciones_transparencia.php?id=' . (int)$solicitud['id_obligaciones_transparencia'], false);
			}
       

    } else {
        $session->msg("d", $errors);
        redirect('obligaciones_transparencia.php', false);
    }
}
?>
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar Recepción de Obligaciones de Transparencia <?php echo $solicitud['folio']; ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_obligaciones_transparencia.php?id=<?php echo (int)$solicitud['id_obligaciones_transparencia']; ?>" >
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ejercicio" class="control-label">Ejercicio</label>
							<select class="form-control" name="ejercicio" id="ejercicio" required>
								<option value="">Escoge una opción</option>                        
								<option <?php if ($solicitud['ejercicio'] == '2023') echo 'selected="selected"'; ?> value="2023">2023</option>
								<option <?php if ($solicitud['ejercicio'] == '2024') echo 'selected="selected"'; ?> value="2024">2024</option>
							</select>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="trimestre_revisado" class="control-label">Trimestre</label>
							<select class="form-control" name="trimestre_revisado" id="trimestre_revisado" required>
								<option value="">Escoge una opción</option>                        
								<option <?php if ($solicitud['trimestre_revisado'] == '1er Trimestre') echo 'selected="selected"'; ?> value="1er Trimestre">1er Trimestre</option>
								<option <?php if ($solicitud['trimestre_revisado'] == '2do Trimestre') echo 'selected="selected"'; ?> value="2do Trimestre">2do Trimestre</option>
								<option <?php if ($solicitud['trimestre_revisado'] == '3er Trimestre') echo 'selected="selected"'; ?> value="3er Trimestre">3er Trimestre</option>
								<option <?php if ($solicitud['trimestre_revisado'] == '4to Trimestre') echo 'selected="selected"'; ?> value="4to Trimestre">4to Trimestre</option>
							</select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_recepcion">Fecha de Recepción<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_recepcion" id="fecha_recepcion" value="<?php echo $solicitud['fecha_recepcion']; ?>" required>
                        </div>
                    </div>
					<div class="col-md-2">
                <div class="form-group">
                    <label for="desktop">Total de Formatos Revisados</label>
                    <input class="form-control monto" type="number" id="total_formatos" name="total_formatos" value="<?php echo $solicitud['total_formatos']; ?>" required>
                </div>
            </div>
                    <div class="col-md-3">
					      <div class="form-group">
                            <label for="ficha_recepcion">Ficha Recepción</label>
                            <input type="file" accept="application/pdf" class="form-control" name="ficha_recepcion" id="ficha_recepcion" >
							<label style="font-size:12px; color:#E3054F;" for="oficio_recibido">Archivo Actual: <?php echo remove_junk($solicitud['ficha_recepcion']); ?><?php ?></label>
                        </div>
                    </div>					
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" required >
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  <?php if ($solicitud['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                        </div>
		
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones"  cols="16" rows="6" ></textarea>
                        </div>
                    </div>

                   
                </div>
				
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="obligaciones_transparencia.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="edit_obligaciones_transparencia" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>