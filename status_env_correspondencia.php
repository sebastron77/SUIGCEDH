<?php
$page_title = 'Estatus Informe Área';
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}

if ($nivel_user == 2) {
    page_require_level_exacto(2);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
?>
<?php
$e_informe = find_by_id('informe_actividades_areas', (int)$_GET['id'], 'id_info_act_areas');

?>
<?php
if (isset($_POST['update'])) {

    if (empty($errors)) {
        $status_st = remove_junk($db->escape(($_POST['status_st'])));
        $observaciones_st = remove_junk($db->escape(($_POST['observaciones_st'])));

        $query  = "UPDATE informe_actividades_areas SET ";
        $query .= "fecha_status_st=NOW(), ";
        $query .= "status_st='{$status_st}', ";
        $query .= "observaciones_st='{$observaciones_st}' ";
        $query .= "WHERE id_info_act_areas='{$db->escape($e_informe['id_info_act_areas'])}'";
		 
		$result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Estatus de Revisión del Informe del Área ha actualizado! ");
			insertAccion($user['id_user'],'"'.$user['username'].'" actualizo el estatus del informe '.$e_informe['folio'].' a '.$status_st.'.',2);
            redirect('informes_areas.php?a=' . (int)$e_informe['area_creacion'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el Estatus del Informe, debido a que no hay cambios registrados en la descripción!');
            redirect('informes_areas.php?a=' . (int)$e_informe['area_creacion'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('informes_areas.php?a=' . (int)$e_informe['area_creacions'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8'); include_once('layouts/header.php'); ?>
<div class="login-page" style="width: 550px;height: 480px;">
    <div class="text-center">
        <h3>Editar Género</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="status_env_correspondencia.php?id=<?php echo (int)$e_informe['id_info_act_areas']; ?>" class="clearfix">
        <div class="form-group">
            <label for="status_st" class="control-label">Datos Actuales: <br><br><?php echo remove_junk($e_informe['status_st']); ?><br><br><?php echo remove_junk($e_informe['observaciones_st']); ?></label>
			                   
        </div> 
<hr style="color: red;">		
		<div class="form-group">
            <label for="status_st" class="control-label">Estatus</label>
			<select class="form-control" name="status_st" required>
				<option value="">Escoge una opción</option>								
				<option value="En Revisión" >En Revisión</option>
				<option value="Con Observación">Con Observación</option>
				<option value="Aprobado">Aprobado</option>
			</select>                   
        </div>
        <div class="form-group">
                        <div class="form-group">
                            <label for="observaciones_st">Descripción</label>
							<textarea class="form-control" name="observaciones_st" cols="10" rows="4"></textarea>
                        </div>
					</div>
        <div class="form-group clearfix">
            <a href="informes_areas.php?a=<?php echo $e_informe['area_creacion']?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>