<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Datos de Contacto de Politicas Públicas';
require_once('includes/load.php');
?>
<?php
$all_detalles = find_all_contactos('contactos_politicas');
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
                    <span>Contacto de Politicas Públicas</span>
                </strong>
				<?php if (($nivel_user <= 2) || ($nivel_user == 6) || ($nivel_user == 23) ) : ?>
                <a href="add_contacto_monitoreo_politicas.php" class="btn btn-info pull-right">Agregar Contacto</a>
				<?php endif;?>						
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 1%;">ID</th>
                            <th class="text-center" style="width: 7%;">Nombre(s)</th>
                            <th class="text-center" style="width: 7%;">Apellidos</th>
                            <th class="text-center" style="width: 3%;">Género</th>
                            <th class="text-center" style="width: 3%;">Teléfono</th>
                            <th class="text-center" style="width: 3%;">Email</th>
                            <th class="text-center" style="width: 10%;">Dependencia</th>
                            <th class="text-center" style="width: 10%;">Cargo</th>
							<?php if (($nivel_user <= 2) || ($nivel_user == 6) || ($nivel_user == 23) ) : ?>
                            <th class="text-center" style="width: 10%;">Acciones</th>
							<?php endif;?>		
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_detalles as $a_detalle) : ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['id_contactos_politicas'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['nombres'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['apellidos'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['genero'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['telefono'])) ?>
                                </td>
								<td class="text-center">
                                    <?php echo remove_junk(($a_detalle['email'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(($a_detalle['nombre_autoridad'])) ?>
                                </td>
								<td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['cargo_desempelado'])) ?>
                                </td>                                
										<?php if (($nivel_user <= 2) || ($nivel_user == 6) || ($nivel_user == 23) ) : ?>
                                <td class="text-center">
                                    <div class="btn-group">                                       
                                        <a href="edit_contacto_monitoreo_politicas.php?id=<?php echo (int) $a_detalle['id_contactos_politicas']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
                                    </div>
                                </td>
										<?php endif;?>	
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>