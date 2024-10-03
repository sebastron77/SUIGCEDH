<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Actividades de Desaparecidos';
require_once('includes/load.php');

$all_actividades= find_all_actividadesUD('actividades_ud');
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
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);
}


$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT
	folio, b.descripcion as tipo_actividades_ud, fecha_actividad,
    nombre_solicitante, no_expediente,motivo_solicitud,no_atendidos,
    quien_atendio,acciones,observaciones,motivo_colaboracion,    
  	
    CONCAT(c.nombre,' ',c.paterno,' ',c.materno) as nombre_victima_atendida,
  	cg1.descripcion as genero_victima_atendida,
  	c.edad as edad_victima_atendida,
    cc1.descripcion as comunidad_victima_atendida,
    cn1.descripcion as nacionalidad_victima_atendida,
    ce1.descripcion as entidad_victima_atendida,
    cm1.descripcion as municipio_victima_atendida,
    ces1.descripcion as escolaridad_victima_atendida,
    co1.descripcion as ocupacion_victima_atendida,
    c.leer_escribir as leer_escribir_victima_atendida,
    cd1.descripcion as discapacidad_victima_atendida,
    cgv1.descripcion as grupo_vulnerable_victima_atendida,
             	
    CONCAT(d.nombre,' ',d.paterno,' ',d.materno) as nombre_persona_desaparecida, 
    cg2.descripcion as genero_persona_desaparecida,
    d.edad as edad_persona_desaparecida,
    cc2.descripcion as comuidad_persona_desaparecida,
    cn2.descripcion as nacionalidad_persona_desaparecida,
    ce2.descripcion as entidad_persona_desaparecida,
    cm2.descripcion as municipio_persona_desaparecida,
    ces2.descripcion as escolaridad_persona_desaparecida,
    co2.descripcion as ocupacion_persona_desaparecida,
    d.leer_escribir as leer_escribir_persona_desaparecida,    
    cd2.descripcion as discapacidad_persona_desaparecida,
    cgv2.descripcion as grupo_vulnerable_persona_desaparecida,
    d.fecha_desaparicion,
    ce3.descripcion as entidad_desaparicion,
    cm3.descripcion as municipio_desaparicion,
    localidad_desaparicion,    
    a.fecha_creacion,
    CONCAT(du.nombre,' ',du.apellidos) as user_creador
    
