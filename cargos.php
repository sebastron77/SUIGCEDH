<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de cargos';
require_once('includes/load.php');

// page_require_level(2);
$all_cargos = find_all_cargos();
$user = current_user();
$nivel = $user['user_level'];

//$id_usuario = $user['id'];
$id_user = $user['id_user'];
$busca_area = area_usuario($id_user);
$otro = $busca_area['nivel_grupo'];
$nivel_user = $user['user_level'];
//@$level = find_user_level('users', (int)$_GET['id']);

if ($nivel_user == 1) {
    page_require_level_exacto(1);
	$acceso_permitido=true;
}
if ($nivel_user == 2) {
    page_require_level_exacto(2);
	$acceso_permitido=true;
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
	$acceso_permitido=true;
}


if ($nivel_user > 2 && $nivel_user < 7):
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 14):
    redirect('home.php');
endif;
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
                    <span>Cargos de la CEDH</span>
                </strong>
                <?php if ($acceso_permitido ) : ?>
                    <a href="add_cargo.php" class="btn btn-info pull-right btn-md"> Agregar cargo</a>
                <?php endif ?>
            </div>
            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 20%;">Nombre del cargo</th>
                            <th class="text-center" style="width: 30%;">Área</th>
                            <th class="text-center" style="width: 10%;">Estatus</th>
                            <?php if ($acceso_permitido) : ?>
                                <th class="text-center" style="width: 10%;">Acciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_cargos as $a_cargo) : ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td><?php echo remove_junk(ucwords($a_cargo['nombre_cargo'])) ?></td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_cargo['nombre_area'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($a_cargo['estatus_cargo'] === '1') : ?>
                                        <span class="label label-success"><?php echo "Activa"; ?></span>
                                    <?php else : ?>
                                        <span class="label label-danger"><?php echo "Inactiva"; ?></span>
                                    <?php endif; ?>
                                </td>
                                <?php if ($acceso_permitido) : ?>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="edit_cargo.php?id=<?php echo (int)$a_cargo['id_cargos']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                <i class="glyphicon glyphicon-pencil"></i>
                                            </a>
                                            <?php if (($nivel == 1) && ($a_cargo['id_cargos'] != 1)) : ?>
                                                <?php if ($a_cargo['estatus_cargo'] == 0) : ?>
                                                    <a href="activate_cargo.php?id=<?php echo (int)$a_cargo['id_cargos']; ?>" class="btn btn-success btn-md" title="Activar" data-toggle="tooltip">
                                                        <span class="glyphicon glyphicon-ok"></span>
                                                    </a>
                                                <?php else : ?>
                                                    <a href="inactivate_cargo.php?id=<?php echo (int)$a_cargo['id_cargos']; ?>" class="btn btn-danger btn-md" title="Inactivar" data-toggle="tooltip">
                                                        <span class="glyphicon glyphicon-ban-circle"></span>
                                                    </a>
                                                <?php endif; ?>
                                                <!-- <a href="delete_cargo.php?id=<?php echo (int)$a_cargo['id_cargos']; ?>" class="btn btn-md btn-delete" data-toggle="tooltip" title="Eliminar" onclick="return confirm('¿Seguro que deseas eliminar este cargo? Los trabajadores relacionados a este cargo se establecerán como *Sin cargo*.');">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </a> -->
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