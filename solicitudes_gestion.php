<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Solicitudes - Administrativo';
require_once('includes/load.php');
$user = current_user();

$user = current_user();
$id_user = $user['id_user'];
$busca_area = area_usuario($id_user);
$otro = $busca_area['nivel_grupo'];
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}
if ($nivel_user == 2) {
    page_require_level_exacto(2);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
}
if ($nivel_user == 35) {
    page_require_level_exacto(35);
}
if ($nivel_user > 2 && $nivel_user < 14) :
    redirect('home.php');
endif;
if ($nivel_user > 14) {
    redirect('home.php');
}
if (!$nivel_user) {
    redirect('home.php');
}
?>

<?php
// $c_categoria     = count_by_id('categorias');
$c_user = count_by_id('users', 'id_user');
$c_trabajadores = count_by_id('detalles_usuario', 'id_det_usuario');
$c_areas = count_by_id('area', 'id_area');
$c_cargos = count_by_id('cargos', 'id_cargos');
$area = 41;
?>

<?php include_once('layouts/header.php'); ?>

<a href="solicitudes.php" class="btn btn-info">Regresar a Áreas</a><br><br>
<h1 style="color:#3A3D44">Procesos de Coordinación Administrativa</h1>


<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="container-fluid">
    <div class="full-box tile-container">
        <?php if($nivel_user == 1 || $nivel_user == 2 || $nivel_user == 14): ?>
        <a href="detalles_usuario.php" class="tile">
            <div class="tile-tittle">Recursos Humanos</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    reduce_capacity
                </span>
            </div>
        </a>
        <a href="desp_presupuesto.php" class="tile">
            <div class="tile-tittle">Presupuesto</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    currency_exchange
                </span>
            </div>
        </a>

        <a href="solicitudes_materiales.php" class="tile">
            <div class="tile-tittle">Recursos Materiales</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    storefront
                </span>
            </div>
        </a>

        <a href="solicitudes_adquisiciones.php" class="tile">
            <div class="tile-tittle">Adquisiciones</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    shopping_cart
                </span>
            </div>
        </a>

        <a href="desp_auditorias.php" class="tile">
            <div class="tile-tittle">Auditorias</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    checklist
                </span>
            </div>
        </a>
        <!--<a href="control_auditorio.php" class="tile">
            <div class="tile-tittle">Control Auditorio</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">				
					patient_list
                </span>
            </div>
        </a>
		-->
        <a href="pat.php?a=<?php echo $area ?>" class="tileO">
            <div class="tileO-tittle" style="font-size: 12px;">Programa Anual de Trabajo</div>
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
            <div class="tile-tittle">Correspondencia</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    local_post_office
                </span>
            </div>
        </a>
        <a href="bienes_inmuebles.php" class="tile">
            <div class="tile-tittle">Bienes Inmuebles</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    apartment
                </span>
            </div>
        </a>
        <a href="solicitudes_vehiculos.php" class="tile">
            <div class="tile-tittle">Parque Vehicular</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    garage
                </span>
            </div>
        </a>
        <a href="solicitudes_inventario.php" class="tile">
            <div class="tile-tittle">Inventario</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    warehouse
                </span>
            </div>
        </a>
        <?php endif; ?>
        <a href="QR/index.php" class="tile">
            <div class="tile-tittle">Credenciales</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    badge
                </span>
            </div>
        </a>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>