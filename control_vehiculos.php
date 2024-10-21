<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Vehículos';
require_once('includes/load.php');
?>
<?php

$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}
if ($nivel_user == 2) {
    page_require_level_exacto(2);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
}
if ($nivel_user > 2 && $nivel_user < 14) :
    redirect('home.php');
endif;
if ($nivel_user > 14) {
    redirect('home.php');
}
if (!$nivel_user) {
    redirect('home.php');
}
$all_vehiculos = find_all('vehiculos');
?>

<?php include_once('layouts/header.php'); ?>
<a href="solicitudes_vehiculos.php" class="btn btn-success">Regresar</a><br><br>
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
                    <span>Lista de Vehículos</span>
                </strong>
                <?php if (($nivel_user <= 2) || ($nivel_user == 6)) : ?>
                    <a href="add_vehiculo.php" class="btn btn-info pull-right">Agregar Vehículo</a>
                <?php endif; ?>
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 1%;">#</th>
                            <th class="text-center" style="width: 7%;">Marca</th>
                            <th class="text-center" style="width: 7%;">Modelo</th>
                            <th class="text-center" style="width: 7%;">Año</th>
                            <th class="text-center" style="width: 7%;">Color</th>
                            <th class="text-center" style="width: 7%;">Placas</th>
                            <?php if ($nivel_user == 1 || $nivel_user == 14) : ?>
                                <th style="width: 1%;" class="text-center">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_vehiculos as $a_vehiculo) : ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_vehiculo['marca'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_vehiculo['modelo'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_vehiculo['anio'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_vehiculo['color'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_vehiculo['placas'])) ?>
                                </td>
                                <!-- <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_vehiculo['estatus'])) ?>
                                </td> -->
                                <td class="text-center">
                                    <?php if ($nivel_user == 1 || $nivel_user == 14) : ?>
                                        <div class="btn-group">
                                            <a href="ver_info_vehiculo.php?id=<?php echo (int) $a_vehiculo['id_vehiculo']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                                <span class="material-symbols-outlined" style="font-size: 22px; color: white; margin-top: 8px;">
                                                    visibility
                                                </span>
                                            </a>
                                            <a href="edit_vehiculo.php?id=<?php echo (int) $a_vehiculo['id_vehiculo']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                <span class="material-symbols-outlined" style="font-size: 22px; color: black; margin-top: 8px;">
                                                    edit
                                                </span>
                                            </a>
                                            <a href="reparacion_vehiculo.php?id=<?php echo (int) $a_vehiculo['id_vehiculo']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Reparaciones" style="background-color:#FF3D51; border-color: #FF3D51">
                                                <span class="material-symbols-outlined" style="font-size: 22px; color: white; margin-top: 8px;">
                                                    build
                                                </span>
                                            </a>
                                            <a href="servicio_vehiculo.php?id=<?php echo (int) $a_vehiculo['id_vehiculo']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Servicio" style="background-color:#0094DE; border-color: #0094DE;">
                                                <span class="material-symbols-outlined" style="font-size: 22px; color: white; margin-top: 8px;">
                                                    no_crash
                                                </span>
                                            </a>
                                            <?php
                                                $mes = date('n');
                                                $ejercicio = date('Y');
                                            ?>
                                            <a href="bitacora_vehiculo.php?id=<?php echo (int) $a_vehiculo['id_vehiculo']; ?>&mes=<?php echo $mes;?>&ejercicio=<?php echo $ejercicio;?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Bitácora" style="background-color:#7437AD; border-color: #7437AD;">
                                                <span class="material-symbols-outlined" style="font-size: 22px; color: white; margin-top: 8px;">
                                                    list_alt
                                                </span>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>