<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Quejas';
require_once('includes/load.php');
?>
<?php
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$user = current_user();
$nivel_user = $user['user_level'];
$id_u = $user['id_user'];
$area_user = muestra_area($id_u);
$notificacion = notificacion();
$date_now = date("d-m-Y");


if ($nivel_user <= 2) {
	page_require_level(2);
}
if ($nivel_user == 5) {
	page_require_level_exacto(5);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo ' . $page_title . ' del Ejercicio ' . $ejercicio, 5);
	page_require_level_exacto(7);
}
if ($nivel_user == 19) {
	page_require_level_exacto(19);
}
if ($nivel_user == 26) {
	page_require_level_exacto(26);
}
if ($nivel_user == 21) {
	page_require_level_exacto(21);
}
if ($nivel_user == 50) {
	page_require_level_exacto(50);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo ' . $page_title . ' del Ejercicio ' . $ejercicio, 5);
	page_require_level_exacto(53);
}

if ($nivel_user > 2 && $nivel_user < 5) :
	redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 7) :
	redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 19) :
	redirect('home.php');
endif;
if ($nivel_user > 19 && $nivel_user < 21) :
	redirect('home.php');
endif;
if ($nivel_user > 21 && $nivel_user < 26) :
	redirect('home.php');
endif;
if ($nivel_user > 26 && $nivel_user < 50) :
	redirect('home.php');
endif;
if ($nivel_user > 50 && $nivel_user < 53) :
	redirect('home.php');
endif;




if (($nivel_user <= 2) || ($nivel_user == 7) || ($nivel_user == 21) || ($nivel_user == 50) || ($nivel_user == 53)) {
	$quejas_libro = find_all_quejas_admin($ejercicio);
} else {
	if ($nivel_user == 26) {
		$quejas_libro = find_all_quejas_region($ejercicio, $area_user['id_area']);
	} else {
		$quejas_libro = find_all_quejas($area_user['id_area'], $area_user['id_det_usuario'], $ejercicio);
	}


	/* if ($area_user['id_det_usuario'] == 92) {
        $quejas_libro = find_all_quejas_lc($ejercicio);
    }  if ($area_user['id_det_usuario'] == 133) {
        $quejas_libro = find_all_quejas_mor($ejercicio);
	}else {
        $quejas_libro = find_all_quejas($area_user['id_area'], $area_user['id_det_usuario'],$ejercicio);
    }
	*/
}

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT q.folio_queja,q.id_queja_date, mp.descripcion as medio_presentacion, au.nombre_autoridad as autoridad_responsable, cq.nombre as nombre_quejoso, cq.paterno a_paterno_quejoso,
            cq.materno a_materno_quejoso, ca.nombre as nombre_agraviado, ca.paterno as a_paterno_agraviado, ca.materno as a_materno_agraviado, u.username as usuario_creador, a.nombre_area as area_asignada,
            eq.descripcion as estatus_queja,tr.descripcion as tipo_resolucion,ta.descripcion as tipo_ambito, q.fecha_presentacion, mp.descripcion as medio_presentacion, q.fecha_avocamiento, 
            cm.descripcion as municipio, q.incompetencia, q.causa_incomp, q.fecha_acuerdo_incomp, q.desechamiento, q.razon_desecha, q.forma_conclusion, q.fecha_conclusion, q.estado_procesal, 
            q.observaciones,  q.a_quien_se_traslada,  q.fecha_creacion, q.fecha_actualizacion, eq.descripcion as estatus_queja, q.archivo, q.dom_calle, q.dom_numero, q.dom_colonia, 
            REPLACE(q.descripcion_hechos,'\r\n',' ') as descripcion_hechos, tr.descripcion as tipo_resolucion, q.num_recomendacion, q.fecha_termino, ta.descripcion as tipo_ambito, 
			IF((IFNULL(total_der_gral,0)+IFNULL(total_der_vul,0)) >0,'Si','No')  calificado,
			 a.nombre_area, q.fecha_vencimiento
            FROM quejas_dates q
            LEFT JOIN cat_medio_pres mp ON mp.id_cat_med_pres = q.id_cat_med_pres
            LEFT JOIN cat_autoridades au ON au.id_cat_aut = q.id_cat_aut
            LEFT JOIN cat_quejosos cq ON cq.id_cat_quejoso = q.id_cat_quejoso
            LEFT JOIN cat_agraviados ca ON ca.id_cat_agrav = q.id_cat_agraviado
            LEFT JOIN users u ON u.id_user = q.id_user_creador
            LEFT JOIN area a ON a.id_area = q.id_area_asignada
            LEFT JOIN cat_estatus_queja eq ON eq.id_cat_est_queja = q.id_estatus_queja
            LEFT JOIN cat_tipo_res tr ON tr.id_cat_tipo_res = q.id_tipo_resolucion
            LEFT JOIN cat_tipo_ambito ta ON ta.id_cat_tipo_ambito = q.id_tipo_ambito
            LEFT JOIN cat_municipios cm ON cm.id_cat_mun = q.id_cat_mun 
			LEFT JOIN detalles_usuario du ON q.id_user_asignado =  du.id_det_usuario
			LEFT JOIN (  	SELECT COUNT(id_queja_date) as total_der_vul,id_queja_date FROM rel_queja_der_vuln  GROUP BY id_queja_date  )x ON(q.id_queja_date =x.id_queja_date)
			LEFT JOIN (  	SELECT COUNT(id_queja_date) as total_der_gral,id_queja_date FROM rel_queja_der_gral      GROUP BY id_queja_date  )y ON(q.id_queja_date =y.id_queja_date)			
			WHERE q.id_queja_date > 0 ";

