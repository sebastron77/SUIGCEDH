<?php
$page_title = 'Denuncias de UT';
require_once('includes/load.php');
?>
<?php
$all_solicitudes = find_all_procesosUT('Denuncia');

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$title_status="Sin Dato";
$image_status="duda.png";
$fechaActual = date('Y-m-d');


if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la lista de '.$page_title.'.', 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 10) {
    page_require_level_exacto(10);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la lista de '.$page_title.'.', 5);
    page_require_level_exacto(53);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
	
endif;if ($nivel_user > 7 && $nivel_user < 10) :
    redirect('home.php');
endif;
if ($nivel_user > 10 && $nivel_user < 53) :
    redirect('home.php');
endif;

?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<a href="solicitudes_transparencia.php" class="btn btn-success">Regresar</a><br><br>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Denuncias</span>
                </strong>
                <?php if (($nivel_user <= 2) || ($nivel_user == 10) ) : ?>
                    <a href="add_denuncia_ut.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Denuncia</a>
                <?php endif; ?>

            </div>
        </div>

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr>
                        <th style="width: 1%;">Status</th>
                        <th style="width: 6%;">Folio</th>
                        <th style="width: 1%;">Fecha Notificación</th>
                        <th style="width: 1%;">Causal Artículo 53</th>
                        <th style="width: 1%;">Fecha de Resolución</th>
                        <th style="width: 1%;">Sentido de la Resolución</th>
                        
                        <?php if ($nivel_user <= 2 || ($nivel_user == 7) || ($nivel_user == 10)) : ?>
                            <th style="width: 1%;" class="text-center">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                   <?php foreach ($all_solicitudes as $datos) : 
				   $fecha_resolucion = ($datos['fecha_resolucion']==NULL?'':date("d-m-Y", strtotime(remove_junk(ucwords($datos['fecha_resolucion'])))));
				   if($datos['fecha_resolucion'] === NULL){						
							$image_status ="green.png";
							$title_status ="En Tramite";
						
					}else{
							$image_status ="verde.png";
							$title_status ="Respuesta a tiempo";
					}
				   ?> 
                        <tr>
                            <td class="text-center">							
								<img src="medios/<?php echo $image_status; ?>" style="width: 21px; height: 20.5px; " title="<?php echo $title_status; ?>">
							</td>
                            <td><?php echo remove_junk(ucwords($datos['folio_accion'])) ?></td>
							<td><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($datos['fecha_notificacion'])))) ?></td>
                            <td><?php echo remove_junk(ucwords($datos['articulo_causal'])) ?></td>
							<td><?php echo date("d-m-Y", strtotime($fecha_resolucion)) ?></td>
                            <td><?php echo remove_junk(ucwords($datos['sentido_resolucion'])) ?></td>
                            
                            <?php if ($nivel_user <= 2 || ($nivel_user == 7) || ($nivel_user == 10)) : ?>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="ver_info_denuncia.php?id=<?php echo (int)$datos['id_recursos_decuncias']; ?>" data-toggle="tooltip" title="Ver información completa">
                                            <img src="medios/ver_info.png" style="width: 31px; height: 30.5px; border-radius: 15%; margin-right: -2px;">
                                        </a>&nbsp;
										<?php if ($nivel_user <= 2 || ($nivel_user == 10) ) : ?>
											<a href="edit_denuncia_ut.php?id=<?php echo (int)$datos['id_recursos_decuncias']; ?>"  title="Editar" data-toggle="tooltip">
												<img src="medios/editar2.png" style="width: 31px; height: 30.5px; border-radius: 15%; margin-right: -2px;">                                            
											</a>&nbsp;
										<?php endif; ?>
										<?php if (($nivel_user <= 2) ||($nivel_user == 10) ) : 
												if($fecha_resolucion === ''):
										?>
											<a href="resolucion_denuncia.php?id=<?php echo (int)$datos['id_recursos_decuncias']; ?>"  title="Resolución" data-toggle="tooltip">
												<img src="medios/resolucion2.png" style="width: 31px; height: 30.5px; border-radius: 15%; ">                                        
											</a>
										<?php endif; 
										endif; ?>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<?php include_once('layouts/footer.php'); ?>