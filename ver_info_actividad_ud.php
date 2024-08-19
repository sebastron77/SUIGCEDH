<?php
$page_title = 'Actividades Desaparecidos';
require_once('includes/load.php');
?>
<?php

$a_actividadUD = find_by_id('actividades_ud', (int)$_GET['id'], 'id_actividades_ud');
$victima = find_by_id_victima_desaparecido('cat_victima_atendida',  $a_actividadUD['id_cat_victima_atendida'], 'id_cat_victima_atendida');
$colectivo = find_by_id_victima_colectivo($a_actividadUD['id_colectivos_atendidos']);
$desaparecido = find_by_id_victima_desaparecido('cat_persona_desaparecida',  $a_actividadUD['id_cat_persona_desaparecida'], 'id_cat_persona_desaparecida');
$tipo_actividad = find_by_id('cat_tipo_actividades_ud', $a_actividadUD['id_cat_tipo_actividades_ud'], 'id_cat_tipo_actividades_ud');

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel <= 2) {
    page_require_level(2);
}else if ($nivel == 7) {
    page_require_level(7);
}else if ($nivel == 12) {
    page_require_level(12);
}else{
	redirect('home.php');
}

if ($nivel == 7 || $nivel == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$a_actividadUD['folio'], 5);   
}

$persona= ($a_actividadUD['id_cat_victima_atendida']==null?0:$a_actividadUD['id_cat_victima_atendida']);
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
                    <span>Actividad <?php echo $a_actividadUD['folio'] ?></span>
                </strong>
                <!-- <a href="add_convenio.php" class="btn btn-info pull-right">Agregar convenio</a> -->
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 8%;">Folio</th>
                            <th >Tipo Actividad</th>                      
                            <th >Fecha de la Actividad</th>                      
                            <th >Quien lo Solicitó</th>                      
                            <th >NUC ó No. Expediente</th>                      
                            <th >Motivo ó Solicitud</th>                      
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_actividadUD['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_actividadUD['id_cat_tipo_actividades_ud'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_actividadUD['fecha_actividad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_actividadUD['nombre_solicitante'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_actividadUD['no_expediente'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_actividadUD['motivo_solicitud'])) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
				
				<table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th >No. Personas Atendidas</th>
                            <th >¿Quién Atendió?</th>                      
                            <th >Acciones</th>                      
                            <th >Observaciones</th>                        
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_actividadUD['no_atendidos'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_actividadUD['quien_atendio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_actividadUD['acciones'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_actividadUD['observaciones'])) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
				
				<div class="panel-heading">
					<strong>
						<span class="glyphicon glyphicon-user"></span>
						<span>Datos de la Victima Indirecta</span>
					</strong>
				</div><hr>
				<?php if($persona >0){ ?>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">                        
						<tr style="height: 10px;">
                            <th >Nombre de la Victima Atendida </th>
                            <th >Género</th>
                            <th >Edad</th>
                            <th >Comunidad</th>
                            <th >Escolaridad</th>
                            <th >Ocupación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($victima['nombre'])) ?></td>
                            <td><?php echo remove_junk(ucwords($victima['genero'])) ?></td>
                            <td><?php echo remove_junk(ucwords($victima['edad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($victima['comunidad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($victima['escolaridad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($victima['ocupacion'])) ?></td>
                            
                        </tr>
                    </tbody>
             
                    <thead class="thead-purple">                        
						<tr style="height: 10px;">
                            <th >Nacionalidad</th>
                            <th >Entidad</th>
                            <th >Municipio</th>
                            <th >¿Sabe leer y escribir?</th>
                            <th >¿Tiene alguna discapacidad?</th>
                            <th >Grupo Vulnerable</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($victima['nacionalidad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($victima['entidad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($victima['municipio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($victima['leer_escribir'])) ?></td>
                            <td><?php echo remove_junk(ucwords($victima['discapacidad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($victima['grupo_vulnerable'])) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
				<?php }else{ ?>
				<table class="table table-bordered table-striped">
                    <thead class="thead-purple">                        
						<tr style="height: 10px;">
                            <th >Nombre Coletivo </th>
                            <th >Entidad</th>
                            <th >Municipio</th>
                            <th >No.Personas Beneficiadas</th>                           
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($colectivo['nombre_colectivo'])) ?></td>
                            <td><?php echo remove_junk(ucwords($colectivo['entidad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($colectivo['municipio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($colectivo['no_beneficiarios'])) ?></td>
                            
                        </tr>
                    </tbody>
             
                    <thead class="thead-purple">                        
						<tr style="height: 10px;">
                            <th >Asistentes Hombres</th>
                            <th >Asistentes Mujeres</th>
                            <th >Asistentes No Binarios</th>
                            <th >Asistentes Asistentes Otros</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($colectivo['asistentes_hombres'])) ?></td>
                            <td><?php echo remove_junk(ucwords($colectivo['asistentes_mujeres'])) ?></td>
                            <td><?php echo remove_junk(ucwords($colectivo['asistentes_nobinario'])) ?></td>
                            <td><?php echo remove_junk(ucwords($colectivo['asistentes_otros'])) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
				<?php } ?>
				
				<br>
				
				<div class="panel-heading">
					<strong>
						<span class="glyphicon glyphicon-search"></span>
						<span>Datos de la Victima Directa</span>
					</strong>
				</div>
				<hr>
				
				<table class="table table-bordered table-striped">
                    <thead class="thead-purple">                        
						<tr style="height: 10px;">
                            <th >Nombre de la Desaparecido </th>
                            <th >Género</th>
                            <th >Edad</th>
                            <th >Comunidad</th>
                            <th >Escolaridad</th>
                            <th >Ocupación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($desaparecido['nombre'])) ?></td>
                            <td><?php echo remove_junk(ucwords($desaparecido['genero'])) ?></td>
                            <td><?php echo remove_junk(ucwords($desaparecido['edad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($desaparecido['comunidad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($desaparecido['escolaridad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($desaparecido['ocupacion'])) ?></td>
                            
                        </tr>
                    </tbody>
             
                    <thead class="thead-purple">                        
						<tr style="height: 10px;">
                            <th >Nacionalidad</th>
                            <th >Entidad</th>
                            <th >Municipio</th>
                            <th >¿Sabe leer y escribir?</th>
                            <th >¿Tiene alguna discapacidad?</th>
                            <th >Grupo Vulnerable</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($desaparecido['nacionalidad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($desaparecido['entidad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($desaparecido['municipio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($desaparecido['leer_escribir'])) ?></td>
                            <td><?php echo remove_junk(ucwords($desaparecido['discapacidad'])) ?></td>
                            <td><?php echo remove_junk(ucwords($desaparecido['grupo_vulnerable'])) ?></td>
                            
                        </tr>
                    </tbody>
                </table>
				
				
				<br>
				<br>
                <a href="actividades_ud.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>