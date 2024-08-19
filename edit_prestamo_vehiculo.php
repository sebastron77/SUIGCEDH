<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Préstamo de Vehículo';
error_reporting(E_ALL ^ E_NOTICE);
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];
$detalle = $user['id_user'];

$e_prestamo = find_by_id('rel_prestamo_vehiculo', (int)$_GET['id'], 'id_rel_prestamo_vehiculo');
$cat_combustible = find_all_order('cat_combustible', 'descripcion');
$prestamo = find_by_id_prestamo((int) $_GET['id']);
$cat_vehiculos = find_all_order('vehiculos', 'marca');
$area = find_all_order('area', 'nombre_area');

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
if ($nivel_user > 14) {
    redirect('home.php');
}
if (!$nivel_user) {
    redirect('home.php');
}

$id_user = $user['id_user'];
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_prestamo_vehiculo'])) {

    if (empty($errors)) {
        $id_prestamo = $e_prestamo['id_rel_prestamo_vehiculo'];
        $id_detalle_usuario   = remove_junk($db->escape($_POST['id_detalle_usuario']));
        $id_vehiculo   = remove_junk($db->escape($_POST['id_vehiculo']));
        $id_area   = remove_junk($db->escape($_POST['id_area']));
        $fecha_hora_presta   = remove_junk($db->escape($_POST['fecha_hora_presta']));
        $fecha_hora_regresa   = remove_junk($db->escape($_POST['fecha_hora_regresa']));
        $km_inicial   = remove_junk($db->escape($_POST['km_inicial']));
        $km_final   = remove_junk($db->escape($_POST['km_final']));
        $motivo_prestamo   = remove_junk($db->escape($_POST['motivo_prestamo']));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));

        $sql = "UPDATE rel_prestamo_vehiculo SET id_detalle_usuario='{$id_detalle_usuario}', id_vehiculo='{$id_vehiculo}', id_area='{$id_area}', 
                fecha_hora_presta='{$fecha_hora_presta}', fecha_hora_regresa='{$fecha_hora_regresa}',  km_inicial='{$km_inicial}', km_final='{$km_final}', 
                motivo_prestamo='{$motivo_prestamo}', observaciones='{$observaciones}'
                WHERE id_rel_prestamo_vehiculo = '{$db->escape($id_prestamo)}'";

        if ($$e_prestamo['id_vehiculo'] != $id_vehiculo) {
            $sql2 = "UPDATE vehiculos SET estatus = '1' WHERE id_vehiculo = '{$e_prestamo['id_vehiculo']}'";
            $sql3 = "UPDATE vehiculos SET estatus = '0' WHERE id_vehiculo = '{$id_vehiculo}'";
            $result2 = $db->query($sql2);
            $result3 = $db->query($sql3);
        }

        $result = $db->query($sql);

        if (($result && $db->affected_rows() === 1) || (($result && $db->affected_rows() === 1) && ($result2 && $db->affected_rows() === 1) && ($result3 && $db->affected_rows() === 1))) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó el préstamo de vehiculo con id:' . $id_prestamo, 2);
            $session->msg('s', " El préstamo ha sido actualizado con éxito.");
            redirect('edit_prestamo_vehiculo.php?id=' . (int)$e_prestamo['id_rel_prestamo_vehiculo'], false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron cambios a la información.');
            redirect('edit_prestamo_vehiculo.php?id=' . (int)$e_prestamo['id_rel_prestamo_vehiculo'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_prestamo_vehiculo.php?id=' . (int)$e_prestamo['id_rel_prestamo_vehiculo'], false);
    }
}
?>

<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="row">
    <div class="panel panel-heading">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span style="font-size: 17px;">Editar Vehículo</span>
            </strong>
        </div>

        <div class="panel-body">
            <form method="post" action="edit_prestamo_vehiculo.php?id=<?php echo (int)$e_prestamo['id_rel_prestamo_vehiculo']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_vehiculo">Vehículos</label>
                            <select class="form-control" name="id_vehiculo" id="id_vehiculo" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_vehiculos as $vehiculo) : ?>
                                    <?php if (($vehiculo['estatus'] != '') || ($vehiculo['estatus'] != 0)) : ?>
                                        <option <?php if ($vehiculo['id_vehiculo'] === $e_prestamo['id_vehiculo']) echo 'selected="selected"'; ?> value="<?php echo $vehiculo['id_vehiculo']; ?>">
                                            <?php echo ucwords($vehiculo['marca'] . ' ' . $vehiculo['modelo'] . ' (' . $vehiculo['color'] . ')'); ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_area_asignada">Área del Responsable</label>
                            <select class="form-control" id="id_area_asignada" name="id_area" required>
                                <?php foreach ($area as $a) : ?>
                                    <option <?php if ($a['id_area'] === $e_prestamo['id_area']) echo 'selected="selected"'; ?> value="<?php echo $a['id_area']; ?>"><?php echo ucwords($a['nombre_area']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <?php $asigna_a = find_all_trabajadores_area($e_prestamo['id_area']) ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_user_asignado">Responsable del Préstamo</label>
                            <select class="form-control" id="id_user_asignado" name="id_detalle_usuario" required>
                                <?php foreach ($asigna_a as $asigna) : ?>
                                    <option <?php if ($asigna['id_det_usuario'] === $e_prestamo['id_detalle_usuario'])
                                                echo 'selected="selected"'; ?> value="<?php echo $asigna['id_det_usuario']; ?>">
                                        <?php echo ucwords($asigna['nombre'] . " " . $asigna['apellidos']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <script>
                        $(function() {
                            $("#id_area_asignada").on("change", function() {
                                var variable = $(this).val();
                                $("#selected").html(variable);
                            })

                        });
                        $(function() {
                            $("#id_user_asignado").on("change", function() {
                                var variable2 = $(this).val();
                                $("#selected2").html(variable2);
                            })
                        });
                    </script>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_hora_presta">Retiro del Vehículo</label>
                            <input type="datetime-local" class="form-control" name="fecha_hora_presta" value="<?php echo $e_prestamo['fecha_hora_presta'] ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_hora_regresa">Devolución del Vehículo</label>
                            <input type="datetime-local" class="form-control" name="fecha_hora_regresa" value="<?php echo $e_prestamo['fecha_hora_regresa'] ?>">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="km_inicial">Km. Inicial</label>
                            <input type="number" class="form-control" name="km_inicial" value="<?php echo $e_prestamo['km_inicial'] ?>">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="km_final">Km. Final</label>
                            <input type="number" class="form-control" name="km_final" value="<?php echo $e_prestamo['km_final'] ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="color">Motivo del Préstamo</label>
                            <textarea class="form-control" name="motivo_prestamo" value="<?php echo $e_prestamo['motivo_prestamo'] ?>"><?php echo $e_prestamo['motivo_prestamo'] ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="color">Observaciones</label>
                            <textarea class="form-control" name="observaciones" value="<?php echo $e_prestamo['observaciones'] ?>"><?php echo $e_prestamo['observaciones'] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="prestamo_vehiculos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="edit_prestamo_vehiculo" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>