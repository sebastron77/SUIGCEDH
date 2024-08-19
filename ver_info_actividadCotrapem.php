<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once('includes/load.php');
$page_title = 'Información Actividad sobre Trata de Personas';
?>
<?php
$a_ficha = find_by_id_actividadCotrapem((int)$_GET['id']);
$user = current_user();
$nivel = $user['user_level'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    page_require_level(3);
}
if ($nivel == 4) {
    redirect('home.php');
}
if ($nivel == 5) {
    redirect('home.php');
}
if ($nivel == 6) {
    redirect('home.php');
}
if ($nivel == 7) {
    redirect('home.php');
}

if ($nivel == 7 || $nivel == 53) {
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
                    <span>Información Actividad sobre Trata de Personas</span>
                </strong>
                <!-- <a href="add_ficha.php" class="btn btn-info pull-right">Agregar ficha</a> -->
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 1%;">Folio</th>
                            <th class="text-center" style="width: 1%;">Tipo de Actividad</th>
                            <th class="text-center" style="width: 1%;">Modalidad</th>
                            <th class="text-center" style="width: 1%;">Fecha</th>
                            <th class="text-center" style="width: 3%;">Municipio</th>
                            <th class="text-center" style="width: 5%;">Lugar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_ficha['tipo_actividad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_ficha['modalidad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_ficha['fecha'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['nombre_municipio'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['lugar'])) ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 15%;">Instituciones Participantes</th>
                            <th class="text-center" style="width: 5%;">Público al que se dirige</th>
                            <th class="text-center" style="width: 1%;">Hombres</th>
                            <th class="text-center" style="width: 1%;">Mujeres</th>
                            <th class="text-center" style="width: 1%;">No Binarios</th>
                            <th class="text-center" style="width: 1%;">Total</th>
                            <th class="text-center" style="width: 5%;">Área Responsable</th>
                            <th class="text-center" style="width: 5%;">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['instituciones_participantes'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($a_ficha['publico_dirige']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($a_ficha['hombres']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($a_ficha['mujeres']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['no_binarios'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['total'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['nombre_area'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['observaciones'])) ?></td>
                        </tr>
                    </tbody>
                </table>

                <a href="actividades_cotrapem.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>