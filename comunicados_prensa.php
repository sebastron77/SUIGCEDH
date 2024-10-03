<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Listado de Cominicados de Prensa';
require_once('includes/load.php');
?>
<?php

$user = current_user();
$nivel_user = $user['user_level'];
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$all_comunicados = find_all_year('comunicados', 'fecha_publicacion',$ejercicio);

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title.' del Ejercicio '.$ejercicio, 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 15) {
    page_require_level_exacto(15);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title.' del Ejercicio '.$ejercicio, 5);
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
$sql = "SELECT folio, fecha_publicacion, tipo_nota, nombre_nota,url_acceso  
FROM `comunicados` a 
WHERE  YEAR (fecha_publicacion) = '{$ejercicio}'
ORDER BY fecha_publicacion DESC";
$resultado = mysqli_query($conexion, $sql) or die;
$atuaciones = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $atuaciones[] = $rows;
}

mysqli_close($conexion);
if (isset($_POST["export_data"])) {
    if (!empty($atuaciones)) {
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=comunicados.xls");
        $filename = "comunicados.xls";
        $mostrar_columnas = false;

        foreach ($atuaciones as $datos) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($datos)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($datos)) . "\n");
        }
		if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" descargó '.$page_title.' del Ejercicio '.$ejercicio, 6);    
		}

    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}


?>

<script type="text/javascript">	
 function changueAnio(anio){
	 window.open("comunicados_prensa.php?anio="+anio,"_self");
	 
 }
</script>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<a href="solicitudes_comunicacion_social.php" class="btn btn-success">Regresar</a><br><br>
<div class="row">

<div class="row">
	    <div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<div class="col-md-8">
							<strong>
								<span class="glyphicon glyphicon-th"></span>
								<span>Comunicados de Prensa de <?php echo $ejercicio ?> </span>
							</strong>
					</div>
					<div class="col-md-1" style="margin: 20px 40px 10px 0px;">
						 <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>?anio=<?php echo $ejercicio?>" method="post">
                    <button style="float: right; margin-top: -20px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>
					</div>
					<div class="col-md-1">
						<?php if (($nivel_user <=2) || ($nivel_user == 15)) : ?>
                    <a href="add_comunicado.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Cominicado</a>
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
					<th width="1%" >#</th>
                        <th width="10%">Folio</th>
                        <th width="10%">Fecha Cominicado</th>
                        <th width="10%">Medio presentación</th>
                        <th width="10%">Tema</th>
                        <th width="10%">Link Acceso</th>
                        <th width="10%">Aciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_comunicados as $datos) : ?>
                        <tr>
						<td class="text-center"><?php echo count_id(); ?></td>
                               <td><?php echo remove_junk(ucwords($datos['folio'])) ?></td> 
                               <td><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($datos['fecha_publicacion'])))) ?></td> 
                               <td><?php echo remove_junk(ucwords($datos['tipo_nota'])) ?></td> 
                               <td><?php echo remove_junk(ucwords($datos['nombre_nota'])) ?></td> 
                               <td>
									<a href="<?php echo $datos['url_acceso']; ?>" data-toggle="tooltip" title="Ir a URL" target="_blank">
									<?php echo remove_junk(($datos['url_acceso'])) ?>
									</a>
								</td> 
                               <td class="text-center">
								<a href="ver_info_comunicado.php?id=<?php echo (int)$datos['id_comunicados']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información completa">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
									<?php if (($nivel_user <=2) || ($nivel_user == 15)) : ?>	
									<a href="edit_comunicado.php?id=<?php echo (int)$datos['id_comunicados']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
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