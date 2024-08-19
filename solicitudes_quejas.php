<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Solicitudes - Quejas';
require_once('includes/load.php');
$user = current_user();

$user = current_user();
$id_user = $user['id_user'];
$busca_area = area_usuario($id_user);

$otro = $busca_area['nivel_grupo'];
$nivel_user = $user['user_level'];
//$area= 4;
$area = $busca_area['id_area'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 5) {
    page_require_level_exacto(5);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 21) {
    page_require_level_exacto(21);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}
if ($nivel_user > 2 && $nivel_user < 5) :
    redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 19) :
    redirect('home.php');
endif;
if ($nivel_user > 19 && $nivel_user < 21) :
    redirect('home.php');
endif;

if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo los '.$page_title, 5);   
}
?>

<?php
$c_user = count_by_id('users', 'id_user');
$c_trabajadores = count_by_id('detalles_usuario', 'id_det_usuario');
$c_areas = count_by_id('area', 'id_area');
$c_cargos = count_by_id('cargos', 'id_cargos');
?>

<?php include_once('layouts/header.php'); ?>

<a href="solicitudes.php" class="btn btn-info">Regresar a Áreas</a><br><br>
<h1 style="color:#3A3D44">Procesos de Orientación Legal, Quejas y Seguimiento </h1>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="container-fluid">
    <div class="full-box tile-container">
	<?php if (($nivel_user != 25)) : ?>
        <a href="quejas.php" class="tile">
            <div class="tile-tittle">Quejas</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    book
                </span>
            </div>
        </a>
		<?php endif ?>
		<?php if (($nivel_user != 19) && ($nivel_user != 25)) : ?>
										
        <a href="orientaciones.php" class="tile">
            <div class="tile-tittle">Orientaciones</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">psychology_alt</span>
                <i class="fas fa-user-tie"></i>
            </div>
        </a>
		<?php endif ?>
		<?php if (($nivel_user != 19) && ($nivel_user != 25)) : ?>
        <a href="canalizaciones.php" class="tile">
            <div class="tile-tittle">Canalizaciones</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    transfer_within_a_station
                </span>
                <i class="fas fa-user-tie"></i>
            </div>
        </a>
		<?php endif ?>
		<?php if (($nivel_user != 19) && ($nivel_user != 25)) : ?>
        <a href="quejosos.php" class="tile">
            <div class="tile-tittle">Promoventes</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    record_voice_over
                </span>
            </div>
        </a>
				<?php endif ?>
		<?php if (($nivel_user != 19) && ($nivel_user != 25)) : ?>

        <a href="agraviados.php" class="tile">
            <div class="tile-tittle">Agraviados</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    voice_over_off
                </span>
            </div>
        </a>
				<?php endif ?>
		<?php if (($nivel_user != 19) && ($nivel_user != 25)) : ?>

        <a href="actuaciones.php" class="tile">
            <div class="tile-tittle">Actuaciones</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    receipt_long
                </span>
            </div>
        </a>
				<?php endif ?> 
		<?php if (($nivel_user < 2)|| ($nivel_user == 7)|| ($nivel_user == 50) && ($nivel_user != 25)) : ?>

        <a href="recomendaciones_antes.php" class="tile">
            <div class="tile-tittle">Recom. antes de 2023</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    auto_stories
                </span>
            </div>
        </a>
		
		 <a href="recomendaciones_generales.php" class="tileO">
            <div class="tileO-tittle">Recomendaciones Grales</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    breaking_news_alt_1
                </span>
            </div>
        </a>
				<?php endif ?>

		<?php if (($nivel_user != 25)) : ?>
		<a href="mediacion.php" class="tile">
            <div class="tile-tittle">Mediación/Conciliación</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    diversity_3
                </span>
            </div>
        </a>
		<?php endif ?>
		
		<?php if (($nivel_user < 2)|| ($nivel_user != 5) || ($nivel_user == 7)|| ($nivel_user == 50) && ($nivel_user != 25)) : ?>
		<a href="mediacion_atencion.php" class="tile">
            <div class="tile-tittle">MASCJR</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    tune
                </span>
            </div>
        </a>
		<?php endif ?>
		
<?php if (($area == 21) || ($nivel_user < 2) || ($nivel_user == 7) || ($nivel_user == 53)):
		?>		
        <a href="actividad_especial_cgv.php" class="tileO">
            <div class="tileO-tittle" >Actividades Especiales CGV</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    rate_review
                </span>
            </div>
        </a>
<?php endif ?>	


		<?php if (($area == 21) || ($nivel_user < 2) || ($nivel_user == 7) ):?>
        <a href="competencia.php" class="tile">
            <div class="tile-tittle" style="font-size: 12px;">Conflictos Competenciales</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    find_in_page
                </span>
            </div>
        </a>
		<?php endif ?>
		
		<?php if (($nivel_user < 2) || ($nivel_user == 7) || ($nivel_user == 25) || ($nivel_user == 50)):?>

        <a href="proyectos.php" class="tile">
            <div class="tile-tittle" >Proyectos</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    quick_reference
                </span>
            </div>
        </a>
		<?php endif ?>
		
		<?php if (($nivel_user < 2) || ($nivel_user == 7) || ($nivel_user == 50)):?>

        <a href="atencion_seguimiento.php" class="tile">
            <div class="tile-tittle">Atención/Seguimiento</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    app_registration
                </span>
            </div>
        </a>
		<?php endif ?>
<a href="solicitudes_mas_acciones.php" class="tileO">
            <div class="tileO-tittle">Más Actividades</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    category
                </span>
            </div>
        </a>
		
       
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>