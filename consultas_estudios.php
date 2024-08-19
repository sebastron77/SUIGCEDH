<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Consultas';
require_once('includes/load.php');
?>
<?php
$all_detalles = find_all_certificaciones();
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
    page_require_level_exacto(7);
}

if (($nivel_user > 2 && $nivel_user < 6)) :
    redirect('home.php');
endif;

if ($nivel_user > 7) :
    redirect('home.php');
endif;
?>

<?php include_once('layouts/header.php'); ?>
<a href="solicitudes_centro_estudios.php" class="btn btn-success">Regresar</a><br><br>
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
                    <span>Lista de Consultas</span>
                </strong>
				<?php if (($nivel_user <= 2) || ($nivel_user == 6) ) : ?>
					<a href="add_certificaciones.php" class="btn btn-info pull-right">Agregar Certificación</a>
				<?php endif; ?>
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 1%;">Folio</th>
                            <th class="text-center" style="width: 7%;">Eje Estratégico</th>
                            <th class="text-center" style="width: 7%;">Agenda</th>
                            <th class="text-center" style="width: 7%;">Nombre Certificación</th>
                            <th class="text-center" style="width: 7%;">Fecha Inicio Proceso</th>
                            <th class="text-center" style="width: 7%;">Avance del Proceso</th>
                            <th class="text-center" style="width: 7%;">Nombre Institución</th>
                            <th class="text-center" style="width: 7%;">Contacto de la Institución</th>
                            <th class="text-center" style="width: 7%;">Área Responsable</th>
                            <th class="text-center" style="width: 7%;">Nombre Responsable</th>
							<?php if (($nivel_user <= 2) || ($nivel_user == 6) || ($nivel_user == 7)) : ?>
								<th style="width: 1%;" class="text-center">Acciones</th>
							<?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_detalles as $a_detalle) : ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['folio'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['nombre_eje'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['nommbre_agenda'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['nombre_certificacion'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo date("d-m-Y", strtotime(remove_junk(ucwords($a_detalle['fecha_inicio_proceso'])))) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['avance_proceso'])).'%' ?>
                                </td>
								<td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['emisor_certificacion'])) ?>
                                </td>
								<td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['contacto_certificacion'])) ?>
                                </td>
								<td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['nombre_area'])) ?>
                                </td>
								<td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['nombre_responsable'])) ?>
                                </td>
                                <td class="text-center">
								<?php if (($nivel_user <= 2) || ($nivel_user == 6) || ($nivel_user == 7)) : ?>
                                    <div class="btn-group">
                                        <a href="ver_info_certificaciones.php?id=<?php echo (int) $a_detalle['id_certificaciones']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
										<?php if (($nivel_user <= 2) || ($nivel_user == 6) ) : ?>
                                        <a href="edit_certificaciones.php?id=<?php echo (int) $a_detalle['id_certificaciones']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
										<?php endif; ?>
										<a href="edit_certificaciones_avance.php?id=<?php echo (int) $a_detalle['id_certificaciones']; ?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Avance Proceso">
                                            <i class="glyphicon glyphicon-arrow-up"></i>
                                        </a>
                                    </div>
									<?php endif; ?>
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