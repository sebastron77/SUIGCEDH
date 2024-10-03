<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Colaboraciones';
require_once('includes/load.php');
?>
<?php 
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$user = current_user();
$id_usuario = $user['id_user'];
$busca_area = area_usuario($id_usuario);
$otro = $busca_area['nivel_grupo'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level(7);
}
if ($nivel_user == 12) {
    page_require_level(12);
}


if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title.' del Ejercicio '.$ejercicio, 5);
}
$all_acompaniamientos= find_all_Colaboraciones($ejercicio);


$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT
	id_colaboraciones,folio,ejercicio,
  IFNULL(CONCAT(d.nombre,' ',d.paterno,' ',d.materno),'') as persona_desaparecida, 
  solicitante,quien_atendio,fecha_creacion
FROM  colaboraciones a
LEFT JOIN cat_persona_desaparecida d USING(id_cat_persona_desaparecida)  
 WHERE ejercicio= '{$ejercicio}'";
$resultado = mysqli_query($conexion, $sql) or die;
$dates = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $dates[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($dates)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=colaboraciones_ud.xls");
        $filename = "colaboraciones_ud.xls";
        $mostrar_columnas = false;

        foreach ($dates as $datos) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($datos)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($datos)) . "\n");
        }
		if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" descargó la lista de  '.$page_title.' del Ejercicio '.$ejercicio, 6);
}
    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}

?>
<script type="text/javascript">	
 function changueAnio(anio){
	 window.open("colaboraciones_ud.php?anio="+anio,"_self");
	 
 }
</script>

<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<a href="solicitudes_desaparecidos.php" class="btn btn-success">Regresar</a><br><br>
<div class="row">

	<div class="row">
	    <div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<div class="col-md-8">
							<strong>
								<span class="glyphicon glyphicon-th"></span>
								<span>Colaboraciones de Desaparecidos <?php echo $ejercicio ?></span>
							</strong>
					</div>
					<div class="col-md-1" style="margin: 20px 40px 10px 0px;">
						 
					</div>
					<div class="col-md-1">
						<?php if (($nivel_user <= 2) || ($nivel_user == 12) ) : ?>
                    <a href="add_colaboracion_ud.php" class="btn btn-info pull-right btn-md"> Agregar Colaboración</a>
                <?php endif ?>
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
					<form action=" <?php echo $_SERVER["PHP_SELF"]; ?>?anio=<?php echo $ejercicio ?>" method="post">
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
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 10%;">Ejercicio</th>
                            <th style="width: 10%;">Folio</th>
                            <th class="text-center" style="width: 15%;">Quien Solicita</th>
                            <th class="text-center" >Nombre Persona Desaparecida</th>
                            <th class="text-center" >¿Quién Atendió?</th>
                            <?php if (($nivel_user <= 2) || ($nivel_user == 7) || ($nivel_user == 12) ) : ?>
                                <th class="text-center" style="width: 20%;">Acciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_acompaniamientos as $adetalle) : ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td><?php echo remove_junk(($adetalle['ejercicio'])) ?></td>
                                <td><?php echo remove_junk(($adetalle['folio'])) ?></td>
                                <td class="text-center"><?php echo remove_junk(($adetalle['solicitante'])) ?></td>
                                <td class="text-center"><?php echo remove_junk($adetalle['persona_desaparecida']) ?></td>
                                <td class="text-center"><?php echo remove_junk($adetalle['quien_atendio']) ?></td>
                                
                                <?php if (($nivel_user <= 2) || ($nivel_user == 7) || ($nivel_user == 12) ) : ?>
                                    <td class="text-center">
                                        <div class="btn-group">
											<a href="ver_info_colaboracion.php?id=<?php echo (int)$adetalle['id_colaboraciones']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información completa">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>&nbsp;
                                            <?php if (($nivel_user <= 2) || ($nivel_user == 12) ) : ?>
                                                <a href="edit_colaboracion_ud.php?id=<?php echo (int)$adetalle['id_colaboraciones']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                    <i class="glyphicon glyphicon-pencil"></i>
                                                </a>&nbsp;
												<a href="seguimiento_colaboracion_ud.php?id=<?php echo (int)$adetalle['id_colaboraciones']; ?>" class="btn btn-md btn-gre" data-toggle="tooltip" title="Seguimiento">
                                                    <i class="glyphicon glyphicon-sort-by-attributes-alt"></i>
                                                </a>
                                            <?php endif ?>
                                            
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