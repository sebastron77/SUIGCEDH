<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Inmuebles';
require_once('includes/load.php');
?>
<?php

$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}
if ($nivel_user == 2) {
    page_require_level_exacto(2);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 28) {
    page_require_level_exacto(28);
}
if ($nivel_user == 29) {
    page_require_level_exacto(29);
}
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 28) :
    redirect('home.php');
endif;
if ($nivel_user > 29) {
    redirect('home.php');
}
if (!$nivel_user) {
    redirect('home.php');
}
$all_inmuebles = find_all_inmuebles();
?>

<?php include_once('layouts/header.php'); ?>
<a href="solicitudes_gestion.php" class="btn btn-success">Regresar</a><br><br>
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
                    <span>Lista de Inmuebles</span>
                </strong>
                <?php if (($nivel_user <= 2) || ($nivel_user == 6)) : ?>
                    <a href="add_bien_inmueble.php" class="btn btn-info pull-right">Agregar Inmueble</a>
                <?php endif; ?>
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 1%;">#</th>
                            <th class="text-center" style="width: 7%;">Denominación</th>
                            <th class="text-center" style="width: 7%;">Municipio</th>
                            <th class="text-center" style="width: 7%;">Tipo Inmueble</th>
                            <th class="text-center" style="width: 7%;">Área Responsable</th>
                            <?php if ($nivel_user == 1 || $nivel_user == 14) : ?>
                                <th style="width: 1%;" class="text-center">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_inmuebles as $a_inmueble) : ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_inmueble['denominacion'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_inmueble['municipio'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_inmueble['tipo_inmueble'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_inmueble['area_responsable'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($nivel_user == 1 || $nivel_user == 14) : ?>
                                        <div class="btn-group">
                                            <a href="ver_info_inmueble.php?id=<?php echo (int) $a_inmueble['id_bien_inmueble']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                                <span class="material-symbols-outlined" style="font-size: 22px; color: white; margin-top: 8px;">
                                                    visibility
                                                </span>
                                            </a>
                                            <a href="edit_bien_inmueble.php?id=<?php echo (int) $a_inmueble['id_bien_inmueble']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                <span class="material-symbols-outlined" style="font-size: 22px; color: black; margin-top: 8px;">
                                                    edit
                                                </span>
                                            </a>
                                            <a href="licencias_financiamiento.php?id=<?php echo (int) $a_inmueble['id_bien_inmueble']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Reparaciones" style="background-color: #ff4574; border-color: #ff4574">
                                                <span class="material-symbols-outlined" style="font-size: 22px; color: white; margin-top: 8px;">
                                                    build
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