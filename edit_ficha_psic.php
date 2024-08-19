<?php
$page_title = 'Ficha Técnica - Área Psicológica';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 4) {
    page_require_level_exacto(4);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 9) {
    page_require_level_exacto(9);
}
if ($nivel_user == 22) {
    page_require_level_exacto(22);
}

if ($nivel_user > 3 && $nivel_user < 4) :
    redirect('home.php');
	
endif;
if ($nivel_user > 4 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 9) :
    redirect('home.php');
endif;
if ($nivel_user > 9 && $nivel_user < 22) :
    redirect('home.php');
endif;
if ($nivel_user > 22) :
    redirect('home.php');
endif;

$busca_area = area_usuario($id_user);
$areas = find_all_area_orden('area');
$funciones = find_all_funcion_P();
$ocupaciones = find_all_order('cat_ocupaciones', 'descripcion');
$escolaridades = find_all('cat_escolaridad');
$visitadurias = find_all_visitadurias();
$autoridades = find_all_aut_res();
$generos = find_all('cat_genero');
$grupos = find_all_order('cat_grupos_vuln', 'id_cat_grupo_vuln');
$derechos_vuln = find_all_order('cat_der_vuln', 'descripcion');
$folios = find_all('folios');
$inticadores_pat_ST = find_all_pat_area(16,'fichas');
$inticadores_pat_AM = find_all_pat_area(17,'fichas');

