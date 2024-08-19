<?php
$page_title = 'Entrevista';
require_once('includes/load.php');
?>
<?php

$entrevista = find_by_id('entrevistas', (int)$_GET['id'], 'id_entrevistas');

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 3) {
    page_require_level_exacto(3);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7) :
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
                    <span>Entrevista  <?php echo $entrevista['folio'] ?></span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 3%;">Folio</th>
                            <th style="width: 3%;">Fecha Entrevista</th>
                            <th style="width: 5%;">Tema Entrevista</th>
                            <th style="width: 3%;">Lugar Entrevista</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($entrevista['folio'])) ?></td>
                            <td><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($entrevista['fecha_entrevista'])))) ?></td>
                            <td><?php echo remove_junk(ucwords($entrevista['tema_entrevista'])) ?></td>
                            <td><?php echo remove_junk(ucwords($entrevista['lugar_entrevista'])) ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 3%;">Nombre Entrevistado</th>
                            <th style="width: 3%;">Cargo de Entrevistado</th>
                            <th style="width: 5%;">Temas Destacados</th>
                            <th style="width: 3%;">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>                            
                            <td><?php echo remove_junk(ucwords($entrevista['nombre_entrevistado'])) ?></td>
                            <td><?php echo remove_junk(ucwords($entrevista['cargo_entrevistado'])) ?></td>
                            <td><?php echo remove_junk(ucwords($entrevista['temas_destacados'])) ?></td>
                            <td><?php echo remove_junk(ucwords($entrevista['observaciones'])) ?></td>                           
                        </tr>
                    </tbody>
                </table>
                
                <a href="entrevistas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>