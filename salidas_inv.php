<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Historial de Salidas en el Inventario';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel_user = $user['user_level'];
$salidas_inv = find_all_salidas_inv();

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
<a href="solicitudes_inventario.php" class="btn btn-success">Regresar</a><br><br>
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
                    <span>HISTORIAL DE SALIDAS EN EL INVENTARIO</span>
                    <a href="add_salida_inv.php" class="btn btn-info pull-right">AGREGAR SALIDA</a>
                </strong>
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 1%;">#</th>
                            <th class="text-center" style="width: 5%;">Categoría</th>
                            <th class="text-center" style="width: 2%;">Cantidad Salida</th>
                            <th class="text-center" style="width: 2%;">Cantidad Anterior</th>
                            <th class="text-center" style="width: 10%;">Área Salida</th>
                            <th class="text-center" style="width: 1%;">Fecha Salida</th>
                            <?php if ($nivel_user == 1 || $nivel_user == 14) : ?>
                                <th style="width: 1%;" class="text-center">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($salidas_inv as $sali_inv) :
                            $o_fecha = date("d/m/Y", strtotime($sali_inv['fecha_salida']));
                        ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td>
                                    <?php echo $sali_inv['descripcion'] ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $sali_inv['cantidad_salida'] ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $sali_inv['cantidad_anterior'] ?>
                                </td>
                                <td>
                                    <?php echo remove_junk(ucwords($sali_inv['nombre_area'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $o_fecha ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($nivel_user == 1 || $nivel_user == 14) : ?>
                                        <div class="btn-group">
                                            <a href="edit_salida_inv.php?id=<?php echo (int)$sali_inv['id_rel_salida_inv']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                                <span class="material-symbols-outlined" style="font-size: 22px; color: black; margin-top: 8px;">
                                                    edit
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