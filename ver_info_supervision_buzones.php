<?php
$page_title = 'Supervisión de Buzones';
require_once('includes/load.php');
?>
<?php

$user = current_user();
$nivel = $user['user_level'];
$nivel_user = $user['user_level'];
$id_u = $user['id_user'];
if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 6) {
    page_require_level_exacto(6);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la '.$page_title.' del Ejercicio '.$ejercicio, 5); 
    page_require_level_exacto(7);
}

if ($nivel_user == 24) {
    page_require_level_exacto(24);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la '.$page_title.' del Ejercicio '.$ejercicio, 5); 
    page_require_level_exacto(53);
}

if ($nivel_user > 2 && $nivel_user < 6) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 24) :
    redirect('home.php');
endif;
if ($nivel_user > 24 && $nivel_user < 53) :
    redirect('home.php');
endif;



$supervision = find_by_id('supervision_buzones', (int)$_GET['id'], 'id_supervision_buzones');
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
                    <span>Supervisión de Buzones <?php echo $supervision['folio'] ?></span>
                </strong>
                <!-- <a href="add_convenio.php" class="btn btn-info pull-right">Agregar convenio</a> -->
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 18%;">Folio</th>
                            <th >Fecha de Supervisión</th>                      
                            <th >Lugar de  Supervisión</th>                      
                            <th >No. de Quejas Captadas</th>                      
                            <th >¿Quién Atendió?</th>                     
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($supervision['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($supervision['fecha_supervision'])) ?></td>
                            <td><?php echo remove_junk(ucwords($supervision['lugar_supervision'])) ?></td>
                            <td><?php echo remove_junk(ucwords($supervision['numero_quejas'])) ?></td>
                            <td><?php echo remove_junk(ucwords($supervision['quien_atendio'])) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
				
				<table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th >Observaciones</th>                      
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($supervision['observaciones'])) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
				
				<br>
				<br>
                <a href="supervision_buzones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>