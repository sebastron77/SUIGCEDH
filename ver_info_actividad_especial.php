<?php
$page_title = 'Actividades Especiales';
require_once('includes/load.php');
?>
<?php

$actividad = find_by_id('actividades_especiales', (int)$_GET['id'], 'id_actividades_especiales');
$nombre_eje =find_campo_id('cat_ejes_estrategicos', $actividad['id_cat_ejes_estrategicos'], 'id_cat_ejes_estrategicos','descripcion');
$nombre_agenda =find_campo_id('cat_agendas', $actividad['id_cat_agendas'], 'id_cat_agendas','descripcion');

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 3) {
    page_require_level_exacto(3);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7) :
    redirect('home.php');
endif;

if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$actividad['folio'], 5);   
}

?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Actividades Especiales  <?php echo $actividad['folio'] ?></span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 3%;">Folio</th>
                            <th style="width: 3%;">Fecha Actividad</th>
                            <th style="width: 5%;">Tema Actividad</th>
                            <th style="width: 3%;">Lugar Actividad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($actividad['folio'])) ?></td>
                            <td><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($actividad['fecha_actividad'])))) ?></td>
                            <td><?php echo remove_junk(ucwords($actividad['tema_actividad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($actividad['lugar_actividad'])) ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 3%;">Eje Estratégico</th>
                            <th style="width: 3%;">Agenda</th>
                            <th style="width: 3%;">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>                            
                            <td><?php echo remove_junk(ucwords($nombre_eje['descripcion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($nombre_agenda['descripcion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($actividad['observaciones'])) ?></td>                           
                        </tr>
                    </tbody>
                </table>
                
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 3%;">Asistentes Hombres</th>
                            <th style="width: 3%;">Asistentes Mujeres</th>
                            <th style="width: 3%;">Asistentes No Binarios</th>
                            <th style="width: 3%;">Asistentes Otros</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>                            
                            <td><?php echo remove_junk(ucwords($actividad['asistentes_hombres'])) ?></td>
                            <td><?php echo remove_junk(ucwords($actividad['asistentes_mujeres'])) ?></td>
                            <td><?php echo remove_junk(ucwords($actividad['asistentes_nobinario'])) ?></td>
                            <td><?php echo remove_junk(ucwords($actividad['asistentes_otros'])) ?></td>                           
                        </tr>
                    </tbody>
                </table>
                
                <a href="actividad_especial.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>