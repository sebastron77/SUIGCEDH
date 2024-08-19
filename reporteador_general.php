<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Reporteador';
require_once('includes/load.php');

$user = current_user();
$nivel = $user['user_level'];
if ($nivel <= 2) {
	page_require_level(2);
} else {
	if ($nivel == 5) {
		page_require_level_exacto(5);
	} else {
		if ($nivel == 7) {
			page_require_level_exacto(7);
		} else {
			if ($nivel == 14) {
				page_require_level_exacto(14);
			} else {
				if ($nivel == 50) {
					page_require_level_exacto(50);
				} else {
					redirect('home.php');
				}
			}
		}
	}
}
?>


<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
	$(document).ready(function() {
		$("#tipo_solicitud").change(function() {

			var pagina = "";

			if ($(this).val() == 'q') {
				pagina = "busquedaquejas.php";
			} else {
				if ($(this).val() == 'o') {
					pagina = "busquedaorientaciones.php";
				} else {
					if ($(this).val() == 'c') {
						pagina = "busquedacanalizaciones.php";
					} else {
						if ($(this).val() == 'a') {
							pagina = "busquedactuaciones.php";
						} else {
							if ($(this).val() == 'ac') {
								pagina = "busqueactividadesareas.php";
							} else {
								if ($(this).val() == 'ca') {
									pagina = "busquedacapacitaciones.php";
								} else {
									if ($(this).val() == 'p') {
										pagina = "busquedapersonal.php";
									} else {
										if ($(this).val() == 'aq') {
											pagina = "busquedacuerdos.php";
										} else {
											if ($(this).val() == 'pat') {
												pagina = "busquedapat.php";
											} else {
												if ($(this).val() == 'e') {
													pagina = "busquedaeventos.php";
												} else {
													if ($(this).val() == 'vd') {
														pagina = "busquedavacaciones.php";
													} else {
														pagina = "";
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
			$("#acctionURL").attr("src", pagina);

		});
	});
</script>
<?php echo display_msg($msg); ?>
<div class="row">
	<div class="panel panel-default">
		<div class="panel-heading">
			<strong>
				<span class="glyphicon glyphicon-th"></span>
				<span>Reporteador General</span>
			</strong>
		</div>
		<div class="panel-body">
			<form method="post" action="add_quejoso.php">
				<h1>Datos búsqueda</h1>
				<div class="row" style="margin-top: 2%">
					<div class="col-md-2">
						<div class="form-group">
							<label for="nombreQ">Tipo Solicitud</label>
							<select class="form-control" name="tipo_solicitud" id="tipo_solicitud">
								<option value="0">Escoge una opción</option>
								<?php if (($nivel == 1) || ($nivel == 2) ||  ($nivel == 5) || ($nivel == 7) || ($nivel == 50)) : ?>
									<option value="aq">Acuerdos/Quejas</option>
								<?php endif; ?>
								<?php if (($nivel == 1) || ($nivel == 2) ||  ($nivel == 7) ||  ($nivel == 50)) : ?>
									<option value="q">Quejas</option>
									<option value="o">Orientaciones</option>
									<option value="c">Canalizaciones</option>
								<?php endif; ?>
								<?php if (($nivel == 6)) : ?>
									<option value="ca">Capacitaciones</option>
								<?php endif; ?>
								<?php if (($nivel == 1) || ($nivel == 2) ||  ($nivel == 7)) : ?>
									<option value="ca">Capacitaciones</option>
									<option value="e">Eventos</option>
									<option value="ac">Actividades Áreas</option>
									<option value="pat">Indicadores PAT</option>
								<?php endif; ?>
								<?php if (($nivel == 1) || ($nivel == 2) ||  ($nivel == 14)) : ?>
									<option value="p">Personal</option>
									<option value="vd">Vacaciones</option>
								<?php endif; ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row" style="margin-top: 2%">
					<iframe id="acctionURL" src="" style="position:absoluta; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:750px; border:none; margin:0; padding:0; overflow:hidden;"> </iframe>


				</div>
			</form>


		</div>
	</div>
</div>


<?php include_once('layouts/footer.php'); ?>