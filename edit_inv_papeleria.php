<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Articulo';
error_reporting(E_ALL ^ E_NOTICE);
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];
$detalle = $user['id_user'];

$e_papeleria = find_by_id('compras_inv', (int)$_GET['id'], 'id_compra_inv');

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

if (isset($_POST['edit_inv_papeleria'])) {

    if (empty($errors)) {
        $id_inv_papeleria = (int)$_GET['id'];
        $marca = remove_junk($db->escape($_POST['marca']));
        $fecha_compra = remove_junk($db->escape($_POST['fecha_compra']));
        $precio_unitario = $db->escape($_POST['precio_unitario']);
        $precio_unitario1 = str_replace("$", "", $precio_unitario);
        $observaciones = remove_junk($db->escape($_POST['observaciones']));

        $sql = "UPDATE compras_inv SET marca='{$marca}', fecha_compra='{$fecha_compra}', precio_unitario='{$precio_unitario1}', observaciones='{$observaciones}'
                WHERE id_compra_inv = '{$id_inv_papeleria}'";
        $result = $db->query($sql);

        if ($result && $db->affected_rows() === 1) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó el articulo de papelería con id:' . $id_inv_papeleria, 2);
            $session->msg('s', " El artículo ha sido actualizado con éxito.");
            redirect('edit_inv_papeleria.php?id=' . (int)$e_papeleria['id_compra_inv'], false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron cambios a la información.');
            redirect('edit_inv_papeleria.php?id=' . (int)$e_papeleria['id_compra_inv'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_inv_papeleria.php?id=' . (int)$e_papeleria['id_compra_inv'], false);
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
                <span style="font-size: 17px;">Editar Artículo</span>
            </strong>
        </div>

        <div class="panel-body">
            <form method="post" action="edit_inv_papeleria.php?id=<?php echo (int)$e_papeleria['id_compra_inv']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="marca">Marca</label>
                            <input type="text" class="form-control" name="marca" value="<?php echo $e_papeleria['marca']; ?>">
                        </div>
                    </div>
                    <?php
                    $monto_comp = "$" . ($e_papeleria['precio_unitario'] == '' ? "0.00" : $e_papeleria['precio_unitario']);
                    ?>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="precio_unitario">Precio Unitario</label>
                            <input type="text" class="form-control" name="precio_unitario" id="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" value="<?php echo $monto_comp; ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_compra">Fecha de Compra</label>
                            <input type="date" class="form-control" name="fecha_compra" value="<?php echo $e_papeleria['fecha_compra']; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" cols="30" rows="3" value="<?php echo $e_papeleria['observaciones'] ?>"><?php echo $e_papeleria['observaciones'] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="inventario_papeleria.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="edit_inv_papeleria" class="btn btn-primary" value="subir">Guardar</button>
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