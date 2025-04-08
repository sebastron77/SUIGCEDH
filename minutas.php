<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Minutas';
require_once('includes/load.php');
?>
<?php
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : DATE('Y');
$all_minutas = find_all_minutas($ejercicio);
$user = current_user();
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
if ($nivel_user > 36 && $nivel_user < 53) :
    redirect('home.php');
endif;

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT * FROM minutas";
$resultado = mysqli_query($conexion, $sql) or die;
$minutas = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $minutas[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($minutas)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel;charset=UTF-8');
        header("Content-Disposition: attachment; filename=minutas.xls");
        $filename = "minutas.xls";
        $mostrar_columnas = false;

        foreach ($minutas as $resolucion) {
            if (!$mostrar_columnas) {
                echo implode("\t", array_keys($resolucion)) . "\n";
                $mostrar_columnas = true;
            }
            echo implode("\t", array_values($resolucion)) . "\n";
        }
        if ($nivel_user == 7 || $nivel_user == 53) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" descargó  la lista de Minutas.', 6);
        }
    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}

?>
<script type="text/javascript">
    function changueAnio(anio) {
        //alert(anio);
        window.open("minutas.php?anio=" + anio, "_self");
    }
</script>
<?php include_once('layouts/header.php'); ?>

<a href="solicitudes_agendas.php" class="btn btn-success">Regresar</a><br><br>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <strong>
                            <span class="glyphicon glyphicon-th"></span>
                            <span>Minutas <?php echo $ejercicio ?></span>
                        </strong>
                    </div>
                    <div class="d-flex">
                        <div class="form-group me-3">
                            <select class="form-control" name="ejercicio" onchange="changueAnio(this.value)">
                                <option value="">Selecciona Ejercicio</option>
                                <?php for ($i = 2020; $i <= (int) date("Y"); $i++) {
                                    echo "<option value='" . $i . "'>" . $i . "</option>";
                                } ?>
                            </select>
                        </div>
                        <a href="add_minuta.php" class="btn btn-info me-3" style="height: 31px;">Agregar Minuta</a>
                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>?anio=<?php echo $ejercicio ?>" method="post">
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
                        <th class="text-center" style="width: 1%;">#</th>
                        <th class="text-center" style="width: 3%;">Folio</th>
                        <th class="text-center" style="width: 1%;">No. Sesión</th>
                        <th class="text-center" style="width: 5%;">Tipo Sesión</th>
                        <th class="text-center" style="width: 3%;">Fecha Sesión</th>
                        <th class="text-center" style="width: 1%;">Hora</th>
                        <th class="text-center" style="width: 15%;">Lugar</th>
                        <th class="text-center" style="width: 1%;">No. Asistentes</th>
                        <th class="text-center" style="width: 3%;">Archivo Minuta</th>
                        <?php if (($nivel_user <= 17) || ($nivel_user == 36)) : ?>
                            <th style="width: 1%;" class="text-center">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_minutas as $a_minutas) : ?>
                        <?php
                        $folio_editar = $a_minutas['folio'];
                        $resultado = str_replace("/", "-", $folio_editar);
                        ?>
                        <tr>
                            <td class="text-center"><?php echo count_id() ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_minutas['folio'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_minutas['num_sesion'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_minutas['tipo_sesion'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($a_minutas['fecha_sesion']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($a_minutas['hora']))) ?></td>
                            <td><?php echo remove_junk(ucwords(($a_minutas['lugar']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($a_minutas['num_asistentes']))) ?></td>
                            <td style="text-align: center;">
                                <a target="_blank" style="color: #0094FF;" href="uploads/minutas/<?php echo $resultado . '/' . $a_minutas['archivo_minuta']; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                        <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z" />
                                    </svg>
                                </a>
                            </td>
                            <?php if (($nivel_user <= 17) || ($nivel_user == 36)) : ?>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="ver_info_minutas.php?id=<?php echo (int) $a_minutas['id_minutas']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
                                        <a href="edit_minuta.php?id=<?php echo (int)$a_minutas['id_minutas']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>