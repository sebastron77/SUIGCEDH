<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Colecciones de Estudios';
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
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);  
    page_require_level_exacto(7);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);  
    page_require_level_exacto(53);
}

if (($nivel_user > 2 && $nivel_user < 6)) :
    redirect('home.php');
endif;

if ($nivel_user > 7 && $nivel_user < 53) :
    redirect('home.php');
endif;
$all_detalles = $all_vistas = find_all_colecciones();
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
                    <span>Lista de Colecciones de Estuios</span>
                </strong>
				<?php if (($nivel_user <= 2) || ($nivel_user == 6) ) : ?>
					<a href="add_colecciones_estudios.php" class="btn btn-info pull-right">Agregar Colección</a>
				<?php endif; ?>
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 7%;">Folio</th>
                            <th class="text-center" style="width: 7%;">Tipo Publicación</th>
                            <th class="text-center" style="width: 7%;">Temporalidad del Proyecto</th>
                            <th class="text-center" style="width: 7%;">Nombre Colección</th>
                            <th class="text-center" style="width: 7%;">Hipervinculo al Documento</th>
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
                                    <?php echo remove_junk(ucwords($a_detalle['tipo_publicacion'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['temporalidad_proyecto'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_detalle['nombre_coleccion'])) ?>
                                </td>
                                <td class="text-center">
								<a target="_blank" style="color: #0094FF;" href="<?php echo $a_detalle['hipervinculo_proyecto']; ?>">
                                    <?php echo remove_junk(ucwords($a_detalle['hipervinculo_proyecto']))?>
									</a>
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
                                        <a href="ver_info_colecciones_estudios.php?id=<?php echo (int) $a_detalle['id_colecciones_estudios']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
										<?php if (($nivel_user <= 2) || ($nivel_user == 6) ) : ?>
                                        <a href="edit_colecciones_estudios.php?id=<?php echo (int) $a_detalle['id_colecciones_estudios']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
										<?php endif; ?>										
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