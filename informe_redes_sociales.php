<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Informe de Estadísticas de Redes Sociales';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel = $user['user_level'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 15) {
    page_require_level_exacto(15);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);
    page_require_level_exacto(53);
}
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user >7  && $nivel_user < 15) :
    redirect('home.php');
endif;
if ($nivel_user > 15 && $nivel_user < 53) :
    redirect('home.php');
endif;
$sesiones = find_all('estadisticas_redes');

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT * FROM estadisticas_redes";
$resultado = mysqli_query($conexion, $sql) or die;
$sintesis = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $sintesis[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($sintesis)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=sintesis.xls");
        $filename = "sintesis.xls";
        $mostrar_columnas = false;

        foreach ($sintesis as $sint) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($sint)) . "\n");
                $mostrar_columnas = true;
            }
                echo utf8_decode(implode("\t", array_values($sint)) . "\n");
        }
		if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" descargó '.$page_title, 6);    
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
<a href="solicitudes_comunicacion_social.php" class="btn btn-success">Regresar</a><br><br>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Estadísticas de Redes Sociales</span>
                </strong>
                <?php if (($nivel <=2) || ($nivel == 15)) : ?>
                    <a href="add_redes_sociales.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Estadística</a>
                <?php endif; ?>
                <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <button style="float: right; margin-top: -20px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>
            </div>
        </div>

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr>
                        <th class="text-center" width="2%">Folio</th>
                        <th class="text-center" width="2%">Ejercicio</th>
                        <th class="text-center" width="2%">Mes</th>
                        <th class="text-center" width="2%">Alcance Facebook</th>
                        <th class="text-center" width="2%">Alcance Instagram</th>
                        <th class="text-center" width="2%">Impresiones X</th>
                        <th class="text-center" width="2%">Tiktok Visualizaciones</th>						
                        <th class="text-center" width="2%">Aciones</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($sesiones as $datos) : ?>
                    <tr>
                        <td class="text-center"><?php echo remove_junk(ucwords($datos['folio'])) ?></td>
                        <td class="text-center"><?php echo remove_junk(ucwords($datos['ejercicio'])) ?></td>
						<?php if($datos['mes'] == 1):?><td class="text-center">Enero</td><?php endif;?>
						<?php if($datos['mes'] == 2):?><td class="text-center">Febrero</td><?php endif;?>
						<?php if($datos['mes'] == 3):?><td class="text-center">Marzo</td><?php endif;?>
						<?php if($datos['mes'] == 4):?><td class="text-center">Abril</td><?php endif;?>
						<?php if($datos['mes'] == 5):?><td class="text-center">Mayo</td><?php endif;?>
						<?php if($datos['mes'] == 6):?><td class="text-center">Junio</td><?php endif;?>
						<?php if($datos['mes'] == 7):?><td class="text-center">Julio</td><?php endif;?>
						<?php if($datos['mes'] == 8):?><td class="text-center">Agosto</td><?php endif;?>
						<?php if($datos['mes'] == 9):?><td class="text-center">Septiembre</td><?php endif;?>
						<?php if($datos['mes'] == 10):?><td class="text-center">Octubre</td><?php endif;?>
						<?php if($datos['mes'] == 11):?><td class="text-center">Noviembre</td><?php endif;?>
						<?php if($datos['mes'] == 12):?><td class="text-center">Diciembre</td><?php endif;?>
                        <td class="text-center"><?php echo remove_junk(ucwords($datos['fb_alcance'])) ?></td>
                        <td class="text-center"><?php echo remove_junk(ucwords($datos['ins_alcance'])) ?></td>
                        <td class="text-center"><?php echo remove_junk(ucwords($datos['x_impresiones'])) ?></td>
                        <td class="text-center"><?php echo remove_junk(ucwords($datos['tk_vizualizacion_video'])) ?></td>
                        <td class="text-center">
						 <a href="ver_info_redes_sociales.php?id=<?php echo (int)$datos['id_estadisticas_redes']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                        <i class="glyphicon glyphicon-eye-open"></i>
                                    </a>
<?php if (($nivel <=2) || ($nivel == 15)) : ?>
                            <a href="edit_redes_sociales.php?id=<?php echo (int)$datos['id_estadisticas_redes']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                <i class="glyphicon glyphicon-pencil"></i>
                            </a>
                <?php endif; ?>
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