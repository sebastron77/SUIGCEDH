<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Correspondencia Interna Recibida';
require_once('includes/load.php');
?>
<?php
page_require_level(53);
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];



$area = isset($_GET['a']) ? $_GET['a'] : '0';

$area_informe = find_by_id('area', $area, 'id_area');
$nombre_area = $area_informe['nombre_area'];


if($area > 0){
	if($area==4){
			// Identificamos a que área pertenece el usuario logueado
			$area_user = area_usuario2($id_user);
			$area = $area_user['id_area'];
			$nombre_area = $area_user['nombre_area'];		
		$all_correspondencia = find_all_env_correspondencia2($area,$ejercicio);
		$page_regresar ="solicitudes_correspondencia.php?a=".$area;
	}else{
if($area == 2){
		$page_regresar ="solicitudes_correspondencia_presidencia.php?a=2";
}else{
		$page_regresar ="solicitudes_correspondencia.php?a=".$area;
}	
		$all_correspondencia = find_all_env_correspondencia2($area,$ejercicio);
	}
}else{
    $all_correspondencia = find_all_env_correspondenciaAdmin2($ejercicio);	
}


if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la '.$page_title." del área ".$nombre_area. " del Ejercicio ".$ejercicio, 5);   
}



$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");

if (($nivel_user <= 2) || ($nivel_user == 7) || ($nivel_user == 8)) {
    $sql = "SELECT c.id_env_corresp, fecha_emision,c.folio, IFNULL(no_oficio,'S/N') as no_oficio,  IFNULL(c.fecha_en_que_se_turna,'') as fecha_en_que_se_turna, 
  IFNULL(c.fecha_espera_respuesta,'') as fecha_espera_respuesta, c.asunto, c.medio_envio, a.nombre_area, 
  b.nombre_area as nombre_area_creacion,IF(accion_realizada is null,'No','Si') as accion_realizada ,IF(accion_realizada is null,'0','1') as atendida
          FROM envio_correspondencia c
          LEFT JOIN area a ON c.se_turna_a_area = a.id_area 
          LEFT JOIN area b ON c.area_creacion = b.id_area 
		  WHERE YEAR(fecha_emision)='{$ejercicio}'
		  ORDER BY fecha_en_que_se_turna DESC";
} else {
    $sql = "SELECT c.id_env_corresp, fecha_emision,c.folio, IFNULL(no_oficio,'S/N') as no_oficio,  IFNULL(c.fecha_en_que_se_turna,'') as fecha_en_que_se_turna, 
  IFNULL(c.fecha_espera_respuesta,'') as fecha_espera_respuesta, c.asunto, c.medio_envio, a.nombre_area, 
  b.nombre_area as nombre_area_creacion,IF(accion_realizada is null,'No','Si') as accion_realizada ,IF(accion_realizada is null,'0','1') as atendida
          FROM envio_correspondencia c
          LEFT JOIN area a ON c.se_turna_a_area = a.id_area 
          LEFT JOIN area b ON c.area_creacion = b.id_area 
		  WHERE se_turna_a_area='{$area}'
			AND YEAR(fecha_emision)='{$ejercicio}'
		  ORDER BY fecha_en_que_se_turna DESC";
}
$resultado = mysqli_query($conexion, $sql) or die;
$correspondencias = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $correspondencias[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($correspondencias)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=correspondencia_interta_recibida.xls");

        $filename = "correspondencia_interta_recibida.xls";
        $mostrar_columnas = false;

        foreach ($correspondencias as $correspondencia) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($correspondencia)) . "\n");
                $mostrar_columnas = true;
            }
				echo utf8_decode(implode("\t", array_values($correspondencia)) . "\n");
        }
		if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" descargo la '.$page_title.' del Área '.$nombre_area.' del Ejercicio '.$ejercicio, 6);   
		}
    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}

// page_require_level(15);

?>
<script type="text/javascript">	
 function changueAnio(anio,area){
	 window.open("correspondencia_recibida.php?anio="+anio+"&a="+area,"_self");	 
 }
</script>
<?php include_once('layouts/header.php'); ?>


