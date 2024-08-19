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
$id= $_GET["id"];

// Identificamos a que área pertenece el usuario logueado
$area_user = area_usuario2($id_user);
$area = $area_user['nombre_area'];
$indicador = find_by_id('indicadores_pat', $id, 'id_indicadores_pat');
$valor_real = find_by_id_calendarizacionReal($id,'Real');
$unidad_medida = find_by_id('cat_unidades_medida', $indicador['id_cat_unidades_medida'], 'id_cat_unidades_medida');
if( (int) $indicador['num_elementos'] > 1){
	$calendario_real = find_all_indicadores_duoreal($indicador['table_relacionada'],$indicador['campo_relacionado'],$indicador['fecha_relacionada'],$id,$indicador['accion_realizar']);		
}else{
	$calendario_real = find_all_indicadores_real($indicador['table_relacionada'],$indicador['campo_relacionado'],$indicador['fecha_relacionada'],$id,$indicador['accion_realizar']);	
}
$mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$meses_real = array();
$datos_reales=false;
?>
<?php
if (isset($_POST['avance_indicador_pat'])) {
    
    if (empty($errors)) {
		$id = (int) $indicador['id_indicadores_pat'];
		$valor_enero   = remove_junk($db->escape($_POST['valor_enero']));
		$valor_febrero   = remove_junk($db->escape($_POST['valor_febrero']));
		$valor_marzo   = remove_junk($db->escape($_POST['valor_marzo']));
		$valor_abril   = remove_junk($db->escape($_POST['valor_abril']));
		$valor_mayo   = remove_junk($db->escape($_POST['valor_mayo']));
		$valor_junio   = remove_junk($db->escape($_POST['valor_junio']));
		$valor_julio   = remove_junk($db->escape($_POST['valor_julio']));
		$valor_agosto   = remove_junk($db->escape($_POST['valor_agosto']));
		$valor_septiembre   = remove_junk($db->escape($_POST['valor_septiembre']));
		$valor_octubre   = remove_junk($db->escape($_POST['valor_octubre']));
		$valor_noviembre   = remove_junk($db->escape($_POST['valor_noviembre']));
		$valor_diciembre   = remove_junk($db->escape($_POST['valor_diciembre']));
		
		
		$sql = "UPDATE rel_indicadores_calendarizacion SET vigente=0 WHERE id_indicadores_pat='{$db->escape($id)}' AND tipo='Real'";       
        $result = $db->query($sql);
        
		
		
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
            $session->msg('s', " El Avance del Indicador se ha acualizado.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" actualizo el Avance del indicador ID' . $id . '.', 1);
            redirect('avance_indicador_pat.php?id='.$indicador['id_indicadores_pat'], false);
        } else {
            //failed
            $session->msg('d', ' No se pudo actualizar el avance del indicador.');
            redirect('avance_indicador_pat.php?id='.$indicador['id_indicadores_pat'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('pat.php?id='.$indicador['id_area_responsable'], false);
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
                <span>Actualizar Avance de Indicador <?php echo (int) $indicador['num_elementos'];?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="avance_indicador_pat.php?id=<?php  echo (int)$indicador['id_indicadores_pat'];?>" enctype="multipart/form-data">
                <div class="row">					
					<div class="col-md-6">
                        <div class="form-group">
                            <label for="nombrindicador">Definición del Indicador</label>
                            <textarea class="form-control"cols="10" rows="3" readonly><?php  echo $indicador['definicion_indicador'];?> </textarea>
                        </div>
                    </div>
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_inicio_proceso">Unidad de Medida</label><br>
                            <input type="text" class="form-control" readonly value="<?php  echo $unidad_medida['descripcion'];?>" >
                        </div>
                    </div>									
					
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="emisor_certificacion">Valor Absoluto</label>
                            <input type="text" class="form-control" readonly value="<?php  echo $indicador['valor_absoluto'];?>" >
                        </div>
                    </div>
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="emisor_certificacion">Valor Actual&nbsp;&nbsp;&nbsp;(<?php  echo ($valor_real?date("d-m-Y", strtotime($valor_real['fecha_actualización'])):"");?>)</label>
                            <input type="text" class="form-control" readonly value="<?php  echo ($valor_real?$valor_real['valor_real']:"0");?>" >
                        </div>
                    </div>
				</div>
				
				
				
                <div class="row">	
					<div class="col-md-12">
                        <div class="form-group">
                             <h3 style="font-weight:bold;">
									<span class="material-symbols-outlined">checklist</span>
									Valores Reales
								</h3>
                        </div>
                    </div>
                  </div>
				  
				<div class="row">					
					<?php foreach ($calendario_real as $datos) : $datos_reales=true;?> 
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
						
					<?php endforeach; ?>	
				</div>
                    <?php if(!$datos_reales){?>
					 <div class="row">	
						<div class="panel-heading clearfix">
							<strong>
								<span style="color: red;">Al indicador no se le ha asignado ninguna accion y/o actividad.</span>
							</strong>
						</div>                  
					</div>
                    <?php }?>
				</div>
               
				
				
					
                <div class="form-group clearfix">
                    <a href="pat.php?a=<?php echo $indicador['id_area_responsable']?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="avance_indicador_pat" class="btn btn-primary" value="subir">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>