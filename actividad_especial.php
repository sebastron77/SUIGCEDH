<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Actividad Especial de Comunicación Social';
require_once('includes/load.php');
?>
<?php

$user = current_user();
$nivel = $user['user_level'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 15) {
    page_require_level_exacto(15);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);
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

$all_actividades = find_all_order('actividades_especiales', 'id_actividades_especiales');

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT folio,fecha_actividad,tema_actividad,lugar_actividad,b.descripcion as eje_estrategico, c.descripcion as agenda_institucional,observaciones,asistentes_otros,asistentes_nobinario,asistentes_mujeres,asistentes_hombres  	
	FROM actividades_especiales a
	LEFT JOIN cat_ejes_estrategicos b USING(id_cat_ejes_estrategicos) 
	LEFT JOIN cat_agendas c USING(id_cat_agendas)
ORDER BY fecha_actividad DESC";
$resultado = mysqli_query($conexion, $sql) or die;
$atuaciones = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $atuaciones[] = $rows;
}

mysqli_close($conexion);
if (isset($_POST["export_data"])) {
    if (!empty($atuaciones)) {
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=actividas_especial.xls");
        $filename = "actividas_especial.xls";
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
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<a href="solicitudes_comunicacion_social.php" class="btn btn-success">Regresar</a><br><br>
<div class="row">
    <div class="col-md-12"> 
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Actividades Especial</span>
                </strong>  
<?php if (($nivel == 1) || ($nivel == 15)) : ?>
                    <a href="add_actividad_especial.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Actividad</a>
                <?php endif; ?>
                <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <button style="float: right; margin-top: -20px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>				
            </div>
        </div>

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr>
                        <th width="10%">Folio</th>
                        <th width="10%">Fecha Actividad</th>
                        <th width="10%">Tema Actividad</th>
                        <th width="10%">Nombre Eje</th>
                        <th width="10%">Nombre Agenda</th>
                        <th width="10%">No. Asistentes</th>
                        <th width="10%">Aciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_actividades as $datos) : 
					$nombre_eje =find_campo_id('cat_ejes_estrategicos', $datos['id_cat_ejes_estrategicos'], 'id_cat_ejes_estrategicos','descripcion');
					$nombre_agenda =find_campo_id('cat_agendas', $datos['id_cat_agendas'], 'id_cat_agendas','descripcion');
					$total_asistentes = (int)$datos['asistentes_hombres'] + (int)$datos['asistentes_mujeres']+(int)$datos['asistentes_nobinario'] + (int)$datos['asistentes_otros'];
					?>
                        <tr>
                               <td><?php echo remove_junk(ucwords($datos['folio'])) ?></td> 
                               <td><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($datos['fecha_actividad'])))) ?></td>                                
                               <td><?php echo remove_junk(ucwords($datos['tema_actividad'])) ?></td> 
                               <td><?php echo remove_junk(ucwords($nombre_eje['descripcion'])) ?></td>                                 
                               <td><?php echo remove_junk(ucwords($nombre_agenda['descripcion'])) ?></td>                                 
                               <td><?php echo  $total_asistentes?></td>                                 
                               
                               <td class="text-center">
							   <a href="ver_info_actividad_especial.php?id=<?php echo (int)$datos['id_actividades_especiales']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información completa">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
<?php if (($nivel == 1) || ($nivel == 15)) : ?>
									<a href="edit_actividad_especial.php?id=<?php echo (int)$datos['id_actividades_especiales']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
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