<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Entregables';
require_once('includes/load.php');
?>
<?php
$e_detalles = find_by_id('entregables', (int)$_GET['id'], 'id_entregables');
$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

page_require_level(53);
if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$e_detalles['folio'], 5);   
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
                    <span>Información del Entregable <?php echo $e_detalles['folio'] ?></span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th >Folio</th>
                            <th >Tipo Entregable</th>
                            <th >Nombre Entregable</th>
                            <th >Nombre Eje</th>
                            <th >Nombre Agenda</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($e_detalles['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_detalles['tipo_estregable'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_detalles['nombre_entragable'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_detalles['id_cat_ejes_estrategicos'])) ?></td>
                            <td><?php echo remove_junk((ucwords($e_detalles['id_cat_agendas']))) ?></td>

                        </tr>
                    </tbody>
                </table>  

				<table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 50%;">Descripción</th>
                            <th >Liga Accedo al Documneto</th>
                            <th >No. ISBN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($e_detalles['descripcion'])) ?></td>
                            <td>
								<a target="_blank" style="color: #0094FF;" href="<?php echo $e_detalles['liga_acceso']; ?>">
									<?php echo remove_junk(ucwords(($e_detalles['liga_acceso']))) ?>
								</a>
							</td>
                            <td><?php echo remove_junk(ucwords($e_detalles['no_isbn'])) ?></td>

                        </tr>
                    </tbody>
                </table>
               
                </table>
                <a href="agenda_entregables.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>