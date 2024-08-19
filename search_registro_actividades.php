<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Bitácora de Actividades';
require_once('includes/load.php');

$user = current_user();
$nivel = $user['user_level'];


?>

<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>

</script>
<?php echo display_msg($msg); ?>

<?php
		
if (isset($_POST['add'])) {
	$dato_busqueda = remove_junk($db->escape($_POST['dato_busqueda']));
	
		$CargaConfig = array();
		$i=0;
		
		$sql= 'SELECT fecha_accion,descripcion FROM registro_actividades WHERE descripcion LIKE "%'.$dato_busqueda.'%" ORDER BY fecha_accion';				
						
		  $result = $db->query($sql);
		  while($row = $result->fetch_assoc()){
			  $CargaConfig[$i]['fecha_accion']=$row["fecha_accion"];
			  $CargaConfig[$i]['descripcion']=$row["descripcion"];
			  $i++;
		  }  
		
		if(sizeof($CargaConfig)>0){
			?>
<div class="row">
  <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Bitácora de Actividades</span>
            </strong>
        </div>
		<h2 >Tu búsqueda sobre '<?php echo $dato_busqueda?>', arrojo los siguientes resultados. </h2>
		<div class="panel-body">
            <table class=" table table-bordered table-striped" style="width:60%; margin: 0 auto;">
                <thead class="thead-purple">
                    <tr>
						<th style="width: 1%;">No.</th>
						 <th>Fecha Accion</th>
						 <th>Actividad</th>
					</tr>		
				</thead>
				<tbody>
<?php 	foreach ($CargaConfig as $datos) : ?>				
					<tr>
							<td class="text-center"><?php echo count_id(); ?></td>						
						
						<td>
							<?php echo date("d-m-Y H:m", strtotime($datos['fecha_accion'])); ?>
						</td>
						<td>
							<?php echo $datos['descripcion']; ?>
						</td> 
					</tr>
<?php endforeach ?>
				</tbody>
			</table>
		</div>
<div class="form-group clearfix">
            
								 <a href="search_registro_actividades.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
							Regresar
						</a>
					</div>		
	</div>	
</div>	

<?php 
		}else{
?>
						
<div class="row" style="margin-left: 30%; margin-top: 10%">
    <div class="panel panel-default" style="width:50%">
	<center>
        <div class="panel-heading">
            <strong>
                
                <span style="font-size:25px">Bitácora de Actividades</span>
            </strong>
        </div>
		</center>
        <div class="panel-body">
            <form class="form-horizontal" action="" method="post">
					<center>
                <h1 style=" margin-top: 3%;">Lo sentimos, pero su busqueda no  no existe registro de actividaees no se encuentra registrado. </h1>
				<br>
               <div class="form-group clearfix">
            
								 <a href="search_registro_actividades.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
							Regresar
						</a>
					</div>
					</center>
                
            </form>
        </div>
    </div>
</div>
						
<?php 			
		}
			
					
}else{
?>
	<div class="row" style="margin-left: 30%; margin-top: 10%">
    <div class="panel panel-default" style="width:50%">
	<center>
        <div class="panel-heading">
            <strong>
                
                <span style="font-size:25px">Bitácora de Actividades</span>
            </strong>
        </div>
		</center>
        <div class="panel-body">
            <form class="form-horizontal" action="" method="post">
					<center>
                <h1 style=" margin-top: 3%;">Datos búsqueda</h1>
                <div class="row" style="margin-top: 2%">
					<center>
                        <div class="form-group">
							<input type="name" class="form-control" name="dato_busqueda" style="width:280px" required>
                        </div>
						</center>
					 <div class="form-group clearfix">
            
					<button type="submit" name="add" class="btn btn-info">Buscar</button>
								 
					</div>
						</center>
				</div>
                
            </form>
        </div>
    </div>
</div>
<?php
}
?>
               


<?php include_once('layouts/footer.php'); ?>