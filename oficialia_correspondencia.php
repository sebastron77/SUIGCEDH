<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Correspondencia - Oficialia de Partes';
require_once('includes/load.php');
?>
<?php
page_require_level(53);
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$area=2;
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$solicitud = find_by_solicitud($area);

// Identificamos a que área pertenece el usuario logueado
$area_user = area_usuario2($id_user);
$area = $area_user['nombre_area'];
$area_ingreso = isset($_GET['a']) ? $_GET['a'] : '0';

    $all_correspondencia = find_all_correspondenciaAdmin();
    //$all_correspondencia = find_all_correspondencia($area_ingreso,$ejercicio);

if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la '.$page_title." del área ".$area. " del Ejercicio ".$ejercicio, 5);   
}


?>
<script type="text/javascript">	
 function changueAnio(anio,area){
	 //alert(anio);
	 window.open("correspondencia.php?anio="+anio+"&a="+area,"_self");
	 
 }
</script>
<?php include_once('layouts/header.php'); ?>

<a href="solicitudes.php" class="btn btn-info">Regresar a Área</a><br><br>

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
								<span>Oficialia de partes <?php echo $ejercicio ?></span>
							</strong>
					</div>
					<div class="col-md-2">
						<div class="form-group" >
							<select class="form-control" name="ejercicio" onchange="changueAnio(this.value,<?php echo $area_ingreso?>)">
								<option value="">Selecciona Ejercicio</option>									
									<?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
								echo "<option value='".$i."'>".$i."</option>";
								}?>										
							</select>
						</div>	
					</div>
					<?php 	
if (( $nivel_user != 7) &&( $nivel_user != 53)) {
			
?>				
                    <a onclick="javascript:window.open('./reporte_datos_oficialia.php','popup','width=800,height=500');" style="margin-left: 10px" class="btn btn-info pull-right">Reporte Datos</a>				 
<?php 
		}
?>
					<?php if (($nivel_user <= 2) || ($nivel_user == 18) || ($nivel_user == 52)) : ?>
                    <a href="add_correspondencia_oficialia.php?a=<?php echo $area_ingreso ?>" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Correspondencia</a>
                <?php endif; ?>						
				</div>
			</div>
		</div>
	</div>



    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span></span>
                </strong>
                

            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th style="width: 1%;">#</th>
                            <th style="width: 3%;">Semáforo</th>
                            <th style="width: 5%;">Folio</th>
                            <th style="width: 1%;">Fecha Recibido</th>
                            <th style="width: 1%;">Fecha espera respuesta</th>
                            <th style="width: 10%;">Remitente</th>
                            <th style="width: 10%;">Institución</th>
                            <th style="width: 5%;">Medio de Recepción</th>
                            <th style="width: 20%;">Área a la que se turnó</th>
                            <th style="width: 5%;">¿Se envió a Área?</</th>
                            <th style="width: 2%;" class="text-center">Acciones</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_correspondencia as $a_correspondencia) : ?>
                            <?php
                            $folio_editar = empty($a_correspondencia['folio']);
                            $resultado = str_replace("/", "-", $folio_editar);
                            date_default_timezone_set('America/Mexico_City');
                            $creacion = date('Y-m-d');
                            ?>
                            <tr>
								<td class="text-center"><?php echo count_id(); ?></td>

                                <?php if ($a_correspondencia['fecha_espera_respuesta'] > $creacion) : ?>
                                    <td class="text-center">
                                        <h1><span class="green">v</span>
                                    </td>
                                <?php endif; ?>
                                <?php if ($a_correspondencia['fecha_espera_respuesta'] == $creacion) : ?>
                                    <td class="text-center">
                                        <h1><span class="yellow">a</span>
                                    </td>
                                <?php endif; ?>
                                <?php if ($a_correspondencia['fecha_espera_respuesta'] < $creacion) : ?>
                                    <td class="text-center">
                                        <h1><span class="red">r</span>
                                    </td>
                                <?php endif; ?>
                                <td><?php echo (remove_junk(ucwords(empty($a_correspondencia['folio'])))==1?'':remove_junk(ucwords(($a_correspondencia['folio'])))) ?></td>
                                <td><?php echo remove_junk(ucwords($a_correspondencia['fecha_recibido'])) ?></td>
                                <td><?php echo (remove_junk(ucwords(empty($a_correspondencia['fecha_espera_respuesta'])))==1?'Sin Fecha':remove_junk(ucwords(($a_correspondencia['folio'])))) ?></td>
                                <td><?php echo remove_junk(ucwords($a_correspondencia['nombre_remitente'])) ?></td>
                                <td><?php echo remove_junk(ucwords(($a_correspondencia['nombre_institucion']))) ?></td>
                                <td><?php echo remove_junk(ucwords(($a_correspondencia['medio_recepcion']))) ?></td>
                                <td><?php echo remove_junk(ucwords(($a_correspondencia['nombre_area']))) ?></td>
                                <td class="text-center"><?php echo  ($a_correspondencia['envio_area']==='1'?"Si":"No") ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="ver_info_correspondencia.php?id=<?php echo (int)$a_correspondencia['id_correspondencia']; ?>&a=<?php echo $area_ingreso ?>" title="Ver información">
                                            <img src="medios/ver_info.png" style="width: 31px; border-radius: 15%; ">
                                        </a>&nbsp;
                                        <?php if (($nivel_user <= 2) || /*($nivel_user == 8)*/($nivel_user == 52)) : ?>
                                            <a href="edit_correspondencia.php?id=<?php echo (int)$a_correspondencia['id_correspondencia']; ?>&a=<?php echo $area_ingreso ?>" title="Editar">
                                                <img src="medios/editar2.png" style="width: 31px; border-radius: 15%;">
                                            </a>&nbsp;
                                            <a href="seguimiento_correspondencia.php?id=<?php echo (int)$a_correspondencia['id_correspondencia']; ?>&a=<?php echo $area_ingreso ?>" title="Seguimiento">
                                                <img src="medios/resolucion2.png" style="width: 31px; border-radius: 15%; ">
                                            </a>
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
</div>

<?php include_once('layouts/footer.php'); ?>