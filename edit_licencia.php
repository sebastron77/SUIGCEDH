<?php
$page_title = 'Editar Licencia';
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}
if ($nivel_user == 2) {
    page_require_level_exacto(2);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
}
if ($nivel_user > 2 && $nivel_user < 14) :
    redirect('home.php');
endif;
if ($nivel_user > 14) :
    redirect('home.php');
endif;
$idP =  (int)$_GET['id'];
$e_licencia = find_by_id('rel_licencias_personal', (int)$_GET['id'], 'id_rel_licencia_personal');
$detalle = find_by_id('detalles_usuario', $e_licencia['id_detalle_usuario'], 'id_det_usuario');
if (!$e_licencia) {
    $session->msg("d", "La información no existe, verifique el ID.");
    redirect('edit_licencia.php?id=' . (int)$e_licencia['id_rel_licencia_personal']);
}

$id_cat_licencias = find_all('cat_licencias');
$consec = find_by_id_consec($idP);
$licencias = find_all_lic($idP);

?>
<?php
if (isset($_POST['edit_licencia'])) {

    if (empty($errors)) {
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_termino = $_POST['fecha_termino'];
        $id_cat_licencia = $_POST['id_cat_licencia'];
        $observaciones = $_POST['observaciones'];

        $carpeta = 'uploads/personal/licencias/' . $idP;

        $dias = abs((strtotime($fecha_termino) - strtotime($fecha_inicio)) / 86400);

        $name = $_FILES['documento']['name'];
        $size = $_FILES['documento']['size'];
        $type = $_FILES['documento']['type'];
        $temp = $_FILES['documento']['tmp_name'];

        if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else {
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }
        // $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        // $no_dias_rest = (strtotime($lic['fecha_termino']) - strtotime(date("Y-m-d"))) / 86400;
        if ($name != '') {
            $query  = "UPDATE rel_licencias_personal SET ";
            $query .= "fecha_inicio='{$fecha_inicio}', fecha_termino='{$fecha_termino}',id_cat_licencia={$id_cat_licencia},no_dias={$dias},";
            $query .= "observaciones='{$observaciones}', documento='{$name}'";
            $query .= "WHERE id_rel_licencia_personal='{$db->escape($e_licencia['id_rel_licencia_personal'])}'";
            $result = $db->query($query);
        }
        if ($name == '') {
            $query  = "UPDATE rel_licencias_personal SET ";
            $query .= "fecha_inicio='{$fecha_inicio}', fecha_termino='{$fecha_termino}',id_cat_licencia={$id_cat_licencia},no_dias={$dias}, ";
            $query .= "observaciones='{$observaciones}'";
            $query .= "WHERE id_rel_licencia_personal='{$db->escape($e_licencia['id_rel_licencia_personal'])}'";
            $result = $db->query($query);
        }
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "La licencia de permiso ha sido actualizada.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó permiso de licencia al usuario de id:' . (int)$idP, 2);
            redirect('edit_licencia.php?id=' . (int)$e_licencia['id_rel_licencia_personal'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado la licencia de permiso, debido a que no hay cambios registrados en la descripción.');
            redirect('edit_licencia.php?id=' . (int)$e_licencia['id_rel_licencia_personal'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_licencia.php?id=' . (int)$e_licencia['id_rel_licencia_personal'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
<div class="row login-page6" style="width: 60%; height: 360px; margin-left: 15%; margin-top: 5%;">
    <div class="panel-heading" style="height: 11%">
        <strong>
            <span style="font-size: 16px;">EDITAR LICENCIA DE: <?php echo upper_case($detalle['nombre'] . " " . $detalle['apellidos']); ?></span>
        </strong>
    </div>
    <form method="post" action="edit_licencia.php?id=<?php echo (int) $e_licencia['id_rel_licencia_personal']; ?>" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="fecha_inicio">Fecha Inicio</label>
                    <input type="date" class="form-control" name="fecha_inicio" value="<?php echo $e_licencia['fecha_inicio'] ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="fecha_termino">Fecha Conclusión</label>
                    <input type="date" class="form-control" name="fecha_termino" value="<?php echo $e_licencia['fecha_termino'] ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="tipo_licencia">Tipo de Licencia</label>
                    <select class="form-control" name="id_cat_licencia" id="id_cat_licencia" required>
                        <option value="">Escoge una opción</option>
                        <?php foreach ($id_cat_licencias as $t_licencia) : ?>
                            <option <?php if ($e_licencia['id_cat_licencia'] == $t_licencia['id_cat_licencia']) echo 'selected="selected"'; ?> value="<?php echo $t_licencia['id_cat_licencia']; ?>">
                                <?php echo ucwords($t_licencia['descripcion']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea type="text" class="form-control" name="observaciones" id="observaciones" cols="30" rows="4"><?php echo $e_licencia['observaciones']; ?></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <label for="documento">Documento</label>
                <input type="file" accept="application/pdf" class="form-control" name="documento" id="documento">
                <label style="font-size:14px; color:#d10c0c;">Archivo Actual:
                    <a href="uploads/personal/licencias/<?php echo $e_licencia['id_rel_licencia_personal'] . '/' . $e_licencia['documento'] ?>" style="font-size:14px; color: #1248c7; text-decoration: underline;"><?php echo remove_junk($e_licencia['documento']); ?></a>
                </label>
            </div>
        </div>
        <div class="form-group clearfix" style="margin-top: 15px;">
            <a href="licencias.php?id=<?php echo $e_licencia['id_detalle_usuario']; ?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="edit_licencia" class="btn btn-primary" value="subir">Guardar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>