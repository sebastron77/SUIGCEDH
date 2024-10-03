<?php
$page_title = 'Actividades sobre Trata de Personas';
require_once('includes/load.php');
?>
<?php

$all_fichas = find_all_actividadesCotrapem();
$user = current_user();
$nivel_user = $user['user_level'];


if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 3) {
    page_require_level_exacto(3);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo las '.$page_title, 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo las '.$page_title, 5);
    page_require_level_exacto(53);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 53) :
    redirect('home.php');
endif;

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT * FROM actividades_cotrapem";
$resultado = mysqli_query($conexion, $sql) or die;
$jornadas = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $jornadas[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($jornadas)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=actividades_cotrapem.xls");
        $filename = "actividades_cotrapem.xls";
        $mostrar_columnas = false;

        foreach ($jornadas as $resolucion) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($resolucion)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($resolucion)) . "\n");
        }
		if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" descargó  la lista de '.$page_title.' ', 6);    
		}
    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}

?>
<?php include_once('layouts/header.php'); ?>

<a href="solicitudes_ejecutiva.php" class="btn btn-success">Regresar</a><br><br>

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
                    <span>Actividades sobre Trata de Personas</span>
                </strong>
                <?php if (($nivel_user <= 2) || ($nivel_user == 3)) : ?>
                    <a href="add_actividadCotrapem.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Actividad</a>
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
                        <th class="text-center" style="width: 2.5%;">Folio</th>
                        <th class="text-center" style="width: 1.8%;">Fecha Actividad</th>
                        <th class="text-center" style="width: 15%;">Lugar de Sesión</th>
                        <th class="text-center" style="width: 2%;">Tipo Actividad</th>
                        <th class="text-center" style="width: 7%;">Área Responsable</th>
                            <th style="width: 0.2%;" class="text-center">Acciones</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_fichas as $a_ficha) : ?>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_ficha['folio'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['fecha'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_ficha['lugar'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['tipo_actividad'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['nombre_area'])) ?></td>
                            
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="ver_info_actividadCotrapem.php?id=<?php echo (int)$a_ficha['id_actividadCotrapem']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
										<?php if (($nivel_user <= 3)  ) : ?>
                                        <a href="ver_imagenes_actCotrapem.php?carpeta=<?php echo $a_ficha['carpeta']; ?>" class="btn btn-md btn-secondary" data-toggle="tooltip" title="Ver evidencia fotográfica">
                                            <i class="glyphicon glyphicon-picture"></i>
                                        </a>
                                        <a href="edit_actividadCotrapem.php?id=<?php echo (int)$a_ficha['id_actividadCotrapem']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
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