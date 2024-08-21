<?php
$page_title = 'Ver Vehículo';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel_user = $user['user_level'];
$vehiculo = find_by_id_vehiculo((int) $_GET['id']);

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
                    <span>Información del Vehículo</span>
                </strong>
                <!-- <a href="add_convenio.php" class="btn btn-info pull-right">Agregar convenio</a> -->
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 3%;">Marca</th>
                            <th class="text-center" style="width: 3%;">Modelo</th>
                            <th class="text-center" style="width: 1%;">Año</th>
                            <th class="text-center" style="width: 2%;">No. Serie</th>
                            <th class="text-center" style="width: 2%;">Placas</th>
                            <th class="text-center" style="width: 5%;">Color</th>
                            <th class="text-center" style="width: 1%;">No. Puertas</th>
                            <th class="text-center" style="width: 1%;">No. Cilindros</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo $vehiculo['marca'] ?></td>
                            <td class="text-center"><?php echo $vehiculo['modelo'] ?></td>
                            <td class="text-center"><?php echo $vehiculo['anio'] ?></td>
                            <td class="text-center"><?php echo $vehiculo['no_serie'] ?></td>
                            <td class="text-center"><?php echo $vehiculo['placas'] ?></td>
                            <td class="text-center"><?php echo $vehiculo['color'] ?></td>
                            <td class="text-center"><?php echo $vehiculo['no_puertas'] ?></td>
                            <td class="text-center"><?php echo $vehiculo['no_cilindros'] ?></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 1%;">Tipo de Combustible</th>
                            <th class="text-center" style="width: 1%;">Compañía de Seguro</th>
                            <th class="text-center" style="width: 1%;">No. Póliza</th>
                            <th class="text-center" style="width: 1%;">Documento Póliza</th>
                            <th class="text-center" style="width: 1%;">Tarjeta Circulación</th>
                            <th class="text-center" style="width: 1%;">Factura</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo $vehiculo['combustible'] ?></td>
                            <td class="text-center"><?php echo $vehiculo['compania_seguros'] ?></td>
                            <td class="text-center"><?php echo $vehiculo['no_poliza'] ?></td>
                            <td><a target="_blank" style="color: #3D94FF;"href="uploads/parquevehicular/vehiculos/<?php echo $vehiculo['id_vehiculo'] . '/' . $vehiculo['documento_poliza']; ?>"><?php echo $vehiculo['documento_poliza']; ?></a></td>
                            <td><a target="_blank" style="color: #3D94FF;"href="uploads/parquevehicular/vehiculos/<?php echo $vehiculo['id_vehiculo'] . '/' . $vehiculo['tarjeta_circulacion']; ?>"><?php echo $vehiculo['tarjeta_circulacion']; ?></a></td>
                            <td><a target="_blank" style="color: #3D94FF;"href="uploads/parquevehicular/vehiculos/<?php echo $vehiculo['id_vehiculo'] . '/' . $vehiculo['factura']; ?>"><?php echo $vehiculo['factura']; ?></a></td>
                        </tr>
                    </tbody>
                </table>
                <a href="control_vehiculos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>