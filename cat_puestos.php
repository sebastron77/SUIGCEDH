<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Puestos';
require_once('includes/load.php');

$cat_puesto = find_all('cat_puestos');
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
                    <span>Puestos de la CEDH</span>
                </strong>
                <?php if ( $nivel_user <= 2 || $nivel_user == 14) : ?>
                    <a href="add_puesto.php" class="btn btn-info pull-right btn-md"> Agregar Puesto</a>
                <?php endif ?>
            </div>
            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 20%;">Nombre del Puesto</th>
                            <th class="text-center" style="width: 10%;">Estatus</th>
                            <?php if ( $nivel_user <= 2 || $nivel_user == 14) : ?>
                                <th class="text-center" style="width: 10%;">Acciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cat_puesto as $datos) : ?>
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
                                            <a href="edit_puesto.php?id=<?php echo (int)$datos['id_cat_puestos']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                <i class="glyphicon glyphicon-pencil"></i>
                                            </a>
                                            <?php if (($nivel_user <= 2 || $nivel_user == 14) && ($datos['id_cat_puestos'] != 1)) : ?>
                                                <?php if ($datos['estatus'] == 0) : ?>
                                                    <a href="activate_puesto.php?id=<?php echo (int)$datos['id_cat_puestos']; ?>&a=0" class="btn btn-success btn-md" title="Activar" data-toggle="tooltip">
                                                        <span class="glyphicon glyphicon-ok"></span>
                                                    </a>
                                                <?php else : ?>
                                                    <a href="activate_puesto.php?id=<?php echo (int)$datos['id_cat_puestos']; ?>&a=1" class="btn btn-danger btn-md" title="Inactivar" data-toggle="tooltip">
                                                        <span class="glyphicon glyphicon-ban-circle"></span>
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