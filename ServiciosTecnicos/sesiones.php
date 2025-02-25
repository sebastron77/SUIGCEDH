<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de sesions';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel = $user['user_level'];
$nivel_user = $user['user_level'];
$id_u = $user['id_user'];

$sesiones1 = find_all_sesiones();

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");

$sql = "SELECT s.folio, s.fecha_atencion, s.estatus, s.nota_sesion, s.atendio, s.fecha_creacion, s.no_sesion
        FROM sesiones s
        LEFT JOIN paciente p ON p.id_paciente = s.id_paciente";

$resultado = mysqli_query($conexion, $sql) or die;
$sesiones = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $sesiones[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($sesiones)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel;charset=UTF-8');
        header("Content-Disposition: attachment; filename=sesiones.xls");
        $filename = "sesiones.xls";
        $mostrar_columnas = false;

        foreach ($sesiones as $resolucion) {
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

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Lista de Sesiones</span>
                </strong>

                <a href="add_sesion.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Sesión</a>
                <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <button style="float: right; margin-top: -22px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>
            </div>
        </div>

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr>
                        <th width="3%">Folio</th>
                        <th width="4%">Fecha de atención</th>
                        <th width="12%">Paciente</th>
                        <th width="10%">Estatus</th>
                        <th width="2%">No. Sesión</th>
                        <th width="1%;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sesiones1 as $sesion) : ?>
                        <tr>
                            <td>
                                <?php echo remove_junk(ucwords($sesion['folio'])) ?>
                            </td>
                            <?php
                            $folio_editar = $sesion['folio_sesion'];
                            $resultado = str_replace("/", "-", $folio_editar);
                            ?>
                            <td>
                                <?php echo date_format(date_create(remove_junk(ucwords($sesion['fecha_atencion']))), "d-m-Y"); ?>
                            </td>
                            <td>
                                <?php echo $sesion['nombre'] . " " . $sesion['paterno'] . " " . $sesion['materno']; ?>
                            </td>
                            <td>
                                <?php echo remove_junk(ucwords($sesion['estatus'])); ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($sesion['no_sesion'])); ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="ver_info_sesion.php?id=<?php echo (int) $sesion['id_sesion']; ?>" title="Ver información">
                                        <!-- <i class="glyphicon glyphicon-eye-open" style="color: #1f4c88; font-size: 25px;"></i> -->
                                        <img src="medios/ver_info.png" style="width: 31px; height: 30.5px; border-radius: 15%; margin-right: -2px;">
                                    </a>&nbsp;
                                    <a href="edit_sesion.php?id=<?php echo (int) $sesion['id_sesion']; ?>" title="Editar">
                                        <!-- <span class="glyphicon glyphicon-edit" style="color: black; font-size: 25px;"></span> -->
                                        <img src="medios/editar2.png" style="width: 31px; height: 30.5px; border-radius: 15%; margin-right: -2px;">
                                    </a>&nbsp;
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- </div> -->
<?php include_once('layouts/footer.php'); ?>