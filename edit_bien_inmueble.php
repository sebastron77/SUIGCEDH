<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Inmueble';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

$e_inmueble = find_by_id('bienes_inmuebles', (int)$_GET['id'], 'id_bien_inmueble');

$denominaciones = find_all_order('cat_denom_inmueble', 'descripcion', 'ASC');
$tipos_inmuebles = find_all_order('cat_tipo_inmueble', 'descripcion', 'ASC');
$origen_propiedades = find_all_order('cat_origen_propiedad', 'descripcion', 'ASC');
$titulos_posesion = find_all_order('cat_titulo_posesion', 'descripcion', 'ASC');
$areas = find_all_order('area', 'nombre_area', 'ASC');
$municipios = find_all_order('cat_municipios', 'descripcion', 'ASC');

if (!$e_inmueble) {
    $session->msg("d", "id de inmueble no encontrado.");
    redirect('bienes_inmuebles.php');
}

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

if (isset($_POST['edit_bien_inmueble'])) {
    if (empty($errors)) {
        $id = (int)$e_inmueble['id_bien_inmueble'];
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

        $folio_carpeta = str_replace("/", "-", $e_inmueble['folio']);
        $carpeta = 'uploads/inmuebles/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['adjunto']['name'];
        $size = $_FILES['adjunto']['size'];
        $type = $_FILES['adjunto']['type'];
        $temp = $_FILES['adjunto']['tmp_name'];

        $move = move_uploaded_file($temp, $carpeta . "/" . $name);

        $sql = "UPDATE bienes_inmuebles SET id_cat_denom_inmueble='{$denom_inmueble}', fecha_adquisicion='{$fecha_adquisicion}', calle_num='{$calle_num}',
                colonia='{$colonia}', cod_pos='{$cod_pos}', id_cat_mun='{$municipio}', localidad='{$localidad}', id_cat_tipo_inmueble='{$tipo_inmueble}',
                valor_catastral='{$valor_catastral}', id_cat_origen_propiedad='{$origen_propiedad}', id_cat_titulo_posesion='{$titulo_posesion}', 
                id_area='{$area_responsable}', observaciones='{$observaciones}'" . ($name != '' ? ",documento_posesion='{$name}' " : "") .
            "WHERE id_bien_inmueble='{$db->escape($id)}'";

        $result = $db->query($sql);

        if ($result && $db->affected_rows() === 1) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó el Inmueble con Folio: -' . $e_inmueble['folio'], 2);
            $session->msg('s', " El Inmueble con folio '" . $e_inmueble['folio'] . "' ha sido acuatizado con éxito.");
            redirect('bienes_inmuebles.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron cambios a la información.');
            redirect('edit_bien_inmueble.php?id=' . (int)$e_inmueble['id_bien_inmueble'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_bien_inmueble.php?id=' . (int)$e_inmueble['id_bien_inmueble'], false);
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
                <span>Editar Inmueble <?php echo $e_inmueble['folio']; ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_bien_inmueble.php?id=<?php echo (int)$e_inmueble['id_bien_inmueble']; ?>" enctype="multipart/form-data">
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
                                <option <?php if ($e_inmueble['id_cat_denom_inmueble'] == $denom['id_cat_denom_inmueble']) echo 'selected="selected"'; ?> value="<?php echo $denom['id_cat_denom_inmueble']; ?>"><?php echo ucwords($denom['descripcion']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fecha_adquisicion">Fecha de Adquisición <span style="color: red; font-weight: bold">*</span></label>
                        <input type="date" class="form-control" name="fecha_adquisicion" value="<?php echo $e_inmueble['fecha_adquisicion'] ?>" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="tipo_inmueble">Tipo de Inmueble <span style="color: red; font-weight: bold">*</span></label>
                        <select class="form-control" name="tipo_inmueble" required>
                            <option value="">Escoge una opción</option>
                            <?php foreach ($tipos_inmuebles as $tipo_inm) : ?>
                                <option <?php if ($e_inmueble['id_cat_tipo_inmueble'] == $tipo_inm['id_cat_tipo_inmueble']) echo 'selected="selected"'; ?> value="<?php echo $tipo_inm['id_cat_tipo_inmueble']; ?>"><?php echo ucwords($tipo_inm['descripcion']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <?php $v1 = ($e_inmueble['valor_catastral'] == '' ? "0.00" : $e_inmueble['valor_catastral']); ?>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="valor_catastral">Valor Catastral <span style="color: red; font-weight: bold">*</span></label>
                        <input type="text" class="form-control" name="valor_catastral" id="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" value="<?php echo ($v1); ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="origen_propiedad">Origen de la Propiedad <span style="color: red; font-weight: bold">*</span></label>
                        <select class="form-control" name="origen_propiedad" required>
                            <option value="">Escoge una opción</option>
                            <?php foreach ($origen_propiedades as $origen) : ?>
                                <option <?php if ($e_inmueble['id_cat_origen_propiedad'] == $origen['id_cat_origen_propiedad']) echo 'selected="selected"'; ?> value="<?php echo $origen['id_cat_origen_propiedad']; ?>"><?php echo ucwords($origen['descripcion']); ?></option>
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
                                <option <?php if ($e_inmueble['id_cat_titulo_posesion'] == $titulos['id_cat_titulo_posesion']) echo 'selected="selected"'; ?> value="<?php echo $titulos['id_cat_titulo_posesion']; ?>"><?php echo ucwords($titulos['descripcion']); ?></option>
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
                            <option <?php if ($e_inmueble['id_area'] == $area['id_area']) echo 'selected="selected"'; ?> value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="adjunto">Adjuntar Documento de Posesión <span style="color: red; font-weight: bold">*</span></label>
                    <input type="file" accept="application/pdf" class="form-control" name="adjunto">
                    <?php
                    $folio_editar = $e_inmueble['folio'];
                    $resultado = str_replace("/", "-", $folio_editar);
                    ?>
                    <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                        <a target="_blank" href="/suigcedh/uploads/inmuebles/<?php echo $resultado . '/' . $e_inmueble['documento_posesion']; ?>" style="font-size:14px; color: #1248c7; text-decoration: underline;"><?php echo remove_junk($e_inmueble['documento_posesion']); ?></a>
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="3" value="<?php echo $e_inmueble['observaciones'] ?>"><?php echo $e_inmueble['observaciones'] ?></textarea>
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
                    <input type="text" class="form-control" name="calle_num" value="<?php echo $e_inmueble['calle_num'] ?>" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="colonia">Colonia <span style="color: red; font-weight: bold">*</span></label>
                    <input type="text" class="form-control" name="colonia" value="<?php echo $e_inmueble['colonia'] ?>" required>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label for="cod_pos">Código Postal <span style="color: red; font-weight: bold">*</span></label>
                    <input type="text" class="form-control" name="cod_pos" value="<?php echo $e_inmueble['cod_pos'] ?>" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="municipio">Municipio <span style="color: red; font-weight: bold">*</span></label>
                    <select class="form-control" name="municipio" required>
                        <option value="">Escoge una opción</option>
                        <?php foreach ($municipios as $mun) : ?>
                            <option <?php if ($e_inmueble['id_cat_mun'] == $mun['id_cat_mun']) echo 'selected="selected"'; ?> value="<?php echo $mun['id_cat_mun']; ?>"><?php echo ucwords($mun['descripcion']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="localidad">Localidad <span style="color: red; font-weight: bold">*</span></label>
                    <input type="text" class="form-control" name="localidad" value="<?php echo $e_inmueble['localidad']; ?>" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group clearfix">
                <a href="bienes_inmuebles.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
                <button type="submit" name="edit_bien_inmueble" class="btn btn-primary" value="subir">Guardar</button>
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
            // Join number by .
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