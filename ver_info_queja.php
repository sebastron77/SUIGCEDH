<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Queja';
require_once('includes/load.php');
?>
<?php
$id = (int) $_GET['id'];
$e_detalle = find_by_id_queja($id);
$e_detalle2 = find_by_id_acuerdo($id);
$user = current_user();
$nivel = $user['user_level'];
$cat_est_procesal = find_all('cat_est_procesal');
$cat_derecho_vuln = find_all_derecho_vuln();
$cat_derecho_gral = find_all_derecho_gral();
$derecho_vulnerado = find_by_violentados('rel_queja_der_vuln', 'cat_der_vuln', $e_detalle['id_queja_date'],0);
$derecho_general = find_by_violentados('rel_queja_der_gral', 'cat_derecho_general', $e_detalle['id_queja_date'],0);
$all_reclasificacion =count_by_reclasificacion( $e_detalle['id_queja_date']);
$all_clasificacion =count_by_clasificacion( $e_detalle['id_queja_date']);
$cat_municipios = find_all_cat_municipios();
$calicado=0;
$anio= substr($e_detalle['folio_queja'],-6,4);
$regresa = (int) $_GET['t']==1?'mediacion.php?anio='.$anio:'quejas.php?anio='.$anio;

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    redirect('home.php');
}
if ($nivel == 4) {
    redirect('home.php');
}
if ($nivel == 5) {
    page_require_level(5);
}
if ($nivel == 6) {
    redirect('home.php');
}
if ($nivel == 7) {
    page_require_level(7);
}

if ($nivel == 7 || $nivel == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.($e_detalle['folio_queja']), 5);   
}
?>

<style>
    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #FFFFFF;
        color: black;
        width: 80%;
        margin-left: 1.2%;
        margin-bottom: -15px;
        border-radius: 10px 10px 10px 10px;
        height: 5%;
    }

    /* Style the buttons inside the tab */
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 17px;
        width: 25%;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    /* .tabcontent {
        display: none;
        padding: -1px -1px;
        border: 1px solid #ccc;
        border-top: none;
    } */

    .rectangulo {
        width: 100%;
        height: 100%;
        border: 3px solid #D6D6D6;
        border-radius: 5px;
        background: #F9F9F9;
        margin: 0 auto;
        display: grid;
        margin-top: 2%;
    }

    .boton1 {
        text-decoration: none;
        width: 200px;
        height: 50px;
        border: 2px solid #075E9A;
        border-radius: 5px;
        background: #075E9A;
        color: black;
        text-align: center;
        line-height: 45px;
        margin: 0 auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .boton1:hover {
        text-decoration: none;
        color: black;
        box-shadow: 0 1px 5px rgb(7, 94, 154);
        transition: all 0.1s ease;
    }

    .boton2 {
        text-decoration: none;
        width: 200px;
        height: 50px;
        border: 2px solid #075E9A;
        border-radius: 5px;
        background: #075E9A;
        color: black;
        text-align: center;
        line-height: 45px;
        margin: 0 auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .boton2:hover {
        text-decoration: none;
        color: white;
        box-shadow: 0 1px 5px rgb(7, 94, 154);
        transition: all 0.1s ease;
    }

    .contenedor {
        width: 1080px;
        margin: auto;
        background: #BFBFBF;
        color: black;
        padding: 20px 15px 50px 50px;
        border-radius: 10px;
        box-shadow: 0 5px 5px 0px rgba(0, 0, 0, 0.8);
    }

    .contenedor .titulo {
        font-size: 3.5ex;
        font-weight: bold;
        margin-left: 10px;
        margin-bottom: 10px;
    }

    #pestanas {
        float: top;
        font-size: 3ex;
        font-weight: bold;
    }

    #pestanas ul {
        margin-left: -40px;
    }

    #pestanas li {
        list-style-type: none;
        float: left;
        text-align: center;
        margin: 0px 2px -2px -0px;
        background: #949494;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        border: 2px solid #949494;
        border-bottom: #0340A1;
        padding: 0px 20px 0px 20px;
    }

    #pestanas a:link {
        text-decoration: none;
        color: white;
    }

    #contenidopestanas {
        clear: both;
        background: #FFFFFF;
        padding: 20px 0px 20px 20px;
        border-radius: 5px;
        border-top-left-radius: 0px;
        border: 2px solid #949494;
        width: 1025px;
    }
