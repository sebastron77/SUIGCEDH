<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Solicitud';
require_once('includes/load.php');


$solicitud = find_by_id('solicitudes_informacion', (int)$_GET['id'], 'id_solicitudes_informacion');
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
$inticadores_pat = find_all_pat_area(13,'solicitudes_informacion');
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_solicitudes_ut'])) {

    if (empty($errors)) {
		
		$id = (int)$solicitud['id_solicitudes_informacion'];
        $folio_solicitud = remove_junk($db->escape($_POST['folio_solicitud']));
        $fecha_presentacion   = remove_junk($db->escape($_POST['fecha_presentacion']));
        $nombre_solicitante   = remove_junk($db->escape($_POST['nombre_solicitante']));
        $id_cat_gen   = remove_junk($db->escape($_POST['id_cat_gen']));
        $id_cat_med_pres_ut = remove_junk($db->escape($_POST['id_cat_med_pres_ut']));
        $id_cat_tipo_solicitud   = remove_junk(($db->escape($_POST['id_cat_tipo_solicitud'])));
        $informacion_solicitada   = remove_junk(($db->escape($_POST['informacion_solicitada'])));
        $id_indicadores_pat   = remove_junk(($db->escape($_POST['id_indicadores_pat'])));
		
		$personalidad_juridica   = remove_junk(($db->escape($_POST['personalidad_juridica'])));
        $informacion_clasificada   = remove_junk(($db->escape($_POST['informacion_clasificada'])));
		$derecho_arco = remove_junk($db->escape($_POST['derecho_arco'] == 'on' ? 1 : 0));
		$tipo_derecho_arco   = remove_junk(($db->escape($_POST['tipo_derecho_arco'])));
        
            $sql = "UPDATE solicitudes_informacion SET 
			folio_solicitud='{$folio_solicitud}', 
			fecha_presentacion='{$fecha_presentacion}', 
			nombre_solicitante='{$nombre_solicitante}', 
			id_cat_gen='{$id_cat_gen}',  
			id_cat_med_pres_ut='{$id_cat_med_pres_ut}', 
			id_cat_tipo_solicitud='{$id_cat_tipo_solicitud}', 
			informacion_solicitada='{$informacion_solicitada}',			
			id_indicadores_pat='{$id_indicadores_pat}',			
			personalidad_juridica='{$personalidad_juridica}',			
			informacion_clasificada='{$informacion_clasificada}',		
			derecho_arco= {$derecho_arco},			
			tipo_derecho_arco='{$tipo_derecho_arco}' 			
			WHERE id_solicitudes_informacion='{$db->escape($id)}'";
			
			
			$result = $db->query($sql);
				if ($result && $db->affected_rows() === 1) {
				insertAccion($user['id_user'], '"' . $user['username'] . '" edito la Solicitud de Información('.$id.') de Folio: -' . $solicitud['folio_solicitud'], 2);
				$session->msg('s', " La Solicitud de Información con folio '" . $solicitud['folio_solicitud'] . "' ha sido acuatizado con éxito.");
				redirect('solicitudes_ut.php', false);
			} else {
				$session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron canmbios a la informacion.');
				redirect('edit_solicitudes_ut.php?id=' . (int)$solicitud['folio_solicitud'], false);
			}
       

    } else {
        $session->msg("d", $errors);
        redirect('solicitudes_ut.php', false);
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
                <span>Editar Solicitud de Información</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_solicitudes_ut.php?id=<?php echo (int)$solicitud['id_solicitudes_informacion']; ?>" >
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_presentacion">Fecha de Presentación<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_presentacion" value="<?php echo ucwords($solicitud['fecha_presentacion']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="folio_solicitud">Folio Solicitud<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="folio_solicitud" placeholder="Folio Solicitud" value="<?php echo ucwords($solicitud['folio_solicitud']); ?>" required>
                        </div>
                    </div>
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_med_pres_ut">Medio de Presentación<span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="id_cat_med_pres_ut" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_medio_presentacion as $datos) : ?>
                                    <option <?php if ($solicitud['id_cat_med_pres_ut'] === $datos['id_cat_med_pres_ut']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_cat_med_pres_ut']; ?>"><?php echo ucwords($datos['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div> 
					
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_tipo_solicitud">Tipo de Información Solicitada<span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="id_cat_tipo_solicitud" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_tipo_solicitud as $datos) : ?>
                                    <option <?php if ($solicitud['id_cat_tipo_solicitud'] === $datos['id_cat_tipo_solicitud'] ) echo 'selected="selected"'; ?> value="<?php echo $datos['id_cat_tipo_solicitud']; ?>"><?php echo ucwords($datos['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>
					
				</div>
                <div class="row">
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_solicitante">Nombre Solicitante<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="nombre_solicitante" placeholder="Nombre Solicitante" value="<?php echo ucwords($solicitud['nombre_solicitante']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_gen">Género Solicitane <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="id_cat_gen" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_genero as $datos) : ?>
                                    <option <?php if ($solicitud['id_cat_gen'] === $datos['id_cat_gen']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_cat_gen']; ?>"><?php echo ucwords($datos['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="personalidad_juridica">Tipo de personalidad jurídica<span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="personalidad_juridica" required>
                                <option value="">Escoge una opción</option>
                                    <option <?php if ($solicitud['personalidad_juridica'] === 'Persona Física') echo 'selected="selected"'; ?> value="Persona Física">Persona Física</option>
                                    <option <?php if ($solicitud['personalidad_juridica'] === 'Persona Moral' ) echo 'selected="selected"'; ?> value="Persona Moral">Persona Moral</option>
									<option <?php if ($solicitud['personalidad_juridica'] === 'Sin Dato' ) echo 'selected="selected"'; ?> value="Sin Dato">Sin Dato</option>
                            </select>

                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="informacion_clasificada">Clasificador de la información</label>
                            <select class="form-control" name="informacion_clasificada" >
                                <option value="">Escoge una opción</option>
                                    <option <?php if ($solicitud['informacion_clasificada'] === 'Reservada' ) echo 'selected="selected"'; ?> value="Reservada">Reservada</option>
                                    <option <?php if ($solicitud['informacion_clasificada'] === 'Confidencial' ) echo 'selected="selected"'; ?> value="Confidencial">Confidencial</option>
                                    <option <?php if ($solicitud['informacion_clasificada'] === 'Ambos' ) echo 'selected="selected"'; ?> value="Ambos">Ambos</option>
                            </select>

                        </div>
                    </div>
						</div>
                <div class="row">
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="derecho_arco">¿La Solicitud es de Derechos ARCO?</label><br>
                            <label class="switch" style="float:left;">
                                <div class="row">
                                    <input type="checkbox" id="derecho_arco" name="derecho_arco" <?php if ($solicitud['derecho_arco'] === '1' ) echo 'checked'; ?>>
                                    <span class="slider round"></span>
                                    <div>
                                        <p style="margin-left: 150%; margin-top: -3%; font-size: 14px;">No/Sí</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

					<div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_derecho_arco">Derecho ARCO</label>
                            <select class="form-control" name="tipo_derecho_arco" >
                                <option value="">Escoge una opción</option>
                                    <option <?php if ($solicitud['tipo_derecho_arco'] === 'Acceso' ) echo 'selected="selected"'; ?> value="Acceso">Acceso</option>
                                    <option <?php if ($solicitud['tipo_derecho_arco'] === 'Rectificación' ) echo 'selected="selected"'; ?> value="Rectificación">Rectificación</option>
                                    <option <?php if ($solicitud['tipo_derecho_arco'] === 'Cancelación' ) echo 'selected="selected"'; ?> value="Cancelación">Cancelación</option>
                                    <option <?php if ($solicitud['tipo_derecho_arco'] === 'Oposición' ) echo 'selected="selected"'; ?> value="Oposición">Oposición</option>
                            </select>

                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" >
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  <?php if ($solicitud['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                     </div>

					<div class="col-md-6">
                        <div class="form-group">
                            <label for="informacion_solicitada">Información Solicitada</label>
                            <textarea class="form-control" name="informacion_solicitada"  cols="16" rows="6" required><?php echo ucwords($solicitud['informacion_solicitada']); ?></textarea>
                        </div>
                    </div>

                   
                </div>

                
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="solicitudes_ut.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="edit_solicitudes_ut" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>