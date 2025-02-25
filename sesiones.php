<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de sesions';
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
if ($nivel_user == 4) {
    page_require_level_exacto(4);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la '.$page_title.' del Ejercicio '.$ejercicio, 5); 
    page_require_level_exacto(7);
}
if ($nivel_user == 9) {
    page_require_level_exacto(9);
}
if ($nivel_user == 22) {
    page_require_level_exacto(22);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la '.$page_title.' del Ejercicio '.$ejercicio, 5); 
    page_require_level_exacto(53);
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
if ($nivel_user > 22 && $nivel_user < 53) :
    redirect('home.php');
endif;

$sesiones1 = find_all_sesiones($ejercicio);

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");

$sql = "SELECT s.folio, s.fecha_atencion, s.estatus, s.nota_sesion, s.atendio, s.fecha_creacion, s.no_sesion
        FROM sesiones s
        LEFT JOIN paciente p ON p.id_paciente = s.id_paciente";
		 $sql .= " WHERE YEAR(s.fecha_atencion)= ".$ejercicio;

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
        header("Content-Disposition: attachment; filename=sesiones.xls");
        $filename = "sesiones.xls";
        $mostrar_columnas = false;

        foreach ($sesiones as $resolucion) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($resolucion)) . "\n");
                $mostrar_columnas = true;
            }
			echo utf8_decode(implode("\t", array_values($resolucion)) . "\n");
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
	 window.open("sesiones.php?anio="+anio,"_self");	 
 }
</script>
<?php include_once('layouts/header.php'); ?>

<a href="solicitudes_servicios_tecnicos.php" class="btn btn-success">Regresar</a><br><br>
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
								<span>Lista de Sesiones de <?php echo $ejercicio ?></span>
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
					<?php if (($nivel_user <= 2) || ($nivel_user == 4) || ($nivel_user == 9) || ($nivel_user == 22)) : ?>
                <a href="add_sesion.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Sesión</a>
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
                        <th width="3%">Folio</th>
                        <th width="4%">Fecha de atención</th>
                        <th width="12%">Paciente</th>
                        <th width="10%">Estatus</th>
                        <th width="2%">No. Sesión</th>
                        <th width="1%;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sesiones1 as $sesion) : ?>
                        <tr>
                            <td>
                                <?php echo remove_junk(ucwords($sesion['folio'])) ?>
                            </td>
                           
                            <td>
                                <?php echo date_format(date_create(remove_junk(ucwords($sesion['fecha_atencion']))), "d-m-Y"); ?>
                            </td>
                            <td>
                                <?php echo $sesion['nombre'] . " " . $sesion['paterno'] . " " . $sesion['materno']; ?>
                            </td>
                            <td>
                                <?php echo remove_junk(ucwords($sesion['estatus'])); ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($sesion['no_sesion'])); ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="ver_info_sesion.php?id=<?php echo (int) $sesion['id_sesion']; ?>" title="Ver información">
                                        <!-- <i class="glyphicon glyphicon-eye-open" style="color: #1f4c88; font-size: 25px;"></i> -->
                                        <img src="medios/ver_info.png" style="width: 31px; height: 30.5px; border-radius: 15%; margin-right: -2px;">
                                    </a>&nbsp;
									<?php if (($nivel_user <= 2) || ($nivel_user == 4) || ($nivel_user == 9) || ($nivel_user == 22)) : ?>
                                    <a href="edit_sesion.php?id=<?php echo (int) $sesion['id_sesion']; ?>" title="Editar">
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