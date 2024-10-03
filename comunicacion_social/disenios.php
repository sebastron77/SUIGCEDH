<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Diseños';
require_once('includes/load.php');
?>
<?php

$user = current_user();
$nivel = $user['user_level'];
$nivel_user = $user['user_level'];
$all_disenios = find_all_order('disenios', 'id_disenios');

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 15) {
    page_require_level_exacto(15);
}
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user >7  && $nivel_user < 15) :
    redirect('home.php');
endif;
if ($nivel_user > 15 ) :
    redirect('home.php');
endif;


$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT
	id_disenios,
  `folio` ,
  `fecha_solicitud`,
  `fecha_entrega` ,
  `tema_disenio` ,
  `id_area_solicitud` ,
  `observaciones` ,
  `user_creador` ,
  fecha_creacion
FROM disenios ";
$resultado = mysqli_query($conexion, $sql) or die;
$atuaciones = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $atuaciones[] = $rows;
}

mysqli_close($conexion);
if (isset($_POST["export_data"])) {
    if (!empty($atuaciones)) {
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=disenios.xls");
        $filename = "disevo.xls";
        $mostrar_columnas = false;

        foreach ($atuaciones as $datos) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($datos)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($datos)) . "\n");
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

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Diseños</span>
                </strong>  
<?php if (($nivel == 1) || ($nivel == 15)) : ?>
                    <a href="add_disenio.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Diseño</a>
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
                        <th width="10%">Tipo Diseño</th>
                        <th width="10%">Área que solicito</th>
                        <th width="10%">Tema</th>
                        <th width="10%">Fecha Solicitud</th>
                        <th width="10%">Fecha Entrega</th>
                        <th width="10%">Aciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_disenios as $datos) : 
					$nombre_area =find_campo_id('area', $datos['id_area_solicitud'], 'id_area','nombre_area');
					$nombre_disenio =find_descripcion_id('cat_tipo_disenios', $datos['id_cat_tipo_disenios'], 'id_cat_tipo_disenios');
					
					?>
                        <tr>
                               <td><?php echo remove_junk(ucwords($datos['folio'])) ?></td> 
                               <td><?php echo  $nombre_disenio['descripcion']?></td> 
                               <td><?php echo remove_junk(ucwords($nombre_area['nombre_area'])) ?></td> 
                               <td><?php echo remove_junk(ucwords($datos['tema_disenio'])) ?></td> 
                               <td><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($datos['fecha_solicitud'])))) ?></td>                                
                               <td><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($datos['fecha_entrega'])))) ?></td>                                
                               
                               <td class="text-center">
								<a href="ver_info_disenio.php?id=<?php echo (int)$datos['id_disenios']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información completa">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
										
									<a href="edit_disenio.php?id=<?php echo (int)$datos['id_disenios']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
										<i class="glyphicon glyphicon-pencil"></i>
									</a>
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