
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de quejas';
require_once('includes/load.php');
?>
<?php
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : '2023';
$anio = $ejercicio == 2023?2022:2023;
$user = current_user();
$nivel = $user['user_level'];
$nivel_user = $user['user_level'];
$id_u = $user['id_user'];
$area_user = muestra_area($id_u);
$notificacion = notificacion();
$date_now = date("d-m-Y");

if (($nivel_user <= 2) || ($nivel_user == 7) || ($nivel_user == 21) || ($nivel_user == 50)) {
    $quejas_libro = find_all_quejas_admin($ejercicio);
} else {
    if ($area_user['id_det_usuario'] == 92) {
        $quejas_libro = find_all_quejas_lc($ejercicio);
    } else {
        $quejas_libro = find_all_quejas($area_user['id_area'], $area_user['id_det_usuario'],$ejercicio);
    }
}

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 5) {
    page_require_level_exacto(5);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 21) {
    page_require_level_exacto(21);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
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

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT q.folio_queja,q.id_queja_date, mp.descripcion as medio_presentacion, au.nombre_autoridad as autoridad_responsable, cq.nombre as nombre_quejoso, cq.paterno a_paterno_quejoso,
            cq.materno a_materno_quejoso, ca.nombre as nombre_agraviado, ca.paterno as a_paterno_agraviado, ca.materno as a_materno_agraviado, u.username as usuario_creador, a.nombre_area as area_asignada,
            eq.descripcion as estatus_queja,tr.descripcion as tipo_resolucion,ta.descripcion as tipo_ambito, q.fecha_presentacion, mp.descripcion as medio_presentacion, q.fecha_avocamiento, 
            cm.descripcion as municipio, q.incompetencia, q.causa_incomp, q.fecha_acuerdo_incomp, q.desechamiento, q.razon_desecha, q.forma_conclusion, q.fecha_conclusion, q.estado_procesal, 
            q.observaciones,  q.a_quien_se_traslada,  q.fecha_creacion, q.fecha_actualizacion, eq.descripcion as estatus_queja, q.archivo, q.dom_calle, q.dom_numero, q.dom_colonia, 
            REPLACE(q.descripcion_hechos,'\r\n',' ') as descripcion_hechos, tr.descripcion as tipo_resolucion, q.num_recomendacion, q.fecha_termino, ta.descripcion as tipo_ambito, u.username, a.nombre_area, q.fecha_vencimiento
            FROM quejas_dates q
            LEFT JOIN cat_medio_pres mp ON mp.id_cat_med_pres = q.id_cat_med_pres
            LEFT JOIN cat_autoridades au ON au.id_cat_aut = q.id_cat_aut
            LEFT JOIN cat_quejosos cq ON cq.id_cat_quejoso = q.id_cat_quejoso
            LEFT JOIN cat_agraviados ca ON ca.id_cat_agrav = q.id_cat_agraviado
            LEFT JOIN users u ON u.id_user = q.id_user_asignado
            LEFT JOIN area a ON a.id_area = q.id_area_asignada
            LEFT JOIN cat_estatus_queja eq ON eq.id_cat_est_queja = q.id_estatus_queja
            LEFT JOIN cat_tipo_res tr ON tr.id_cat_tipo_res = q.id_tipo_resolucion
            LEFT JOIN cat_tipo_ambito ta ON ta.id_cat_tipo_ambito = q.id_tipo_ambito
            LEFT JOIN cat_municipios cm ON cm.id_cat_mun = q.id_cat_mun;";
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
    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}

?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Lista de Quejas</span>
                </strong>
