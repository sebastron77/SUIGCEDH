<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Áreas';
require_once('includes/load.php');
$user = current_user();
$id_usuario = $user['id_user'];

$busca_area = area_usuario($id_usuario);
$otro = $busca_area['nivel_grupo'];
$nivel = $user['user_level'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 5) {
    page_require_level_exacto(5);
}
if ($nivel == 7) {
    page_require_level_exacto(7);
}
// if ($nivel == 19) {
//     page_require_level_exacto(19);
// }
if ($nivel == 21) {
    page_require_level_exacto(21);
}
if ($nivel == 50) {
    page_require_level_exacto(50);
}

if ($nivel > 2 && $nivel < 5) :
    redirect('home.php');
endif;
if ($nivel > 5 && $nivel < 7) :
    redirect('home.php');
endif;
if ($nivel > 7 && $nivel < 19) :
    redirect('home.php');
endif;
if ($nivel > 19 && $nivel < 21) :
    redirect('home.php');
endif;

?>

<?php
// $c_categoria     = count_by_id('categorias');
$c_user = count_by_id('users', 'id_user');
$c_trabajadores = count_by_id('detalles_usuario', 'id_det_usuario');
$c_areas = count_by_id('area', 'id_area');
$c_cargos = count_by_id('cargos', 'id_cargos');
?>

<?php include_once('layouts/header.php'); ?>

<h1 style="color:#3A3D44; margin-left: 10px;">Áreas</h1>

<div class="row">
	<div class="col-md-12">
		<?php echo display_msg($msg); ?>
	</div>
</div>

<div class="organigrama">
    <ul>
        <li>
          <a href="#">Director</a>
            <ul>
                <li>
                  <a href="#">Vicepresidente</a>
                  <ul>
                    <li>
                      <a href="#">Vicepresidente</a>
                    </li>
                    <li>
                      <a href="#">Vicepresidentes</a>
                    </li>
                  </ul>              
                </li>
              
                <li><a href="#">Vicepresidente</a>
                  <ul>
                    <li><a href="#">Vicepresidente</a>
                  </ul>
              </li>
            </ul>
        </li>
    </ul>
</div>

<div class="container-fluid">
	<div class="full-box tile-container">
		<?php if (($otro <= 2) || ($otro == 7) || ($otro == 21)) : ?>
			<a href="solicitudes_consejo.php" class="tileA">
				<div class="tileA-tittle">Consejo</div>
				<div class="tileA-icon">
					<span class="material-symbols-rounded" style="font-size: 95px;">
						groups_2
					</span>
				</div>
			</a>
		<?php endif ?>
		<?php if (($otro <= 2) || ($otro == 7) || ($otro == 21)) : ?>
			<a href="solicitudes_presidencia.php" class="tileA">
				<div class="tileA-tittle">Presidencia</div>
				<div class="tileA-icon">
					<span class="material-symbols-rounded" style="font-size: 95px;">
						person
					</span>
				</div>
			</a>
		<?php endif ?>

		<?php if (($otro == 7) || ($otro <= 2) || ($otro == 21)) : ?>
			<a href="solicitudes_tecnica.php" class="tileA">
				<div class="tileA-tittle">Secretaría Técnica</div>
				<div class="tileA-icon">
					<span class="material-symbols-rounded" style="font-size: 95px;">
						account_box
					</span>
				</div>
			</a>
		<?php endif ?>
		
		<?php if (($otro == 7) || ($otro <= 2) || ($otro == 21)) : ?>
			<a href="solicitudes_ejecutiva.php" class="tileA">
				<div class="tileA-tittle">Secretaría Ejecutiva</div>
				<div class="tileA-icon">
					<span class="material-symbols-rounded" style="font-size: 95px;">
						next_week
					</span>
				</div>
			</a>
		<?php endif ?>

		<?php if (($otro <= 2) || ($otro == 7) || ($otro == 21)) : ?>
			<a href="#" class="tileA">
				<div class="tileA-tittle">Mecanismos y Agendas</div>
				<div class="tileA-icon">
					<span class="material-symbols-rounded" style="font-size: 95px;">
						calendar_view_month
					</span>
				</div>
			</a>
		<?php endif ?>
		
<?php if (($otro <= 2) || ($otro == 12)) : ?>
			<a href="#" class="tileA">
				<div class="tileA-tittle">Desaparecidos</div>
				<div class="tileA-icon">
					<span class="material-symbols-rounded" style="font-size: 95px;">
						person_search
					</span>
				</div>
			</a>
		<?php endif ?>

		<?php if (($otro == 5) || ($otro <= 2) || ($otro == 7) || ($otro == 19) || ($otro == 20) || ($otro == 21) || ($otro == 50)) : ?>
			<a href="solicitudes_quejas.php" class="tileA">
				<div class="tileA-tittle">Quejas y Seguimiento</div>
				<div class="tileA-icon">
					<span class="material-symbols-rounded" style="font-size: 95px;">
						book
					</span>
				</div>
			</a>
		<?php endif ?>
		
		<?php if ( ($otro <= 2)) : ?>
			<a href="solicitudes_gestion.php" class="tileA">
				<div class="tileA-tittle">Unidad de Gestión</div>				
				<div class="tileA-icon">
					<span class="material-symbols-rounded" style="font-size: 95px;">
						rebase_edit
					</span>
				</div>
			</a>
		<?php endif ?>
		<?php if (($otro <= 2) || ($otro == 7) || ($otro == 11)) : ?>
			<a href="solicitudes_transparencia.php" class="tileA">
				<div class="tileA-tittle" style="font-size: 13px;">Unidad de Transparencia</div>				
				<div class="tileA-icon">
					<span class="material-symbols-rounded" style="font-size: 95px;">
						travel_explore
					</span>
				</div>
			</a>
		<?php endif ?>
	</div>
</div>
<br>
<?php include_once('layouts/footer.php'); ?>