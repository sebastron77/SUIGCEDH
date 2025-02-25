<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Inventario de Mediación/Conciliación';
require_once('includes/load.php');
?>
<?php

$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$all_inventario = find_data_year('inventario_macs','fecha_informe',$ejercicio);

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la lista de '.$page_title.' del Ejercicio '.$ejercicio, 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 19) {
    page_require_level_exacto(19);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la lista de '.$page_title.' del Ejercicio '.$ejercicio, 5);
    page_require_level_exacto(53);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 19) :
    redirect('home.php');
endif;

if ($nivel_user > 19 && $nivel_user < 50) :
    redirect('home.php');
endif;
if ($nivel_user > 50 && $nivel_user < 53) :
    redirect('home.php');
endif;

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT 
folio,
  YEAR(fecha_informe) as ejercicio ,
  IF(MONTH(fecha_informe)=1,'Enero',IF(MONTH(fecha_informe)=2,'Febrero',IF(MONTH(fecha_informe)=3,'Marzo',IF(MONTH(fecha_informe)=4,'Abril',
  	IF(MONTH(fecha_informe)=5,'Mayo',IF(MONTH(fecha_informe)=6,'Junio',IF(MONTH(fecha_informe)=7,'Julio',IF(MONTH(fecha_informe)=8,'Agosto',
    IF(MONTH(fecha_informe)=9,'Septiembre',IF(MONTH(fecha_informe)=10,'Octubre',IF(MONTH(fecha_informe)=11,'Noviembre',IF(MONTH(fecha_informe)=12,'Diciembre','S/D')))))))))))) as mes ,
  num_quejas_recibidas ,
  num_sesiones_programadas ,
  num_sesiones_desahogadas ,
  num_conciliaciones ,
  num_convenios ,
  num_actas_llamadas ,
  num_actas_comparecencia ,
  num_actas_circunstanciadas ,
  num_quejas_enviadas ,
  num_quejas_visitadurias ,
  num_quejas_tramite ,
  num_quejas_concluidas ,
  observaciones 
  from inventario_macs;";
$resultado = mysqli_query($conexion, $sql) or die;
$consejo = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $consejo[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($consejo)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=mediacion_atencion.xls");
        $filename = "mediacion_atencion.xls";
        $mostrar_columnas = false;

        foreach ($consejo as $resolucion) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($resolucion)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($resolucion)) . "\n");
        }
		if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" descargó  la lista de '.$page_title.' del Ejercicio '.$ejercicio, 6);
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
	 window.open("mediacion_atencion.php?anio="+anio,"_self");	 
 }
</script>
<?php include_once('layouts/header.php'); ?>

<a href="solicitudes_quejas.php" class="btn btn-success">Regresar</a><br><br>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">

	<div class="row">
	    <div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<div class="col-md-10">
							<strong>
								<span class="glyphicon glyphicon-th"></span>
								<span>Lista de Inventario de Mediación/Conciliación <?php echo $ejercicio ?></span>
							</strong>
					</div>
					<div class="col-md-2">
						<div class="form-group" >
							<select class="form-control" name="ejercicio" onchange="changueAnio(this.value)">
								<option value="">Selecciona Ejercicio</option>									
									<?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
								echo "<option value='".$i."'>".$i."</option>";
								}?>										
							</select>
						</div>	
					</div>					
					
						<form action=" <?php echo $_SERVER["PHP_SELF"]; ?>?anio=<?php echo $ejercicio ?>" method="post">
								<button style="float: right; margin-top: 0px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
							</form>
					
					
						 <?php if (($nivel <=2) || ($nivel == 19) ) : ?>
							<a href="add_mediacion_atencion.php" style="margin-left: 10px;margin-right: 10px" class="btn btn-info pull-right">Agregar Registro</a>
						<?php endif; ?>
														
					
				</div>
			</div>
		</div>
	</div>


    <div class="col-md-12">

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr style="height: 10px;">
                        <th class="text-center" >Folio</th>
                        <th class="text-center" >Ejercicio</th>
                        <th class="text-center" >Mes</th>
                        <th class="text-center" >No. Quejas canalizadas al área</th>
                        <th class="text-center" >No. Quejas concluidas para mediación y/o conciliación</th>
                        <th class="text-center" >No. Quejas en trámite pendientes de celebración de sesión y/o mediación</th>						 
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
			
                   <?php foreach ($all_inventario as $inventario_archivos) : 
				   $fecha_informe = $inventario_archivos['fecha_informe'];
					$ejercicio = date("Y", strtotime($fecha_informe));
					$mes = date("m", strtotime($fecha_informe));
				?>                     
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords($inventario_archivos['folio'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($ejercicio)) ?></td>
                             <?php if($mes == 1):?><td class="text-center">Enero</td><?php endif;?>
                                <?php if($mes == 2):?><td class="text-center">Febrero</td><?php endif;?>
                                <?php if($mes == 3):?><td class="text-center">Marzo</td><?php endif;?>
                                <?php if($mes == 4):?><td class="text-center">Abril</td><?php endif;?>
                                <?php if($mes == 5):?><td class="text-center">Mayo</td><?php endif;?>
                                <?php if($mes == 6):?><td class="text-center">Junio</td><?php endif;?>
                                <?php if($mes == 7):?><td class="text-center">Julio</td><?php endif;?>
                                <?php if($mes == 8):?><td class="text-center">Agosto</td><?php endif;?>
                                <?php if($mes == 9):?><td class="text-center">Septiembre</td><?php endif;?>
                                <?php if($mes == 10):?><td class="text-center">Octubre</td><?php endif;?>
                                <?php if($mes == 11):?><td class="text-center">Noviembre</td><?php endif;?>
                                <?php if($mes == 12):?><td class="text-center">Diciembre</td><?php endif;?>
                            <td class="text-center"><?php echo remove_junk(ucwords($inventario_archivos['num_quejas_recibidas'])) ?></td>                                               
                            <td class="text-center"><?php echo remove_junk(ucwords($inventario_archivos['num_quejas_concluidas'])) ?></td>                                               
                            <td class="text-center"><?php echo remove_junk(ucwords($inventario_archivos['num_quejas_tramite'])) ?></td>                                               
                            <td class="text-center">
                                <div class="btn-group">
									<a href="ver_info_mediacion_atencion.php?id=<?php echo (int) $inventario_archivos['id_inventario_macs']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                        <img src="medios/ver_info.png" style="width: 16px; border-radius: 15%; margin-right: -2px;">
                                    </a>&nbsp;
									<?php if (($nivel <= 2) || ($nivel == 19) ) : ?>
                                    <a href="edit_mediacion_atencion.php?id=<?php echo (int)$inventario_archivos['id_inventario_macs']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>
									<?php endif ?>
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