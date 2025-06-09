<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Inmueble';
require_once('includes/load.php');

$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$id_folio = last_id_folios();

$denominaciones = find_all_order('cat_denom_inmueble', 'descripcion', 'ASC');
$tipos_inmuebles = find_all_order('cat_tipo_inmueble', 'descripcion', 'ASC');
$origen_propiedades = find_all_order('cat_origen_propiedad', 'descripcion', 'ASC');
$titulos_posesion = find_all_order('cat_titulo_posesion', 'descripcion', 'ASC');
$areas = find_all_order('area', 'nombre_area', 'ASC');
$municipios = find_all_order('cat_municipios', 'descripcion', 'ASC');

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}
if ($nivel_user == 2) {
    page_require_level_exacto(2);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 28) {
    page_require_level_exacto(28);
}
if ($nivel_user == 29) {
    page_require_level_exacto(29);
}
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 28) :
    redirect('home.php');
endif;
if ($nivel_user > 29) {
    redirect('home.php');
}
if (!$nivel_user) {
    redirect('home.php');
}
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_bien_inmueble'])) {
    if (empty($errors)) {

        $denom_inmueble = remove_junk($db->escape($_POST['denom_inmueble']));
        $fecha_adquisicion = remove_junk($db->escape($_POST['fecha_adquisicion']));
        $calle_num = remove_junk($db->escape($_POST['calle_num']));
        $colonia = remove_junk($db->escape($_POST['colonia']));
        $cod_pos = remove_junk($db->escape($_POST['cod_pos']));
        $municipio = remove_junk($db->escape($_POST['municipio']));
        $localidad = remove_junk($db->escape($_POST['localidad']));
        $tipo_inmueble = remove_junk($db->escape($_POST['tipo_inmueble']));
        $valor_catastral = remove_junk($db->escape($_POST['valor_catastral']));
        $origen_propiedad = remove_junk($db->escape($_POST['origen_propiedad']));
        $titulo_posesion = remove_junk($db->escape($_POST['titulo_posesion']));
        $area_responsable = remove_junk($db->escape($_POST['area_responsable']));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
        $fecha_creacion = date('Y-m-d');

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int) $nuevo['contador'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['contador'] + 1);
            }
        }
        $year = date("Y");
        $folio = 'CEDH/' . $no_folio . '/' . $year . '-INM';
        $folio_carpeta = 'CEDH-' . $no_folio . '-' . $year . '-INM';
        $carpeta = 'uploads/inmuebles/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['adjunto']['name'];
        $size = $_FILES['adjunto']['size'];
        $type = $_FILES['adjunto']['type'];
        $temp = $_FILES['adjunto']['tmp_name'];

        $move = move_uploaded_file($temp, $carpeta . "/" . $name);

        /*Creo archivo index para que no se muestre el Index Of*/
        $source = 'uploads/index.php';
        if (copy($source, $carpeta . '/index.php')) {
            echo "El archivo ha sido copiado exitosamente.";
        } else {
            echo "Ha ocurrido un error al copiar el archivo.";
        }

        if ($move && $name != '') {
            $query = "INSERT INTO bienes_inmuebles (";
            $query .= "folio, id_cat_denom_inmueble, fecha_adquisicion, calle_num, colonia, cod_pos, id_cat_mun, localidad, id_cat_tipo_inmueble, 
                        valor_catastral, id_cat_origen_propiedad, id_cat_titulo_posesion, documento_posesion, id_area, observaciones, fecha_creacion, 
                        user_creador";
            $query .= ") VALUES (";
            $query .= " '{$folio}', '{$denom_inmueble}', '{$fecha_adquisicion}', '{$calle_num}', '{$colonia}', '{$cod_pos}', '{$municipio}', '{$localidad}',
                        '{$tipo_inmueble}', '{$valor_catastral}', '{$origen_propiedad}', '{$titulo_posesion}', '{$name}', '{$area_responsable}', 
                        '{$observaciones}', '{$fecha_creacion}', {$id_user})";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio}'";
            $query2 .= ")";

            if ($db->query($query) && $db->query($query2)) {
                //sucess
                insertAccion($user['id_user'], '"' . $user['username'] . '" agregó Bien Inmueble con folio: -' . $folio, 1);
                $session->msg('s', " El Inmueble con folio '{$folio}' ha sido agregado con éxito.");
                redirect('bienes_inmuebles.php', false);
            } else {
                //failed
                $session->msg('d', ' No se pudo agregar el Inmueble.');
                redirect('add_bien_inmueble.php', false);
            }
        } else {
            $session->msg("d", "Error en el nombre del archivo");
            redirect('add_bien_inmueble.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_bien_inmueble.php', false);
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
                <span>Agregar Bien Inmueble</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_bien_inmueble.php" enctype="multipart/form-data">
                <span style="display: inline-flex; align-items: center; font-size: 18px; font-weight: bold;">
                    <span class="material-symbols-outlined" style="margin-right: 5px; font-size: 30px;">
                        dashboard
                    </span>
                    Datos Generales
                </span>
                <div class="row" style="margin-top: 15px;"">
                    <div class=" col-md-2">
                    <div class="form-group">
                        <label for="denom_inmueble">Denominación del Inmueble <span style="color: red; font-weight: bold">*</span></label>
                        <select class="form-control" name="denom_inmueble" required>
                            <option value="">Escoge una opción</option>
                            <?php foreach ($denominaciones as $denom) : ?>
                                <option value="<?php echo $denom['id_cat_denom_inmueble']; ?>"><?php echo ucwords($denom['descripcion']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fecha_adquisicion">Fecha de Adquisición <span style="color: red; font-weight: bold">*</span></label>
                        <input type="date" class="form-control" name="fecha_adquisicion" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="tipo_inmueble">Tipo de Inmueble <span style="color: red; font-weight: bold">*</span></label>
                        <select class="form-control" name="tipo_inmueble" required>
                            <option value="">Escoge una opción</option>
                            <?php foreach ($tipos_inmuebles as $tipo_inm) : ?>
                                <option value="<?php echo $tipo_inm['id_cat_tipo_inmueble']; ?>"><?php echo ucwords($tipo_inm['descripcion']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="valor_catastral">Valor Catastral <span style="color: red; font-weight: bold">*</span></label>
                        <input type="text" class="form-control" name="valor_catastral" id="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="origen_propiedad">Origen de la Propiedad <span style="color: red; font-weight: bold">*</span></label>
                        <select class="form-control" name="origen_propiedad" required>
                            <option value="">Escoge una opción</option>
                            <?php foreach ($origen_propiedades as $origen) : ?>
                                <option value="<?php echo $origen['id_cat_origen_propiedad']; ?>"><?php echo ucwords($origen['descripcion']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="titulo_posesion">Título de Posesión <span style="color: red; font-weight: bold">*</span></label>
                        <select class="form-control" name="titulo_posesion" required>
                            <option value="">Escoge una opción</option>
                            <?php foreach ($titulos_posesion as $titulos) : ?>
                                <option value="<?php echo $titulos['id_cat_titulo_posesion']; ?>"><?php echo ucwords($titulos['descripcion']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="area_responsable">Área Responsable <span style="color: red; font-weight: bold">*</span></label>
                    <select class="form-control" name="area_responsable" required>
                        <option value="">Escoge una opción</option>
                        <?php foreach ($areas as $area) : ?>
                            <option value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="adjunto">Adjuntar Documento de Posesión <span style="color: red; font-weight: bold">*</span></label>
                    <input type="file" accept="application/pdf" class="form-control" name="adjunto" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="3"></textarea>
                </div>
            </div>
        </div>
        <span style="display: inline-flex; align-items: center; font-size: 18px; font-weight: bold;">
            <span class="material-symbols-outlined" style="margin-right: 5px; font-size: 35px;">
                home_pin
            </span>
            Domicilio del Inmueble
        </span>
        <div class="row" style="margin-top: 15px;">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="calle_num">Calle y número <span style="color: red; font-weight: bold">*</span></label>
                    <input type="text" class="form-control" name="calle_num" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="colonia">Colonia <span style="color: red; font-weight: bold">*</span></label>
                    <input type="text" class="form-control" name="colonia" required>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label for="cod_pos">Código Postal <span style="color: red; font-weight: bold">*</span></label>
                    <input type="text" class="form-control" name="cod_pos" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="municipio">Municipio <span style="color: red; font-weight: bold">*</span></label>
                    <select class="form-control" name="municipio" required>
                        <option value="">Escoge una opción</option>
                        <?php foreach ($municipios as $mun) : ?>
                            <option value="<?php echo $mun['id_cat_mun']; ?>"><?php echo ucwords($mun['descripcion']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="localidad">Localidad <span style="color: red; font-weight: bold">*</span></label>
                    <input type="text" class="form-control" name="localidad" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group clearfix">
                <a href="bienes_inmuebles.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
                <button type="submit" name="add_bien_inmueble" class="btn btn-primary" value="subir">Guardar</button>
            </div>
        </div>
        </form>
    </div>
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