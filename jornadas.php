<?php
$page_title = ' Lista de Jornadas';
require_once('includes/load.php');
?>
<?php
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$user = current_user();
$nivel_user = $user['user_level'];

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

$all_fichas = find_all_jornadas($ejercicio);
$conexion = mysqli_connect ("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion,"utf8");
mysqli_select_db ($conexion, "suigcedh");
$sql = "SELECT * FROM jornadas WHERE YEAR(fecha_actividad)=".$ejercicio;
$resultado = mysqli_query ($conexion, $sql) or die;
$jornadas = array();
while( $rows = mysqli_fetch_assoc($resultado) ) {
    $jornadas[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($jornadas)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel;charset=UTF-8');
        header("Content-Disposition: attachment; filename=jornadas.xls");        
        $filename = "jornadas.xls";
        $mostrar_columnas = false;

        foreach ($jornadas as $resolucion) {
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
	 window.open("jornadas.php?anio="+anio,"_self");	 
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
								<span>Jornadas de <?php echo $ejercicio ?></span>
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
                    <a href="add_jornada.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar jornada</a>
                <?php endif; ?>
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
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 2%;">Folio</th>
                            <th class="text-center" style="width: 10%;">Nombre Actividad</th>
                            <th class="text-center" style="width: 15%;">Objetivo de Actividad</th>
                            <th class="text-center" style="width: 1%;">Total atendidos</th>
                            <th class="text-center" style="width: 1%;">Fecha de actividad</th>
                                <th style="width: 3%;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_fichas as $a_ficha) : ?>
                            <tr>
                                <td><?php echo remove_junk(ucwords($a_ficha['folio'])) ?></td>
                                <td><?php echo remove_junk(ucwords($a_ficha['nombre_actividad'])) ?></td>
                                <td><?php echo remove_junk(ucwords($a_ficha['objetivo_actividad'])) ?></td>
                                <td class="text-center"><?php echo remove_junk($a_ficha['mujeres'] + $a_ficha['hombres'] + $a_ficha['lgbtiq']) ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['fecha_actividad'])) ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="ver_info_jornada.php?id=<?php echo (int)$a_ficha['id_jornada']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                                <i class="glyphicon glyphicon-eye-open"></i>
                                            </a>
                                <?php if (($nivel_user <= 2) || ($nivel_user == 4) || ($nivel_user == 9) || ($nivel_user == 22)) : ?>
                                            <a href="ver_imagenes.php?carpeta=<?php echo $a_ficha['carpeta']; ?>" class="btn btn-md btn-secondary" data-toggle="tooltip" title="Ver evidencia fotográfica">
                                                <i class="glyphicon glyphicon-picture"></i>
                                            </a>
                                            <a href="edit_jornada.php?id=<?php echo (int)$a_ficha['id_jornada']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
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