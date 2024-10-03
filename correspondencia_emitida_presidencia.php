
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Correspondencia Generada en Presidencia';
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
$all_correspondencia = find_all_correspondenciaPte($area,$ejercicio);	


if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la '.$page_title." del área ".$nombre_area. " del Ejercicio ".$ejercicio, 5);   
}

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");

if (($nivel_user <= 2) || ($nivel_user == 7) || ($nivel_user == 8)) {
    $sql = "SELECT c.id_env_corresp, fecha_emision,IFNULL(no_oficio,'S/N') as no_oficio,c.folio, IFNULL(c.fecha_en_que_se_turna,'') as fecha_en_que_se_turna, 
  IFNULL(c.fecha_espera_respuesta,'') as fecha_espera_respuesta, c.asunto, c.medio_envio, a.nombre_area ,IF(accion_realizada is null,'No','Si') as accion_realizada
          FROM envio_correspondencia c
          LEFT JOIN area a ON c.se_turna_a_area = a.id_area WHERE  YEAR(fecha_emision)='".$ejercicio."'";
} else {
    $sql = "SELECT c.id_env_corresp, fecha_emision,IFNULL(no_oficio,'S/N') as no_oficio,c.folio, IFNULL(c.fecha_en_que_se_turna,'') as fecha_en_que_se_turna, 
  IFNULL(c.fecha_espera_respuesta,'') as fecha_espera_respuesta, c.asunto, c.medio_envio, a.nombre_area ,IF(accion_realizada is null,'No','Si') as accion_realizada
          FROM envio_correspondencia c
          LEFT JOIN area a ON c.se_turna_a_area = a.id_area WHERE area_creacion='{$area}' AND YEAR(fecha_emision)='".$ejercicio."'";
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
        header("Content-Disposition: attachment; filename=correspondencia_interta_enviada.xls");

        $filename = "correspondencia_interta_enviada.xls";
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

?>
<script type="text/javascript">	
 function changueAnio(anio,area){
	 window.open("correspondencia_emitida_presidencia.php?anio="+anio+"&a="+area,"_self");	 
 }
</script>
<?php include_once('layouts/header.php'); ?>

<a href="solicitudes_correspondencia_presidencia.php?a=<?php echo $area?>" class="btn btn-success">Regresar</a><br><br>

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
								<span>Correspondencia Generada de <?php echo $nombre_area?>  de <?php echo $ejercicio ?></span>
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
					
                <?php if( ( $nivel_user != 7) &&( $nivel_user != 53)) :         ?>	
                <a href="add_mail_emitida_presidencia.php?a=<?php echo $area?>" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Correspondencia</a>
				<?php endif;               ?>	
                

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
                            <th class="text-center" style="width: 1%;">#</th>
                            <th class="text-center" style="width: 1%;">Estatus</th>
                            <th class="text-center" style="width: 1%;">¿Viene de Oficialia?</th>
                            <th class="text-center" style="width: 1%;">Tipo Correspondencia</th>
                            <th class="text-center" style="width: 3%;">Folio</th>
                            <th class="text-center" style="width: 3%;">No. Oficio</th>
                            <th class="text-center" style="width: 3%;">Fecha Emisión Oficio</th>
                            <th class="text-center" style="width: 7%;">Asunto</th>
                            <th class="text-center" style="width: 4%;">Medio de Envío</th>
                            <th class="text-center" style="width: 5%;">Turnado a</th>
							<th class="text-center" style="width: 3%;">¿Atendida?</th>
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
                                <td class="text-center">
									
                                </td>
                                <td><?php echo remove_junk(ucwords($a_correspondencia['envio_oficialia'])) ?></td>
                                <td><?php echo remove_junk(ucwords($a_correspondencia['tipo_mail'])) ?></td>
                                <td><?php echo remove_junk(ucwords($a_correspondencia['folio'])) ?></td>
                                <td><?php echo remove_junk(ucwords($a_correspondencia['no_oficio'])) ?></td>
								<td class="text-center"><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($a_correspondencia['fecha_emision'])))) ?></td>
                                <td><?php echo remove_junk(ucwords($a_correspondencia['asunto'])) ?></td>
                                <td><?php echo remove_junk(ucwords(($a_correspondencia['medio_envio']))) ?></td>
                                <td><?php echo remove_junk(ucwords(($a_correspondencia['area_turnada']))) ?></td>
								<td class="text-center"><?php echo remove_junk(ucwords($a_correspondencia['accion_realizada'])) ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="ver_info_mail_emitida_presidencia.php?id=<?php echo (int)$a_correspondencia['id']; ?>&t=<?php echo (int)$a_correspondencia['id_tipo']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>&nbsp;
										<?php if( ( $nivel_user != 7) &&( $nivel_user != 53)) :         ?>		
                                        <a href="edit_mail_emitida_presidencia.php?id=<?php echo (int)$a_correspondencia['id']; ?>&t=<?php echo (int)$a_correspondencia['id_tipo']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>
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