FROM  actividades_ud a
LEFT JOIN cat_tipo_actividades_ud b USING(id_cat_tipo_actividades_ud)
LEFT JOIN cat_victima_atendida c USING(id_cat_victima_atendida)
LEFT JOIN cat_persona_desaparecida d USING(id_cat_persona_desaparecida)
LEFT JOIN cat_entidad_fed e ON(e.id_cat_ent_fed = a.id_cat_ent_fed_colaboracion)
LEFT JOIN cat_genero cg1 ON(cg1.id_cat_gen = c.id_cat_gen)
LEFT JOIN cat_genero cg2 ON(cg2.id_cat_gen = d.id_cat_gen)
LEFT JOIN cat_comunidades cc1 ON(cc1.id_cat_comun = c.id_cat_comun)
LEFT JOIN cat_comunidades cc2 ON(cc2.id_cat_comun = d.id_cat_comun)
LEFT JOIN cat_nacionalidades cn1 ON(cn1.id_cat_nacionalidad = c.id_cat_nacionalidad)
LEFT JOIN cat_nacionalidades cn2 ON(cn2.id_cat_nacionalidad = d.id_cat_nacionalidad)
LEFT JOIN cat_entidad_fed ce1 ON(ce1.id_cat_ent_fed = c.id_cat_ent_fed)
LEFT JOIN cat_entidad_fed ce2 ON(ce2.id_cat_ent_fed = d.id_cat_ent_fed)
LEFT JOIN cat_municipios cm1 ON(cm1.id_cat_mun = c.id_cat_mun)
LEFT JOIN cat_municipios cm2 ON(cm2.id_cat_mun = d.id_cat_mun)
LEFT JOIN cat_escolaridad ces1 ON(ces1.id_cat_escolaridad = c.id_cat_escolaridad)
LEFT JOIN cat_escolaridad ces2 ON(ces2.id_cat_escolaridad = d.id_cat_escolaridad)
LEFT JOIN cat_ocupaciones co1 ON(co1.id_cat_ocup = c.id_cat_ocup)
LEFT JOIN cat_ocupaciones co2 ON(co2.id_cat_ocup = d.id_cat_ocup)
LEFT JOIN cat_discapacidades cd1 ON(cd1.id_cat_disc = c.id_cat_disc)
LEFT JOIN cat_discapacidades cd2 ON(cd2.id_cat_disc = d.id_cat_disc)
LEFT JOIN cat_grupos_vuln cgv1 ON(cgv1.id_cat_grupo_vuln = c.id_cat_grupo_vuln)
LEFT JOIN cat_grupos_vuln cgv2 ON(cgv2.id_cat_grupo_vuln = d.id_cat_grupo_vuln)
LEFT JOIN cat_entidad_fed ce3 ON(ce3.id_cat_ent_fed = d.id_cat_ent_fed_desaparicion)
LEFT JOIN cat_municipios cm3 ON(cm3.id_cat_mun = d.id_cat_mun_desaparicion)
LEFT JOIN users u ON(u.id_user= a.user_creador)
LEFT JOIN detalles_usuario du ON(du.id_det_usuario= u.id_detalle_user)";
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
        header("Content-Disposition: attachment; filename=actividades_desaparecidos.xls");
        $filename = "actividades_desaparecidos.xls";
        $mostrar_columnas = false;

        foreach ($dates as $datos) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($datos)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($datos)) . "\n");
        }
		if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" descargó la lista de  '.$page_title, 6);
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
<a href="solicitudes_desaparecidos.php" class="btn btn-success">Regresar</a><br><br>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Actividades de Desaparecidos</span>
                </strong>
                <?php if (($nivel_user <= 2) || ($nivel_user == 12) ) : ?>
                    <a href="add_actividad_ud.php" class="btn btn-info pull-right btn-md"> Agregar Actividad</a>
                <?php endif ?>
				
				 <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <button style="float: right; margin-top: -20px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>
            </div>
        </div>
		
        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr style="height: 10px;">
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 10%;">Folio</th>
                            <th class="text-center" >Tipo Actividad</th>
                            <th class="text-center" >Fecha Actividad</th>
                            <th class="text-center" >Solicitante</th>
                            <th class="text-center" >Nombre Victima Indirecta</th>
                            <th class="text-center" >Nombre Persona Directa</th>
                            <th class="text-center" >¿Quién Atendió?</th>
                            <?php if (($nivel_user <= 2) || ($nivel_user == 7) || ($nivel_user == 12) ) : ?>
                                <th class="text-center" style="width: 20%;">Acciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_actividades as $adetalle) : ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td><?php echo remove_junk(($adetalle['folio'])) ?></td>
                                <td class="text-center"><?php echo remove_junk(($adetalle['tipo_actividades_ud'])) ?></td>
                                <td class="text-center"><?php echo date("d-m-Y", strtotime(remove_junk(($adetalle['fecha_actividad'])))) ?></td>
                                <td class="text-center"><?php echo remove_junk($adetalle['nombre_solicitante'])?></td>
                                <td class="text-center"><?php echo remove_junk($adetalle['victima_atendida'])?></td>
                                <td class="text-center"><?php echo remove_junk($adetalle['persona_desaparecida']) ?></td>
                                <td class="text-center"><?php echo remove_junk($adetalle['quien_atendio']) ?></td>
                                
                                <?php if (($nivel_user <= 2) || ($nivel_user == 7) || ($nivel_user == 12) ) : ?>
                                    <td class="text-center">
                                        <div class="btn-group">
											<a href="ver_info_actividad_ud.php?id=<?php echo (int)$adetalle['id_actividades_ud']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información completa">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>&nbsp;
                                            <?php if (($nivel_user <= 2) || ($nivel_user == 12) ) : ?>
                                                <a href="edit_actividad_ud.php?id=<?php echo (int)$adetalle['id_actividades_ud']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                    <i class="glyphicon glyphicon-pencil"></i>
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