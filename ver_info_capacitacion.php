<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Capacitaciones';
require_once('includes/load.php');
?>
<?php
$a_capacitacion = find_by_id('capacitaciones', (int)$_GET['id'], 'id_capacitacion');
$grupos_vuln = find_all_grupos((int)$_GET['id']);
$area = find_by_id('area',$a_capacitacion['area_creacion'],'id_area');
$user = current_user();
$nivel_user = $user['user_level'];

page_require_level(53);
if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$a_capacitacion['folio'], 5);   
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
                    <span>Información de la Capacitación <?php echo $a_capacitacion['folio'] ?></span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 20%;">Folio</th>
                            <th style="width: 20%;">Tipo de Divulgación</th>
                            <th style="width: 20%;">Nombre de la Capacitación</th>
                            <th style="width: 10%;">Tipo de Evento</th>
                            <th style="width: 20%;">Modalidad</th>
                            <th style="width: 20%;">¿Quién Solicita?</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['tipo_capacitacion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['nombre_capacitacion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['tipo_evento'])) ?></td>
                            <td><?php echo remove_junk((ucwords($a_capacitacion['modalidad']))) ?></td>
                            <td><?php echo remove_junk((ucwords($a_capacitacion['quien_solicita']))) ?></td>

                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 20%;">Fecha</th>
                            <th style="width: 20%;">Hora</th>
                            <th style="width: 20%;">Lugar</th>
                            <th style="width: 20%;">Depto./ Org.</th>
                            <th style="width: 20%;">Capacitador</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['fecha'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['hora'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['lugar'])) ?></td>
                            <td><?php echo remove_junk((ucwords($area['nombre_area']))) ?></td>
                            <td><?php echo remove_junk((ucwords($a_capacitacion['capacitador']))) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
				<div class="row">
				 <h3 style="font-weight:bold;">
                    <span class="material-symbols-outlined">checklist</span>
                    Asistentes
                </h3>
			</div>
				<table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 20%;"> Hombres</th>
                            <th style="width: 20%;"> Mujeres</th>
                            <th style="width: 20%;"> No Binarios</th>
                            <th style="width: 20%;"> Otros</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['asistentes_hombres'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['asistentes_mujeres'])) ?></td>
                            <td><?php echo remove_junk((ucwords($a_capacitacion['asistentes_nobinario']))) ?></td>
                            <td><?php echo remove_junk((ucwords($a_capacitacion['asistentes_otros']))) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
				<table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 20%;"> De 0 a 10 años</th>
                            <th style="width: 20%;"> De 11 a 20 años</th>
                            <th style="width: 20%;"> De 21 a 30 años</th>
                            <th style="width: 20%;"> De 31 a 40 años</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['asistentes_10'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['asistentes_20'])) ?></td>
                            <td><?php echo remove_junk((ucwords($a_capacitacion['asistentes_30']))) ?></td>
                            <td><?php echo remove_junk((ucwords($a_capacitacion['asistentes_40']))) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
				<table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 20%;"> De 41 a 50 años</th>
                            <th style="width: 20%;"> De 51 a 60 años</th>
                            <th style="width: 20%;"> De 61 a 70 años</th>
                            <th style="width: 20%;"> Más de 71 años</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['asistentes_50'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_capacitacion['asistentes_60'])) ?></td>
                            <td><?php echo remove_junk((ucwords($a_capacitacion['asistentes_70']))) ?></td>
                            <td><?php echo remove_junk((ucwords($a_capacitacion['asistentes_80']))) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
				<div class="row">
				 <h3 style="font-weight:bold;">
                    <span class="material-symbols-outlined">checklist</span>
                    Grupos Vulnerables
                </h3>
				</div>
				<table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 20%;"></th>
                            <th style="width: 20%;">Grupo Vulnerable</th> 
							</tr>
                    </thead>
                    <tbody>
					<?php foreach ($grupos_vuln as $grupo) : ?>
                        <tr>
                            <td><?php echo remove_junk(ucwords($grupo['descripcion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($grupo['no_asistentes'])) ?></td>                                   
                        </tr>
					<?php endforeach; ?>                            
                    </tbody>
                </table>
                <a href="capacitaciones.php?a=<?php echo $a_capacitacion['area_creacion']?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>