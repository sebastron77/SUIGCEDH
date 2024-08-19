<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Sesión';
require_once('includes/load.php');
?>
<?php
$id = (int) $_GET['id'];
$regresa = (int) $_GET['t'] == 1 ? 'mediacion.php' : 'quejas.php';
$e_detalle = find_by_id('sesiones', (int) $_GET['id'], 'id_sesion');
$user = current_user();
$nivel = $user['user_level'];

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
                            <span>Información general de la Sesión: <?php echo remove_junk(ucwords($e_detalle['folio'])) ?></span>
                            <!-- <button id="btnCrearPdf" style="margin-top: -5px; margin-left: 65px; background: #FE2C35; color: white; font-size: 14px;" class="btn btn-pdf btn-md">Mostrar en PDF</button> -->
                        </strong>
                    </div>

                    <div class="panel-body" style="page-break-inside: auto;">
                        <table style="color:#3a3d44; margin-top: -10px; page-break-after:always;">
                            <tr>
                                <td style="width: 1.5%;">
                                    <span class="text-center" style="height:5%;">
                                        <span style="font-weight: bold;">No. de sesión: </span>
                                        <?php echo remove_junk(ucwords($e_detalle['no_sesion'])) ?><br><br>
                                    </span>
                                </td>
                                <td style="width: 2%;">
                                    <span class="text-center">
                                        <span style="font-weight: bold;">Folio: </span>
                                        <?php echo remove_junk(ucwords($e_detalle['folio'])) ?><br><br>
                                    </span>
                                </td>
                                <td style="width: 3.5%;">
                                    <span class="text-center">
                                        <span style="font-weight: bold;">Fecha de atención: </span>
                                        <?php echo remove_junk(ucwords($e_detalle['fecha_atencion'])) ?><br><br>
                                    </span>
                                </td>
                                <td style="width: 2%;">
                                    <span class="text-center">
                                        <span style="font-weight: bold;">Estatus: </span>
                                        <?php echo remove_junk(ucwords($e_detalle['estatus'])) ?><br><br>
                                    </span>
                                </td>
                                <td style="width: 4%;">
                                    <span class="text-center" style="height:5%;">
                                        <span style="font-weight: bold;">Atendió: </span>
                                        <?php echo remove_junk(ucwords($e_detalle['atendio'])) ?><br><br>
                                    </span>
                                </td>
                                <td style="width: 3%;">
                                    <span class="text-center" style="height:5%;">
                                        <span style="font-weight: bold;">Fecha de Creación: </span>
                                        <?php echo remove_junk(ucwords($e_detalle['fecha_creacion'])) ?><br><br>
                                    </span>
                                </td>
                            </tr>
                            <table class="page_break">
                                <tr>
                                    <span class="text-center">
                                        <span style="font-weight: bold;">Notas de la sesión: </span>
                                        <?php echo remove_junk($e_detalle['nota_sesion']) ?>
                                    </span>
                                </tr>
                            </table>
                        </table>
                    </div>
                    <div class="form-group clearfix" style="margin-left: 1%;">
                        <a href="sesiones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>


<?php include_once('layouts/footer.php'); ?>