<?php
$page_title = 'Editar Actividad sobre Trata de Personas';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel = $user['user_level'];
$areas = find_all_area_orden('area');
$cat_municipios = find_all_cat_municipios();
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 3) {
    page_require_level(3);
}
if ($nivel_user == 4) {
    redirect('home.php');
}
if ($nivel_user == 5) {
    redirect('home.php');
}
if ($nivel_user == 6) {
    redirect('home.php');
}
if ($nivel_user == 7) {
    redirect('home.php');
}


$e_cotrapem = $e_cotrapem = find_by_id('actividades_cotrapem', (int)$_GET['id'], 'id_actividadCotrapem');;
//$e_cotrapem = find_by_id_actividadCotrapem((int)$_GET['id']);
if (!$e_cotrapem) {
    $session->msg("d", "id de jornada no encontrado.");
    redirect('actividades_cotrapem.php');
}
$inticadores_pat = find_all_pat_corto(10);
?>
<?php
if (isset($_POST['edit_actividadCotrapem'])) {
    $countfiles = count($_FILES['files']['name']);
    if (empty($errors)) {
        $id = (int)$e_cotrapem['id_actividadCotrapem'];
        $tipo_actividad   = remove_junk($db->escape($_POST['tipo_actividad']));
        $modalidad   = remove_junk($db->escape($_POST['modalidad']));
        $fecha   = remove_junk($db->escape($_POST['fecha']));
        $municipio   = remove_junk($db->escape($_POST['municipio']));
        $lugar   = remove_junk($db->escape($_POST['lugar']));
        $instituciones_participantes   = remove_junk($db->escape($_POST['instituciones_participantes']));
        $publico_dirige   = remove_junk($db->escape($_POST['publico_dirige']));
        $hombres   = remove_junk($db->escape($_POST['hombres']));
        $mujeres   = remove_junk($db->escape($_POST['mujeres']));
        $no_binarios   = remove_junk($db->escape($_POST['no_binarios']));
        $total   = remove_junk($db->escape($_POST['total']));
        $area_responsable   = remove_junk($db->escape($_POST['area_responsable']));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));
		$id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));

        $folio_editar = $e_cotrapem['folio'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/actividadCotrapem/' . $resultado;

        // Generamos el bucle de todos los archivos
        for ($i = 0; $i < $countfiles; $i++) {

            // Extraemos en variable el nombre de archivo
            $filename = $_FILES['files']['name'][$i];

            // Designamos la carpeta de subida
            $target_file = 'uploads/actividadCotrapem/' . $resultado . '/' . $filename;

            // Obtenemos la extension del archivo
            $file_extension = pathinfo($target_file, PATHINFO_EXTENSION);

            $file_extension = strtolower($file_extension);

            // Validamos la extensión de la imagen
            $valid_extension = array("png", "jpeg", "jpg");

            if (in_array($file_extension, $valid_extension)) {

                // Subimos la imagen al servidor
                move_uploaded_file($_FILES['files']['tmp_name'][$i], $target_file);
            }
        }

        $sql = "UPDATE actividades_cotrapem SET tipo_actividad='{$tipo_actividad}', modalidad='{$modalidad}', fecha='{$fecha}', municipio='{$municipio}', 
                lugar='{$lugar}', instituciones_participantes='{$instituciones_participantes}', publico_dirige='{$publico_dirige}', hombres='{$hombres}',
                mujeres='{$mujeres}', no_binarios='{$no_binarios}', total='{$total}', area_responsable='{$area_responsable}', observaciones='{$observaciones}',
				id_indicadores_pat='{$id_indicadores_pat}'
                WHERE id_actividadCotrapem='{$db->escape($id)}'";

        $result = $db->query($sql);
        if (($result && $db->affected_rows() === 1) || ($result && $db->affected_rows() === 0)) {
            $session->msg('s', "Información Actualizada ");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó actividad de COTRAPEM, Folio: ' . $folio . '.', 2);
            redirect('actividades_cotrapem.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos.');
            redirect('actividades_cotrapem.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_actividadCotrapem.php?id=' . (int)$e_cotrapem['id_actividadCotrapem'], false);
    }
}
?>
<script type="text/javascript">
    function sumar() {
        const $total = document.getElementById('total');
        let subtotal = 0;
        [...document.getElementsByClassName("monto")].forEach(function(element) {
            if (element.value !== '') {
                subtotal += parseFloat(element.value);
            }
        });
        $total.value = subtotal;
    }
