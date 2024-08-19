<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Estadísticas de Redes Sociales';
require_once('includes/load.php');
?>
<?php
$informe_redes_sociales = find_by_id('estadisticas_redes', (int)$_GET['id'], 'id_estadisticas_redes');
$user = current_user();
$nivel = $user['user_level'];

page_require_level(53);
if ($nivel == 7 || $nivel == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$informe_redes_sociales['folio'], 5);   
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
                    <span>Información de la Estadísticas de Redes Sociales <?php echo $informe_redes_sociales['folio'] ?></span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 20%;">Folio</th>
                            <th style="width: 20%;">Ejercio</th>
                            <th style="width: 20%;">Mes</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($informe_redes_sociales['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($informe_redes_sociales['ejercicio'])) ?></td>
							<?php if($informe_redes_sociales['mes'] == 1):?><td class="text-center">Enero</td><?php endif;?>
						<?php if($informe_redes_sociales['mes'] == 2):?><td class="text-center">Febrero</td><?php endif;?>
						<?php if($informe_redes_sociales['mes'] == 3):?><td class="text-center">Marzo</td><?php endif;?>
						<?php if($informe_redes_sociales['mes'] == 4):?><td class="text-center">Abril</td><?php endif;?>
						<?php if($informe_redes_sociales['mes'] == 5):?><td class="text-center">Mayo</td><?php endif;?>
						<?php if($informe_redes_sociales['mes'] == 6):?><td class="text-center">Junio</td><?php endif;?>
						<?php if($informe_redes_sociales['mes'] == 7):?><td class="text-center">Julio</td><?php endif;?>
						<?php if($informe_redes_sociales['mes'] == 8):?><td class="text-center">Agosto</td><?php endif;?>
						<?php if($informe_redes_sociales['mes'] == 9):?><td class="text-center">Septiembre</td><?php endif;?>
						<?php if($informe_redes_sociales['mes'] == 10):?><td class="text-center">Octubre</td><?php endif;?>
						<?php if($informe_redes_sociales['mes'] == 11):?><td class="text-center">Noviembre</td><?php endif;?>
						<?php if($informe_redes_sociales['mes'] == 12):?><td class="text-center">Diciembre</td><?php endif;?>

                        </tr>
                    </tbody>
                </table>
				
				<h3 style="font-weight:bold;">
                    <span class="material-symbols-outlined">checklist</span>
                    Facebook
                </h3> 
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 20%;">Alcance</th>
                            <th style="width: 20%;">Visitas al Perfil</th>
                            <th style="width: 20%;">Nuevos Me Gusta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($informe_redes_sociales['fb_alcance'])) ?></td>
                            <td><?php echo remove_junk(ucwords($informe_redes_sociales['fb_visitas'])) ?></td>
                            <td><?php echo remove_junk(ucwords($informe_redes_sociales['fb_nuevos'])) ?></td>
                            
                        </tr>
                    </tbody>
                </table>	
				<h3 style="font-weight:bold;">
                    <span class="material-symbols-outlined">checklist</span>
                    Instagram
                </h3> 
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 20%;">Alcance</th>
                            <th style="width: 20%;">Visitas al Perfil</th>
                            <th style="width: 20%;">Nuevos Me Gusta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($informe_redes_sociales['ins_alcance'])) ?></td>
                            <td><?php echo remove_junk(ucwords($informe_redes_sociales['ins_visitas'])) ?></td>
                            <td><?php echo remove_junk(ucwords($informe_redes_sociales['ins_nuevos'])) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
				<h3 style="font-weight:bold;">
                    <span class="material-symbols-outlined">checklist</span>
                    X
                </h3> 
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 20%;">Impresiones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($informe_redes_sociales['x_impresiones'])) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
				<h3 style="font-weight:bold;">
                    <span class="material-symbols-outlined">checklist</span>
                    Tiktok
                </h3> 
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 20%;">Visualizaciones de Videos</th>
                            <th style="width: 20%;">Visualizaciones de Perfil</th>
                            <th style="width: 20%;">Me Gusta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($informe_redes_sociales['tk_vizualizacion_video'])) ?></td>
                            <td><?php echo remove_junk(ucwords($informe_redes_sociales['tk_vizualizacion_perfil'])) ?></td>
                            <td><?php echo remove_junk(ucwords($informe_redes_sociales['tk_like'])) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
				<table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 20%;">Comentarios</th>
                            <th style="width: 20%;">Veces Compartido</th>
                            <th style="width: 20%;">Espectadores Únicos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($informe_redes_sociales['tk_comentarios'])) ?></td>
                            <td><?php echo remove_junk(ucwords($informe_redes_sociales['tk_compartido'])) ?></td>
                            <td><?php echo remove_junk(ucwords($informe_redes_sociales['tk_espectadores_unicos'])) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
                <a href="informe_redes_sociales.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>