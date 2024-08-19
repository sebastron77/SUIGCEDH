<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Estadísticas de Solicitudes de Información';
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
        <?php echo 'Estadísticas de Solicitudes de Informacion'; ?>
    </div>
</div>


<div class="container-fluid">
    <div class="full-box tile-container">
		<a href="est_solicitudes_anio.php" class="tileA">
            <div class="tileA-tittle">Registro</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">                    
					stylus_note
                </span>
            </div>
        </a>
		<a href="est_solicitudes_status.php" class="tileA">
            <div class="tileA-tittle">Estatus</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    rebase_edit
                </span>
            </div>
        </a>
		
		<a href="est_solicitudes_medio.php" class="tileA">
            <div class="tileA-tittle">Medio Presentación</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    input_circle
                </span>
            </div>
        </a>
		
		<a href="est_solicitudes_genero.php" class="tileA">
            <div class="tileA-tittle">Género</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    diversity_3
                </span>
            </div>
        </a>
	
		<a href="est_solicitudes_tipo.php" class="tileA">
            <div class="tileA-tittle">Tipo Solicitud</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    fact_check
                </span>
            </div>
        </a>
		
    </div>
</div>



<?php include_once('layouts/footer.php'); ?>