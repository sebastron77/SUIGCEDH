<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Préstamo de Vehículos';
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
$all_prestamos = find_all_prestamos_vehiculos();
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
                    <span>Lista de Préstamos de los Vehículos</span>
                </strong>
                <?php if (($nivel_user <= 2) || ($nivel_user == 6)) : ?>
                    <a href="add_prestamo_vehiculo.php" class="btn btn-info pull-right">Agregar préstamo</a>
                <?php endif; ?>
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 10%;">Vehículo</th>
                            <th class="text-center" style="width: 2%;">Placas</th>
                            <th class="text-center" style="width: 7%;">Fecha y Hora de Préstamo</th>
                            <th class="text-center" style="width: 7%;">Fecha y Hora de Regreso</th>
                            <th class="text-center" style="width: 10%;">Prestado a</th>
                            <!-- <th class="text-center" style="width: 5%;">Estatus</th> -->
                            <?php if ($nivel_user == 1 || $nivel_user == 14) : ?>
                                <th style="width: 1%;" class="text-center">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_prestamos as $a_prestamo) : ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_prestamo['vehiculo'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_prestamo['placas'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_prestamo['fecha_hora_presta'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_prestamo['fecha_hora_regresa'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_prestamo['nombre'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($nivel_user == 1 || $nivel_user == 14) : ?>
                                        <div class="btn-group">
                                            <a href="ver_info_prestamo.php?id=<?php echo (int) $a_prestamo['id_rel_prestamo_vehiculo']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                                <i class="glyphicon glyphicon-eye-open"></i>
                                            </a>
                                            <a href="edit_prestamo_vehiculo.php?id=<?php echo (int) $a_prestamo['id_rel_prestamo_vehiculo']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                <i class="glyphicon glyphicon-pencil"></i>
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