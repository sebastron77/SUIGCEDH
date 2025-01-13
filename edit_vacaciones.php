<?php
$page_title = 'Editar Vacaciones';
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

$id_rel_vac = (int)$_GET['idrv'];
$id_rel_per_vac = (int)$_GET['idrpv'];

$e_rel_vacaciones = find_by_id('rel_vacaciones', $id_rel_vac, 'id_rel_vacaciones');
$e_rel_p_vacaciones = find_by_id('rel_periodos_vac', $id_rel_per_vac, 'id_rel_periodo_vac');

$detalle = find_by_id('detalles_usuario', $e_rel_vacaciones['id_detalle_usuario'], 'id_det_usuario');

if (!$e_rel_vacaciones) {
    $session->msg("d", "La información no existe, verifique el ID.");
    redirect('edit_vacaciones.php?idrv=' . (int)$e_rel_vacaciones['id_rel_vacaciones'] . '&idrpv=' . (int)$e_rel_p_vacaciones);
}
$periodos = find_all('cat_periodos_vac');
?>
<?php
if (isset($_POST['edit_vacaciones'])) {

    $id_cat_periodo_vac = $_POST['id_cat_periodo_vac'];
    $derecho_vacas = $_POST['derecho_vacas'];
    $observaciones = $_POST['observaciones'];
    $ejercicio = $_POST['ejercicio'];
    $semana1_1 = $_POST['semana1_1'];
    $semana1_2 = $_POST['semana1_2'];

    $query  = "UPDATE rel_vacaciones SET id_cat_periodo_vac = '{$id_cat_periodo_vac}', derecho_vacas = '{$derecho_vacas}', observaciones = '{$observaciones}',
                ejercicio = '{$ejercicio}'";
    $query .= "WHERE id_rel_vacaciones='{$db->escape($e_rel_vacaciones['id_rel_vacaciones'])}'";

    $query2  = "UPDATE rel_periodos_vac SET semana1_1 = '{$semana1_1}', semana1_2 = '{$semana1_2}'";
    $query2 .= "WHERE id_rel_periodo_vac='{$db->escape($e_rel_p_vacaciones['id_rel_periodo_vac'])}'";

    $result = $db->query($query);
    $result2 = $db->query($query2);

    if (($result == 1) || ($result2 == 1)) {
        //sucess
        $session->msg('s', "La información del periodo vacacional de permiso ha sido actualizada.");
        insertAccion($user['id_user'], '"' . $user['username'] . '" editó vacaciones al usuario de id:' . (int)$e_rel_vacaciones['id_detalle_usuario'], 2);
        redirect('edit_vacaciones.php?idrv=' . (int)$e_rel_vacaciones['id_rel_vacaciones'] .  '&idrpv=' . (int)$e_rel_p_vacaciones['id_rel_periodo_vac'], false);
    } else {
        //failed
        $session->msg('d', 'Lamentablemente no se ha actualizado la licencia de permiso, debido a que no hay cambios registrados en la descripción.');
        redirect('edit_vacaciones.php?idrv=' . (int)$e_rel_vacaciones['id_rel_vacaciones'] .  '&idrpv=' . (int)$e_rel_p_vacaciones['id_rel_periodo_vac'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
<div class="row login-page6" style="width: 40%; height: 450px; margin-left: 25%; margin-top: 5%;">
    <div class="panel-heading" style="height: 11%">
        <strong>
            <span style="font-size: 16px;">EDITAR PERIODO VACACIONAL DE: <?php echo upper_case($detalle['nombre'] . " " . $detalle['apellidos']); ?></span>
        </strong>
    </div>
    <div class="panel-body" style=" margin-top: -1%;">
        <?php if ($e_rel_vacaciones['derecho_vacas'] == '0'): ?>
            <form method="post" action="edit_vacaciones.php?idrv=<?php echo (int)$e_rel_vacaciones['id_rel_vacaciones']; ?>" enctype="multipart/form-data">
            <?php endif; ?>
            <?php if ($e_rel_vacaciones['derecho_vacas'] == '1'): ?>
                <form method="post" action="edit_vacaciones.php?idrv=<?php echo (int)$e_rel_vacaciones['id_rel_vacaciones']; ?>&idrpv=<?php echo (int)$e_rel_p_vacaciones['id_rel_periodo_vac'] ?>" enctype="multipart/form-data">
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="id_cat_periodo_vac">Periodo Vacacional <span style="color:red;font-weight:bold;">*</span></label>
                            <select class="form-control" name="id_cat_periodo_vac" id="id_cat_periodo_vac" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($periodos as $periodo) : ?>
                                    <option <?php if ($e_rel_vacaciones['id_cat_periodo_vac'] == $periodo['id_cat_periodo_vac']) echo 'selected="selected"'; ?> value="<?php echo $periodo['id_cat_periodo_vac']; ?>"><?php echo ucwords($periodo['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ejercicio">Ejercicio <span style="color:red;font-weight:bold;">*</span></label>
                            <select class="form-control" name="ejercicio" required>
                                <option value="">Ejercicio (Año)</option>
                                <?php for ($i = 2022; $i <= (int) date("Y"); $i++) { ?>
                                    <option <?php if ((int)$e_rel_vacaciones['ejercicio'] == $i) echo 'selected="selected"'; ?> value='<?php echo $i; ?>'><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="derecho_vacas">¿Derecho a vacaciones? <span style="color:red;font-weight:bold;">*</span></label>
                            <select class="form-control" name="derecho_vacas" id="derecho_vacas" required>
                                <option value="">Escoge una opción</option>
                                <!-- <option value="1">Sí</option> -->
                                <option <?php if ($e_rel_vacaciones['derecho_vacas'] == '1') echo 'selected="selected"'; ?> value="1">Sí</option>
                                <option <?php if ($e_rel_vacaciones['derecho_vacas'] == '0') echo 'selected="selected"'; ?> value="0">No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea type="text" class="form-control" name="observaciones" id="observaciones" cols="30" rows="4"><?php echo $e_rel_vacaciones['observaciones'] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 1%;">
                    <div class="col-md-6">
                        <span style="font-weight: bold; margin-left: 45%; margin-bottom: 1%;"> - Semana/Día -</span><br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="semana1_1" style="margin-left: 35%;">Del día</label>
                            <input type="date" class="form-control" name="semana1_1" value="<?php echo $e_rel_p_vacaciones['semana1_1'] ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="semana1_2" style="margin-left: 35%;">Al día</label>
                            <input type="date" class="form-control" name="semana1_2" value="<?php echo $e_rel_p_vacaciones['semana1_2'] ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="vacaciones.php?id=<?php echo $e_rel_vacaciones['id_detalle_usuario'] ?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_vacaciones" class="btn btn-info">Agregar</button>
                </div>
                </form>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>