if (($nivel_user <= 2) || ($nivel_user == 7) || ($nivel_user == 21) || ($nivel_user == 50) || ($nivel_user == 53)) {
	$sql .= " AND q.folio_queja LIKE '%/" . $ejercicio . "-%' ";
} else {
	if ($area_user['id_det_usuario'] == 92) {
		$sql .= " AND q.id_area_asignada = '23' AND q.folio_queja LIKE '%/" . $ejercicio . "-%' ";
	}
	if ($area_user['id_det_usuario'] == 133) {
		$sql .= " AND q.id_area_asignada = '24' AND q.folio_queja LIKE '%/" . $ejercicio . "-%' ";
	} else {
		$sql .= " AND q.id_area_asignada = '" . $area_user['id_area'] . "' AND du.id_det_usuario = '" . $area_user['id_det_usuario'] . "' AND q.folio_queja LIKE '%/" . $ejercicio . "-%' ";
	}
}

$sql .= " ORDER BY q.id_queja_date DESC";
$resultado = mysqli_query($conexion, $sql) or die;
$quejas = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
	$quejas[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
	if (!empty($quejas)) {
		header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
		header("Content-Disposition: attachment; filename=quejas.xls");
		$filename = "quejas.xls";
		$mostrar_columnas = false;

		foreach ($quejas as $datos) {
			if (!$mostrar_columnas) {
				echo utf8_decode(implode("\t", array_keys($datos)) . "\n");
				$mostrar_columnas = true;
			}
			echo utf8_decode(implode("\t", array_values($datos)) . "\n");
		}
		if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" descargó ' . $page_title . ' del Ejercicio ' . $ejercicio, 6);
		}
	} else {
		echo 'No hay datos a exportar';
	}
	exit;
}

?>
<script type="text/javascript">
	function changueAnio(anio) {
		//alert(anio);
		window.open("quejas.php?anio=" + anio, "_self");

	}
</script>

<?php include_once('layouts/header.php'); ?>

<div class="row">
	<div class="col-md-12">
		<?php echo display_msg($msg); ?>
	</div>
</div>


