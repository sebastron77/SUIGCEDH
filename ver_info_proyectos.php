<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Proyectos';
require_once('includes/load.php');
?>
<?php
$proyectos = find_by_id('proyectos', (int)$_GET['id'], 'id_proyectos');
$user = current_user();
$nivel = $user['user_level'];

page_require_level(53);
if ($nivel == 7 || $nivel == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$proyectos['folio'], 5);   
}

?>
<?php include_once('layouts/header.php'); ?>

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
                    <span>Información de Proyectos <?php echo $proyectos['folio'] ?></span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 20%;">Folio</th>
                            <th style="width: 20%;">Ejercio</th>
                            <th style="width: 20%;">Mes</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($proyectos['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($proyectos['ejercicio'])) ?></td>
							<?php if($proyectos['mes'] == 1):?><td class="text-center">Enero</td><?php endif;?>
						<?php if($proyectos['mes'] == 2):?><td class="text-center">Febrero</td><?php endif;?>
						<?php if($proyectos['mes'] == 3):?><td class="text-center">Marzo</td><?php endif;?>
						<?php if($proyectos['mes'] == 4):?><td class="text-center">Abril</td><?php endif;?>
						<?php if($proyectos['mes'] == 5):?><td class="text-center">Mayo</td><?php endif;?>
						<?php if($proyectos['mes'] == 6):?><td class="text-center">Junio</td><?php endif;?>
						<?php if($proyectos['mes'] == 7):?><td class="text-center">Julio</td><?php endif;?>
						<?php if($proyectos['mes'] == 8):?><td class="text-center">Agosto</td><?php endif;?>
						<?php if($proyectos['mes'] == 9):?><td class="text-center">Septiembre</td><?php endif;?>
						<?php if($proyectos['mes'] == 10):?><td class="text-center">Octubre</td><?php endif;?>
						<?php if($proyectos['mes'] == 11):?><td class="text-center">Noviembre</td><?php endif;?>
						<?php if($proyectos['mes'] == 12):?><td class="text-center">Diciembre</td><?php endif;?>

                        </tr>
                    </tbody>
                </table>				
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 20%;">Total Pendientes de estudio para Resolución</th>
                            <th style="width: 20%;">Total de Emisión de Resolución</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($proyectos['no_pendientes_estudio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($proyectos['no_emision_resolucion'])) ?></td>
                            
                        </tr>
                    </tbody>
                </table>					
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 20%;">Listado de Expedientes Pendientes de estudio para Resolución</th>
                            <th style="width: 20%;">Listado de Expedientes de Emisión de Resolución</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($proyectos['listado_pendientes_estudio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($proyectos['listado_emision_resolucion'])) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
				 
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 20%;">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($proyectos['observaciones'])) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
				
                <a href="proyectos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>