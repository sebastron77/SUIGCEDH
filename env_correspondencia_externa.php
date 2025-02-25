
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Correspondencia Externa enviada';
require_once('includes/load.php');
?>
<?php
page_require_level(53);
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];


$area = isset($_GET['a']) ? $_GET['a'] : '0';

$area_informe = find_by_id('area', $area, 'id_area');
$nombre_area = $area_informe['nombre_area'];

$all_correspondencia = find_all_env_correspondenciaExter($area,$ejercicio);


if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la '.$page_title." del área ".$nombre_area. " del Ejercicio ".$ejercicio, 5);   
}
	

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");

    $sql = "SELECT 
	id_correspondencia_externa,
	folio,
    num_oficio,
    fecha_oficio,
    nombre_destinatario,
    nombre_institucion,
    cargo_destinatario,
    asunto,
    medio_entrega,
    quien_realizo
FROM correspondencia_externa a
LEFT JOIN `area` b ON a.`id_area_creacion`=b.`id_area` 
WHERE id_area_creacion=".$area."
  AND YEAR(fecha_oficio)= '".$ejercicio."'
ORDER BY fecha_oficio DESC ";

$resultado = mysqli_query($conexion, $sql) or die;
$correspondencias = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $correspondencias[] = $rows;
}

mysqli_close($conexion);
if (isset($_POST["export_data"])) {
    if (!empty($correspondencias)) {
		header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');;
        header("Content-Disposition: attachment; filename=correspondencia_enviada_externa.xls");
        $filename = "correspondencia_enviada_externa.xls";
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
        echo 'No hay datos a exportar'.$sql;
    }
    exit;
}

?>

<script type="text/javascript">	
 function changueAnio(anio,area){
	 window.open("env_correspondencia_externa.php?anio="+anio+"&a="+area,"_self");	 
 }
</script>
<?php include_once('layouts/header.php'); ?>

<a href="solicitudes_correspondencia.php?a=<?php echo $area?>" class="btn btn-success">Regresar</a><br><br>

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
								<span>Correspondencia Externa Enviada de <?php echo $nombre_area?> de <?php echo $ejercicio ?></span>
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
                <a href="add_env_correspondencia_externa.php?a=<?php echo $area?>" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Correspondencia</a>
				<?php endif; ?>

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
                            <th class="text-center" style="width: 3%;">Folio</th>
                            <th class="text-center" style="width: 3%;">No. Oficio</th>
                            <th class="text-center" style="width: 3%;">Fecha de Oficio</th>
                            <th class="text-center" style="width: 7%;">Institución Destinataria</th>
                            <th class="text-center" style="width: 7%;">Asunto</th>
                            <th class="text-center" style="width: 4%;">Medio de Entrega</th>
                            <th class="text-center" style="width: 1%;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_correspondencia as $a_correspondencia) : ?>                            
                            <tr>
							<td class="text-center"><?php echo count_id(); ?></td>
                                <td><?php echo remove_junk(ucwords($a_correspondencia['folio'])) ?></td>
                                <td><?php echo remove_junk(ucwords($a_correspondencia['num_oficio'])) ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords(($a_correspondencia['fecha_oficio']))) ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords(($a_correspondencia['nombre_institucion']))) ?></td>
                                <td><?php echo remove_junk(ucwords($a_correspondencia['asunto'])) ?></td>
                                <td><?php echo remove_junk(ucwords(($a_correspondencia['medio_entrega']))) ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="ver_info_env_correspondencia_externa.php?id=<?php echo (int)$a_correspondencia['id_correspondencia_externa']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>&nbsp;
										<?php if( ( $nivel_user != 7) &&( $nivel_user != 53)) :         ?>	
                                        <a href="edit_env_correspondencia_externa.php?id=<?php echo (int)$a_correspondencia['id_correspondencia_externa']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
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