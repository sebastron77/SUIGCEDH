<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'LISTA DE SISTEMAS Y HERRAMIENTAS INFORMÁTICAS';
require_once('includes/load.php');

$all_acciones = find_all_order('herramientas_sistemas', 'id_herramientas_sistemas');
$user = current_user();
$nivel = $user['user_level'];

$id_usuario = $user['id_user'];
$id_user = $user['id_user'];
$busca_area = area_usuario($id_usuario);
$otro = $busca_area['nivel_grupo'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);
    page_require_level(7);
}
if ($nivel_user == 13) {
    page_require_level_exacto(13);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);
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


$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");

$sql = "SELECT folio, nombre_aplicativo, fecha_inicio_operacion, REPLACE(descripcion_aplicativo,'\r\n',' ') as descripcion_aplicativo, status
          FROM herramientas_sistemas a";

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
        header("Content-Disposition: attachment; filename=herramientas_sistemas.xls");
        $filename = "herramientas_sistemas.xls";
        $mostrar_columnas = false;

        foreach ($sesiones as $resolucion) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($resolucion))) . "\n";
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($resolucion))) . "\n";
        }
		
		
if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" descargó  la '.$page_title, 6);    
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
<a href="solicitudes_sistemas.php" class="btn btn-success">Regresar</a><br><br>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>SISTEMAS Y HERRAMIENTAS INFORMÁTICOS</span>
                </strong>
                <?php if ($nivel_user <= 2 ||  $nivel_user == 13) : ?>
                    <a href="add_herramientas_sistemas.php" class="btn btn-info pull-right btn-md">Agregar Aplicativo</a>
                <?php endif ?>
				<form action=" <?php echo $_SERVER["PHP_SELF"]; ?>?anio=<?php echo $ejercicio?>" method="post">
                    <button style="float: right; margin-top: -22px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>
            </div>
            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead>
                        <tr class="thead-purple">
                            <th width="1%" >#</th>
                            <th class="text-center">Folio</th>
                            <th class="text-center">Nombre Sistema</th>
                            <th class="text-center">Fecha de Inicio de Operaciones</th>
                            <th class="text-center">Concepto y Utilidad</th>
                            <th class="text-center">Estado que se encuentra</th>
                            <?php if ($nivel_user <= 2 ||  $nivel_user == 13) : ?>
                                <th class="text-center">Acciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_acciones as $datos) : ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                
                                <td class="text-center"><?php echo remove_junk(ucwords($datos['folio'])) ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords($datos['nombre_aplicativo'])) ?></td>
                                <td class="text-center"><?php echo date_format(date_create(remove_junk(ucwords($datos['fecha_inicio_operacion']))), "d-m-Y");  ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords($datos['descripcion_aplicativo'])) ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords($datos['status'])) ?></td>
                                <?php if ($nivel_user <= 2 ||  $nivel_user == 13) : ?>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="edit_herramientas_sistemas.php?id=<?php echo (int)$datos['id_herramientas_sistemas']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
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