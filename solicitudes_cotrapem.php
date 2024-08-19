<?php
$page_title = 'Secretaría Técnica';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 3) {
    page_require_level_exacto(3);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo las Solicitudes COTRAPEM', 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo las Solicitudes COTRAPEM', 5);
    page_require_level_exacto(53);
}

if ($nivel_user > 3 && $nivel_user < 5) :
    redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 53) :
    redirect('home.php');
endif;
?>

<?php
$c_user = count_by_id('users', 'id_user');
?>

<?php include_once('layouts/header.php'); ?>

<a href="solicitudes_ejecutiva.php" class="btn btn-info">Regresar a Áreas</a><br><br>
<h1 style="color: #3a3d44;"> Solicitudes COTRAPEM</h1>


<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="container-fluid">
    <div class="full-box tileO-container">
        <a href="cotrapem.php" class="tile">
            <div class="tile-tittle">Sesiones COTRAPEM</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    category
                </span>
            </div>
        </a>
        <a href="actividades_cotrapem.php" class="tile">
            <div class="tile-tittle">Actividades sobre Trata</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    data_check
                </span>
            </div>
        </a>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>