<?php
$page_title = 'Entrega de Insumos';
require_once('includes/load.php');
?>
<?php
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");

$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$fechaActual = date('Y-m-d');
$year =date("y", strtotime($ejercicio.'-01-01')); 

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 4) {
    page_require_level_exacto(4);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 9) {
    page_require_level_exacto(9);
}
if ($nivel_user == 22) {
    page_require_level_exacto(22);
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
if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title.' del Ejercicio '.$ejercicio, 5);
}

$all_obligaciones = find_all_year('insumos','fecha_entrega',$ejercicio);


$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT * FROM insumos ";
$resultado = mysqli_query($conexion, $sql) or die;
$solicitudes_informacion = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $solicitudes_informacion[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($solicitudes_informacion)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=Insumos.xls");
        $filename = "Insumos.xls";
        $mostrar_columnas = false;

        foreach ($solicitudes_informacion as $datos) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($datos)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($datos)) . "\n");
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
	 window.open("insumos.php?anio="+anio,"_self");
	 
 }
 </script>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<a href="solicitudes_servicios_tecnicos.php" class="btn btn-success">Regresar</a><br><br>

<div class="row">


<div class="row">
	    <div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<div class="col-md-8">
							<strong>
								<span class="glyphicon glyphicon-th"></span>
								<span>Entrega de Insumos del <?php echo $ejercicio; ?> </span>
							</strong>
					</div>
					<div class="col-md-1" style="margin: 20px 40px 10px 0px;">
						 <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>?anio=<?php echo $ejercicio?>" method="post">
                    <button style="float: right; margin-top: -20px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>
					</div>
					<div class="col-md-1">
						 <?php if (($nivel_user <= 2) || ($nivel_user == 4) || ($nivel_user == 9) || ($nivel_user == 22)) : ?>
						 <a href="add_insumos.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Entrega</a>
                <?php endif; ?>
					</div>										
					<div class="col-md-1">
						<div class="form-group" >
							<select class="form-control" name="ejercicio" onchange="changueAnio(this.value)">
								<option value="">Selecciona Ejercicio</option>																								
								<?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
								echo "<option value='".$i."'>".$i."</option>";
								}?>																							
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
                    <tr>
                        <th style="width: 1%;">#</th>
                        <th style="width: 1%;">Folio</th>
                        <th style="width: 1%;">Fecha Entrega</th>
                        <th style="width: 1%;">Nombre Actividad</th>
                        <th style="width: 1%;">Total Insumos Entregados</th>
                        
                        <?php if (($nivel_user <= 2) || ($nivel_user == 4) || ($nivel_user == 9) || ($nivel_user == 22)) : ?>
                            <th style="width: 1%;" class="text-center">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                   <?php foreach ($all_obligaciones as $datos) : 
				    $carpeta = str_replace("/", "-", $datos['folio']);
				   ?> 
                        <tr>
							<td class="text-center"><?php echo count_id(); ?></td>                           
                            <td><?php echo remove_junk(ucwords($datos['folio'])) ?></td>
							<td><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($datos['fecha_entrega'])))) ?></td>
                            <td><?php echo remove_junk((ucwords($datos['tema_actividad']))) ?></td>
                            <td><?php echo remove_junk((ucwords($datos['total_insumos_entregado']))) ?></td>
							                            
                            <?php if (($nivel_user <= 2) || ($nivel_user == 4) || ($nivel_user == 9) || ($nivel_user == 22)) : ?>
                                <td class="text-center">
                                    <div class="btn-group">
											<a href="edit_insumos.php?id=<?php echo (int)$datos['id_insumos']; ?>"  title="Editar" data-toggle="tooltip">
												<img src="medios/editar2.png" style="width: 31px; height: 30.5px; border-radius: 15%; margin-right: -2px;">                                            
											</a>&nbsp;
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