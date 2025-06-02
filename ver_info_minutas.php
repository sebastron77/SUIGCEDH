<?php
$page_title = 'Información de minuta';
require_once('includes/load.php');
?>
<?php
header('Content-Type: text/html; charset=UTF-8');

//$all_detalles = find_all_trabajadores();
$minuta = find_by_id('minutas', (int) $_GET['id'], 'id_minutas');
$user = current_user();
$nivel_user = $user['user_level'];
$year = date("Y");
$folio_editar = $minuta['folio'];
$resultado = str_replace("/", "-", $folio_editar);
if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo ' . $page_title, 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 17) {
    page_require_level_exacto(17);
}
if ($nivel_user == 36) {
    page_require_level_exacto(36);
}
if ($nivel_user == 53) {
    insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo ' . $page_title, 5);
    page_require_level_exacto(53);
}
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7  && $nivel_user < 17) :
    redirect('home.php');
endif;
if ($nivel_user > 17 && $nivel_user < 36) :
    redirect('home.php');
endif;
if ($nivel_user > 36 && $nivel_user < 53) :
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
                    <span>Información de la minuta:
                        <?php echo remove_junk(ucwords($minuta['num_sesion']) . ' del ' . $year) ?>
                    </span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 1%;">Folio</th>
                            <th class="text-center" style="width: 1%;">Núm. de Sesión</th>
                            <th class="text-center" style="width: 1%;">Tipo de Sesión</th>
                            <th class="text-center" style="width: 1%;">Fecha de Sesión</th>
                            <th class="text-center" style="width: 1%;">Hora</th>
                            <th class="text-center" style="width: 5%;">Lugar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- <td class="text-center"><?php echo count_id(); ?></td> -->
                            <td class="text-center">
                                <?php echo remove_junk($minuta['folio']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($minuta['num_sesion']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($minuta['tipo_sesion']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($minuta['fecha_sesion']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($minuta['hora']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($minuta['lugar']) ?>
                            </td>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 3%;">Núm de Asistentes</th>
                            <th class="text-center" style="width: 8%;">Avance de Acuerdos</th>
                            <th class="text-center" style="width: 7%;">Archivo de Minuta</th>
                            <th class="text-center" style="width: 10%;">Lista de Asistencia</th>
                            <th class="text-center" style="width: 10%;">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">
                                <?php echo remove_junk($minuta['num_asistentes']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($minuta['avance_acuerdos']) ?>
                            </td>
                            <td class="text-center">
                                <a href="uploads/minutas/<?php echo $resultado ?>/<?php echo $minuta['archivo_minuta'] ?>"><?php echo $minuta['archivo_minuta'] ?></a>
                            </td>
                            <td class="text-center">
                                <a href="uploads/minutas/<?php echo $resultado ?>/<?php echo $minuta['lista_asistencia'] ?>"><?php echo $minuta['lista_asistencia'] ?></a>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($minuta['observaciones']) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <iframe src="uploads/minutas/<?php echo $resultado ?>/<?php echo $minuta['archivo_minuta'] ?>#zoom=100" width="650px;" height="850px;"></iframe>
                <iframe src="uploads/minutas/<?php echo $resultado ?>/<?php echo $minuta['lista_asistencia'] ?>#zoom=100" width="650px;" height="850px;"></iframe>
                <div class="row">
                    <div class="col-md-9">
                        <a href="minutas.php?anio=<?php echo $year; ?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>