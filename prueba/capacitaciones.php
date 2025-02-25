<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Capacitaciones';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id'];
$nivel_user = $user['user_level'];
$area_user = area_usuario2($id_user);
$area = $area_user['nombre_area'];

if (($nivel_user <= 2) || ($nivel_user == 7)) {
    $all_capacitaciones = find_all_capacitaciones();
} else {
    $all_capacitaciones = find_all_capacitaciones_area($area);
}

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT * FROM capacitaciones";
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
                    <span>Lista de Capacitaciones</span>
                </strong>
                <?php //if (($nivel <= 2) || ($nivel == 4) || ($nivel == 6) || ($nivel == 7)) : 
                ?>
                <a href="add_capacitacion.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar capacitación</a>
                <?php //endif; 
                ?>
                <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <button style="float: right; margin-top: -20px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>
            </div>
        </div>

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr style="height: 10px;">
                        <th style="width: 11%;">Folio</th>
                        <th style="width: 15%;">Capacitación</th>
                        <th style="width: 7%;">Fecha</th>
                        <th style="width: 3%;">Hora</th>
                        <th style="width: 5%;">Lugar</th>
                        <th style="width: 1%;">Asistentes</th>
                        <th style="width: 1%;">Modalidad</th>
                        <th style="width: 5%;">Capacitador</th>
                        <th style="width: 1%;">Curriculum</th>
                        <!-- <th style="width: 3%;">Constancia</th> -->

                        <th style="width: 1%;" class="text-center">Acciones</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_capacitaciones as $a_capacitacion) : ?>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['nombre_capacitacion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['fecha'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['hora'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['lugar'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_capacitacion['no_asistentes'])) ?></td>
                            <td><?php echo remove_junk((ucwords($a_capacitacion['modalidad']))) ?></td>
                            <td><?php echo remove_junk((ucwords($a_capacitacion['capacitador']))) ?></td>
                            <?php
                            $folio_editar = $a_capacitacion['folio'];
                            $resultado = str_replace("/", "-", $folio_editar);
                            ?>
                            <td style="text-align: center;">
                                <a target="_blank" style="color: #0094FF;" href="uploads/capacitaciones/curriculums/<?php echo $resultado . '/' . $a_capacitacion['curriculum']; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                        <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z" />
                                    </svg>
                                </a>

                            </td>

                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="ver_info_capacitacion.php?id=<?php echo (int)$a_capacitacion['id_capacitacion']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                        <i class="glyphicon glyphicon-eye-open"></i>
                                    </a>
                                    <a href="edit_capacitacion.php?id=<?php echo (int)$a_capacitacion['id_capacitacion']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-edit"></span>
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