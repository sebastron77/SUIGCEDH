<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Áreas';
require_once('includes/load.php');
$user = current_user();
$id_usuario = $user['id_user'];

$busca_area = area_usuario($id_usuario);
$otro = $busca_area['nivel_grupo'];
$nivel = $user['user_level'];

page_require_level(53);
$c_user = count_by_id('users', 'id_user');
$c_trabajadores = count_by_id('detalles_usuario', 'id_det_usuario');
$c_areas = count_by_id('area', 'id_area');
$c_cargos = count_by_id('cargos', 'id_cargos');
?>

<?php include_once('layouts/header.php'); ?>

<h1 style="color:#3A3D44; text-align: center; font-size: 35px; margin-bottom: 2%;">ÁREAS DE LA CEDH</h1>

<div class="row">
	<div class="col-md-12">
		<?php echo display_msg($msg); ?>
	</div>
</div>

<div class="container-fluid">
	<div class="full-box tile-container">

		<div class="organigrama">
			<ul>
				<li>
					<a href="<?php if (($otro <= 3) || ($otro == 7) || ($otro == 21) || ($otro == 53)) : echo "solicitudes_consejo.php";	endif ?>" class="tileA">
						<div class="tileA-tittle">Consejo</div>
						<div class="tileA-icon">
							<span class="material-symbols-rounded" style="font-size: 85px;">
								groups_2
							</span>
						</div>
					</a>
					
					<ul>
						<li>
							<a href="<?php if (($otro <= 2) || ($otro == 7)  || ($otro == 52) || ($otro == 53)) : echo "solicitudes_presidencia.php"; endif ?>" class="tileA">
								<div class="tileA-tittle">Presidencia</div>
								<div class="tileA-icon">
									<span class="material-symbols-rounded" style="font-size: 85px;">
										person
									</span>
								</div>
							</a>
							<ul>
								<li>
									<a href="<?php if (($otro == 7) || ($otro <= 2) || ($otro == 3) || ($otro == 53)) : echo "solicitudes_ejecutiva.php";
												endif ?>" class="tileA">
										<div class="tileA-tittle">Secretaría Ejecutiva</div>
										<div class="tileA-icon">
											<span class="material-symbols-rounded" style="font-size: 85px;">
												next_week
											</span>
										</div>
									</a>
								</li>
								<li>
									<a href="<?php if (($otro == 7) || ($otro <= 2) || ($otro == 21) || ($otro == 51) || ($otro == 53)) : echo "solicitudes_tecnica.php";
												endif ?>" class="tileA">
										<div class="tileA-tittle">Secretaría Técnica</div>
										<div class="tileA-icon">
											<span class="material-symbols-rounded" style="font-size: 85px;">
												account_box
											</span>
										</div>
									</a>
									<ul>
										<li>
											<a href="<?php if (($otro <= 2) || ($otro == 7) || ($otro == 12) || ($otro == 53)) : echo "solicitudes_desaparecidos.php";
														endif ?>" class="tileA">
												<div class="tileA-tittle">Desaparecidos</div>
												<div class="tileA-icon">
													<span class="material-symbols-rounded" style="font-size: 85px;">
														person_search
													</span>
												</div>
											</a>
										</li>

										<li>
											<a href="<?php if (($otro <= 2) || ($otro == 7) || ($otro == 10) || ($otro == 53)) : echo "solicitudes_transparencia.php";
														endif ?>" class="tileA">
												<div class="tileA-tittle">Transparencia</div>
												<div class="tileA-icon">
													<span class="material-symbols-rounded" style="font-size: 85px;">
														travel_explore
													</span>
												</div>
											</a>
										</li>
										
										<li>
											<a href="<?php if (($otro <= 2) || ($otro == 7) || ($otro == 11) || ($otro == 53)) : echo "solicitudes_archivo.php";
														endif ?>" class="tileA">
												<div class="tileA-tittle">Archivo</div>
												<div class="tileA-icon">
													<span class="material-symbols-rounded" style="font-size: 85px;">
														inventory_2
													</span>
												</div>
											</a>
										</li>
										
										<li>
											<a href="<?php if (($otro <= 2) || ($otro == 4) || ($otro == 7) || ($otro == 9)|| ($otro == 22) || ($otro == 53)) : echo "solicitudes_servicios_tecnicos.php";
														endif ?>" class="tileA">
												<div class="tileA-icon">
												<div class="tileA-tittle">Servicios Técnicos</div>
													<span class="material-symbols-rounded" style="font-size: 85px;">
														procedure
													</span>
												</div>
											</a>
										</li>
									</ul>
								</li>
								<li>
									<a href="<?php if ( ($otro <= 2) || ($otro == 6) || ($otro == 7) || ($otro == 53)) : echo "solicitudes_centro_estudios.php";endif ?>" class="tileA">
										<div class="tileA-tittle">Centro Estudios</div>
										<div class="tileA-icon">
											<span class="material-symbols-rounded" style="font-size: 85px;">
												local_library
											</span>
										</div>
									</a>
									<ul>
										<li>
											<a href="<?php if (($otro <= 2) || ($otro == 7) || ($otro == 23) || ($otro == 53)) : echo "solicitudes_equidad.php";
														endif ?>" class="tileA">
												<div class="tileA-icon">
												<div class="tileA-tittle">Equidad Género</div>
													<span class="material-symbols-rounded" style="font-size: 85px;">
														diversity_2
													</span>
												</div>
											</a>
										</li>
										<li>
											<a href="<?php if (($otro <= 2) || ($otro == 7) || ($otro == 24) || ($otro == 53)) : echo "solicitudes_grupo.php";
														endif ?>" class="tileA">
												<div class="tileA-icon">
												<div class="tileA-tittle">Grupos Vulnerables</div>
													<span class="material-symbols-rounded" style="font-size: 85px;">
														groups_3
													</span>
												</div>
											</a>
										</li>
									</ul>
								</li>
								<li>
							
									<a href="<?php if (($otro == 5) || ($otro <= 2) || ($otro == 7) || ($otro == 19) || ($otro == 20) || ($otro == 21) || ($otro == 25) || ($otro == 26) ||($otro == 50) || ($otro == 53)):echo "solicitudes_quejas.php";
											endif ?>" class="tileA">
										<div class="tileA-tittle">Quejas y Seguimiento</div>
										<div class="tileA-icon">
											<span class="material-symbols-rounded" style="font-size: 85px;">
												book
											</span>
										</div>
									</a>
								</li>

								<li>
								
									<a href="<?php if (($otro <= 2) || ($otro == 7)  || ($otro == 17) || ($otro == 36) || ($otro == 53)) : echo "solicitudes_agendas.php"; endif ?>" class="tileA">
										<div class="tileA-tittle" style="font-size: 13px;">Mecanismos y Agendas</div>
										<div class="tileA-icon">
											<span class="material-symbols-rounded" style="font-size: 85px;">
												calendar_view_month
											</span>
										</div>
									</a>
								</li>


								<li>
									<a href="<?php if (($otro <= 2) ||($otro == 14) ) : echo "solicitudes_gestion.php";
												endif ?>" class="tileA">
										<div class="tileA-tittle">coord.Administrativa</div>
										<div class="tileA-icon">
											<span class="material-symbols-rounded" style="font-size: 85px;">
												rebase_edit
											</span>
										</div>
									</a>
								</li>


								<li>
									<a href="<?php if (($otro <= 2) || ($otro == 7) || ($otro == 15) || ($otro == 53)) : echo "solicitudes_comunicacion_social.php";endif ?>" class="tileA">
										<div class="tileA-tittle">Comunicación Social</div>
										<div class="tileA-icon">
											<span class="material-symbols-rounded" style="font-size: 85px;">
												contact_mail
											</span>
										</div>
									</a>
								</li>
								
								<li>
									<a href="<?php if (($otro <= 2) || ($otro == 7) || ($otro == 13) || ($otro == 53)) : echo "solicitudes_sistemas.php";endif ?>" class="tileA">
										<div class="tileA-tittle">Coordinación Sistemas</div>
										<div class="tileA-icon">
											<span class="material-symbols-rounded" style="font-size: 85px;">												
												touchpad_mouse
											</span>
										</div>
									</a>
								</li>

							</ul>
						</li>
					</ul>
				</li>			
			</ul>
		</div>
	</div>
</div>
<br>
<br>
<div class="container-fluid">		
    <div class="full-box tile-container" style="text-align: right; padding: 0PX 100PX;">
	
	 <a href="oficialia_correspondencia.php" class="tileA">
            <div class="tileA-tittle">Oficios-Oficialía</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    outgoing_mail
                </span>
            </div>
        </a>
	</div>
</div>
<br>

</span>
<?php include_once('layouts/footer.php'); ?>