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
?>
<?php


if (isset($_POST['add_atencion_seguimiento'])) {

    if (empty($errors)) {
		$id_folio = last_id_folios();
		$ejercicio = remove_junk($db->escape($_POST['ejercicio']));
		$mes = remove_junk($db->escape($_POST['mes']));
		$fecha_accion = $ejercicio."-".$mes."-"."01";
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));
		
		$id_cat_tipo_atencion = $_POST['id_cat_tipo_atencion'];
		$id_indicadores_pat = $_POST['id_indicadores_pat'];
		$numero_accion = $_POST['numero_accion'];
		
        		
		if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int) $nuevo['contador'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['contador'] + 1);
            }
        }

        $year = date("Y");
        $folio = 'CEDH/' . $no_folio . '/' . $year . '-RASCOLQS';	
		
		  $dbh = new PDO('mysql:host=localhost;dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');
		  
		  
				$query = "INSERT INTO atencion_seguimiento (";
				$query .= "folio,mes,ejercicio,id_area,observaciones,user_creador,fecha_creacion) VALUES (";
				$query .= " '{$folio}','{$mes}','{$ejercicio}',4,'{$observaciones}','{$id_user}',NOW() )";
	
				$query2 = "INSERT INTO folios (";
				$query2 .= "folio, contador";
				$query2 .= ") VALUES (";
				$query2 .= " '{$folio}','{$no_folio}'";
				$query2 .= ")";
				
				$dbh->exec($query);
			if ($db->query($query2) ) {
				$id_atencion_seguimiento = $dbh->lastInsertId();
				
				if($id_atencion_seguimiento > 0){	
				for ($i = 0; $i < sizeof($id_cat_tipo_atencion); $i = $i + 1) {
						if($id_cat_tipo_atencion[$i] > 0){
							$queryInsert4 = "INSERT INTO rel_accion_atencion (id_atencion_seguimiento,id_cat_tipo_atencion,id_indicadores_pat,fecha_accion,numero_accion) 
																	  VALUES('$id_atencion_seguimiento','$id_cat_tipo_atencion[$i]','$id_indicadores_pat[$i]','$fecha_accion',$numero_accion[$i])";
																	  
							$db->query($queryInsert4);
						}
				}
					
					//sucess
					$session->msg('s', " El Registro de Atención/Seguimiento ha sido agregado con éxito.");
					insertAccion($user['id_user'], '"' . $user['username'] . '" agregó el Registro de Atención/Seguimiento, Folio: ' . $folio . '.', 1);
					redirect('atencion_seguimiento.php', false);
				}else{
					$session->msg('d', ' No se pudo agregar el Registro de Atención/Seguimiento,debido a que no se genero ID de la misma');
					redirect('add_atencion_seguimiento.php', false);
				}
			}
		
			
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
                <span>Alta de Registro de Atención/Seguimiento</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_atencion_seguimiento.php" >
    <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ejercicio" class="control-label">Ejercicio</label>
                    <select class="form-control" name="ejercicio" id="ejercicio" required>
                        <option value="">Escoge una opción</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024" selected="selected">2024</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="mes" class="control-label">Mes</label>
                    <select class="form-control" name="mes" id="mes" required>
                        <option value="">Escoge una opción</option>
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
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
<?php foreach ($tipo_atencion as $tipo) : ?>
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
								<input type="number"  class="form-control" max="10000" name="numero_accion[]" value="0" required>                         
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<select class="form-control form-select" name="id_indicadores_pat[]" required>
									<option value="">Selecciona Indicador</option>
									<?php foreach ($inticadores_pat as $datos) : ?>
										<option  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['nombre_indicador']); ?></option>									
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
								<textarea class="form-control" name="observaciones" id="observaciones" cols="30" rows="3"></textarea>
							</div>
						</div>
                </div>

               
                <div class="form-group clearfix">
                    <a href="atencion_seguimiento.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_atencion_seguimiento" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>