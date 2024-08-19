<?php
$page_title = 'Actuación';
require_once('includes/load.php');
?>
<?php

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$year = remove_junk($db->escape($_POST['ejercicio']));
$id_area = remove_junk($db->escape($_POST['id_area']));

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 5) {
    page_require_level_exacto(5);
}
if ($nivel == 7) {
    page_require_level_exacto(7);
}
if ($nivel == 19) {
    page_require_level_exacto(19);
}
if ($nivel == 21) {
    page_require_level_exacto(21);
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

$mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$acceso=0;


?>


<?php 
/*header("Pragma: public");
header("Expires: 0");
header("Content-type: application/x-msdownload");
header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
header("Content-Disposition: attachment; filename=actividiades_area.xls");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");*/

?>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<link rel="stylesheet" href="libs/css/main.css" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://harvesthq.github.io/chosen/chosen.css" rel="stylesheet" />
<?php if((int)$id_area > 0) :
	$modulos = find_modulos_areas($id_area);
	$area =find_campo_id('area', $id_area, 'id_area',"nombre_area");
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span style="font-size: 18px;">Actividades de '<?php echo ($area['nombre_area']); ?>' del <?php echo $year?></span>
                </strong>
            </div>
			
			<div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Fecha de Reporte: <?php  echo date('d/m/Y');?></span>
                </strong>
            </div>

            <div class="panel-body">
			<div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                            <label ></label>
								<table class="table table-bordered table-striped">
									<thead class="thead-purple">
										<tr style="height: 40px;">
											<th style="width: 1%;"><?php echo (" Nombre del Módulo");?></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
										</tr>
									</thead>
									<tbody>
			
					<?php foreach ($modulos as $datos) :
						$area_creacion=$datos['campo_area'];
						$acceso=0;
						if((int) $datos['num_campos_fecha']==1){
							if($area_creacion){
									$datos_modulo = find_datos_modulosF($datos['nombre_tabla_modulo'],$datos['id_key'],$datos['campo_fecha'],$id_area,$datos['campo_area'], $year);
							}else{
								$datos_modulo = find_datos_modulos($datos['nombre_tabla_modulo'],$datos['id_key'],$datos['campo_fecha'], $year);
							}
						}else{
							if((int) $datos['num_campos_fecha']==2){
									$dato_fecha= explode(",",$datos['campo_fecha']);
									$datos_modulo = find_datos_modulosP($datos['nombre_tabla_modulo'],$datos['id_key'],$dato_fecha[0],$dato_fecha[1], $year);
								}else{
									if((int) $datos['num_campos_fecha']==3){
										$datos_modulo = find_datos_modulosOC($datos['nombre_tabla_modulo'],$datos['id_key'],$datos['campo_fecha'],$id_area,$datos['campos_extras'], $year);										
									}
								}
						}
					?>
										<tr style="height: 40px;">
											<td style="text-align: center;"><?php echo (remove_junk(ucwords($datos['nombre_modulo']))) ?></td>
							<?php if($datos_modulo):?>
										<?php foreach ($datos_modulo as $datos) : 
										$val_mes = (int) $datos['mes']-1;
										$acceso=1;
										?>
											<?php if((int) $datos['mes']==1):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==2):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==3):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==4):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==6):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==7):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==8):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==9):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==10):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==11):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==10):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
										<?php endforeach; ?>
							
								<?php else :
								
								if($acceso == 0):
							?>										
											<td style="text-align: center;" colspan="12">Módulo sin Información generada al respecto.</td>										
							<?php
								endif;
							endif;?>
										</tr>
						<?php endforeach; 
							
							if($acceso == 0):
							?>										
											<td style="text-align: center;" colspan="12">Área sin módulos en el SUIGCEDH</td>										
							<?php
								endif;
							
							?>
									</tbody>
								</table>
                        </div>
                    </div>
			</div>

			

               
            </div>
        </div>
    </div>
