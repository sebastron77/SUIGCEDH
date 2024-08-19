<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Agenda';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id'];
$nivel_user = $user['user_level'];

// Identificamos a que área pertenece el usuario logueado
$area_user = area_usuario2($id_user);
$area = $area_user['nombre_area'];

if (($nivel_user <= 2) || ($nivel_user == 7)) {
    $avances = find_by_id_descripcion_porcentaje((int)$_GET['id']);
} else {
    $avances = find_by_id_descripcion_porcentaje_area((int)$_GET['id'], $area);
}

?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<a href="agendas.php" class="btn btn-success">Regresar</a><br><br>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Avance del Proyecto</span>
                </strong>
            </div>
        </div>

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr style="height: 10px;">
                        <th style="width: 5%;">Fecha y hora de avance</th>
                        <th class="text-center" style="width: 10%;">Porcentaje de avance</th>
                        <th class="text-center" style="width: 15%;">Descripción del avance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($avances as $avance) : ?>
                        <tr>
                            <td><?php echo remove_junk(ucwords($avance['fecha_hora_creacion'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($avance['porcentaje'])) ?></td>
                            <td><?php echo remove_junk(ucwords($avance['descripcion_avance'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<?php include_once('layouts/footer.php'); ?>