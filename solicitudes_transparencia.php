<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Solicitudes - Transparencia';
require_once('includes/load.php');

$user = current_user();
$id_usuario = $user['id_user'];
$nivel = $user['user_level'];
$nivel_user = $user['user_level'];
$area=13;

$no_atendidos= count_oficiosInter($area);
$no_atendidos_ext= count_oficiosExt($area);
$total_correspondencia = (int)$no_atendidos['total'] + (int)$no_atendidos_ext['total'];
page_require_level(53);
if (($nivel_user > 2 && $nivel_user < 7)) :
    redirect('home.php');
endif;
if (($nivel_user > 7 && $nivel_user < 10)) :
    redirect('home.php');
endif;
if ($nivel_user > 10 && $nivel_user < 53) :
    redirect('home.php');
endif;


if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);
}

?>

<?php include_once('layouts/header.php'); ?>

<a href="solicitudes.php" class="btn btn-info">Regresar a Áreas</a><br><br>
<h1 style="color:#3A3D44">Solicitudes Transparencia</h1>


<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="container-fluid">
    <div class="full-box tile-container">
        <a href="actividad_especial_areas.php?a=<?php echo $area ?>" class="tileO">
            <div class="tileO-tittle" >Actividades Especiales</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    rate_review
                </span>
            </div>
        </a>
		
		<a href="sesiones_comite_ut.php" class="tileO">
            <div class="tileO-tittle" >Sesiones Comite UT</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    groups
                </span>
            </div>
        </a>
		
		<a href="obligaciones_transparencia.php" class="tileO">
            <div class="tileO-tittle" style="font-size: 13px;" >Obligaciones Transparencia</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    data_check
                </span>
            </div>
        </a>
		
        <a href="solicitudes_ut.php" class="tile">
            <div class="tile-tittle" style="font-size: 13px;">Solicitudes de Información</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
					contact_support
				</span>
                <i class="fas fa-user-tie"></i>
            </div>
        </a>
		<a href="recursos_ut.php" class="tile">
            <div class="tile-tittle">Recursos Revisión</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
					warning
				</span>
                <i class="fas fa-user-tie"></i>
            </div>
        </a>
        <a href="denuncias_ut.php" class="tile">
            <div class="tile-tittle">Denuncias</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    sync_problem
                </span>
                <i class="fas fa-user-tie"></i>
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
		
        <a href="capacitaciones.php?a=<?php echo $area ?>" class="tile">
            <div class="tile-tittle">Capacitaciones</div>
            <div class="tile-icon">
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
        <a href="informes_areas.php?a=<?php echo $area ?>" class="tile">
            <div class="tile-tittle">Informe Actividades</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    task_alt
                </span>
            </div>
        </a>
		<a href="solicitudes_correspondencia.php?a=<?php echo $area ?>" class="tile">
            <div class="tile-tittle">Corresppondencia</div>
		<span class="position-absolute top-1000 start-1000 translate-middle badge rounded-pill bg-danger" title="<?php echo $total_correspondencia; ?> Oficios No Atendidos">
		<?php echo $no_atendidos['total']; ?>
                            <span class="visually-hidden" >unread messages</span>
                        </span>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    local_post_office
                </span>
            </div>
        </a>
       
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>