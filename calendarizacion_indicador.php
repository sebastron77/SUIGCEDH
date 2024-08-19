<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<link rel="stylesheet" href="libs/css/main.css" />
<?php
$page_title = 'Solicitud de Información';
require_once('includes/load.php');
?>
<?php
$id= base64_decode($_GET["id"]);

$user = current_user();
$calendario_programado = find_by_id_calendarizacion($id,'Programado');
$calendario_real = find_by_id_calendarizacion($id,'real');
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
                    <span>Calendarización</span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
						<th style="text-align:center" colspan="6">Programado</th>
                        </tr>
                        <tr style="height: 10px;">
                            <th style="">Enero</th>
                            <th style="">Febrero</th>
                            <th style="">Marzo</th>
                            <th style="">Abril</th>
                            <th style="">Mayo</th>
                            <th style="">Junio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo (int) $calendario_programado['valor_enero']; ?></td>
                            <td class="text-center"><?php echo (int) $calendario_programado['valor_febrero']; ?></td>
                            <td class="text-center"><?php echo (int) $calendario_programado['valor_marzo']; ?></td>
                            <td class="text-center"><?php echo (int) $calendario_programado['valor_abril']; ?></td>
                            <td class="text-center"><?php echo (int) $calendario_programado['valor_mayo']; ?></td>
                            <td class="text-center"><?php echo (int) $calendario_programado['valor_junio']; ?></td>
                           
                        </tr>
                    </tbody>
					<tbody>
                        <tr>
                            <td colspan="6"></td>
                        </tr>
                    </tbody>
                
                    <thead class="thead-purple">                        
                        <tr style="height: 10px;">
                            <th style="">Julio</th>
                            <th style="">Agosto</th>
                            <th style="">Septiembre</th>
                            <th style="">Octubre</th>
                            <th style="">Noviembre</th>
                            <th style="">Diciembre</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo (int) $calendario_programado['valor_julio']; ?></td>
                            <td class="text-center"><?php echo (int) $calendario_programado['valor_agosto']; ?></td>
                            <td class="text-center"><?php echo (int) $calendario_programado['valor_septiembre']; ?></td>
                            <td class="text-center"><?php echo (int) $calendario_programado['valor_octubre']; ?></td>
                            <td class="text-center"><?php echo (int) $calendario_programado['valor_noviembre']; ?></td>
                            <td class="text-center"><?php echo (int) $calendario_programado['valor_diciembre']; ?></td>
                        </tr>
                    </tbody>
                </table>

<br><br><br>
                
				
           <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
						<th style="text-align:center" colspan="6">Real</th>
                        </tr>
                        <tr style="height: 10px;">
                            <th style="">Enero</th>
                            <th style="">Febrero</th>
                            <th style="">Marzo</th>
                            <th style="">Abril</th>
                            <th style="">Mayo</th>
                            <th style="">Junio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo $calendario_real?(int) $calendario_real['valor_enero']:0; ?></td>
                            <td class="text-center"><?php echo $calendario_real?(int) $calendario_real['valor_febrero']:0; ?></td>
                            <td class="text-center"><?php echo $calendario_real?(int) $calendario_real['valor_marzo']:0; ?></td>
                            <td class="text-center"><?php echo $calendario_real?(int) $calendario_real['valor_abril']:0; ?></td>
                            <td class="text-center"><?php echo $calendario_real?(int) $calendario_real['valor_mayo']:0; ?></td>
                            <td class="text-center"><?php echo $calendario_real?(int) $calendario_real['valor_junio']:0; ?></td>
                           
                        </tr>
                    </tbody>
					<tbody>
                        <tr>
                            <td colspan="6"></td>
                        </tr>
                    </tbody>
                
                    <thead class="thead-purple">                        
                        <tr style="height: 10px;">
                            <th style="">Julio</th>
                            <th style="">Agosto</th>
                            <th style="">Septiembre</th>
                            <th style="">Octubre</th>
                            <th style="">Noviembre</th>
                            <th style="">Diciembre</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo $calendario_real?(int) $calendario_real['valor_julio']:0; ?></td>
                            <td class="text-center"><?php echo $calendario_real?(int) $calendario_real['valor_agosto']:0; ?></td>
                            <td class="text-center"><?php echo $calendario_real?(int) $calendario_real['valor_septiembre']:0; ?></td>
                            <td class="text-center"><?php echo $calendario_real?(int) $calendario_real['valor_octubre']:0; ?></td>
                            <td class="text-center"><?php echo $calendario_real?(int) $calendario_real['valor_noviembre']:0; ?></td>
                            <td class="text-center"><?php echo $calendario_real?(int) $calendario_real['valor_diciembre']:0; ?></td>
                        </tr>
                    </tbody>
                </table>   



                <div class="form-group clearfix" style="margin: 0 auto; text-align: center;">            
					<button type="button"  class="btn btn-md btn-success" onclick="javascript:window.close();">Cerrar</button>&nbsp;&nbsp;					
				</div>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>