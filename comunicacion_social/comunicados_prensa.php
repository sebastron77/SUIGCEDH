<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Cominicados de Prensa';
require_once('includes/load.php');
?>
<?php

$user = current_user();
$nivel = $user['user_level'];
$nivel_user = $user['user_level'];
$all_comunicados = find_all_order('comunicados', 'fecha_publicacion');

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 15) {
    page_require_level_exacto(15);
}
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user >7  && $nivel_user < 15) :
    redirect('home.php');
endif;
if ($nivel_user > 15 ) :
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
                    <span>Comunicados de Prensa</span>
                </strong>  
<?php if (($nivel == 1) || ($nivel == 15)) : ?>
                    <a href="add_comunicado.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Cominicado</a>
                <?php endif; ?>
                <form action=" <?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <button style="float: right; margin-top: -20px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>				
            </div>
        </div>

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr>
                        <th width="10%">Folio</th>
                        <th width="10%">Fecha Cominicado</th>
                        <th width="10%">Medio presentación</th>
                        <th width="10%">Tema</th>
                        <th width="10%">Link Acceso</th>
                        <th width="10%">Aciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_comunicados as $datos) : ?>
                        <tr>
                               <td><?php echo remove_junk(ucwords($datos['folio'])) ?></td> 
                               <td><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($datos['fecha_publicacion'])))) ?></td> 
                               <td><?php echo remove_junk(ucwords($datos['tipo_nota'])) ?></td> 
                               <td><?php echo remove_junk(ucwords($datos['nombre_nota'])) ?></td> 
                               <td>
									<a href="<?php echo $datos['url_acceso']; ?>" data-toggle="tooltip" title="Ir a URL" target="_blank">
									<?php echo remove_junk(($datos['url_acceso'])) ?>
									</a>
								</td> 
                               <td class="text-center">
								<a href="ver_info_comunicado.php?id=<?php echo (int)$datos['id_comunicados']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información completa">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
										
									<a href="edit_comunicado.php?id=<?php echo (int)$datos['id_comunicados']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
										<i class="glyphicon glyphicon-pencil"></i>
									</a>
								</td> 
                                          
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- </div> -->
<?php include_once('layouts/footer.php'); ?>