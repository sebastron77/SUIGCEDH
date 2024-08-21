<?php
$page_title = 'Editar Servicio';
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];
$idP =  (int)$_GET['id'];

$e_servicio = find_by_id('rel_servicios_vehiculos', (int)$_GET['id'], 'id_rel_servicios_vehiculos');
$vehiculo = find_by_id('vehiculos', $e_servicio['id_vehiculo'], 'id_vehiculo');

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

if (!$e_servicio) {
    $session->msg("d", "La información no existe, verifique el ID.");
    redirect('edit_servicio_vehiculo.php?id=' . (int)$e_servicio['id_rel_servicios_vehiculos']);
}

?>
<?php
if (isset($_POST['edit_servicio_vehiculo'])) {

    if (empty($errors)) {
        $tipo_servicio = $_POST['tipo_servicio'];
        $km_al_servicio = $_POST['km_al_servicio'];
        $fecha_servicio = $_POST['fecha_servicio'];
        $km_prox_servicio = $_POST['km_prox_servicio'];
        $observaciones = $_POST['observaciones'];

        $query  = "UPDATE rel_servicios_vehiculos SET ";
        $query .= "tipo_servicio='{$tipo_servicio}', km_al_servicio='{$km_al_servicio}', fecha_servicio='{$fecha_servicio}', 
                    km_prox_servicio='{$km_prox_servicio}', observaciones='{$observaciones}'";
        $query .= "WHERE id_rel_servicios_vehiculos='{$db->escape($e_servicio['id_rel_servicios_vehiculos'])}'";
        $result = $db->query($query);

        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Información del servicio ha sido actualizado.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó servicio del vehículo con id:' . (int)$e_servicio['id_vehiculo'], 2);
            redirect('edit_servicio_vehiculo.php?id=' . (int)$e_servicio['id_rel_servicios_vehiculos'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado la información del servicio, debido a que no hay cambios registrados en la descripción.');
            redirect('edit_servicio_vehiculo.php?id=' . (int)$e_servicio['id_rel_servicios_vehiculos'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_servicio_vehiculo.php?id=' . (int)$e_servicio['id_rel_servicios_vehiculos'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
<div class="row login-page6" style="width: 60%; height: 360px; margin-left: 15%; margin-top: 5%;">
    <div class="panel-heading" style="height: 11%">
        <strong>
            <span style="font-size: 16px;">EDITAR SERVICIO DE: <?php echo upper_case($vehiculo['marca'] . " " . $vehiculo['modelo']); ?></span>
        </strong>
    </div>
    <form method="post" action="edit_servicio_vehiculo.php?id=<?php echo (int) $e_servicio['id_rel_servicios_vehiculos']; ?>" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tipo_servicio">Tipo de Servicio</label>
                    <input class="form-control" type="text" name="tipo_servicio" value="<?php echo $e_servicio['tipo_servicio']?>">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="fecha_servicio">Fecha del Servicio</label>
                    <input type="date" class="form-control" name="fecha_servicio" value="<?php echo $e_servicio['fecha_servicio'];?>">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="km_al_servicio">Km. Antes del servicio</label>
                    <input class="form-control" type="text" name="km_al_servicio" value="<?php echo $e_servicio['km_al_servicio'];?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="km_prox_servicio">Km. Próximo Servicio</label>
                    <input class="form-control" type="text" name="km_prox_servicio" value="<?php echo $e_servicio['km_prox_servicio'];?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea type="text" class="form-control" name="observaciones" id="observaciones" cols="30" rows="4" value="<?php echo $e_servicio['observaciones']?>"><?php echo $e_servicio['observaciones']?></textarea>
                </div>
            </div>
        </div>
        <div class="form-group clearfix" style="margin-top: 15px;">
            <a href="servicio_vehiculo.php?id=<?php echo $e_servicio['id_rel_servicios_vehiculos']; ?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="edit_servicio_vehiculo" class="btn btn-primary" value="subir">Guardar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>