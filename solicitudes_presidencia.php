<?php
$page_title = 'Presidencia';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$id_grupo=2;
$area= 2;

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level(7);
}
if ($nivel_user == 52) {
    page_require_level_exacto(52);
}

if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;

if ($nivel_user > 7 && $nivel_user < 52) :
    redirect('home.php');
endif;
?>

<?php include_once('layouts/header.php'); ?>

<a href="solicitudes.php" class="btn btn-info">Regresar a Áreas</a><br><br>
<h1 style="color: #3a3d44;">Solicitudes de Presidencia</h1>


<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<?php if(($nivel_user <= 2) ||($nivel_user == 52) ){?>
<div class="container-fluid">
    <div class="full-box tileO-container">
        <a href="add_gestion.php?a=1" class="tileO">
            <div class="tileO-tittle">Acciones Incost.</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    gavel
                </span>
            </div>
        </a>
<?php }?>
       
        <a href="eventos_pres.php" class="tileO">
            <div class="tileO-tittle">Actividades</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    arrow_circle_right
                </span>
            </div>
        </a>
<?php if(($nivel_user <= 2) ||($nivel_user == 52)){?>		
        <a href="add_gestion.php?a=3" class="tileO">
            <div class="tileO-tittle">Amicus Curiae</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    groups
                </span>
            </div>
        </a>
<?php }?>		
<?php if(($nivel_user <= 2) ||($nivel_user == 52)){?>		
        <a href="#" class="tileO">
            <div class="tileO-tittle">Ámparo</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    balance
                </span>
            </div>
        </a>
<?php }?>		
<?php if(($nivel_user <= 2) ||($nivel_user == 52)){?>		
        <a href="add_gestion.php?a=<?php echo $area ?>" class="tileO">
            <div class="tileO-tittle">Controversia Const.</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    account_balance
                </span>
            </div>
        </a>
<?php }?>       
        <a href="gestiones.php" class="tileO">
            <div class="tileO-tittle">Gestión Jurisdiccional</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">send_time_extension</span>
            </div>
        </a>

        <a href="recomendaciones_generales.php" class="tileO">
            <div class="tileO-tittle">Recomendaciones</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    breaking_news_alt_1
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
		
       
        <a href="informes_areas.php?a=<?php echo $area ?>" class="tile">
            <div class="tile-tittle">Informe Actividades</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    task_alt
                </span>
            </div>
        </a>
        <a href="solicitudes_correspondencia_presidencia.php" class="tile">
            <div class="tile-tittle">Corresppondencia</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    local_post_office
                </span>
            </div>
        </a>
		
		<a href="competencia.php" class="tile">
            <div class="tile-tittle" style="font-size: 12px;">Conflictos Competenciales</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    find_in_page
                </span>
            </div>
        </a>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>