<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Solicitudes - Comunicación Social';
require_once('includes/load.php');
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$area=46;
if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 15) {
    page_require_level_exacto(15);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);
    page_require_level_exacto(53);
}

if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user >7  && $nivel_user < 15) :
    redirect('home.php');
endif;
if ($nivel_user > 15 && $nivel_user < 53) :
    redirect('home.php');
endif;
?>


<?php include_once('layouts/header.php'); ?>

<a href="solicitudes.php" class="btn btn-info">Regresar a Áreas</a><br><br>
<h1 style="color: #3a3d44;">Solicitudes Comunicación Social</h1>


<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="container-fluid">
    <div class="full-box tileO-container">

		
	   
        <a href="comunicados_prensa.php" class="tileO">
            <div class="tileO-tittle">Cominicados Prensa</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    newsmode
                </span>
            </div>
        </a>
		  
        <a href="disenios.php" class="tileO">
            <div class="tileO-tittle">Diseños</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    design_services
                </span>
            </div>
        </a>
		  
        <a href="actividad_especial.php" class="tileO">
            <div class="tileO-tittle">Actividad Especial</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    ambient_screen
                </span>
            </div>
        </a>
		  
        <a href="entrevistas.php" class="tileO">
            <div class="tileO-tittle">Entrevistas</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    mic
                </span>
            </div>
        </a>
		
		
        <a href="difusion.php" class="tileO">
            <div class="tileO-tittle">Difusión</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    campaign
                </span>
            </div>
        </a>

 <a href="otras_acciones.php" class="tileO">
            <div class="tileO-tittle">Otras Acciones</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    category
                </span>
            </div>
        </a>

        <a href="sintesis_diaria.php" class="tileO">
            <div class="tileO-tittle">Síntesis Diaria</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    rate_review
                </span>
            </div>
        </a>

	
        <a href="informe_redes_sociales.php" class="tileO">
            <div class="tileO-tittle">Redes Sociales</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
					connect_without_contact
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
       
        <a href="eventos.php" class="tile">
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