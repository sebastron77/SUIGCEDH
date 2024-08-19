<?php
$page_title = 'Secretaría Ejecutiva';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$area =10;

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 3) {
    page_require_level_exacto(3);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo las Solicitudes de la Secretaría Ejecutiva', 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 21) {
    page_require_level_exacto(21);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo las Solicitudes de la Secretaría Ejecutiva', 5);
    page_require_level_exacto(53);
}

if ($nivel_user > 3 && $nivel_user < 5) :
    redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 21) :
    redirect('home.php');
endif;

if ($nivel_user > 21 && $nivel_user < 53) :
    redirect('home.php');
endif;
?>

<?php
$c_user = count_by_id('users', 'id_user');
?>

<?php include_once('layouts/header.php'); ?>

<a href="solicitudes.php" class="btn btn-info">Regresar a Áreas</a><br><br>
<h1 style="color: #3a3d44;"> Solicitudes Secretaría Ejecutiva</h1>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="container-fluid">
    <div class="full-box tileO-container">
               
		 
        <a href="consejo.php" class="tileO">
            <div class="tileO-tittle">Actas sesión de consejo</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    receipt_long
                </span>

            </div>
        </a>
		
        <a href="convenios.php" class="tileO">
            <div class="tileO-tittle">Convenios</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    description
                </span>
            </div>
        </a>	
		<a href="acciones_vinculacion.php" class="tile">
            <div class="tile-tittle">Acciones Vinculación</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    compare_arrows
                </span>
            </div>
        </a>
        <a href="solicitudes_cotrapem.php" class="tile">
            <div class="tile-tittle">Trata de Personas</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    balance
                </span>
            </div>
        </a>	

 <a href="servicio_social.php" class="tile">
            <div class="tile-tittle">Servicio Social</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    person_raised_hand
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
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    local_post_office
                </span>
            </div>
        </a>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>
