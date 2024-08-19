<?php
$page_title = 'Editar Orientación';
require_once('includes/load.php');

// page_require_level(5);
$e_detalle = find_by_id_orientacion((int)$_GET['id']);
if (!$e_detalle) {
    $session->msg("d", "id de orientación no encontrado.");
    redirect('orientaciones.php');
}
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$niv_estudios = find_all('cat_escolaridad');
$ocupaciones = find_all('cat_ocupaciones');
$grupos_vuln = find_all('cat_grupos_vuln');
$generos = find_all('cat_genero');
$autoridades = find_all_autoridades();
$entidades = find_all('cat_entidad_fed');
$nacionalidad = find_all('cat_nacionalidades');
$medios_pres = find_all('cat_medio_pres');
$cat_municipios = find_all_cat_municipios();


if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 3) {
    redirect('home.php');
}
if ($nivel_user == 4) {
    redirect('home.php');
}
if ($nivel_user == 5) {
    page_require_level_exacto(5);
}
if ($nivel_user == 6) {
    redirect('home.php');
}
if ($nivel_user == 7) {
    redirect('home.php');
}
?>

<?php
if (isset($_POST['edit_orientacion'])) {
    $req_fields = array('nombre', 'nestudios', 'ocupacion', 'edad', 'tel', 'sexo', 'calle', 'colonia', 'cpostal', 'entidad', 'nacionalidad', 'grupo_vulnerable', 'lengua');
    validate_fields($req_fields);
    if (empty($errors)) {
        $id = (int)$e_detalle['idcan'];
        $folio_orientacion   = remove_junk($db->escape($_POST['folio']));
        $correo   = remove_junk($db->escape($_POST['correo']));
        $nombre   = remove_junk($db->escape($_POST['nombre']));
        $nestudios   = remove_junk($db->escape($_POST['nestudios']));
        $ocupacion   = remove_junk($db->escape($_POST['ocupacion']));
        $edad   = remove_junk(upper_case($db->escape($_POST['edad'])));
        $tel   = remove_junk(upper_case($db->escape($_POST['tel'])));
        $ext   = remove_junk($db->escape($_POST['ext']));
        $sexo   = remove_junk($db->escape($_POST['sexo']));
        $calle   = remove_junk($db->escape($_POST['calle']));
        $colonia   = remove_junk($db->escape($_POST['colonia']));
        $cpostal   = remove_junk($db->escape($_POST['cpostal']));
        //$municipio   = remove_junk($db->escape($_POST['municipio']));
        $id_cat_mun = remove_junk($db->escape($_POST['id_cat_mun']));
        $localidad = remove_junk($db->escape($_POST['municipio_localidad']));
        $entidad   = remove_junk($db->escape($_POST['entidad']));
        $nacionalidad   = remove_junk($db->escape($_POST['nacionalidad']));
        $institucion_canaliza   = remove_junk($db->escape($_POST['institucion_canaliza']));
        $medio   = remove_junk($db->escape($_POST['medio']));
        $grupo_vulnerable   = remove_junk($db->escape($_POST['grupo_vulnerable']));
        $lengua   = remove_junk($db->escape($_POST['lengua']));
        $adjunto   = remove_junk($db->escape($_POST['adjunto']));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));
        $la_orientacion = $e_detalle['folio'];
        $creacion   = remove_junk($db->escape($_POST['creacion']));

        $folio_editar = $e_detalle['folio'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/orientacioncanalizacion/orientacion/' . $resultado;

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
            $sql = "UPDATE folios SET folio='{$folio_orientacion}' WHERE folio='{$db->escape($la_orientacion)}'";
            $sql2 = "UPDATE orientacion_canalizacion SET folio='{$folio_orientacion}', correo_electronico='{$correo}', nombre_completo='{$nombre}', nivel_estudios='{$nestudios}', ocupacion='{$ocupacion}', edad='{$edad}', telefono='{$tel}', extension='{$ext}', sexo='{$sexo}', calle_numero='{$calle}', colonia='{$colonia}',codigo_postal='{$cpostal}', id_cat_mun='{$id_cat_mun}', municipio_localidad='{$localidad}', entidad='{$entidad}', nacionalidad='{$nacionalidad}', medio_presentacion='{$medio}', grupo_vulnerable='{$grupo_vulnerable}', lengua='{$lengua}', institucion_canaliza='{$institucion_canaliza}', observaciones='{$observaciones}', adjunto='{$name}', creacion='{$creacion}' WHERE id_or_can='{$db->escape($id)}'";
        }
        if ($name == '') {
            $sql3 = "UPDATE folios SET folio='{$folio_orientacion}' WHERE folio='{$db->escape($la_orientacion)}'";
            $sql4 = "UPDATE orientacion_canalizacion SET folio='{$folio_orientacion}', correo_electronico='{$correo}', nombre_completo='{$nombre}', nivel_estudios='{$nestudios}', ocupacion='{$ocupacion}', edad='{$edad}', telefono='{$tel}', extension='{$ext}', sexo='{$sexo}', calle_numero='{$calle}', colonia='{$colonia}',codigo_postal='{$cpostal}', id_cat_mun='{$id_cat_mun}', municipio_localidad='{$localidad}', entidad='{$entidad}', nacionalidad='{$nacionalidad}', medio_presentacion='{$medio}', grupo_vulnerable='{$grupo_vulnerable}', lengua='{$lengua}', institucion_canaliza='{$institucion_canaliza}', observaciones='{$observaciones}', creacion='{$creacion}' WHERE id_or_can='{$db->escape($id)}'";
        }

        $result = $db->query($sql);
        $result2 = $db->query($sql2);
        $result3 = $db->query($sql3);
        $result4 = $db->query($sql4);

        if (($result && $db->affected_rows() === 1) || ($result2 && $db->affected_rows() === 1) || ($result3 && $db->affected_rows() === 1) || ($result4 && $db->affected_rows() === 1)) {
            $session->msg('s', "Información Actualizada ");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó orientación, Folio: ' . $folio_orientacion . '.', 2);
            redirect('orientaciones.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos.');
            redirect('orientaciones.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_orientacion.php?id=' . (int)$e_detalle['idcan'], false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar orientación <?php echo $e_detalle['folio']; ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_orientacion.php?id=<?php echo (int)$e_detalle['idcan']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="folio">Folio de Orientación</label>
                            <input type="text" class="form-control" name="folio" value="<?php echo remove_junk($e_detalle['folio']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="creacion">Fecha de creación</label><br>
                            <input type="date" class="form-control" name="creacion" value="<?php echo remove_junk($e_detalle['creacion']); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="correo">Correo Electrónico</label>
                            <input type="text" class="form-control" name="correo" placeholder="ejemplo@correo.com" value="<?php echo remove_junk($e_detalle['correo_electronico']); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre">Nombre Completo</label>
                            <input type="text" class="form-control" name="nombre" placeholder="Nombre Completo" value="<?php echo remove_junk($e_detalle['nombre_completo']); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="nestudios">Nivel de Estudios</label>
                            <select class="form-control" name="nestudios">
                                <?php foreach ($niv_estudios as $estudios) : ?>

                                    <option <?php if ($estudios['id_cat_escolaridad'] === $e_detalle['est']) echo 'selected="selected"'; ?> value="<?php echo $estudios['id_cat_escolaridad']; ?>"><?php echo ucwords($estudios['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ocupacion">Ocupacion</label>
                            <select class="form-control" name="ocupacion">
                                <?php foreach ($ocupaciones as $ocupacion) : ?>
                                    <option <?php if ($ocupacion['id_cat_ocup'] === $e_detalle['ocupacion']) echo 'selected="selected"'; ?> value="<?php echo $ocupacion['id_cat_ocup']; ?>"><?php echo ucwords($ocupacion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="edad">Edad</label>
                            <input type="number" min="1" max="120" class="form-control" name="edad" placeholder="Edad" value="<?php echo remove_junk($e_detalle['edad']); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tel">Teléfono</label>
                            <input type="text" class="form-control" maxlength="10" name="tel" placeholder="Teléfono" value="<?php echo remove_junk($e_detalle['telefono']); ?>">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="ext">Ext</label>
                            <input type="text" class="form-control" maxlength="3" name="ext" value="<?php echo remove_junk($e_detalle['extension']); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="lengua">Dialecto</label>
                            <input type="text" class="form-control" name="lengua" value="<?php echo remove_junk($e_detalle['lengua']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="grupo_vulnerable">Grupo Vulnerable</label>
                            <select class="form-control" name="grupo_vulnerable">
                                <?php foreach ($grupos_vuln as $grupo_vuln) : ?>
                                    <option <?php if ($grupo_vuln['id_cat_grupo_vuln'] === $e_detalle['grupo_vulnerable']) echo 'selected="selected"'; ?> value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sexo">Género</label>
                            <select class="form-control" name="sexo">
                                <?php foreach ($generos as $genero) : ?>
                                    <option <?php if ($genero['id_cat_gen'] === $e_detalle['sexo']) echo 'selected="selected"'; ?> value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="calle">Calle y número</label>
                            <input type="text" class="form-control" name="calle" placeholder="Calle y número" value="<?php echo remove_junk($e_detalle['calle_numero']); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="colonia">Colonia</label>
                            <input type="text" class="form-control" name="colonia" placeholder="Colonia" value="<?php echo remove_junk($e_detalle['colonia']); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cpostal">Código Postal</label>
                            <input type="text" class="form-control" maxlength="5" name="cpostal" placeholder="Código Postal" value="<?php echo remove_junk($e_detalle['codigo_postal']); ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="municipio">Municipio</label>
                            <select class="form-control" name="id_cat_mun">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_municipios as $municipio) : ?>
                                    <option <?php if ($municipio['id_cat_mun'] === $e_detalle['id_cat_mun'])
                                                echo 'selected="selected"'; ?> value="<?php echo $municipio['id_cat_mun']; ?>"><?php
                                                                                                                                echo ucwords($municipio['descripcion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="municipio_localidad">Localidad</label>
                            <input type="text" class="form-control" name="municipio_localidad" placeholder="Localidad" value="<?php echo remove_junk($e_detalle['municipio_localidad']); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="entidad">Entidad</label>
                            <select class="form-control" name="entidad">
                                <?php foreach ($entidades as $entidad) : ?>
                                    <option <?php if ($entidad['id_cat_ent_fed'] === $e_detalle['entidad']) echo 'selected="selected"'; ?> value="<?php echo $entidad['id_cat_ent_fed']; ?>"><?php echo ucwords($entidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nacionalidad">Nacionalidad</label>
                            <select class="form-control" name="nacionalidad">
                                <?php foreach ($nacionalidad as $nacion) : ?>
                                    <option <?php if ($nacion['id_cat_nacionalidad'] === $e_detalle['nacionalidad']) echo 'selected="selected"'; ?> value="<?php echo $nacion['id_cat_nacionalidad']; ?>"><?php echo ucwords($nacion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="medio">Medio de presentación</label>
                            <select class="form-control" name="medio">
                                <?php foreach ($medios_pres as $medio) : ?>
                                    <option <?php if ($medio['id_cat_med_pres'] === $e_detalle['medio_presentacion']) echo 'selected="selected"'; ?> value="<?php echo $medio['id_cat_med_pres']; ?>"><?php echo ucwords($medio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="institucion_canaliza">Institución señalada como responsable</label>
                            <select class="form-control" name="institucion_canaliza">
                                <?php foreach ($autoridades as $autoridad) : ?>
                                    <option <?php if ($autoridad['id_cat_aut'] === $e_detalle['institucion_canaliza']) echo 'selected="selected"'; ?> value="<?php echo $autoridad['id_cat_aut']; ?>"><?php echo ucwords($autoridad['nombre_autoridad']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="adjunto">Adjunto</label>
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto" value="uploads/orientacioncanalizacion/<?php echo $e_detalle['adjunto']; ?>">
                            <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_detalle['adjunto']); ?></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label><br>
                            <textarea name="observaciones" class="form-control" id="observaciones" cols="50" rows="2" value="<?php echo remove_junk($e_detalle['observaciones']); ?>"><?php echo remove_junk($e_detalle['observaciones']); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">

                </div>
                <div class="form-group clearfix">
                    <a href="orientaciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_orientacion" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>