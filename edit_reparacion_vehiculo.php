<?php
$page_title = 'Editar Servicio';
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];
$idP =  (int)$_GET['id'];

$e_reparacion = find_by_id('rel_reparaciones_vehiculos', (int)$_GET['id'], 'id_rel_reparaciones_vehiculos');
$vehiculo = find_by_id('vehiculos', $e_reparacion['id_vehiculo'], 'id_vehiculo');

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

if (!$e_reparacion) {
    $session->msg("d", "La información no existe, verifique el ID.");
    redirect('edit_reparacion_vehiculo.php?id=' . (int)$e_reparacion['id_rel_reparaciones_vehiculos']);
}

?>
<?php
if (isset($_POST['edit_reparacion_vehiculo'])) {

    if (empty($errors)) {
        $tipo_reparacion = $_POST['tipo_reparacion'];
        $fecha_reparacion = $_POST['fecha_reparacion'];
        $monto = $_POST['monto'];
        $monto1 = str_replace("$", "", $monto);
        $dias_en_taller = $_POST['dias_en_taller'];
        $observaciones = $_POST['observaciones'];

        $query  = "UPDATE rel_reparaciones_vehiculos SET ";
        $query .= "tipo_reparacion='{$tipo_reparacion}', fecha_reparacion='{$fecha_reparacion}', monto='{$monto1}', dias_en_taller='{$dias_en_taller}', 
                    observaciones='{$observaciones}'";
        $query .= "WHERE id_rel_reparaciones_vehiculos='{$db->escape($e_reparacion['id_rel_reparaciones_vehiculos'])}'";
        $result = $db->query($query);

        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Información de la reparación ha sido actualizada.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó reparación del vehículo con id:' . (int)$e_reparacion['id_vehiculo'], 2);
            redirect('edit_reparacion_vehiculo.php?id=' . (int)$e_reparacion['id_rel_reparaciones_vehiculos'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado la información de la reparación, debido a que no hay cambios registrados en la descripción.');
            redirect('edit_reparacion_vehiculo.php?id=' . (int)$e_reparacion['id_rel_reparaciones_vehiculos'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_reparacion_vehiculo.php?id=' . (int)$e_reparacion['id_rel_reparaciones_vehiculos'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
<div class="row login-page6" style="width: 60%; height: 360px; margin-left: 15%; margin-top: 5%;">
    <div class="panel-heading" style="height: 11%">
        <strong>
            <span style="font-size: 16px;">EDITAR REPARACIÓN DE: <?php echo upper_case($vehiculo['marca'] . " " . $vehiculo['modelo']); ?></span>
        </strong>
    </div>
    <form method="post" action="edit_reparacion_vehiculo.php?id=<?php echo (int) $e_reparacion['id_rel_reparaciones_vehiculos']; ?>" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tipo_reparacion">Tipo de Reparación</label>
                    <input class="form-control" type="text" name="tipo_reparacion" value="<?php echo $e_reparacion['tipo_reparacion'] ?>">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="fecha_reparacion">Fecha del Reparación</label>
                    <input type="date" class="form-control" name="fecha_reparacion" value="<?php echo $e_reparacion['fecha_reparacion'] ?>">
                </div>
            </div>
            <?php
            $v1 = "$" . ($e_reparacion['monto'] == '' ? "0.00" : $e_reparacion['monto']);
            ?>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="monto">Monto de Reparación</label>
                    <input type="text" class="form-control" name="monto" id="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" value="<?php echo ($v1); ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="dias_en_taller">Días en taller</label>
                    <input class="form-control" type="number" min="0" name="dias_en_taller" value="<?php echo $e_reparacion['dias_en_taller'] ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea type="text" class="form-control" name="observaciones" id="observaciones" cols="30" rows="4" value="<?php echo $e_reparacion['observaciones'] ?>"><?php echo $e_reparacion['observaciones'] ?></textarea>
                </div>
            </div>
        </div>
        <div class="form-group clearfix" style="margin-top: 15px;">
            <a href="servicio_vehiculo.php?id=<?php echo $e_reparacion['id_rel_reparaciones_vehiculos']; ?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="edit_reparacion_vehiculo" class="btn btn-primary" value="subir">Guardar</button>
        </div>
    </form>
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