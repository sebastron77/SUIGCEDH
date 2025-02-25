<?php
$page_title = 'Solicitudes de Información';
require_once('includes/load.php');
?>
<?php
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
//$all_solicitudes = find_all('solicitudes_informacion');

$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$title_status="Sin Dato";
$image_status="duda.png";
$fechaActual = date('Y-m-d');
$year =date("y", strtotime($ejercicio.'-01-01')); 
$all_solicitudes = find_all_sai($year);

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la lista de '.$page_title.' del Ejercicio '.$ejercicio, 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 10) {
    page_require_level_exacto(10);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la lista de '.$page_title.' del Ejercicio '.$ejercicio, 5);
    page_require_level_exacto(53);
}

if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');	
endif;
if ($nivel_user > 7 && $nivel_user < 10) :
    redirect('home.php');
endif;
if ($nivel_user > 10 && $nivel_user < 53) :
    redirect('home.php');
endif;

//echo "SELECT * FROM solicitudes_informacion WHERE folio_solicitud LIKE '1603528{$year}%' ORDER BY fecha_presentacion";

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT 
  CONCAT('\'',folio_solicitud ) as folio_solicitud ,
  fecha_presentacion ,
  nombre_solicitante ,
  b.`descripcion` as genero ,
  c.descripcion as medio_presentacion ,
  REPLACE(informacion_solicitada,  CHAR(13, 10), ' ') as informacion_solicitada ,
  d.descripcion as tipo_solicitud ,
  personalidad_juridica ,
  informacion_clasificada,
  IF(derecho_arco=1,'Si','No') as derecho_arco,
  tipo_derecho_arco,
  fecha_respuesta,
  archivo_respuesta,
  fecha_creacion
FROM solicitudes_informacion a
LEFT JOIN `cat_genero` b USING( id_cat_gen)
LEFT JOIN `cat_medio_pres_ut` c USING(id_cat_med_pres_ut)
LEFT JOIN cat_tipo_solicitud d USING(id_cat_tipo_solicitud) 
WHERE folio_solicitud LIKE '1603528{$year}%' 
ORDER BY fecha_presentacion ";
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
        header("Content-Disposition: attachment; filename=solicitudes_informacion.xls");
        $filename = "solicitudes_informacion.xls";
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
	 window.open("solicitudes_ut.php?anio="+anio,"_self");
	 
 }
 </script>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<a href="solicitudes_transparencia.php" class="btn btn-success">Regresar</a><br><br>

<div class="row">


