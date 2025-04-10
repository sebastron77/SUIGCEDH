<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Diseño';
require_once('includes/load.php');


$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 15) {
    page_require_level(15);
}
if ($nivel > 2 && $nivel < 7) :
    redirect('home.php');
endif;
if ($nivel >7  && $nivel < 15) :
    redirect('home.php');
endif;
if ($nivel > 15 ) :
    redirect('home.php');
endif;

$areas = find_all_order('area', 'jerarquia');
$disenios = find_all('cat_tipo_disenios');
$disenio = find_by_id('disenios', (int)$_GET['id'], 'id_disenios');
$inticadores_pat = find_all_pat(46);
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_disenio'])) {

    if (empty($errors)) {
		$id = (int)$disenio['id_disenios'];
        $fecha_solicitud = remove_junk($db->escape($_POST['fecha_solicitud']));
        $fecha_entrega   = remove_junk($db->escape($_POST['fecha_entrega']));
        $id_cat_tipo_disenios   = remove_junk($db->escape($_POST['id_cat_tipo_disenios']));
        $tema_disenio   = remove_junk($db->escape($_POST['tema_disenio']));
        $id_area_solicitud   = remove_junk($db->escape($_POST['id_area_solicitud']));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
        $id_indicadores_pat = remove_junk($db->escape($_POST['id_indicadores_pat']));
		
        
            $sql = "UPDATE disenios SET 
			fecha_solicitud='{$fecha_solicitud}', 
			fecha_entrega='{$fecha_entrega}', 
			id_cat_tipo_disenios={$id_cat_tipo_disenios}, 
			tema_disenio='{$tema_disenio}', 
			id_area_solicitud='{$id_area_solicitud}', 
			id_indicadores_pat='{$id_indicadores_pat}', 
			observaciones='{$observaciones}'			
			WHERE id_disenios='{$db->escape($id)}'";
			
			
			$result = $db->query($sql);
				if ($result && $db->affected_rows() === 1) {
				insertAccion($user['id_user'], '"' . $user['username'] . '" edito un Diseño de Folio: -' . $disenio['folio'], 2);
				$session->msg('s', " El Diseñ con folio '" . $disenio['folio'] . "' ha sido acuatizado con éxito.");
				redirect('disenios.php', false);
			} else {
				$session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron canmbios a la informacion.');
				redirect('edit_disenio.php?id=' . (int)$disenio['id_disenios'], false);
			}
       
    } else {
        $session->msg("d", $errors);
        redirect('edit_disenio.php', false);
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
                <span>Editar Diseño</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_disenio.php?id=<?php echo (int)$disenio['id_disenios']; ?>" >
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_solicitud">Fecha de Solicitud<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_solicitud" value="<?php echo ($disenio['fecha_solicitud']); ?>" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_entrega">Fecha de Entrega<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_entrega" value="<?php echo ($disenio['fecha_entrega']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_tipo_disenios">Tipo de Diseño <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="id_cat_tipo_disenios" required>
                                <option value="">Escoge una opción</option>
								<?php foreach ($disenios as $datos) : ?>
                                    <option <?php if ($disenio['id_cat_tipo_disenios'] === $datos['id_cat_tipo_disenios']) echo 'selected="selected"'; ?>  value="<?php echo $datos['id_cat_tipo_disenios']; ?>"><?php echo ucwords($datos['descripcion']); ?></option>
                                <?php endforeach; ?>  
                                    
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tema_disenio">Tema de Diseño<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="tema_disenio" placeholder="Tema Diseño" value="<?php echo ucwords($disenio['tema_disenio']); ?>" required>
                        </div>
                    </div>
                    
                </div>

                <div class="row">
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area_solicitud">Área a la que se turna</label>
                            <select class="form-control" name="id_area_solicitud">
							<option value="">Selecciona una Área</option>
                                <?php foreach ($areas as $area) : ?>
                                    <option <?php if ($disenio['id_area_solicitud'] === $area['id_area']) echo 'selected="selected"'; ?> value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					
                      <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" >
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option <?php if ($disenio['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?>  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
            

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label><br>
                            <textarea name="observaciones" class="form-control" id="observaciones" cols="50" rows="5"><?php echo ucwords($disenio['observaciones']); ?></textarea>
                        </div>
                    </div>
					
                   
                </div>
               
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="disenios.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="edit_disenio" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>