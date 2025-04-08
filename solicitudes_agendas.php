<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Solicitudes - Agendas y Mecanismos';
require_once('includes/load.php');
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$area = 18;

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo ' . $page_title, 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 17) {
    page_require_level_exacto(17);
}
if ($nivel_user == 36) {
    page_require_level_exacto(36);
}
if ($nivel_user == 53) {
    insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo ' . $page_title, 5);
    page_require_level_exacto(53);
}

if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7  && $nivel_user < 17) :
    redirect('home.php');
endif;
if ($nivel_user > 17  && $nivel_user < 36) :
    redirect('home.php');
endif;
if ($nivel_user > 36 && $nivel_user < 53) :
    redirect('home.php');
endif;

?>

<?php include_once('layouts/header.php'); ?>

<a href="solicitudes.php" class="btn btn-info">Regresar a Áreas</a><br><br>
<h1 style="color:#3A3D44">Solicitudes de Agendas y Mecanismos</h1>


<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="container-fluid">
    <div class="full-box tile-container">
        <?php if ($nivel_user != 36): ?>
            <a href="agenda_entregables.php" class="tileO">
                <div class="tileO-tittle">Entregables</div>
                <div class="tileO-icon">
                    <span class="material-symbols-rounded" style="font-size:95px;">
                        photo_album
                    </span>
                </div>
            </a>

            <a href="agenda_informes.php" class="tileO">
                <div class="tileO-tittle">Informes Especiales</div>
                <div class="tileO-icon">
                    <span class="material-symbols-rounded" style="font-size:95px;">
                        bookmark_added
                    </span>
                </div>
            </a>

            <a href="pat.php?a=<?php echo $area ?>" class="tileO">
                <div class="tileO-tittle" style="font-size: 12px;">Programa Anual de Trabajo</div>
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
        <?php endif; ?>
        <a href="supervision_mecanismos.php" class="tileO">
            <div class="tileO-tittle">Supervisión</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    search
                </span>
            </div>
        </a>
        <a href="reuniones_vinculacion.php?t=m" class="tileO">
            <div class="tileO-tittle">Reunión Vinculación</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    groups_2
                </span>
            </div>
        </a>
        <a href="minutas.php" class="tileO">
            <div class="tileO-tittle">Minutas</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    text_snippet
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
            <div class="tile-tittle">Correspondencia</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    local_post_office
                </span>
            </div>
        </a>

    </div>
</div><br>

<?php include_once('layouts/footer.php'); ?>