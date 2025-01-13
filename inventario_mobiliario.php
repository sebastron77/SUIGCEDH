<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Inventario de Mobiliario';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel_user = $user['user_level'];
$mobiliario = find_by_id_inventario(2);

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
                    <span>INVENTARIO DE MOBILIARIO</span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 1%;">#</th>
                            <th class="text-center" style="width: 5%;">Artículo</th>
                            <th class="text-center" style="width: 5%;">Marca</th>
                            <th class="text-center" style="width: 5%;">Material</th>
                            <th class="text-center" style="width: 1%;">Stock</th>
                            <th class="text-center" style="width: 2%;">Precio Unitario</th>
                            <th class="text-center" style="width: 2%;">Fecha Compra</th>
                            <th class="text-center" style="width: 10%;">Observaciones</th>
                            <?php if ($nivel_user == 1 || $nivel_user == 14) : ?>
                                <th style="width: 1%;" class="text-center">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mobiliario as $m_inv) : ?>
                            <?php if ($m_inv['existencia'] != '') : ?>
                                <tr>
                                    <td class="text-center"><?php echo count_id(); ?></td>
                                    <td>
                                        <?php echo remove_junk(ucwords($m_inv['articulo'])) ?>
                                    </td>
                                    <td>
                                        <?php echo remove_junk(ucwords($m_inv['marca'])) ?>
                                    </td>
                                    <td>
                                        <?php echo remove_junk(ucwords($m_inv['material'])) ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo remove_junk(ucwords($m_inv['cantidad_compra'])) ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo '$' . $m_inv['precio_unitario']; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo remove_junk(date("d/m/Y", strtotime($m_inv['fecha_compra']))) ?>
                                    </td>
                                    <td>
                                        <?php echo remove_junk(ucwords($m_inv['observaciones'])) ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($nivel_user == 1 || $nivel_user == 14) : ?>
                                            <div class="btn-group">
                                                <a href="edit_inv_mobiliario.php?id=<?php echo (int) $m_inv['id_compra_inv']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                    <span class="material-symbols-outlined" style="font-size: 22px; color: black; margin-top: 8px;">
                                                        edit
                                                    </span>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>