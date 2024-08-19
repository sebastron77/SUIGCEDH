<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Sesión COTRAPEM';
require_once('includes/load.php');
?>
<?php
$id = (int) $_GET['id'];
$e_cotrapem = find_by_id_sesionCotrapem((int)$_GET['id']);
$user = current_user();
$nivel = $user['user_level'];

page_require_level(53);
if ($nivel == 7 || $nivel == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$e_cotrapem['folio'], 5);   
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">

    <div id="prueba">
        <div id="Generales" class="tabcontent">

            <body onload="return openCity(event, 'Generales');"></body>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <strong>
                            <span class="glyphicon glyphicon-th"></span>
                            <span>Información general de la Sesión: <?php echo remove_junk(ucwords($e_cotrapem['folio'])) ?></span>
                            <!-- <button id="btnCrearPdf" style="margin-top: -5px; margin-left: 65px; background: #FE2C35; color: white; font-size: 14px;" class="btn btn-pdf btn-md">Mostrar en PDF</button> -->
                        </strong>
                    </div>

                    <div class="panel-body" style="page-break-inside: auto;">
                         <table class="table table-bordered table-striped">
							<thead class="thead-purple">
								<tr style="height: 10px;">
									<th class="text-center" >Fecha de la Sesión:</th>
									<th class="text-center" >Lugar de la Sesión:</th>
								</tr>                                                              
							</thead>
							<tbody>
								<tr>
									<td class="text-center"><?php echo remove_junk(ucwords($e_cotrapem['fecha'])) ?></td>
									<td><?php echo remove_junk(ucwords($e_cotrapem['lugar'])) ?></td>
								</tr>
							</tbody>
							                            </table>
                            
							<table class="table table-bordered table-striped">
							<thead class="thead-purple">
								<tr style="height: 10px;">
									<th class="text-center" >Orden del día:</th>
								</tr>                                                              
							</thead>
							<tbody>
								<tr>
									<td class="text-center"><?php echo remove_junk(ucwords($e_cotrapem['acuerdos'])) ?></td>
								</tr>
							</tbody>
                               
                        </table>
                    </div>
                    <div class="form-group clearfix" style="margin-left: 1%;">
                        <a href="cotrapem.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>


<?php include_once('layouts/footer.php'); ?>