<?php
$page_title = 'Editar Histórico Interno de Puestos';
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

$areas = find_all_area_orden('area');
$cat_puestos = find_all('cat_puestos');
$e_hist = find_by_id('rel_hist_exp_int', (int)$_GET['id'], 'id_rel_hist_exp_int');
$detalle = find_by_id('detalles_usuario', $e_hist['id_detalle_usuario'], 'id_det_usuario');
if (!$e_hist) {
    $session->msg("d", "La información no existe, verifique el ID.");
    redirect('edit_hist_puestos.php?id=' . (int)$e_hist['id_rel_hist_exp_int']);
}
?>
<?php
if (isset($_POST['edit_hist_puestos'])) {
    if (empty($errors)) {
        $id_cat_puestos = remove_junk($db->escape(($_POST['id_cat_puestos'])));
        $id_area = remove_junk($db->escape(($_POST['id_area'])));
        $clave = remove_junk($db->escape(($_POST['clave'])));
        $niv_puesto = remove_junk($db->escape(($_POST['niv_puesto'])));
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_conclusion = $_POST['fecha_conclusion'];

        $query  = "UPDATE rel_hist_exp_int SET ";
        $query .= "id_cat_puestos='{$id_cat_puestos}', id_area='{$id_area}', clave='{$clave}', niv_puesto='{$niv_puesto}', fecha_inicio='{$fecha_inicio}',
                    fecha_conclusion='{$fecha_conclusion}' ";
        $query .= "WHERE id_rel_hist_exp_int='{$db->escape($e_hist['id_rel_hist_exp_int'])}'";
        $result = $db->query($query);

        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Histórico laboral ha sido actualizado.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" edito histórico laboral ' . 'id:' . (int)$e_hist['id_rel_hist_exp_int'] . '.', 2);
            redirect('edit_hist_puestos.php?id=' . (int)$e_hist['id_rel_hist_exp_int'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el expediente laboral, debido a que no hay cambios registrados en la descripción.');
            redirect('edit_hist_puestos.php?id=' . (int)$e_hist['id_rel_hist_exp_int'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_hist_puestos.php?id=' . (int)$e_hist['id_rel_hist_exp_int'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
<div class="row login-page7" style="margin-left: 25%; margin-top: 5%;">
    <div class="panel-heading">
        <strong>
            <span style="font-size: 16px;">Editar Expediente Histórico: <?php echo $detalle['nombre'] . " " . $detalle['apellidos'] ?></span>
        </strong>
    </div>
    <div class="panel-body" style=" margin-top: -10px;">
        <form method="post" action="edit_hist_puestos.php?id=<?php echo (int) $e_hist['id_rel_hist_exp_int']; ?>" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_cat_puestos" class="control-label">Puesto</label>
                        <select class="form-control" name="id_cat_puestos">
                            <option value="">Escoge una opción</option>
                            <?php foreach ($cat_puestos as $datos) : ?>
                                <option <?php if ($e_hist['id_cat_puestos'] == $datos['id_cat_puestos']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_cat_puestos']; ?>"><?php echo ucwords($datos['descripcion']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_area">Área</label>
                        <select class="form-control" name="id_area">
                            <option value="0">Escoge una opción</option>
                            <?php foreach ($areas as $area) : ?>
                                <option <?php if ($e_hist['id_area'] == $area['id_area']) echo 'selected="selected"'; ?> value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="clave" class="control-label">Clave</label>
                        <input type="text" class="form-control" name="clave" value="<?php echo $e_hist['clave'] ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="niv_puesto" class="control-label">Nivel de Puesto</label>
                        <input type="text" class="form-control" name="niv_puesto" value="<?php echo $e_hist['niv_puesto'] ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha de Inicio</label>
                        <input type="date" class="form-control" name="fecha_inicio" value="<?php echo $e_hist['fecha_inicio']?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fecha_conclusion">Fecha de Conclusión</label>
                        <input type="date" class="form-control" name="fecha_conclusion" value="<?php echo $e_hist['fecha_conclusion']?>" required>
                    </div>
                </div>
            </div>
            <div class="form-group clearfix" style="margin-top: 35px;">
                <a href="hist_puestos.php?id=<?php echo $e_hist['id_detalle_usuario']; ?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
                <button type="submit" name="edit_hist_puestos" class="btn btn-primary" value="subir">Guardar</button>
            </div>
        </form>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>