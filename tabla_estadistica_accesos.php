<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Estadísticas de Accesos';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    page_require_level(3);
}
if ($nivel == 4) {
    redirect('home.php');
}
if ($nivel == 5) {
    page_require_level_exacto(5);
}
if ($nivel == 6) {
    redirect('home.php');
}
if ($nivel == 7) {
    page_require_level_exacto(7);
}



?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12" style="font-size: 40px; color: #3a3d44;">
        <?php echo 'Estadísticas de Accesos'; ?>
    </div>
</div>


<div class="container-fluid">
    <div class="full-box tile-container">
        <a href="est_acceso_web.php?id=1" class="tileA">
            <div class="tileA-tittle">Presenta Queja</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    notification_multiple
                </span>
            </div>
        </a>
        <a href="est_acceso_web.php?id=2" class="tileA">
            <div class="tileA-tittle">Consulta Queja</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    mystery
                </span>
            </div>
        </a>
        <a href="est_acceso_web.php?id=3" class="tileA">
            <div class="tileA-tittle">Recomendaciones</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    auto_stories
                </span>
            </div>
        </a>
        
    </div>
</div>



<?php include_once('layouts/footer.php'); ?>