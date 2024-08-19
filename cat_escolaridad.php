<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Escolaridades';
require_once('includes/load.php');

// page_require_level(2);
$all_escolaridad = find_all_order('cat_escolaridad', 'descripcion');
$user = current_user();
$nivel = $user['user_level'];

$id_user = $user['id_user'];
$busca_area = area_usuario($id_user);
$otro = $busca_area['nivel_grupo'];
$nivel_user = $user['user_level'];

if ($nivel_user == 50) {
    page_require_level_exacto(50);
}

if ($nivel_user > 2 && $nivel_user < 50):
    redirect('home.php');
endif;

if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la '.$page_title, 5);   
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
                    <span>Catálogo de Escolaridad <span>
                </strong>
                <?php if ($otro == 1 || $nivel == 1 || $id_user == 46) : ?>
                    <a href="add_escolaridad.php" class="btn btn-info pull-right btn-md"> Agregar Escolaridad</a>
                <?php endif ?>
            </div>
            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 40%;">Nombre de la Escolaridad</th>
                            <th class="text-center" style="width: 20%;">Estatus</th>
                            <?php if ($otro == 1 || $nivel == 1 || $id_user == 46) : ?>
                                <th class="text-center" style="width: 15%;">Acciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_escolaridad as $a_escolaridad) : ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td><?php echo remove_junk(ucwords($a_escolaridad['descripcion'])) ?></td>
                                <td class="text-center">
                                    <?php if ($a_escolaridad['estatus'] === '1') : ?>
                                        <span class="label label-success"><?php echo "Activa"; ?></span>
                                    <?php else : ?>
                                        <span class="label label-danger"><?php echo "Inactiva"; ?></span>
                                    <?php endif; ?>
                                </td>
                                <?php if ($otro == 1 || $nivel == 1 || $id_user == 46) : ?>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <?php if ($otro == 1 || $nivel == 1 || $id_user == 46) : ?>
                                                <a href="edit_escolaridad.php?id=<?php echo (int)$a_escolaridad['id_cat_escolaridad']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                    <i class="glyphicon glyphicon-pencil"></i>
                                                </a>
                                            <?php endif ?>
                                            <?php if (($nivel == 1) && ($a_escolaridad['id_cat_escolaridad'] != 1)) : ?>

                                                <?php if ($a_escolaridad['estatus'] == 0) : ?>
                                                    <a href="activate_escolaridad.php?id=<?php echo (int)$a_escolaridad['id_cat_escolaridad']; ?>&a=0" class="btn btn-success btn-md" title="Activar" data-toggle="tooltip">
                                                        <span class="glyphicon glyphicon-ok"></span>
                                                    </a>
                                                <?php else : ?>
                                                    <a href="activate_escolaridad.php?id=<?php echo (int)$a_escolaridad['id_cat_escolaridad']; ?>&a=1" class="btn btn-md btn-danger" data-toggle="tooltip" title="Inactivar">
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