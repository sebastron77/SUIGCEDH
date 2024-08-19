<?php
$page_title = 'Editar Sesión COTRAPEM';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel = $user['user_level'];
$areas = find_all_area_orden('area');
if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    page_require_level(3);
}
if ($nivel == 4) {
    redirect('home.php');
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

$e_cotrapem = find_by_id('cotrapem', (int)$_GET['id'], 'id_sesion');
//$e_cotrapem = find_by_id_sesionCotrapem((int)$_GET['id']);
if (!$e_cotrapem) {
    $session->msg("d", "id de sesión no encontrado.");
    redirect('cotrapem.php');
}
$inticadores_pat = find_all_pat_corto(10);
?>
<?php
if (isset($_POST['edit_sesionCotrapem'])) {
    $countfiles = count($_FILES['files']['name']);
    if (empty($errors)) {
        $id = (int)$e_cotrapem['id_sesion'];
        $fecha   = remove_junk($db->escape($_POST['fecha']));
        $lugar   = remove_junk($db->escape($_POST['lugar']));
        $acuerdos   = remove_junk($db->escape($_POST['acuerdos']));
		$id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));

        $folio_editar = $e_cotrapem['folio'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/sesionCotrapem/' . $resultado;

        // Generamos el bucle de todos los archivos
        for ($i = 0; $i < $countfiles; $i++) {

            // Extraemos en variable el nombre de archivo
            $filename = $_FILES['files']['name'][$i];

            // Designamos la carpeta de subida
            $target_file = 'uploads/sesionCotrapem/' . $resultado . '/' . $filename;

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

        $sql = "UPDATE cotrapem SET fecha='{$fecha}', lugar='{$lugar}', acuerdos='{$acuerdos}',id_indicadores_pat='{$id_indicadores_pat}' WHERE id_sesion='{$db->escape($id)}'";

        $result = $db->query($sql);
        if (($result && $db->affected_rows() === 1) || ($result && $db->affected_rows() === 0)) {
            $session->msg('s', "Información Actualizada ");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó sesión de COTRAPEM, Folio: ' . $folio . '.', 2);
            redirect('cotrapem.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos.');
            redirect('cotrapem.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_sesionCotrapem.php?id=' . (int)$e_cotrapem['id_sesion'], false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar Jornada</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_sesionCotrapem.php?id=<?php echo (int)$e_cotrapem['id_sesion']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha de la Sesión</label>
                            <input type="date" class="form-control" value="<?php echo remove_junk($e_cotrapem['fecha']); ?>" name="fecha" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lugar">Lugar de la Sesión</label>
                            <input type="text" class="form-control" value="<?php echo remove_junk($e_cotrapem['lugar']); ?>" name="lugar" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="acuerdos">Orden del día</label>
                            <textarea class="form-control" name="acuerdos" id="" cols="10" rows="3"><?php echo $e_cotrapem['acuerdos'] ?></textarea>
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
                <div class="form-group clearfix">
                    <a href="cotrapem.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_sesionCotrapem" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>