<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Reporteador';
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
	
	$folio=find_queja_folio($folio_queja);	
	$existe = ($folio==null?0:1);
?>

                    <?php 
					if( $existe==1){
					?>
					
  <div class="row">
  <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Búsqueda general de Quejas</span>
            </strong>
        </div>
		<div class="panel-body">
            <table class=" table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr>
                        <th width="1%">Folio</th>
                        <th width="1%">Fecha presentación</th>
                        <th width="10%">Medio presentación</th>
                        <th width="10%">Área Asignada</th>
                        <th width="10%">Asignado a</th>
                        <th width="10%">Autoridad responsable</th>
                        <th width="5%">Quejoso</th>
                        <th width="5%">Estado Procesal</th>
                        <th width="1%">Tipo Resolución</th>
                    </tr>
                </thead>
               <tbody>
			   
                        <tr>
                            <td>
                                <?php echo (($folio['folio_queja'])) ?>
                            </td>                            
                            <td>
                                <?php echo date_format(date_create(remove_junk(ucwords($folio['fecha_presentacion']))), "d-m-Y"); ?>
                            </td>
                            <td>
                                <?php echo remove_junk(ucwords($folio['medio_pres'])) ?>
                            </td>
                            <td>
                                <?php echo remove_junk(ucwords($folio['nombre_area'])) ?>
                            </td>
                            <td>
                                <?php echo remove_junk(ucwords($folio['user_asignado'])) ?>
                            </td>
                            <td>
                                <?php echo remove_junk(ucwords($folio['nombre_autoridad'])) ?>
                            </td>
                            <td>
                                <?php echo remove_junk(ucwords($folio['nombre_quejoso'] . " " . $folio['paterno_quejoso'] . " " . $folio['materno_quejoso'])) ?>
                            </td>
                            <td>
                                <?php echo remove_junk(ucwords($folio['estado_procesal'])) ?>
                            </td>
                            <td>
                                <?php echo remove_junk(ucwords($folio['tipo_resolucion'])) ?>
                            </td>
                           
                        </tr>
                </tbody>
				</table>
				<div class="form-group clearfix">
            
								 <a href="quejas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
							Regresar
						</a>
					</div>
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
                
                <span style="font-size:25px">Búsqueda general de Quejas</span>
            </strong>
        </div>
		</center>
        <div class="panel-body">
            <form class="form-horizontal" action="" method="post">
					<center>
                <h1 style=" margin-top: 3%;">Lo sentimos pero el No. Expediente,<br> no se encuentra registrado. </h1>
				<br>
               <div class="form-group clearfix">
            
								 <a href="quejas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
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
                
                <span style="font-size:25px">Búsqueda general de Quejas</span>
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
                            <label for="nombreQ">No. Expediente(Folio Queja)</label>
							<input type="name" class="form-control" name="folio_queja" style="width:280px" required>
                        </div>
						</center>
					 <div class="form-group clearfix">
            
					<button type="submit" name="add" class="btn btn-info">Buscar</button>
								 <a href="quejas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
							Regresar
						</a>
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