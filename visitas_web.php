<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Visitas Web';
require_once('includes/load.php');
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$user = current_user();
$nivel = $user['user_level'];

$id_usuario = $user['id_user'];
$id_user = $user['id_user'];
$busca_area = area_usuario($id_usuario);
$otro = $busca_area['nivel_grupo'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title.'del Ejercicio '.$ejercicio, 5);
    page_require_level(7);
}
if ($nivel_user == 13) {
    page_require_level_exacto(13);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title.'del Ejercicio '.$ejercicio, 5);
    page_require_level(53);
}
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 13) :
    redirect('home.php');
endif;
if ($nivel_user > 13 && $nivel_user < 53) :
    redirect('home.php');
endif;
$all_vistas = find_data_year('visitas_web','ejercicio',$ejercicio);



$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");

$sql = "SELECT ejercicio, date_format(CONCAT(ejercicio,'-',mes,'-01'),'%M') as periodo,date_format(LAST_DAY(CONCAT('2023-',mes,'-01')),'%d') as dia, 
date_format(LAST_DAY(CONCAT('2023-',mes,'-01')),'%m') as mes,
date_format(LAST_DAY(CONCAT('2023-',mes,'-01')),'%Y') as anio, desktop,movil,tablet,vistas_a_pag,total_vistas
        FROM visitas_web s WHERE ejercicio={$ejercicio}
        ORDER BY ejercicio";

$resultado = mysqli_query($conexion, $sql) or die;
$sesiones = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $sesiones[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($sesiones)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel;charset=UTF-8');
        header("Content-Disposition: attachment; filename=sesiones.xls");
        $filename = "sesiones.xls";
        $mostrar_columnas = false;

        foreach ($sesiones as $resolucion) {
            if (!$mostrar_columnas) {
                echo implode("\t", array_keys($resolucion)) . "\n";
                $mostrar_columnas = true;
            }
            echo implode("\t", array_values($resolucion)) . "\n";
        }
		if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" descargó  la '.$page_title.' del Ejercicio '.$ejercicio, 6);
		}
    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}

?>
<script type="text/javascript">	
 function changueAnio(anio){
	 window.open("visitas_web.php?anio="+anio,"_self");	 
 }
</script>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<a href="solicitudes_sistemas.php" class="btn btn-success">Regresar</a><br><br>
<div class="row">

<div class="row">
	    <div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<div class="col-md-10">
							<strong>
								<span class="glyphicon glyphicon-th"></span>
								<span>Visitas Web de <?php echo $ejercicio ?></span>
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
				<?php if ($nivel_user <= 2 ||  $nivel_user == 13) : ?>
                    <a href="add_visita_web.php" style="margin-left: 10px; margin-right: 10px" class="btn btn-info pull-right btn-md">Agregar visita</a>
                <?php endif ?>
				<form action=" <?php echo $_SERVER["PHP_SELF"]; ?>?anio=<?php echo $ejercicio?>" method="post">	
                    <button style="float: right; margin-top: -0px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>
			</div>
		</div>
	</div>

    <div class="col-md-12">
        <div class="panel panel-default">
            
            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead>
                        <tr class="thead-purple">
                            <th class="text-center" style="width: 1%;">Ejercicio</th>
                            <th class="text-center" style="width: 1%;">Mes</th>
                            <th class="text-center" style="width: 1%;">Desktop</th>
                            <th class="text-center" style="width: 1%;">Móvil</th>
                            <th class="text-center" style="width: 1%;">Tablet</th>
                            <th class="text-center" style="width: 1%;">Vistas Página</th>
                            <th class="text-center" style="width: 1%;">Total Vistas</th>
                            <?php if ($nivel_user <= 2 ||  $nivel_user == 13) : ?>
                                <th class="text-center" style="width: 1%;">Acciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_vistas as $a_vistas) : ?>
                            <tr>
                                <td class="text-center"><?php echo remove_junk(ucwords($a_vistas['ejercicio'])) ?></td>
                                <?php if($a_vistas['mes'] == 1):?><td class="text-center">Enero</td><?php endif;?>
                                <?php if($a_vistas['mes'] == 2):?><td class="text-center">Febrero</td><?php endif;?>
                                <?php if($a_vistas['mes'] == 3):?><td class="text-center">Marzo</td><?php endif;?>
                                <?php if($a_vistas['mes'] == 4):?><td class="text-center">Abril</td><?php endif;?>
                                <?php if($a_vistas['mes'] == 5):?><td class="text-center">Mayo</td><?php endif;?>
                                <?php if($a_vistas['mes'] == 6):?><td class="text-center">Junio</td><?php endif;?>
                                <?php if($a_vistas['mes'] == 7):?><td class="text-center">Julio</td><?php endif;?>
                                <?php if($a_vistas['mes'] == 8):?><td class="text-center">Agosto</td><?php endif;?>
                                <?php if($a_vistas['mes'] == 9):?><td class="text-center">Septiembre</td><?php endif;?>
                                <?php if($a_vistas['mes'] == 10):?><td class="text-center">Octubre</td><?php endif;?>
                                <?php if($a_vistas['mes'] == 11):?><td class="text-center">Noviembre</td><?php endif;?>
                                <?php if($a_vistas['mes'] == 12):?><td class="text-center">Diciembre</td><?php endif;?>
                                <td class="text-center"><?php echo remove_junk(ucwords($a_vistas['desktop'])) ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords($a_vistas['movil'])) ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords($a_vistas['tablet'])) ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords($a_vistas['vistas_a_pag'])) ?></td>
                                <td class="text-center"><?php echo remove_junk(ucwords($a_vistas['total_vistas'])) ?></td>
                                <?php if ($nivel_user <= 2 ||  $nivel_user == 13) : ?>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="edit_visita_web.php?id=<?php echo (int)$a_vistas['id_visitas']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                <i class="glyphicon glyphicon-pencil"></i>
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