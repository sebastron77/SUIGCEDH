<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Difusión';
require_once('includes/load.php');
?>
<?php

$user = current_user();
$nivel = $user['user_level'];
$nivel_user = $user['user_level'];
$all_difusion = find_all_difusion();

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

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT dif.id_difusion, dif.folio, dif.fecha, dif.tipo_difusion, td.descripcion as tipo_dif, dif.tema, dif.link, dif.entrevistado, dif.medio
        FROM difusion dif
        LEFT JOIN cat_tipo_difusion td ON td.id_cat_tipo_dif = dif.tipo_difusion";
$resultado = mysqli_query($conexion, $sql) or die;
$difusiones = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $difusiones[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($difusiones)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=difusion.xls");
        $filename = "difusion.xls";
        $mostrar_columnas = false;

        foreach ($difusiones as $difusion) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($difusion)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($difusion)) . "\n");
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
                    <span>Difusión</span>
                </strong>
                <?php if (($nivel <=2) || ($nivel == 15)) : ?>
                    <a href="add_difusion.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Difusión</a>
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
                        <th width="2%">Folio</th>
                        <th width="2%">Fecha</th>
                        <th width="2%">Tipo Difusión</th>
                        <th width="10%">Tema</th>
                        <th width="10%">Entrevistado</th>
                        <th width="1%">Aciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_difusion as $datos) :
                        
                    ?>
                        <tr>
                            <td><?php echo remove_junk(ucwords($datos['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($datos['fecha'])) ?></td>
                            <td><?php echo remove_junk(ucwords($datos['tipd'])) ?></td>
                            <td><?php echo remove_junk(ucwords($datos['tema'])) ?></td>
                            <td><?php echo remove_junk(ucwords($datos['entrevistado'])) ?></td>

                            <td class="text-center">
                                <a href="ver_info_difusion.php?id=<?php echo (int)$datos['id_difusion']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información completa">
                                    <i class="glyphicon glyphicon-eye-open"></i>
                                </a>
<?php if (($nivel <=2) || ($nivel == 15)) : ?>
                                <a href="edit_difusion.php?id=<?php echo (int)$datos['id_difusion']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
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

<?php include_once('layouts/footer.php'); ?>