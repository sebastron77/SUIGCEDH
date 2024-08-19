<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Entrega de Insumos';
require_once('includes/load.php');

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 4) {
    page_require_level_exacto(4);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 9) {
    page_require_level_exacto(9);
}
if ($nivel_user == 22) {
    page_require_level_exacto(22);
}

if ($nivel_user > 2 && $nivel_user < 4) :
    redirect('home.php');
endif;
if ($nivel_user > 4 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 22) :
    redirect('home.php');
endif;
if ($nivel_user > 22 && $nivel_user < 53) :
    redirect('home.php');
endif;

$e_detalle = find_by_id('insumos', (int)$_GET['id'], 'id_insumos');
$inticadores_pat = find_all_pat_area(16,'insumos');
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_insumos'])) {

    if (empty($errors)) {
        $id = (int)$e_detalle['id_insumos'];
        $fecha_entrega   = remove_junk($db->escape($_POST['fecha_entrega']));			
        $tema_actividad   = remove_junk($db->escape($_POST['tema_actividad']));			
        $total_insumos_entregado   = remove_junk($db->escape($_POST['total_insumos_entregado']));			
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));			
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));			

        
            $query = "UPDATE insumos SET ";
            $query .= "fecha_entrega = '{$fecha_entrega}',";
            $query .= "tema_actividad = '{$tema_actividad}',";
            $query .= "total_insumos_entregado = '{$total_insumos_entregado}',";
            $query .= "observaciones = '{$observaciones}',";
            $query .= "id_indicadores_pat = '{$id_indicadores_pat}' ";
            $query .= "WHERE id_insumos= '{$db->escape($id)}'";
			
           $result = $db->query($query);
			if ($result && $db->affected_rows() === 1) {
				//sucess
				$session->msg('s', " La entrega de Insumos ha sido actualizada con éxito.");
				insertAccion($user['id_user'], '"' . $user['username'] . '" editó la Entrega de Insumos, Folio: ' . $e_detalle['folio'] . '.', 2);
				redirect('insumos.php?', false);
			} else {
				//failed
				$session->msg('d', ' No se pudo editar la entrega de Insumo.');
				redirect('edit_insumos.php?id=' . (int)$e_detalle['id_insumos'], false);
			}
       
    } else {
        $session->msg("d", $errors);
        redirect('insumos.php', false);
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
                <span>Agregar Entrega de Insumos <?php echo remove_junk($e_detalle['folio']); ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_insumos.php?id=<?php echo (int)$e_detalle['id_insumos']; ?>" enctype="multipart/form-data">
                <div class="row">
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_entrega">Fecha de Entrega<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_entrega" id="fecha_entrega" value="<?php echo remove_junk($e_detalle['fecha_entrega']); ?>" required>
                        </div>
                    </div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="desktop">Nombre de la Actividad</label>
							<input class="form-control monto" type="text" id="tema_actividad" name="tema_actividad" value="<?php echo remove_junk($e_detalle['tema_actividad']); ?>" required>
						</div>                
					</div>                
					<div class="col-md-2">
						<div class="form-group">
							<label for="desktop">Total de Insumos Entregados</label>
							<input class="form-control monto" type="number" id="total_insumos_entregado" name="total_insumos_entregado" value="<?php echo remove_junk($e_detalle['total_insumos_entregado']); ?>" required>
						</div>
					</div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" required >
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  <?php if ($e_detalle['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?>  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                        </div>
		
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones"  cols="16" rows="6" ><?php echo remove_junk($e_detalle['observaciones']); ?></textarea>
                        </div>
                    </div>

                   
                </div>

                
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="insumos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="edit_insumos" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>