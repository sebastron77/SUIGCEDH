
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Supervisión de Buzones';
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
if ($nivel_user == 24) {
    page_require_level_exacto(24);
}

if ($nivel_user > 2 && $nivel_user < 6) :
    redirect('home.php');
endif;
if ($nivel_user > 24 && $nivel_user < 53) :
    redirect('home.php');
endif;


$supervision = find_by_id('supervision_buzones', (int)$_GET['id'], 'id_supervision_buzones');

$inticadores_pat = find_all_pat_area(39,'supervision_buzones');
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_supervision_buzones'])) {

    if (empty($errors)) {
		$id = (int)$supervision['id_supervision_buzones'];
        $fecha_supervision   = remove_junk($db->escape($_POST['fecha_supervision']));
        $lugar_supervision   = remove_junk($db->escape($_POST['lugar_supervision']));
        $numero_quejas   = remove_junk(($db->escape($_POST['numero_quejas'])));
        $quien_atendio   = remove_junk(($db->escape($_POST['quien_atendio'])));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));

        
            $sql = "UPDATE supervision_buzones SET 
			fecha_supervision='{$fecha_supervision}', 
			lugar_supervision='{$lugar_supervision}', 
			numero_quejas='{$numero_quejas}', 
			quien_atendio='{$quien_atendio}',  
			observaciones='{$observaciones}', 
			id_indicadores_pat='{$id_indicadores_pat}' 			
			WHERE id_supervision_buzones='{$db->escape($id)}'";
			
			
			$result = $db->query($sql);
				if ($result && $db->affected_rows() === 1) {
				insertAccion($user['id_user'], '"' . $user['username'] . '" edito la Supervisión de Buzones('.$id.') de Folio: -' . $supervision['folio'], 2);
				$session->msg('s', " La Supervisión de Buzones con folio '" . $supervision['folio'] . "' ha sido acuatizada con éxito.");
				redirect('supervision_buzones.php', false);
			} else {
				$session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron canmbios a la informacion.');
				redirect('edit_supervision_buzones.php?id=' . (int)$supervision['id_supervision_buzones'], false);
			}

    } else {
        $session->msg("d", $errors);
        redirect('supervision_buzones.php', false);
    }
}
?>

<?php 
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Supervisión de Buzones</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_supervision_buzones.php?id=<?php echo (int)$supervision['id_supervision_buzones']; ?>">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_supervision">Fecha Supervisión</label><br>
                            <input type="date" class="form-control" name="fecha_supervision" value="<?php echo ucwords($supervision['fecha_supervision']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lugar_supervision">Lugar de Supervisión</label>
                            <input type="text" class="form-control" name="lugar_supervision" value="<?php echo ucwords($supervision['lugar_supervision']); ?>" required>
                        </div>
                    </div>
					
					 <div class="col-md-2">
                        <div class="form-group">
                            <label for="numero_quejas">No. de Quejas Captadas</label>
                            <input type="number" min="0" class="form-control" max="1000" name="numero_quejas" value="<?php echo ucwords($supervision['numero_quejas']); ?>" required>
                        </div>
                    </div>
					 <div class="col-md-4">
                        <div class="form-group">
                            <label for="quien_atendio">¿Quién Atendió?<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="quien_atendio" value="<?php echo ucwords($supervision['quien_atendio']); ?>" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" >
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  <?php if ($supervision['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?>  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
			
                            
	
					<div class="col-md-5">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="5"><?php echo ucwords($supervision['observaciones']); ?></textarea>
                        </div>
                    </div>
				</div>

      

              
                <div class="form-group clearfix">
                    <a href="supervision_buzones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_supervision_buzones" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>