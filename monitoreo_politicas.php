<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Monitoreo de Políticas Públicas ';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel = $user['user_level'];

$id_usuario = $user['id_user'];
$busca_area = area_usuario($id_usuario);
$otro = $busca_area['nivel_grupo'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 6) {
    page_require_level_exacto(6);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la '.$page_title.'  ', 5); 
    page_require_level_exacto(7);
}
if ($nivel_user == 23) {
    page_require_level_exacto(23);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la '.$page_title.'  ', 5); 
    page_require_level_exacto(53);
}

if ($nivel_user > 2 && $nivel_user < 6) :
    redirect('home.php');
endif;
if ($nivel_user > 6 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 23) :
    redirect('home.php');
endif;
if ($nivel_user > 23 && $nivel_user < 53) :
    redirect('home.php');
endif;
$all_detalles = find_all_order('monitoreo_politicas', 'folio');
?>

<?php include_once('layouts/header.php'); ?>
<a href="solicitudes_equidad.php" class="btn btn-success">Regresar</a><br><br>
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
                    <span>Monitoreo de Políticas Públicas</span>
                </strong>
				<?php if (($nivel_user <= 2) || ($nivel_user == 6) || ($nivel_user == 23) ) : ?>
                <a href="add_monitoreo_politicas.php" class="btn btn-info pull-right">Agregar Monitoreo</a>
				<?php endif;?>						
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 1%;">Ejercicio</th>
                            <th class="text-center" style="width: 7%;">Folio</th>
                            <th class="text-center" style="width: 7%;">Nombre Monitoreo</th>
                            <th class="text-center" style="width: 3%;">Fecha Inicio</th>
                            <th class="text-center" style="width: 3%;">¿Quién Atendió?</th>
                            <th class="text-center" style="width: 10%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_detalles as $a_detalle) : ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['ejercicio'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['folio'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['nombre_monitoreo'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['fecha_inicio'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['quien_atendio'])) ?>
                                </td>								
                                <td class="text-center">
                                    <div class="btn-group"> 
<a href="ver_info_monitoreo_politicas.php?id=<?php echo (int)$a_detalle['id_monitoreo_politicas']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                        <i class="glyphicon glyphicon-eye-open"></i>
                                    </a>&nbsp;
<?php if (($nivel_user < 3) || ($nivel_user == 23)) : ?>														
                                        <a href="edit_monitoreo_politicas.php?id=<?php echo (int) $a_detalle['id_monitoreo_politicas']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>&nbsp;
										<a href="seguimiento_monitoreo_politicas.php?id=<?php echo (int)$a_detalle['id_monitoreo_politicas']; ?>" class="btn btn-md btn-gre" data-toggle="tooltip" title="Seguimiento">
                                                    <i class="glyphicon glyphicon-sort-by-attributes-alt"></i>
                                                </a>
										<?php endif;?>
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