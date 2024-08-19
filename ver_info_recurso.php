<?php
$page_title = 'Solicitud de Información';
require_once('includes/load.php');
?>
<?php

$recursos_decuncias = find_by_id('recursos_decuncias', (int)$_GET['id'], 'id_recursos_decuncias');
$fecha_resolucion = ($recursos_decuncias['fecha_resolucion']==NULL?'':date("d-m-Y", strtotime(remove_junk(ucwords($recursos_decuncias['fecha_resolucion'])))));
$folio_carpeta = str_replace("/", "-", $recursos_decuncias['folio_accion']);

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 10) {
    page_require_level_exacto(10);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 10) :
    redirect('home.php');
endif;

if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$recursos_decuncias['folio_accion'], 5);   
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
                    <span>Recurso de Revisión   <?php echo $recursos_decuncias['folio_accion'] ?></span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="">Folio</th>
                            <th style="">Fecha Notificación</th>
                            <th style="">Causal Artículo 136</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($recursos_decuncias['folio_accion'])) ?></td>
                            <td><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($recursos_decuncias['fecha_notificacion'])))) ?></td>
                            <td><?php echo remove_junk(ucwords($recursos_decuncias['articulo_causal'])) ?></td>
                        </tr>
                    </tbody>
                </table>

                <?php if($fecha_resolucion !== ''):	?>
										<table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="">Fecha Respuesta</th>
                            <th style="">Documento Respuesta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>                                                                                 
                            <td><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($recursos_decuncias['fecha_resolucion'])))) ?></td>                      
                            <td><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($recursos_decuncias['sentido_resolucion'])))) ?></td>                      
                           
							
                        </tr>
                    </tbody>
                </table>
											<?php endif; ?>
                <a href="recursos_ut.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>