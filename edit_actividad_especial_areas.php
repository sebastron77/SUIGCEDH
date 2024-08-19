<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Editar Actividad Especial';
require_once('includes/load.php');



$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 4) {
    page_require_level_exacto(4);
}
if ($nivel_user == 9) {
    page_require_level_exacto(9);
}
if ($nivel_user == 10) {
    page_require_level_exacto(10);
}
if ($nivel_user == 12) {
    page_require_level_exacto(12);
}
if ($nivel_user == 22) {
    page_require_level_exacto(22);
}
if ($nivel_user == 24) {
    page_require_level_exacto(24);
}
if ($nivel_user > 2 && $nivel_user < 4) :
    redirect('home.php');
endif;
if ($nivel_user > 4 && $nivel_user <9) :
    redirect('home.php');
endif;
if ($nivel_user > 9 && $nivel_user <10) :
    redirect('home.php');
endif;
if ($nivel_user > 10 && $nivel_user <12) :
    redirect('home.php');
endif;
if ($nivel_user > 12 && $nivel_user <22) :
    redirect('home.php');
endif;
if ($nivel_user > 22 && $nivel_user <24) :
    redirect('home.php');
endif;

$actividades = find_by_id('actividades_especiales_areas', (int)$_GET['id'], 'id_actividades_especiales_areas');
$area = $actividades['id_area_responsable'];
$inticadores_pat = find_all_pat_area($area,'actividades_especiales_areas');
$solicitud = find_by_solicitud($area);
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['update'])) {

    if (empty($errors)) {
	$id_actividades_especiales_areas = (int)$actividades['id_actividades_especiales_areas'];
        $tema_actividad = remove_junk($db->escape($_POST['tema_actividad']));
        $fecha_actividad   = remove_junk($db->escape($_POST['fecha_actividad']));
        $quien_atendio   = remove_junk($db->escape($_POST['quien_atendio']));
        $descripcion   = remove_junk($db->escape($_POST['descripcion']));
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));
				
        $carpeta = 'uploads/actividades_especiales/' . str_replace("/", "-", $actividades['folio']);

        $name = $_FILES['adjunto']['name'];
        $size = $_FILES['adjunto']['size'];
        $type = $_FILES['adjunto']['type'];
        $temp = $_FILES['adjunto']['tmp_name'];
        //Verificamos que exista la carpeta y si sí, guardamos el pdf
        if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else{
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }

        
            $query = "UPDATE actividades_especiales_areas SET 
						tema_actividad = '{$tema_actividad}',
						fecha_actividad = '{$fecha_actividad}',
						quien_atendio = '{$quien_atendio}',
						descripcion= '{$descripcion}',
						id_indicadores_pat = '{$id_indicadores_pat}' ";
 if($name != '') {
	 $query .= ", documento= '{$name}'";
}	
						
			$query .= " WHERE id_actividades_especiales_areas={$id_actividades_especiales_areas}";            

         
            $result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
                //sucess
                insertAccion($user['id_user'], '"' . $user['username'] . '" edito de una Actividad Especial de Folio: -' . $actividades['folio'] . '-.', 2);
                $session->msg('s', " La Actividad Especial con folio '{$actividades['folio']}' ha sido actualizada con éxito.");
                redirect('actividad_especial_areas.php?a='.$area, false);
            } else {
                //failed
                $session->msg('d', ' Lo siento no se actualizaron los datos.');
                redirect('edit_actividad_especial_areas.php?id='.id_actividades_especiales_areas, false);
            }
       
    } else {
        $session->msg("d", $errors);
        redirect('edit_actividad_especial_areas.php?id='.$id_actividades_especiales_areas, false);
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
                <span>Editar Actividad Especial de la <?php echo $solicitud['nombre_area'];?> <?php echo $actividades['folio']; ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_actividad_especial_areas.php?id=<?php echo (int)$actividades['id_actividades_especiales_areas']; ?>" enctype="multipart/form-data" >
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
                            <label for="quien_atendio">¿Quién Atendió?<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="quien_atendio" value="<?php echo ucwords($actividades['quien_atendio']); ?>" required>
                        </div>
                    </div>
					
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" required>
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option <?php if ($actividades['id_indicadores_pat'] === $datos['id_indicadores_pat']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select
                        </div>
                    </div>
                    
                </div>
                           
				  <div class="row">
					 <div class="col-md-5">
                        <div class="form-group">
                            <label for="descripcion">Descripción Actividad</label>
                            <textarea class="form-control" name="descripcion" cols="10" rows="3"><?php echo ucwords($actividades['descripcion']); ?></textarea>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="adjunto">Adjuntar archivo</label>
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto">
							<label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($actividades['documento']); ?><?php ?></label>
                        </div>
                    </div>
			   </div>

				
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="actividad_especial_areas.php?a=<?php echo $area?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="update" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>