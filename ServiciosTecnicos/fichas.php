<?php
$page_title = 'Fichas Técnicas - Área Médica';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : '2023';
$anio = $ejercicio == 2023 ? 2022 : 2023;

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 4) {
    page_require_level_exacto(4);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 9) {
    page_require_level_exacto(9);
}
if ($nivel_user == 22) {
    page_require_level_exacto(22);
}

if ($nivel_user > 2 && $nivel_user < 4) :
    redirect('home.php');
endif;
if ($nivel_user > 4 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 22) :
    redirect('home.php');
endif;
if ($nivel_user > 22) :
    redirect('home.php');
endif;

if (($nivel_user == 1) || ($nivel_user == 2) || ($nivel_user == 22) || ($nivel_user == 7)) {
    $all_fichas = find_all_fichas($ejercicio);
} else {
    $all_fichas = find_all_fichasUser($ejercicio, $id_user);
}

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT f.id_ficha, f.folio, fun.descripcion as funcion, f.num_queja, f.ficha_adjunto, 
		a.nombre_area as visitaduria, a2.nombre_area as area_solicitante,cd.descripcion as hechos,protocolo_estambul,fecha_intervencion,resultado,documento_emitido,nombre_especialista,clave_documento
        FROM fichas f
        LEFT JOIN cat_funcion fun ON f.id_cat_funcion = fun.id_cat_funcion
        LEFT JOIN area a ON a.id_area = f.id_visitaduria
		LEFT JOIN area a2 ON a2.id_area = f.id_area_solicitante 
		LEFT JOIN cat_der_vuln cd USING(id_cat_der_vuln) WHERE tipo_ficha=1";
$resultado = mysqli_query($conexion, $sql) or die;
$fichas = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $fichas[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($fichas)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel;charset=UTF-8');
        header("Content-Disposition: attachment; filename=fichas.xls");
        $filename = "fichas.xls";
        $mostrar_columnas = false;

        foreach ($fichas as $resolucion) {
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

<a href="solicitudes_servicios_tecnicos.php" class="btn btn-success">Regresar</a><br><br>

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
                    <span>Fichas técnicas - Área Médica</span>
                </strong>
                <a href="fichas.php?anio=<?php echo $anio; ?>" style="margin-left: 10px" class="btn btn-info pull-right">Ver <?php echo $anio ?></a>
                <?php if (($nivel_user <= 2) || ($nivel_user == 4) || ($nivel_user == 9) || ($nivel_user == 22)) : ?>
                    <a href="add_ficha.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar ficha</a>
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
                        <th style="width: 3%;">Folio</th>
                        <th style="width: 5%;">Función</th>
                        <th style="width: 1%;">No. Queja</th>
                        <th style="width: 3%;">Visitaduría</th>
                        <th style="width: 3%;">Área Solicitante</th>
                        <th class="text-center" style="width: 1%;">Adjunto</th>
                        <?php if (($nivel_user <= 2) || ($nivel_user == 4) || ($nivel_user == 7) || ($nivel_user == 9) || ($nivel_user == 22)) : ?>
                            <th style="width: 1%;" class="text-center">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_fichas as $a_ficha) : ?>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_ficha['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_ficha['funcion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_ficha['num_queja'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_ficha['visitaduria'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_ficha['area_solicitante'])) ?></td>
                            <?php
                            $folio_editar = $a_ficha['folio'];
                            $resultado = str_replace("/", "-", $folio_editar);
                            ?>
                            <td class="text-center">
                                <?php
                                if ($a_ficha['ficha_adjunto'] != '') {
                                ?>
                                    <a target="_blank" style="color: #0094FF;" href="uploads/fichastecnicas/medica/<?php echo $resultado . '/' . $a_ficha['ficha_adjunto']; ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                                            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                            <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z" />
                                        </svg>
                                    </a>

                                <?php } ?>
                            </td>
                            <?php if (($nivel_user <= 2) || ($nivel_user == 4) || ($nivel_user == 7) || ($nivel_user == 9) || ($nivel_user == 22)) : ?>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="ver_info_ficha.php?id=<?php echo (int)$a_ficha['id_ficha']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
                                        <?php if (($nivel_user <= 2) || ($nivel_user == 4) || ($nivel_user == 9) || ($nivel_user == 22)) : ?>
                                            <a href="edit_ficha.php?id=<?php echo (int)$a_ficha['id_ficha']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                                <span class="glyphicon glyphicon-edit"></span>
                                            </a>
                                        <?php endif; ?>
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
</div>

<?php include_once('layouts/footer.php'); ?>