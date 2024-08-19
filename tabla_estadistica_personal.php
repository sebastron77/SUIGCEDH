<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Estadísticas de Personal CEDH';
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
        <?php echo 'Estadísticas de Personal CEDH'; ?>
    </div>
</div>


<div class="container-fluid">
    <div class="full-box tile-container">
	<a href="est_personal_genero.php" class="tileA">
            <div class="tileA-tittle">Por Género</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    diversity_3
                </span>
            </div>
        </a>
		
		<a href="est_personal_areas.php" class="tileA">
            <div class="tileA-tittle">Por Área</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">                    
					domain
                </span>
            </div>
        </a>
		<a href="est_personal_puestos.php" class="tileA">
            <div class="tileA-tittle">Por Puestos</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    manage_accounts
                </span>
            </div>
        </a>
		
		<a href="est_personal_tipo.php" class="tileA">
            <div class="tileA-tittle">Por Tipo Integrante</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    groups_3
                </span>
            </div>
        </a>
			
	
		<a href="est_personal_conocimiento.php" class="tileA">
            <div class="tileA-tittle">Por Área Conocimiento</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    play_lesson
                </span>
            </div>
        </a>
		
		<a href="est_personal_carrera.php" class="tileA">
            <div class="tileA-tittle">Por Carrera</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    school
                </span>
            </div>
        </a>
		
		<a href="est_personal_escolaridad.php" class="tileA">
            <div class="tileA-tittle">Por Grado Escolar</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    draw_abstract
                </span>
            </div>
        </a>
		
		<a href="est_personal_sueldo.php" class="tileA">
            <div class="tileA-tittle">Por Sueldo</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    paid
                </span>
            </div>
        </a>
		
    </div>
</div>



<?php include_once('layouts/footer.php'); ?>