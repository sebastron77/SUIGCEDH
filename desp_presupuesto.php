<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Presupuestos CEDH';
require_once('includes/load.php');
?>
<?php

$user = current_user();
$nivel = $user['user_level'];
$nivel_user = $user['user_level'];
$all_presupuesto = find_all_order('presupuesto', 'ejercicio');;

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
}

if ($nivel_user > 2 && $nivel_user < 14) :
    redirect('home.php');
endif;
if ($nivel_user > 14 ) :
    redirect('home.php');
endif;


?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<a href="solicitudes_gestion.php" class="btn btn-success">Regresar</a><br><br>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span> Presupuestos CEDH</span>
                </strong>
                <?php if (($nivel <=2) || ($nivel == 15)) : ?>
                    <a href="add_presupuesto.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Presupuesto</a>
                <?php endif; ?>
                <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <button style="float: right; margin-top: -20px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>
            </div>
        </div>

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr>
                        <th width="2%">Folio</th>
                        <th width="2%">Ejercicio</th>
                        <th width="2%">Aprobado</th>
                        <th width="2%">Ampliaciones /(Reducciones)</th>
                        <th width="2%">Modificado</th>
                        <th width="2%">Devengado</th>
                        <th width="2%">Pagado</th>
                        <th width="2%">Subejercicio</th>
                        <th width="1%">Aciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_presupuesto as $datos) :
                        
                    ?>
                        <tr>
                            <td><?php echo remove_junk(ucwords($datos['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($datos['ejercicio'])) ?></td>
                            <td><?php echo money_format('%n', $datos['monto_aprobado']) ?></td>
                            <td><?php echo money_format('%n', $datos['monto_apliacion_reduccion']) ?></td>
                            <td><?php echo money_format('%n', $datos['monto_devengado']) ?></td>
                            <td><?php echo money_format('%n', $datos['monto_pagado']) ?></td>
                            <td><?php echo money_format('%n', $datos['monto_ejercido']) ?></td>

                            <td class="text-center">
                                <a href="ver_info_presupuesto.php?id=<?php echo (int)$datos['id_presupuesto']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información completa">
                                    <i class="glyphicon glyphicon-eye-open"></i>
                                </a>
<?php if (($nivel <=2) || ($nivel == 15)) : ?>
                                <a href="edit_presupuesto.php?id=<?php echo (int)$datos['id_presupuesto']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                    <i class="glyphicon glyphicon-pencil"></i>
                                </a>
                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>