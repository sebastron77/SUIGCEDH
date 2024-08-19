<?php
$page_title = 'Datos quejoso';
require_once('includes/load.php');
?>
<?php
header('Content-Type: text/html; charset=UTF-8');
page_require_level(50);
//$all_detalles = find_all_trabajadores();
$e_detalle = find_by_id_cat_quejoso((int) $_GET['id']);
$user = current_user();
$nivel = $user['user_level'];

page_require_level(53);
if ($nivel == 7 || $nivel == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Nombre:'.($e_detalle['nombre'] . " " . $e_detalle['paterno'] . " " . $e_detalle['materno']), 5);   
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
                    <span>Información completa del quejoso:
                        <?php echo remove_junk(ucwords($e_detalle['nombre'] . " " . $e_detalle['paterno'] . " " . $e_detalle['materno'])) ?>
                    </span>
                </strong>
            </div>

            <div class="panel-body">

                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 1%;">ID Quejoso</th>
                            <th class="text-center" style="width: 10%;">Nombre</th>
                            <th class="text-center" style="width: 10%;">Apellidos</th>
                            <th class="text-center" style="width: 1%;">Sexo</th>
                            <th class="text-center" style="width: 1%;">Edad</th>
                            <th class="text-center" style="width: 3%;">Nacionalidad</th>
                            <th class="text-center" style="width: 8%;">Municipio</th>
                            <th class="text-center" style="width: 7%;">Escolaridad</th>
                            <th class="text-center" style="width: 10%;">Ocupación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- <td class="text-center"><?php echo count_id(); ?></td> -->
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['id_cat_quejoso']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['nombre']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['paterno'] . " " . $e_detalle['materno']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['genero']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['edad']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['nacionalidad']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['municipio']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['escolaridad']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['ocupacion']) ?>
                            </td>
                        </tr>
                    </tbody>

                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th class="text-center" style="width: 3%;">Teléfono</th>
                            <th class="text-center" style="width: 15%;">Email</th>
                            <th class="text-center" style="width: 8%;">¿Sabe leer y/o escribir?</th>
                            <th class="text-center" style="width: 15%;">Grupo Vulnerable</th>
                            <th class="text-center" style="width: 8%;">¿Tiene alguna discapacidad?</th>
                            <th class="text-center" style="width: 8%;">Comunidad a la que pertenece</th>
                            <th class="text-center" style="width: 8%;">Calle</th>
                            <th class="text-center" style="width: 8%;">Número</th>
                            <th class="text-center" style="width: 8%;">Colonia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['telefono']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['email']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['leer_escribir']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['grupo_vuln']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['discapacidad']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['comunidad']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['calle_quejoso']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['numero_quejoso']) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['colonia_quejoso']) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-9">
                        <a href="quejosos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                    </div>
                    <div class="col-md-3">

                        <!-- <a href="edit_quejoso.php?id=<?php echo (int) $e_detalle['id_cat_quejoso']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                Editar
                            </a> -->

                        <!-- <?php if ($nivel == 1): ?>
                            <?php if ($e_detalle['estatus_detalle'] == 1): ?>
                                <a href="inactivate_detalle_usuario.php?id=<?php echo (int) $e_detalle['id_det_usuario']; ?>" class="btn btn-md btn-danger" data-toggle="tooltip" title="Inactivar">
                                    Inactivar
                                </a>
                            <?php endif; ?>
                            <?php if ($e_detalle['estatus_detalle'] == 0): ?>
                                <a href="activate_detalle_usuario.php?id=<?php echo (int) $e_detalle['id_det_usuario']; ?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Activar">
                                    Activar
                                </a>
                            <?php endif; ?> -->
                            <!-- <a href="delete_detalle_usuario.php?id=<?php echo (int) $e_detalle['id']; ?>" class="btn btn-delete btn-md" title="Eliminar" data-toggle="tooltip" onclick="return confirm('¿Seguro que deseas eliminar este trabajador? También se eliminarán su usuario, asignaciones y resguardos.');">
                                Eliminar
                            </a> -->
                            <!-- <?php endif; ?> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>