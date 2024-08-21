<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Reparaciones del Vehículo';
require_once('includes/load.php');

$idP =  (int)$_GET['id'];
$user = current_user();
$nivel_user = $user['user_level'];
$usuario = $user['id_user'];
$reparacion = find_by_id_consec_rep($idP);
$rel_reparaciones = find_all_by('rel_reparaciones_vehiculos', $idP, 'id_vehiculo');

$e_detalle = find_by_id('vehiculos', $idP, 'id_vehiculo');
if (!$e_detalle) {
    $session->msg("d", "id de vehiculo no encontrado.");
    redirect('control_vehiculos.php');
}

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
?>

<?php
if (isset($_POST['reparacion_vehiculo'])) {

    $id_vehiculo = $idP;
    $no_reparacion = $reparacion['no_reparacion'] + 1;
    $tipo_reparacion = $_POST['tipo_reparacion'];
    $fecha_reparacion = $_POST['fecha_reparacion'];
    $monto = $_POST['monto'];
    $monto1 = str_replace("$", "", $monto);
    $dias_en_taller = $_POST['dias_en_taller'];
    $observaciones = $_POST['observaciones'];
    date_default_timezone_set('America/Mexico_City');
    $fecha_creacion = date('Y-m-d');

    $query = "INSERT INTO rel_reparaciones_vehiculos (";
    $query .= "id_vehiculo, no_reparacion, tipo_reparacion, fecha_reparacion, monto, dias_en_taller, observaciones, user_creador, fecha_creacion";
    $query .= ") VALUES (";
    $query .= " '{$id_vehiculo}', '{$no_reparacion}', '{$tipo_reparacion}', '{$fecha_reparacion}', '{$monto1}', '{$dias_en_taller}', '{$observaciones}', 
                '{$usuario}', '{$fecha_creacion}'";
    $query .= ")";
    $db->query($query);
    insertAccion($user['id_user'], '"' . $user['username'] . '" agregó reparación de vehiculo:' . (int)$id_vehiculo, 1);
    redirect('reparacion_vehiculo.php?id='.$e_detalle['id_vehiculo'], false);
}
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
    <div class="col-md-6">
        <div class="panel login-page4" style="margin-left: 0%;">
            <div class="panel-heading">
                <strong style="font-size: 16px; font-family: 'Montserrat', sans-serif">
                    <span class="glyphicon glyphicon-th"></span>
                    REPARACIONES DEL VEHÍCULO: <?php echo upper_case(ucwords($e_detalle['marca'] . " " . $e_detalle['modelo'])); ?>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="reparacion_vehiculo.php?id=<?php echo (int)$e_detalle['id_vehiculo']; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipo_reparacion">Tipo de Reparación</label>
                                <input class="form-control" type="text" name="tipo_reparacion">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_reparacion">Fecha del Reparación</label>
                                <input type="date" class="form-control" name="fecha_reparacion">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="monto">Monto de Reparación</label>
                                <input type="text" class="form-control" name="monto" id="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="dias_en_taller">Días en taller</label>
                                <input class="form-control" type="number" min="0" name="dias_en_taller">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="observaciones">Observaciones</label>
                                <textarea type="text" class="form-control" name="observaciones" id="observaciones" cols="30" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <a href="control_vehiculos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="reparacion_vehiculo" class="btn btn-info">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 panel-body" style="height: 100%; margin-top: -5px;">
        <table class="table table-bordered table-striped" style="width: 100%; float: left;" id="tblProductos">
            <thead class="thead-purple" style="margin-top: -50px;">
                <tr style="height: 10px;">
                    <th colspan="7" style="text-align:center; font-size: 14px;">Servicios de <?php echo $e_detalle['marca'] . " " . $e_detalle['modelo'] ?></th>
                </tr>
                <tr style="height: 10px;">
                    <th class="text-center" style="width: 1%; font-size: 11.5px;">No. Reparación</th>
                    <th class="text-center" style="width: 10%; font-size: 11.5px;">Tipo Reparación</th>
                    <th class="text-center" style="width: 1%; font-size: 11.5px;">Monto Reparación</th>
                    <th class="text-center" style="width: 3%; font-size: 11.5px;">Fecha Reparación</th>
                    <th class="text-center" style="width: 3%; font-size: 11.5px;">Días en Taller</th>
                    <th class="text-center" style="width: 10%; font-size: 11.5px;">Observaciones</th>
                    <th class="text-center" style="width: 1%; font-size: 11.5px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rel_reparaciones as $serv) : ?>
                    <tr>
                        <td class="text-center" style="font-size: 14px;"><?php echo ucwords($serv['no_reparacion']) ?></td>
                        <td style="font-size: 14px;"><?php echo ucwords($serv['tipo_reparacion']) ?></td>
                        <td class="text-center" style="font-size: 14px;"><?php echo '$' . ucwords($serv['monto']) ?></td>
                        <td style="font-size: 14px;"><?php echo ucwords($serv['fecha_reparacion']) ?></td>
                        <td class="text-center" style="font-size: 14px;"><?php echo ucwords($serv['dias_en_taller']) ?></td>
                        <td style="font-size: 14px;"><?php echo ucwords($serv['observaciones']) ?></td>
                        <td style="font-size: 14px;" class="text-center">
                            <a href="edit_reparacion_vehiculo.php?id=<?php echo (int)$serv['id_rel_reparaciones_vehiculos']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 30px; width: 30px;">
                                <span class="material-symbols-rounded" style="font-size: 22px; color: black; margin-top: -1.5px; margin-left: -5px;">
                                    edit
                                </span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Jquery Dependency
    $("input[data-type='currency']").on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() {
            formatCurrency($(this), "blur");
        }
    });

    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }

    function formatCurrency(input, blur) {
        // Appends $ to value, validates decimal side and puts cursor back in right position.
        // Get input value
        var input_val = input.val();
        // Don't validate empty input
        if (input_val === "") {
            return;
        }
        // Original length
        var original_len = input_val.length;
        // Initial caret position 
        var caret_pos = input.prop("selectionStart");
        // Check for decimal
        if (input_val.indexOf(".") >= 0) {
            // Get position of first decimal this prevents multiple decimals from being entered
            var decimal_pos = input_val.indexOf(".");
            // Split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);
            // Add commas to left side of number
            left_side = formatNumber(left_side);
            // Validate right side
            right_side = formatNumber(right_side);
            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }
            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);
            // Jjoin number by .
            input_val = "$" + left_side + "." + right_side;
        } else {
            // No decimal entered, add commas to number, remove all non-digits
            input_val = formatNumber(input_val);
            input_val = "$" + input_val;
            // Final formatting
            if (blur === "blur") {
                input_val += ".00";
            }
        }
        // Send updated string to input
        input.val(input_val);
        // Put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }
</script>

<?php include_once('layouts/footer.php'); ?>