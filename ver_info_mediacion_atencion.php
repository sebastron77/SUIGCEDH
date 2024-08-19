<?php
$page_title = 'Inventario de Mediación/Conciliación';
require_once('includes/load.php');
?>
<?php


$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) :
    page_require_level(2);
endif;
if ($nivel_user == 7) :
    page_require_level_exacto(7);
endif;
if ($nivel_user == 19) :
    page_require_level_exacto(19);
endif;
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}
if ($nivel_user == 53) {
    page_require_level_exacto(53);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 19) :
    redirect('home.php');
endif;
if ($nivel_user > 19 && $nivel_user < 50) :
    redirect('home.php');
endif;
if ($nivel_user > 50 && $nivel_user < 53) :
    redirect('home.php');
endif;

if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$a_reunion['folio'], 5);   
}

$e_detalle = find_by_id('inventario_macs', (int)$_GET['id'], 'id_inventario_macs');
$ejercicio_act = date("Y",strtotime($e_detalle['fecha_informe']));   
$mes_act = date("m",strtotime($e_detalle['fecha_informe']));
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
 <a href="mediacion_atencion.php?anio=<?php echo $ejercicio_act?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
				<br><br>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Inventario de Mediación/Conciliación <?php echo $e_detalle['folio']; ?></span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 8%;">Folio</th>
                            <th >Ejercicio</th>                      
                            <th >Mes</th>                
                            <th >Quejas canalizadas al área</th>
                            <th >Sesiones programadas</th>
                            <th >Sesiones desahogadas</th>
                            <th >Conciliaciones y/o mediaciones</th>
                            <th >Convenios</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($e_detalle['folio'])) ?></td>
                            <td><?php echo $ejercicio_act ?></td>
                            <td><?php  if ($mes_act == '1') echo 'Enero'; 
											if ($mes_act == '2') echo 'Febrero'; 
											if ($mes_act == '3') echo 'Marzo'; 
											if ($mes_act == '4') echo 'Abril'; 
											if ($mes_act == '5') echo 'Mayo'; 
											if ($mes_act == '6') echo 'Junio'; 
											if ($mes_act == '7') echo 'Julio'; 
											if ($mes_act == '8') echo 'Agosto'; 
											if ($mes_act == '9') echo 'Septiembre'; 
											if ($mes_act == '10') echo 'Octubre'; 
											if ($mes_act == '11') echo 'Noviembre'; 
											if ($mes_act == '12') echo 'Diciembre'; ?>
                             <td><?php echo remove_junk(($e_detalle['num_quejas_recibidas'])) ?></td>
                            <td><?php echo remove_junk(($e_detalle['num_sesiones_programadas'])) ?></td>
                            <td><?php echo remove_junk(($e_detalle['num_sesiones_desahogadas'])) ?></td>
                            <td><?php echo remove_junk(($e_detalle['num_conciliaciones'])) ?></td>
                            <td><?php echo remove_junk(($e_detalle['num_convenios'])) ?></td>
                        </tr>
                    </tbody>
                </table>
					
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">                        
						<tr style="height: 10px;">
                            <th >Actas de llamadas telefónicas</th>
                            <th >Actas de comparecencia</th>
                            <th >Actas de sesión y/o circunstanciadas</th>
                            <th >Quejas remitidas a Coordinación de Orientación Legal, Quejas y Seguimiento para vigilancia del convenio</th>
                            <th >Quejas remitidas a Visitaduría para trámite o por desistimiento</th>
                            <th >Quejas concluidas para mediación y/o conciliación</th>
                            <th >Quejas en trámite pendientes de celebración de sesión y/o mediación</th>
                            <th >Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(($e_detalle['num_actas_llamadas'])) ?></td>
                            <td><?php echo remove_junk(($e_detalle['num_actas_comparecencia'])) ?></td>
                            <td><?php echo remove_junk(($e_detalle['num_actas_circunstanciadas'])) ?></td>
                            <td><?php echo remove_junk(($e_detalle['num_quejas_enviadas'])) ?></td>
                            <td><?php echo remove_junk(($e_detalle['num_quejas_visitadurias'])) ?></td>
                            <td><?php echo remove_junk(($e_detalle['num_quejas_tramite'])) ?></td>
                            <td><?php echo remove_junk(($e_detalle['num_quejas_concluidas'])) ?></td>
                            <td><?php echo remove_junk(($e_detalle['observaciones'])) ?></td>
                            
                        </tr>
                    </tbody>
                       
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>