<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
require_once('includes/load.php');
$page_title = 'Editar Vehículo';
error_reporting(E_ALL ^ E_NOTICE);
$user = current_user();
$nivel_user = $user['user_level'];
$detalle = $user['id_user'];

$e_vehiculo = find_by_id('vehiculos', (int)$_GET['id'], 'id_vehiculo');
$cat_combustible = find_all_order('cat_combustible', 'descripcion');
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

if (isset($_POST['edit_vehiculo'])) {

    if (empty($errors)) {
        $id_vehiculo = $e_vehiculo['id_vehiculo'];
        $marca   = remove_junk($db->escape($_POST['marca']));
        $modelo   = remove_junk($db->escape($_POST['modelo']));
        $anio   = remove_junk($db->escape($_POST['anio']));
        $no_serie   = remove_junk($db->escape($_POST['no_serie']));
        $placas   = remove_junk($db->escape($_POST['placas']));
        $color   = remove_junk($db->escape($_POST['color']));
        $no_puertas   = remove_junk($db->escape($_POST['no_puertas']));
        $no_cilindros   = remove_junk($db->escape($_POST['no_cilindros']));
        $tipo_combustible   = remove_junk($db->escape($_POST['tipo_combustible']));
        $compania_seguros   = remove_junk($db->escape($_POST['compania_seguros']));
        $no_poliza   = remove_junk($db->escape($_POST['no_poliza']));
        $area_asignacion   = remove_junk($db->escape($_POST['area_asignacion']));

        $carpeta = 'uploads/parquevehicular/vehiculos/' . $id_vehiculo;

        $name = $_FILES['documento_poliza']['name'];
        $size = $_FILES['documento_poliza']['size'];
        $type = $_FILES['documento_poliza']['type'];
        $temp = $_FILES['documento_poliza']['tmp_name'];

        if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else {
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }

        $name2 = $_FILES['tarjeta_circulacion']['name'];
        $size2 = $_FILES['tarjeta_circulacion']['size'];
        $type2 = $_FILES['tarjeta_circulacion']['type'];
        $temp2 = $_FILES['tarjeta_circulacion']['tmp_name'];

        if (is_dir($carpeta)) {
            $move2 =  move_uploaded_file($temp2, $carpeta . "/" . $name2);
        } else {
            mkdir($carpeta, 0777, true);
            $move2 =  move_uploaded_file($temp2, $carpeta . "/" . $name2);
        }

        $name3 = $_FILES['factura']['name'];
        $size3 = $_FILES['factura']['size'];
        $type3 = $_FILES['factura']['type'];
        $temp3 = $_FILES['factura']['tmp_name'];

        if (is_dir($carpeta)) {
            $move3 =  move_uploaded_file($temp3, $carpeta . "/" . $name3);
        } else {
            mkdir($carpeta, 0777, true);
            $move3 =  move_uploaded_file($temp3, $carpeta . "/" . $name3);
        }


        $sql = "UPDATE vehiculos SET marca='{$marca}', modelo='{$modelo}', anio='{$anio}', no_serie='{$no_serie}', placas='{$placas}',  color='{$color}', 
                no_puertas='{$no_puertas}', no_cilindros='{$no_cilindros}', tipo_combustible='{$tipo_combustible}', compania_seguros='{$compania_seguros}', 
                no_poliza='{$no_poliza}', area_asignacion='{$area_asignacion}'";

        if ($name != '') {
            $sql .= ", documento_poliza='{$name}' ";
        }
        if ($name2 != '') {
            $sql .= ", tarjeta_circulacion='{$name2}' ";
        }
        if ($name3 != '') {
            $sql .= ", factura='{$name3}' ";
        }

        $sql .= "WHERE id_vehiculo = '{$db->escape($id_vehiculo)}'";

        $result = $db->query($sql);

        if ($result && $db->affected_rows() === 1) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó el vehículo con id:' . $id_vehiculo . ' - ' . $e_vehiculo['marca'] . ' ' . $e_vehiculo['modelo'], 2);
            $session->msg('s', " El vehículo '" . $e_vehiculo['marca'] . ' ' . $e_vehiculo['modelo'] . "' ha sido actualizado con éxito.");
            redirect('edit_vehiculo.php?id=' . (int)$e_vehiculo['id_vehiculo'], false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron cambios a la información.');
            redirect('edit_vehiculo.php?id=' . (int)$e_vehiculo['id_vehiculo'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_vehiculo.php?id=' . (int)$e_vehiculo['id_vehiculo'], false);
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
            <form method="post" action="edit_vehiculo.php?id=<?php echo (int)$e_vehiculo['id_vehiculo']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="marca">Marca</label>
                            <input type="text" class="form-control" name="marca" value="<?php echo $e_vehiculo['marca']; ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="modelo">Modelo</label>
                            <input type="text" class="form-control" name="modelo" value="<?php echo $e_vehiculo['modelo']; ?>">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="anio">Año</label>
                            <input type="number" class="form-control" min="1970" max="2080" name="anio" oninput="validateLength(this)" value="<?php echo $e_vehiculo['anio']; ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_serie">No. Serie</label>
                            <input type="text" class="form-control" name="no_serie" value="<?php echo $e_vehiculo['no_serie']; ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="placas">Placas</label>
                            <input type="text" class="form-control" name="placas" value="<?php echo $e_vehiculo['placas']; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="color">Color</label>
                            <input type="text" class="form-control" name="color" value="<?php echo $e_vehiculo['color']; ?>">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="no_puertas">No. Puertas</label>
                            <input type="number" class="form-control" name="no_puertas" min="0" max="8" value="<?php echo $e_vehiculo['no_puertas']; ?>">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="no_cilindros">No. Cilindros</label>
                            <input type="number" class="form-control" name="no_cilindros" min="0" max="10" value="<?php echo $e_vehiculo['no_cilindros']; ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_combustible">Tipo Combustible</label>
                            <select class="form-control" name="tipo_combustible" id="tipo_combustible" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_combustible as $combustible) : ?>
                                    <option <?php if ($e_vehiculo['tipo_combustible'] == $combustible['id_cat_combustible']) echo 'selected="selected"'; ?> value="<?php echo $combustible['id_cat_combustible']; ?>"><?php echo ucwords($combustible['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="compania_seguros">Compañía de Seguros</label>
                            <input type="text" class="form-control" name="compania_seguros" value="<?php echo $e_vehiculo['compania_seguros']; ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="no_poliza">No. Póliza</label>
                            <input type="text" class="form-control" name="no_poliza" value="<?php echo $e_vehiculo['no_poliza']; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="area_asignacion">Área a la que se asigna</label>
                            <select class="form-control" id="area_asignacion" name="area_asignacion" required>
                                <?php foreach ($area as $a) : ?>
                                    <option <?php if ($a['id_area'] === $e_vehiculo['area_asignacion']) echo 'selected="selected"'; ?> value="<?php echo $a['id_area']; ?>"><?php echo ucwords($a['nombre_area']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="documento_poliza">Documento Póliza</label>
                            <input type="file" accept="application/pdf" class="form-control" name="documento_poliza" value="<?php echo remove_junk($e_vehiculo['documento_poliza']); ?>">
                            <label style="font-size:12px; color:#E3054F;" for="documento_poliza">Archivo Actual: <?php echo remove_junk($e_vehiculo['documento_poliza']); ?><?php ?></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tarjeta_circulacion">Tarjeta Circulación</label>
                            <input type="file" accept="application/pdf" class="form-control" name="tarjeta_circulacion" value="<?php echo remove_junk($e_vehiculo['tarjeta_circulacion']); ?>">
                            <label style="font-size:12px; color:#E3054F;" for="tarjeta_circulacion">Archivo Actual: <?php echo remove_junk($e_vehiculo['tarjeta_circulacion']); ?><?php ?></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="factura">Factura</label>
                            <input type="file" accept="application/pdf" class="form-control" name="factura" value="<?php echo remove_junk($e_vehiculo['factura']); ?>">
                            <label style="font-size:12px; color:#E3054F;" for="factura">Archivo Actual: <?php echo remove_junk($e_vehiculo['factura']); ?><?php ?></label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group clearfix">
                        <a href="control_vehiculos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="edit_vehiculo" class="btn btn-primary" value="subir">Guardar</button>
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