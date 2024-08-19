<?php
$page_title = 'Registro de Atención/Seguimiento';
require_once('includes/load.php');
?>
<?php

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}

if ($nivel_user > 3 && $nivel_user < 50) :
    redirect('home.php');
endif;

$inticadores_pat = find_all_pat_area(4,'rel_accion_atencion');
$tipo_atencion = find_all_order('cat_tipo_atencion', 'id_cat_tipo_atencion');
$e_detalle = find_by_id('atencion_seguimiento', (int)$_GET['id'], 'id_atencion_seguimiento');
$accion = find_by_atencion_seguimiento((int)$_GET['id']);
?>
<?php


if (isset($_POST['edit_atencion_seguimiento'])) {

    if (empty($errors)) {
		$id = (int)$e_detalle['id_atencion_seguimiento'];
		$ejercicio = remove_junk($db->escape($_POST['ejercicio']));
		$mes = remove_junk($db->escape($_POST['mes']));
		$fecha_accion = $ejercicio."-".$mes."-"."01";
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));
		
		$id_cat_tipo_atencion = $_POST['id_cat_tipo_atencion'];
		$id_indicadores_pat = $_POST['id_indicadores_pat'];
		$numero_accion = $_POST['numero_accion'];
		

		$query = "UPDATE atencion_seguimiento SET ";
		$query .= "mes='{$mes}',
					ejercicio='{$ejercicio}',
					observaciones='{$observaciones}' ";
		$query .= "WHERE  id_atencion_seguimiento='{$db->escape($id)}'";
		$result1 = $db->query($query);
		      
		$query = "DELETE FROM rel_accion_atencion WHERE id_atencion_seguimiento =" . $id;
		$result = $db->query($query);

		 
	for ($i = 0; $i < sizeof($id_cat_tipo_atencion); $i = $i + 1) {
						if($id_cat_tipo_atencion[$i] > 0){
							$queryInsert4 = "INSERT INTO rel_accion_atencion (id_atencion_seguimiento,id_cat_tipo_atencion,id_indicadores_pat,fecha_accion,numero_accion) 
																	  VALUES('$id','$id_cat_tipo_atencion[$i]','$id_indicadores_pat[$i]','$fecha_accion',$numero_accion[$i])";
																	  
							$db->query($queryInsert4);
						}
				}
					//sucess
					$session->msg('s', " El Registro de Atención/Seguimiento ha sido actualizado con éxito.");
					insertAccion($user['id_user'], '"' . $user['username'] . '" actualizo el Registro de Atención/Seguimiento, Folio: ' . $e_detalle['folio'] . '(ID '.$id.').', 1);
					redirect('atencion_seguimiento.php', false);
		
			
    } else {
        $session->msg("d", $errors);
        redirect('atencion_seguimiento.php' , false);
    }
}
?>
<script type="text/javascript">

</script>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Edición de Registro de Atención/Seguimiento <?php echo $e_detalle['folio']?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_atencion_seguimiento.php?id=<?php echo $e_detalle['id_atencion_seguimiento']?>" >
    <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ejercicio" class="control-label">Ejercicio</label>
                    <select class="form-control" name="ejercicio" id="ejercicio" required>
                        <option value="">Escoge una opción</option>
                        <?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
								echo "<option value='".$i."' ".($e_detalle['ejercicio'] == "$i"?"selected='selected'":"").">".$i."</option>";
								}?>		
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="mes" class="control-label">Mes</label>
                    <select class="form-control" name="mes" id="mes" required>
                        <option value="">Escoge una opción</option>
                         <option value="1" <?php if ($e_detalle['mes'] == '1') echo 'selected="selected"'; ?> >Enero</option>
                        <option value="2" <?php if ($e_detalle['mes'] == '2') echo 'selected="selected"'; ?> >Febrero</option>
                        <option value="3" <?php if ($e_detalle['mes'] == '3') echo 'selected="selected"'; ?> >Marzo</option>
                        <option value="4" <?php if ($e_detalle['mes'] == '4') echo 'selected="selected"'; ?> >Abril</option>
                        <option value="5" <?php if ($e_detalle['mes'] == '5') echo 'selected="selected"'; ?> >Mayo</option>
                        <option value="6" <?php if ($e_detalle['mes'] == '6') echo 'selected="selected"'; ?> >Junio</option>
                        <option value="7" <?php if ($e_detalle['mes'] == '7') echo 'selected="selected"'; ?> >Julio</option>
                        <option value="8" <?php if ($e_detalle['mes'] == '8') echo 'selected="selected"'; ?> >Agosto</option>
                        <option value="9" <?php if ($e_detalle['mes'] == '9') echo 'selected="selected"'; ?> >Septiembre</option>
                        <option value="10" <?php if ($e_detalle['mes'] == '10') echo 'selected="selected"'; ?> >Octubre</option>
                        <option value="11" <?php if ($e_detalle['mes'] == '11') echo 'selected="selected"'; ?> >Noviembre</option>
                        <option value="12" <?php if ($e_detalle['mes'] == '12') echo 'selected="selected"'; ?> >Diciembre</option>
                    </select>
                </div>
            </div>
        </div>
		 <div class="row" >
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo"></label>            
							</div>
						</div>
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="tipo_acuerdo">Número de Atención/Seguimiento</label>            							                       
							</div>
						</div>
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<label for="id_indicadores_pat">Definición del Indicador</label>            							                       
							</div>
						</div>
						
				</div>
<?php foreach ($accion as $tipo) : ?>
<?php if ($tipo['visitadurias']==='0') : ?>
                <div class="row" >
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group" style="text-align: right;">
								<label for="tipo_acuerdo"><?php echo $tipo['descripcion']; ?></label> 
									<input type="hidden" name="id_cat_tipo_atencion[]" value="<?php echo $tipo['id_cat_tipo_atencion']; ?>">
							</div>
						</div>
						<div class="col-md-2 d-flex flex-column justify-content-end">
							<div class="form-group">
								<input type="number"  class="form-control" max="10000" name="numero_accion[]" value="<?php echo $tipo['numero_accion']; ?>" required>                         
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<select class="form-control form-select" name="id_indicadores_pat[]" required>
									<option value="">Selecciona Indicador</option>
									<?php foreach ($inticadores_pat as $datos) : ?>
										<option  <?php if ($tipo['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['nombre_indicador']); ?></option>									
									<?php endforeach; ?>
								</select>
							</div>
						</div>
				</div>
				<?php endif; ?>
				<?php endforeach; ?>
				<div class="row" style="border-radius: 10px 10px 10px 10px;">
						
						
						<div class="col-md-3">
							<div class="form-group">
								<label for="observaciones">Observaciones</label>
								<textarea class="form-control" name="observaciones" id="observaciones" cols="30" rows="3"><?php  echo $e_detalle['observaciones'];?></textarea>
							</div>
						</div>
                </div>

               
                <div class="form-group clearfix">
                    <a href="atencion_seguimiento.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_atencion_seguimiento" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>