<div class="row">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<div class="col-md-10">
						<strong>
							<span class="glyphicon glyphicon-th"></span>
							<span>Lista de Quejas <?php echo $ejercicio ?></span>
						</strong>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<select class="form-control" name="ejercicio" onchange="changueAnio(this.value)">
								<option value="">Selecciona Ejercicio</option>
								<?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
									echo "<option value='" . $i . "'>" . $i . "</option>";
								} ?>
							</select>
						</div>
					</div>



					<a href="seach_queja.php" style="margin-left: 10px" class="btn btn-info pull-right">Búsqueda General</a>
					<?php if (($nivel_user == 1) || ($id_u == 3)) : ?>
						<a href="quejas_publicas.php" class="btn btn-primary position-relative" style="float: right; margin-top: 0px; margin-right: 5px; margin-left: 10px;">
							Quejas en Línea
							<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
								<?php echo $notificacion['total']; ?>
								<span class="visually-hidden">unread messages</span>
							</span>
						</a>
					<?php endif; ?>
					<?php if (($nivel_user == 1) || ($nivel_user == 5) || ($nivel_user == 26) || ($nivel_user == 50)) : ?>
						<a href="add_queja.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Queja</a>
					<?php endif; ?>
					<form action=" <?php echo $_SERVER["PHP_SELF"]; ?>?anio=<?php echo $ejercicio ?>" method="post">
						<button style="float: right; margin-top: 0px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
					</form>
				</div>
			</div>
		</div>
	</div>


	<div class="col-md-12">


		<div class="panel-body">
			<table class="datatable table table-bordered table-striped">
				<thead class="thead-purple">
					<tr>
						<th width="1%">#</th>
						<th width="1%">&nbsp;</th>
						<th width="">Folio</th>
						<th width="">Fecha presentación</th>
						<th width="8%">Medio presentación</th>
						<th width="">Área Asignada</th>
						<th width="10%">Asignado a</th>
						<th width="8%">Autoridad responsable</th>
						<th width="5%">Quejoso</th>
						<th width="5%">Agraviado</th>
						<th width="5%">Estado Procesal</th>
						<th width="1%">Tipo Resolución</th>
						<th>¿Calificada?</th>
						<th width="5%;" class="text-center">Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($quejas_libro as $queja) : ?>
						<tr>
							<td class="text-center"><?php echo count_id(); ?></td>
							<td class="text-center">
								<?php
								if ((int)$queja['estado_procesal'] == 9) { //// Queja en estado de Presentada
									$dia_vencimiento = date("d-m-Y", strtotime($queja['fecha_presentacion'] . "+ 3 days"));
									$dia_alerta = date("d-m-Y", strtotime($queja['fecha_presentacion'] . "+ 2 days"));
									if (strtotime($date_now) < strtotime($dia_alerta)) {
								?>
										<img src="medios/green.png" style="width: 21px; height: 20.5px; " title="Día de Vencimiento: <?php echo $dia_vencimiento; ?>">
										<?php } else {
										if ((strtotime($date_now) < strtotime($dia_vencimiento))) {
										?>
											<img src="medios/orange.png" style="width: 21px; height: 20.5px; " title="Día de Vencimiento:<?php echo $dia_vencimiento; ?>">
										<?php } else { ?>
											<img src="medios/red.png" style="width: 21px; height: 20.5px; " title="Día de Vencimiento:<?php echo $dia_vencimiento; ?>">
										<?php
										}
									}
								} else {
									if ((int)$queja['estado_procesal'] == 1) { ///queja en estado de En Tramite
										$dia_vencimiento = date("d-m-Y", strtotime($queja['fecha_estado_procesal'] . "+ 10 days"));
										$dia_alerta = date("d-m-Y", strtotime($queja['fecha_estado_procesal'] . "+ 7 days"));
										if (strtotime($date_now) < strtotime($dia_alerta)) {
										?>
											<img src="medios/green.png" style="width: 21px; height: 20.5px; " title="Día de Vencimiento:<?php echo $dia_vencimiento; ?>">
											<?php
										} else {
											if ((strtotime($date_now) < strtotime($dia_vencimiento))) {
											?>
												<img src="medios/orange.png" style="width: 21px; height: 20.5px; " title="Día de Vencimiento:<?php echo $dia_vencimiento; ?>">
											<?php
											} else {
											?>
												<img src="medios/red.png" style="width: 21px; height: 20.5px; " title="Día de Vencimiento:<?php echo $dia_vencimiento; ?>">
											<?php
											}
										}
									} else {
										if ((int)$queja['estado_procesal'] == 3) { ///queja en estado de Vista de Informe
											$dia_vencimiento = date("d-m-Y", strtotime($queja['fecha_estado_procesal'] . "+ 15 days"));
											$dia_alerta = date("d-m-Y", strtotime($queja['fecha_estado_procesal'] . "+ 13 days"));
											if (strtotime($date_now) < strtotime($dia_alerta)) {
											?>
												<img src="medios/green.png" style="width: 21px; height: 20.5px; " title="Día de Estado Procesal:<?php echo $queja['fecha_estado_procesal'] . "  Día de Vencimiento:" . $dia_vencimiento; ?>">
												<?php
											} else {
												if ((strtotime($date_now) < strtotime($dia_vencimiento))) {
												?>
													<img src="medios/orange.png" style="width: 21px; height: 20.5px; " title="Día de Estado Procesal:<?php echo $queja['fecha_estado_procesal'] . "- Día de Vencimiento:" . $dia_vencimiento; ?>">
												<?php
												} else {
												?>
													<img src="medios/red.png" style="width: 21px; height: 20.5px; " title="Día de Estado Procesal:<?php echo $queja['fecha_estado_procesal'] . " -  Día de Vencimiento:" . $dia_vencimiento; ?>">
												<?php
												}
											}
										} else {
											if ((int)$queja['estado_procesal'] == 4) { ///queja en estado de Periodo Probatorio
												$dia_vencimiento = date("d-m-Y", strtotime($queja['fecha_estado_procesal'] . "+ 30 days"));
												$dia_alerta = date("d-m-Y", strtotime($queja['fecha_estado_procesal'] . "+ 28 days"));
												if (strtotime($date_now) < strtotime($dia_alerta)) {
												?>
													<img src="medios/green.png" style="width: 21px; height: 20.5px; " title="Día de Estado Procesal:<?php echo $queja['fecha_estado_procesal'] . "- Día de Vencimiento:" . $dia_vencimiento; ?>">
													<?php
												} else {
													if ((strtotime($date_now) < strtotime($dia_vencimiento))) {
													?>
														<img src="medios/orange.png" style="width: 21px; height: 20.5px; " title="Día de Estado Procesal:<?php echo $queja['fecha_estado_procesal'] . "- Día de Vencimiento:" . $dia_vencimiento; ?>">
													<?php
													} else {
													?>
														<img src="medios/red.png" style="width: 21px; height: 20.5px; " title="Día de Estado Procesal:<?php echo $queja['fecha_estado_procesal'] . " - Día de Vencimiento:" . $dia_vencimiento; ?>">
													<?php
													}
												}
											} else {
												if ((int)$queja['estado_procesal'] == 5) { ///queja en estado de Etapa de Conciliación
													$dia_vencimiento = date("d-m-Y", strtotime($queja['fecha_estado_procesal'] . "+ 90 days"));
													$dia_alerta = date("d-m-Y", strtotime($queja['fecha_estado_procesal'] . "+ 80 days"));
													if (strtotime($date_now) < strtotime($dia_alerta)) {
													?>
														<img src="medios/green.png" style="width: 21px; height: 20.5px; " title="Día de Estado Procesal:<?php echo $queja['fecha_estado_procesal'] . "- Día de Vencimiento:" . $dia_vencimiento; ?>">
														<?php
													} else {
														if ((strtotime($date_now) < strtotime($dia_vencimiento))) {
														?>
															<img src="medios/orange.png" style="width: 21px; height: 20.5px; " title="Día de Estado Procesal:<?php echo $queja['fecha_estado_procesal'] . "- Día de Vencimiento:" . $dia_vencimiento; ?>">
														<?php
														} else {
														?>
															<img src="medios/red.png" style="width: 21px; height: 20.5px; " title="Día de Estado Procesal:<?php echo $queja['fecha_estado_procesal'] . " - Día de Vencimiento:" . $dia_vencimiento; ?>">
														<?php
														}
													}
												} else {
													if ((int)$queja['estado_procesal'] == 6) { ///queja en estado de Vías de Cumplimiento a Acuerdo Conciliatorio
														$dia_vencimiento = date("d-m-Y", strtotime($queja['fecha_estado_procesal'] . "+ 15 days"));
														$dia_alerta = date("d-m-Y", strtotime($queja['fecha_estado_procesal'] . "+ 13 days"));
														if (strtotime($date_now) < strtotime($dia_alerta)) {
														?>
															<img src="medios/green.png" style="width: 21px; height: 20.5px; " title="Día de Estado Procesal:<?php echo $queja['fecha_estado_procesal'] . "- Día de Vencimiento:" . $dia_vencimiento; ?>">
															<?php
														} else {
															if ((strtotime($date_now) < strtotime($dia_vencimiento))) {
															?>
																<img src="medios/orange.png" style="width: 21px; height: 20.5px; " title="Día de Estado Procesal:<?php echo $queja['fecha_estado_procesal'] . "- Día de Vencimiento:" . $dia_vencimiento; ?>">
															<?php
															} else {
															?>
																<img src="medios/red.png" style="width: 21px; height: 20.5px; " title="Día de Estado Procesal:<?php echo $queja['fecha_estado_procesal'] . " - Día de Vencimiento:" . $dia_vencimiento; ?>">
															<?php
															}
														}
													} else {
														if ((int)$queja['estado_procesal'] == 7) { ///queja en estado de En Etapa de Resolución
															$dia_vencimiento = date("d-m-Y", strtotime($queja['fecha_estado_procesal'] . "+ 25 days"));
															$dia_alerta = date("d-m-Y", strtotime($queja['fecha_estado_procesal'] . "+ 23 days"));
															if (strtotime($date_now) < strtotime($dia_alerta)) {
															?>
																<img src="medios/green.png" style="width: 21px; height: 20.5px; " title="Día de Estado Procesal:<?php echo $queja['fecha_estado_procesal'] . "- Día de Vencimiento:" . $dia_vencimiento; ?>">
																<?php
															} else {
																if ((strtotime($date_now) < strtotime($dia_vencimiento))) {
																?>
																	<img src="medios/orange.png" style="width: 21px; height: 20.5px; " title="Día de Estado Procesal:<?php echo $queja['fecha_estado_procesal'] . "- Día de Vencimiento:" . $dia_vencimiento; ?>">
																<?php
																} else {
																?>
																	<img src="medios/red.png" style="width: 21px; height: 20.5px; " title="Día de Estado Procesal:<?php echo $queja['fecha_estado_procesal'] . " - Día de Vencimiento:" . $dia_vencimiento; ?>">
																<?php
																}
															}
														} else {
															if ((int)$queja['estado_procesal'] == 8) { ///queja en estado de Conclusión De Trámite Ante Visitaduría/C.O.L.Q.S.
																if ((int)$queja['id_resolucion'] == 1) { //queja  con tipode Resolucion En trámite
																?>
																	<img src="medios/red.png" style="width: 21px; height: 20.5px; " title="Sin Resolución">
																<?php
																} else {
																?>
																	<img src="medios/verde.png" style="width: 21px; height: 20.5px; " title="Expediente Concluido">
																	<?php
																}
															} else {
																if ((int)$queja['estado_procesal'] == 2) { ///queja en estado de Solicitud e informe
																	$dia_vencimiento = date("d-m-Y", strtotime($queja['fecha_estado_procesal'] . "+ 10 days"));
																	$dia_alerta = date("d-m-Y", strtotime($queja['fecha_estado_procesal'] . "+ 7 days"));
																	if (strtotime($date_now) < strtotime($dia_alerta)) {
																	?>
																		<img src="medios/green.png" style="width: 21px; height: 20.5px; " title="Día de Estado Procesal:<?php echo $queja['fecha_estado_procesal'] . "- Día de Vencimiento:" . $dia_vencimiento; ?>">
																		<?php
																	} else {
																		if ((strtotime($date_now) < strtotime($dia_vencimiento))) {
																		?>
																			<img src="medios/orange.png" style="width: 21px; height: 20.5px; " title="Día de Estado Procesal:<?php echo $queja['fecha_estado_procesal'] . "- Día de Vencimiento:" . $dia_vencimiento; ?>">
																		<?php
																		} else {
																		?>
																			<img src="medios/red.png" style="width: 21px; height: 20.5px; " title="Día de Estado Procesal:<?php echo $queja['fecha_estado_procesal'] . " - Día de Vencimiento:" . $dia_vencimiento; ?>">
								<?php
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
								} ?>
							</td>
							<td>
								<?php echo remove_junk(ucwords($queja['folio_queja'])) ?>
							</td>
							<?php
							$folio_editar = $queja['folio_queja'];
							$resultado = str_replace("/", "-", $folio_editar);
							?>
							<td>
								<?php echo date_format(date_create(remove_junk(ucwords($queja['fecha_presentacion']))), "d-m-Y"); ?>
							</td>
							<td>
								<?php echo remove_junk(ucwords($queja['medio_pres'])) ?>
							</td>
							<td>
								<?php echo remove_junk(ucwords($queja['nombre_area'])) ?>
							</td>
							<td>
								<?php echo remove_junk(ucwords($queja['user_asignado'])) ?>
							</td>
							<td>
								<?php echo remove_junk(ucwords($queja['nombre_autoridad'])) ?>
							</td>
							<td>
								<?php echo remove_junk(ucwords($queja['nombre_quejoso'] . " " . $queja['paterno_quejoso'] . " " . $queja['materno_quejoso'])) ?>
							</td>
							<td>
								<?php echo remove_junk(ucwords($queja['nombre_agraviado'])); ?>
							</td>
							<td>
								<?php echo remove_junk(ucwords($queja['est_proc'])) ?>
							</td>
							<td>
								<?php echo remove_junk(ucwords($queja['id_tipo_resolucion'])) ?>
							</td>
							<td>
								<?php echo remove_junk(ucwords($queja['calificado'])) ?>
							</td>
							<td class="text-center">
								<div class="btn-group">
									<a href="ver_info_queja.php?id=<?php echo (int) $queja['id_queja_date']; ?>&t=0" title="Ver información">
										<img src="medios/ver_info.png" style="width: 31px; height: 30.5px; border-radius: 15%; margin-right: -2px;">
									</a>&nbsp;&nbsp;
									<?php if (($nivel_user <= 2) || ($nivel_user == 5) || ($nivel_user == 26) || ($nivel_user == 50)) : ?>
										<a href="edit_queja.php?id=<?php echo (int) $queja['id_queja_date']; ?>" title="Editar">
											<img src="medios/editar2.png" style="width: 31px; height: 30.5px; border-radius: 15%; margin-right: -2px;">
										</a>&nbsp;&nbsp;
										<a href="acuerdos_queja.php?id=<?php echo (int) $queja['id_queja_date']; ?>" title="Expediente">
											<img src="medios/acuerdos2.png" style="width: 31px; height: 30.5px; margin-right: -2px;">
										</a>&nbsp;&nbsp;
										<a href="procesal_queja.php?id=<?php echo (int) $queja['id_queja_date']; ?>" title="Calificación">
											<img src="medios/estado_procesal2.png" style="width: 31px; height: 30.5px; margin-right: -2px;">
										</a>&nbsp;&nbsp;

										<?php if ((($id_u <= 2) || ($id_u == 3)) && $queja['id_cat_med_pres'] == 5) : ?>
											<a href="convertir_queja.php?id=<?php echo (int) $queja['id_queja_date']; ?>" title="Convertir">
												<!-- <span class="glyphicon glyphicon-retweet"></span> -->
												<img src="medios/linea.png" alt="" srcset="">
											</a>
										<?php endif; ?>
									<?php endif; ?>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!-- </div> -->
<?php include_once('layouts/footer.php'); ?>