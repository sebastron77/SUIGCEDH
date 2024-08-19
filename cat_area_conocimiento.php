<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Áreas del Conocimento';
require_once('includes/load.php');

// page_require_level(2);
$all_conocimiento = find_all_order('cat_area_conocimiento', 'descripcion');
$user = current_user();
$nivel = $user['user_level'];


$user = current_user();
$id_user = $user['id_user'];
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
if ($nivel_user > 14) :
    redirect('home.php');
endif;

?>

?>
<?php header('Content-Type: text/html; charset=utf-8');
	include_once('layouts/header.php'); ?>

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
                    <span>Catálogo de Áreas del Conocimento<span>
                </strong>
                <?php if ( $nivel_user <= 2 || $nivel_user == 14) : ?>
                    <a href="add_area_conocimiento.php" class="btn btn-info pull-right btn-md"> Agregar Áreas del Conocimento</a>
                <?php endif ?>
				
            </div>
            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 40%;">Nombre del Áreas del Conocimento</th>
                            <th class="text-center" style="width: 20%;">Estatus</th>
                            <?php if ($nivel_user <= 2 || $nivel_user == 14) : ?>
                                <th class="text-center" style="width: 15%;">Acciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_conocimiento as $datos) : ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td><?php echo remove_junk(ucwords($datos['descripcion'])) ?></td>
                                <td class="text-center">
                                    <?php if ($datos['estatus'] === '1') : ?>
                                        <span class="label label-success"><?php echo "Activa"; ?></span>
                                    <?php else : ?>
                                        <span class="label label-danger"><?php echo "Inactiva"; ?></span>
                                    <?php endif; ?>
                                </td>
                                <?php if ( $nivel_user <= 2 || $nivel_user == 14) : ?>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <?php if ($nivel_user <= 2 || $nivel_user == 14) : ?>
                                                <a href="edit_area_conocimiento.php?id=<?php echo (int)$datos['id_cat_area_con']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                    <i class="glyphicon glyphicon-pencil"></i>
                                                </a>
                                            <?php endif ?>
                                            <?php if (($nivel_user <= 2 || $nivel_user == 14) && ($datos['id_cat_area_con'] != 1)) : ?>

                                                <?php if ($datos['estatus'] == 0) : ?>
                                                    <a href="activate_area_conocimiento.php?id=<?php echo (int)$datos['id_cat_area_con']; ?>&a=0" class="btn btn-success btn-md" title="Activar" data-toggle="tooltip" onclick="return confirm('¿Seguro que deseas activar el Área del Conocimiento? ');">
                                                        <span class="glyphicon glyphicon-ok"></span>
                                                    </a>
                                                <?php else : ?>
                                                    <a href="activate_area_conocimiento.php?id=<?php echo (int)$datos['id_cat_area_con']; ?>&a=1" class="btn btn-md btn-danger" data-toggle="tooltip" title="Inactivar" onclick="return confirm('¿Seguro que deseas desctivar el Área del Conocimiento? ');">
                                                        <i class="glyphicon glyphicon-ban-circle"></i>
                                                    </a>
                                                <?php endif; ?>                                               
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                <?php endif ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>