<?php
$page_title = 'Diseño';
require_once('includes/load.php');
?>
<?php

$disenios = find_by_id('disenios', (int)$_GET['id'], 'id_disenios');
$nombre_area =find_campo_id('area', $disenios['id_area_solicitud'], 'id_area','nombre_area');
$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 3) {
    page_require_level_exacto(3);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7) :
    redirect('home.php');
endif;
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
                    <span>Diseño   <?php echo $disenios['folio'] ?></span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 3%;">Folio</th>
                            <th style="width: 3%;">Fecha de Solicitud</th>
                            <th style="width: 3%;">Fecha de Entrega</th>
                            <th style="width: 5%;">Tipo de Diseños</th>
                            <th style="width: 3%;">Tema de Diseño</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($disenios['folio'])) ?></td>
                            <td><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($disenios['fecha_solicitud'])))) ?></td>
                            <td><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($disenios['fecha_entrega'])))) ?></td>
                            <td><?php echo remove_junk(ucwords($disenios['tipo_disenio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($disenios['tema_disenio'])) ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 3%;">Área que Solicito</th>
                            <th style="width: 3%;">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>                            
                            <td><?php echo remove_junk(ucwords($nombre_area['nombre_area'])) ?></td>
                            <td><?php echo remove_junk(ucwords($disenios['observaciones'])) ?></td>                           
                        </tr>
                    </tbody>
                </table>
                
                <a href="disenios.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>