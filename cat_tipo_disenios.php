<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Diseños';
require_once('includes/load.php');

// page_require_level(2);
$all_disenios = find_all_order('cat_tipo_disenios', 'descripcion');
$user = current_user();
$nivel = $user['user_level'];

$id_user = $user['id_user'];
$busca_area = area_usuario($id_user);
$otro = $busca_area['nivel_grupo'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 15) {
    page_require_level_exacto(15);
}
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user >7  && $nivel_user < 15) :
    redirect('home.php');
endif;
if ($nivel_user > 15 ) :
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
                    <span>Catálogo de Tipos de Diseños<span>
                </strong>
                <?php if ($nivel_user <= 2 || $nivel == 15 ) : ?>
                    <a href="add_tipo_disenios.php" class="btn btn-info pull-right btn-md"> Agregar Tipo Diseño</a>
                <?php endif ?>
            </div>
            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 40%;">Nombre de Tipo Diseño</th>
                            <th class="text-center" style="width: 20%;">Estatus</th>
                            <?php if ($otro == 1 || $nivel == 15 ) : ?>
                                <th class="text-center" style="width: 15%;">Acciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_disenios as $a_disenios) : ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td><?php echo remove_junk(ucwords($a_disenios['descripcion'])) ?></td>
                                <td class="text-center">
                                    <?php if ($a_disenios['estatus'] === '1') : ?>
                                        <span class="label label-success"><?php echo "Activa"; ?></span>
                                    <?php else : ?>
                                        <span class="label label-danger"><?php echo "Inactiva"; ?></span>
                                    <?php endif; ?>
                                </td>
                                <?php if ($nivel_user <= 2 || $nivel == 15 ) : ?>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <?php if ($nivel_user <= 2 || $nivel == 15 ) : ?>
                                                <a href="edit_tipo_disenios.php?id=<?php echo (int)$a_disenios['id_cat_tipo_disenios']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                    <i class="glyphicon glyphicon-pencil"></i>
                                                </a>
                                            <?php endif ?>
                                            <?php if (($nivel_user <= 2 || $nivel == 15 ) && ($a_disenios['id_cat_tipo_disenios'] != 1)) : ?>

                                                <?php if ($a_disenios['estatus'] == 0) : ?>
                                                    <a href="activate_tipo_disenios.php?id=<?php echo (int)$a_disenios['id_cat_tipo_disenios']; ?>&a=0" class="btn btn-success btn-md" title="Activar" data-toggle="tooltip">
                                                        <span class="glyphicon glyphicon-ok"></span>
                                                    </a>
                                                <?php else : ?>
                                                    <a href="activate_tipo_disenios.php?id=<?php echo (int)$a_disenios['id_cat_tipo_disenios']; ?>&a=1" class="btn btn-md btn-danger" data-toggle="tooltip" title="Inactivar">
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