<a href="quejas.php?anio=<?php echo $anio;?>" style="margin-left: 10px" class="btn btn-info pull-right">Ver <?php echo $anio?></a>                
                <a href="seach_queja.php" style="margin-left: 10px" class="btn btn-info pull-right">Búsqueda General</a>				
                <?php if (($nivel == 1) || ($id_u == 3)) : ?>
                    <a href="quejas_publicas.php" class="btn btn-primary position-relative" style="float: right; margin-top: 0px; margin-right: 5px; margin-left: 10px;">
                        Quejas en Línea
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo $notificacion['total']; ?>
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </a>
                <?php endif; ?>
                <?php if (($nivel == 1) || ($nivel == 5) || ($nivel == 50)) : ?>
                    <a href="add_queja.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Queja</a>
                <?php endif; ?>
                <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <button style="float: right; margin-top: -22px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>
            </div>
        </div>

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr>
                        <th width="1%" >#</th>
                        <th width="1%" >&nbsp;</th>
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
                        <?php if (($nivel <= 2) || ($nivel == 5) || ($nivel == 7) || ($nivel == 19) || ($nivel == 21) || ($nivel == 50)) : ?>
                            <th width="5%;" class="text-center">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quejas_libro as $queja) : ?>
                        <tr>
                           <td class="text-center"><?php echo count_id(); ?></td>
                           <td class="text-center">
							<?php 
								if((int)$queja['estado_procesal']== 9){ //// Queja en estado de Presentada
									$dia_vencimiento = date("d-m-Y",strtotime($queja['fecha_presentacion']."+ 3 days"));
									$dia_alerta = date("d-m-Y",strtotime($queja['fecha_presentacion']."+ 2 days"));
									if(strtotime($date_now) < strtotime($dia_alerta)){
							?>
									<img src="medios/green.png" style="width: 21px; height: 20.5px; " title="<?php echo $dia_vencimiento; ?>">
									<?php }else{
										if((strtotime($date_now) < strtotime($dia_vencimiento)) ){
									?>
									<img src="medios/orange.png" style="width: 21px; height: 20.5px; " title="<?php echo $dia_vencimiento; ?>">
										<?php }else{?>
									<img src="medios/red.png" style="width: 21px; height: 20.5px; " title="<?php echo $dia_vencimiento; ?>">
									<?php 
									}}
								}else{ if((int)$queja['estado_procesal']== 1){ ///queja en estado de En Tramite
										$dia_vencimiento = date("d-m-Y",strtotime($queja['fecha_estado_procesal']."+ 10 days"));
										$dia_alerta = date("d-m-Y",strtotime($queja['fecha_estado_procesal']."+ 7 days"));
										if(strtotime($date_now) < strtotime($dia_alerta)){
											?>
											<img src="medios/green.png" style="width: 21px; height: 20.5px; " title="<?php echo $dia_vencimiento; ?>">
											<?php
										}else{
											if((strtotime($date_now) < strtotime($dia_vencimiento)) ){
											?>
											<img src="medios/orange.png" style="width: 21px; height: 20.5px; " title="<?php echo $dia_vencimiento; ?>">
											<?php
											}else{
												?>
											<img src="medios/red.png" style="width: 21px; height: 20.5px; " title="<?php echo $dia_vencimiento; ?>">
											<?php
											}
										}
									}else{ if((int)$queja['estado_procesal']== 3){ ///queja en estado de Vista de Informe
										$dia_vencimiento = date("d-m-Y",strtotime($queja['fecha_estado_procesal']."+ 15 days"));
										$dia_alerta = date("d-m-Y",strtotime($queja['fecha_estado_procesal']."+ 13 days"));
											if(strtotime($date_now) < strtotime($dia_alerta)){
											?>
											<img src="medios/green.png" style="width: 21px; height: 20.5px; " title="<?php echo $queja['fecha_estado_procesal']."- ". $dia_vencimiento; ?>">
											<?php
											}else{
												if((strtotime($date_now) < strtotime($dia_vencimiento)) ){
												?>
												<img src="medios/orange.png" style="width: 21px; height: 20.5px; " title="<?php echo $queja['fecha_estado_procesal']."- ". $dia_vencimiento; ?>">
												<?php
												}else{
													?>
												<img src="medios/red.png" style="width: 21px; height: 20.5px; " title="<?php echo $queja['fecha_estado_procesal']." - ".$dia_vencimiento; ?>">
												<?php
												}
											}
										}else{  if((int)$queja['estado_procesal']== 4){ ///queja en estado de Periodo Probatorio
											$dia_vencimiento = date("d-m-Y",strtotime($queja['fecha_estado_procesal']."+ 30 days"));
											$dia_alerta = date("d-m-Y",strtotime($queja['fecha_estado_procesal']."+ 28 days"));
												if(strtotime($date_now) < strtotime($dia_alerta)){
												?>
												<img src="medios/green.png" style="width: 21px; height: 20.5px; " title="<?php echo $queja['fecha_estado_procesal']."- ". $dia_vencimiento; ?>">
												<?php
												}else{
													if((strtotime($date_now) < strtotime($dia_vencimiento)) ){
													?>
													<img src="medios/orange.png" style="width: 21px; height: 20.5px; " title="<?php echo $queja['fecha_estado_procesal']."- ". $dia_vencimiento; ?>">
													<?php
													}else{
														?>
													<img src="medios/red.png" style="width: 21px; height: 20.5px; " title="<?php echo $queja['fecha_estado_procesal']." - ".$dia_vencimiento; ?>">
													<?php
													}
												}
											}else{
												if((int)$queja['estado_procesal']== 5){ ///queja en estado de Etapa de Conciliación
												$dia_vencimiento = date("d-m-Y",strtotime($queja['fecha_estado_procesal']."+ 90 days"));
												$dia_alerta = date("d-m-Y",strtotime($queja['fecha_estado_procesal']."+ 80 days"));
													if(strtotime($date_now) < strtotime($dia_alerta)){
													?>
													<img src="medios/green.png" style="width: 21px; height: 20.5px; " title="<?php echo $queja['fecha_estado_procesal']."- ". $dia_vencimiento; ?>">
													<?php
													}else{
														if((strtotime($date_now) < strtotime($dia_vencimiento)) ){
														?>
														<img src="medios/orange.png" style="width: 21px; height: 20.5px; " title="<?php echo $queja['fecha_estado_procesal']."- ". $dia_vencimiento; ?>">
														<?php
														}else{
															?>
														<img src="medios/red.png" style="width: 21px; height: 20.5px; " title="<?php echo $queja['fecha_estado_procesal']." - ".$dia_vencimiento; ?>">
														<?php
														}
													}
												}else{
													if((int)$queja['estado_procesal']== 6){ ///queja en estado de Vías de Cumplimiento a Acuerdo Conciliatorio
													$dia_vencimiento = date("d-m-Y",strtotime($queja['fecha_estado_procesal']."+ 15 days"));
													$dia_alerta = date("d-m-Y",strtotime($queja['fecha_estado_procesal']."+ 13 days"));
														if(strtotime($date_now) < strtotime($dia_alerta)){
														?>
														<img src="medios/green.png" style="width: 21px; height: 20.5px; " title="<?php echo $queja['fecha_estado_procesal']."- ". $dia_vencimiento; ?>">
														<?php
														}else{
															if((strtotime($date_now) < strtotime($dia_vencimiento)) ){
															?>
															<img src="medios/orange.png" style="width: 21px; height: 20.5px; " title="<?php echo $queja['fecha_estado_procesal']."- ". $dia_vencimiento; ?>">
															<?php
															}else{
																?>
															<img src="medios/red.png" style="width: 21px; height: 20.5px; " title="<?php echo $queja['fecha_estado_procesal']." - ".$dia_vencimiento; ?>">
															<?php
															}
														}
													}else{
														if((int)$queja['estado_procesal']== 7){ ///queja en estado de En Etapa de Resolución
														$dia_vencimiento = date("d-m-Y",strtotime($queja['fecha_estado_procesal']."+ 25 days"));
														$dia_alerta = date("d-m-Y",strtotime($queja['fecha_estado_procesal']."+ 23 days"));
															if(strtotime($date_now) < strtotime($dia_alerta)){
															?>
															<img src="medios/green.png" style="width: 21px; height: 20.5px; " title="<?php echo $queja['fecha_estado_procesal']."- ". $dia_vencimiento; ?>">
															<?php
															}else{
																if((strtotime($date_now) < strtotime($dia_vencimiento)) ){
																?>
																<img src="medios/orange.png" style="width: 21px; height: 20.5px; " title="<?php echo $queja['fecha_estado_procesal']."- ". $dia_vencimiento; ?>">
																<?php
																}else{
																	?>
																<img src="medios/red.png" style="width: 21px; height: 20.5px; " title="<?php echo $queja['fecha_estado_procesal']." - ".$dia_vencimiento; ?>">
																<?php
																}
															}
														}else{
															if((int)$queja['estado_procesal']== 7){ ///queja en estado de En Etapa de Resolución
															$dia_vencimiento = date("d-m-Y",strtotime($queja['fecha_estado_procesal']."+ 25 days"));
															$dia_alerta = date("d-m-Y",strtotime($queja['fecha_estado_procesal']."+ 23 days"));
																if(strtotime($date_now) < strtotime($dia_alerta)){
																?>
																<img src="medios/green.png" style="width: 21px; height: 20.5px; " title="<?php echo $queja['fecha_estado_procesal']."- ". $dia_vencimiento; ?>">
																<?php
																}else{
																	if((strtotime($date_now) < strtotime($dia_vencimiento)) ){
																	?>
																	<img src="medios/orange.png" style="width: 21px; height: 20.5px; " title="<?php echo $queja['fecha_estado_procesal']."- ". $dia_vencimiento; ?>">
																	<?php
																	}else{
																		?>
																	<img src="medios/red.png" style="width: 21px; height: 20.5px; " title="<?php echo $queja['fecha_estado_procesal']." - ".$dia_vencimiento; ?>">
																	<?php
																	}
																}
															}else{
																if((int)$queja['estado_procesal']== 8){ ///queja en estado de Conclusión De Trámite Ante Visitaduría/C.O.L.Q.S.
																	if((int)$queja['id_resolucion']== 1){//queja  con tipode Resolucion En trámite
																		?>
																		<img src="medios/red.png" style="width: 21px; height: 20.5px; " title="Sin Resolución">
																		<?php
																	}else{
																		?>
																	<img src="medios/verde.png" style="width: 21px; height: 20.5px; " title="Expediente Concluido">
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
                            </td><td>
                                <?php echo remove_junk(ucwords($queja['nombre_agraviado'] )); ?>
                            </td>
                            <td>
                                <?php echo remove_junk(ucwords($queja['est_proc'])) ?>
                            </td>
                            <td>
                                <?php echo remove_junk(ucwords($queja['id_tipo_resolucion'])) ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="ver_info_queja.php?id=<?php echo (int) $queja['id_queja_date']; ?>&t=0" title="Ver información">
                                        <img src="medios/ver_info.png" style="width: 31px; height: 30.5px; border-radius: 15%; margin-right: -2px;">
                                    </a>&nbsp;&nbsp;
                                    <?php if (($nivel == 1) || ($nivel == 5) || ($nivel == 50)) : ?>
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