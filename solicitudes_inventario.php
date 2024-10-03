<?php
$page_title = 'Inventario CEDH';
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
<h1 style="color: #3a3d44;">Inventarios de la CEDH</h1>
<div class="panel-heading clearfix">
    <center>
        <a href="add_inventario.php" class="btn btn-info">AGREGAR A INVENTARIO</a>
    </center>
</div>
<div class="container-fluid">
    <div class="full-box tileO-container">
        <a href="inventario_papeleria.php" class="tile">
            <div class="tile-tittle">Papelería</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    design_services
                </span>
            </div>
        </a>
        <a href="inventario_mobiliario.php" class="tile">
            <div class="tile-tittle">Mobiliario</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    chair_alt
                </span>
            </div>
        </a>
        <a href="inventario_computo.php" class="tile">
            <div class="tile-tittle">Equipos de Cómputo</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    desktop_windows
                </span>
            </div>
        </a>
        <a href="inventario_electrico.php" class="tile">
            <div class="tile-tittle">Eléctrico</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    bolt
                </span>
            </div>
        </a>
        <a href="inventario_otros.php" class="tile">
            <div class="tile-tittle">Otros</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    home_storage
                </span>
            </div>
        </a>
        <a href="inventario_abarrotes.php" class="tile">
            <div class="tile-tittle">Abarrotes</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    cleaning
                </span>
            </div>
        </a>
        <a href="inventario_electrodomesticos.php" class="tile">
            <div class="tile-tittle">Electrodomésticos</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    microwave
                </span>
            </div>
        </a>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>