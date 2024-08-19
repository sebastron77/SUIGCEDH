<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Correspondencia Externa Recibida';
require_once('includes/load.php');
?>
<?php
page_require_level(53);
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];


$area = isset($_GET['a']) ? $_GET['a'] : '0';
$area_informe = find_by_id('area', $area, 'id_area');
$nombre_area = $area_informe['nombre_area'];

$all_correspondencia = find_all_correspondencia($area,$ejercicio);


if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la '.$page_title." del área ".$nombre_area. " del Ejercicio ".$ejercicio, 5);   
}

?>
<script type="text/javascript">	
 function changueAnio(anio,area){
	 window.open("correspondencia_recibida_externa.php?anio="+anio+"&a="+area,"_self");	 
 }
</script>
<?php include_once('layouts/header.php'); ?>

<a href="solicitudes_correspondencia.php?a=<?php echo $area?>" class="btn btn-success">Regresar</a><br><br>

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
								<span>Correspondencia Externa Recibida de <?php echo $nombre_area?> de <?php echo $ejercicio ?></span>
							</strong>
					</div>
					<div class="col-md-2">
						<div class="form-group" >
							<select class="form-control" name="ejercicio" onchange="changueAnio(this.value,<?php echo $area;?>)">
								<option value="">Selecciona Ejercicio</option>
								<?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
								echo "<option value='".$i."'>".$i."</option>";
								}?>								
							</select>
						</div>	
					</div>
					
                <?php if (($nivel_user <= 2) || ($nivel_user == 18)) : ?>
                    <a href="add_correspondencia_externa.php?a=<?php echo $area ?>" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Correspondencia</a>
                <?php endif; ?>
				
				</div>
			</div>
		</div>
	</div>


    <div class="col-md-12">
        <div class="panel panel-default">           

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
						<th class="text-center" style="width: 1%;">#</th>
                            <th style="width: 5%;">Folio</th>
                            <th style="width: 1%;">Fecha Recibido</th>
                            <th style="width: 1%;">Fecha espera respuesta</th>
                            <th style="width: 10%;">Remitente</th>
                            <th style="width: 10%;">Institución</th>
                            <th style="width: 5%;">Medio de Recepción</th>
                            <th style="width: 20%;">Área a la que se turnó</th>
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
                               
                                <td><?php echo (remove_junk(ucwords(empty($a_correspondencia['folio'])))==1?'':remove_junk(ucwords(($a_correspondencia['folio'])))) ?></td>
                                <td><?php echo remove_junk(ucwords($a_correspondencia['fecha_recibido'])) ?></td>
                                <td><?php echo (remove_junk(ucwords(empty($a_correspondencia['fecha_espera_respuesta'])))==1?'Sin Fecha':remove_junk(ucwords(($a_correspondencia['fecha_espera_respuesta'])))) ?></td>
                                <td><?php echo remove_junk(ucwords($a_correspondencia['nombre_remitente'])) ?></td>
                                <td><?php echo remove_junk(ucwords(($a_correspondencia['nombre_institucion']))) ?></td>
                                <td><?php echo remove_junk(ucwords(($a_correspondencia['medio_recepcion']))) ?></td>
                                <td><?php echo remove_junk(ucwords(($a_correspondencia['nombre_area']))) ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="ver_info_correspondencia_externa.php?id=<?php echo (int)$a_correspondencia['id_correspondencia']; ?>&a=<?php echo $area ?>" title="Ver información">
                                            <img src="medios/ver_info.png" style="width: 31px; border-radius: 15%; ">
                                        </a>&nbsp;
                                        <?php if( ( $nivel_user != 7) &&( $nivel_user != 53)) :         ?>	
                                            <a href="edit_correspondencia_externa.php?id=<?php echo (int)$a_correspondencia['id_correspondencia']; ?>&a=<?php echo $area ?>" title="Editar">
                                                <img src="medios/editar2.png" style="width: 31px; border-radius: 15%;">
                                            </a>&nbsp;
                                            <a href="seguimiento_correspondencia.php?id=<?php echo (int)$a_correspondencia['id_correspondencia']; ?>&a=<?php echo $area ?>" title="Seguimiento">
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