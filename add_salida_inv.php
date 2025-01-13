<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL ^ E_NOTICE);
require_once('includes/load.php');
$page_title = 'Agregar Salida de Inventario';
$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];
$categorias_articulos = find_all_order_by('cat_categorias_inv', 'descripcion', 'padre', 0);
// $tipos_articulos = find_all_order('cat_subcategorias_inv', 'descripcion');
$areas = find_all_order('area', 'nombre_area');

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
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_salida_inv'])) {
    if (empty($errors)) {

        $id_categoria_inv2 = $db->escape($_POST['id_categoria_inv2']);
        $id_categoria_inv3 = $db->escape($_POST['id_categoria_inv3']);
        $id_area_asigna = $db->escape($_POST['id_area']);
        $cantidad_salida = $db->escape($_POST['cantidad_salida']);
        $fecha_salida = $db->escape($_POST['fecha_salida']);
        if ($id_categoria_inv3 == '') {
            $id_categoria_inv = $id_categoria_inv2;
        } else {
            $id_categoria_inv = $id_categoria_inv3;
        }
        date_default_timezone_set('America/Mexico_City');
        $creacion = date('Y-m-d');

        $busqueda = find_by_id('stock_inv', $id_categoria_inv, 'id_categoria_inv');

        if (($busqueda['existencia'] != null) && ($cantidad_salida <= $busqueda['existencia'])) {
            $resta = $busqueda['existencia'] - $cantidad_salida;
        } else {
            // $resta = $busqueda['existencia'];
            $session->msg('d', 'No se pudo realizar la acción debido a que no se tiene la cantidad solicitada en el inventario.');
        }

        $query = "INSERT INTO rel_salidas_inv (";
        $query .= "id_categoria_inv, id_area_asigna, cantidad_salida, cantidad_anterior, fecha_salida, fecha_creacion, usuario_creador";
        $query .= ") VALUES (";
        $query .= " '{$id_categoria_inv}', '{$id_area_asigna}', '{$cantidad_salida}', '{$busqueda['existencia']}', '{$fecha_salida}', '{$creacion}', 
                    '{$id_user}'";
        $query .= ")";

        $update = "UPDATE stock_inv SET existencia = '{$resta}' WHERE id_categoria_inv = '{$db->escape($id_categoria_inv)}'";

        if ($db->query($query) && $db->query($update)) {
            //success
            $session->msg('s', " El artículo ha sido asignado con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" asignó articulo: (subcat: ' . $id_categoria_inv . ', cant.: ' . $cantidad_salida . ', área: ' . $id_area_asigna . ')', 1);
            redirect('solicitudes_inventario.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo asignar el articulo.');
            redirect('add_salida_inv.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_salida_inv.php', false);
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
                <span>Agregar Salida de Inventario</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_salida_inv.php" enctype="multipart/form-data">
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
                            <select class="form-control" id="id_categoria_inv3" name="id_categoria_inv3"></select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cantidad_salida">Cantidad</label>
                            <input type="number" min="1" max='<?php echo $busqueda['existencia']; ?>' class="form-control" name="cantidad_salida" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area">Área a la que se asigna</label>
                            <select class="form-control" id="id_area" name="id_area" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($areas as $area) : ?>
                                    <option value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
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
                            <label for="fecha_salida">Fecha de asignación</label>
                            <input type="date" class="form-control" name="fecha_salida">
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="salidas_inv.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_salida_inv" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Evento que detecta el cambio en el select y conocer el id del inventario que utilizará
    document.getElementById('id_categoria_inv').addEventListener('change', function() {
        mostrarInputs(this.value);
    });

    // Dependencia de Jquery para que al quitar el cursor de donde esta el input de moneda tome el formato que le corresponde
    $("input[data-type='currency']").on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() {
            formatCurrency($(this), "blur");
        }
    });
    // Función para darle formato de moneda al número que se coloque en el input
    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }

    // Función para obtener la cantidad que hay en stock de un artículo en el inventario, y detecta cuando hay un cambio en el input de los selects dinámicos
    $(document).ready(function() {
        // Detecta cambios en los selects de categoría y subcategoría
        $("#id_categoria_inv3, #id_categoria_inv2").on("change", function() {
            // Obtén los valores de ambos selects
            var idSubcategoria = $("#id_categoria_inv3").val();
            var idCategoria = $("#id_categoria_inv2").val();

            // Decide cuál usar (prioriza id_categoria_inv3)
            var idProducto = idSubcategoria || idCategoria;

            if (idProducto) {
                // Llama al servidor para obtener la cantidad en existencia
                $.ajax({
                    url: "get_existencia.php", // Cambia a la ruta correcta
                    type: "POST",
                    dataType: "json",
                    data: {
                        id_categoria_inv: idProducto
                    },
                    success: function(response) {
                        if (response.existencia !== undefined) {
                            // Actualiza el atributo max del input de cantidad
                            var existencia = response.existencia;

                            if (existencia > 0) {
                                // Si hay stock disponible
                                $("input[name='cantidad_salida']").attr("max", existencia);
                                $("input[name='cantidad_salida']").attr("min", 1);
                                $("input[name='cantidad_salida']").val("");
                                $("input[name='cantidad_salida']").attr(
                                    "placeholder",
                                    "Máximo: " + existencia
                                );
                                $("input[name='cantidad_salida']").prop("disabled", false);
                            } else {
                                // Si no hay stock disponible
                                $("input[name='cantidad_salida']").attr("max", 0);
                                $("input[name='cantidad_salida']").attr("min", 0);
                                $("input[name='cantidad_salida']").val("");
                                $("input[name='cantidad_salida']").attr(
                                    "placeholder",
                                    "Sin stock disponible."
                                );
                                $("input[name='cantidad_salida']").prop("disabled", true);
                            }
                        } else {
                            // Si el producto no existe
                            $("input[name='cantidad_salida']").attr("max", "");
                            $("input[name='cantidad_salida']").attr("min", "");
                            $("input[name='cantidad_salida']").val("");
                            $("input[name='cantidad_salida']").attr(
                                "placeholder",
                                "Producto no encontrado."
                            );
                            $("input[name='cantidad_salida']").prop("disabled", true);
                        }
                    },
                    error: function() {
                        alert("Error al obtener la existencia del producto.");
                    },
                });
            } else {
                // Si no hay producto seleccionado, restablece el max y limpia el input
                $("input[name='cantidad_salida']").attr("max", "");
                $("input[name='cantidad_salida']").attr("min", "");
                $("input[name='cantidad_salida']").val("");
                $("input[name='cantidad_salida']").attr("placeholder", "Seleccione un artículo");
                $("input[name='cantidad_salida']").prop("disabled", true);
            }
        });
    });
</script>
<?php include_once('layouts/footer.php'); ?>