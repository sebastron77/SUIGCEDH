<?php
$page_title = 'Acciones de Vinculación';
require_once('includes/load.php');
?>
<?php
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$all_fichas = find_all_accionesV($ejercicio);
$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 3) {
    page_require_level_exacto(3);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la Acciones de Vinculación del Ejercicio '.$ejercicio, 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la Acciones de Vinculación del Ejercicio '.$ejercicio, 5);
    page_require_level_exacto(53);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 53) :
    redirect('home.php');
endif;


$conexion = mysqli_connect ("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion,"utf8");
mysqli_select_db ($conexion, "suigcedh");
$sql = "SELECT av.id_accionV, av.folio, av.fecha, av.lugar, REPLACE(av.nombre_actividad,  CHAR(13, 10), ' ') as nombre_actividad, 
		REPLACE(av.descripcion,  CHAR(13, 10), ' ') descripcion, REPLACE(av.participantes,  CHAR(13, 10), ' ') as participantes, a.descripcion as inst_procedencia, 
          av.modalidad, REPLACE(av.observaciones,  CHAR(13, 10), ' ') as observaciones, av.carpeta, av.creador, av.fecha_creacion 
          FROM acciones_vinculacion av
          LEFT JOIN cat_instituciones a ON a.id_cat_instituciones = av.inst_procedencia
		  WHERE av.folio LIKE '%/".$ejercicio."-%'";
$resultado = mysqli_query ($conexion, $sql) or die;
$jornadas = array();
while( $rows = mysqli_fetch_assoc($resultado) ) {
    $jornadas[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($jornadas)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=acciones_vinculacion.xls");        
        $filename = "acciones_vinculacion.xls";
        $mostrar_columnas = false;

        foreach ($jornadas as $resolucion) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($resolucion)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($resolucion)) . "\n");
        }
		if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" descargó  la lista de Acciones de Vinculación del Ejercicio '.$ejercicio, 6);    
		}

    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}

?>
<script type="text/javascript">	
 function changueAnio(anio){
	 window.open("acciones_vinculacion.php?anio="+anio,"_self");	 
 }
</script>
<?php include_once('layouts/header.php'); ?>

<a href="solicitudes_ejecutiva.php" class="btn btn-success">Regresar</a><br><br>

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
								<span>Acciones de Vinculación <?php echo $ejercicio ?></span>
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
					
					 <?php if (($nivel_user <= 2) || ($nivel_user == 3) ) : ?>
                    <a href="add_accionV.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar acción de vinculación</a>
                <?php endif; ?>
                <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>?anio=<?php echo $ejercicio ?>" method="post">
                    <button style="float: right; margin-top: 0px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>
					
					
				</div>
			</div>
		</div>
	</div>


    <div class="col-md-12">
        

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 1.5%;">Folio</th>
                            <th class="text-center" style="width: 1.2%;">Fecha</th>
                            <th class="text-center" style="width: 10%;">Lugar</th>
                            <th class="text-center" style="width: 10%;">Nombre de actividad</th>
                            <th class="text-center" style="width: 10%;">Institución de Procedencia</th>
                                <th style="width: 0.1%;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_fichas as $a_ficha) : ?>
                            <tr>
                                <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['folio'])) ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['fecha'])) ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['lugar'])) ?></td>
                                <td class="text-center"><?php echo remove_junk($a_ficha['nombre_actividad']) ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords($a_ficha['inst_procedencia'])) ?></td>
                                
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="ver_info_accionV.php?id=<?php echo (int)$a_ficha['id_accionV']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                                <i class="glyphicon glyphicon-eye-open"></i>
                                            </a>
											<?php if (($nivel_user <= 3)  ) : ?>
                                            <a href="ver_imagenes_accionV.php?carpeta=<?php echo $a_ficha['carpeta']; ?>" class="btn btn-md btn-secondary" data-toggle="tooltip" title="Ver evidencia fotográfica">
                                                <i class="glyphicon glyphicon-picture"></i>
                                            </a>
                                            <a href="edit_accionv.php?id=<?php echo (int)$a_ficha['id_accionV']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                                <span class="glyphicon glyphicon-edit"></span>
                                            </a>
											<?php endif; ?>										
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