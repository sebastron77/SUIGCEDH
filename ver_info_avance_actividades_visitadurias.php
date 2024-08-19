<?php
$page_title = 'Registro de Avances Visitadurías';
require_once('includes/load.php');
?>
<?php

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 5) {
    page_require_level_exacto(5);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}
if ($nivel_user == 53) {
    page_require_level_exacto(53);
}

if ($nivel_user > 3 && $nivel_user < 5) :
    redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 50) :
    redirect('home.php');
endif;
if ($nivel_user > 50 && $nivel_user < 53) :
    redirect('home.php');
endif;
$e_detalle = find_by_id('atencion_seguimiento', (int)$_GET['id'], 'id_atencion_seguimiento');
$area_informe = find_by_id('area', $e_detalle['id_area'], 'id_area');

if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$e_detalle['folio'], 5);   
}

$tipo_atencion = find_all_order('cat_tipo_atencion', 'id_cat_tipo_atencion');
$accion = find_by_atencion_seguimiento((int)$_GET['id']);
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
                    <span>Avances  <?php echo $area_informe['nombre_area'];?> <?php echo $e_detalle['folio'] ?></span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="">Folio</th>
                            <th style="">Ejercicio</th>
                            <th style="">Mes</th>
                            <th style="">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($e_detalle['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_detalle['ejercicio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_detalle['mes'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_detalle['observaciones'])) ?></td>
                        </tr>
                    </tbody>
                </table>

				<table class="table table-bordered table-striped" style="width: 50%;margin: 0 auto;">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style=""></th>
                            <th style="">Número de Atención/Seguimiento</th>
                        </tr>
                    </thead>
                <?php foreach ($accion as $tipo) : ?>
                    <tbody>
                        <tr>                                                                                 
                            <td><?php echo remove_junk(ucwords($tipo['descripcion'])) ?></td>                      
                            <td><?php echo remove_junk(ucwords($tipo['numero_accion'])) ?></td>                      
                           
							
                        </tr>
                    </tbody>
					<?php endforeach; ?>
                </table>
				<a href="avance_actividades_visitadurias.php?a=<?php echo $e_detalle['id_area'];?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>