<?php
$page_title = 'Vehículos';
require_once('includes/load.php');
?>
<?php
page_require_level(53);
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$area = isset($_GET['a']) ? $_GET['a'] : '0';
$solicitud = find_by_solicitud($area);
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

<?php
$c_user = count_by_id('users', 'id_user');
?>

<?php include_once('layouts/header.php'); ?>

<a href="solicitudes_gestion.php" class="btn btn-info">Regresar a Área</a><br><br>
<h1 style="color: #3a3d44;">Parque Vehicular de la CEDH</h1>

<div class="container-fluid">

    <div class="full-box tileO-container">
        <a href="control_vehiculos.php" class="tile">
            <div class="tile-tittle">Control de Vehículos</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    directions_car
                </span>
            </div>
        </a>
        <a href="prestamo_vehiculos.php" class="tile">
            <div class="tile-tittle">Préstamo de Vehículos</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    swap_driving_apps
                </span>
            </div>
        </a>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>