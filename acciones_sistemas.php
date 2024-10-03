<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Acciones de Sistemas';
require_once('includes/load.php');
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$user = current_user();

$id_usuario = $user['id_user'];
$id_user = $user['id_user'];
$busca_area = area_usuario($id_usuario);
$otro = $busca_area['nivel_grupo'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo ' . $page_title . ' del Ejercicio ' . $ejercicio, 5);
    page_require_level(7);
}
if ($nivel_user == 13) {
    page_require_level_exacto(13);
}
if ($nivel_user == 53) {
    insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo ' . $page_title . ' del Ejercicio ' . $ejercicio, 5);
    page_require_level(53);
}
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 13) :
    redirect('home.php');
endif;
if ($nivel_user > 13 && $nivel_user < 53) :
    redirect('home.php');
endif;

$all_acciones = find_all_accionesCSI($ejercicio);

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");

$sql = "SELECT id_acciones_sistemas, b.descripcion as tipo_accion, nombre_area, fecha_accion,REPLACE(descripcion_accion,'\r\n',' ') as descripcion_accion, quien_atendio,definicion_indicador
          FROM acciones_sistemas a
          LEFT JOIN cat_tipo_accion_sistemas b USING(id_cat_tipo_accion_sistemas) 
          LEFT JOIN area c USING(id_area)  
		  LEFT JOIN indicadores_pat d USING(id_indicadores_pat) 
          WHERE YEAR(fecha_accion)=" . $ejercicio;

$resultado = mysqli_query($conexion, $sql) or die;
$sesiones = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $sesiones[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($sesiones)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=acciones_sistemas.xls");
        $filename = "acciones_sistemas.xls";
        $mostrar_columnas = false;

        foreach ($sesiones as $resolucion) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($resolucion))) . "\n";
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($resolucion))) . "\n";
        }
        if ($nivel_user == 7 || $nivel_user == 53) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" descargó  la ' . $page_title . ' del Ejercicio ' . $ejercicio, 6);
        }
    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}

?>
<script type="text/javascript">
    function changueAnio(anio) {
        window.open("acciones_sistemas.php?anio=" + anio, "_self");
    }
</script>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<a href="solicitudes_sistemas.php" class="btn btn-success">Regresar</a><br><br>
<div class="row">


    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="col-md-10">
                        <strong>
                            <span class="glyphicon glyphicon-th"></span>
                            <span>Lista de Acciones Relevantes del <?php echo $ejercicio ?></span>
                        </strong>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="form-control" name="ejercicio" onchange="changueAnio(this.value)">
                                <option value="">Selecciona Ejercicio</option>
                                <?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
                                    echo "<option value='" . $i . "'>" . $i . "</option>";
                                } ?>
                            </select>
                        </div>
                    </div>


                    <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>?anio=<?php echo $ejercicio ?>" method="post">
                        <button style="float: right; margin-top: 0px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                    </form>

                    <?php if ($nivel_user <= 2 ||  $nivel_user == 13) : ?>
                        <a href="add_acciones_sistemas.php" style="margin-left: 10px;margin-right: 10px" class="btn btn-info pull-right">Agregar Acción</a>
                    <?php endif; ?>


                </div>
            </div>
        </div>
    </div>


    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead>
                        <tr class="thead-purple">
                            <th width="1%">#</th>
                            <th class="text-center">Fecha de Acción</th>
                            <th class="text-center">Tipo Acción</th>
                            <th class="text-center">Área en la que se realizo la Acción</th>
                            <th class="text-center">Breve descipción de la acción o hecho</th>
                            <th class="text-center">¿Quien Atendio?</th>
                            <?php if ($nivel_user <= 2 ||  $nivel_user == 13) : ?>
                                <th class="text-center">Acciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_acciones as $datos) : ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>

                                <td class="text-center"><?php echo date_format(date_create(remove_junk(ucwords($datos['fecha_accion']))), "d-m-Y");  ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords($datos['tipo_accion'])) ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords($datos['nombre_area'])) ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords($datos['descripcion_accion'])) ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords($datos['quien_atendio'])) ?></td>
                                <?php if ($nivel_user <= 2 ||  $nivel_user == 13) : ?>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="edit_acciones_sistemas.php?id=<?php echo (int)$datos['id_acciones_sistemas']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                <i class="glyphicon glyphicon-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                <?php endif ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>