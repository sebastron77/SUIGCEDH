<?php
$page_title = 'Coordinación de Sistemas';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$area= 1;

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);  
    page_require_level(7);
}
if ($nivel_user == 13) {
    page_require_level(13);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);  
    page_require_level(53);
}
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 13) :
    redirect('home.php');
endif;
if ($nivel_user > 13 && $nivel_user < 53) :
    redirect('home.php');
endif;
?>

<?php
$c_user = count_by_id('users', 'id_user');
$c_trabajadores = count_by_id('detalles_usuario', 'id_det_usuario');
$c_areas = count_by_id('area', 'id_area');
$c_cargos = count_by_id('cargos', 'id_cargos');
$no_atendidos= count_oficiosInter($area);
$no_atendidos_ext= count_oficiosExt($area);
$total_correspondencia = (int)$no_atendidos['total'] + (int)$no_atendidos_ext['total'];
?>

<?php include_once('layouts/header.php'); ?>

<a href="solicitudes.php" class="btn btn-info">Regresar a Áreas</a><br><br>
<h1 style="color: #3a3d44;">Solicitudes de la Cooridnación de Sistemas de Informática</h1>


<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="container-fluid">

    <div class="full-box tileO-container">  

		 <a href="herramientas_sistemas.php" class="tile">
            <div class="tile-tittle">Sistemas Implementados</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    terminal
                </span>
            </div>
        </a> 
		
		<a href="acciones_sistemas.php" class="tile">
            <div class="tile-tittle">Aciones Relevantes</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    support_agent
                </span>
            </div>
        </a> 
		
		<a href="visitas_web.php" class="tile">
            <div class="tile-tittle">Visitas Web</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    travel_explore
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
        <a href="informes_areas.php?a=<?php echo $area ?>" class="tileO">
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