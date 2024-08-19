<?php
$page_title = 'Editar Expediente Laboral';
require_once('includes/load.php');

page_require_level(1);
$areas = find_all_area_orden('area');
$tipo_int = find_all('cat_tipo_integrante');
?>
<?php
$e_detalle = find_by_id('detalles_usuario', (int)$_GET['id'], 'id_det_usuario');
if (!$e_detalle) {
    $session->msg("d", "id de usuario no encontrado.");
    redirect('detalles_usuario.php');
}
$user = current_user();
$nivel = $user['user_level'];
?>

<?php
if (isset($_POST['update'])) {
    if (empty($errors)) {
        $id = (int)$e_detalle['id_det_usuario'];
        $nombre   = $e_detalle['nombre'];
        $apellidos   = $e_detalle['apellidos'];
        $puesto   = $db->escape($_POST['puesto']);
        $area_adscripcion   = $db->escape($_POST['area_adscripcion']);
        $monto_bruto   = $db->escape($_POST['monto_bruto']);
        $monto_neto   = $db->escape($_POST['monto_neto']);
        $tipo_inte   = $db->escape($_POST['tipo_inte']);
        $clave   = $db->escape($_POST['clave']);
        $niv_puesto   = $db->escape($_POST['niv_puesto']);

        $carpeta_trabajador = $nombre . ' ' . $apellidos;
        $carpeta = 'uploads/personal/expediente/' . $carpeta_trabajador;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $nameActa = $_FILES['acta_nacimiento']['name'];
        $sizeActa = $_FILES['acta_nacimiento']['size'];
        $typeActa = $_FILES['acta_nacimiento']['type'];
        $tempActa = $_FILES['acta_nacimiento']['tmp_name'];
        $moveActa = move_uploaded_file($tempActa, $carpeta . "/" . $nameActa);

        $nameCartaAnt = $_FILES['carta_no_ant']['name'];
        $sizeCartaAnt = $_FILES['carta_no_ant']['size'];
        $typeCartaAnt = $_FILES['carta_no_ant']['type'];
        $tempCartaAnt = $_FILES['carta_no_ant']['tmp_name'];
        $moveCartaAnt = move_uploaded_file($tempCartaAnt, $carpeta . "/" . $nameCartaAnt);

        $nameConstIn = $_FILES['const_no_in']['name'];
        $sizeConstIn = $_FILES['const_no_in']['size'];
        $typeConstIn = $_FILES['const_no_in']['type'];
        $tempConstIn = $_FILES['const_no_in']['tmp_name'];
        $moveConstIn = move_uploaded_file($tempConstIn, $carpeta . "/" . $nameConstIn);

        $nameCompDom = $_FILES['comp_dom']['name'];
        $sizeCompDom = $_FILES['comp_dom']['size'];
        $typeCompDom = $_FILES['comp_dom']['type'];
        $tempCompDom = $_FILES['comp_dom']['tmp_name'];
        $moveCompDom = move_uploaded_file($tempCompDom, $carpeta . "/" . $nameCompDom);

        $nameCartaRec1 = $_FILES['carta_rec1']['name'];
        $sizeCartaRec1 = $_FILES['carta_rec1']['size'];
        $typeCartaRec1 = $_FILES['carta_rec1']['type'];
        $tempCartaRec1 = $_FILES['carta_rec1']['tmp_name'];
        $moveCartaRec1 = move_uploaded_file($tempCartaRec1, $carpeta . "/" . $nameCartaRec1);

        $nameCartaRec2 = $_FILES['carta_rec2']['name'];
        $sizeCartaRec2 = $_FILES['carta_rec2']['size'];
        $typeCartaRec2 = $_FILES['carta_rec2']['type'];
        $tempCartaRec2 = $_FILES['carta_rec2']['tmp_name'];
        $moveCartaRec2 = move_uploaded_file($tempCartaRec2, $carpeta . "/" . $nameCartaRec2);


        $sql = "UPDATE detalles_usuario SET   nombre='{$e_detalle['nombre']}' ";
        if ($nameActa !== '') {
            $sql .= ",acta_nacimiento='{$nameActa}'";
        }
        if ($nameCartaAnt !== '') {
            $sql .= ", carta_no_ant='{$nameCartaAnt}'";
        }
        if ($nameConstIn !== '') {
            $sql .= ", const_no_in='{$nameConstIn}'";
        }
        if ($nameCompDom !== '') {
            $sql .= ", comp_dom='{$nameCompDom}'";
        }
        if ($nameCartaRec1 !== '') {
            $sql .= ", carta_rec1='{$nameCartaRec1}'";
        }
        if ($nameCartaRec2 !== '') {
            $sql .= ", carta_rec2='{$nameCartaRec2}'";
        }

        if ($puesto !== '') {
            $sql .= ", puesto='{$puesto}'";
        }
        if ($area_adscripcion !== '') {
            $sql .= ", area_adscripcion='{$area_adscripcion}'";
        }
        if ($monto_bruto !== '') {
            $monto_solo1 = str_replace("$", "", $monto_bruto);
            // $monto_coma1 = str_replace(",", "", $monto_solo1);
            $sql .= ", monto_bruto='{$monto_solo1}'";
        }
        if ($monto_neto !== '') {
            $monto_solo2 = str_replace("$", "", $monto_neto);
            // $monto_coma2 = str_replace(",", "", $monto_solo2);
            $sql .= ", monto_neto='{$monto_solo2}'";
        }
        if ($tipo_inte !== '') {
            $sql .= ", tipo_inte='{$tipo_inte}'";
        }
        if ($clave !== '') {
            $sql .= ", clave='{$clave}'";
        }
        if ($niv_puesto !== '') {
            $sql .= ", niv_puesto='{$niv_puesto}'";
        }
        $sql .= " WHERE id_det_usuario='{$db->escape($id)}'";

        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Expediente Actualizado");
            insertAccion($user['id_user'], '"' . $user['username'] . '" actualizó expediente laboral al trabajador(a): ' . $nombre . ' ' . $apellidos . '.', 2);
            redirect('exp_general.php?id=' . (int)$e_detalle['id_det_usuario'], false);
        } else {
            $session->msg('d', ' Lo sentimos, no se pudo actualizar el expediente.' . $sql);
            redirect('exp_general.php?id=' . (int)$e_detalle['id_det_usuario'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('exp_general.php?id=' . (int)$e_detalle['id_det_usuario'], false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">

    <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    Expediente general: <?php echo (ucwords($e_detalle['nombre'])); ?> <?php echo ($e_detalle['apellidos']); ?>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="exp_general.php?id=<?php echo (int)$e_detalle['id_det_usuario']; ?>" class="clearfix" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="nombre" class="control-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" value="<?php echo ($e_detalle['nombre']); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="apellidos" class="control-label">Apellidos</label>
                                <input type="text" class="form-control" name="apellidos" value="<?php echo ($e_detalle['apellidos']); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="curp">CURP</label>
                                <input type="text" class="form-control" name="curp" value="<?php echo ($e_detalle['curp']); ?>" placeholder="CURP" readonly>
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1%; margin-top: -1%">
                        <span class="material-symbols-rounded" style="margin-top: 2%; color: #3a3d44;">contact_page</span>
                        <p style="font-size: 15px; font-weight: bold; margin-top: -27px; margin-left: 2%">EXPEDIENTE INTERNO</p>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="acta_nacimiento">Acta de Nacimiento</label>
                                <input type="file" accept="application/pdf" class="form-control" name="acta_nacimiento" id="acta_nacimiento">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                    <?php echo remove_junk($e_detalle['acta_nacimiento']); ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="carta_no_ant">Carta de No Antecedentes Penales</label>
                                <input type="file" accept="application/pdf" class="form-control" name="carta_no_ant" id="carta_no_ant">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                    <?php echo remove_junk($e_detalle['carta_no_ant']); ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="const_no_in">Constancia de No Inhabilitación</label>
                                <input type="file" accept="application/pdf" class="form-control" name="const_no_in" id="const_no_in">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                    <?php echo remove_junk($e_detalle['const_no_in']); ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="comp_dom">Comprobante de Domicilio</label>
                                <input type="file" accept="application/pdf" class="form-control" name="comp_dom" id="comp_dom">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                    <?php echo remove_junk($e_detalle['comp_dom']); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="carta_rec1">Carta de Recpmendación (1)</label>
                                <input type="file" accept="application/pdf" class="form-control" name="carta_rec1" id="carta_rec1">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                    <?php echo remove_junk($e_detalle['carta_rec1']); ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="carta_rec2">Carta de Recomendación (2)</label>
                                <input type="file" accept="application/pdf" class="form-control" name="carta_rec2" id="carta_rec2">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                    <?php echo remove_junk($e_detalle['carta_rec2']); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1%; margin-top: -1%">
                        <span class="material-symbols-rounded" style="margin-top: 2%; color: #3a3d44;">work</span>
                        <p style="font-size: 15px; font-weight: bold; margin-top: -27px; margin-left: 2%">EXPEDIENTE INTERNO</p>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="puesto" class="control-label">Puesto</label>
                                <input type="text" class="form-control" name="puesto" value="<?php echo ($e_detalle['puesto']); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="area_adscripcion">Área</label>
                                <select class="form-control" name="area_adscripcion">
                                    <?php foreach ($areas as $area) : ?>
                                        <option <?php if ($area['id_area'] === $e_detalle['area_adscripcion']) echo 'selected="selected"'; ?> value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_inte">Tipo de Integrante</label>
                                <select class="form-control" name="tipo_inte">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($tipo_int as $inte) : ?>
                                        <option <?php if ($inte['id_tipo_integrante'] === $e_detalle['tipo_inte']) echo 'selected="selected"'; ?> value="<?php echo $inte['id_tipo_integrante']; ?>"><?php echo ucwords($inte['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="clave" class="control-label">Clave</label>
                                <input type="text" class="form-control" name="clave" value="<?php echo ($e_detalle['clave']); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="niv_puesto" class="control-label">Nivel de Puesto</label>
                                <input type="text" class="form-control" name="niv_puesto" value="<?php echo ($e_detalle['niv_puesto']); ?>">
                            </div>
                        </div>
                        <?php $v1 = "$" . $e_detalle['monto_bruto'];
                        $v2 = "$" . $e_detalle['monto_neto']; ?>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="monto_bruto">Monto Mensual (Bruto)</label>
                                <input type="text" class="form-control" name="monto_bruto" id="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" value="<?php echo ($v1); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="monto_neto">Monto Mensual (Neto)</label>
                                <input type="text" class="form-control" name="monto_neto" id="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" value="<?php echo ($v2); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <a href="detalles_usuario.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="update" class="btn btn-info">Actualizar</button>
                    </div>
                </form>
            </div>
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