<a href="<?php echo $page_regresar?>" class="btn btn-success">Regresar</a><br><br>

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
								<span>Correspondencia Interna Recibida de <?php echo $nombre_area?> de <?php echo $ejercicio ?></span>
							</strong>
					</div>
					<div class="col-md-2">
						<div class="form-group" >
							<select class="form-control" name="ejercicio" onchange="changueAnio(this.value,<?php echo $area;?>)">
								<option value="">Selecciona Ejercicio</option>
								<?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
								echo "<option value='".$i."'>".$i."</option>";
								}?>								
							</select>
						</div>	
					</div>
					
                <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>?a=<?php echo $area?>&anio=<?php echo $ejercicio ?>" method="post">
                    <button style="float: right; margin-top: 0px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>				
					
				</div>
			</div>
		</div>
	</div>


    <div class="col-md-12">
        <div class="panel panel-default">
           

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
						<th style="width: 1%;">#</th>
                            <th class="text-center" style="width: 1%;">Semáforo</th>
                            <th class="text-center" style="width: 3%;">Folio</th>
                            <th class="text-center" style="width: 3%;">No. Oficio</th>
							<th class="text-center" style="width: 3%;">Fecha Emisión Oficio</th>
                            <th class="text-center" style="width: 3%;">Fecha en que se turna</th>
                            <th class="text-center" style="width: 3%;">Fecha espera respuesta</th>
                            <th class="text-center" style="width: 3%;">¿Atendida?</th>
                            <th class="text-center" style="width: 7%;">Asunto</th>
                            <th class="text-center" style="width: 4%;">Medio de Envío</th>
                            <th class="text-center" style="width: 4%;">Área que turnó el oficio</th>
                            <th class="text-center" style="width: 5%;">Área a la que se turna</th>
                            <th class="text-center" style="width: 1%;" class="text-center">Acciones</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_correspondencia as $a_correspondencia) : ?>
                            <?php
                            $folio_editar = $a_correspondencia['folio'];
                            $resultado = str_replace("/", "-", $folio_editar);
                            date_default_timezone_set('America/Mexico_City');
                            $creacion = date('Y-m-d');
                            ?>
                            <tr>
							<td class="text-center"><?php echo count_id(); ?></td>
                                <?php if ($a_correspondencia['fecha_espera_respuesta'] > $creacion) : ?>
                                    <td class="text-center">
                                        <h1><span class="green">v</span>
                                    </td>
                                <?php endif; ?>
                                <?php if ($a_correspondencia['fecha_espera_respuesta'] == $creacion) : ?>
                                    <td class="text-center">
                                        <h1><span class="yellow">a</span>
                                    </td>
                                <?php endif; ?>
                                <?php if ($a_correspondencia['fecha_espera_respuesta'] < $creacion) : ?>
                                    <td class="text-center">
                                        <h1><span class="red">r</span>
                                    </td>
                                <?php endif; ?>
                                <td><?php echo remove_junk(ucwords($a_correspondencia['folio'])) ?></td>
								<td><?php echo remove_junk(ucwords($a_correspondencia['no_oficio'])) ?></td>
                                <td class="text-center"><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($a_correspondencia['fecha_emision'])))) ?></td>
                                <td class="text-center"><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($a_correspondencia['fecha_en_que_se_turna'])))) ?></td>
                                <td class="text-center"><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($a_correspondencia['fecha_espera_respuesta'])))) ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords($a_correspondencia['accion_realizada'])) ?></td>
                                <td><?php echo remove_junk(ucwords($a_correspondencia['asunto'])) ?></td>
                                <td><?php echo remove_junk(ucwords(($a_correspondencia['medio_envio']))) ?></td>
                                <td><?php echo remove_junk(ucwords(($a_correspondencia['nombre_area_creacion']))) ?></td>
                                <td><?php echo remove_junk(ucwords(($a_correspondencia['nombre_area']))) ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="ver_info_env_correspondencia.php?id=<?php echo (int)$a_correspondencia['id_env_corresp']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
                                      <!--  <a href="edit_env_correspondencia.php?id=<?php echo (int)$a_correspondencia['id_env_corresp']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>
										-->
										<?php if( ( $nivel_user != 7) &&( $nivel_user != 53)) :         ?>					
										<?php if((int)$a_correspondencia['atendida'] ==0): ?>
                                        <a href="seguimiento_env_correspondencia.php?id=<?php echo (int)$a_correspondencia['id_env_corresp']; ?>&a=<?php echo $area?>" class="btn btn-secondary btn-md" title="Seguimiento" data-toggle="tooltip">
                                            <span class="glyphicon glyphicon-arrow-right"></span>
                                        </a>
										<?php endif;?>
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
</div>

<?php include_once('layouts/footer.php'); ?>