</div>
<?php  else: 

	$areas_all = find_all_areasFull();
	
	 foreach ($areas_all as $datos_area) :
		if((int) $datos_area['id_area'] >0):
		$existen_modulos=0;
			$modulos = find_modulos_areas($datos_area['id_area']);
			$area =find_campo_id('area', $datos_area['id_area'], 'id_area',"nombre_area");
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span style="font-size: 18px;">Actividades de '<?php echo ($area['nombre_area']); ?>' del <?php echo $year?></span>
                </strong>
            </div>
			
			<div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Fecha de Reporte: <?php  echo date('d/m/Y');?></span>
                </strong>
            </div>

		<div class="panel-body">
			<div class="row">
                     <div class="col-md-12">
                        <div class="form-group">
                            <label ></label>
								<table class="table table-bordered table-striped">
									<thead class="thead-purple">
										<tr style="height: 40px;">
											<th style="width: 1%;"><?php echo (" Nombre del Módulo");?></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
											<th style="width: 1%;"></th>
										</tr>
									</thead>
									<tbody>
			
					<?php foreach ($modulos as $datos) :
						$area_creacion=$datos['campo_area'];
						$area_creacion=$datos['campo_area'];
						$acceso=0;
						$existen_modulos=1;
						if((int) $datos['num_campos_fecha']==1){
							if($area_creacion){
									$datos_modulo = find_datos_modulosF($datos['nombre_tabla_modulo'],$datos['id_key'],$datos['campo_fecha'],$datos_area['id_area'] ,$datos['campo_area'], $year);
							}else{
								$datos_modulo = find_datos_modulos($datos['nombre_tabla_modulo'],$datos['id_key'],$datos['campo_fecha'], $year);
							}
						}else{
							if((int) $datos['num_campos_fecha']==2){
									$dato_fecha= explode(",",$datos['campo_fecha']);
									$datos_modulo = find_datos_modulosP($datos['nombre_tabla_modulo'],$datos['id_key'],$dato_fecha[0],$dato_fecha[1], $year);
								}else{
									if((int) $datos['num_campos_fecha']==3){
										$datos_modulo = find_datos_modulosOC($datos['nombre_tabla_modulo'],$datos['id_key'],$datos['campo_fecha'],$datos_area['id_area'],$datos['campos_extras'], $year);										
									}
								}
						}
					?>
										<tr style="height: 40px;">
											<td style="text-align: center;"><?php echo (remove_junk(ucwords($datos['nombre_modulo']))) ?></td>
							<?php if($datos_modulo):?>
										<?php foreach ($datos_modulo as $datos) : 
										$val_mes = (int) $datos['mes']-1;
										$acceso=1;?>
											<?php if((int) $datos['mes']==1):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==2):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==3):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==4):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==6):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==7):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==8):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==9):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==10):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==11):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
											<?php if((int) $datos['mes']==10):?><td style="text-align: center;"><?php echo "<span style='    font-weight: bold;'>".$mes[$val_mes]."</span><br>".remove_junk(ucwords($datos['total'])) ?></td><?php endif;?>
										<?php endforeach; ?>
							
							<?php else :
							
							if($acceso == 0):
							?>										
											<td style="text-align: center;" colspan="12">Módulo sin Información generada al respecto.</td>										
							<?php
							endif;
							endif;?>
										</tr>
						<?php endforeach;
					if($existen_modulos == 0):
							?>										
											<td style="text-align: center;" colspan="12">Área sin módulos en el SUIGCEDH</td>										
							<?php
								endif;
							
						?>
									</tbody>
								</table>
                        </div>
                    </div>
			</div>

			

               
            </div>
     

			

        </div>
    </div>
</div>
<div class="row">

</div>
<?php
		endif;
	endforeach;
endif;
?>

<div class="form-group clearfix" style="text-align: center;">
                    <button type="button" class="btn btn-md btn-success" onclick="javascript:window.close();">Cerrar</button>&nbsp;&nbsp;                    
</div>
