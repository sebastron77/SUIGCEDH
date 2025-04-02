<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Supervisión Mecanismos';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

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
if ($nivel_user >36 && $nivel_user < 53) :
    redirect('home.php');
endif;

$all_supervisiones = find_all('supervision_mecanismos');

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT fecha_visita, nombre_actividad, institucion_visitada, quien_atendio FROM supervision_mecanismos";
$resultado = mysqli_query($conexion, $sql) or die;
$consejo = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $consejo[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($consejo)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=supervision_mecanismos.xls");
        $filename = "supervision_mecanismos.xls";
        $mostrar_columnas = false;

        foreach ($consejo as $resolucion) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($resolucion)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($resolucion)) . "\n");
        }
        if ($nivel_user == 7 || $nivel_user == 53) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" descargó ' . $page_title, 6);
        }
    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}

?>
<?php include_once('layouts/header.php'); ?>

<a href="solicitudes_agendas.php" class="btn btn-success">Regresar</a><br><br>

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
                    <span>Supervisión Mecanismos</span>
                </strong>
                <?php if (($nivel_user <= 2) || ($nivel_user == 17)) : ?>
                    <a href="add_supervision.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Supervisión</a>
                <?php endif; ?>
                <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <button style="float: right; margin-top: -20px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>
            </div>
        </div>

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr style="height: 10px;">
                        <th class="text-center" style="width: 5%;">Folio</th>
                        <th class="text-center" style="width: 3%;">Fecha Visita</th>
                        <th class="text-center" style="width: 10%;">Nombre Actividad</th>
                        <th class="text-center" style="width: 10%;">Institución Visitada</th>
                        <th class="text-center" style="width: 10%;">Atendió</th>
                        <th class="text-center" style="width: 10%;">Observaciones</th>
                        <th class="text-center" style="width: 1%;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_supervisiones as $datos) : ?>
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords($datos['folio'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($datos['fecha_visita'])) ?></td>
                            <td><?php echo remove_junk(ucwords($datos['nombre_actividad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($datos['institucion_visitada'])) ?></td>
                            <td><?php echo remove_junk(ucwords(($datos['quien_atendio']))) ?></td>
                            <td><?php echo remove_junk(ucwords(($datos['observaciones']))) ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <?php if (($nivel_user <= 2) || ($nivel_user == 17)) : ?>
                                        <a href="edit_supervision_mecanismos.php?id=<?php echo (int)$datos['id_supervision_mecanismos']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>
                                    <?php endif ?>
                                </div>
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