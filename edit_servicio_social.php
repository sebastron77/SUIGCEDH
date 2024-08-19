<?php
$page_title = 'Editar Servicio Social';
require_once('includes/load.php');

page_require_level(3);
$areas = find_all_area_orden('area');
$tipo_int = find_all('cat_tipo_integrante');

$e_detalle = find_by_id_ss((int)$_GET['id']);
if (!$e_detalle) {
    $session->msg("d", "id de información no encontrado.");
    redirect('servicio_social.php');
}
$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user <= 3) {
    page_require_level(3);
}
if ($nivel_user == 5) {
    redirect('home.php');
}
if ($nivel_user == 7) {
    redirect('home.php');
}
if ($nivel_user == 21) {
    redirect('home.php');
}
if ($nivel_user == 19) {
    redirect('home.php');
}
if ($nivel_user > 3 && $nivel_user < 5) :
    redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7) :
    redirect('home.php');
endif;
if ($nivel_user > 19 && $nivel_user < 21) :
    redirect('home.php');
endif;

$generos = find_all('cat_genero');
$nacionalidades = find_all('cat_nacionalidades');
$municipios = find_all('cat_municipios');
$escolaridades = find_all('cat_escolaridad');
$grupos_vuln = find_all('cat_grupos_vuln');
$discapacidades = find_all('cat_discapacidades');
$comunidades = find_all('cat_comunidades');
$entidades = find_all('cat_entidad_fed');
$carreras = find_all_order('cat_carreras', 'descripcion');
$inticadores_pat = find_all_pat(10);
?>

