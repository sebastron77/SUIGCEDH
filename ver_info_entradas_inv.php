<?php
$page_title = 'Ver Entrada de Inventario';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel_user = $user['user_level'];
$entrada_inv = find_by_id_entrada_inv((int) $_GET['id']);

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
                    <span>Información de la Entrada al Inventario</span>
                </strong>
                <!-- <a href="add_convenio.php" class="btn btn-info pull-right">Agregar convenio</a> -->
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 5%;">Categoría</th>
                            <th class="text-center" style="width: 5%;">Marca</th>
                            <th class="text-center" style="width: 5%;">Modelo</th>
                            <th class="text-center" style="width: 5%;">No. Serie</th>
                            <th class="text-center" style="width: 5%;">Material</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo $entrada_inv['categoria'] ?></td>
                            <td class="text-center"><?php echo $entrada_inv['marca'] ?></td>
                            <td class="text-center"><?php echo $entrada_inv['modelo'] ?></td>
                            <td class="text-center"><?php echo $entrada_inv['no_serie'] ?></td>
                            <td class="text-center"><?php echo $entrada_inv['material'] ?></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 10%;">Especificaciones</th>
                            <th class="text-center" style="width: 5%;">Cantidad de Compra</th>
                            <th class="text-center" style="width: 5%;">Precio Unitario</th>
                            <th class="text-center" style="width: 5%;">Fecha de Compra</th>
                            <th class="text-center" style="width: 10%;">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php $o_fecha = date("d/m/Y", strtotime($entrada_inv['fecha_compra'])); ?>
                            <td class="text-center"><?php echo $entrada_inv['especificaciones'] ?></td>
                            <td class="text-center"><?php echo $entrada_inv['cantidad_compra'] ?></td>
                            <td class="text-center"><?php echo '$' . $entrada_inv['precio_unitario'] ?></td>
                            <td class="text-center"><?php echo $o_fecha ?></td>
                            <td class="text-center"><?php echo $entrada_inv['observaciones'] ?></td>
                        </tr>
                    </tbody>
                </table>
                <a href="entradas_inv.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>