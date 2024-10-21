<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<link rel="stylesheet" href="libs/css/main.css" />

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
<script src="html2pdf.bundle.min.js"></script>
<script src="script.js"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

<link href="https://harvesthq.github.io/chosen/chosen.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>
<?php header('Content-type: text/html; charset=utf-8');

require_once('includes/load.php');
$page_title = 'Editar Bitácora Vehículo';
// error_reporting(E_ALL ^ E_NOTICE);

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

$e_bitacora = find_by_id('rel_bitacora_vehiculo', (int)$_GET['id'], 'id_rel_bitacora_vehiculo');
$id_b_v = (int)$_GET['id'];

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

if (isset($_POST['edit_bitacora_vehiculo'])) {

    if (empty($errors)) {
        $fecha = $e_bitacora['dia_g'] . '/' . $e_bitacora['mes'] . '/' . $e_bitacora['ejercicio'];
        $id_b_vehiculo = $id_b_v;
        $dia_g = $_POST['dia_g'];
        $kilometraje_g = $_POST['kilometraje_g'];
        $litros_g = $_POST['litros_g'];
        $importe_g = $_POST['importe_g'];
        $texto = "";

        $query  = "UPDATE rel_bitacora_vehiculo SET ";
        $query .= "dia_g='{$dia_g}', kilometraje_g='{$kilometraje_g}', litros_g='{$litros_g}', importe_g='{$importe_g}' ";
        $query .= "WHERE id_rel_bitacora_vehiculo='{$id_b_vehiculo}'";
        $result = $db->query($query);

        if ($result && $db->affected_rows() === 1) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó el día:' . $fecha . ', vehículo ' . $id_b_vehiculo, 2);
            $session->msg('s', " La bitácora ha sido actualizada con éxito.");
            // Si la ventana fue abierta con window.open(), ejecuta el script para cerrarla
            echo "<script>window.opener.location.reload(); window.close();</script>";
            exit();  // Importante para evitar cualquier ejecución adicional
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron cambios a la información.');
        }
    } else {
        $session->msg("d", $errors);
        redirect('bitacora_vehiculo.php?id=' . (int)$e_vehiculo['id_vehiculo'], false);
    }
}
?>

<?php header('Content-type: text/html; charset=utf-8');
// include_once('layouts/header.php'); 
?>
<?php echo display_msg($msg); ?>

<div class="row">
    <div class="panel panel-heading">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span style="font-size: 17px;">Editar Vehículo</span>
            </strong>
        </div>

        <div class="panel-body" style="margin-left: 80px;">
            <form method="post" action="edit_bitacora_vehiculo.php?id=<?php echo (int)$e_bitacora['id_rel_bitacora_vehiculo']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="dia_g">Día</label>
                            <input type="number" class="form-control" name="dia_g" value="<?php echo $e_bitacora['dia_g']; ?>" style="width: 220px;">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="kilometraje_g">Kilometraje</label>
                            <input type="number" class="form-control" name="kilometraje_g" value="<?php echo $e_bitacora['kilometraje_g']; ?>" style="width: 220px;">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="litros_g">Litros</label>
                            <input type="number" class="form-control" name="litros_g" value="<?php echo $e_bitacora['litros_g']; ?>" style="width: 220px;">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="importe_g">Importe</label>
                            <input type="number" class="form-control" name="importe_g" value="<?php echo $e_bitacora['importe_g']; ?>" style="width: 220px;">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group clearfix">
                        <button type="submit" name="edit_bitacora_vehiculo" class="btn btn-primary" value="subir"">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>
<script>
    function closeWindowAfterSubmit() {
        window.close(); // Cierra la ventana emergente
    }
</script>

<?php include_once('layouts/footer.php'); ?>