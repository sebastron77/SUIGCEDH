<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Préstamo de Vehículo';
error_reporting(E_ALL ^ E_NOTICE);
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];
$detalle = $user['id_user'];

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
if (isset($_POST['add_prestamo_vehiculo'])) {


    if (empty($errors)) {
        $id_detalle_usuario   = remove_junk($db->escape($_POST['id_detalle_usuario']));
        $id_vehiculo   = remove_junk($db->escape($_POST['id_vehiculo']));
        $id_area   = remove_junk($db->escape($_POST['id_area']));
        $fecha_hora_presta   = remove_junk($db->escape($_POST['fecha_hora_presta']));
        $fecha_hora_regresa   = remove_junk($db->escape($_POST['fecha_hora_regresa']));
        $km_inicial   = remove_junk($db->escape($_POST['km_inicial']));
        $km_final   = remove_junk($db->escape($_POST['km_final']));
        $motivo_prestamo   = remove_junk($db->escape($_POST['motivo_prestamo']));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));

        date_default_timezone_set('America/Mexico_City');
        $creacion = date('Y-m-d');


        $query = "INSERT INTO rel_prestamo_vehiculo (";
        $query .= "id_detalle_usuario, id_vehiculo, id_area, fecha_hora_presta, fecha_hora_regresa, km_inicial, km_final, motivo_prestamo, observaciones,
                    usuario_creador, fecha_creacion";
        $query .= ") VALUES (";
        $query .= " '{$id_detalle_usuario}', '{$id_vehiculo}', '{$id_area}', '{$fecha_hora_presta}', '{$fecha_hora_regresa}', '{$km_inicial}', '{$km_final}', 
                    '{$motivo_prestamo}', '{$observaciones}', '{$id_user}', '{$creacion}'";
        $query .= ")";

        $query2 = "UPDATE vehiculos SET estatus = '0' WHERE id_vehiculo = '{$id_vehiculo}'";

        if ($db->query($query) && $db->query($query2)) {
            //sucess
            $session->msg('s', " El préstamo ha sido registrado con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó préstamo vehiculo: ' . $id_vehiculo . ' al trabajador ' . $id_detalle_usuario . '.', 1);
            redirect('prestamo_vehiculos.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar el préstamo.');
            redirect('add_prestamo_vehiculo.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_prestamo_vehiculo.php', false);
    }
}
?>

<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<?php

?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Préstamo de Vehículo</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_prestamo_vehiculo.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_vehiculo">Vehículos</label>
                            <select class="form-control" name="id_vehiculo" id="id_vehiculo" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_vehiculos as $vehiculo) : ?>
                                    <?php if (($vehiculo['estatus'] != '') || ($vehiculo['estatus'] != 0)) : ?>
                                        <option value="<?php echo $vehiculo['id_vehiculo']; ?>"><?php echo ucwords($vehiculo['marca'] . ' ' . $vehiculo['modelo'] . ' (' . $vehiculo['color'] . ')'); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_area">Área del Responsable</label>
                            <select class="form-control" id="id_area_asignadaV" name="id_area" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($area as $a) : ?>
                                    <option value="<?php echo $a['id_area']; ?>"><?php echo ucwords($a['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <?php $trabajadores = find_all_trabajadores_areaGral($a['id_area']) ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_detalle_usuario">Responsable del Préstamo</label>
                            <select class="form-control" id="id_user_asignadoV" name="id_detalle_usuario" required></select>
                        </div>
                    </div>
                    <script>
                        $(function() {
                            $("#id_area_asignadaV").on("change", function() {
                                var variable = $(this).val();
                                $("#selected").html(variable);
                            })

                        });
                        $(function() {
                            $("#id_user_asignadoV").on("change", function() {
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
                            <input type="datetime-local" class="form-control" name="fecha_hora_presta">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_hora_regresa">Devolución del Vehículo</label>
                            <input type="datetime-local" class="form-control" name="fecha_hora_regresa">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="km_inicial">Km. Inicial</label>
                            <input type="number" class="form-control" name="km_inicial">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="km_final">Km. Final</label>
                            <input type="number" class="form-control" name="km_final">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="color">Motivo del Préstamo</label>
                            <textarea class="form-control" name="motivo_prestamo"></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="color">Observaciones</label>
                            <textarea class="form-control" name="observaciones"></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="prestamo_vehiculos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_prestamo_vehiculo" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function validateLength(input) {
        if (input.value.length > 4) {
            input.value = input.value.slice(0, 4);
        }
    }
</script>
<?php include_once('layouts/footer.php'); ?>