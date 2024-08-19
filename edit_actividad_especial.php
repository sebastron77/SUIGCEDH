<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Editar Actividad Especial';
require_once('includes/load.php');

$actividades = find_by_id('actividades_especiales', (int)$_GET['id'], 'id_actividades_especiales');
$cat_ejes = find_all('cat_ejes_estrategicos');
$cat_agendas = find_all('cat_agendas');

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
$inticadores_pat = find_all_pat(46);
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_actividad'])) {

    if (empty($errors)) {
	$id = (int)$actividades['id_actividades_especiales'];
        $fecha_actividad = remove_junk($db->escape($_POST['fecha_actividad']));
        $tema_actividad   = remove_junk($db->escape($_POST['tema_actividad']));
        $lugar_actividad   = remove_junk($db->escape($_POST['lugar_actividad']));
        $id_cat_ejes_estrategicos   = remove_junk($db->escape($_POST['id_cat_ejes_estrategicos']));
        $id_cat_agendas   = remove_junk($db->escape($_POST['id_cat_agendas']));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
        $asistentes_otros = remove_junk($db->escape($_POST['asistentes_otros']));
        $asistentes_nobinario = remove_junk($db->escape($_POST['asistentes_nobinario']));
        $asistentes_mujeres = remove_junk($db->escape($_POST['asistentes_mujeres']));
        $asistentes_hombres = remove_junk($db->escape($_POST['asistentes_hombres']));
		
		 $sql = "UPDATE actividades_especiales SET 
			fecha_actividad='{$fecha_actividad}', 
			tema_actividad='{$tema_actividad}', 
			lugar_actividad='{$lugar_actividad}', 
			id_cat_ejes_estrategicos='{$id_cat_ejes_estrategicos}', 
			id_cat_agendas='{$id_cat_agendas}', 
			asistentes_hombres='{$asistentes_hombres}', 
			asistentes_mujeres='{$asistentes_mujeres}', 
			asistentes_nobinario='{$asistentes_nobinario}', 
			asistentes_otros='{$asistentes_otros}', 
			observaciones='{$observaciones}'			
			WHERE id_actividades_especiales='{$db->escape($id)}'";
			
          
			$result = $db->query($sql);
				if ($result && $db->affected_rows() === 1) {
				insertAccion($user['id_user'], '"' . $user['username'] . '" edito la Actividad Especial de Folio: -' . $actividades['folio'], 2);
				$session->msg('s', " La Atididad Especial con folio '" . $actividades['folio'] . "' ha sido acuatizado con éxito.");
				redirect('actividad_especial.php', false);
			} else {
				$session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron canmbios a la informacion.');
				redirect('edit_actividad_especial.php?id=' . (int)$actividades['id_actividades_especiales'], false);
			}
       
    } else {
        $session->msg("d", $errors);
        redirect('actividad_especial.php', false);
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
                <span>Editar Actividad Especial</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_actividad_especial.php?id=<?php echo (int)$actividades['id_actividades_especiales']; ?>" >
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_actividad">Fecha de la Actividad<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_actividad" value="<?php echo ucwords($actividades['fecha_actividad']); ?>" required>
                        </div>
                    </div>					
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tema_actividad">Tema de la Actividad<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="tema_actividad" placeholder="Tema Actividad" value="<?php echo ucwords($actividades['tema_actividad']); ?>" required>
                        </div>
                    </div>                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="lugar_actividad">Lugar de la Actividad<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="lugar_actividad" placeholder="Lugar de Actividad" value="<?php echo ucwords($actividades['lugar_actividad']); ?>" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" >
								<option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  <?php if ($actividades['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>

                    
                </div>
                           
				  <div class="row">
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="eje">Eje Estratégico</label>
                            <select class="form-control" name="id_cat_ejes_estrategicos" required>
                                <option value="Escoge una opción">Escoge una opción</option>
								<?php foreach ($cat_ejes as $ejes) : ?>
                                    <option <?php if ($actividades['id_cat_ejes_estrategicos'] === $ejes['id_cat_ejes_estrategicos']) echo 'selected="selected"'; ?> value="<?php echo $ejes['id_cat_ejes_estrategicos']; ?>"><?php echo ucwords($ejes['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="agenda">Agenda</label>
                            <select class="form-control" name="id_cat_agendas" required>
                                <option value="Escoge una opción">Escoge una opción</option>
								<?php foreach ($cat_agendas as $agendas) : ?>
                                    <option <?php if ($actividades['id_cat_agendas'] === $agendas['id_cat_agendas']) echo 'selected="selected"'; ?>  value="<?php echo $agendas['id_cat_agendas']; ?>"><?php echo ucwords($agendas['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label><br>
                            <textarea name="observaciones" class="form-control" id="observaciones" cols="50" rows="2"><?php echo ucwords($actividades['observaciones']); ?></textarea>
                        </div>
                    </div>
			   </div>

				<div class="row">
				 <h3 style="font-weight:bold;">
                    <span class="material-symbols-outlined">checklist</span>
                    Asistentes
                </h3>
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">Hombres</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_hombres" value="<?php echo ucwords($actividades['asistentes_hombres']); ?>"  >
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">Mujeres</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_mujeres" value="<?php echo ucwords($actividades['asistentes_mujeres']); ?>"  >
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">No Binarios</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_nobinario" value="<?php echo ucwords($actividades['asistentes_nobinario']); ?>"  >
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">Otros</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_otros" value="<?php echo ucwords($actividades['asistentes_otros']); ?>"  >
                        </div>
                    </div>
				</div>
					
                                                  
               
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="actividad_especial.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="edit_actividad" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>