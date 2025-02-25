<?php
$page_title = 'Recomendaciones Generales';
require_once('includes/load.php');
?>
<?php

//$all_detalles = find_all_detalles_busqueda($_POST['consulta']);
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : '2023';
$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level(7);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}
if ($nivel_user == 52) {
    page_require_level_exacto(52);
}
if ($nivel_user == 53) {
    page_require_level_exacto(53);
}

if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 50) :
    redirect('home.php');
endif;
if ($nivel_user > 50 && $nivel_user < 52) :
    redirect('home.php');
endif;

if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la '.$page_title, 5);   
}
$all_recomendaciones = find_all('recomendaciones_generales');

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT numero_recomendacion,fecha_recomendacion, autoridad_responsable,		
			REPLACE(observaciones, CHAR(13, 10), ' ') as observaciones, 
			REPLACE(hecho_completo, CHAR(13, 10), ' ') as hecho_completo 
			FROM recomendaciones_generales";
$resultado = mysqli_query($conexion, $sql) or die;
$resoluciones = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $recomendaciones[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($recomendaciones)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=recomendaciones_generales.xls");
        $filename = "recomendaciones_generales.xls";
        $mostrar_columnas = false;

        foreach ($recomendaciones as $resolucion) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($resolucion)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($resolucion)) . "\n");
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

<a href="solicitudes_quejas.php" class="btn btn-success">Regresar</a><br><br>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Recomendaciones Generales</span>
                </strong>
				<?php if (($nivel_user <= 2) || ($nivel_user == 50)) : ?>
                <a href="add_recomendacion_general.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Recomendación</a>
				<?php endif; ?>
						<form action=" <?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
								<button style="float: right; margin-top: -22px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
							</form>
               
            </div>
        </div>

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr style="height: 10px;">
                        <th style="width: 10%;">Num. Recomendación</th>
                        <th style="width: 5%;">Fecha de Recomendación</th>
                        <th style="width: 7%;">Autoridad Responsable</th>
                        <th style="width: 2%;">Observaciones</th>
                        <th style="width: 5%;">Recomendación</th>
                        <th style="width: 5%;">Recomendación Pública</th>
                        <!-- <?php //if (($nivel <= 2)): ?> -->
                            <?php if (($nivel_user <= 2) || ($nivel_user == 50)) : ?>
                            <th style="width: 3%;" class="text-center">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_recomendaciones as $a_recomendacion) : ?>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_recomendacion['numero_recomendacion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_recomendacion['fecha_recomendacion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_recomendacion['autoridad_responsable'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_recomendacion['observaciones'])) ?></td>
                            <?php
                            $folio_editar = $a_recomendacion['numero_recomendacion'];
                            $resultado = str_replace("/", "-", $folio_editar);
                            ?>
                            <td class="text-center"><a target="_blank" style="color: #3D94FF;" href="uploads/recomendacionesGenerales/<?php echo $resultado . '/' . $a_recomendacion['recomendacion_adjunto']; ?>">
								 <span class="material-symbols-outlined" style="color: #0094FF;">
                                        file_save
                                    </span></a></td>
                            <td class="text-center"><a target="_blank" style="color: #3D94FF;" href="uploads/recomendacionesGenerales/<?php echo $resultado . '/' . $a_recomendacion['recomendacion_adjunto_publico']; ?>">
							 <span class="material-symbols-outlined" style="color: #0094FF;">
                                        file_save
                                    </span></a></td>

                            <?php if (($nivel_user <= 2) || ($nivel_user == 50)) : ?>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="edit_recomendacion_general.php?id=<?php echo (int)$a_recomendacion['id_recom_general']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>&nbsp;
									 <a href="add_acuerdo_rec_gral.php?id=<?php echo (int)$a_recomendacion['id_recom_general']; ?>" class="btn btn-warning btn-md" title="Acuerdo" data-toggle="tooltip" style="background: #5B54DB;">
                                            <span class="material-symbols-outlined" style="font-size: 21px; color:white;">
                                                feed
                                            </span>
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
</div>

<?php include_once('layouts/footer.php'); ?>