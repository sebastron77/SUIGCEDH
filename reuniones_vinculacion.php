<?php
$page_title = 'Reuniones de Vinculación';
require_once('includes/load.php');
?>
<?php
$ejercicio = isset($_GET['a']) ? $_GET['a'] : date("Y");
$user = current_user();
$nivel_user = $user['user_level'];
$id_u = $user['id_user'];

// Identificamos a que área pertenece el usuario logueado
$date_user = area_usuario2($id_u);

// Identificamos a que área pertenece 
$area = isset($_GET['a']) ? $_GET['a'] : '0';
$tipo = isset($_GET['t']) ? $_GET['t'] : 'x';

if ($tipo != '') {
    $all_reuniones_vinculacion = find_all_reuniones_area($tipo, $ejercicio);
} else {
    $all_reuniones_vinculacion = find_all_reuniones($ejercicio);
}

$solicitud = find_by_solicitud($area);

if ($nivel_user == 7 || $nivel_user == 53) {
    insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la lista de  ' . $page_title . ' del Área ' . $solicitud['nombre_area'] . ' del Ejercicio ' . $ejercicio, 5);
}
if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 6) {
    page_require_level_exacto(6);
}
if ($nivel_user == 7) {
    insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la ' . $page_title . ' del Ejercicio ' . $ejercicio, 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 17) {
    page_require_level_exacto(17);
}
if ($nivel_user == 24) {
    page_require_level_exacto(24);
}
if ($nivel_user == 36) {
    page_require_level_exacto(36);
}
if ($nivel_user == 53) {
    insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la ' . $page_title . ' del Ejercicio ' . $ejercicio, 5);
    page_require_level_exacto(53);
}
if ($nivel_user > 2 && $nivel_user < 6) :
    redirect('home.php');
endif;
if ($nivel_user > 6 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 17) :
    redirect('home.php');
endif;
if ($nivel_user > 17 && $nivel_user < 24) :
    redirect('home.php');
endif;
if ($nivel_user > 24 && $nivel_user < 36) :
    redirect('home.php');
endif;
if ($nivel_user > 36 && $nivel_user < 53) :
    redirect('home.php');
endif;

// $all_reuniones_vinculacion = find_all_order('reuniones_vinculacion', 'fecha_reunion');

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT * FROM poa";
$resultado = mysqli_query($conexion, $sql) or die;
$poa = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $poa[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($poa)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel;charset=UTF-8');
        header("Content-Disposition: attachment; filename=poa.xls");
        $filename = "poa.xls";
        $mostrar_columnas = false;

        foreach ($poa as $resolucion) {
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
<script type="text/javascript">
    function changueAnio(anio, tipo) {

        window.open("reuniones_vinculacion.php?a=" + anio + "&t=" + tipo, "_self");

    }
</script>
<?php include_once('layouts/header.php'); ?>
<?php if ($tipo == 'c'): ?>
    <a href="solicitudes_grupo.php" class="btn btn-success">Regresar</a><br><br>
<?php endif; ?>
<?php if ($tipo == 'm'): ?>
    <a href="solicitudes_agendas.php" class="btn btn-success">Regresar</a><br><br>
<?php endif; ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center">
                        <strong>
                            <span class="glyphicon glyphicon-th"></span>
                            <span>Reuniones de Vinculación de <?php echo $ejercicio ?></span>
                        </strong>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="form-group mb-0">
                            <select class="form-control" name="ejercicio" onchange="changueAnio(this.value,'<?php echo $tipo ?>')">
                                <option value="">Selecciona Ejercicio</option>
                                <?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
                                    echo "<option value='" . $i . "'>" . $i . "</option>";
                                } ?>
                            </select>
                        </div>
                        <?php if (($nivel_user <= 2) || ($nivel_user == 6) || ($nivel_user == 24)) : ?>
                            <a href="add_reuniones_vinculacion.php?t=<?php echo $tipo ?>" class="btn btn-info" style="height: 31px;">Agregar Reunión</a>
                        <?php endif; ?>
                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>?t=<?php echo $area2 ?>&anio=<?php echo $ejercicio ?>" method="post" class="mb-0">
                            <button type="submit" id="export_data" name="export_data" value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr style="height: 10px;">
                        <th style="width: 8%;">Folio</th>
                        <th style="width: 10%;">Nombre Reunión</th>
                        <th style="width: 8%;">Fecha</th>
                        <th style="width: 8%;">Lugar</th>
                        <th style="width: 8%;">Modalidad</th>
                        <th style="width: 8%;">No.Asistentes</th>
                        <th style="width: 1%;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_reuniones_vinculacion as $a_reuniones_vinculacion) : ?>
                        <?php
                        $folio_editar = $a_reuniones_vinculacion['folio'];
                        $resultado = str_replace("/", "-", $folio_editar);

                        ?>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_reuniones_vinculacion['folio'])) ?></td>
                            <td style="text-align: center;"><?php echo remove_junk(ucwords($a_reuniones_vinculacion['nombre_reunion'])) ?></td>
                            <td style="text-align: center;"><?php echo date_format(date_create($a_reuniones_vinculacion['fecha_reunion']), 'd/m/Y') ?></td>
                            <td style="text-align: center;"><?php echo remove_junk(ucwords($a_reuniones_vinculacion['lugar_reunion'])) ?></td>
                            <td style="text-align: center;"><?php echo remove_junk(ucwords($a_reuniones_vinculacion['modalidad'])) ?></td>
                            <td style="text-align: center;"><?php echo remove_junk(ucwords($a_reuniones_vinculacion['numero_asistentes'])) ?></td>

                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="ver_info_reuniones_vinculacion.php?id=<?php echo (int)$a_reuniones_vinculacion['id_reuniones_vinculacion']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información completa">
                                        <i class="glyphicon glyphicon-eye-open"></i>
                                    </a>
                                    <?php if (($nivel_user <= 2) || ($nivel_user == 6) || ($nivel_user == 24)) : ?>
                                        <a href="edit_reuniones_vinculacion.php?id=<?php echo (int)$a_reuniones_vinculacion['id_reuniones_vinculacion']; ?>&t=<?php echo $tipo; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>
                                    <?php endif; ?>
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