?>
<?php
$e_ficha = find_ficha_completa((int)$_GET['id']);
if (!$e_ficha) {
    $session->msg("d", "id de ficha no encontrado.");
    redirect('fichas_psic.php');
}
?>
<?php
if (isset($_POST['update'])) {
    if (empty($errors)) {
        $id = (int)$e_ficha['id_ficha'];
        $fecha_intervencion   = remove_junk($db->escape($_POST['fecha_intervencion']));
        $funcion   = remove_junk($db->escape($_POST['funcion']));       
        $area_solicitante   = remove_junk($db->escape($_POST['area_solicitante']));
        $resultado2   = remove_junk($db->escape($_POST['resultado']));
        $documento_emitido   = remove_junk($db->escape($_POST['documento_emitido']));
        $clave_documento   = remove_junk($db->escape($_POST['clave_documento']));
        $nombre_especialista   = remove_junk($db->escape($_POST['nombre_especialista']));
        $protocolo_estambul   = remove_junk($db->escape($_POST['protocolo_estambul']));
		$id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));
        $adjunto   = remove_junk($db->escape($_POST['ficha_adjunto']));

        $folio_editar = $e_ficha['folio'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/fichastecnicas/psic/' . $resultado;

        $name = $_FILES['adjunto']['name'];
        $size = $_FILES['adjunto']['size'];
        $type = $_FILES['adjunto']['type'];
        $temp = $_FILES['adjunto']['tmp_name'];

        if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else {
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }

        if ($name != '') {
            $sql = "UPDATE fichas SET id_cat_funcion='{$funcion}',
            id_area_solicitante='{$area_solicitante}',fecha_intervencion='{$fecha_intervencion}',resultado='{$resultado2}', 
			documento_emitido='{$documento_emitido}', ficha_adjunto='{$name}', protocolo_estambul='{$protocolo_estambul}',
			nombre_especialista='{$nombre_especialista}', clave_documento='{$clave_documento}',id_indicadores_pat='{$id_indicadores_pat}'  WHERE id_ficha='{$db->escape($id)}'";
        }
        if ($name == '') {
            $sql = "UPDATE fichas SET id_cat_funcion='{$funcion}',
            id_area_solicitante='{$area_solicitante}',fecha_intervencion='{$fecha_intervencion}',resultado='{$resultado2}',
			documento_emitido='{$documento_emitido}',protocolo_estambul='{$protocolo_estambul}',nombre_especialista='{$nombre_especialista}',clave_documento='{$clave_documento}',id_indicadores_pat='{$id_indicadores_pat}' WHERE id_ficha='{$db->escape($id)}'";
        }
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Información Actualizada ");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó Ficha Psic, Folio: ' . $folio . '.', 2);
            redirect('fichas_psic.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos.');
            redirect('fichas_psic.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_ficha_psic.php?id=' . (int)$e_ficha['id_ficha'], false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar Ficha Técnica - Área Psicológica</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_ficha_psic.php?id=<?php echo $e_ficha['id_ficha']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="num_queja">No. Expediente</label>
                            <input type="text" class="form-control" value="<?php echo $e_ficha['exp_ficha']; ?>" readonly>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_paciente">Paciente</label>
                            <input type="text" class="form-control" value="<?php echo ($e_ficha['nombre'] . " " . $e_ficha['paterno'] . " " . $e_ficha['materno']); ?>" readonly>
                          
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_intervencion">Fecha de Intervención</label>
                            <input type="date" class="form-control" name="fecha_intervencion" value="<?php echo $e_ficha['fecha_intervencion'] ?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="funcion">Función</label>
                            <select class="form-control" name="funcion">
                                <option value="">Escoge una opción</option>|
                                <?php foreach ($funciones as $funcion) : ?>
                                    <option <?php if ($funcion['id_cat_funcion'] == $e_ficha['id_cat_funcion'])
                                                echo 'selected="selected"'; ?> value="<?php echo $funcion['id_cat_funcion']; ?>"><?php echo ucwords($funcion['descripcion']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="area_solicitante">Área Solicitante</label>
                            <select class="form-control" name="area_solicitante">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($areas as $area) : ?>
                                    <option <?php if ($area['id_area'] == $e_ficha['id_area_solicitante'])
                                                echo 'selected="selected"'; ?> value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
					 <div class="col-md-2">
                        <div class="form-group">
                            <label for="resultado">Resultado</label>
                            <select class="form-control" name="resultado">
                                <option value="">Escoge una opción</option>
                                <option <?php if ($e_ficha['resultado'] == 'Positivo') echo 'selected="selected"'; ?> value="Positivo">Positivo</option>
                                <option <?php if ($e_ficha['resultado'] == 'Negativo') echo 'selected="selected"'; ?> value="Negativo">Negativo</option>
                                <option <?php if ($e_ficha['resultado'] == 'No Aplica') echo 'selected="selected"'; ?> value="No Aplica">No Aplica</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="documento_emitido">Documento Emitido</label>
                            <select class="form-control" name="documento_emitido">
                                <option value="">Escoge una opción</option>
                                <option <?php if ($e_ficha['documento_emitido'] == 'Informe Psicológico') echo 'selected="selected"'; ?> value="Informe Psicológico">Informe Psicológico</option>
                                <option <?php if ($e_ficha['documento_emitido'] == 'Dictamen Psicológico') echo 'selected="selected"'; ?> value="Dictamen Psicológico">Dictamen Psicológico</option>
                                <option <?php if ($e_ficha['documento_emitido'] == 'No Aplica') echo 'selected="selected"'; ?> value="No Aplica">No Aplica</option>
                            </select>
                        </div>
                    </div>
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="protocolo_estambul">Protocolo de Estambul</label>
                            <select class="form-control" name="protocolo_estambul">
                                <option value="">Escoge una opción</option>
                                <option <?php if ($e_ficha['protocolo_estambul'] == 'Aplicado') echo 'selected="selected"'; ?> value="Aplicado">Aplicado</option>
                                <option <?php if ($e_ficha['protocolo_estambul'] == 'No Aplicado') echo 'selected="selected"'; ?> value="No Aplicado">No Aplicado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="clave_documento">Clave del documento</label>
                            <input type="text" class="form-control" name="clave_documento" value="<?php echo $e_ficha['clave_documento']; ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_usuario">Especialista que emite</label>
                            <input type="text" class="form-control" name="nombre_especialista" value="<?php echo $e_ficha['nombre_especialista']; ?>">
                        </div>
                    </div>                    
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="adjunto">Adjuntar documento emitido</label>
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto">
                            <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                <?php echo remove_junk($e_ficha['ficha_adjunto']); ?>
                            </label>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" >
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat_ST as $datos) : ?>
                                    <option <?php if ($e_ficha['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?>  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
								<?php foreach ($inticadores_pat_AM as $datos) : ?>
                                    <option <?php if ($e_ficha['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?>  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                        </div>

                </div>
				
				<div class="panel-heading">
					<strong>
						<span class="glyphicon glyphicon-user"></span>
						<span>Datos del Paciente</span>
					</strong>
				</div><hr>
				
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_usuario">Nombre del usuario
                                <div style="margin-left: 10px;" class="popup caja" onclick="myFunction()">
                                    <p style="font-size: 14px;">?</p>
                                    <span class="popuptext" id="myPopup" style="width: 100px">Información No Modificable.</span>
                                </div>
                            </label>
                            <input type="text" class="form-control" value="<?php echo $e_ficha['nombre'] . " " . $e_ficha['paterno'] . " " . $e_ficha['materno'] ?>" readonly>
                            <input type="text" class="form-control" name="nombre_usuario" placeholder="Nombre Completo" value="<?php echo $e_ficha['id_paciente'] ?>" hidden>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="edad">Edad
                                <div style="margin-left: 10px;" class="popup caja" onclick="myFunction2()">
                                    <p style="font-size: 14px;">?</p>
                                    <span class="popuptext" id="myPopup2" style="width: 100px">Información No Modificable.</span>
                                </div>
                            </label>
                            <input type="number" class="form-control" min="1" max="120" name="edad" value="<?php echo $e_ficha['edad'] ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sexo">Género
                                <div style="margin-left: 10px;" class="popup caja" onclick="myFunction3()">
                                    <p style="font-size: 14px;">?</p>
                                    <span class="popuptext" id="myPopup3" style="width: 100px">Información No Modificable.</span>
                                </div>
                            </label>
                            <input type="text" class="form-control" value="<?php echo $e_ficha['genero'] ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ocupacion">Ocupación
                                <div style="margin-left: 10px;" class="popup caja" onclick="myFunction4()">
                                    <p style="font-size: 14px;">?</p>
                                    <span class="popuptext" id="myPopup4" style="width: 100px">Información No Modificable.</span>
                                </div>
                            </label>
                            <input type="text" class="form-control" value="<?php echo $e_ficha['ocupacion'] ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="escolaridad">Escolaridad
                                <div style="margin-left: 10px;" class="popup caja" onclick="myFunction5()">
                                    <p style="font-size: 14px;">?</p>
                                    <span class="popuptext" id="myPopup5" style="width: 100px">Información No Modificable.</span>
                                </div>
                            </label>
                            <input type="text" class="form-control" value="<?php echo $e_ficha['escolaridad'] ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="grupo_vulnerable">Grupo Vulnerable
                                <div style="margin-left: 10px;" class="popup caja" onclick="myFunction6()">
                                    <p style="font-size: 14px;">?</p>
                                    <span class="popuptext" id="myPopup6" style="width: 100px">Información No Modificable.</span>
                                </div>
                            </label>
                            <input type="text" class="form-control" value="<?php echo $e_ficha['grupo_vulnerable'] ?>" readonly>
                        </div>
                    </div>                   
                   
                </div>
                
                <div class="form-group clearfix">
                    <a href="fichas_psic.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="update" class="btn btn-info">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>