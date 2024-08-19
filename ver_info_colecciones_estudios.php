<?php
$page_title = 'Colecciones de Estuios';
require_once('includes/load.php');
?>
<?php

$e_coleccion = find_by_colecciones((int)$_GET['id']);

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 6) {
    page_require_level_exacto(6);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}

if ($nivel_user > 3 && $nivel_user < 6) :
    redirect('home.php');
endif;
if ($nivel_user > 7) :
    redirect('home.php');
endif;

if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$e_coleccion['folio'], 5);   
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
                    <span>Colecciones de Estuios  <?php echo $e_coleccion['folio'] ?></span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 5%;">Folio</th>
                            <th style="width: 3%;">Tipo Publicación</th>
                            <th style="width: 5%;">Temporalidad de Proyecto</th>
                            <th style="width: 3%;">Nombre Colección</th>
                            <th style="width: 3%;">Área Responsable</th>
                            <th style="width: 3%;">Nombre del Responsable</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($e_coleccion['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_coleccion['tipo_publicacion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_coleccion['temporalidad_proyecto'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_coleccion['nombre_coleccion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_coleccion['nombre_area'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_coleccion['nombre_responsable'])) ?></td>
                        </tr>
                    </tbody>
                </table>

				<table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 3%;">Hipervinculo al Documento</th>
                            <th style="width: 3%;">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: center;">
                                <a target="_blank" style="color: red;" href="<?php echo $e_coleccion['hipervinculo_proyecto']; ?>">
								 <?php echo $e_coleccion['hipervinculo_proyecto']; ?>
								 </a>
							</td>
                            <td><?php echo remove_junk(ucwords($e_coleccion['observaciones'])) ?></td>
                        </tr>
                    </tbody>
                </table>

							
<br>
<br>
                
                <a href="colecciones_estudios.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>