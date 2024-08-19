<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de gestiones jurisdiccionales';
require_once('includes/load.php');

// page_require_level(2);
$all_gestiones = find_all('gestiones_jurisdiccionales');
$user = current_user();
$nivel = $user['user_level'];

$id_usuario = $user['id_user'];
$busca_area = area_usuario($id_usuario);
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level(7);
}
if ($nivel_user == 52) {
    page_require_level_exacto(52);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 52) :
    redirect('home.php');
endif;

if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la '.$page_title, 5);   
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
                    <span>Gestiones Jurisdiccionales</span>
                </strong>
                <?php if (($nivel_user <= 2)  ) : ?>
                    <a href="add_gestion.php?a=4" class="btn btn-info pull-right btn-md"> Agregar gestión</a>
                <?php endif ?>
            </div>
            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr >
                            <th class="text-center" style="width: 1%;">#</th>
                            <th style="width: 20%;">Tipo Gestión</th>
                            <th class="text-center" style="width: 30%;">Descripción</th>
                            <th class="text-center" style="width: 30%;">Observaciones</th>
                            <?php if (($nivel_user <= 2)  ) : ?>
                                <th class="text-center" style="width: 5%;">Acciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_gestiones as $a_gestion) : ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td><?php echo remove_junk(ucwords($a_gestion['tipo_gestion'])) ?></td>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_gestion['descripcion'])) ?>
                                </td>
                                <?php
                                    $folio_editar = $a_gestion['folio'];
                                    $resultado = str_replace("/", "-", $folio_editar);
                                ?>
                                <td class="text-center">
                                    <?php echo remove_junk(ucwords($a_gestion['observaciones'])) ?>
                                </td>
                                <?php if (($nivel_user <= 2)  ) : ?>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="edit_gestion.php?id=<?php echo (int)$a_gestion['id_gestion']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                <i class="glyphicon glyphicon-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                <?php endif ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>