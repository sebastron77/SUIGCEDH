<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Actividad sobre Trata de Personas';
require_once('includes/load.php');
$user = current_user();
$id_folio = last_id_folios_general();
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
$id_user = $user['id_user'];
$cat_municipios = find_all_cat_municipios();
$areas = find_all('area');
$inticadores_pat = find_all_pat_corto(10);
?>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_actividadCotrapem'])) {
    // Contamos la cantidad de imagenes
    $countfiles = count($_FILES['files']['name']);

    if (empty($errors)) {
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
        date_default_timezone_set('America/Mexico_City');
        $creacion = date('Y-m-d');

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int)$nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int)$nuevo['contador'] + 1);
            }
        }

        $year = date("Y");
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-ACPM';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-ACPM';
        $carpeta = 'uploads/actividadCotrapem/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        // $move =  move_uploaded_file($temp, $carpeta . "/" . $name);

        // Generamos el bucle de todos los archivos
        for ($i = 0; $i < $countfiles; $i++) {

            // Extraemos en variable el nombre de archivo
            $filename = $_FILES['files']['name'][$i];

            // Designamos la carpeta de subida
            $target_file = 'uploads/actividadCotrapem/' . $folio_carpeta . '/' . $filename;

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
		
		/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}


        $query = "INSERT INTO actividades_cotrapem (";
        $query .= "folio, tipo_actividad, modalidad, fecha, municipio, lugar, instituciones_participantes, publico_dirige, hombres, mujeres, no_binarios,
                    total, area_responsable, observaciones, carpeta, id_indicadores_pat, id_creador, fecha_creacion";
        $query .= ") VALUES (";
        $query .= " '{$folio}','{$tipo_actividad}','{$modalidad}','{$fecha}','{$municipio}','{$lugar}','{$instituciones_participantes}','{$publico_dirige}',
                    '{$hombres}','{$mujeres}','{$no_binarios}','{$total}','{$area_responsable}','{$observaciones}','{$carpeta}','{$id_indicadores_pat}','{$id_user}','{$creacion}'";
        $query .= ")";

        $query2 = "INSERT INTO folios (";
        $query2 .= "folio, contador";
        $query2 .= ") VALUES (";
        $query2 .= " '{$folio}','{$no_folio1}'";
        $query2 .= ")";

        if ($db->query($query) && $db->query($query2)) {
            //sucess
            $session->msg('s', " La actividad de COTRAPEM ha sido agregada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó actividad COTRAPEM, Folio: ' . $folio . '.', 1);
            redirect('actividades_cotrapem.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la actividad de COTRAPEM.');
            redirect('add_actividadCotrapem.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_actividadCotrapem.php', false);
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
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Actividad sobre Trata de Personas</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_actividadCotrapem.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_actividad">Tipo de Actividad</label>
                            <select class="form-control" name="tipo_actividad" id="tipo_actividad">
                                <option value="">Escoge una opción</option>
                                <option value="Conferencia">Conferencia</option>
                                <option value="Conversatorio">Conversatorio</option>
                                <option value="Presentación de obra de teatro">Presentación de obra de teatro</option>
                                <option value="Congreso">Congreso</option>
                                <option value="Taller">Taller</option>
                                <option value="Diplomado">Diplomado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="modalidad">Modalidad</label>
                            <select class="form-control" name="modalidad" id="modalidad">
                                <option value="">Escoge una opción</option>
                                <option value="Virtual">Virtual</option>
                                <option value="Presencial">Presencial</option>
                                <option value="Híbrido">Híbrido</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha de Actividad</label>
                            <input type="date" class="form-control" name="fecha" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="municipio">Municipio</label>
                            <select class="form-control" name="municipio">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_municipios as $id_cat_municipio) : ?>
                                    <option value="<?php echo $id_cat_municipio['id_cat_mun']; ?>"><?php echo ucwords($id_cat_municipio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lugar">Lugar de Actividad</label>
                            <input type="text" class="form-control" name="lugar" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="instituciones_participantes">Instituciones participantes</label>
                            <textarea class="form-control" name="instituciones_participantes" cols="10" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="publico_dirige">Público a quien se dirige</label>
                            <select class="form-control" name="publico_dirige" id="publico_dirige">
                                <option value="">Escoge una opción</option>
                                <option value="Personas Servidoras Públicas">Personas Servidoras Públicas</option>
                                <option value="Población en General">Población en General</option>
                                <option value="Organización de la sociedad civil">Organización de la sociedad civil</option>
                                <option value="Estudiantes">Estudiantes</option>
                                <option value="Niñas, niños y adolescentes">Niñas, niños y adolescentes</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="hombres">Hombres</label>
                            <input type="number" class="form-control monto" max="1000" name="hombres" id="hombres" onchange="sumar();">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="mujeres">Mujeres</label>
                            <input type="number" class="form-control monto" max="1000" name="mujeres" id="mujeres" onchange="sumar();">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="no_binarios">No Binarios</label>
                            <input type="number" class="form-control monto" max="1000" name="no_binarios" id="no_binarios" onchange="sumar();">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="total">Total</label>
                            <input type="text" class="form-control" name="total" id="total" readonly>
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
                                    <option value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" cols="10" rows="3"></textarea>
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
                            <select class="form-control form-select" name="id_indicadores_pat" required>
                                <option value="">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="actividades_cotrapem.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_actividadCotrapem" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>