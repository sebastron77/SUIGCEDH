<?php
$page_title = 'Reunión de Trabajo';
require_once('includes/load.php');
?>
<?php

$a_reunion = find_by_id('reuniones_trabajo_ud', (int)$_GET['id'], 'id_reuniones_trabajo_ud');
$a_asistentes = find_by_asistenes( (int)$a_reunion['id_reuniones_trabajo_ud']);


$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) :
    page_require_level(2);
endif;
if ($nivel_user == 7) :
    page_require_level_exacto(7);
endif;
if ($nivel_user == 12) :
    page_require_level_exacto(12);
endif;
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 12) :
    redirect('home.php');
endif;


if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$a_reunion['folio'], 5);   
}

?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
 <a href="reuniones_ud.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
				<br><br>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Reunión de Trabajo <?php echo $a_reunion['folio'] ?></span>
                </strong>
                <!-- <a href="add_convenio.php" class="btn btn-info pull-right">Agregar convenio</a> -->
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 8%;">Folio</th>
                            <th >Fecha de la Reunión</th>                      
                            <th >Hora de la Reunión</th>                      
                            <th >Lugar de la Actividad</th>                      
                            <th >¿Quién Atendió?</th>                       
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_reunion['folio'])) ?></td>
                            <td><?php echo date("d-m-Y", strtotime(remove_junk(($a_reunion['fecha_reunion'])))) ?></td>
                            <td><?php echo remove_junk(ucwords($a_reunion['hora_reunion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_reunion['lugar_reunion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_reunion['quien_atendio'])) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
				
				<table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th >No. Personas Asistentes</th>
                            <th >Acciones a Realizar Derivadas de la Reunión</th>                      
                            <th >Observaciones</th>                        
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_reunion['no_asistentes'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_reunion['acciones_realizar'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_reunion['observaciones'])) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
				
				<div class="panel-heading">
					<strong>
						<span class="glyphicon glyphicon-user"></span>
						<span>Asistentes a la Reunion</span>
					</strong>
				</div>
				
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">                        
						<tr style="height: 10px;">
                            <th >Nombre </th>
                            <th >Género</th>
                            <th >Nombre de la Institución</th>
                        </tr>
                    </thead>
                    <tbody>
					<?php foreach ($a_asistentes as $adetalle) : ?>
                        <tr>
                            <td><?php echo remove_junk(ucwords($adetalle['nombre_participante'])) ?></td>
                            <td><?php echo remove_junk(ucwords($adetalle['genero'])) ?></td>
                            <td><?php echo remove_junk(ucwords($adetalle['institucion_participante'])) ?></td>
                            
                        </tr>
                    </tbody>
             <?php endforeach; ?>
                       
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>