<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Vehículo';
error_reporting(E_ALL ^ E_NOTICE);
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];
$detalle = $user['id_user'];

$cat_combustible = find_all_order('cat_combustible', 'descripcion');

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
if (isset($_POST['add_vehiculo'])) {


    if (empty($errors)) {
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

        date_default_timezone_set('America/Mexico_City');
        $creacion = date('Y-m-d');

        $dbh = new PDO('mysql:host=localhost; dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');

        $name = $_FILES['documento_poliza']['name'];
        $size = $_FILES['documento_poliza']['size'];
        $type = $_FILES['documento_poliza']['type'];
        $temp = $_FILES['documento_poliza']['tmp_name'];

        $name2 = $_FILES['tarjeta_circulacion']['name'];
        $size2 = $_FILES['tarjeta_circulacion']['size'];
        $type2 = $_FILES['tarjeta_circulacion']['type'];
        $temp2 = $_FILES['tarjeta_circulacion']['tmp_name'];

        $name3 = $_FILES['factura']['name'];
        $size3 = $_FILES['factura']['size'];
        $type3 = $_FILES['factura']['type'];
        $temp3 = $_FILES['factura']['tmp_name'];


        $query = "INSERT INTO vehiculos (";
        $query .= "marca, modelo, anio, no_serie, placas, color, no_puertas, no_cilindros, tipo_combustible, compania_seguros, no_poliza, documento_poliza, 
                        tarjeta_circulacion, factura, area_asignacion, estatus, usuario_creador, fecha_creacion";
        $query .= ") VALUES (";
        $query .= " '{$marca}', '{$modelo}', '{$anio}', '{$no_serie}', '{$placas}', '{$color}', '{$no_puertas}', '{$no_cilindros}', '{$tipo_combustible}', 
                    '{$compania_seguros}', '{$no_poliza}', '{$name}', '{$name2}', '{$name3}', 40, 1, '{$id_user}', '{$creacion}'";
        $query .= ")";

        $dbh->exec($query);
        $id_vehiculo = $dbh->lastInsertId();

        $carpeta = 'uploads/parquevehicular/vehiculos/' . $id_vehiculo;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        $move2 =  move_uploaded_file($temp2, $carpeta . "/" . $name2);
        $move3 =  move_uploaded_file($temp3, $carpeta . "/" . $name3);

        /*creo archivo index para que no se muestre el Index Of*/
        $source = 'uploads/index.php';
        if (copy($source, $carpeta . '/index.php')) {
            echo "El archivo ha sido copiado exitosamente.";
        } else {
            echo "Ha ocurrido un error al copiar el archivo.";
        }

        if ($id_vehiculo != 0) {
            //sucess
            $session->msg('s', " El vehículo ha sido registrado con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó Vehículo, ' . $marca . ' ' . $modelo . ' ' . $placas . '.', 1);
            redirect('control_vehiculos.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la ficha.');
            redirect('add_vehiculo.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_vehiculo.php', false);
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
                <span>Agregar Vehículo</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_vehiculo.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="marca">Marca</label>
                            <input type="text" class="form-control" name="marca" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="modelo">Modelo</label>
                            <input type="text" class="form-control" name="modelo" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="anio">Año</label>
                            <input type="number" class="form-control" min="1970" max="2080" name="anio" oninput="validateLength(this)" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_serie">No. Serie</label>
                            <input type="text" class="form-control" name="no_serie" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="placas">Placas</label>
                            <input type="text" class="form-control" name="placas" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="color">Color</label>
                            <input type="text" class="form-control" name="color" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="no_puertas">No. Puertas</label>
                            <input type="number" class="form-control" name="no_puertas" min="0" max="8" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="no_cilindros">No. Cilindros</label>
                            <input type="number" class="form-control" name="no_cilindros" min="0" max="10" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_combustible">Tipo Combustible</label>
                            <select class="form-control" name="tipo_combustible" id="tipo_combustible" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_combustible as $combustible) : ?>
                                    <option value="<?php echo $combustible['id_cat_combustible']; ?>"><?php echo ucwords($combustible['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="compania_seguros">Compañía de Seguros</label>
                            <input type="text" class="form-control" name="compania_seguros" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="no_poliza">No. Póliza</label>
                            <input type="text" class="form-control" name="no_poliza" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="documento_poliza">Documento Póliza</label>
                            <input type="file" class="form-control" name="documento_poliza">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tarjeta_circulacion">Tarjeta Circulación</label>
                            <input type="file" class="form-control" name="tarjeta_circulacion">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="factura">Factura</label>
                            <input type="file" class="form-control" name="factura">
                        </div>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <a href="control_vehiculos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_vehiculo" class="btn btn-primary">Guardar</button>
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