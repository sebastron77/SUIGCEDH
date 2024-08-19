<?php
$page_title = 'Ficha Técnica - Área Psicológica';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel = $user['user_level'];
$areas = find_all_area_orden('area');
$tipo_ficha = find_tipo_ficha((int)$_GET['id']);

$funciones = find_all('cat_funcion');
$ocupaciones = find_all_order('cat_ocupaciones', 'descripcion');
$escolaridades = find_all('cat_escolaridad');
$visitadurias = find_all_visitadurias();
$autoridades = find_all_aut_res();
$generos = find_all('cat_genero');
$grupos = find_all_order('cat_grupos_vuln', 'id_cat_grupo_vuln');
$derechos_vuln = find_all_order('cat_der_vuln', 'descripcion');

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    redirect('home.php');
}
if ($nivel == 4) {
    page_require_level(4);
}
if ($nivel == 5) {
    redirect('home.php');
}
if ($nivel == 6) {
    redirect('home.php');
}
if ($nivel == 7) {
    redirect('home.php');
}
?>
<?php
$e_ficha = find_by_id_ficha((int)$_GET['id'],2);
if (!$e_ficha) {
    $session->msg("d", "id de ficha no encontrado.");
    redirect('fichas_psic.php');
}
?>
<?php
if (isset($_POST['edit_ficha_psic'])) {
    $req_fields = array('funcion', 'num_queja', 'area_solicitante', 'visitaduria', 'ocupacion', 'escolaridad', 'hechos', 'autoridad', 'nombre_usuario', 'edad', 'sexo', 'grupo_vulnerable', 'fecha_intervencion', 'resultado', 'documento_emitido');
    validate_fields($req_fields);
    if (empty($errors)) {
        $id = (int)$e_ficha['id_ficha'];
        $funcion   = remove_junk($db->escape($_POST['funcion']));
        $num_queja   = remove_junk($db->escape($_POST['num_queja']));
        $visitaduria   = remove_junk($db->escape($_POST['visitaduria']));
        $area_solicitante   = remove_junk($db->escape($_POST['area_solicitante']));
        $ocupacion   = remove_junk(($db->escape($_POST['ocupacion'])));
        $escolaridad   = remove_junk(($db->escape($_POST['escolaridad'])));
        $hechos   = remove_junk(($db->escape($_POST['hechos'])));
        $autoridad   = remove_junk(($db->escape($_POST['autoridad'])));
        $nombre_usuario   = remove_junk($db->escape($_POST['nombre_usuario']));
        $edad   = remove_junk($db->escape($_POST['edad']));
        $sexo   = remove_junk($db->escape($_POST['sexo']));
        $grupo_vulnerable   = remove_junk($db->escape($_POST['grupo_vulnerable']));
        $fecha_intervencion   = remove_junk($db->escape($_POST['fecha_intervencion']));
        $resultado2   = remove_junk($db->escape($_POST['resultado']));
        $documento_emitido   = remove_junk($db->escape($_POST['documento_emitido']));
        $adjunto   = remove_junk($db->escape($_POST['ficha_adjunto']));
        $protocolo_estambul   = remove_junk($db->escape($_POST['protocolo_estambul']));
        $nombre_especialista   = remove_junk($db->escape($_POST['nombre_especialista']));
        $clave_documento   = remove_junk($db->escape($_POST['clave_documento']));

        $folio_editar = $e_ficha['folio'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/fichastecnicas/' . $resultado;

        $name = $_FILES['ficha_adjunto']['name'];
        $size = $_FILES['ficha_adjunto']['size'];
        $type = $_FILES['ficha_adjunto']['type'];
        $temp = $_FILES['ficha_adjunto']['tmp_name'];

        if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else {
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }

        if ($name != '') {
            $sql = "UPDATE fichas SET id_cat_funcion='{$funcion}', num_queja='{$num_queja}', id_visitaduria='{$visitaduria}', id_area_solicitante='{$area_solicitante}',
			id_cat_ocup='{$ocupacion}', id_cat_escolaridad='{$escolaridad}', id_cat_der_vuln='{$hechos}', id_cat_aut='{$autoridad}', nombre_usuario='{$nombre_usuario}',
			edad='{$edad}', id_cat_gen='{$sexo}', id_cat_grupo_vuln='{$grupo_vulnerable}', fecha_intervencion='{$fecha_intervencion}', resultado='{$resultado2}', 
			documento_emitido='{$documento_emitido}', ficha_adjunto='{$name}', protocolo_estambul='{$protocolo_estambul}',nombre_especialista='{$nombre_especialista}', 
			clave_documento='{$clave_documento}' WHERE id_ficha='{$db->escape($id)}'";
        }
        if ($name == '') {
            $sql = "UPDATE fichas SET id_cat_funcion='{$funcion}', num_queja='{$num_queja}', id_visitaduria='{$visitaduria}', id_area_solicitante='{$area_solicitante}', 
			id_cat_ocup='{$ocupacion}', id_cat_escolaridad='{$escolaridad}', id_cat_der_vuln='{$hechos}', id_cat_aut='{$autoridad}', nombre_usuario='{$nombre_usuario}',
			edad='{$edad}', id_cat_gen='{$sexo}', id_cat_grupo_vuln='{$grupo_vulnerable}', fecha_intervencion='{$fecha_intervencion}', resultado='{$resultado2}', 
			documento_emitido='{$documento_emitido}', protocolo_estambul='{$protocolo_estambul}',nombre_especialista='{$nombre_especialista}', 
			clave_documento='{$clave_documento}' WHERE id_ficha='{$db->escape($id)}'";
        }
            
		
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Información Actualizada ");
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
                <span>Editar ficha - Área Psicológica</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_ficha_psic.php?id=<?php echo (int)$e_ficha['id_ficha']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="funcion">Función</label>
                            <select class="form-control" name="funcion">
							<option>Escoge una opción</option>
							<?php foreach ($funciones as $datos) : ?>
                                    <option <?php if ($datos['id_cat_funcion'] === $e_ficha['id_cat_funcion']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_cat_funcion']; ?>"><?php echo ucwords($datos['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="num_queja">No. de Queja</label>
                            <input type="text" class="form-control" value="<?php echo remove_junk($e_ficha['num_queja']); ?>" name="num_queja" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="area_solicitante">Área Solicitante</label>
                            <select class="form-control" name="area_solicitante">
							<option>Escoge una opción</option>
                                <?php foreach ($areas as $area) : ?>
                                    <option <?php if ($area['id_area'] === $e_ficha['id_area_solicitante']) echo 'selected="selected"'; ?> value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ocupacion">Ocupacion</label>
                            <select class="form-control" name="ocupacion">
							<option>Escoge una opción</option>
                                <?php foreach ($ocupaciones as $ocupacion) : ?>
                                    <option <?php if ($ocupacion['id_cat_ocup'] == $e_ficha['id_cat_ocup']) echo 'selected="selected"'; ?> value="<?php echo $ocupacion['id_cat_ocup']; ?>"><?php echo ucwords($ocupacion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="escolaridad">Escolaridad</label>
                            <select class="form-control" name="escolaridad">
							<option>Escoge una opción</option>
                                <?php foreach ($escolaridades as $estudios) : ?>
                                    <option <?php if ($estudios['id_cat_escolaridad'] === $e_ficha['id_cat_escolaridad']) echo 'selected="selected"'; ?> value="<?php echo $estudios['id_cat_escolaridad']; ?>"><?php echo ucwords($estudios['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="visitaduria">Visitaduria</label>
                            <select class="form-control" name="visitaduria">
                                <option value="">Escoge una opción</option>
                               <?php foreach ($visitadurias as $visitaduria) : ?>
                                    <option <?php if ($visitaduria['id_area'] === $e_ficha['id_visitaduria']) echo 'selected="selected"'; ?> value="<?php echo $visitaduria['id_area']; ?>"><?php echo ucwords($visitaduria['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="hechos">Presuntos hechos violatorios</label>
                            <select class="form-control" name="hechos">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($derechos_vuln as $derecho) : ?>
                                    <option <?php if ($derecho['id_cat_der_vuln'] === $e_ficha['id_cat_der_vuln']) echo 'selected="selected"'; ?> value="<?php echo $derecho['id_cat_der_vuln']; ?>"><?php echo ucwords($derecho['descripcion']); ?></option>
                                <?php endforeach; ?>
								</select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="autoridad">Autoridad señalada</label>
                            <select class="form-control" name="autoridad">
                                <option value="">Escoge una opción</option>
                            <?php foreach ($autoridades as $autoridad) : ?>
                                <option <?php if ($autoridad['id_cat_aut'] === $e_ficha['id_cat_aut']) echo 'selected="selected"'; ?> value="<?php echo $autoridad['id_cat_aut']; ?>"><?php echo ucwords($autoridad['nombre_autoridad']); ?></option>
                            <?php endforeach; ?>
							</select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_usuario">Nombre del usuario</label>
                            <input type="text" class="form-control" name="nombre_usuario" placeholder="Nombre Completo" value="<?php echo remove_junk($e_ficha['nombre_usuario']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="edad">Edad</label>
                            <input type="number" class="form-control" min="1" max="120" name="edad" value="<?php echo remove_junk($e_ficha['edad']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Género</label>
                            <select class="form-control" name="sexo">
                                <option value="">Escoge una opción</option>
                            <?php foreach ($generos as $genero) : ?>
                                <option <?php if ($genero['id_cat_gen'] === $e_ficha['id_cat_gen']) echo 'selected="selected"'; ?> value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="grupo_vulnerable">Grupo Vulnerable</label>
                            <select class="form-control" name="grupo_vulnerable">
                                <option value="">Escoge una opción</option>
                            <?php foreach ($grupos as $grupo) : ?>
                                <option <?php if ($grupo['id_cat_grupo_vuln'] === $e_ficha['id_cat_grupo_vuln']) echo 'selected="selected"'; ?> value="<?php echo $grupo['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo['descripcion']); ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_intervencion">Fecha de Intervención</label>
                            <input type="date" class="form-control" value="<?php echo remove_junk($e_ficha['fecha_intervencion']); ?>" name="fecha_intervencion" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="protocolo_estambul">Protocolo de Estambul</label>
                            <select class="form-control" name="protocolo_estambul">
                                <option <?php if ($e_ficha['protocolo_estambul'] === 'Aplicado') echo 'selected="selected"'; ?> value="Aplicado">Aplicado</option>
                                <option <?php if ($e_ficha['protocolo_estambul'] === 'No Aplicado') echo 'selected="selected"'; ?> value="No Aplicado">No Aplicado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="resultado">Resultado</label>
                            <select class="form-control" name="resultado">
                                <option <?php if ($e_ficha['resultado'] === 'Positivo') echo 'selected="selected"'; ?> value="Positivo">Positivo</option>
                                <option <?php if ($e_ficha['resultado'] === 'Negativo') echo 'selected="selected"'; ?> value="Negativo">Negativo</option>
                                <option <?php if ($e_ficha['resultado'] === 'No aplica') echo 'selected="selected"'; ?> value="No Aplica">No Aplica</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="documento_emitido">Documento Emititdo</label>
                            <select class="form-control" name="documento_emitido">
                                <option <?php if ($e_ficha['documento_emitido'] === 'Dictamen psicológico')  echo 'selected="selected"'; ?> value="Dictamen psicológico">Dictamen psicológico</option>
                                <option <?php if ($e_ficha['documento_emitido'] === 'Informe psicológico')  echo 'selected="selected"'; ?> value="Informe psicológico">Informe psicológico</option>
                                <option <?php if ($e_ficha['documento_emitido'] === 'No Aplica')  echo 'selected="selected"'; ?> value="No Aplica">No Aplica</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_especialista">Especialista que emite</label>
                            <input type="text" class="form-control" name="nombre_especialista" value="<?php echo remove_junk($e_ficha['nombre_especialista']); ?>" placeholder="Nombre Completo del especialista" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="clave_documento">Clave del documento</label>
                            <input type="text" class="form-control" name="clave_documento" value="<?php echo remove_junk($e_ficha['clave_documento']); ?>" placeholder="Insertar la clave del documento" required>
                        </div>
                    </div>     
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ficha_adjunto">Ficha Adjunta</label>
                            <input type="file" accept="application/pdf" class="form-control" name="ficha_adjunto" id="ficha_adjunto" value="uploads/fichastecnicas/<?php echo $e_ficha['ficha_adjunto']; ?>">
                            <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_ficha['ficha_adjunto']); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <?php if ($tipo_ficha['tipo_ficha'] == 1) : ?>
                        <a href="fichas_psic.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                    <?php else : ?>
                        <a href="fichas_psic.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                    <?php endif; ?>
                    <button type="submit" name="edit_ficha_psic" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>