<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Proyectos';
require_once('includes/load.php');


$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 25) {
    page_require_level(25);
}
if ($nivel > 2  && $nivel < 25) :
    redirect('home.php');
endif;
if ($nivel > 25) :
    redirect('home.php');
endif;
$proyectos = find_by_id('proyectos', (int)$_GET['id'], 'id_proyectos');
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_proyectos'])) {

    if (empty($errors)) {

		$id = (int)$proyectos['id_proyectos'];
        $ejercicio = remove_junk($db->escape($_POST['ejercicio']));
        $mes   = remove_junk($db->escape($_POST['mes']));
		$fecha_completa = $ejercicio."-".$mes."-"."01";
        $no_pendientes_estudio   = remove_junk($db->escape($_POST['no_pendientes_estudio']));
		$listado_pendientes_estudio = remove_junk($db->escape($_POST['listado_pendientes_estudio']));
        $no_emision_resolucion   = remove_junk($db->escape($_POST['no_emision_resolucion']));
        $listado_emision_resolucion   = remove_junk($db->escape($_POST['listado_emision_resolucion']));
		$observaciones = remove_junk($db->escape($_POST['observaciones']));

       
		$sql = "UPDATE proyectos SET 
				ejercicio='{$ejercicio}', 
				mes='{$mes}', 
				fecha_completa='{$fecha_completa}', 
				no_pendientes_estudio='{$no_pendientes_estudio}', 
				listado_pendientes_estudio='{$listado_pendientes_estudio}',
				no_emision_resolucion='{$no_emision_resolucion}', 
				listado_emision_resolucion='{$listado_emision_resolucion}', 
				observaciones='{$observaciones}'
				WHERE id_proyectos='{$db->escape($id)}'";
       
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó los datos de Proyectos de Folio: -' . $proyectos['folio'], 2);
            $session->msg('s', " La difusión con folio '" . $proyectos['folio'] . "' ha sido acuatizado con éxito.");
            redirect('proyectos.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron cambios a la informacion.');
            redirect('edit_proyectos.php?id=' . (int)$proyectos['id_proyectos'], false);
        }
    } else {
        $session->msg("d", $errors);
		redirect('edit_proyectos.php?id=' . (int)$proyectos['id_proyectos'], false);
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
                <span>Editar Proyectos <?php echo $proyectos['folio'];?> </span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_proyectos.php?id=<?php echo $proyectos['id_proyectos']; ?>" enctype="multipart/form-data">
                <div class="row">
				<div class="col-md-3">
                <div class="form-group">
                    <label for="ejercicio" class="control-label">Ejercicio</label>
                    <select class="form-control" name="ejercicio" id="ejercicio" required>
                        <option value="">Escoge una opción</option>
                        <option <?php if ($proyectos['ejercicio'] == '2022') echo 'selected="selected"'; ?> value="2022">2022</option>
                            <option <?php if ($proyectos['ejercicio'] == '2023') echo 'selected="selected"'; ?> value="2023">2023</option>
                            <option <?php if ($proyectos['ejercicio'] == '2024') echo 'selected="selected"'; ?> value="2024">2024</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="mes" class="control-label">Mes</label>
                    <select class="form-control" name="mes" id="mes" required>
                        <option value="">Escoge una opción</option>
                         <option <?php if ($proyectos['mes'] == '1') echo 'selected="selected"'; ?> value="1">Enero</option>
                            <option <?php if ($proyectos['mes'] == '2') echo 'selected="selected"'; ?> value="2">Febrero</option>
                            <option <?php if ($proyectos['mes'] == '3') echo 'selected="selected"'; ?> value="3">Marzo</option>
                            <option <?php if ($proyectos['mes'] == '4') echo 'selected="selected"'; ?> value="4">Abril</option>
                            <option <?php if ($proyectos['mes'] == '5') echo 'selected="selected"'; ?> value="5">Mayo</option>
                            <option <?php if ($proyectos['mes'] == '6') echo 'selected="selected"'; ?> value="6">Junio</option>
                            <option <?php if ($proyectos['mes'] == '7') echo 'selected="selected"'; ?> value="7">Julio</option>
                            <option <?php if ($proyectos['mes'] == '8') echo 'selected="selected"'; ?> value="8">Agosto</option>
                            <option <?php if ($proyectos['mes'] == '9') echo 'selected="selected"'; ?> value="9">Septiembre</option>
                            <option <?php if ($proyectos['mes'] == '10') echo 'selected="selected"'; ?> value="10">Octubre</option>
                            <option <?php if ($proyectos['mes'] == '11') echo 'selected="selected"'; ?> value="11">Noviembre</option>
                            <option <?php if ($proyectos['mes'] == '12') echo 'selected="selected"'; ?> value="12">Diciembre</option>
                    </select>
                </div>
            </div>
            </div>
            <div class="row">
				               
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_pendientes_estudio">Total Pendientes de estudio para Resolución</label><br>
                            <input type="text" class="form-control" name="no_pendientes_estudio" value="<?php echo $proyectos['no_pendientes_estudio']; ?>" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_emision_resolucion">Total de Emisión de Resolución</label><br>
                            <input type="text" class="form-control" name="no_emision_resolucion" value="<?php echo $proyectos['no_emision_resolucion']; ?>">
                        </div>
                    </div>
			</div>
            <div class="row">
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="listado_pendientes_estudio">Listado de Expedientes Pendientes de estudio para Resolución</label>
							<textarea class="form-control" name="listado_pendientes_estudio" cols="10" rows="5" required><?php echo $proyectos['listado_pendientes_estudio']; ?></textarea>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="listado_emision_resolucion">Listado de Expedientes de Emisión de Resolución</label>
							<textarea class="form-control" name="listado_emision_resolucion" cols="10" rows="5" ><?php echo $proyectos['listado_emision_resolucion']; ?></textarea>
                        </div>
                    </div>
                    </div>
					
            <div class="row">
				<div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
							<textarea class="form-control" name="observaciones" cols="10" rows="4"><?php echo $proyectos['observaciones']; ?></textarea>
                        </div>
                    </div>
			</div>
			
            </div>
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="proyectos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="edit_proyectos" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>