<?php
$page_title = 'Comunicado Prensa';
require_once('includes/load.php');
?>
<?php

$comunicados = find_by_id('comunicados', (int)$_GET['id'], 'id_comunicados');
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
                    <span>Comunicado de Presnsa   <?php echo $comunicados['folio'] ?></span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 3%;">Folio</th>
                            <th style="width: 3%;">Fecha de Publicación</th>
                            <th style="width: 5%;">Tipo de Comunicado </th>
                            <th style="width: 3%;">Título del Comunicado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($comunicados['folio'])) ?></td>
                            <td><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($comunicados['fecha_publicacion'])))) ?></td>
                            <td><?php echo remove_junk(ucwords($comunicados['tipo_nota'])) ?></td>
                            <td><?php echo remove_junk(ucwords($comunicados['nombre_nota'])) ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 3%;">Link Acceso</th>
                            <th style="width: 3%;">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>                            
                            <td>
								<a href="<?php echo $comunicados['url_acceso']; ?>" data-toggle="tooltip" title="Ir a URL" target="_blank">
									<?php echo remove_junk(($comunicados['url_acceso'])) ?>
									</a>
							</td>                           
                            <td><?php echo remove_junk(ucwords($comunicados['observaciones'])) ?></td>                           
                        </tr>
                    </tbody>
                </table>
                
                <a href="comunicados_prensa.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>