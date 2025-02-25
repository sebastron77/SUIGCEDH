<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Servicio Social';
require_once('includes/load.php');

// page_require_level(2);
$all_gestiones = find_all_ss();
$user = current_user();
$nivel = $user['user_level'];

$id_usuario = $user['id_user'];
$busca_area = area_usuario($id_usuario);
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 3) {
    page_require_level_exacto(3);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la lista de  '.$page_title, 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la lista de  '.$page_title, 5);
    page_require_level_exacto(53);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 53) :
    redirect('home.php');
endif;

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT 
       ss.modalidad,
       ss.nombre_prestador,
       ss.paterno_prestador,
       ss.materno_prestador,
       g.id_cat_gen,
       g.descripcion as genero,
       ss.edad,
       nac.descripcion as nacionalidad,
       ent.descripcion as entidad,
       mun.descripcion as municipio,
       esc.descripcion as escolaridad,
       disc.descripcion as discapacidad,
       gv.descripcion as grupo_vulnerable,
       com.descripcion as comunidad,
       c.descripcion as carrera,
       ss.institucion,
       ss.fecha_inicio,
       ss.fecha_termino,
       ss.total_horas,
       ss.observaciones
FROM servicio_social ss
     LEFT JOIN cat_carreras c ON c.id_cat_carrera = ss.carrera
     LEFT JOIN cat_genero g ON g.id_cat_gen = ss.genero
     LEFT JOIN cat_nacionalidades nac ON nac.id_cat_nacionalidad =
       ss.nacionalidad
     LEFT JOIN cat_entidad_fed ent ON ent.id_cat_ent_fed = ss.entidad
     LEFT JOIN cat_municipios mun ON mun.id_cat_mun = ss.municipio
     LEFT JOIN cat_escolaridad esc ON esc.id_cat_escolaridad = ss.escolaridad
     LEFT JOIN cat_discapacidades disc ON disc.id_cat_disc = ss.discapacidad
     LEFT JOIN cat_grupos_vuln gv ON gv.id_cat_grupo_vuln = ss.grupo_vulnerable
     LEFT JOIN cat_comunidades com ON com.id_cat_comun = ss.comunidad ";
$resultado = mysqli_query($conexion, $sql) or die;
$servicio = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $servicio[] = $rows;
}

mysqli_close($conexion);
if (isset($_POST["export_data"])) {
    if (!empty($servicio)) {
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=servicio_social.xls");
        $filename = "servicio_social.xls";
        $mostrar_columnas = false;

        foreach ($servicio as $datos) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($datos)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($datos)) . "\n");
        }
		if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" descargó  la lista de '.$page_title.' ', 6);    
		}
    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}
?>

<?php include_once('layouts/header.php'); ?>

<a href="solicitudes_ejecutiva.php" class="btn btn-success">Regresar</a><br><br>
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
                    <span>Servicio Social</span>
                </strong>
                <?php if (($nivel_user <= 3)  ) : ?>
                    <a href="add_servicio_social.php" class="btn btn-info pull-right btn-md"> Agregar Servicio Social</a>&nbsp;
                <?php endif ?>
				 <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <button style="float: right; margin-top: -20px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>
            </div>
            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th class="text-center" style="width: 1%;">#</th>
                            <th style="width: 25%;">Nombre Prestador</th>
                            <th class="text-center" style="width: 25%;">Carrera</th>
                            <th class="text-center" style="width: 25%;">Institución</th>
                            <th class="text-center" >Fecha Inicio</th>
                            <th class="text-center" >Fecha Término</th>
                                <th class="text-center" style="width: 1%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_gestiones as $a_gestion) : ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td>
                                    <?php echo remove_junk(ucwords($a_gestion['nombre_prestador'] . " " . $a_gestion['paterno_prestador'] . " " . $a_gestion['materno_prestador'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_gestion['carrera'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_gestion['institucion'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo date("d-m-Y", strtotime(remove_junk(ucwords($a_gestion['fecha_inicio'])))) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo date("d-m-Y", strtotime(remove_junk(ucwords($a_gestion['fecha_termino'])))) ?>
                                </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="ver_info_servicio_social.php?id=<?php echo (int)$a_gestion['id_servicio_social']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información completa">
                                                <i class="glyphicon glyphicon-eye-open"></i>
                                            </a>&nbsp;
                                <?php if (($nivel_user <= 3)  ) : ?>
                                            <a href="edit_servicio_social.php?id=<?php echo (int)$a_gestion['id_servicio_social']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                <i class="glyphicon glyphicon-pencil"></i>
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