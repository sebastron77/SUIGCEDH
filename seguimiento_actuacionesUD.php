<?php
$page_title = 'Seguimiento Actuaciones';
require_once('includes/load.php');
?>
<?php


$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 5) {
    page_require_level_exacto(5);
}
if ($nivel == 7) {
    page_require_level_exacto(7);
}
if ($nivel == 12) {
    page_require_level_exacto(19);
}
if ($nivel == 50) {
    page_require_level_exacto(50);
}

if ($nivel > 2 && $nivel < 5) :
    redirect('home.php');
endif;
if ($nivel > 5 && $nivel < 7) :
    redirect('home.php');
endif;
if ($nivel > 7 && $nivel < 12) :
    redirect('home.php');
endif;
if ($nivel > 12 && $nivel < 50) :
    redirect('home.php');
endif;



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
                    <span>Lista de Seguimiento de Actuaciones</span>
                </strong>
                <?php if (($nivel == 1) || ($nivel == 11) ) : ?>
                    <a href="add_seguimiento_ud.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Seguimiento</a>
                <?php endif; ?>
                
            </div>
        </div>

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr>
                        <th width="15%">Folio</th>
                        <th width="10%">Folio Actuación</th>
                        <th width="10%">Tipo Actuación</th>
                        <th width="10%">Área Asignada</th>
                        <th width="10%">Tipo Documento</th>
                        <?php if (($nivel <= 2) || ($nivel == 12) ) : ?>
                            <th width="10%;" class="text-center">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<?php include_once('layouts/footer.php'); ?>