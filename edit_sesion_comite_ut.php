<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Sesión CUT';
require_once('includes/load.php');

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
$solicitud = find_by_id('comite_transparencia', (int)$_GET['id'], 'id_comite_transparencia');
$inticadores_pat = find_all_pat(13);
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_sesion_comite_ut'])) {

    if (empty($errors)) {
		
		$id = (int)$solicitud['id_comite_transparencia'];
        $fecha_sesion = remove_junk($db->escape($_POST['fecha_sesion']));
        $acta_sesion   = remove_junk($db->escape($_POST['acta_sesion']));
        $no_sesion   = remove_junk($db->escape($_POST['no_sesion']));
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));
		
		$carpeta = 'uploads/sesiones_comite/' . str_replace("/", "-", $solicitud['folio']);
        
		$name = $_FILES['acta_sesion']['name'];
        $size = $_FILES['acta_sesion']['size'];
        $type = $_FILES['acta_sesion']['type'];
        $temp = $_FILES['acta_sesion']['tmp_name'];

		if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else{
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }
		
		$sql = "UPDATE comite_transparencia SET 
			fecha_sesion='{$fecha_sesion}', 
			no_sesion='{$no_sesion}', 
			id_indicadores_pat='{$id_indicadores_pat}', 
			observaciones='{$observaciones}'";
			if ($name != '') {
				$sql .= ", acta_sesion='{$name}' ";
			}
			 $sql .="WHERE id_comite_transparencia='{$db->escape($id)}'";
			
			
			$result = $db->query($sql);
				if ($result && $db->affected_rows() === 1) {
				insertAccion($user['id_user'], '"' . $user['username'] . '" edito la Sesión de Comite de Transparencia con Folio: -' . $solicitud['folio'], 2);
				$session->msg('s', " La Solicitud de Información con folio '" . $solicitud['folio'] . "' ha sido acuatizado con éxito.");
				redirect('sesiones_comite_ut.php', false);
			} else {
				$session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron canmbios a la informacion.');
				redirect('edit_sesion_comite_ut.php?id=' . (int)$solicitud['folio'], false);
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
                <span>Editar Sesión del Comite <?php echo $solicitud['folio']; ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_sesion_comite_ut.php?id=<?php echo (int)$solicitud['id_comite_transparencia']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_sesion">No. Sesión<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="no_sesion" placeholder="No.Sesión" value="<?php echo $solicitud['no_sesion']; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_sesion">Fecha de Sesión<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_sesion" value="<?php echo $solicitud['fecha_sesion']; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
					      <div class="form-group">
                            <label for="acta_sesion">Acta</label>
                            <input type="file" accept="application/pdf" class="form-control" name="acta_sesion" id="acta_sesion">
							<label style="font-size:12px; color:#E3054F;" for="oficio_recibido">Archivo Actual: <?php echo remove_junk($solicitud['acta_sesion']); ?><?php ?></label>
                        </div>
                    </div>					
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" required >
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  <?php if ($solicitud['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?>  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                        </div>
                </div>
				
                <div class="row">
		
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones"  cols="16" rows="6"  > <?php echo $solicitud['observaciones']; ?></textarea>
                        </div>
                    </div>

                   
                </div>
                </div>

                
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="sesiones_comite_ut.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="edit_sesion_comite_ut" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>