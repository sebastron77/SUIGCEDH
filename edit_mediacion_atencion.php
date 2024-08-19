<?php
$page_title = 'Inventario de Mediación/Conciliación';
require_once('includes/load.php');
?>
<?php

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 19) {
    page_require_level_exacto(19);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}

if ($nivel_user > 3 && $nivel_user < 19) :
    redirect('home.php');
endif;
if ($nivel_user > 19 && $nivel_user < 50) :
    redirect('home.php');
endif;

$inticadores_pat = find_all_pat_area(48,'inventario_macs');
$e_detalle = find_by_id('inventario_macs', (int)$_GET['id'], 'id_inventario_macs');
$ejercicio_act = date("Y",strtotime($e_detalle['fecha_informe']));   
$mes_act = date("m",strtotime($e_detalle['fecha_informe']));

?>
<?php


if (isset($_POST['add_mediacion_atencion'])) {

    if (empty($errors)) {
		$id = (int)$e_detalle['id_inventario_macs'];
		$ejercicio = remove_junk($db->escape($_POST['ejercicio']));
		$mes = remove_junk($db->escape($_POST['mes']));
		$fecha_informe = $ejercicio."-".$mes."-"."01";
        $num_quejas_recibidas   = remove_junk($db->escape($_POST['num_quejas_recibidas']));
        $num_sesiones_programadas   = remove_junk($db->escape($_POST['num_sesiones_programadas']));
		$num_sesiones_desahogadas = remove_junk($db->escape($_POST['num_sesiones_desahogadas']));
		$num_conciliaciones   = remove_junk($db->escape($_POST['num_conciliaciones']));
        $num_convenios   = remove_junk($db->escape($_POST['num_convenios']));
		$num_actas_llamadas = remove_junk($db->escape($_POST['num_actas_llamadas']));
        $num_actas_comparecencia   = remove_junk($db->escape($_POST['num_actas_comparecencia']));
        $num_actas_circunstanciadas   = remove_junk($db->escape($_POST['num_actas_circunstanciadas']));
		$num_quejas_enviadas = remove_junk($db->escape($_POST['num_quejas_enviadas']));
        $num_quejas_visitadurias   = remove_junk($db->escape($_POST['num_quejas_visitadurias']));
        $num_quejas_tramite   = remove_junk($db->escape($_POST['num_quejas_tramite']));
		$num_quejas_concluidas = remove_junk($db->escape($_POST['num_quejas_concluidas']));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));
		
		
		$sql = "UPDATE inventario_macs SET 
		fecha_informe='{$fecha_informe}', 
		num_quejas_recibidas='{$num_quejas_recibidas}', 
		num_sesiones_programadas='{$num_sesiones_programadas}', 
		num_sesiones_desahogadas='{$num_sesiones_desahogadas}', 
		num_conciliaciones='{$num_conciliaciones}', 
		num_convenios='{$num_convenios}', 
		num_actas_llamadas='{$num_actas_llamadas}', 
		num_actas_comparecencia='{$num_actas_comparecencia}', 
		num_actas_circunstanciadas='{$num_actas_circunstanciadas}', 
		num_quejas_enviadas='{$num_quejas_enviadas}', 
		num_quejas_visitadurias='{$num_quejas_visitadurias}', 
		num_quejas_tramite='{$num_quejas_tramite}', 
		num_quejas_concluidas='{$num_quejas_concluidas}', 
		observaciones='{$observaciones}', 
		id_indicadores_pat={$id_indicadores_pat}  
		WHERE id_inventario_macs='{$db->escape($id)}'";
        
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó el Inventario de MACS  con Folio: -' . $e_detalle['folio'] . '-.', 2);
            $session->msg('s', " El el Inventario de MACS con folio ".$e_detalle['folio']." ha sido editado con éxito.");
            redirect('mediacion_atencion.php?anio='.$ejercicio, false);
        } else {
            //failed
            $session->msg('d', 'No se detectaron cambios en la información el Inventario de MACS.');
            redirect('edit_mediacion_atencion.php?id='.$id, false);
        }
			
			
    } else {
        $session->msg("d", $errors);
        redirect('mediacion_atencion.php?anio='.$ejercicio, false);
    }
}
?>
<script type="text/javascript">

