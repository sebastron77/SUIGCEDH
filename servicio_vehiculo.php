<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Servicios del Vehículo';
require_once('includes/load.php');

$idP =  (int)$_GET['id'];
$user = current_user();
$nivel_user = $user['user_level'];
$usuario = $user['id_user'];
$servicio = find_by_id_consec_serv_v($idP);
$rel_servicios = find_all_by('rel_servicios_vehiculos', $idP, 'id_vehiculo');

$e_detalle = find_by_id('vehiculos', $idP, 'id_vehiculo');
if (!$e_detalle) {
    $session->msg("d", "id de vehiculo no encontrado.");
    redirect('control_vehiculos.php');
}

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
?>

<?php
if (isset($_POST['servicio_vehiculo'])) {

    $id_vehiculo = $idP;
    $no_servicio = $servicio['no_servicio'] + 1;
    $tipo_servicio = $_POST['tipo_servicio'];
    $km_al_servicio = $_POST['km_al_servicio'];
    $fecha_servicio = $_POST['fecha_servicio'];
    $km_prox_servicio = $_POST['km_prox_servicio'];
    $observaciones = $_POST['observaciones'];
    date_default_timezone_set('America/Mexico_City');
    $fecha_creacion = date('Y-m-d');

    $query = "INSERT INTO rel_servicios_vehiculos (";
    $query .= "id_vehiculo, no_servicio, tipo_servicio, km_al_servicio, fecha_servicio, km_prox_servicio, observaciones, user_creador, fecha_creacion";
    $query .= ") VALUES (";
    $query .= " '{$id_vehiculo}', '{$no_servicio}', '{$tipo_servicio}', '{$km_al_servicio}', '{$fecha_servicio}', '{$km_prox_servicio}', '{$observaciones}', 
                '{$usuario}', '{$fecha_creacion}'";
    $query .= ")";
    $db->query($query);
    insertAccion($user['id_user'], '"' . $user['username'] . '" agregó servicio de vehiculo:' . (int)$id_vehiculo, 1);
    redirect('servicio_vehiculo.php?id='.$e_detalle['id_vehiculo'], false);
}
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
    <div class="col-md-6">
        <div class="panel login-page4" style="margin-left: 0%;">
            <div class="panel-heading">
                <strong style="font-size: 16px; font-family: 'Montserrat', sans-serif">
                    <span class="glyphicon glyphicon-th"></span>
                    SERVICIOS DEL VEHÍCULO: <?php echo upper_case(ucwords($e_detalle['marca'] . " " . $e_detalle['modelo'])); ?>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="servicio_vehiculo.php?id=<?php echo (int)$e_detalle['id_vehiculo']; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipo_servicio">Tipo de Servicio</label>
                                <input class="form-control" type="text" name="tipo_servicio">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_servicio">Fecha del Servicio</label>
                                <input type="date" class="form-control" name="fecha_servicio">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="km_al_servicio">Km. Antes del servicio</label>
                                <input class="form-control" type="text" name="km_al_servicio">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="km_prox_servicio">Km. Próximo Servicio</label>
                                <input class="form-control" type="text" name="km_prox_servicio">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="observaciones">Observaciones</label>
                                <textarea type="text" class="form-control" name="observaciones" id="observaciones" cols="30" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <a href="control_vehiculos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="servicio_vehiculo" class="btn btn-info">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 panel-body" style="height: 100%; margin-top: -5px;">
        <table class="table table-bordered table-striped" style="width: 100%; float: left;" id="tblProductos">
            <thead class="thead-purple" style="margin-top: -50px;">
                <tr style="height: 10px;">
                    <th colspan="7" style="text-align:center; font-size: 14px;">Servicios de <?php echo $e_detalle['marca'] . " " . $e_detalle['modelo'] ?></th>
                </tr>
                <tr style="height: 10px;">
                    <th class="text-center" style="width: 1%; font-size: 11.5px;">No. Servicio</th>
                    <th class="text-center" style="width: 10%; font-size: 11.5px;">Tipo de Servicio</th>
                    <th class="text-center" style="width: 1%; font-size: 11.5px;">Km. Antes Servicio</th>
                    <th class="text-center" style="width: 4%; font-size: 11.5px;">Fecha de Servicio</th>
                    <th class="text-center" style="width: 1%; font-size: 11.5px;">Km. Próx. Servicio</th>
                    <th class="text-center" style="width: 10%; font-size: 11.5px;">Observaciones</th>
                    <th class="text-center" style="width: 1%; font-size: 11.5px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rel_servicios as $serv) : ?>
                    <tr>
                        <td class="text-center" style="font-size: 14px;"><?php echo ucwords($serv['no_servicio']) ?></td>
                        <td style="font-size: 14px;"><?php echo ucwords($serv['tipo_servicio']) ?></td>
                        <td style="font-size: 14px;"><?php echo ucwords($serv['km_al_servicio']) ?></td>
                        <td style="font-size: 14px;"><?php echo ucwords($serv['fecha_servicio']) ?></td>
                        <td style="font-size: 14px;"><?php echo ucwords($serv['km_prox_servicio']) ?></td>
                        <td style="font-size: 14px;"><?php echo ucwords($serv['observaciones']) ?></td>
                        <td style="font-size: 14px;" class="text-center">
                            <a href="edit_servicio_vehiculo.php?id=<?php echo (int)$serv['id_rel_servicios_vehiculos']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 30px; width: 30px;">
                                <span class="material-symbols-rounded" style="font-size: 22px; color: black; margin-top: -1.5px; margin-left: -5px;">
                                    edit
                                </span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>