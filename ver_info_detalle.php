<?php
$page_title = 'Datos trabajadores';
require_once('includes/load.php');
?>
<?php
header('Content-Type: text/html; charset=UTF-8');

$e_detalle = find_by_id('detalles_usuario', (int) $_GET['id'], 'id_det_usuario');
$e_detalle_cargo = find_detalle_cargo((int) $_GET['id']);
$cargos = find_all('cargos');
$generos = find_all('cat_genero');
$areas = find_all('area');
$user = current_user();
$nivel_user = $user['user_level'];

page_require_level(53);
if ($nivel_user == 7 || $nivel_user == 53) {
    insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de ' . $page_title . ' . Folio:' . $e_detalle['nombre'] . ' ' . $e_detalle['apellidos'], 5);
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
                    <span>Información de
                        <?php echo remove_junk(ucwords($e_detalle['nombre'])) ?>
                        <?php echo remove_junk(ucwords($e_detalle['apellidos'])) ?>
                    </span>
                    <a href="resumen_info_detalle.php?id=<?php echo (int)$_GET['id']; ?>" style="margin-left: 15%;">Resumen del Trabajador</a>
                </strong>
            </div>

            <div class="panel-body">

                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 1%;">ID</th>
                            <th class="text-center" style="width: 10%;">Nombre</th>
                            <th class="text-center" style="width: 10%;">Apellidos</th>
                            <th class="text-center" style="width: 5%;">Género</th>
                            <th class="text-center" style="width: 10%;">Correo</th>
                            <th class="text-center" style="width: 20%;">Cargo - Área</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <?php echo remove_junk($e_detalle['id_det_usuario']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['nombre']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['apellidos']) ?>
                            </td>
                            <td>
                                <?php
                                foreach ($generos as $genero) :
                                    if ($genero['id_cat_gen'] === $e_detalle['id_cat_gen'])
                                        echo $genero['descripcion'];
                                endforeach;
                                ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['correo']) ?>
                            </td>
                            <td>
                                <?php
                                foreach ($cargos as $cargo) : foreach ($areas as $area) :
                                        if ($cargo['id_cargos'] === $e_detalle['id_cargo'])
                                            if ($area['id_area'] === $cargo['id_area'])
                                                echo $cargo['nombre_cargo'] . " - " . $area['nombre_area'];
                                    endforeach;
                                endforeach;
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-9">
                        <a href="detalles_usuario.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>