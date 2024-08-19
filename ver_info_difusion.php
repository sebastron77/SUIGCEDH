<?php
$page_title = 'Difusión';
require_once('includes/load.php');
?>
<?php

$difusion = find_by_id_difusion((int)$_GET['id']);
$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 3) {
    page_require_level_exacto(3);
}
if ($nivel_user == 15) {
    page_require_level_exacto(15);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 15) :
    redirect('home.php');
endif;

if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$difusion['folio'], 5);   
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
                    <span>Difusión <?php echo $difusion['folio'] ?></span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 1%;">Folio</th>
                            <th class="text-center" style="width: 1%;">Fecha</th>
                            <th class="text-center" style="width: 1%;">Tipo de Difusión</th>
                            <th class="text-center" style="width: 5%;">Tema</th>
                            <th class="text-center" style="width: 5%;">Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords($difusion['folio'])) ?></td>
                            <td class="text-center"><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($difusion['fecha'])))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($difusion['tipd'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($difusion['tema'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($difusion['link'])) ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 3%;">PDF</th>
                            <th class="text-center" style="width: 2%;">Entrevistado</th>
                            <th class="text-center" style="width: 3%;">Medio</th>
                        </tr>
                    </thead>
                    <?php
                    $folio_editar = $difusion['folio'];
                    $resultado = str_replace("/", "-", $folio_editar);
                    $carpeta = 'uploads/difusiones_CS/' . $resultado;
                    ?>
                    <tbody>
                        <tr>
                            <td class="text-center"><a target="_blank" style="color:#3D94FF" href="<?php echo $carpeta . '/' . $difusion['pdf']; ?>"><?php echo $difusion['pdf']; ?></a></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($difusion['entrevistado'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($difusion['medio'])) ?></td>
                        </tr>
                    </tbody>
                </table>

                <a href="difusion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
				
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>