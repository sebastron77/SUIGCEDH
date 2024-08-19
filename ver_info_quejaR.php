<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Queja';
require_once('includes/load.php');
?>
<?php
$e_detalle = find_by_id_quejaR((int) $_GET['id']);
$user = current_user();
$nivel = $user['user_level'];
$cat_est_procesal = find_all('cat_est_procesal');
$cat_municipios = find_all_cat_municipios();

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    redirect('home.php');
}
if ($nivel == 4) {
    redirect('home.php');
}
if ($nivel == 5) {
    page_require_level(5);
}
if ($nivel == 6) {
    redirect('home.php');
}
if ($nivel == 7) {
    redirect('home.php');
}

if ($nivel == 7 || $nivel == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Nombre:'.($e_detalle['folio_queja_p']), 5);   
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
                    <span>Información de Queja</span>
                </strong>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 14%;" class="text-center">Folio</th>
                            <th style="width: 10%;" class="text-center">Fecha Creación</th>
                            <th style="width: 15%;" class="text-center">Nombre</th>
                            <th style="width: 15%;" class="text-center">Apellido Paterno</th>
                            <th style="width: 15%;" class="text-center">Apellido Materno</th>
                            <th style="width: 7%;" class="text-center">Genero</th>
                            <th style="width: 5%;" class="text-center">Edad</th>
                            <th style="width: 7%;" class="text-center">Escolaridad</th>
                            <th style="width: 7%;" class="text-center">Ocupación</th>
                            <th style="width: 7%;" class="text-center">Grupo Vulnerable</th>
                            <th style="width: 7%;" class="text-center">Nacionalidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['folio_queja_p'])) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['fecha_creacion'])) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['nombre'])) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['paterno'])) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['materno'])) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['genero'])) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['edad'])) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['escolaridad'])) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['ocupacion'])) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['grupo_vuln'])) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['nacionalidad'])) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th style="width: 10%;" class="text-center">Correo</th>
                            <th style="width: 1%;" class="text-center">Telefono</th>
                            <th style="width: 15%;" class="text-center">Calle y Núm.</th>
                            <th style="width: 15%;" class="text-center">Colonia</th>
                            <th style="width: 3%;" class="text-center">Código Postal</th>
                            <th style="width: 30%;" class="text-center">Descripción de los hechos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['correo'])) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['telefono'])) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['calle'] . " #" . $e_detalle['numero'])) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['colonia'])) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['codigo_postal'])) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['descripcion_hechos'])) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th style="width: 7%;" class="text-center">Entidad</th>
                            <th style="width: 7%;" class="text-center">Municipio</th>
                            <th style="width: 7%;" class="text-center">Localidad</th>
                            <th style="width: 7%;" class="text-center">Autoridad Responsable</th>
                            <th style="width: 7%;" class="text-center">Archivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['entidad'])) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['municipio'])) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['localidad'])) ?>
                            </td>
                            <td class="text-center">
                                <?php echo remove_junk(ucwords($e_detalle['nombre_autoridad'])) ?>
                            </td>
                            <?php $folio_editar = $e_detalle['folio_queja_p'];
                            $resultado = str_replace("/", "-", $folio_editar); ?>
                            <td class="text-center">
                                <a target="_blank" style="color: #0094FF;" href="uploads/quejas_publicas/<?php echo $resultado . '/' . $e_detalle['archivo']; ?>"><?php echo $e_detalle['archivo']; ?></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <a href="quejas_publicas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>