<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Solicitudes - Equidad de Género';
require_once('includes/load.php');
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$area =36;

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 6) {
    page_require_level_exacto(6);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);  
    page_require_level_exacto(7);
}
if ($nivel_user == 23) {
    page_require_level_exacto(23);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);  
    page_require_level_exacto(53);
}

if (($nivel_user > 2 && $nivel_user < 6)) :
    redirect('home.php');
endif;

if ($nivel_user > 7 && $nivel_user < 23) :
    redirect('home.php');
endif;
if ($nivel_user > 23 && $nivel_user < 53) :
    redirect('home.php');
endif;




?>

<?php include_once('layouts/header.php'); ?>

<a href="solicitudes.php" class="btn btn-info">Regresar a Áreas</a><br><br>
<h1 style="color: #3a3d44;">Solicitudes de Subcoordinación de Equidad entre Mujeres y Hombres </h1>


<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="container-fluid">
    <div class="full-box tileO-container">
		
			
        <a href="monitoreo_politicas.php" class="tile">
            <div class="tileO-tittle" style="font-size: 12px;">Políticas Públicas</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    joystick
                </span>
            </div>
        </a>
		
        <a href="directorio_monitoreo_politicas.php" class="tile">
            <div class="tileO-tittle" style="font-size: 12px;">Directorio Contactos</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    contact_phone
                </span>
            </div>
        </a>
		
        <a href="pat.php?a=<?php echo $area ?>" class="tileO">
            <div class="tileO-tittle" style="font-size: 12px;">Programa  Anual de Trabajo</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    engineering
                </span>
            </div>
        </a>		
	   
        <a href="informes_areas.php?a=<?php echo $area ?>" class="tileO">
            <div class="tileO-tittle">Informe de Actividades</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    task_alt
                </span>
            </div>
        </a>

        <a href="capacitaciones.php?a=<?php echo $area ?>" class="tileO">
            <div class="tileO-tittle">Capacitaciones</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    supervisor_account
                </span>
            </div>
        </a>
       
        <a href="eventos.php?a=<?php echo $area ?>" class="tile">
            <div class="tile-tittle">Eventos</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    event_available
                </span>
            </div>
        </a>
		 <a href="solicitudes_correspondencia.php?a=<?php echo $area ?>" class="tile">
            <div class="tile-tittle">Corresppondencia</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    local_post_office
                </span>
            </div>
        </a>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>