</style>

<?php include_once('layouts/header.php'); ?>

<button id="btnCrearPdf" style="margin-top: -5px; margin-bottom: 1%; margin-left: 45%; background: #FE2C35; color: white; font-size: 14px;" class="btn btn-pdf btn-md">Mostrar en PDF</button>
<div class="tab">
    <button class="tablinks" onclick="openCity(event, 'Generales')">Generales</button>
    <button class="tablinks3" onclick="openCity(event, 'Calificacion')">Calificación</button>
    <button class="tablinks1" onclick="openCity(event, 'Seguimiento')">Resolución</button>
    <button class="tablinks2" onclick="openCity(event, 'Expedientes')">Expediente</button>
</div>
<div class="row">

    <div id="prueba">
        <div id="Generales" class="tabcontent">

            <body onload="return openCity(event, 'Generales');"></body>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <strong>
                            <span class="glyphicon glyphicon-th"></span>
                            <span>Información general de la Queja <?php echo remove_junk(ucwords($e_detalle['folio_queja'])) ?></span>
                            <!-- <button id="btnCrearPdf" style="margin-top: -5px; margin-left: 65px; background: #FE2C35; color: white; font-size: 14px;" class="btn btn-pdf btn-md">Mostrar en PDF</button> -->
                        </strong>
                    </div>

                    <div class="panel-body" style="page-break-inside: auto;">
                        <table style="color:#3a3d44; margin-top: -10px; page-break-after:always;">
                            <tr>
                                <td style="width: 2%;">
                                    <span class="text-center">
                                        <span style="font-weight: bold;">Folio: </span>
                                        <?php echo remove_junk(ucwords($e_detalle['folio_queja'])) ?><br><br>
                                    </span>
                                </td>
                                <td style="width: 4%;">
                                    <span class="text-center">
                                        <span style="font-weight: bold;">Fecha de presentación: </span>
                                        <?php echo remove_junk(ucwords($e_detalle['fecha_presentacion'])) ?><br><br>
                                    </span>
                                </td>
                                <td style="width: 2%;">
                                    <span class="text-center">
                                        <span style="font-weight: bold;">Medio de presentación: </span>
                                        <?php echo remove_junk(ucwords($e_detalle['medio_pres'])) ?><br><br>
                                    </span>
                                </td>
                                <td style="width: 3%;">
                                    <span class="text-center" style="height:5%;">
                                        <span style="font-weight: bold;">Autoridad responsable: </span>
                                        <?php echo remove_junk(ucwords($e_detalle['nombre_autoridad'])) ?><br><br>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 2%;">
                                    <span class="text-center">
                                        <span style="font-weight: bold;">Estado procesal: </span>
                                        <?php echo $e_detalle['estado_procesal']; ?><br><br>
                                    </span>
                                </td>
                                <td style="width: 4%;">
                                    <span class="text-center">
                                        <span style="font-weight: bold;">Tipo de ámbito: </span>
                                        <?php if ($e_detalle['tipo_ambito'] == "") {
                                            echo "N/A";
                                        } else
                                            echo remove_junk(ucwords($e_detalle['tipo_ambito'])) ?><br><br>
                                    </span>
                                </td>

                                <td style="width: 3%;">
                                    <?php
                                    $folio_editar = $e_detalle['folio_queja'];
                                    $resultado = str_replace("/", "-", $folio_editar);
                                    ?>
                                    <span class="text-center">
                                        <span style="font-weight: bold;">Documento: </span>
                                        <a target="_blank" style="color:#0094FF" href="uploads/quejas/<?php echo $resultado . '/' . $e_detalle['archivo']; ?>"><?php echo $e_detalle['archivo']; ?></a><br><br>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 2%;">
                                    <span class="text-center">
                                        <span style="font-weight: bold;">Usuario asignado: </span>
                                        <?php echo remove_junk(ucwords(($e_detalle['user_asignado']))) ?><br><br>
                                    </span>
                                </td>
                                <td style="width: 4%;">
                                    <span class="text-center">
                                        <span style="font-weight: bold;">Área del usuario: </span>
                                        <?php echo remove_junk(ucwords(($e_detalle['nombre_area']))) ?><br><br>
                                    </span>
                                </td>
                                <td style="width: 2%;">
                                    <span class="text-center">
                                        <span style="font-weight: bold;">Colonia: </span>
                                        <?php echo remove_junk(ucwords($e_detalle['dom_colonia'])) ?><br><br>
                                    </span>
                                </td>
                                <td style="width: 3%;">
                                    <span class="text-center">
                                        <span style="font-weight: bold;">Municipio: </span>
                                        <!-- <?php echo remove_junk(ucwords($e_detalle['id_cat_mun'])) ?> -->
                                        <?php
                                        foreach ($cat_municipios as $municipios) {
                                            if ($e_detalle['id_cat_mun'] == $municipios['id_cat_mun']) {
                                                echo remove_junk(ucwords($municipios['descripcion']));
                                            }
                                        }
                                        ?><br><br>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 2%;">
                                    <span class="text-center">
                                        <span style="font-weight: bold;">Calle y núm: </span>
                                        <?php echo remove_junk(ucwords($e_detalle['dom_calle'] . " #" . $e_detalle['dom_numero'])) ?><br><br>
                                    </span>
                                </td>
                                <td style="width: 4%;">
                                    <span class="text-center">
                                        <span style="font-weight: bold;">Observaciones: </span>
                                        <?php echo remove_junk($e_detalle['observaciones']) ?><br><br>
                                    </span>
                                </td>
                                <td style="width: 4%;">
                                    <span class="text-center">
                                        <span style="font-weight: bold;">Creador: </span>
                                        <?php echo remove_junk($e_detalle['user_creador']) ?><br><br>
                                    </span>
                                </td>
                                <?php
                                //Si es un directorio
                                $folio_editarQ = $e_detalle['folio_queja'];
                                $resultadoQ = str_replace("/", "-", $folio_editarQ);
                                $directorio = 'uploads/quejas/' . $resultadoQ . '/imagenes';
                                if (is_dir($directorio)) {
                                    //Escaneamos el directorio
                                    $carpeta = @scandir($directorio);
                                    //Miramos si existen archivos
                                    if (count($carpeta) > 0) {
                                        //echo 'El directorio tiene archivos';
                                ?>
                                        <td>
                                            <div class="form-group clearfix">
                                                <a href="descargar_zip.php?id=<?php echo (int) $e_detalle['id_queja_date']; ?>&t=q" class="btn btn-md btn-success" data-toggle="tooltip" title="Descargar Imágenes">
                                                    Descargar Imágenes
                                                </a>
                                            </div>
                                        </td>
                                <?php }
                                } else {
                                    echo '';
                                }
                                ?>
                            </tr>
                            <table class="page_break">
                                <tr>
                                    <span class="text-center">
                                        <span style="font-weight: bold;">Descripción de los hechos: </span>
                                        <?php echo remove_junk($e_detalle['descripcion_hechos']) ?>
                                    </span>
                                </tr>
                            </table>
                        </table>

                        <h3 style="margin-top: 5%; margin-bottom:1%; font-weight: bold;">Información del Quejoso y Agraviado</h3>
                        <table style="color: #3a3d44">
                            <tr>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Nombre completo del quejoso: </span>
                                    <span onclick="javascript:window.open('./ver_info_qa.php?id=<?php echo $e_detalle['id_cat_quejoso'] ?>&t=q','popup','width=750,height=400');" class="text-center">
                                        <a href="">
                                            <?php echo remove_junk(ucwords(($e_detalle['nombre_quejoso'] . " " . $e_detalle['paterno_quejoso'] . " " . $e_detalle['materno_quejoso']))) ?>
                                        </a>
                                    </span>
                                </td>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Nombre completo del agraviado: </span>
                                    <span onclick="javascript:window.open('./ver_info_qa.php?id=<?php echo $e_detalle['id_cat_agraviado'] ?>&t=a','popup','width=700,height=400');" class="text-center">
                                        <a href="">
                                            <?php echo remove_junk(ucwords(($e_detalle['nombre_agraviado'] . " " . $e_detalle['paterno_agraviado'] . " " . $e_detalle['materno_agraviado']))) ?>
                                        </a>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="Calificacion" class="tabcontent">
		<div class="col-md-12">
           <div class="panel panel-default"> 
				<div class="panel-heading clearfix">
                    <strong>
                        <span class="glyphicon glyphicon-th"></span>
                        <span>Calificación de la Queja <?php echo remove_junk(ucwords($e_detalle['folio_queja'])) ?></span>
                    </strong>
                </div>
				 <div class="panel-heading clearfix">
						<strong>
							<span class="glyphicon glyphicon-th"></span>
							<span> Iniciales</span>
						</strong>
					</div>
				 <div class="panel-body">
                    <table style="color:#3a3d44; margin-top: -10px">
					<tr>
							<td style="width: 50%;"><br><label for="derecho_general">Derecho general </label> </td>
							<td style="width: 50%;"><br><label for="derecho_violentado">Derecho violentado </label> </td>
						</tr>
						<tr>
							<td style="width: 50%;">
							<?php foreach ($derecho_general as $general) : ?>
								<div class="col-md-7">
									<div class="form-group">									
										<li><?php foreach ($cat_derecho_gral as $datos) {
													if ($datos['id_cat_derecho_general'] === $general['id_cat_derecho_general'])
                                                            echo ucwords($datos['descripcion']);
											} ?>		
										</li>
									</div>
								</div>	
								<?php $calicado += $calicado ; endforeach; ?>
							</td>
							
							<td style="width: 50%;">
							<?php foreach ($derecho_vulnerado as $general) : ?>
							<div class="col-md-7">
									<div class="form-group">
										<li><?php foreach ($cat_derecho_vuln as $datos) {
													if ($datos['id_cat_der_vuln'] === $general['id_cat_der_vuln'])
                                                            echo ucwords($datos['descripcion']);
											} ?>		
										</li>
										
										
									</div>
								</div>		
								<?php  $calicado += $calicado ; endforeach; ?>
							</td>						
						</tr>
						<?php if($calicado < 1){?>
						<tr>
							<td colspan="2">
							<div class="col-md-12">
									<div class="form-group">
										Sin Calificar
									</div>
								</div>	
							</td>						
						</tr>
						<?php }?>
						
					</table>
				</div>
				
				<?php if((int) $all_reclasificacion['total'] > 0){ 
	$reclasificacion_queja= find_reclasificacion_queja($e_detalle['id_queja_date']);
?>				
				 <div class="row">
					 <div class="panel-heading">
						<strong>
							<span class="glyphicon glyphicon-th"></span>
							<span> Reclasificación</span>
						</strong>
					</div>
					<?php foreach ($reclasificacion_queja as $datos):
					$derecho_vulnerado = find_by_violentados('rel_queja_der_vuln', 'cat_der_vuln', $e_detalle['id_queja_date'],$datos['id_rel_queja_reclasificacion']);
					$derecho_general = find_by_violentados('rel_queja_der_gral', 'cat_derecho_general', $e_detalle['id_queja_date'],$datos['id_rel_queja_reclasificacion']);
 
							
?>
					<table style="color:#3a3d44; margin-top: -10px; page-break-after:always;" >
						<tr>
							<td style="width: 50%;"><br><label for="fecha">Fecha de Reclasificación: <?php echo $datos['fecha_reclasificacion']; ?></label> </td>
							<td style="width: 50%;"><br><label for="observaciones">Observaciones: <?php echo $datos['observaciones_reclasificacion']; ?> </label> </td>
						</tr>
<tr>
							<td style="width: 50%;"><br><label for="derecho_general">Derecho general </label> </td>
							<td style="width: 50%;"><br><label for="derecho_violentado">Derecho violentado </label> </td>
						</tr>
						<tr>
							<td style="width: 50">
								<?php foreach ($derecho_general as $general) : ?>
								<div class="col-md-7">
									<div class="form-group">									
										<li><?php foreach ($cat_derecho_gral as $datos) {
													if ($datos['id_cat_derecho_general'] === $general['id_cat_derecho_general'])
                                                            echo ucwords($datos['descripcion']);
											} ?>		
										</li>
									</div>
								</div>	
								<?php  endforeach; ?>
							</td>
							<td style="width: 50%;">
<?php foreach ($derecho_vulnerado as $general) : ?>
							<div class="col-md-7">
									<div class="form-group">
										<li><?php foreach ($cat_derecho_vuln as $datos) {
													if ($datos['id_cat_der_vuln'] === $general['id_cat_der_vuln'])
                                                            echo ucwords($datos['descripcion']);
											} ?>		
										</li>
										
										
									</div>
								</div>		
								<?php  endforeach; ?>
							</td>
						</tr>
						
					</table>
					<?php  endforeach; ?>
				</div> 		
				
<?php } ?>	
		   </div>
		</div>
    </div>
	
	<div id="Seguimiento" class="tabcontent">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <strong>
                        <span class="glyphicon glyphicon-th"></span>
                        <span>Seguimiento de la Queja <?php echo remove_junk(ucwords($e_detalle['folio_queja'])) ?></span>
                    </strong>
                </div>

                <div class="panel-body">
                    <table style="color:#3a3d44; margin-top: -10px">
                        <tr>
                            <?php if ($e_detalle['id_tipo_resolucion'] == 1) : ?>
                                <td style="width: 10%;">
                                    <span style="font-weight: bold;">Tipo de Resolución: </span>
                                    <?php echo 'En trámite' ?>
                                </td>
                            <?php endif; ?>
                            <?php if ($e_detalle['id_tipo_resolucion'] != 1) : ?>
                                <td style="width: 10%;">
                                    <span style="font-weight: bold;">Tipo de Resolución: </span>
                                    <?php echo $e_detalle['tipo_resolucion'] ?>
                                </td>
                            <?php endif; ?>
                            <?php if ($e_detalle['id_tipo_resolucion'] == 2) : ?>
                                <td style="width: 1%;">
                                    <span style="font-weight: bold;">Causa de incompetencia: </span>
                                    <?php echo $e_detalle['causa_incomp'] ?>
                                </td>
                                <td style="width: 1%;">
                                    <span style="font-weight: bold;">Fecha de Acuerdo de Incompetencia: </span>
                                    <?php echo $e_detalle['fecha_acuerdo_incomp'] ?>
                                </td>
                                <td style="width: 1%;">
                                    <span style="font-weight: bold;">¿A quién se traslada?: </span>
                                    <?php echo $e_detalle['a_quien_se_traslada'] ?>
                                </td>
                            <?php endif; ?>
                            <?php if ($e_detalle['id_tipo_resolucion'] == 3) : ?>
                                <td style="width: 1%;">
                                    <span style="font-weight: bold;">Descripción (Sin Materia): </span>
                                    <?php echo $e_detalle['descripcion_sin_materia'] ?>
                                </td>
                                <td style="width: 1%;">
                                    <span style="font-weight: bold;">Archivo (Sin Materia): </span>
                                    <a target="_blank" style="color:#0094FF" href="uploads/quejas/<?php echo $resultado . '/Sin_Materia/' . $e_detalle['archivo_sin_materia']; ?>"><?php echo $e_detalle['archivo_sin_materia']; ?></a>
                                </td>
                            <?php endif; ?>
                            <?php if ($e_detalle['id_tipo_resolucion'] == 4) : ?>
                                <td style="width: 15%;">
                                    <span style="font-weight: bold;">Núm ANV: </span>
                                    <?php echo $e_detalle['num_anv'] ?>
                                </td>
                                <td style="width: 15%;">
                                    <span style="font-weight: bold;">Fecha ANV: </span>
                                    <?php echo $e_detalle['fecha_anv'] ?>
                                </td>
                                <td style="width: 15%;">
                                    <span style="font-weight: bold;">Observaciones ANV: </span>
                                    <?php echo $e_detalle['observaciones_anv'] ?>
                                </td>
                        </tr>
                        <tr>
                            <td style="width: 15%;">
                                <span style="font-weight: bold;">Archivo (ANV): </span>
                                <a target="_blank" style="color:#0094FF" href="uploads/quejas/<?php echo $resultado . '/ANV/' . $e_detalle['archivo_anv']; ?>"><?php echo $e_detalle['archivo_anv']; ?></a>
                            </td>
                            <td style="width: 15%;">
                                <span style="font-weight: bold;">Archivo (ANV) público: </span>
                                <a target="_blank" style="color:#0094FF" href="uploads/quejas/<?php echo $resultado . '/ANV/' . $e_detalle['anv_publico']; ?>"><?php echo $e_detalle['anv_publico']; ?></a>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php if ($e_detalle['id_tipo_resolucion'] == 6) : ?>
                        <td style="width: 30%;">
                            <span style="font-weight: bold;">Razón de desechamiento: </span>
                            <?php echo $e_detalle['razon_desecha'] ?>
                        </td>
                    <?php endif; ?>
                    <?php if ($e_detalle['id_tipo_resolucion'] == 7) : ?>
                        <td style="width: 20%;">
                            <span style="font-weight: bold;">Descripción (Falta de Interés): </span>
                            <?php echo $e_detalle['descripcion_falta_interes'] ?>
                        </td>
                        <td style="width: 10%;">
                            <span style="font-weight: bold;">Archivo (Falta de Interés): </span>
                            <a target="_blank" style="color:#0094FF" href="uploads/quejas/<?php echo $resultado . '/Falta de interes/' . $e_detalle['archivo_falta_interes']; ?>"><?php echo $e_detalle['archivo_falta_interes']; ?></a>
                        </td>
                    <?php endif; ?>
                    <?php if ($e_detalle['id_tipo_resolucion'] == 8) : ?>
                        <td style="width: 15%;">
                            <span style="font-weight: bold;">Descripción (Acumulación): </span>
                            <?php echo $e_detalle['descripcion_acumulacion'] ?>
                        </td>
                        <td style="width: 15%;">
                            <span style="font-weight: bold;">Archivo (Acumulación): </span>
                            <a target="_blank" style="color:#0094FF" href="uploads/quejas/<?php echo $resultado . '/Acumulacion/' . $e_detalle['archivo_acumulacion']; ?>"><?php echo $e_detalle['archivo_acumulacion']; ?></a>
                        </td>
                    <?php endif; ?>
                    <?php if ($e_detalle['id_tipo_resolucion'] == 9) : ?>
                        <td style="width: 15%;">
                            <span style="font-weight: bold;">Descripción (Conciliación/Mediación): </span>
                            <?php echo $e_detalle['descripcion_cm'] ?>
                        </td>
                        <td style="width: 15%;">
                            <span style="font-weight: bold;">Archivo (Acumulación): </span>
                            <a target="_blank" style="color:#0094FF" href="uploads/quejas/<?php echo $resultado . '/Conciliacion_Mediacion/' . $e_detalle['archivo_cm']; ?>"><?php echo $e_detalle['archivo_cm']; ?></a>
                        </td>
                    <?php endif; ?>
                    <?php if ($e_detalle['id_tipo_resolucion'] == 10) : ?>
                        <td style="width: 15%;">
                            <span style="font-weight: bold;">Fecha de Desistimiento: </span>
                            <?php echo $e_detalle['fecha_desistimiento'] ?>
                        </td>
                        <td style="width: 15%;">
                            <span style="font-weight: bold;">Archivo (Desistimiento): </span>
                            <a target="_blank" style="color:#0094FF" href="uploads/quejas/<?php echo $resultado . '/Desistimiento/' . $e_detalle['archivo_desistimiento']; ?>"><?php echo $e_detalle['archivo_desistimiento']; ?></a>
                        </td>
                    <?php endif; ?>
                    <?php if ($e_detalle['id_tipo_resolucion'] == 11) : ?>
                        <td style="width: 15%;">
                            <span style="font-weight: bold;">Descripción(Improcedencia): </span>
                            <?php echo $e_detalle['descripcion_improcedencia'] ?>
                        </td>
                        <td style="width: 15%;">
                            <span style="font-weight: bold;">Archivo (Improcedencia): </span>
                            <a target="_blank" style="color:#0094FF" href="uploads/quejas/<?php echo $resultado . '/Improcedencia/' . $e_detalle['archivo_improcedencia']; ?>"><?php echo $e_detalle['archivo_improcedencia']; ?></a>
                        </td>
                    <?php endif; ?>
                    </tr>
                    <?php if ($e_detalle['id_tipo_resolucion'] == 5) : ?>
                        <td style="width: 15%;">
                            <span style="font-weight: bold;">Núm Recomendación: </span>
                            <?php echo $e_detalle['num_recomendacion'] ?>
                        </td>
                        <td style="width: 15%;">
                            <span style="font-weight: bold;">Núm Recomendación: </span>
                            <?php echo $e_detalle['servidor_publico'] ?>
                        </td>
                        <td style="width: 15%;">
                            <span style="font-weight: bold;">Fecha Recomendación: </span>
                            <?php echo $e_detalle['fecha_recomendacion'] ?>
                        </td>
                        <td style="width: 15%;">
                            <span style="font-weight: bold;">Observaciones Recomendación: </span>
                            <?php echo $e_detalle['observaciones_recomendacion'] ?>
                        </td>
                        </tr>
                        <tr>
                            <td style="width: 15%;">
                                <span style="font-weight: bold;">Recomendación: </span>
                                <a target="_blank" style="color:#0094FF" href="uploads/quejas/<?php echo $resultado . '/Recomendacion/' . $e_detalle['adjunto_recomendacion']; ?>"><?php echo $e_detalle['adjunto_recomendacion']; ?></a>
                            </td>
                            <td style="width: 15%;">
                                <span style="font-weight: bold;">Recomendación pública: </span>
                                <a target="_blank" style="color:#0094FF" href="uploads/quejas/<?php echo $resultado . '/Recomendacion/' . $e_detalle['adjunto_rec_publico']; ?>"><?php echo $e_detalle['adjunto_rec_publico']; ?></a>
                            </td>
                        </tr>
                    <?php endif; ?>

                    </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="Expedientes" class="tabcontent">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <strong>
                        <span class="glyphicon glyphicon-th"></span>
                        <span>Expediente de la Queja <?php echo remove_junk(ucwords($e_detalle['folio_queja'])) ?></span>
                    </strong>
                </div>
                <div class="panel-heading" style="text-align: right;border-bottom: 0px;">


                    <div class="panel-heading" style="text-align: right;border-bottom: 0px;">

                        <a href="download_caratula.php?id=<?php echo $id; ?>" class="btn btn-md btn-delete" data-toggle="tooltip" title="Generar Caratula Expediente" onclick="return confirm('¿Seguro que deseas descargar la caratula del Expediente? ');">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-word" viewBox="0 0 16 16">
                                <path d="M5.485 6.879a.5.5 0 1 0-.97.242l1.5 6a.5.5 0 0 0 .967.01L8 9.402l1.018 3.73a.5.5 0 0 0 .967-.01l1.5-6a.5.5 0 0 0-.97-.242l-1.036 4.144-.997-3.655a.5.5 0 0 0-.964 0l-.997 3.655L5.485 6.88z" />
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                            </svg>

                        </a>
                        <a href="download_zip.php?id=<?php echo $id; ?>" class="btn btn-md btn-delete" data-toggle="tooltip" title="Descargar ZIP" onclick="return confirm('¿Seguro que deseas descargar el expedientede completo la queja? ');">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-zip" viewBox="0 0 16 16">
                                <path d="M6.5 7.5a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v.938l.4 1.599a1 1 0 0 1-.416 1.074l-.93.62a1 1 0 0 1-1.109 0l-.93-.62a1 1 0 0 1-.415-1.074l.4-1.599V7.5zm2 0h-1v.938a1 1 0 0 1-.03.243l-.4 1.598.93.62.93-.62-.4-1.598a1 1 0 0 1-.03-.243V7.5z" />
                                <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm5.5-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9v1H8v1h1v1H8v1h1v1H7.5V5h-1V4h1V3h-1V2h1V1z" />
                            </svg>

                        </a>


                    </div>

                </div>
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tipo Acuerdo</th>
                            <th scope="col">Fecha Acuerdo</th>
                            <th scope="col">Documentos</th>
                            <th scope="col">Síntesis</th>
                            <th scope="col">¿Es público?</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $num = 1;
                        foreach ($e_detalle2 as $detalle) : ?>

                            <tr>
                                <th scope="row"><?php echo $num++ ?></th>
                                <td><?php echo remove_junk(($detalle['tipo_acuerdo'])) ?></td>
                                <td><?php echo remove_junk(($detalle['fecha_acuerdo'])) ?></td>
                                <td>
								<?php if (!$detalle['acuerdo_adjunto'] == "") { ?>
                                    &nbsp;&nbsp;&nbsp;
                                    <a target="_blank" href="uploads/quejas/<?php echo $resultado . '/Acuerdos/' . $detalle['acuerdo_adjunto']; ?>" title="Ver Acuerdo">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                                            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                            <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z" />
                                        </svg>
                                    </a>
								<?php } ?>
                                    <?php if (!$detalle['acuerdo_adjunto_publico'] == "") { ?>
                                        &nbsp;&nbsp;&nbsp;
                                        <a target="_blank" href="uploads/quejas/<?php echo $resultado . '/Acuerdos/' . $detalle['acuerdo_adjunto_publico']; ?>" title="Ver Versión Publica del Acuerdo">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-medical" viewBox="0 0 16 16">
                                                <path d="M7.5 5.5a.5.5 0 0 0-1 0v.634l-.549-.317a.5.5 0 1 0-.5.866L6 7l-.549.317a.5.5 0 1 0 .5.866l.549-.317V8.5a.5.5 0 1 0 1 0v-.634l.549.317a.5.5 0 1 0 .5-.866L8 7l.549-.317a.5.5 0 1 0-.5-.866l-.549.317V5.5zm-2 4.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z" />
                                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                            </svg>
                                        </a>
                                    <?php } ?>
                                </td>
                                <td><?php echo remove_junk(($detalle['sintesis_documento'])) ?></td>
                                <td><?php echo remove_junk(($detalle['publico'] == 1 ? "Sí" : "No")) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="form-group clearfix">
    <a href="<?php echo $regresa?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
        Regresar
    </a>
