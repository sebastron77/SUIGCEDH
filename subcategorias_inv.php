<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Categorías del Inventario';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel_user = $user['user_level'];
$subcategorias_inv = find_all_cat_subcat();
// $subcategorias_inv = find_all_order_by('cat_categorias_inv', 'descripcion', 'padre', 0); 

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
                    <span>SUBCATEGORÍAS DEL INVENTARIO</span>
                </strong>
                <a href="add_subcategorias_inv.php" class="btn btn-info pull-right">Agregar Subcategoría</a>
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 5%;">#</th>
                            <th class="text-center" style="width: 40%;">Subcategoría</th>
                            <th class="text-center" style="width: 40%;">Categoría Padre</th>
                            <th class="text-center" style="width: 15%;">Nivel</th>
                            <?php if ($nivel_user == 1 || $nivel_user == 14) : ?>
                                <th style="width: 15%;" class="text-center">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subcategorias_inv as $subcat_inv) : ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td>
                                    <?php echo $subcat_inv['categoria']; ?>
                                </td>
                                <td>
                                    <?php echo $subcat_inv['desc_padre']; ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $subcat_inv['nivel']; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($nivel_user == 1 || $nivel_user == 14) : ?>
                                        <div class="btn-group">
                                            <a href="edit_subcategorias_inv.php?id=<?php echo (int)$subcat_inv['id_categoria_inv']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
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