</script>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Edicion de Inventario de Mediación/Conciliación <?php echo $e_detalle['folio']; ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_mediacion_atencion.php?id=<?php echo (int)$e_detalle['id_inventario_macs']; ?>" ">
    <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ejercicio" class="control-label">Ejercicio</label>
                    <select class="form-control" name="ejercicio" id="ejercicio" required>
                        <option value="">Escoge una opción</option>
                        <option value="2022" <?php if ($ejercicio_act == '2022') echo 'selected="selected"'; ?>  >2022</option>
                        <option value="2023" <?php if ($ejercicio_act == '2023') echo 'selected="selected"'; ?>  >2023</option>
                        <option value="2024" <?php if ($ejercicio_act == '2024') echo 'selected="selected"'; ?>  >2024</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="mes" class="control-label">Mes</label>
                    <select class="form-control" name="mes" id="mes" required>
                        <option value="">Escoge una opción</option>
                        <option value="1" <?php if ($mes_act == '1') echo 'selected="selected"'; ?> >Enero</option>
                        <option value="2" <?php if ($mes_act == '2') echo 'selected="selected"'; ?> >Febrero</option>
                        <option value="3" <?php if ($mes_act == '3') echo 'selected="selected"'; ?> >Marzo</option>
                        <option value="4" <?php if ($mes_act == '4') echo 'selected="selected"'; ?> >Abril</option>
                        <option value="5" <?php if ($mes_act == '5') echo 'selected="selected"'; ?> >Mayo</option>
                        <option value="6" <?php if ($mes_act == '6') echo 'selected="selected"'; ?> >Junio</option>
                        <option value="7" <?php if ($mes_act == '7') echo 'selected="selected"'; ?> >Julio</option>
                        <option value="8" <?php if ($mes_act == '8') echo 'selected="selected"'; ?> >Agosto</option>
                        <option value="9" <?php if ($mes_act == '9') echo 'selected="selected"'; ?> >Septiembre</option>
                        <option value="10" <?php if ($mes_act == '10') echo 'selected="selected"'; ?> >Octubre</option>
                        <option value="11" <?php if ($mes_act == '11') echo 'selected="selected"'; ?> >Noviembre</option>
                        <option value="12" <?php if ($mes_act == '12') echo 'selected="selected"'; ?> >Diciembre</option>
                    </select>
                </div>
            </div>
        </div>
                <div class="row" style="border-radius: 10px 10px 10px 10px;">
                   
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Quejas canalizadas al área</label>
								<input type="number"  class="form-control" max="10000" name="num_quejas_recibidas"  value="<?php echo (int) $e_detalle['num_quejas_recibidas']; ?>" required>                         
							</div>
						</div>
						
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Sesiones programadas</label>
								<input type="number"  class="form-control" max="10000" name="num_sesiones_programadas"  value="<?php echo (int) $e_detalle['num_sesiones_programadas']; ?>" >                         
							</div>
						</div>

						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Sesiones desahogadas</label>
								<input type="number"  class="form-control" max="10000" name="num_sesiones_desahogadas"  value="<?php echo (int) $e_detalle['num_sesiones_desahogadas']; ?>"   >                         
							</div>
						</div>
						
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Conciliaciones y/o mediaciones</label>
								<input type="number"  class="form-control" max="10000" name="num_conciliaciones"  value="<?php echo (int) $e_detalle['num_conciliaciones']; ?>"  >                         
							</div>
						</div>

						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Convenios</label>
								<input type="number"  class="form-control" max="10000" name="num_convenios"  value="<?php echo (int) $e_detalle['num_convenios']; ?>"   >                         
							</div>
						</div>
						
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Actas de llamadas telefónicas</label>
								<input type="number"  class="form-control" max="10000" name="num_actas_llamadas"  value="<?php echo (int) $e_detalle['num_actas_llamadas']; ?>"  >                         
							</div>
						</div>

						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Actas de comparecencia</label>
								<input type="number"  class="form-control" max="10000" name="num_actas_comparecencia"  value="<?php echo (int) $e_detalle['num_actas_comparecencia']; ?>"   >                         
							</div>
						</div>
						
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Actas de sesión y/o circunstanciadas</label>
								<input type="number"  class="form-control" max="10000" name="num_actas_circunstanciadas"  value="<?php echo (int) $e_detalle['num_actas_circunstanciadas']; ?>"  >                         
							</div>
						</div>

						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Quejas remitidas a Coordinación de Orientación Legal, Quejas y Seguimiento para vigilancia del convenio</label>
								<input type="number"  class="form-control" max="10000" name="num_quejas_enviadas"  value="<?php echo (int) $e_detalle['num_quejas_enviadas']; ?>"   >                         
							</div>
						</div>
						
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Quejas remitidas a Visitaduría para trámite o por desistimiento</label>
								<input type="number"  class="form-control" max="10000" name="num_quejas_visitadurias"  value="<?php echo (int) $e_detalle['num_quejas_visitadurias']; ?>"  >                         
							</div>
						</div>

						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Quejas concluidas para mediación y/o conciliación</label>
								<input type="number"  class="form-control" max="10000" name="num_quejas_concluidas"  value="<?php echo (int) $e_detalle['num_quejas_concluidas']; ?>"   >                         
							</div>
						</div>
						
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Quejas en trámite pendientes de celebración de sesión y/o mediación</label>
								<input type="number"  class="form-control" max="10000" name="num_quejas_tramite"   value="<?php echo (int) $e_detalle['num_quejas_tramite']; ?>" >                         
							</div>
						</div> 
						<div class="col-md-4">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" required>
                                <option value="">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  <?php if ($e_detalle['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?>  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="observaciones">Observaciones</label>
								<textarea class="form-control" name="observaciones" id="observaciones" cols="30" rows="3"><?php echo $e_detalle['observaciones']; ?></textarea>
							</div>
						</div>
                </div>

               
                <div class="form-group clearfix">
                    <a href="mediacion_atencion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_mediacion_atencion" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>