<?php
if (isset($_POST['update'])) {
    if (empty($errors)) {
        $id = (int)$e_detalle['id_servicio_social'];
        $modalidad   = remove_junk($db->escape($_POST['modalidad']));
        $nombre_prestador   = remove_junk($db->escape($_POST['nombre_prestador']));
        $paterno_prestador   = remove_junk($db->escape($_POST['paterno_prestador']));
        $materno_prestador   = remove_junk($db->escape($_POST['materno_prestador']));
        $genero   = remove_junk($db->escape($_POST['genero']));
        $edad   = remove_junk(($db->escape($_POST['edad'])));
        $nacionalidad   = remove_junk(($db->escape($_POST['nacionalidad'])));
        $entidad   = remove_junk($db->escape($_POST['entidad']));
        $municipio   = remove_junk($db->escape($_POST['municipio']));
        $escolaridad   = remove_junk($db->escape($_POST['escolaridad']));
        $discapacidad   = remove_junk($db->escape($_POST['discapacidad']));
        $grupo_vulnerable   = remove_junk($db->escape($_POST['grupo_vulnerable']));
        $comunidad   = remove_junk($db->escape($_POST['comunidad']));
        $carrera   = remove_junk($db->escape($_POST['carrera']));
        $institucion   = remove_junk($db->escape($_POST['institucion']));
        $fecha_inicio   = '2024-04-01';//$db->escape($_POST['fecha_inicio']);
        $fecha_termino   = $db->escape($_POST['fecha_termino']);
        $total_horas   = remove_junk($db->escape($_POST['total_horas']));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));

        $name1 = $_FILES['carta_presentacion']['name'];
        $size1 = $_FILES['carta_presentacion']['size'];
        $type1 = $_FILES['carta_presentacion']['type'];
        $temp1 = $_FILES['carta_presentacion']['tmp_name'];

        $name2 = $_FILES['oficio_aceptacion']['name'];
        $size2 = $_FILES['oficio_aceptacion']['size'];
        $type2 = $_FILES['oficio_aceptacion']['type'];
        $temp2 = $_FILES['oficio_aceptacion']['tmp_name'];

        $name3 = $_FILES['informe_bim1']['name'];
        $size3 = $_FILES['informe_bim1']['size'];
        $type3 = $_FILES['informe_bim1']['type'];
        $temp3 = $_FILES['informe_bim1']['tmp_name'];

        $name4 = $_FILES['informe_bim2']['name'];
        $size4 = $_FILES['informe_bim2']['size'];
        $type4 = $_FILES['informe_bim2']['type'];
        $temp4 = $_FILES['informe_bim2']['tmp_name'];

        $name5 = $_FILES['informe_bim3']['name'];
        $size5 = $_FILES['informe_bim3']['size'];
        $type5 = $_FILES['informe_bim3']['type'];
        $temp5 = $_FILES['informe_bim3']['tmp_name'];

        $name6 = $_FILES['informe_global']['name'];
        $size6 = $_FILES['informe_global']['size'];
        $type6 = $_FILES['informe_global']['type'];
        $temp6 = $_FILES['informe_global']['tmp_name'];

        $name7 = $_FILES['evaluacion_uReceptora']['name'];
        $size7 = $_FILES['evaluacion_uReceptora']['size'];
        $type7 = $_FILES['evaluacion_uReceptora']['type'];
        $temp7 = $_FILES['evaluacion_uReceptora']['tmp_name'];

        $name8 = $_FILES['oficio_terminacion']['name'];
        $size8 = $_FILES['oficio_terminacion']['size'];
        $type8 = $_FILES['oficio_terminacion']['type'];
        $temp8 = $_FILES['oficio_terminacion']['tmp_name'];
		
		$name9 = $_FILES['oficio_asignacion_area']['name'];
        $size9 = $_FILES['oficio_asignacion_area']['size'];
        $type9 = $_FILES['oficio_asignacion_area']['type'];
        $temp9 = $_FILES['oficio_asignacion_area']['tmp_name'];
		
		$name10 = $_FILES['oficio_obligaciones_prestador']['name'];
        $size10 = $_FILES['oficio_obligaciones_prestador']['size'];
        $type10 = $_FILES['oficio_obligaciones_prestador']['type'];
        $temp10 = $_FILES['oficio_obligaciones_prestador']['tmp_name'];



        $sql = "UPDATE servicio_social SET modalidad='{$modalidad}',id_indicadores_pat={$id_indicadores_pat} ";
        if ($nombre_prestador !== '') {
            $sql .= ",nombre_prestador='{$nombre_prestador}'";
        }
        if ($paterno_prestador !== '') {
            $sql .= ",paterno_prestador='{$paterno_prestador}'";
        }
        if ($materno_prestador !== '') {
            $sql .= ", materno_prestador='{$materno_prestador}'";
        }
        if ($genero !== '') {
            $sql .= ", genero='{$genero}'";
        }
        if ($edad !== '') {
            $sql .= ", edad='{$edad}'";
        }
        if ($nacionalidad !== '') {
            $sql .= ", nacionalidad='{$nacionalidad}'";
        }
        if ($entidad !== '') {
            $sql .= ", entidad='{$entidad}'";
        }
        if ($municipio !== '') {
            $sql .= ", municipio='{$municipio}'";
        }
        if ($escolaridad !== '') {
            $sql .= ", escolaridad='{$escolaridad}'";
        }        
        if ($discapacidad !== '') {
            $sql .= ", discapacidad='{$discapacidad}'";
        }        
        if ($grupo_vulnerable !== '') {
            $sql .= ", grupo_vulnerable='{$grupo_vulnerable}'";
        }
        if ($comunidad !== '') {
            $sql .= ", comunidad='{$comunidad}'";
        }
        if ($carrera !== '') {
            $sql .= ", carrera={$carrera}";
        }
        if ($institucion !== '') {
            $sql .= ", institucion='{$institucion}'";
        }
        if ($fecha_inicio !== '') {
            $sql .= ", fecha_inicio='{$fecha_inicio}'";
        }
        if ($fecha_termino !== '') {
            $sql .= ", fecha_termino='{$fecha_termino}'";
        }
        if ($total_horas !== '') {
            $sql .= ", total_horas='{$total_horas}'";
        }
        if ($observaciones !== '') {
            $sql .= ", observaciones='{$observaciones}'";
        }
        if ($name1 !== '') {
            $sql .= ", carta_presentacion='{$name1}'";
        }
        if ($name2 !== '') {
            $sql .= ", oficio_aceptacion='{$name2}'";
        }
        if ($name3 !== '') {
            $sql .= ", informe_bim1='{$name3}'";
        }
        if ($name4 !== '') {
            $sql .= ", informe_bim2='{$name4}'";
        }
        if ($name5 !== '') {
            $sql .= ", informe_bim3='{$name5}'";
        }
        if ($name6 !== '') {
            $sql .= ", informe_global='{$name6}'";
        }
        if ($name7 !== '') {
            $sql .= ", evaluacion_uReceptora='{$name7}'";
        }
        if ($name8 !== '') {
            $sql .= ", oficio_terminacion='{$name8}'";
        }
		if ($name9 !== '') {
            $sql .= ", oficio_asignacion_area='{$name9}'";
        }if ($name10 !== '') {
            $sql .= ", oficio_obligaciones_prestador='{$name10}'";
        }
        $sql .= " WHERE id_servicio_social='{$db->escape($id)}'";

        $carpeta = 'uploads/servicioSocial/' . $e_detalle['id_servicio_social'];

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $move1 =  move_uploaded_file($temp1, $carpeta . "/" . $name1);
        $move2 =  move_uploaded_file($temp2, $carpeta . "/" . $name2);
        $move3 =  move_uploaded_file($temp3, $carpeta . "/" . $name3);
        $move4 =  move_uploaded_file($temp4, $carpeta . "/" . $name4);
        $move5 =  move_uploaded_file($temp5, $carpeta . "/" . $name5);
        $move6 =  move_uploaded_file($temp6, $carpeta . "/" . $name6);
        $move7 =  move_uploaded_file($temp7, $carpeta . "/" . $name7);
        $move8 =  move_uploaded_file($temp8, $carpeta . "/" . $name8);
		$move9 =  move_uploaded_file($temp9, $carpeta . "/" . $name9);
        $move10 =  move_uploaded_file($temp10, $carpeta . "/" . $name10);

        $result = $db->query($sql);
		echo $sql;
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Expediente Actualizado");
            insertAccion($user['id_user'], '"' . $user['username'] . '" actualizó expediente de servicio social de: ' . $nombre . ' ' . $apellidos . '.', 2);
            redirect('edit_servicio_social.php?id=' . (int)$e_detalle['id_servicio_social'], false);
        } else {
            $session->msg('d', ' Lo sentimos, no se pudo actualizar el expediente debido a que no se encontraron cambions en la información.' );
            redirect('edit_servicio_social.php?id=' . (int)$e_detalle['id_servicio_social'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_servicio_social.php?id=' . (int)$e_detalle['id_servicio_social'], false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">

    <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    Expediente general de Servicio Social: <?php echo (ucwords($e_detalle['nombre_prestador'] . " " . $e_detalle['paterno_prestador'] . " " . $e_detalle['materno_prestador'])); ?>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="edit_servicio_social.php?id=<?php echo (int)$e_detalle['id_servicio_social']; ?>" class="clearfix" enctype="multipart/form-data">
                    <div class="row">
					<div class="panel-heading">
			<strong>
				<span class="glyphicon glyphicon-user"></span>
				<span>Datos Generales</span>
			</strong>
		</div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="modalidad">Modalidad</label>
                                <select class="form-control" name="modalidad">
                                    <option <?php if ($e_detalle['modalidad'] == 'Prácticas Profesionales') echo 'selected="selected"'; ?> value="Prácticas Profesionales">Prácticas Profesionales</option>
                                    <option <?php if ($e_detalle['modalidad'] == 'Servicio Social') echo 'selected="selected"'; ?> value="Servicio Social">Servicio Social</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nombre_prestador" class="control-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre_prestador" value="<?php echo ($e_detalle['nombre_prestador']); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="paterno_prestador" class="control-label">Apellido Paterno</label>
                                <input type="text" class="form-control" name="paterno_prestador" value="<?php echo ($e_detalle['paterno_prestador']); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="materno_prestador" class="control-label">Apellido Materno</label>
                                <input type="text" class="form-control" name="materno_prestador" value="<?php echo ($e_detalle['materno_prestador']); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="genero">Género</label>
                                <select class="form-control" name="genero">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($generos as $genero) : ?>
                                        <option <?php if ($genero['id_cat_gen'] === $e_detalle['id_cat_gen'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="edad" class="control-label">Edad</label>
                                <input type="text" class="form-control" name="edad" value="<?php echo ($e_detalle['edad']); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="nacionalidad">Nacionalidad</label>
                            <select class="form-control" name="nacionalidad">
                                <?php foreach ($nacionalidades as $nacionalidad1) : ?>
                                    <option <?php if ($nacionalidad1['id_cat_nacionalidad'] === $e_detalle['id_cat_nacionalidad'])
                                                echo 'selected="selected"'; ?> value="<?php echo $nacionalidad1['id_cat_nacionalidad']; ?>"><?php echo ucwords($nacionalidad1['descripcion']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="entidad" class="control-label">Entidad</label>
                                <select class="form-control" name="entidad">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($entidades as $entidad) : ?>
                                        <option <?php if ($entidad['id_cat_ent_fed'] === $e_detalle['id_cat_ent_fed'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $entidad['id_cat_ent_fed']; ?>"><?php echo ucwords($entidad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="municipio" class="control-label">Municipio</label>
                                <select class="form-control" name="municipio">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($municipios as $municipio) : ?>
                                        <option <?php if ($municipio['id_cat_mun'] === $e_detalle['id_cat_mun'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="escolaridad" class="control-label">Escolaridad</label>
                                <select class="form-control" name="escolaridad">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($escolaridades as $escolaridad1) : ?>
                                        <option <?php if ($escolaridad1['id_cat_escolaridad'] === $e_detalle['id_cat_escolaridad'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $escolaridad1['id_cat_escolaridad']; ?>"><?php echo ucwords($escolaridad1['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="discapacidad" class="control-label">Discapacidad</label>
                                <select class="form-control" name="discapacidad">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($discapacidades as $discapacidad) : ?>
                                        <option <?php if ($discapacidad['id_cat_disc'] === $e_detalle['id_cat_disc'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $discapacidad['id_cat_disc']; ?>">
                                            <?php echo ucwords($discapacidad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="grupo_vulnerable" class="control-label">Grupo Vulnerable</label>
                                <select class="form-control" name="grupo_vulnerable">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($grupos_vuln as $grupo) : ?>
                                        <option <?php if ($grupo['id_cat_grupo_vuln'] === $e_detalle['id_cat_grupo_vuln'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $grupo['id_cat_grupo_vuln']; ?>">
                                            <?php echo ucwords($grupo['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="comunidad" class="control-label">Comunidades</label>
                                <select class="form-control" name="comunidad">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($comunidades as $comunidad) : ?>
                                        <option <?php if ($comunidad['id_cat_comun'] === $e_detalle['id_cat_comun'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $comunidad['id_cat_comun']; ?>">
                                            <?php echo ucwords($comunidad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
						<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" >
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  <?php if ($e_detalle['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    </div>
					<div class="panel-heading">
			<strong>
				<span class="glyphicon glyphicon-briefcase"></span>
				<span>Datos Institución Educativa</span>
			</strong>
		</div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="carrera" class="control-label">Carrera</label>
                                <select class="form-control" name="carrera">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($carreras as $carrera1) : ?>
                                        <option <?php if ($carrera1['id_cat_carrera'] === $e_detalle['id_cat_carrera'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $carrera1['id_cat_carrera']; ?>">
                                            <?php echo ucwords($carrera1['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="institucion" class="control-label">Institución</label>
                                <input type="text" class="form-control" name="institucion" value="<?php echo ($e_detalle['institucion']); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="fecha_inicio">Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio" value="<?php echo $e_detalle['fecha_inicio']; ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="fecha_termino">Fecha Término</label>
                                <input type="date" class="form-control" name="fecha_termino" value="<?php echo $e_detalle['fecha_termino']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="total_horas">Total Hrs.</label>
                                <input type="number" class="form-control" name="total_horas" value="<?php echo ($e_detalle['total_horas']); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="observaciones">Observaciones</label>
                                <textarea class="form-control" name="observaciones" id="observaciones" cols="30" rows="3"><?php echo ($e_detalle['observaciones']); ?></textarea>
                            </div>
                        </div>
                        </div>
						<div class="panel-heading">
			<strong>
				<span class="glyphicon glyphicon-folder-open"></span>
				<span>Documentación</span>
			</strong>
		</div>
		 <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="carta_presentacion">Carta Presentación</label>
                                <input type="file" accept="application/pdf" class="form-control" name="carta_presentacion" id="carta_presentacion" value="<?php echo remove_junk($e_detalle['carta_presentacion']); ?>">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_detalle['carta_presentacion']); ?></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="oficio_aceptacion">Oficio Aceptacion</label>
                                <input type="file" accept="application/pdf" class="form-control" name="oficio_aceptacion" id="oficio_aceptacion" value="<?php echo remove_junk($e_detalle['oficio_aceptacion']); ?>">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_detalle['oficio_aceptacion']); ?></label>
                            </div>
                        </div>
						<div class="col-md-3">
                        <div class="form-group">
                            <label for="oficio_asignacion_area">Oficio Asignación a Área</label>
                            <input type="file" accept="application/pdf" class="form-control" name="oficio_asignacion_area" id="oficio_asignacion_area">
							<label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_detalle['oficio_asignacion_area']); ?></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="oficio_obligaciones_prestador">Obligaciones de la persona prestadora</label>
                            <input type="file" accept="application/pdf" class="form-control" name="oficio_obligaciones_prestador" id="oficio_obligaciones_prestador">
							<label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_detalle['oficio_obligaciones_prestador']); ?></label>
                        </div>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="informe_bim1">Informe Bimestral 1</label>
                                <input type="file" accept="application/pdf" class="form-control" name="informe_bim1" id="informe_bim1" value="<?php echo remove_junk($e_detalle['carta_presentacion']); ?>">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_detalle['informe_bim1']); ?></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="informe_bim2">Informe Bimestral 2</label>
                                <input type="file" accept="application/pdf" class="form-control" name="informe_bim2" id="informe_bim2" value="<?php echo remove_junk($e_detalle['carta_presentacion']); ?>">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_detalle['informe_bim2']); ?></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="informe_bim3">Informe Bimestral 3</label>
                                <input type="file" accept="application/pdf" class="form-control" name="informe_bim3" id="informe_bim3" value="<?php echo remove_junk($e_detalle['carta_presentacion']); ?>">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_detalle['informe_bim3']); ?></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="informe_global">Informe Global</label>
                                <input type="file" accept="application/pdf" class="form-control" name="informe_global" id="informe_global">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_detalle['informe_global']); ?></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="evaluacion_uReceptora">Eval. Unidad Receptora</label>
                                <input type="file" accept="application/pdf" class="form-control" name="evaluacion_uReceptora" id="evaluacion_uReceptora">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_detalle['evaluacion_uReceptora']); ?></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="oficio_terminacion">Oficio de Terminación</label>
                                <input type="file" accept="application/pdf" class="form-control" name="oficio_terminacion" id="oficio_terminacion">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_detalle['oficio_terminacion']); ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <a href="servicio_social.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="update" class="btn btn-info">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>