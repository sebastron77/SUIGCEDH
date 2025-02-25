<?php
$page_title = 'Recomendaciones antes de 2023';
require_once('includes/load.php');
?>
<?php
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : '2023';
$all_recomendaciones = find_all_recomendaciones($ejercicio);
$year_recomendaciones = find_all_year_recos();
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 5) {
    page_require_level_exacto(5);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}

if ($nivel_user > 2 && $nivel_user < 5) :
    redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 7) :
    redirect('home.php');
endif;


$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT numero_recomendacion,folio_queja,fecha_recomendacion, servidor_publico,		
			REPLACE(observaciones, CHAR(13, 10), ' ') as observaciones, 
			REPLACE(hecho_completo, CHAR(13, 10), ' ') as hecho_completo 
			FROM recomendaciones WHERE numero_recomendacion LIKE '%/{$ejercicio}'";
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
        header("Content-Disposition: attachment; filename=recomendaciones.xls");
        $filename = "recomendaciones.xls";
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
<script type="text/javascript">	
 function changueAnio(anio){
	 //alert(anio);
	 window.open("recomendaciones_antes.php?anio="+anio,"_self");
	 
 }
</script>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<a href="solicitudes_quejas.php" class="btn btn-success">Regresar</a><br><br>
<div class="row">

	<div class="row">
	    <div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<div class="col-md-8">
							<strong>
								<span class="glyphicon glyphicon-th"></span>
								<span>Recomendaciones <?php echo $ejercicio ?></span>
							</strong>
					</div>
					<div class="col-md-1" style="margin: 20px 40px 10px 0px;">
						<form action=" <?php echo $_SERVER["PHP_SELF"]; ?>?anio=<?php echo $ejercicio ?>" method="post">
								<button style="float: right; margin-top: -22px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
							</form>
					</div>
					<div class="col-md-1">
						<?php if (($nivel_user == 1) || ($nivel_user == 50)) : ?>
                        <a href="add_recomendacion.php" class="btn btn-info pull-right">Agregar Recomendación</a>
                    <?php endif; ?>
					</div>										
					<div class="col-md-1">
						<div class="form-group" >
							<select class="form-control" name="ejercicio" onchange="changueAnio(this.value)">
								<option value="">Selecciona Ejercicio</option>									
									<?php foreach ($year_recomendaciones as $datos) : ?>
										<option value="<?php echo $datos['years']; ?>"><?php echo $datos['years']; ?></option>
									<?php endforeach; ?>								
							</select>
						</div>	
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
                        <th style="width: 5%;">Folio Rec.</th>
                        <th style="width: 5%;">Folio Queja</th>
                        <th style="width: 5%;">Servidor Público</th>
                        <th style="width: 3%;">Fecha Rec.</th>
                        <th style="width: 5%;">Observaciones</th>
                        <th style="width: 1%;">Recomendación</th>
                        <th style="width: 1%;">Recomendación Pública</th>
                        <?php if (($nivel_user == 1) || ($nivel_user == 5)  || ($nivel_user == 50)) : ?>
                            <th style="width: 1%;" class="text-center">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_recomendaciones as $a_recomendacion) : ?>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_recomendacion['numero_recomendacion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_recomendacion['folio_queja'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_recomendacion['servidor_publico'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_recomendacion['fecha_recomendacion'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_recomendacion['observaciones'])) ?></td>
                            <?php
                            $folio_editar = $a_recomendacion['numero_recomendacion'];
                            $resultado = str_replace("/", "-", $folio_editar);
                            ?>

                            <?php $verifica = substr($a_recomendacion['numero_recomendacion'], 0, 4); ?>
                            <td style="text-align: center;">
                                <a href="uploads/recomendaciones/<?php echo $resultado . '/' . $a_recomendacion['recomendacion_adjunto']; ?>" target="_blank">
                                    <span class="material-symbols-outlined" style="color: #0094FF;">
                                        file_save
                                    </span>

                                </a>
                            </td>
                            <td style="text-align: center;">
                                <a href="uploads/recomendaciones/<?php echo $resultado . '/' . $a_recomendacion['recomendacion_adjunto_publico']; ?>" target="_blank">
                                    <span class="material-symbols-outlined" style="color: #0094FF;">
                                        file_save
                                    </span>
                                </a>
                            </td>

                            <?php if (($nivel_user == 1) || ($nivel_user == 5)  || ($nivel_user == 50)) : ?>
                                <td class="text-center">
                                    <div class="btn-group" style="height: 30px">
                                        <a href="edit_recomendacion.php?id=<?php echo (int)$a_recomendacion['id_recomendacion']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                            <span class="glyphicon glyphicon-edit" style="margin-top: 1px"></span>
                                        </a>
                                        <a href="add_acuerdo_rec.php?id=<?php echo (int)$a_recomendacion['id_recomendacion']; ?>" class="btn btn-warning btn-md" title="Acuerdo" data-toggle="tooltip" style="background: #5B54DB;">
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