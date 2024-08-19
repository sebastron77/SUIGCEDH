<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<link rel="stylesheet" href="libs/css/main.css" />
<?php
$page_title = 'Solicitud de Información';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$all_years = find_all_correspondencia_totales();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];
    
?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Reporte de Datos de Correspondnecia</span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">                        
                        <tr style="height: 10px;">
                            <th style="">Ejercicio</th>
                            <th style="">Total Registro</th>
                            <th style="">Fecha de Recibido</th>
                            <th style="">No. Oficion Recepción</th>
                            <th style="">Nombre Remitente</th>
                            <th style="">Nombre Institución Remitente</th>
                            <th style="">Área CEDH Turnada</th>
                            <th style="">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
					<?php foreach ($all_years as $datos) : 
							$datos_anio = find_by_lastcorrespondencia($datos['ejercicio']);
						?>
                        <tr>
                            <td class="text-center"><?php echo $datos['ejercicio']; ?></td>
                            <td class="text-center"><?php echo $datos['total_registros']; ?></td>
							
								<td class="text-center"><?php echo date("d/m/Y", strtotime( $datos_anio['fecha_recibido'])) ; ?></td>
								<td class="text-center"><?php echo $datos_anio['num_oficio_recepcion']; ?></td>
								<td class="text-center"><?php echo $datos_anio['nombre_remitente']; ?></td>
								<td class="text-center"><?php echo $datos_anio['nombre_institucion']; ?></td>
								<td class="text-center"><?php echo $datos_anio['area_turnada']; ?></td>
								<td class="text-center"><?php echo $datos_anio['observaciones']; ?></td>
							   
                        </tr>
						<?php endforeach; ?>
                    </tbody>					
					</table>
                 </div>                  

                <div class="form-group clearfix" style="margin: 0 auto; text-align: center;">            
					<button type="button"  class="btn btn-md btn-success" onclick="javascript:window.close();">Cerrar</button>&nbsp;&nbsp;					
				</div>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>