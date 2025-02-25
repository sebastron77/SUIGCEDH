<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Inventario de Archivos';
require_once('includes/load.php');
?>
<?php

$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$all_inventario_archivos = find_data_year('inventario_archivos','ejercicio',$ejercicio);

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
if ($nivel_user == 11) {
    page_require_level_exacto(11);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la lista de '.$page_title.' del Ejercicio '.$ejercicio, 5);
    page_require_level_exacto(53);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 11) :
    redirect('home.php');
endif;

if ($nivel_user > 11 && $nivel_user < 21) :
    redirect('home.php');
endif;


$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT folio,ejercicio, IF(mes=1,'Enero',IF(mes=1,'Febrero',IF(mes=1,'Marzo',
			IF(mes=1,'Abril',IF(mes=1,'Mayo',IF(mes=1,'Junio',IF(mes=1,'Julio',
			IF(mes=1,'Agosto',IF(mes=1,'Septiembe',IF(mes=1,'Octubre',IF(mes=1,'noviembre',IF(mes=1,'Diciembre','')))))))))))) as mes,total_archivos,observaciones,fecha_creacion FROM inventario_archivos";
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
        header("Content-Disposition: attachment; filename=inventario_archivos.xls");
        $filename = "inventario_archivos.xls";
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
	 window.open("inventario_archivos.php?anio="+anio,"_self");
	 
 }
</script>
<?php include_once('layouts/header.php'); ?>

<a href="solicitudes_archivo.php" class="btn btn-success">Regresar</a><br><br>

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
								<span>Lista de Inventario de Archivos <?php echo $ejercicio ?></span>
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
					
					
						 <?php if (($nivel <=2) || ($nivel == 11) ) : ?>
							<a href="add_inventario_archivos.php" style="margin-left: 10px;margin-right: 10px" class="btn btn-info pull-right">Agregar Registro</a>
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
                        <th class="text-center" >Valor Absoluto</th>
                        <th class="text-center" >Observaciones</th>
						 <?php if (($nivel == 1) || ($nivel == 11) ) : ?>
                        <th class="text-center">Acciones</th>
						<?php endif; ?>
                    </tr>
                </thead>
                <tbody>
			
                   <?php foreach ($all_inventario_archivos as $inventario_archivos) : ?>                     
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords($inventario_archivos['folio'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($inventario_archivos['ejercicio'])) ?></td>
                             <?php if($inventario_archivos['mes'] == 1):?><td class="text-center">Enero</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 2):?><td class="text-center">Febrero</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 3):?><td class="text-center">Marzo</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 4):?><td class="text-center">Abril</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 5):?><td class="text-center">Mayo</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 6):?><td class="text-center">Junio</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 7):?><td class="text-center">Julio</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 8):?><td class="text-center">Agosto</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 9):?><td class="text-center">Septiembre</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 10):?><td class="text-center">Octubre</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 11):?><td class="text-center">Noviembre</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 12):?><td class="text-center">Diciembre</td><?php endif;?>
                            <td class="text-center"><?php echo remove_junk(ucwords($inventario_archivos['total_archivos'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($inventario_archivos['observaciones']))) ?></td>                                                      
										<?php if (($nivel <= 2) || ($nivel == 11) ) : ?>
                            <td class="text-center">
                                <div class="btn-group">
								
                                    <a href="edit_inventario_archivos.php?id=<?php echo (int)$inventario_archivos['id_inventario_archivos']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>
                                </div>
                            </td>
									<?php endif ?>
                        </tr>
                    <?php endforeach; ?>
					
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<?php include_once('layouts/footer.php'); ?>