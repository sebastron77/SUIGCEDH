<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once('includes/load.php');
$page_title = 'Información Acción de Vinculación';
?>
<?php
$a_ficha = find_by_accionV((int)$_GET['id']);
// $tipo_ficha = find_tipo_ficha((int)$_GET['id']);
$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 3) {
    page_require_level_exacto(3);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 53) {
    page_require_level_exacto(53);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 53) :
    redirect('home.php');
endif;

if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$a_ficha['folio'], 5);   
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
                    <span>Información de Acción de Vinculación</span>
                </strong>
                <!-- <a href="add_ficha.php" class="btn btn-info pull-right">Agregar ficha</a> -->
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 3%;">Folio</th>
                            <th class="text-center" style="width: 3%;">Fecha de Actividad</th>
                            <th class="text-center" style="width: 15%;">Lugar de Actividad</th>
                            <th class="text-center" style="width: 15%;">Nombre de Actividad</th>
                            <th class="text-center" style="width: 15%;">Descripción</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_ficha['fecha'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_ficha['lugar'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_ficha['nombre_actividad'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['descripcion'])) ?></td>


                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 15%;">Participantes</th>
                            <th class="text-center" style="width: 15%;">Institución de Procedencia</th>
                            <th class="text-center" style="width: 5%;">Modalidad</th>
                            <th class="text-center" style="width: 20%;">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['participantes'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($a_ficha['nombre_autoridad']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($a_ficha['modalidad']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($a_ficha['observaciones']))) ?></td>
                        </tr>
                    </tbody>
                </table>

                <a href="acciones_vinculacion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>