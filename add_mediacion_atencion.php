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
?>
<?php


if (isset($_POST['add_mediacion_atencion'])) {

    if (empty($errors)) {
		$id_folio = last_id_folios();
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
		
		if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int) $nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int) $nuevo['contador'] + 1);
            }
        }

        $year = date("Y");
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-IMACS';	
		
		$query = "INSERT INTO inventario_macs (";
        $query .= "folio, fecha_informe	,num_quejas_recibidas,num_sesiones_programadas,num_sesiones_desahogadas,num_conciliaciones,num_convenios,
				num_actas_llamadas,num_actas_comparecencia,num_actas_circunstanciadas,num_quejas_enviadas,num_quejas_visitadurias,num_quejas_tramite,
				num_quejas_concluidas,observaciones,id_indicadores_pat,id_usuario_creador,fecha_creacion";
        $query .= ") VALUES (";
        $query .= "'{$folio}', '{$fecha_informe}','{$num_quejas_recibidas}','{$num_sesiones_programadas}','{$num_sesiones_desahogadas}','{$num_conciliaciones}','{$num_convenios}',
			'{$num_actas_llamadas}','{$num_actas_comparecencia}','{$num_actas_circunstanciadas}','{$num_quejas_enviadas}','{$num_quejas_visitadurias}','{$num_quejas_tramite}',
			'{$num_quejas_concluidas}','{$observaciones}','{$id_indicadores_pat}','{$id_user}',NOW());";        

        $query2 = "INSERT INTO folios (";
        $query2 .= "folio, contador";
        $query2 .= ") VALUES (";
        $query2 .= " '{$folio}','{$no_folio1}'";
        $query2 .= ")";

        if ($db->query($query) && $db->query($query2)) {
            //sucess
            insertAccion($user['id_user'], '"' . $user['username'] . '" dio de alta el Inventario de MACS  con Folio: -' . $folio . '-.', 1);
            $session->msg('s', " El el Inventario de MACS con folio '{$folio}' ha sido agregada con éxito.");
            redirect('mediacion_atencion.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar el el Inventario de MACS.');
            redirect('mediacion_atencion.php', false);
        }
			
			
    } else {
        $session->msg("d", $errors);
        redirect('mediacion_atencion.php' , false);
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
                <span>Alta de Inventario de Mediación/Conciliación</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_mediacion_atencion.php" >
    <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ejercicio" class="control-label">Ejercicio</label>
                    <select class="form-control" name="ejercicio" id="ejercicio" required>
                        <option value="">Escoge una opción</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024" selected="selected">2024</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="mes" class="control-label">Mes</label>
                    <select class="form-control" name="mes" id="mes" required>
                        <option value="">Escoge una opción</option>
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>
                </div>
            </div>
        </div>
                <div class="row" style="border-radius: 10px 10px 10px 10px;">
                   
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Quejas canalizadas al área</label>
								<input type="number"  class="form-control" max="10000" name="num_quejas_recibidas"  required>                         
							</div>
						</div>
						
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Sesiones programadas</label>
								<input type="number"  class="form-control" max="10000" name="num_sesiones_programadas"  value="0">                         
							</div>
						</div>

						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Sesiones desahogadas</label>
								<input type="number"  class="form-control" max="10000" name="num_sesiones_desahogadas"  value="0"  >                         
							</div>
						</div>
						
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Conciliaciones y/o mediaciones</label>
								<input type="number"  class="form-control" max="10000" name="num_conciliaciones"  value="0" >                         
							</div>
						</div>

						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Convenios</label>
								<input type="number"  class="form-control" max="10000" name="num_convenios"  value="0"  >                         
							</div>
						</div>
						
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Actas de llamadas telefónicas</label>
								<input type="number"  class="form-control" max="10000" name="num_actas_llamadas"  value="0" >                         
							</div>
						</div>

						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Actas de comparecencia</label>
								<input type="number"  class="form-control" max="10000" name="num_actas_comparecencia"  value="0"  >                         
							</div>
						</div>
						
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Actas de sesión y/o circunstanciadas</label>
								<input type="number"  class="form-control" max="10000" name="num_actas_circunstanciadas"  value="0" >                         
							</div>
						</div>

						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Quejas remitidas a Coordinación de Orientación Legal, Quejas y Seguimiento para vigilancia del convenio</label>
								<input type="number"  class="form-control" max="10000" name="num_quejas_enviadas"  value="0"  >                         
							</div>
						</div>
						
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Quejas remitidas a Visitaduría para trámite o por desistimiento</label>
								<input type="number"  class="form-control" max="10000" name="num_quejas_visitadurias"  value="0" >                         
							</div>
						</div>

						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Quejas concluidas para mediación y/o conciliación</label>
								<input type="number"  class="form-control" max="10000" name="num_quejas_concluidas"  value="0"  >                         
							</div>
						</div>
						
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Quejas en trámite pendientes de celebración de sesión y/o mediación</label>
								<input type="number"  class="form-control" max="10000" name="num_quejas_tramite"   value="0">                         
							</div>
						</div> 
						
						<div class="col-md-4">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" required>
                                <option value="">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="observaciones">Observaciones</label>
								<textarea class="form-control" name="observaciones" id="observaciones" cols="30" rows="3"></textarea>
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