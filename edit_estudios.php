<?php
$page_title = 'Editar Estudios';
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}

if ($nivel_user == 50) {
    page_require_level_exacto(50);
}

$e_estudios = find_by_id_curr((int)$_GET['id']);
$escolaridades = find_all('cat_escolaridad');
if (!$e_estudios) {
    $session->msg("d", "La información no existe, verifique el ID.");
    redirect('edit_estudios.php?id=' . (int)$e_estudios['id_rel_cur_acad']);
}
?>
<?php
if (isset($_POST['edit_estudios'])) {

    if (empty($errors)) {
        $estudios = remove_junk($db->escape(($_POST['estudios'])));
        $institucion = remove_junk($db->escape(($_POST['institucion'])));
        $grado = remove_junk($db->escape(($_POST['grado'])));
        $cedula_profesional = remove_junk($db->escape(($_POST['cedula_profesional'])));
        $observaciones = remove_junk($db->escape(($_POST['observaciones'])));

        $carpeta = 'uploads/personal/expediente/' . $e_estudios['id_rel_detalle_usuario'];

        $name = $_FILES['archivo_comprobatorio']['name'];
        $size = $_FILES['archivo_comprobatorio']['size'];
        $type = $_FILES['archivo_comprobatorio']['type'];
        $temp = $_FILES['archivo_comprobatorio']['tmp_name'];

        //Verificamos que exista la carpeta y si sí, guardamos el pdf
        if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else {
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }

        $query  = "UPDATE rel_curriculum_academico SET ";
        $query .= "estudios='{$estudios}', institucion='{$institucion}', grado='{$grado}', cedula_profesional='{$cedula_profesional}', 
                    archivo_comprobatorio='{$name}', observaciones='{$observaciones}' ";
        $query .= "WHERE id_rel_cur_acad='{$db->escape($e_estudios['id_rel_cur_acad'])}'";

        $result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Expediente de estudios ha sido actualizado. '" . ($name) . "'");
            insertAccion($user['id_user'], '"' . $user['username'] . '" edito expediente de estudios ' . $name . '(id:' . (int)$e_estudios['id_rel_cur_acad'] . ').', 2);
            redirect('edit_estudios.php?id=' . (int)$e_estudios['id_rel_cur_acad'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el expediente de estudios, debido a que no hay cambios registrados en la descripción.');
            redirect('edit_estudios.php?id=' . (int)$e_estudios['id_rel_cur_acad'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_estudios.php?id=' . (int)$e_estudios['id_rel_cur_acad'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar Expediente de Estudios: <?php echo $e_estudios['nombre'] . " " . $e_estudios['apellidos'] ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_estudios.php?id=<?php echo (int) $e_estudios['id_rel_cur_acad']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="estudios">Estudios</label>
                            <input type="text" class="form-control" name="estudios" value="<?php echo remove_junk($e_estudios['estudios']); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="institucion">Institución</label>
                            <input type="text" class="form-control" name="institucion" value="<?php echo remove_junk($e_estudios['institucion']); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="grado">Grado</label>
                            <select class="form-control" name="grado">
                                <?php foreach ($escolaridades as $escolaridad) : ?>
                                    <option <?php if ($escolaridad['id_cat_escolaridad'] === $e_estudios['grado']) echo 'selected="selected"'; ?> value="<?php echo $escolaridad['id_cat_escolaridad']; ?>"><?php echo ucwords($escolaridad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cedula_profesional">Cédula Profesional</label>
                            <input type="text" class="form-control" name="cedula_profesional" value="<?php echo remove_junk($e_estudios['cedula_profesional']); ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="archivo_comprobatorio">Archivo Comprobatorio</label><br>
                            <input type="file" accept="application/pdf" class="form-control" name="archivo_comprobatorio" id="archivo_comprobatorio" value="uploads/personal/expediente/<?php echo $e_estudios['id_rel_detalle_usuario']; ?>">
                            <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_estudios['archivo_comprobatorio']); ?><?php ?></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="hora">Observaciones</label><br>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="50" rows="3"><?php echo $e_estudios['observaciones']?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="exp_ac_lab.php?id=<?php echo $e_estudios['id_rel_detalle_usuario'];?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_estudios" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>