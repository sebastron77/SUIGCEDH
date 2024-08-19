<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once('includes/load.php');
$page_title = 'Información de Jornada';
?>
<?php
$a_ficha = find_by_id('jornadas', (int)$_GET['id'], 'id_jornada');
// $tipo_ficha = find_tipo_ficha((int)$_GET['id']);
$user = current_user();
$nivel = $user['user_level'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    redirect('home.php');
}
if ($nivel == 4) {
    page_require_level(4);
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
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.'. Folio:'.$a_ficha['folio'], 5);   
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
                    <span>Información de Jornada</span>
                </strong>
                <!-- <a href="add_ficha.php" class="btn btn-info pull-right">Agregar ficha</a> -->
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 10%;">Folio</th>
                            <th class="text-center" style="width: 15%;">Nombre de Actividad</th>
                            <th class="text-center" style="width: 15%;">Objetivo Actividad</th>
                            <th class="text-center" style="width: 15%;">Área Responsable</th>
                            <th class="text-center" style="width: 5%;">Mujeres</th>
                            <th class="text-center" style="width: 5%;">Hombres</th>
                            <th class="text-center" style="width: 3%;">LGBTIQ+</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_ficha['nombre_actividad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_ficha['objetivo_actividad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_ficha['area_responsable'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['mujeres'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['hombres'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($a_ficha['lgbtiq']))) ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 20%;">Inicio de Actividad</th>
                            <th class="text-center" style="width: 20%;">Fin de Actividad</th>
                            <th class="text-center" style="width: 40%;">Alcance</th>
                            <th class="text-center" style="width: 40%;">Colaboración Institucional</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords(($a_ficha['fecha_actividad']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($a_ficha['fin_actividad']))) ?></td>
                            <td><?php echo remove_junk(ucwords(($a_ficha['alcance']))) ?></td>
                            <td><?php echo remove_junk(ucwords(($a_ficha['colaboracion_institucional']))) ?></td>
                        </tr>
                    </tbody>
                </table>
                
                    <a href="jornadas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>