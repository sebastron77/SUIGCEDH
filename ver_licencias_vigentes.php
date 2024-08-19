<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<?php
$page_title = 'Ver información de las licencias';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$licencias = find_all_lic_vig();

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}
if ($nivel_user == 2) {
    page_require_level_exacto(2);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
}
if ($nivel_user > 2 && $nivel_user < 14) :
    redirect('home.php');
endif;
if ($nivel_user > 14) :
    redirect('home.php');
endif;

?>
<?php include_once('layouts/header.php'); ?>

<a href="detalles_usuario.php" class="btn btn-success">Regresar</a><br><br>

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
                    <span>Información de licencias vigentes del personal de la CEDH</span>
                </strong>
            </div>
            <div class="panel-body">
                <table class="table datatable table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <!-- <th class="text-center" style="width: 1%;">#</th> -->
                            <th class="text-center" style="width: 2%;">Cant. de Permisos</th>
                            <th class="text-center" style="width: 12%;">Trabajador</th>
                            <th class="text-center" style="width: 3%;">Tipo Licencia</th>
                            <th class="text-center" style="width: 1%;">No. Días</th>
                            <th class="text-center" style="width: 1%;">Días Rest.</th>
                            <th class="text-center" style="width: 4%;">Fecha Inicio</th>
                            <th class="text-center" style="width: 4%;">Fecha Conclusión</th>
                            <th class="text-center" style="width: 10%;">Observaciones</th>
                            <th class="text-center" style="width: 10%;">Documento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($licencias as $lic) : ?>
                            <?php $diferencia = (strtotime($lic['fecha_termino']) - strtotime(date("Y-m-d"))) / 86400; ?>
                            <tr>
                                <!-- <td class="text-center"><?php echo count_id(); ?></td> -->
                                <td class="text-center"><?php echo $lic['no_consec']; ?></td>
                                <td><?php echo $lic['nombre'] . ' ' . $lic['apellidos'] ?></td>
                                <td><?php echo $lic['tipo_lic'] ?></td>
                                <td class="text-center"><?php echo $lic['no_dias'] ?></td>
                                <?php if ($diferencia <= 0) : ?>
                                    <td class="text-center" style="color: red; font-weight: bold;" ;><?php echo $diferencia ?></td>
                                <?php else : ?>
                                    <td class="text-center"><?php echo $diferencia ?></td>
                                <?php endif; ?>
                                <td class="text-center"><?php echo $lic['fecha_inicio'] ?></td>
                                <td class="text-center"><?php echo $lic['fecha_termino'] ?></td>
                                <td><?php echo $lic['observaciones'] ?></td>
                                <td><a href="/suigcedh/uploads/personal/licencias/<?php echo $lic['id_rel_licencia_personal'] . '/' . $lic['documento'] ?>"><?php echo $lic['documento'] ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>