<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Áreas';
require_once('includes/load.php');

// page_require_level(2);
$all_areas = find_all_order('area', 'nombre_area');
$user = current_user();
$nivel = $user['user_level'];

//$id_usuario = $user['id'];

// $user = current_user();
$id_user = $user['id_user'];
$busca_area = area_usuario($id_user);
$otro = $busca_area['nivel_grupo'];
$nivel_user = $user['user_level'];
$acceso_permitido=false;

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
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}

if ($nivel_user > 2 && $nivel_user < 7):
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 14):
    redirect('home.php');
endif;
if ($nivel_user > 14 && $nivel_user < 50):
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
                    <span>Áreas de trabajo de la CEDH</span>
                </strong>
                <?php if ($acceso_permitido ) : ?>
                    <a href="add_area.php" class="btn btn-info pull-right btn-md"> Agregar área</a>
                <?php endif ?>
            </div>
            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 40%;">Nombre del área</th>
                            <th class="text-center" style="width: 15%;">Abreviación del área</th>
                            <th class="text-center" style="width: 20%;">Estatus del área</th>
                            <?php if ($acceso_permitido) : ?>
                                <th class="text-center" style="width: 15%;">Acciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_areas as $a_area) : ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td><?php echo remove_junk(ucwords($a_area['nombre_area'])) ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords($a_area['abreviatura'])) ?></td>
                                <td class="text-center">
                                    <?php if ($a_area['estatus_area'] === '1') : ?>
                                        <span class="label label-success"><?php echo "Activa"; ?></span>
                                    <?php else : ?>
                                        <span class="label label-danger"><?php echo "Inactiva"; ?></span>
                                    <?php endif; ?>
                                </td>
                                <?php if ($acceso_permitido) : ?>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <?php if ($acceso_permitido) : ?>
                                                <a href="edit_area.php?id=<?php echo (int)$a_area['id_area']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                    <i class="glyphicon glyphicon-pencil"></i>
                                                </a>
                                            <?php endif ?>
                                            <?php if ($a_area['id_area'] != 1) : ?>

                                                <?php if ($a_area['estatus_area'] == 0) : ?>
                                                    <a href="activate_area.php?id=<?php echo (int)$a_area['id_area']; ?>" class="btn btn-success btn-md" title="Activar" data-toggle="tooltip">
                                                        <span class="glyphicon glyphicon-ok"></span>
                                                    </a>
                                                <?php else : ?>
                                                    <a href="inactivate_area.php?id=<?php echo (int)$a_area['id_area']; ?>" class="btn btn-md btn-danger" data-toggle="tooltip" title="Inactivar">
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