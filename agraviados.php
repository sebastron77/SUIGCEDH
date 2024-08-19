<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Agraviados Registrados';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$id_usuario = $user['id_user'];
$busca_area = area_usuario($id_usuario);
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 5) {
    page_require_level_exacto(5);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 19) {
    page_require_level_exacto(19);
}
if ($nivel_user == 21) {
    page_require_level_exacto(21);
}
if ($nivel_user == 26) {
    page_require_level_exacto(26);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);
    page_require_level_exacto(53);
}

if ($nivel_user > 2 && $nivel_user < 5) :
    redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 19) :
    redirect('home.php');
endif;
if ($nivel_user > 19 && $nivel_user < 21) :
    redirect('home.php');
endif;
if ($nivel_user > 21 && $nivel_user < 26) :
    redirect('home.php');
endif;
if ($nivel_user > 26 && $nivel_user < 50) :
    redirect('home.php');
endif;
if ($nivel_user > 50 && $nivel_user < 53) :
    redirect('home.php');
endif;
$all_detalles = find_all_agraviados();
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<a href="solicitudes_quejas.php" class="btn btn-success">Regresar</a><br><br>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
        <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Lista de Agraviados </span>
                </strong>
                <?php if ($nivel_user <= 2 || $nivel_user == 5 || $nivel_user == 26 || $nivel_user == 50) : ?>
                    <a href="add_quejoso.php" class="btn btn-info pull-right">Agregar promovente/agraviado</a>
                <?php endif ?>
                <?php if ($nivel_user <= 2 || $nivel_user == 5 || $nivel_user == 26 || $nivel_user == 50) : ?>
                    <a href="add_agraviado_solo.php" class="btn btn-info pull-right" style="margin-right: 5px;">Agregar agraviado</a>
                <?php endif ?>
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 1%;">#</th>
                            <th style="width: 7%;">Nombre(s)</th>
                            <th style="width: 7%;">Apellido Paterno</th>
                            <th style="width: 7%;">Apellido Materno</th>
                            <th style="width: 10%;">Correo</th>
                            <th style="width: 1%;">Teléfono</th>
                            <th style="width: 15%;">Grupo Vuln.</th>
                            <th style="width: 1%;">PPL</th>
                            <th style="width: 1%;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_detalles as $a_detalle) : ?>
                            <tr>
                                <td>
                                    <?php echo remove_junk(ucwords($a_detalle['id_cat_agrav'])) ?>
                                </td>
                                <td>
                                    <?php echo remove_junk(ucwords($a_detalle['nombre'])) ?>
                                </td>
                                <td>
                                    <?php echo remove_junk(ucwords($a_detalle['paterno'])) ?>
                                </td>
                                <td>
                                    <?php echo remove_junk(ucwords($a_detalle['materno'])) ?>
                                </td>
                                <td>
                                    <?php echo remove_junk($a_detalle['email']) ?>
                                </td>
                                <td>
                                    <?php echo remove_junk(ucwords($a_detalle['telefono'])) ?>
                                </td>
                                <td>
                                    <?php echo remove_junk(ucwords($a_detalle['grupo_vuln'])) ?>
                                </td>
                                <td>
                                    <?php
                                    if ($a_detalle['ppl'] == 0) {
                                        echo 'No';
                                    } else {
                                        echo 'Sí';
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <a href="ver_info_agraviado.php?id=<?php echo (int) $a_detalle['id_cat_agrav']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                        <i class="glyphicon glyphicon-eye-open"></i>
                                    </a>
                                    <?php if ($nivel_user <= 2 || $nivel_user == 5 || $nivel_user == 26 || $nivel_user == 50) : ?>
                                        <div class="btn-group">
                                            <a href="edit_agraviado.php?id=<?php echo (int) $a_detalle['id_cat_agrav']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                <i class="glyphicon glyphicon-pencil"></i>
                                            </a>
                                        </div>
                                    <?php endif ?>
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