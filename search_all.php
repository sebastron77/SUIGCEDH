<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Buscador General';
require_once('includes/load.php');

$user = current_user();
$nivel = $user['user_level'];


?>

<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
$(document).ready(function(){
 
});
</script>
<?php echo display_msg($msg); ?>

<?php
		
if (isset($_POST['add'])) {
	$folio_queja = remove_junk($db->escape($_POST['folio_queja']));
	
	$folios=find_expediente($folio_queja);	
	$existe = ($folios==null?0:1);
	if( $existe==1){
		$CargaConfig = array();
		$i=0;
		foreach ($folios as $estudios) : 
			//echo $estudios['folio'];
			if($estudios['tipo']==='C'){
				$sql= 'SELECT "Canalización" as tipo, folio, b.descripcion as medio_presentacion, creacion FROM orientacion_canalizacion a LEFT JOIN cat_medio_pres b ON b.id_cat_med_pres= a.medio_presentacion  WHERE folio="'.$estudios['folio'].'"';
			}else if($estudios['tipo']==='O'){
				$sql= 'SELECT "Orientación" as tipo, folio, b.descripcion as medio_presentacion, creacion FROM orientacion_canalizacion a LEFT JOIN cat_medio_pres b ON b.id_cat_med_pres= a.medio_presentacion WHERE folio="'.$estudios['folio'].'"';				
			}else if($estudios['tipo']==='Q'){
				$sql= 'SELECT "Queja" as tipo, folio_queja as folio, b.descripcion as medio_presentacion, fecha_presentacion as creacion FROM quejas_dates a LEFT JOIN cat_medio_pres b ON b.id_cat_med_pres= a.id_cat_med_pres WHERE folio_queja="'.$estudios['folio'].'"';				//$CargaConfig[$i]= "Queja";
			}else if($estudios['tipo']==='ACT'){
				$sql= 'SELECT "Actuación" as tipo, folio_actuacion as folio, "-" as medio_presentacion, fecha_creacion_sistema as creacion FROM `actuaciones` WHERE folio_actuacion="'.$estudios['folio'].'"';				
			}else if($estudios['tipo']==='CONV'){
				$sql= 'SELECT "Convenio" as tipo, folio_solicitud as folio, "-" as medio_presentacion, fecha_creacion as creacion FROM `convenios` WHERE folio_solicitud="'.$estudios['folio'].'"';				
			}else if($estudios['tipo']==='CONS'){
				$sql= 'SELECT "Acta Consejo" as tipo, folio, "-" as medio_presentacion, fecha_sesion as creacion FROM `consejo` WHERE folio="'.$estudios['folio'].'"';				
			}else if($estudios['tipo']==='COL'){
				$sql= 'SELECT "Colaboracion UD" as tipo, folio, "-" as medio_presentacion, fecha_creacion as creacion FROM colaboraciones WHERE folio="'.$estudios['folio'].'"';				
			}else if($estudios['tipo']==='COR'){
				$sql= 'SELECT "Correspondencia" as tipo, folio, "-" as medio_presentacion, fecha_creacion as creacion FROM correspondencia WHERE folio="'.$estudios['folio'].'"';				
			}else if($estudios['tipo']==='EVEN'){
				$sql= 'SELECT "Eventos de Áreas" as tipo, folio, "-" as medio_presentacion, fecha_creacion as creacion FROM eventos WHERE folio="'.$estudios['folio'].'"';				
			}else if($estudios['tipo']==='EVENP'){
				$sql= 'SELECT "Evento Presidencia" as tipo, folio, "-" as medio_presentacion, fecha_creacion as creacion FROM eventos_presidencia WHERE folio="'.$estudios['folio'].'"';				
			}else if($estudios['tipo']==='GESTJ'){
				$sql= 'SELECT "Gestión Jurisdiccional" as tipo, folio, "-" as medio_presentacion, fecha_subida as creacion FROM gestiones_jurisdiccionales WHERE folio="'.$estudios['folio'].'"';				
			}elseif($estudios['tipo']==='INF'){
				$sql= 'SELECT "Informe Áreas" as tipo, folio, "-" as medio_presentacion, fecha_creacion as creacion FROM informe_actividades_areas WHERE folio="'.$estudios['folio'].'"';				
			}elseif($estudios['tipo']==='CAP'){
				$sql= 'SELECT "Capacitación" as tipo, folio, tipo_capacitacion as medio_presentacion, fecha_creacion as creacion FROM capacitaciones WHERE folio="'.$estudios['folio'].'"';				
			}elseif($estudios['tipo']==='COM'){
				$sql= 'SELECT "Comunicado" as tipo, folio, "-" as medio_presentacion, fecha_creacion as creacion FROM comunicados WHERE folio="'.$estudios['folio'].'"';				
			}elseif($estudios['tipo']==='ENTR'){
				$sql= 'SELECT "Entrevista" as tipo, folio, "-" as medio_presentacion, fecha_creacion as creacion FROM entrevistas WHERE folio="'.$estudios['folio'].'"';				
			}elseif($estudios['tipo']==='ACTESP'){
				$sql= 'SELECT "Actividad Especial" as tipo, folio, "-" as medio_presentacion, fecha_creacion as creacion FROM actividades_especiales WHERE folio="'.$estudios['folio'].'"';				
			}else{
				$sql= 'SELECT "" as tipo, folio, "-" as medio_presentacion, "-" as creacion FROM folios WHERE folio="'.$estudios['folio'].'"';								
			}				
		  $result = $db->query($sql);
		  while($row = $result->fetch_assoc()){
			  $CargaConfig[$i]['tipo']=$row["tipo"];
			  $CargaConfig[$i]['folio']=$row["folio"];
			  $CargaConfig[$i]['fecha_creacion']=$row["creacion"];
			  $CargaConfig[$i]['medio_presentacion']=$row["medio_presentacion"];
		  }  
			$i++;
		endforeach;
		
		if(sizeof($CargaConfig)>0){
			?>
<div class="row">
  <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Búsqueda general de Quejas</span>
            </strong>
        </div>
		<h2 >Tu búsqueda de No. Expediente '<?php echo $folio_queja?>', arrojo los siguientes resultados. </h2>
		<div class="panel-body">
            <table class=" table table-bordered table-striped" style="width:60%; margin: 0 auto;">
                <thead class="thead-purple">
                    <tr>
						 <th>Tipo Expediente</th>
						 <th>No. Expediente</th>
						 <th >Fecha de Creación</th>
						 <th >Medio Presentación</th>
					</tr>		
				</thead>
				<tbody>
<?php 	foreach ($CargaConfig as $datos) : ?>				
					<tr>
						<td>
							<?php echo $datos['tipo']; ?>
						</td>
						<td>
							<?php echo $datos['folio']; ?>
						</td> 
						<td>
							<?php echo date("d-m-Y", strtotime($datos['fecha_creacion'])); ?>
						</td>
						<td>
							<?php echo $datos['medio_presentacion']; ?>
						</td> 
					</tr>
<?php endforeach ?>
				</tbody>
			</table>
		</div>
<div class="form-group clearfix">
            
								 <a href="search_all.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
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
                
                <span style="font-size:25px">Búsqueda general </span>
            </strong>
        </div>
		</center>
        <div class="panel-body">
            <form class="form-horizontal" action="" method="post">
					<center>
                <h1 style=" margin-top: 3%;">Lo sentimos, pero el No. Expediente '<?php echo $folio_queja?>',<br> no se encuentra registrado. </h1>
				<br>
               <div class="form-group clearfix">
            
								 <a href="search_all.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
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
                
                <span style="font-size:25px">Búsqueda general </span>
            </strong>
        </div>
		</center>
        <div class="panel-body">
            <form class="form-horizontal" action="" method="post">
					<center>
                <h1 style=" margin-top: 3%;">Lo sentimos, pero el No. Expediente '<?php echo $folio_queja?>',<br> no se encuentra registrado. </h1>
				<br>
               <div class="form-group clearfix">
            
								 <a href="search_all.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
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
                
                <span style="font-size:25px">Búsqueda general</span>
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
                            <label for="nombreQ">No. Expediente(Folio)</label>
							<input type="name" class="form-control" name="folio_queja" style="width:280px" required>
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