<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Avance Indicador PAT';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$id_area = isset($_GET['a']) ? $_GET['a'] : '0';

// Identificamos a que área pertenece el usuario logueado
$area_user = area_usuario2($id_user);
$area = $area_user['nombre_area'];

$inticadores_pat = find_all_pat($id_area);
$mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

?>
<?php
if (isset($_POST['avance_global_indicador_pat'])) {
    
    if (empty($errors)) {
		
		foreach ($inticadores_pat as $indicador) : 
			$id = $indicador['id_indicadores_pat'];		
			
			$sql = "UPDATE rel_indicadores_calendarizacion SET vigente=0 WHERE id_indicadores_pat='{$id }' AND tipo='Real'";       
			$result = $db->query($sql);
			
			if($indicador['table_relacionada'] !== ''){
				if( (int) $indicador['num_elementos'] > 1){
					$calendario_real = find_all_indicadores_duoreal($indicador['table_relacionada'],$indicador['campo_relacionado'],$indicador['fecha_relacionada'],$id,$indicador['accion_realizar']);		
				}else{
					$calendario_real = find_all_indicadores_real($indicador['table_relacionada'],$indicador['campo_relacionado'],$indicador['fecha_relacionada'],$id,$indicador['accion_realizar']);	
				}
				$valor_real_total=0;
				$valor_enero=0;
				$valor_febrero=0;
				$valor_marzo=0;
				$valor_abril=0;
				$valor_mayo=0;
				$valor_junio=0;
				$valor_julio=0;
				$valor_agosto=0;
				$valor_septiembre=0;
				$valor_octubre=0;
				$valor_noviembre=0;
				$valor_diciembre=0;
		
			foreach ($calendario_real as $datos) :
				$valor_enero += ($datos['mes']==1)?$datos['total']:0;
				$valor_febrero += ($datos['mes']==2)?$datos['total']:0;
				$valor_marzo += ($datos['mes']==3)?$datos['total']:0;
				$valor_abril += ($datos['mes']==4)?$datos['total']:0;
				$valor_mayo += ($datos['mes']==5)?$datos['total']:0;
				$valor_junio += ($datos['mes']==6)?$datos['total']:0;
				$valor_julio += ($datos['mes']==7)?$datos['total']:0;
				$valor_agosto += ($datos['mes']==8)?$datos['total']:0;
				$valor_septiembre += ($datos['mes']==9)?$datos['total']:0;
				$valor_octubre += ($datos['mes']==10)?$datos['total']:0;
				$valor_noviembre += ($datos['mes']==11)?$datos['total']:0;
				$valor_diciembre += ($datos['mes']==12)?$datos['total']:0;
			endforeach;
			
				$query = "INSERT INTO rel_indicadores_calendarizacion (";
				$query .= "id_indicadores_pat, tipo, id_user_actualizacion, fecha_actualización,";
				$query .= "valor_enero, valor_febrero, valor_marzo, valor_abril,valor_mayo,valor_junio,valor_julio,valor_agosto,valor_septiembre,valor_octubre,valor_noviembre,valor_diciembre";
				$query .= ") VALUES (";
				$query .= " '{$id}','Real','{$id_user}',NOW()";
				$query .= ($valor_enero?",".$valor_enero:",0");
				$query .= ($valor_febrero?",".$valor_febrero:",0");
				$query .= ($valor_marzo?",".$valor_marzo:",0");
				$query .= ($valor_abril?",".$valor_abril:",0");
				$query .= ($valor_mayo?",".$valor_mayo:",0");
				$query .= ($valor_junio?",".$valor_junio:",0");
				$query .= ($valor_julio?",".$valor_julio:",0");
				$query .= ($valor_agosto?",".$valor_agosto:",0");
				$query .= ($valor_septiembre?",".$valor_septiembre:",0");
				$query .= ($valor_octubre?",".$valor_octubre:",0");
				$query .= ($valor_noviembre?",".$valor_noviembre:",0");
				$query .= ($valor_diciembre?",".$valor_diciembre:",0");
				$query .= ")";

				if ($db->query($query)) {
					//sucess
					
					insertAccion($user['id_user'], '"' . $user['username'] . '" actualizo el Avance del indicador ID' . $id . '.', 1);
					
				} else {
					//failed
					$session->msg('d', ' No se pudo actualizar el avance del indicador.');
					
				}
			}
		endforeach;
		
		$session->msg('s', " El Avance de los Indicadores se han acualizado.");
		redirect('pat.php?a='.$id_area, false);
		
    } else {
        $session->msg("d", $errors);
        redirect('pat.php?a='.$id_area, false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Actualizar Avance de Indicadores </span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="avance_global_indicador_pat.php?a=<?php  echo (int)$id_area;?>" enctype="multipart/form-data">
                <div class="row">

<table class="table table-bordered table-striped">
									<thead class="thead-purple">
										<tr style="height: 40px;">
											<th style="width: 5%;">#</th>
											<th style="width: 10%;">Definición del Indicador</th>
											<th style="width: 10%;">Unidad de Medida</th>
											<th style="width: 10%;">Valor Absoluto</th>
											<th style="width: 10%;">Valor Actual</th>
											<th style="width: 10%;">Fecha de Actualización</th>
											<th style="width: 45%;"> Avance</th>
										</tr>
									</thead>
									<tbody>
									<?php 
										foreach ($inticadores_pat as $indicador) : 
											$id = $indicador['id_indicadores_pat'];
											$unidad_medida = find_by_id('cat_unidades_medida', $indicador['id_cat_unidades_medida'], 'id_cat_unidades_medida');
											$valor_real = find_by_id_calendarizacionReal($id,'Real');
			
											if($indicador['table_relacionada'] !== ''){
												if( (int) $indicador['num_elementos'] > 1){
													$calendario_real = find_all_indicadores_duoreal($indicador['table_relacionada'],$indicador['campo_relacionado'],$indicador['fecha_relacionada'],$id,$indicador['accion_realizar']);		
												}else{
													$calendario_real = find_all_indicadores_real($indicador['table_relacionada'],$indicador['campo_relacionado'],$indicador['fecha_relacionada'],$id,$indicador['accion_realizar']);	
												}
											}
									?>
										<tr>
											<td class="text-center"><?php echo count_id(); ?></td>
											<td style="text-align: center;"><?php  echo $indicador['definicion_indicador'];?></td>
											<td style="text-align: center;"><?php  echo $unidad_medida['descripcion'];?></td>
											<td style="text-align: center;"><?php  echo $indicador['valor_absoluto'];?></td>
											<td style="text-align: center;"><?php  echo ($valor_real?$valor_real['valor_real']:"0");?></td>
											<td style="text-align: center;"><?php  echo ($valor_real?date("d-m-Y", strtotime($valor_real['fecha_actualización'])):"");?></td>
											<td style="text-align: center;">
											
												<div class="row">					
					<?php if($indicador['table_relacionada'] !== ''){
					foreach ($calendario_real as $datos) : $datos_reales=true;?> 
						<?php if($datos['mes']==1):?>
							<div class="col-md-1">
								<div class="form-group">
									<label for="nombrindicador"><?php echo ($mes[0])?></label>
									<input type="text" class="form-control" name="valor_enero" readonly value="<?php echo $datos['total']; ?>" >
								</div>
							</div>
									
						<?php endif;?>
						<?php if($datos['mes']==2):?>
							<div class="col-md-1">
								<div class="form-group">
									<label for="nombrindicador"><?php echo ($mes[1])?></label>
									<input type="text" class="form-control" name="valor_febrero" readonly value="<?php echo $datos['total']; ?>" >									
								</div>
							</div>
						<?php endif;?>
						<?php if($datos['mes']==3):?>
							<div class="col-md-1">
								<div class="form-group">
									<label for="nombrindicador"><?php echo ($mes[2])?></label>
									<input type="text" class="form-control" name="valor_marzo" readonly value="<?php echo $datos['total']; ?>" >
								</div>
							</div>
						<?php endif;?>
						<?php if($datos['mes']==4):?>
							<div class="col-md-1">
								<div class="form-group">
									<label for="nombrindicador"><?php echo ($mes[3])?></label>
									<input type="text" class="form-control" name="valor_abril" readonly value="<?php echo $datos['total']; ?>" >
								</div>
							</div>
						<?php endif;?><?php if($datos['mes']==5):?>
							<div class="col-md-1">
								<div class="form-group">
									<label for="nombrindicador"><?php echo ($mes[4])?></label>
									<input type="text" class="form-control" name="valor_mayo" readonly value="<?php echo $datos['total']; ?>" >
								</div>
							</div>
						<?php endif;?>
						<?php if($datos['mes']==6):?>
							<div class="col-md-1">
								<div class="form-group">
									<label for="nombrindicador"><?php echo ($mes[5])?></label>
									<input type="text" class="form-control" name="valor_junio" readonly value="<?php echo $datos['total']; ?>" >
								</div>
							</div>
						<?php endif;?><?php if($datos['mes']==7):?>
							<div class="col-md-1">
								<div class="form-group">
									<label for="nombrindicador"><?php echo ($mes[6])?></label>
									<input type="text" class="form-control" name="valor_julio" readonly value="<?php echo $datos['total']; ?>" >
								</div>
							</div>
						<?php endif;?>
						<?php if($datos['mes']==8):?>
							<div class="col-md-1">
								<div class="form-group">
									<label for="nombrindicador"><?php echo ($mes[7])?></label>
									<input type="text" class="form-control" name="valor_agosto" readonly value="<?php echo $datos['total']; ?>" >
								</div>
							</div>
						<?php endif;?>
						<?php if($datos['mes']==9):?>
							<div class="col-md-1">
								<div class="form-group">
									<label for="nombrindicador"><?php echo ($mes[8])?></label>
									<input type="text" class="form-control" name="valor_septiembre" readonly value="<?php echo $datos['total']; ?>" >
								</div>
							</div>
						<?php endif;?>
						<?php if($datos['mes']==10):?>
							<div class="col-md-1">
								<div class="form-group">
									<label for="nombrindicador"><?php echo ($mes[9])?></label>
									<input type="text" class="form-control" name="valor_octubre" readonly value="<?php echo $datos['total']; ?>" >
								</div>
							</div>
						<?php endif;?>
						<?php if($datos['mes']==11):?>
							<div class="col-md-1">
								<div class="form-group">
									<label for="nombrindicador"><?php echo ($mes[10])?></label>
									<input type="text" class="form-control" name="valor_noviembre" readonly value="<?php echo $datos['total']; ?>" >
								</div>
							</div>
						<?php endif;?>
						<?php if($datos['mes']==12):?>
							<div class="col-md-1">
								<div class="form-group">
									<label for="nombrindicador"><?php echo ($mes[11])?></label>
									<input type="text" class="form-control" name="valor_diciembre" readonly value="<?php echo $datos['total']; ?>" >
								</div>
							</div>
						<?php endif;?>
						
					<?php endforeach; }?>
											
											</td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>



				
				
				
					
                <div class="form-group clearfix">
                    <a href="pat.php?a=<?php echo $indicador['id_area_responsable']?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="avance_global_indicador_pat" class="btn btn-primary" value="subir">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>