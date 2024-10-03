<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Agenda';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$area_user = area_usuario2($id_user);
$area = $area_user['nombre_area'];

if (($nivel_user <= 2) || ($nivel_user == 7)) {
    $all_agendas = find_all_agendas();
} else {
    $all_agendas = find_all_agendas_area($area);
}

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT * FROM agendas";
$resultado = mysqli_query($conexion, $sql) or die;
$capacitaciones = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $capacitaciones[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($capacitaciones)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel;charset=UTF-8');
        header("Content-Disposition: attachment; filename=capacitaciones.xls");
        $filename = "capacitaciones.xls";
        $mostrar_columnas = false;

        foreach ($capacitaciones as $resolucion) {
            if (!$mostrar_columnas) {
                echo implode("\t", array_keys($resolucion)) . "\n";
                $mostrar_columnas = true;
            }
            echo implode("\t", array_values($resolucion)) . "\n";
        }
    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}

?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<a href="solicitudes.php" class="btn btn-success">Regresar</a><br><br>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Lista de Proyectos</span>
                </strong>
                <a href="add_agenda.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar actividad</a>
                <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <button style="float: right; margin-top: -20px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>
            </div>
        </div>

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr style="height: 10px;">
                        <th style="width: 4%;">Fecha</th>
                        <th style="width: 3%;">% Avance</th>
                        <th style="width: 5%;">Actividad (Descripción)</th>
                        <th style="width: 5%;">Responsable</th>
                        <th style="width: 2%;">Supervisor</th>
                        <th style="width: 5%;">Fecha Límite</th>
                        <th style="width: 5%;">Fecha Entrega</th>
                        <th style="width: 5%;">Entregables</th>
                        <th style="width: 3%;">Observaciones</th>
                        <th style="width: 1%;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_agendas as $a_agenda) : ?>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_agenda['fecha'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_agenda['porcentaje_avance'])) ?>%</td>
                            <td><?php echo remove_junk(ucwords($a_agenda['actividad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_agenda['nombre_responsable']) . " " . ucwords($a_agenda['apellido_responsable'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_agenda['nombre_supervisor']) . " " . ucwords($a_agenda['apellido_responsable'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_agenda['fecha_limite'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_agenda['fecha_entrega'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_agenda['entregables'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_agenda['observaciones'])) ?></td>

                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit_agenda.php?id=<?php echo (int)$a_agenda['id_agenda']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a href="avance_proyecto.php?id=<?php echo (int)$a_agenda['id_agenda']; ?>" class="btn btn-secondary btn-md" title="Progreso" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-dashboard"></span>
                                    </a>
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