</div>
<script>
    function openCity(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    function prueba(pestana) {
        if (pestana == 1) {
            return openCity(event, 'Generales');
        }
    }
</script>
<script>
    function cambiarPestanna(pestannas, pestanna) {

        // Obtiene los elementos con los identificadores pasados.
        pestanna = document.getElementById(pestanna.id);
        console.log(pestanna.id);
        listaPestannas = document.getElementById(pestannas.id);

        // Obtiene las divisiones que tienen el contenido de las pestañas.
        cpestanna = document.getElementById('c' + pestanna.id);
        listacPestannas = document.getElementById('contenido' + pestannas.id);

        i = 0;
        // Recorre la lista ocultando todas las pestañas y restaurando el fondo y el padding de las pestañas.

        while (typeof listacPestannas.getElementsByTagName('div')[i] != 'undefined') {
            $(document).ready(function() {
                // ----------->>>>>>>>>>> ES LO QUE BLOQUEA EL QUE FUNCIONEN LOS DIVS <<<<<<<<<<<<<----------------
                $(listacPestannas.getElementsByTagName('div')[i]).css('display', 'none');
                // ------------------------------------------------------------------------------------------------
                $(listaPestannas.getElementsByTagName('li')[i]).css('background', '');
                $(listaPestannas.getElementsByTagName('li')[i]).css('padding-bottom', '');
            });
            i += 1;
        }

        $(document).ready(function() {
            // Muestra el contenido de la pestaña pasada como parámetro a la función, cambia el color de la pestaña y aumenta el padding para que tape el  
            // borde superior del contenido que esta puesto debajo y se vea de este modo que esta seleccionada.
            $(cpestanna).css('display', '');
            $(pestanna).css('background', '#056DCD');
            $(pestanna).css('padding-bottom', '2px');
        });
    }
</script>

<?php include_once('layouts/footer.php'); ?>