<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Capacitaciones';
require_once('includes/load.php');
?>
<?php

page_require_level(53);
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

// Identificamos a que área pertenece el usuario logueado
$date_user = area_usuario2($id_user);
$user_area = $date_user['id_area'];

// Identificamos a que área pertenece 
$area = isset($_GET['a']) ? $_GET['a'] : '0';

$area_informe = find_by_id('area', $area, 'id_area');

if ($area > 0) {
    $all_capacitaciones = find_all_capacitaciones_area($area, $ejercicio);
} else {
    $all_capacitaciones = find_all_capacitaciones($ejercicio);
}
$solicitud = find_by_solicitud($area);

if ($nivel_user == 7 || $nivel_user == 53) {
    insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la lista de  ' . $page_title . ' del Área ' . $solicitud['nombre_area'] . ' del Ejercicio ' . $ejercicio, 5);
}


$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT 
	folio,
     tipo_capacitacion as tipo_divulgacion,
      nombre_capacitacion,
       tipo_evento,
  modalidad,
   quien_solicita,
    fecha,
     hora,
      lugar,
        a.no_asistentes,
  asistentes_otros,
   asistentes_nobinario,
    asistentes_mujeres,
     asistentes_hombres,     
  asistentes_10,
   asistentes_20,
    asistentes_30,
     asistentes_40,
      asistentes_50,      
  asistentes_60,
   asistentes_70,
    asistentes_80,
     capacitador,
	 IFNULL(nombre_grupo,'') as nombre_grupo,
			IFNULL(e.no_asistentes,'') as no_asistentes_grupo,
	 b2.nombre_area as nombre_area_creacion,
     CONCAT(d.`nombre`,' ',d.`apellidos`) as usuario_creador,
fecha_creacion
FROM capacitaciones a
LEFT JOIN `area` b ON(a.`id_area`=b.`id_area`)
LEFT JOIN `users` c ON(a.`user_creador`= c.`id_user`)
LEFT JOIN `detalles_usuario` d ON(c.`id_detalle_user`= d.`id_det_usuario`) 
LEFT JOIN `area` b2 ON(a.`area_creacion`=b2.`id_area`) 
LEFT JOIN (
			SELECT
				id_capacitacion,
				GROUP_CONCAT(descripcion ) as nombre_grupo,
				GROUP_CONCAT(no_asistentes) as no_asistentes
			FROM rel_capacitacion_grupos z
			LEFT JOIN cat_grupos_vuln y USING(id_cat_grupo_vuln)
		GROUP BY id_capacitacion    
		) e USING(id_capacitacion)
WHERE YEAR(fecha)= '{$ejercicio}' ";
if ($area > 0) {
    $sql .= " AND area_creacion = " . $area;
}
//echo $sql;
$resultado = mysqli_query($conexion, $sql) or die;
$capacitaciones = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $capacitaciones[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($capacitaciones)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=capacitaciones.xls");
        $filename = "capacitaciones.xls";
        $mostrar_columnas = false;

        foreach ($capacitaciones as $resolucion) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($resolucion)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($resolucion)) . "\n");
        }
        if ($nivel_user == 7 || $nivel_user == 53) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" descargó la lista de  ' . $page_title . ' del Área ' . $solicitud['nombre_area'] . ' del Ejercicio ' . $ejercicio, 6);
        }
    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}

?>
<script type="text/javascript">
    function changueAnio(anio, area) {
        window.open("capacitaciones.php?anio=" + anio + "&a=" + area, "_self");

    }
</script>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<a href="<?php echo $solicitud['nombre_solicitud']; ?>" class="btn btn-success">Regresar</a><br><br>

<div class="row">

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="col-md-8">
                        <strong>
                            <span class="glyphicon glyphicon-th"></span>
                            <span>Lista de Capacitaciones de <?php echo $ejercicio ?> de <?php echo $solicitud['nombre_area']; ?> </span>
                        </strong>
                    </div>
                    <div class="col-md-1" style="margin-left: 20px; margin-top: 20px;">
                        <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>?a=<?php echo $area ?>&anio=<?php echo $ejercicio ?>" method="post">
                            <button style="float: right; margin-top: -20px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar Excel</button>
                        </form>
                    </div>
                    <div class="col-md-1" style="margin-left: 90px;">
                        <?php if (($nivel != 7) && ($nivel != 53)):         ?>
                            <a href="add_capacitacion.php?a=<?php echo $area ?>" style="margin-left: 10px" class="btn btn-info pull-right">Agregar capacitación</a>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-1" style="margin-left: -20px;">
                        <div class="form-group">
                            <select class="form-control" name="ejercicio" onchange="changueAnio(this.value,<?php echo $area ?>)">
                                <option value="">Ejercicio</option>
                                <?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
                                    echo "<option value='" . $i . "'>" . $i . "</option>";
                                } ?>
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
                    <tr style="height: 10px;">
                        <th style="width: 11%;">Folio</th>
                        <th style="width: 15%;">Tipo Divulgación</th>
                        <th style="width: 15%;">Capacitación</th>
                        <th style="width: 7%;">Fecha</th>
                        <th style="width: 3%;">Hora</th>
                        <th style="width: 5%;">Lugar</th>
                        <th style="width: 1%;">Duración(Hrs)</th>
                        <th style="width: 1%;">Asistentes</th>
                        <th style="width: 1%;">Modalidad</th>
                        <!--<th style="width: 1%;">Curriculum</th>
                         <th style="width: 3%;">Constancia</th> -->

                        <th style="width: 1%;" class="text-center">Acciones</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_capacitaciones as $a_capacitacion) :
                        $asistentes = (int)$a_capacitacion['asistentes_otros'] + (int)$a_capacitacion['asistentes_nobinario'] + (int)$a_capacitacion['asistentes_mujeres'] + (int)$a_capacitacion['asistentes_hombres'];
                    ?>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['tipo_capacitacion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['nombre_capacitacion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['fecha'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['hora'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['lugar'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['duracion'])) ?></td>
                            <td class="text-center"><?php echo  $asistentes ?></td>
                            <td><?php echo remove_junk((ucwords($a_capacitacion['modalidad']))) ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="ver_info_capacitacion.php?id=<?php echo (int)$a_capacitacion['id_capacitacion']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                        <i class="glyphicon glyphicon-eye-open"></i>
                                    </a>
                                    <?php if (($nivel < 7) || ($nivel > 7)) : ?>
                                        <a href="edit_capacitacion.php?id=<?php echo (int)$a_capacitacion['id_capacitacion']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
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