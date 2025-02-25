<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Supervisión de Buzones';
require_once('includes/load.php');
?>
<?php
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$user = current_user();
$nivel = $user['user_level'];
$nivel_user = $user['user_level'];
$id_u = $user['id_user'];
if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 6) {
    page_require_level_exacto(6);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la '.$page_title.' del Ejercicio '.$ejercicio, 5); 
    page_require_level_exacto(7);
}

if ($nivel_user == 24) {
    page_require_level_exacto(24);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la '.$page_title.' del Ejercicio '.$ejercicio, 5); 
    page_require_level_exacto(53);
}

if ($nivel_user > 2 && $nivel_user < 6) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 24) :
    redirect('home.php');
endif;
if ($nivel_user > 24 && $nivel_user < 53) :
    redirect('home.php');
endif;

$supervision = find_all_order('supervision_buzones', 'fecha_supervision');

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");

$sql = "SELECT folio,fecha_supervision,lugar_supervision,numero_quejas,quien_atendio,REPLACE(observaciones,  CHAR(13, 10), ' ') as observaciones 
        FROM supervision_buzones s ";
		 $sql .= " WHERE YEAR(s.fecha_supervision)= ".$ejercicio;

$resultado = mysqli_query($conexion, $sql) or die;
$supervisiones = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $supervisiones[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($supervisiones)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel;charset=UTF-8');
        header("Content-Disposition: attachment; filename=supervision_buzones.xls");
        $filename = "supervision_buzones.xls";
        $mostrar_columnas = false;

        foreach ($supervisiones as $resolucion) {
            if (!$mostrar_columnas) {
                echo implode("\t", array_keys($resolucion)) . "\n";
                $mostrar_columnas = true;
            }
            echo implode("\t", array_values($resolucion)) . "\n";
        }
		if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" descargó  la lista de '.$page_title. ' del Ejercicio '.$ejercicio, 6);    
		}
    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}

?>
<script type="text/javascript">	
 function changueAnio(anio){
	 window.open("supervision_buzones.php?anio="+anio,"_self");	 
 }
</script>
<?php include_once('layouts/header.php'); ?>

<a href="solicitudes_grupo.php" class="btn btn-success">Regresar</a><br><br>
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
								<span>Lista de Supervisión de Buzones de <?php echo $ejercicio ?></span>
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
					<?php if (($nivel_user <= 2) || ($nivel_user == 6) || ($nivel_user == 24) ) : ?>
                <a href="add_supervision_buzones.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Supervisión</a>
				<?php endif;?>						
                <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>?anio=<?php echo $ejercicio?>" method="post">
                    <button style="float: right; margin-top: 0px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>				
					
				</div>
			</div>
		</div>
	</div>


    <div class="col-md-12">
       

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr>
                        <th width="5%">Folio</th>
                        <th width="5%">Fecha de Supervisión</th>
                        <th width="5%">Lugar de Supervisión</th>
                        <th width="5%">No. Quejas Captadas</th>
                        <th width="5%">¿Quién Atendió? </th>
                        <th width="5%;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($supervision as $datos) : ?>
                        <tr>
                            <td>
                                <?php echo remove_junk(ucwords($datos['folio'])) ?>
                            </td>
                           
                            <td>
                                <?php echo date_format(date_create(remove_junk(ucwords($datos['fecha_supervision']))), "d-m-Y"); ?>
                            </td>                           
                            <td>
                                <?php echo remove_junk(ucwords($datos['lugar_supervision'])); ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($datos['numero_quejas'])); ?>
                            </td><td class="text-center">
                                <?php echo remove_junk(ucwords($datos['quien_atendio'])); ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="ver_info_supervision_buzones.php?id=<?php echo (int) $datos['id_supervision_buzones']; ?>" title="Ver información">
                                        <!-- <i class="glyphicon glyphicon-eye-open" style="color: #1f4c88; font-size: 25px;"></i> -->
                                        <img src="medios/ver_info.png" style="width: 31px; height: 30.5px; border-radius: 15%; margin-right: -2px;">
                                    </a>&nbsp;
									<?php if (($nivel_user <= 2) || ($nivel_user == 6) || ($nivel_user == 24) ) : ?>
                                    <a href="edit_supervision_buzones.php?id=<?php echo (int) $datos['id_supervision_buzones']; ?>" title="Editar">
                                        <!-- <span class="glyphicon glyphicon-edit" style="color: black; font-size: 25px;"></span> -->
                                        <img src="medios/editar2.png" style="width: 31px; height: 30.5px; border-radius: 15%; margin-right: -2px;">
                                    </a>&nbsp;
				<?php endif;?>						
                                </div>
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