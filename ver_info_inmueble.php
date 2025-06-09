<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once('includes/load.php');
$page_title = 'Información del Inmueble';

$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

$ver_info = find_by_id_inmueble((int)$_GET['id']);

$folio_carpeta = str_replace("/", "-", $ver_info['folio']);
$carpeta = 'uploads/inmuebles/' . $folio_carpeta;

$fecha_compra = date("d/m/Y", strtotime($ver_info['fecha_adquisicion']));

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}
if ($nivel_user == 2) {
    page_require_level_exacto(2);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 28) {
    page_require_level_exacto(28);
}
if ($nivel_user == 29) {
    page_require_level_exacto(29);
}
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 28) :
    redirect('home.php');
endif;
if ($nivel_user > 29) {
    redirect('home.php');
}
if (!$nivel_user) {
    redirect('home.php');
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
                    <span>Información del Inmueble: <?php echo $ver_info['folio'] ?></span>
                </strong>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 3%;">Folio</th>
                            <th class="text-center" style="width: 3%;">Denominación Inmueble</th>
                            <th class="text-center" style="width: 1%;">Fecha Adquisición</th>
                            <th class="text-center" style="width: 3%;">Tipo Inmueble</th>
                            <th class="text-center" style="width: 3%;">Valor Catastral</th>
                            <th class="text-center" style="width: 3%;">Origen Propiedad</th>
                            <th class="text-center" style="width: 3%;">Titulo Posesión</th>
                            <th class="text-center" style="width: 10%;">Documento Posesión</th>
                            <th class="text-center" style="width: 10%;">Área Responsable</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo $ver_info['folio']; ?></td>
                            <td class="text-center"><?php echo $ver_info['denominacion']; ?></td>
                            <td class="text-center"><?php echo $fecha_compra ?></td>
                            <td class="text-center"><?php echo $ver_info['tipo_inmueble']; ?></td>
                            <td class="text-center"><?php echo $ver_info['valor_catastral']; ?></td>
                            <td class="text-center"><?php echo $ver_info['origen_propiedad']; ?></td>
                            <td class="text-center"><?php echo $ver_info['titulo_posesion']; ?></td>
                            <td class="text-center">
                                <a target="_blank" href="<?php echo $carpeta ?>/<?php echo $ver_info['documento_posesion'] ?>">
                                    <?php echo $ver_info['documento_posesion'] ?>
                                </a>
                            </td>
                            <td class="text-center"><?php echo $ver_info['area_responsable']; ?></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 10%;">Calle y Núm.</th>
                            <th class="text-center" style="width: 10%;">Colonia</th>
                            <th class="text-center" style="width: 3%;">Código Postal</th>
                            <th class="text-center" style="width: 10%;">Municipio</th>
                            <th class="text-center" style="width: 10%;">Localidad</th>
                            <th class="text-center" style="width: 10%;">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo $ver_info['calle_num']; ?></td>
                            <td class="text-center"><?php echo $ver_info['colonia']; ?></td>
                            <td class="text-center"><?php echo $ver_info['cod_pos']; ?></td>
                            <td class="text-center"><?php echo $ver_info['municipio']; ?></td>
                            <td class="text-center"><?php echo $ver_info['localidad']; ?></td>
                            <td class="text-center"><?php echo $ver_info['observaciones']; ?></td>
                        </tr>
                    </tbody>
                </table>
                <a href="bienes_inmuebles.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>