<?php
$page_title = 'Editar Acción de Vinculación';
require_once('includes/load.php');

$user = current_user();
$detalle = $user['id_user'];
$nivel = $user['user_level'];
//page_require_area(4);
$id_user = $user['id_user'];
$cat_intituciones = find_all('cat_instituciones');
$accionV = find_by_accionV((int)$_GET['id']);
if (!$accionV) {
    $session->msg("d", "id de LA ACCION  no encontrado.");
    redirect('acciones_vinculacion.php');
}
//page_require_level(3);

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    page_require_level(3);
}
if ($nivel > 3) {
    redirect('home.php');
}
$inticadores_pat = find_all_pat(10);
?>
<?php
if (isset($_POST['edit_accionv'])) {
    $countfiles = count($_FILES['files']['name']);
    if (empty($errors)) {
        $id = (int)$accionV['id_accionV'];
        $fecha   = remove_junk($db->escape($_POST['fecha']));
        $lugar   = remove_junk($db->escape($_POST['lugar']));
        $nombre_actividad   = remove_junk($db->escape($_POST['nombre_actividad']));
        $descripcion   = remove_junk($db->escape($_POST['descripcion']));
        $participantes   = remove_junk($db->escape($_POST['participantes']));
        $inst_procedencia   = remove_junk($db->escape($_POST['inst_procedencia']));
        $modalidad   = remove_junk($db->escape($_POST['modalidad']));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));
		$id_indicadores_pat   = remove_junk(($db->escape($_POST['id_indicadores_pat'])));
        date_default_timezone_set('America/Mexico_City');
        $creacion = date('Y-m-d');

        $folio_editar = $accionV['folio'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/accionesVinc/' . $resultado;

        // Generamos el bucle de todos los archivos
        for ($i = 0; $i < $countfiles; $i++) {

            // Extraemos en variable el nombre de archivo
            $filename = $_FILES['files']['name'][$i];

            // Designamos la carpeta de subida
            $target_file = 'uploads/accionesVinc/' . $resultado . '/' . $filename;

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

        $sql = "UPDATE acciones_vinculacion SET fecha='{$fecha}', lugar='{$lugar}', nombre_actividad='{$nombre_actividad}', descripcion='{$descripcion}',id_indicadores_pat={$id_indicadores_pat},
                participantes='{$participantes}', inst_procedencia='{$inst_procedencia}', modalidad='{$modalidad}', observaciones='{$observaciones}'
                WHERE id_accionV={$db->escape($id)}";

        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Información Actualizada ");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó acción de Vinculación, Folio: ' . $folio . '.', 2);
            redirect('acciones_vinculacion.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos.');
            redirect('acciones_vinculacion.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_accionv.php?id=' . (int)$accionV['id_accionV'], false);
    }
}
?>
<?php
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar Acción de Vinculación</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_accionv.php?id=<?php echo (int)$accionV['id_accionV']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha de la Actividad</label>
                            <input type="date" class="form-control" name="fecha" value="<?php echo $accionV['fecha'] ?>">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="lugar">Lugar de la Actividad</label>
                            <input type="text" class="form-control" name="lugar" value="<?php echo $accionV['lugar'] ?>">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="nombre_actividad">Nombre de la Actividad</label>
                            <input type="text" class="form-control" name="nombre_actividad" value="<?php echo $accionV['nombre_actividad'] ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="modalidad">Modalidad</label>
                            <select class="form-control" name="modalidad">
                                <option value="">Escoge una opción</option>
                                <option <?php if ($accionV['modalidad'] == 'Virtual') echo 'selected="selected"'; ?> value="Virtual">Virtual</option>
                                <option <?php if ($accionV['modalidad'] == 'Presencial') echo 'selected="selected"'; ?> value="Presencial">Presencial</option>
                                <option <?php if ($accionV['modalidad'] == 'Híbrido') echo 'selected="selected"'; ?> value="Híbrido">Híbrido</option>
                            </select>
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
                            <label for="inst_procedencia">Institución de Procedencia</label>
                            <select class="form-control" name="inst_procedencia">
                                <?php foreach ($cat_intituciones as $autoridad) : ?>
                                    <option <?php if ($autoridad['id_cat_instituciones'] == $accionV['inst_procedencia']) echo 'selected="selected"'; ?> value="<?php echo $autoridad['id_cat_instituciones']; ?>"><?php echo ucwords($autoridad['descripcion']); ?></option>
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
                                    <option  <?php if ($accionV['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" name="descripcion" cols="10" rows="3"><?php echo $accionV['descripcion'] ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="participantes">Participantes</label>
                            <textarea class="form-control" name="participantes" cols="10" rows="3"><?php echo $accionV['participantes'] ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" cols="10" rows="3"><?php echo $accionV['observaciones'] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="acciones_vinculacion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_accionv" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>