<div class="row">
	    <div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<div class="col-md-8">
							<strong>
								<span class="glyphicon glyphicon-th"></span>
								<span>Solicitud de Información de <?php echo $ejercicio ?> </span>
							</strong>
					</div>
					<div class="col-md-1" style="margin: 20px 40px 10px 0px;">
						 <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>?anio=<?php echo $ejercicio?>" method="post">
                    <button style="float: right; margin-top: -20px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>
					</div>
					<div class="col-md-1">
						 <?php if (($nivel_user <= 2) || ($nivel_user == 10) ) : ?>
                    <a href="add_solicitud_ut.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Solicitud</a>
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
                        <th style="width: 1%;">Status</th>
                        <th style="width: 6%;">Folio</th>
                        <th style="width: 1%;">Fecha Presentacion</th>
                        <th style="width: 1%;">Fecha Tentativa de Respuesta</th>
                        <th style="width: 1%;">Fecha de Respuesta</th>
                        <th style="width: 1%;">Nombre Solicitante</th>
                        <th style="width: 5%;">Género Solicitante</th>
                        <th style="width: 2%;">Medio de Presentación</th>
                        <th style="width: 2%;">Tipo Solicitud</th>
                        
                        <?php if ($nivel_user <= 2 || ($nivel_user == 10) || ($nivel_user == 7)) : ?>
                            <th style="width: 1%;" class="text-center">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                   <?php foreach ($all_solicitudes as $datos) : 
				   $genero =find_campo_id('cat_genero', $datos['id_cat_gen'], 'id_cat_gen','descripcion');
				   $presentacion =find_campo_id('cat_medio_pres_ut', $datos['id_cat_med_pres_ut'], 'id_cat_med_pres_ut','descripcion');
				   $tipo_solicitud =find_campo_id('cat_tipo_solicitud', $datos['id_cat_tipo_solicitud'], 'id_cat_tipo_solicitud','descripcion');
				   $dia_vencimiento = sumasdiasemana($datos['fecha_presentacion'],20);
				   $fecha_proxima =sumasdiasemana($datos['fecha_presentacion'],15);
				   $fecha_respuesta = ($datos['fecha_respuesta']==NULL?'':date("d-m-Y", strtotime(remove_junk(ucwords($datos['fecha_respuesta'])))));
				   
					if($datos['fecha_respuesta'] === NULL){
						if(strtotime($fechaActual,time()) > strtotime($dia_vencimiento) ){
							$image_status ="red.png";
							$title_status = "Vencida";
						}else if(strtotime($fechaActual,time()) > strtotime($fecha_proxima) && strtotime($fechaActual,time()) < strtotime($dia_vencimiento)){
							$image_status ="orange.png";
							$title_status = "Proxima a vencer";
						}else if(strtotime($fechaActual,time()) <  strtotime($dia_vencimiento)){
							$image_status ="green.png";
							$title_status ="En tiempo";
						}
					}else{
						if(strtotime($datos['fecha_respuesta'],time()) > strtotime($dia_vencimiento) ){
							$image_status ="roja.png";
							$title_status ="Respuesta a destiempo";
						}else{
							$image_status ="verde.png";
							$title_status ="Respuesta a tiempo";
						}
					}
				   
				   ?> 
                        <tr>
                            <td class="text-center">							
								<img src="medios/<?php echo $image_status; ?>" style="width: 21px; height: 20.5px; " title="<?php echo $title_status; ?>">
							</td>
                            <td><?php echo remove_junk(ucwords($datos['folio_solicitud'])) ?></td>
							<td><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($datos['fecha_presentacion'])))) ?></td>
							<td><?php echo date("d-m-Y", strtotime($dia_vencimiento)) ?></td>
							<td><?php echo  "".$fecha_respuesta. ""?></td>
                            <td><?php echo remove_junk((ucwords($datos['nombre_solicitante']))) ?></td>
                            <td><?php echo remove_junk((ucwords($genero['descripcion']))) ?></td>
                            <td><?php echo remove_junk((ucwords($presentacion['descripcion']))) ?></td>
                            <td><?php echo remove_junk((ucwords($tipo_solicitud['descripcion']))) ?></td>
                            
                            <?php if (($nivel_user <= 2) || ($nivel_user == 7) || ($nivel_user == 10)) : ?>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="ver_info_solicitud_info.php?id=<?php echo (int)$datos['id_solicitudes_informacion']; ?>" data-toggle="tooltip" title="Ver información completa">
                                            <img src="medios/ver_info.png" style="width: 31px; height: 30.5px; border-radius: 15%; margin-right: -2px;">
                                        </a>&nbsp;
										<?php if (($nivel_user <= 2) ||($nivel_user == 10) ) : ?>
											<a href="edit_solicitudes_ut.php?id=<?php echo (int)$datos['id_solicitudes_informacion']; ?>"  title="Editar" data-toggle="tooltip">
												<img src="medios/editar2.png" style="width: 31px; height: 30.5px; border-radius: 15%; margin-right: -2px;">                                            
											</a>&nbsp;
										<?php endif; ?>
										<?php if (($nivel_user <= 2) ||($nivel_user == 10) ) : 
												if($fecha_respuesta === ''):
										?>
											<a href="respuesta_solicitud.php?id=<?php echo (int)$datos['id_solicitudes_informacion']; ?>"  title="Respuesta" data-toggle="tooltip">
												<img src="medios/resolucion2.png" style="width: 31px; height: 30.5px; border-radius: 15%; ">                                        
											</a>
										<?php endif; 
										endif; ?>
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