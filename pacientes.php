<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Datos de Pacientes';
require_once('includes/load.php');
?>
<?php
$all_detalles = find_all_pacientes('paciente');
$user = current_user();
$nivel = $user['user_level'];

$id_usuario = $user['id_user'];
$busca_area = area_usuario($id_usuario);
$otro = $busca_area['nivel_grupo'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 4) {
    page_require_level_exacto(4);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la '.$page_title.'  ', 5); 
    page_require_level_exacto(7);
}
if ($nivel_user == 9) {
    page_require_level_exacto(9);
}
if ($nivel_user == 22) {
    page_require_level_exacto(22);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la '.$page_title.'  ', 5); 
    page_require_level_exacto(53);
}

if ($nivel_user > 2 && $nivel_user < 4) :
    redirect('home.php');
endif;
if ($nivel_user > 4 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 22) :
    redirect('home.php');
endif;
if ($nivel_user > 22 && $nivel_user < 53) :
    redirect('home.php');
endif;
?>

<?php include_once('layouts/header.php'); ?>
<a href="solicitudes_servicios_tecnicos.php" class="btn btn-success">Regresar</a><br><br>
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
                    <span>Lista de Pacientes</span>
                </strong>
				<?php if (($nivel_user <= 2) || ($nivel_user == 4) || ($nivel_user == 9) || ($nivel_user == 22)) : ?>
                <a href="add_paciente.php" class="btn btn-info pull-right">Agregar Paciente</a>
				<?php endif;?>						
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 1%;">ID</th>
                            <th class="text-center" style="width: 7%;">Nombre(s)</th>
                            <th class="text-center" style="width: 7%;">Apellido Paterno</th>
                            <th class="text-center" style="width: 7%;">Apellido Materno</th>
                            <th class="text-center" style="width: 3%;">Género</th>
                            <th class="text-center" style="width: 1%">Edad</th>
                            <th class="text-center" style="width: 3%;">Teléfono</th>
                            <th class="text-center" style="width: 10%;">Grupo Vuln.</th>
                            <th class="text-center" style="width: 10%;">No. Expediente</th>
							
                            <th style="width: 1%;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_detalles as $a_detalle) : ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['id_paciente'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['nombre'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['paterno'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['materno'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['genero'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['edad'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['telefono'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['grupo_vulnerable'])) ?>
                                </td>
								<td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['folio'])) ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="ver_info_pacientes.php?id=<?php echo (int) $a_detalle['id_paciente']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
										<?php if (($nivel_user <= 2) || ($nivel_user == 4) || ($nivel_user == 9) || ($nivel_user == 22)) : ?>
                                        <a href="edit_paciente.php?id=<?php echo (int) $a_detalle['id_paciente']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                            <i class="glyphicon glyphicon-pencil"></i>
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