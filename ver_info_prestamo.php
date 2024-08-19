<?php
$page_title = 'Ver Préstamo de Vehículo';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel_user = $user['user_level'];
$prestamo = find_by_id_prestamo((int) $_GET['id']);
$id_prestamo = (int) $_GET['id'];

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
                    <span>Información del Préstamo del Vehículo</span>
                </strong>
                <!-- <a href="add_convenio.php" class="btn btn-info pull-right">Agregar convenio</a> -->
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 10%;">Vehículo</th>
                            <th class="text-center" style="width: 15%;">Área del Responsable</th>
                            <th class="text-center" style="width: 15%;">Responsable del Préstamo</th>
                            <th class="text-center" style="width: 10%;">Retiro del Vehículo</th>
                            <th class="text-center" style="width: 10%;">Devolución del Vehículo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo $prestamo['vehiculo'] ?></td>
                            <td class="text-center"><?php echo $prestamo['nombre_area'] ?></td>
                            <td class="text-center"><?php echo $prestamo['nombre'] ?></td>
                            <td class="text-center"><?php echo $prestamo['fecha_hora_presta'] ?></td>
                            <td class="text-center"><?php echo $prestamo['fecha_hora_regresa'] ?></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 5%;">Km. Inicial</th>
                            <th class="text-center" style="width: 5%;">Km. Final</th>
                            <th class="text-center" style="width: 10%;">Motivo del Préstamo</th>
                            <th class="text-center" style="width: 10%;">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo $prestamo['km_inicial'] ?></td>
                            <td class="text-center"><?php echo $prestamo['km_final'] ?></td>
                            <td class="text-center"><?php echo $prestamo['motivo_prestamo'] ?></td>
                            <td class="text-center"><?php echo $prestamo['observaciones'] ?></td>
                        </tr>
                    </tbody>
                </table>
                <a href="prestamo_vehiculos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>