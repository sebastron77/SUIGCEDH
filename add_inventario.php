<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL ^ E_NOTICE);
require_once('includes/load.php');
$page_title = 'Agregar a Inventario';
$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];
$cat_combustible = find_all_order('cat_combustible', 'descripcion');
$categorias_articulos = find_all_order_by('cat_categorias_inv', 'descripcion', 'padre', 0);
$tipos_articulos = find_all_order('cat_subcategorias_inv', 'descripcion');

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

?>
<style>
    .hidden {
        visibility: hidden;
        height: 0;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }
</style>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_inventario'])) {
    if (empty($errors)) {

        $id_categoria_inv2 = $db->escape($_POST['id_categoria_inv2']);
        $id_categoria_inv3 = $db->escape($_POST['id_categoria_inv3']);

        $marca = remove_junk($db->escape($_POST['marca']));
        $modelo = remove_junk($db->escape($_POST['modelo']));
        $no_serie = remove_junk($db->escape($_POST['no_serie']));
        $material = remove_junk($db->escape($_POST['material']));
        $especificaciones = remove_junk($db->escape($_POST['especificaciones']));
        $fecha_compra = remove_junk($db->escape($_POST['fecha_compra']));
        $cantidad_compra = remove_junk($db->escape($_POST['cantidad_compra']));
        $precio_unitario = $db->escape($_POST['precio_unitario']);
        $precio_unitario1 = str_replace("$", "", $precio_unitario);
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
        date_default_timezone_set('America/Mexico_City');
        $creacion = date('Y-m-d');

        if ($id_categoria_inv3 == '') {
            $id_categoria_inv = $id_categoria_inv2;
        } else {
            $id_categoria_inv = $id_categoria_inv3;
        }

        $busqueda = find_by_id('stock_inv', $id_categoria_inv, 'id_categoria_inv');
        
        if ($busqueda['existencia'] != null) {
            $suma = $busqueda['existencia'] + $cantidad_compra;
        } else {
            $suma = $cantidad_compra;
        }

        $query = "INSERT INTO stock_inv (";
        $query .= "id_categoria_inv, existencia, fecha_actualizacion, usuario_creador, fecha_creacion";
        $query .= ") VALUES (";
        $query .= " '{$id_categoria_inv}', '{$suma}', '{$creacion}', '{$id_user}', '{$creacion}') ";

        $query .= "ON DUPLICATE KEY UPDATE ";
        $query .= "existencia = VALUES(existencia), ";
        $query .= "fecha_actualizacion = VALUES(fecha_actualizacion)";

        $query2 = "INSERT INTO compras_inv (";
        $query2 .= "id_categoria_inv, marca, modelo, no_serie, material, especificaciones, fecha_compra, cantidad_compra, precio_unitario, 
                    observaciones, usuario_creador, fecha_creacion";
        $query2 .= ") VALUES (";
        $query2 .= " '{$id_categoria_inv}', '{$marca}', '{$modelo}', '{$no_serie}', '{$material}', '{$especificaciones}', '{$fecha_compra}', 
                        '{$cantidad_compra}', '{$precio_unitario1}', '{$observaciones}', '{$id_user}', '{$creacion}'";
        $query2 .= ")";

        if ($db->query($query) && $db->query($query2)) {
            //sucess
            $session->msg('s', " El artículo ha sido agregado al inventario con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó articulo: (subcat: ' . $id_categoria_inv . ', cant.: ' . $cantidad_compra . ', marca: ' . $marca . ')', 1);
            redirect('solicitudes_inventario.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar el articulo al inventario.');
            redirect('add_inventario.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_inventario.php', false);
    }
}
?>

<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Articulo a Inventario</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_inventario.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_categoria_inv">Categoría del Artículo</label>
                            <select class="form-control" id="id_categoria_inv" name="id_categoria_inv" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($categorias_articulos as $c_articulo) : ?>
                                    <option value="<?php echo $c_articulo['id_categoria_inv']; ?>"><?php echo ucwords($c_articulo['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_categoria_inv2">Subcategoría del Artículo / Artículo</label>
                            <select class="form-control" id="id_categoria_inv2" name="id_categoria_inv2" required></select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_categoria_inv3">Artículo</label>
                            <select class="form-control" id="id_categoria_inv3" name="id_categoria_inv3" ></select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="cantidad_compra">Cantidad</label>
                            <input type="number" min="1" class="form-control" name="cantidad_compra" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="precio_unitario">Precio Unitario</label>
                            <input type="text" class="form-control" name="precio_unitario" id="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_compra">Fecha de Compra</label>
                            <input type="date" class="form-control" name="fecha_compra">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" cols="30" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <script>
                    //CATEGORÍA
                    $(function() {
                        $("#id_categoria_inv").on("change", function() {
                            var variable = $(this).val();
                            $("#selected").html(variable);
                            
                        })

                    });
                    //SUBCATEGORÍA
                    $(function() {
                        $("#id_categoria_inv").on("change", function() {
                            var variable2 = $(this).val();
                            $("#selected2").html(variable2);
                        })
                    });
                </script>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="marca">Marca</label>
                            <input type="text" class="form-control" name="marca">
                        </div>
                    </div>
                    <div id="input1" class="col-md-10 hidden">
                        <div class="col-md-3" id="input5">
                            <div class="form-group" style="margin-left: -15px;">
                                <label for="modelo">Modelo</label>
                                <input type="text" class="form-control" name="modelo">
                            </div>
                        </div>
                        <div class="col-md-3" id="input4">
                            <div class="form-group" style="margin-left: -15px;">
                                <label for="no_serie">No. Serie</label>
                                <input type="text" class="form-control" name="no_serie">
                            </div>
                        </div>
                        <div class="col-md-3" id="input2">
                            <div class="form-group" style="margin-left: -15px;">
                                <label for="material">Material</label>
                                <input type="text" class="form-control" name="material">
                            </div>
                        </div>
                        <div class="col-md-4" id="input3">
                            <div class="form-group" style="margin-left: -15px;">
                                <label for="especificaciones">Especificaciones</label>
                                <textarea class="form-control" name="especificaciones" cols="30" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="solicitudes_inventario.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_inventario" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Función para mostrar y ocultar inputs
    function mostrarInputs(valorSeleccionado) {
        // Ocultar todos los inputs
        document.querySelectorAll('.hidden').forEach(input => {
            input.classList.add('hidden');
        });

        // Mostrar input según la selección
        if (valorSeleccionado === "1") { //Oculta modelo, no_serie, material y especificaciones
            document.getElementById('input1').classList.add('hidden');
            // document.getElementById('input3').classList.add('hidden');
        }
        if (valorSeleccionado === "2") { //Muestra material y oculta especificaciones, no_serie y modelo
            document.getElementById('input1').classList.remove('hidden');
            document.getElementById('input2').classList.remove('hidden');
            document.getElementById('input3').classList.add('hidden');
            document.getElementById('input4').classList.add('hidden');
            document.getElementById('input5').classList.add('hidden');
        }
        if (valorSeleccionado === "3") { //Oculta material y muestra, no_serie, modelo y especificaciones
            document.getElementById('input1').classList.remove('hidden');
            document.getElementById('input2').classList.add('hidden');
            document.getElementById('input3').classList.remove('hidden');
            document.getElementById('input4').classList.remove('hidden');
            document.getElementById('input5').classList.remove('hidden');
        }
        if (valorSeleccionado === "4") { //Oculta modelo, no_serie, material y muestra especificaciones
            document.getElementById('input1').classList.remove('hidden');
            document.getElementById('input2').classList.add('hidden');
            document.getElementById('input3').classList.remove('hidden');
            document.getElementById('input4').classList.add('hidden');
            document.getElementById('input5').classList.add('hidden');
        }
        if (valorSeleccionado === "5") { //Oculta modelo, no_serie, material y especificaciones
            document.getElementById('input1').classList.add('hidden');
            // document.getElementById('input3').classList.add('hidden');
        }
        if (valorSeleccionado === "6") { //Oculta modelo, no_serie, especificaciones y muestra material
            document.getElementById('input1').classList.remove('hidden');
            document.getElementById('input2').classList.add('hidden');
            document.getElementById('input3').classList.remove('hidden');
            document.getElementById('input4').classList.remove('hidden');
            document.getElementById('input5').classList.remove('hidden');
        }
    }

    // Evento que detecta el cambio en el select
    document.getElementById('id_categoria_inv').addEventListener('change', function() {
        mostrarInputs(this.value);
    });

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