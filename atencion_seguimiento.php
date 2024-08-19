<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Registro de Atención/Seguimiento';
require_once('includes/load.php');
?>
<?php

$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$all_atencion_seguimiento = find_all_atencion_seguimiento(4);

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la lista de '.$page_title.' del Ejercicio '.$ejercicio, 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la lista de '.$page_title.' del Ejercicio '.$ejercicio, 5);
    page_require_level_exacto(53);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 50) :
    redirect('home.php');
endif;
if ($nivel_user > 50 && $nivel_user < 53) :
    redirect('home.php');
endif;


?>

<script type="text/javascript">	
 function changueAnio(anio){
	 //alert(anio);
	 window.open("atencion_seguimiento.php?anio="+anio,"_self");	 
 }
</script>
<?php include_once('layouts/header.php'); ?>

<a href="solicitudes_quejas.php" class="btn btn-success">Regresar</a><br><br>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">

	<div class="row">
	    <div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<div class="col-md-10">
							<strong>
								<span class="glyphicon glyphicon-th"></span>
								<span>Lista de Registro de Atención/Seguimiento <?php echo $ejercicio ?></span>
							</strong>
					</div>
					<div class="col-md-2">
						<div class="form-group" >
							<select class="form-control" name="ejercicio" onchange="changueAnio(this.value)">
								<option value="">Selecciona Ejercicio</option>									
									<?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
								echo "<option value='".$i."'>".$i."</option>";
								}?>										
							</select>
						</div>	
					</div>					
					
						
					
						 <?php if (($nivel <=2) || ($nivel == 50) ) : ?>
							<a href="add_atencion_seguimiento.php" style="margin-left: 10px;margin-right: 10px" class="btn btn-info pull-right">Agregar Registro</a>
						<?php endif; ?>
														
					
				</div>
			</div>
		</div>
	</div>


    <div class="col-md-12">

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr style="height: 10px;">
                        <th class="text-center" >Folio</th>
                        <th class="text-center" >Ejercicio</th>
                        <th class="text-center" >Mes</th>
                        <th class="text-center" >Total de Atención/Seguimiento</th>						 
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
			
                   <?php foreach ($all_atencion_seguimiento as $inventario_archivos) :
				?>                     
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords($inventario_archivos['folio'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($inventario_archivos['ejercicio'])) ?></td>
                             <?php if($inventario_archivos['mes'] == 1):?><td class="text-center">Enero</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 2):?><td class="text-center">Febrero</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 3):?><td class="text-center">Marzo</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 4):?><td class="text-center">Abril</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 5):?><td class="text-center">Mayo</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 6):?><td class="text-center">Junio</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 7):?><td class="text-center">Julio</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 8):?><td class="text-center">Agosto</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 9):?><td class="text-center">Septiembre</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 10):?><td class="text-center">Octubre</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 11):?><td class="text-center">Noviembre</td><?php endif;?>
                                <?php if($inventario_archivos['mes'] == 12):?><td class="text-center">Diciembre</td><?php endif;?>
                            <td class="text-center"><?php echo remove_junk(ucwords($inventario_archivos['numero_accion'])) ?></td>                                                
                            <td class="text-center">
                                <div class="btn-group">
									<a href="ver_info_atencion_seguimiento.php?id=<?php echo (int) $inventario_archivos['id_atencion_seguimiento']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                        <img src="medios/ver_info.png" style="width: 16px; border-radius: 15%; margin-right: -2px;">
                                    </a>&nbsp;
									<?php if (($nivel <= 2) || ($nivel == 50) ) : ?>
                                    <a href="edit_atencion_seguimiento.php?id=<?php echo (int)$inventario_archivos['id_atencion_seguimiento']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>
									<?php endif ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
					
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<?php include_once('layouts/footer.php'); ?>