</script>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar Actividad sobre Trata de Personas</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_actividadCotrapem.php?id=<?php echo (int)$e_cotrapem['id_actividadCotrapem']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_actividad">Tipo de Actividad</label>
                            <select class="form-control" name="tipo_actividad" id="tipo_actividad">
                                <option value="">Escoge una opción</option>
                                <option <?php if ($e_cotrapem['tipo_actividad'] == 'Conferencia') echo 'selected="selected"'; ?> value="Conferencia">Conferencia</option>
                                <option <?php if ($e_cotrapem['tipo_actividad'] == 'Conversatorio') echo 'selected="selected"'; ?> value="Conversatorio">Conversatorio</option>
                                <option <?php if ($e_cotrapem['tipo_actividad'] == 'Presentación de obra de teatro') echo 'selected="selected"'; ?> value="Presentación de obra de teatro">Presentación de obra de teatro</option>
                                <option <?php if ($e_cotrapem['tipo_actividad'] == 'Congreso') echo 'selected="selected"'; ?> value="Congreso">Congreso</option>
                                <option <?php if ($e_cotrapem['tipo_actividad'] == 'Taller') echo 'selected="selected"'; ?> value="Taller">Taller</option>
                                <option <?php if ($e_cotrapem['tipo_actividad'] == 'Diplomado') echo 'selected="selected"'; ?> value="Diplomado">Diplomado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="modalidad">Modalidad</label>
                            <select class="form-control" name="modalidad" id="modalidad">
                                <option value="">Escoge una opción</option>
                                <option <?php if ($e_cotrapem['modalidad'] == 'Virtual') echo 'selected="selected"'; ?> value="Virtual">Virtual</option>
                                <option <?php if ($e_cotrapem['modalidad'] == 'Presencial') echo 'selected="selected"'; ?> value="Presencial">Presencial</option>
                                <option <?php if ($e_cotrapem['modalidad'] == 'Híbrido') echo 'selected="selected"'; ?> value="Híbrido">Híbrido</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha de Actividad</label>
                            <input type="date" class="form-control" name="fecha" value="<?php echo $e_cotrapem['fecha'] ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="municipio">Municipio</label>
                            <select class="form-control" name="municipio">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_municipios as $municipio) : ?>
                                    <option <?php if ($municipio['id_cat_mun'] === $e_cotrapem['municipio']) echo 'selected="selected"'; ?> value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lugar">Lugar de Actividad</label>
                            <input type="text" class="form-control" name="lugar" value="<?php echo $e_cotrapem['lugar'] ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="instituciones_participantes">Instituciones participantes</label>
                            <textarea class="form-control" name="instituciones_participantes" cols="10" rows="5"><?php echo $e_cotrapem['instituciones_participantes'] ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="publico_dirige">Público a quien se dirige</label>
                            <select class="form-control" name="publico_dirige" id="publico_dirige">
                                <option value="">Escoge una opción</option>
                                <option <?php if ($e_cotrapem['publico_dirige'] == 'Personas Servidoras Públicas') echo 'selected="selected"'; ?> value="Personas Servidoras Públicas">Personas Servidoras Públicas</option>
                                <option <?php if ($e_cotrapem['publico_dirige'] == 'Población en General') echo 'selected="selected"'; ?> value="Población en General">Población en General</option>
                                <option <?php if ($e_cotrapem['publico_dirige'] == 'Organización de la sociedad civil') echo 'selected="selected"'; ?> value="Organización de la sociedad civil">Organización de la sociedad civil</option>
                                <option <?php if ($e_cotrapem['publico_dirige'] == 'Estudiantes') echo 'selected="selected"'; ?> value="Estudiantes">Estudiantes</option>
                                <option <?php if ($e_cotrapem['publico_dirige'] == 'Niñas, niños y adolescentes') echo 'selected="selected"'; ?> value="Niñas, niños y adolescentes">Niñas, niños y adolescentes</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="hombres">Hombres</label>
                            <input type="number" class="form-control monto" max="1000" name="hombres" id="hombres" value="<?php echo $e_cotrapem['hombres'] ?>" onchange="sumar();">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="mujeres">Mujeres</label>
                            <input type="number" class="form-control monto" max="1000" name="mujeres" id="mujeres" value="<?php echo $e_cotrapem['mujeres'] ?>" onchange="sumar();">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="no_binarios">No Binarios</label>
                            <input type="number" class="form-control monto" max="1000" name="no_binarios" id="no_binarios" value="<?php echo $e_cotrapem['no_binarios'] ?>" onchange="sumar();">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="total">Total</label>
                            <input type="text" class="form-control" name="total" id="total" value="<?php echo $e_cotrapem['total'] ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="area_responsable">Área a la que se turna</label>
                            <select class="form-control" name="area_responsable">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($areas as $area) : ?>
                                    <option <?php if ($area['id_area'] == $e_cotrapem['area_responsable']) echo 'selected="selected"'; ?> value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" cols="10" rows="3"><?php echo $e_cotrapem['observaciones'] ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="files">Evidencia Fotográfica</label>
                            <input type='file' class="custom-file-input form-control" id="inputGroupFile01" name='files[]' multiple />
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" >
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  <?php if ($e_cotrapem['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                </div>
                </div>
                <div class="form-group clearfix">
                    <a href="actividades_cotrapem.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_actividadCotrapem" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>