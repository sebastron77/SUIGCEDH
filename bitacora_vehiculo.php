<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Bitácora de los Vehículos';
require_once('includes/load.php');
?>
<?php

$user = current_user();
$nivel_user = $user['user_level'];

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
// $all_bitacoras = find_all('rel_bitacora_vehiculo');
$vehiculo = find_by_id('vehiculos', (int)$_GET['id'], 'id_vehiculo');
$id_v = (int)$_GET['id'];

$ejercicio = isset($_GET['ejercicio']) ? $_GET['ejercicio'] : date("Y");
$mes = $_GET['mes'];

if ($ejercicio != '' && $mes != '') {
    $all_bitacoras = find_all_bit_ejer_mes($ejercicio, $mes);
} else {
    $all_bitacoras = find_all('rel_bitacora_vehiculo');
}
?>

<?php include_once('layouts/header.php'); ?>
<a href="control_vehiculos.php" class="btn btn-success">Regresar</a><br><br>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<script type="text/javascript">
    function changueAnio(mes) {
        id = document.getElementById('id').value;
        ejercicio = document.getElementById('ejercicio').value;
        window.open("bitacora_vehiculo.php?ejercicio=" + ejercicio + "&mes=" + mes + "&id=" + id, "_self");
    }
</script>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Bitácora del Vehículo: <?php echo $vehiculo['marca'] . ' ' . $vehiculo['modelo'] ?></span>
                </strong>
                <a href="add_bitacora_vehiculo.php?id=<?php echo $id_v; ?>" class="btn btn-info pull-right" style="margin-top: 45px;">Agregar información</a>
                <div class="row" style="margin-top: 20px; display: flex; justify-content: flex-start; gap: 0px;">
                    <div class="col-md-1" style="margin-top: 5px;">
                        <b>Buscar por: </b>
                    </div>
                    <div class="col-md-2" style="margin-left: -30px">
                        <div class="form-group">
                            <input type="hidden" id="id" value="<?php echo $id_v; ?>">
                            <select class="form-control" name="ejercicio" id="ejercicio">
                                <option value="">Selecciona Ejercicio</option>
                                <?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
                                    echo "<option value='" . $i . "'>" . $i . "</option>";
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="mes" onchange="changueAnio(this.value)">
                            <option value="">Selecciona Mes</option>
                            <option value="1">Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 1%;">#</th>
                            <th class="text-center" style="width: 1%;">Ejercicio</th>
                            <th class="text-center" style="width: 1%;">Mes</th>
                            <th class="text-center" style="width: 1%;">Km Inicial</th>
                            <th class="text-center" style="width: 1%;">Km Final</th>
                            <th class="text-center" style="width: 1%;">Litros</th>
                            <th class="text-center" style="width: 1%;">Kilometraje</th>
                            <th class="text-center" style="width: 1%;">Importe</th>
                            <?php if ($nivel_user == 1 || $nivel_user == 14) : ?>
                                <th style="width: 1%;" class="text-center">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_bitacoras as $a_bitacora) : ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_bitacora['ejercicio'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_bitacora['mes'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_bitacora['km_inicial'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_bitacora['km_final'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_bitacora['litros_g'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_bitacora['kilometraje_g'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_bitacora['importe_g'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($nivel_user == 1 || $nivel_user == 14) : ?>
                                        <div class="btn-group">
                                            <a href="ver_info_bitacora_vehiculo.php?id=<?php echo (int) $a_bitacora['id_rel_bitacora_vehiculo']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                                <span class="material-symbols-outlined" style="font-size: 22px; color: white; margin-top: 8px;">
                                                    visibility
                                                </span>
                                            </a>
                                            <a href="edit_bitacora_vehiculo.php?id=<?php echo (int) $a_bitacora['id_rel_bitacora_vehiculo']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                <span class="material-symbols-outlined" style="font-size: 22px; color: black; margin-top: 8px;">
                                                    edit
                                                </span>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>