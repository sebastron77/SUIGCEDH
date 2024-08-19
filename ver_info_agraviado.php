<?php
$page_title = 'Datos Agraviado';
require_once('includes/load.php');
?>
<?php
header('Content-Type: text/html; charset=UTF-8');
page_require_level(50);
//$all_detalles = find_all_trabajadores();
$e_detalle = find_by_id_cat_agraviado((int) $_GET['id']);
$user = current_user();
$nivel_user = $user['user_level'];
page_require_level(53);
if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.remove_junk(ucwords($e_detalle['nombre'] . " " . $e_detalle['paterno'] . " " . $e_detalle['materno'])), 5);   
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
                    <span>Información completa del agaraviado:
                        <?php echo remove_junk(ucwords($e_detalle['nombre'] . " " . $e_detalle['paterno'] . " " . $e_detalle['materno'])) ?>
                    </span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 1%;">ID Agraviado</th>
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
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['id_cat_agrav']) ?>
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
                            <th class="text-center" style="width: 1%;">Teléfono</th>
                            <th class="text-center" style="width: 10%;">Email</th>
                            <th class="text-center" style="width: 5%;">¿Sabe leer y/o escribir?</th>
                            <th class="text-center" style="width: 10%;">Grupo Vulnerable</th>
                            <th class="text-center" style="width: 7%;">¿Tiene alguna discapacidad?</th>
                            <th class="text-center" style="width: 8%;">Comunidad a la que pertenece</th>
                            <th class="text-center" style="width: 8%;">PPL</th>
                            <th class="text-center" style="width: 8%;">Si es PPL, ¿Quién presenta la queja?</th>
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
                                <?php
                                if ($e_detalle['ppl'] == 0)
                                    echo 'No';
                                else {
                                    echo 'Sí';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk($e_detalle['catppl'])?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-9">
                        <a href="agraviados.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>