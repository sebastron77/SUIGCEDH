
<?php
$page_title = 'Agregar Actividad de Desaparecidos';
require_once('includes/load.php');

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

$cat_actividades = find_all('cat_tipo_actividades_ud');
$cat_entidades = find_all('cat_entidad_fed');
$generos = find_all('cat_genero');
$nacionalidades = find_all('cat_nacionalidades');
$municipios = find_all('cat_municipios');
$escolaridades = find_all('cat_escolaridad');
$ocupaciones = find_all('cat_ocupaciones');
$grupos_vuln = find_all('cat_grupos_vuln');
$discapacidades = find_all('cat_discapacidades');
$comunidades = find_all('cat_comunidades');
$id_folio = last_id_folios();
$inticadores_pat = find_all_pat_area(12,'actividades_ud');

?>

<?php
if (isset($_POST['add_actividad'])) {	
	$colaboracion = array();
	
    if (empty($errors)) {
		
        $id_cat_tipo_actividades_ud = remove_junk($db->escape($_POST['id_cat_tipo_actividades_ud']));
		$fecha_actividad   = remove_junk($db->escape($_POST['fecha_actividad']));
        $nombre_solicitante = remove_junk($db->escape($_POST['nombre_solicitante']));
        $motivo_solicitud = remove_junk($db->escape($_POST['motivo_solicitud']));
        $no_expediente = remove_junk($db->escape($_POST['no_expediente']));
        $no_atendidos = remove_junk($db->escape($_POST['no_atendidos']));
        $acciones = remove_junk($db->escape($_POST['acciones']));
        $observaciones = remove_junk($db->escape($_POST['actividades_ud']));
        $quien_atendio = remove_junk($db->escape($_POST['quien_atendio']));
		$institucion_colaboracion = remove_junk($db->escape($_POST['institucion_colaboracion']));
        $motivo_colaboracion = remove_junk($db->escape($_POST['motivo_colaboracion']));
        $id_cat_ent_fed_colaboracion = remove_junk($db->escape($_POST['id_cat_ent_fed_colaboracion']));
        $id_indicadores_pat = remove_junk($db->escape($_POST['id_indicadores_pat']));
		$persona = isset($_POST['persona']) ? 1 : 0;
		
		if($persona){
		//Datos de la Victima atendida-persona
        $nombre = remove_junk($db->escape($_POST['nombre']));
        $paterno = remove_junk($db->escape($_POST['paterno']));
        $materno = remove_junk($db->escape($_POST['materno']));
		$id_cat_gen = remove_junk($db->escape($_POST['id_cat_gen']));
        $edad = remove_junk($db->escape($_POST['edadQ']));
        $id_cat_nacionalidad = remove_junk($db->escape($_POST['id_cat_nacionalidad']));
        $id_cat_escolaridad = remove_junk($db->escape($_POST['id_cat_escolaridad']));
        $id_cat_ent_fed = remove_junk($db->escape($_POST['id_cat_ent_fed']));
        $id_cat_mun = remove_junk($db->escape($_POST['id_cat_mun']));
        $id_cat_escolaridad = remove_junk($db->escape($_POST['id_cat_escolaridad']));
        $id_cat_ocup = remove_junk($db->escape($_POST['id_cat_ocup']));
        $leer_escribir = remove_junk($db->escape($_POST['leer_escribir']));
        $id_cat_grupo_vuln = remove_junk($db->escape($_POST['id_cat_grupo_vuln']));
        $id_cat_disc = remove_junk($db->escape($_POST['id_cat_disc']));
        $id_cat_comun = remove_junk($db->escape($_POST['id_cat_comun']));
	}else{
		//Datos de la Victima atendida-colectivo
        $nombreCol = remove_junk($db->escape($_POST['nombreCol']));
        $id_cat_ent_fedCol = remove_junk($db->escape($_POST['id_cat_ent_fedCol']));
        $id_cat_munCol = remove_junk($db->escape($_POST['id_cat_munCol']));
		$beneficiadasCol = remove_junk($db->escape($_POST['beneficiadasCol']));
        $asistentes_hombres = remove_junk($db->escape($_POST['asistentes_hombres']));
        $asistentes_mujeres = remove_junk($db->escape($_POST['asistentes_mujeres']));
        $asistentes_nobinario = remove_junk($db->escape($_POST['asistentes_nobinario']));
        $asistentes_otros = remove_junk($db->escape($_POST['asistentes_otros']));
	}
	
		
		//Datos de la Persona Desaparecida
        $nombre_PD = remove_junk($db->escape($_POST['nombrePD']));
        $paterno_PD = remove_junk($db->escape($_POST['paternoPD']));
        $materno_PD = remove_junk($db->escape($_POST['maternoPD']));
		$id_cat_gen_PD = remove_junk($db->escape($_POST['id_cat_genPD']));
        $edad_PD = remove_junk($db->escape($_POST['edadPD']));
        $id_cat_nacionalidad_PD = remove_junk($db->escape($_POST['id_cat_nacionalidadPD']));
        $id_cat_escolaridad_PD = remove_junk($db->escape($_POST['id_cat_escolaridadPD']));       
        $id_cat_ent_fed_PD = remove_junk($db->escape($_POST['id_cat_ent_fedPD']));
        $id_cat_mun_PD = remove_junk($db->escape($_POST['id_cat_munPD']));
        $id_cat_escolaridad_PD = remove_junk($db->escape($_POST['id_cat_escolaridadPD']));
        $id_cat_ocup_PD = remove_junk($db->escape($_POST['id_cat_ocupPD']));
        $leer_escribir_PD = remove_junk($db->escape($_POST['leer_escribirPD']));
        $id_cat_grupo_vuln_PD = remove_junk($db->escape($_POST['id_cat_grupo_vulnPD']));
        $id_cat_disc_PD = remove_junk($db->escape($_POST['id_cat_discPD']));
        $id_cat_comun_PD = remove_junk($db->escape($_POST['id_cat_comunPD']));
        $fecha_desaparicion = remove_junk($db->escape($_POST['fecha_desparicion']));
        $id_cat_ent_fed_desaparicion = remove_junk($db->escape($_POST['id_cat_ent_fed_desaparicion']));
        $id_cat_mun_desaparicion = remove_junk($db->escape($_POST['id_cat_mun_desaparicion']));
        $localidad_desaparicion = remove_junk($db->escape($_POST['localidad_desaparicion']));
		
		
		
        
		//Suma el valor del id anterior + 1, para generar ese id para el nuevo resguardo
        //La variable $no_folio sirve para el numero de folio

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int)$nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int)$nuevo['contador'] + 1);
            }
        }
		//Se crea el número de folio
        $year = date("Y");
        // Se crea el folio orientacion
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-ACTD';

        		
		$dbh1 = new PDO('mysql:host=localhost;dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');	
		$id_cat_victima_atendida=null;
		$id_colectivos_atendidos=null;
		if($persona){
			// Inserto los datos de la victima atendida	-persona	
			$query = "INSERT INTO cat_victima_atendida (";
			$query .= "nombre, paterno, materno, id_cat_gen, edad, id_cat_nacionalidad, id_cat_ent_fed, id_cat_mun,id_cat_escolaridad, id_cat_ocup, leer_escribir,id_cat_grupo_vuln,id_cat_disc,id_cat_comun)  VALUES (";
			$query .= " '{$nombre}','{$paterno}','{$materno}','{$id_cat_gen}',{$edad},'{$id_cat_nacionalidad}','{$id_cat_ent_fed}','{$id_cat_mun}','{$id_cat_escolaridad}','{$id_cat_ocup}','{$leer_escribir}',
						'{$id_cat_grupo_vuln}','{$id_cat_disc}', '{$id_cat_comun}'); ";
			
			if ($dbh1->query($query)){
				$id_cat_victima_atendida = $dbh1->lastInsertId();
				insertAccion($user['id_user'], '"'.$user['username'].'" agregó una Victima Atendida-Persona con Nombre '.$nombre.' '.$materno.' '.$paterno.'.', 1);
			}
		}else{
							// Inserto los datos de la victima atendida	-colectivo	
			$query = "INSERT INTO colectivos_atendidos (";
			$query .= "nombre_colectivo, id_cat_ent_fed, id_cat_mun, no_beneficiarios, asistentes_hombres, asistentes_mujeres, asistentes_nobinario, asistentes_otros)  VALUES (";
			$query .= " '{$nombreCol}','{$id_cat_ent_fedCol}','{$id_cat_munCol}','{$beneficiadasCol}',{$asistentes_hombres},'{$asistentes_mujeres}','{$asistentes_nobinario}','{$asistentes_otros}'); ";
			
			if ($dbh1->query($query)){
				$id_colectivos_atendidos = $dbh1->lastInsertId();
				insertAccion($user['id_user'], '"'.$user['username'].'" agregó una Victima Atendida-Colectivo con Nombre '.$nombreCol.'.', 1);
			}

			
		}
		// Inserto los datos de la persona desaparecida		
		$query = "INSERT INTO cat_persona_desaparecida (";
		$query .= "nombre, paterno, materno, id_cat_gen, edad, id_cat_nacionalidad, id_cat_ent_fed, id_cat_mun,id_cat_escolaridad, id_cat_ocup, leer_escribir,id_cat_grupo_vuln,
					id_cat_disc,id_cat_comun,fecha_desaparicion,id_cat_ent_fed_desaparicion,id_cat_mun_desaparicion,localidad_desaparicion)  VALUES (";
		$query .= " '{$nombre_PD}','{$paterno_PD}','{$materno_PD}','{$id_cat_gen_PD}',{$edad_PD},'{$id_cat_nacionalidad_PD}','{$id_cat_ent_fed_PD}','{$id_cat_mun_PD}','{$id_cat_escolaridad_PD}','{$id_cat_ocup_PD}','{$leer_escribir_PD}',
					'{$id_cat_grupo_vuln_PD}','{$id_cat_disc_PD}', '{$id_cat_comun_PD}','{$fecha_desaparicion}','{$id_cat_ent_fed_desaparicion}','{$id_cat_mun_desaparicion}','{$localidad_desaparicion}'); ";
						
		if ($dbh1->query($query)){
			$id_cat_persona_desaparecida = $dbh1->lastInsertId();
			insertAccion($user['id_user'], '"'.$user['username'].'" agregó una Persona desaparecida con Nombre '.$nombre_PD.' '.$paterno_PD.' '.$materno_PD.'.', 1);
		}
			
		if( $id_cat_persona_desaparecida > 0){
			$id_cat_victima_atendida=($id_cat_victima_atendida==''?'null':$id_cat_victima_atendida);
			$id_colectivos_atendidos=($id_colectivos_atendidos==''?'null':$id_colectivos_atendidos);
			$query="INSERT INTO actividades_ud (folio, id_cat_tipo_actividades_ud, fecha_actividad, id_colectivos_atendidos,id_cat_victima_atendida, id_cat_persona_desaparecida,nombre_solicitante, 
					no_expediente, motivo_solicitud, no_atendidos,acciones,observaciones,quien_atendio,user_creador,fecha_creacion,institucion_colaboracion,motivo_colaboracion,id_cat_ent_fed_colaboracion,id_indicadores_pat) VALUES(
					'{$folio}','{$id_cat_tipo_actividades_ud}','{$fecha_actividad}',{$id_colectivos_atendidos},{$id_cat_victima_atendida},{$id_cat_persona_desaparecida},'{$nombre_solicitante}',
					'{$no_expediente}','{$motivo_solicitud}',{$no_atendidos},'{$acciones}','{$observaciones}','{$quien_atendio}',{$id_user},NOW(),'{$institucion_colaboracion}','{$motivo_colaboracion}','{$id_cat_ent_fed_colaboracion}',{$id_indicadores_pat});";
			
			$query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";
			echo $query;
			if ($db->query($query) && $db->query($query2)) {			
				insertAccion($user['id_user'], '"'.$user['username'].'" agregó una Actividad de Desaparecidos, Folio: '.$folio.'.', 1);
				
				$session->msg('s', " Los datos de acompañamiento se han sido agregado con éxito.  ");
				redirect('actividades_ud.php', false);
			} else {
				//faile
				$session->msg('d', ' No se pudieron agregar los datos de acompañamiento.');
				redirect('add_actividad_ud.php', false);
			}
		}
		 
		
		
    } else {
        $session->msg("d", $errors);
        redirect('add_actividad_ud.php', false);
    }
}
?>
<script>

function showMe(it, box) {
var name_div1="victima_persona";
var name_div2="victima_colectivo";
	
if($('#persona').is(":checked")){	
//	alert("Persona");
	$("#"+name_div1).show();
	$("#"+name_div2).hide();
	
	document.querySelector('#nombre').required = true;
	document.querySelector('#paterno').required = true;
	document.querySelector('#materno').required = true;
	document.querySelector('#id_cat_gen').required = true;
	document.querySelector('#edad').required = true;
	document.querySelector('#id_cat_comun').required = true;
	document.querySelector('#id_cat_nacionalidad').required = true;
	document.querySelector('#id_cat_ent_fed').required = true;
	document.querySelector('#id_cat_mun').required = true;
	document.querySelector('#id_cat_escolaridad').required = true;
	document.querySelector('#id_cat_ocup').required = true;
	document.querySelector('#leer_escribir').required = true;
	document.querySelector('#id_cat_disc').required = true;
	document.querySelector('#id_cat_grupo_vuln').required = true;
	
	document.querySelector('#nombreCol').required = false;
	document.querySelector('#id_cat_ent_fedCol').required = false;
	document.querySelector('#id_cat_munCol').required = false;
	document.querySelector('#beneficiadasCol').required = false;
	document.querySelector('#asistentes_hombres').required = false;
	document.querySelector('#asistentes_mujeres').required = false;
	document.querySelector('#asistentes_nobinario').required = false;
	document.querySelector('#asistentes_otros').required = false;
	
	}else{		 
		//alert("Colectivo");
		$("#"+name_div2).show();
		$("#"+name_div1).hide();
		
		document.querySelector('#nombreCol').required = true;
		document.querySelector('#id_cat_ent_fedCol').required = true;
		document.querySelector('#id_cat_munCol').required = true;
		document.querySelector('#beneficiadasCol').required = true;
		document.querySelector('#asistentes_hombres').required = true;
		document.querySelector('#asistentes_mujeres').required = true;
		document.querySelector('#asistentes_nobinario').required = true;
		document.querySelector('#asistentes_otros').required = true;
		
		document.querySelector('#nombre').required = false;
		document.querySelector('#paterno').required = false;
		document.querySelector('#materno').required = false;
		document.querySelector('#id_cat_gen').required = false;
		document.querySelector('#edad').required = false;
		document.querySelector('#id_cat_comun').required = false;
		document.querySelector('#id_cat_nacionalidad').required = false;
		document.querySelector('#id_cat_ent_fed').required = false;
		document.querySelector('#id_cat_mun').required = false;
		document.querySelector('#id_cat_escolaridad').required = false;
		document.querySelector('#id_cat_ocup').required = false;
		document.querySelector('#leer_escribir').required = false;
		document.querySelector('#id_cat_disc').required = false;
		document.querySelector('#id_cat_grupo_vuln').required = false;
	}
       
	}
</script>	
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Actividad</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_actividad_ud.php" class="clearfix" enctype="multipart/form-data">
			
                <div class="row">
				
					<div class="col-md-2">
						<div class="form-group">
							<label for="id_cat_tipo_actividades_ud">Tipo Actividad</label>
							<select class="form-control"  name="id_cat_tipo_actividades_ud"  required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_actividades as $datos) : ?>
                                    <option  value="<?php echo $datos['id_cat_tipo_actividades_ud']; ?>" ><?php echo ucwords($datos['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
						</div>
					</div>	
					<div class="col-md-2">
						<div class="form-group">
							<label for="fecha_actividad">Fecha de la Actividad</label>
							<input type="date" class="form-control" name="fecha_actividad" required>
						</div>
					</div>					
					<div class="col-md-3">
						<div class="form-group">
							<label for="nombre_solicitante">Quien lo solicita</label>
							<input type="text" class="form-control" name="nombre_solicitante" required>
						</div>
					</div>					
					<div class="col-md-2">
						<div class="form-group">
							<label for="no_expediente">NUC ó No. Expediente</label>
							<input type="text" class="form-control" name="no_expediente" >
						</div>
					</div>	
					<div class="col-md-3">
						<div class="form-group">
							<label for="motivo_solicitud">Motivo ó Solicitud</label>
							<input type="text" class="form-control" name="motivo_solicitud" >
						</div>
					</div>				
				
				</div>
				
				<div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label for="no_atendidos">No.Personas Atendidas</label>
							<input type="number" class="form-control" min="1" max="1300" maxlength="4" name="no_atendidos" required >
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="quien_atendio">¿Quién Atendió?</label>
							<input type="text" class="form-control" name="quien_atendio" required>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="acciones">Acciones</label>
							<textarea class="form-control" name="acciones" cols="8" rows="4"></textarea>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="observaciones">Observaciones</label>
							<textarea class="form-control" name="actividades_ud" cols="8" rows="4"></textarea>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat">
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="id_cat_ent_fed_colaboracion">En caso de  Aplicar, Entidad Federativa con la que se realiza la Colaboracion</label>
							<select class="form-control"  name="id_cat_ent_fed_colaboracion" id="id_cat_ent_fed_colaboracion"  >
									<option value="0">Escoge una opción</option>
									<?php foreach ($cat_entidades as $id_cat_ent_fed) : ?>
										<option  value="<?php echo $id_cat_ent_fed['id_cat_ent_fed']; ?>" ><?php echo ucwords($id_cat_ent_fed['descripcion']); ?></option>
									<?php endforeach; ?>
								</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="motivo_colaboracion">En caso de  Aplicar, Motivo de la Colaboración</label>
							<textarea class="form-control" name="motivo_colaboracion" cols="5" rows="3"></textarea>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="institucion_colaboracion">En caso de  Aplicar, Intituciones con la que se realiza la Colaboracion</label>
							<textarea class="form-control" name="institucion_colaboracion" cols="5" rows="3"></textarea>
						</div>
					</div>
				</div>
				
				<div class="panel-heading">
					<strong>
						<span class="glyphicon glyphicon-user"></span>
						<span>Datos de la Victima Indirecta</span>
					</strong>
				</div><hr>
				
						<div class="row" >
				 <div class="col-md-2">
                        <div class="form-group">
                            <label for="persona">¿Se trata de una Persona?</label><br>
                            <label class="switch" style="float:left;">
                                <div class="row">
                                    <input type="checkbox" id="persona" name="persona" onclick="showMe('victima_colectivo', this)" checked>
                                    <span class="slider round"></span>
                                    <div>
                                        <p style="margin-left: 150%; margin-top: -3%; font-size: 14px;">No/Sí</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    </div>
					<br>
					<div id="victima_persona" style="display:'';">
						<div class="row" >
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre(s)" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="paterno">Apellido Paterno</label>
                            <input type="text" class="form-control" name="paterno" id="paterno" placeholder="Apellido Paterno" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="materno">Apellido Materno</label>
                            <input type="text" class="form-control" name="materno" id="materno" placeholder="Apellido Materno" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_gen">Género</label>
                            <select class="form-control" name="id_cat_gen" id="id_cat_gen" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($generos as $genero) : ?>
                                    <option value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="edad">Edad</label>
                            <input type="number" class="form-control" min="1" max="130" maxlength="4" name="edad" id="edad" required>
                        </div>
                    </div>
                     <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_comun">Comunidad</label>
                            <select class="form-control" name="id_cat_comun" id="id_cat_comun" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($comunidades as $comunidad) : ?>
                                    <option value="<?php echo $comunidad['id_cat_comun']; ?>"><?php echo ucwords($comunidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_nacionalidad">Nacionalidad</label>
                            <select class="form-control" name="id_cat_nacionalidad" id="id_cat_nacionalidad" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($nacionalidades as $nacionalidad) : ?>
                                    <option value="<?php echo $nacionalidad['id_cat_nacionalidad']; ?>"><?php echo ucwords($nacionalidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-2">
						<div class="form-group">
								<label for="id_cat_ent_fed">Entidad</label>
							<select class="form-control"  name="id_cat_ent_fed" id="id_cat_ent_fed" required >
									<option value="">Escoge una opción</option>
									<?php foreach ($cat_entidades as $id_cat_ent_fed) : ?>
										<option value="<?php echo $id_cat_ent_fed['id_cat_ent_fed']; ?>" ><?php echo ucwords($id_cat_ent_fed['descripcion']); ?></option>
									<?php endforeach; ?>
								</select>
						</div>
					</div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_mun">Municipio</label>
                            <select class="form-control" name="id_cat_mun" id="id_cat_mun" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($municipios as $municipio) : ?>
                                    <option value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_escolaridad">Escolaridad</label>
                            <select class="form-control" name="id_cat_escolaridad" id="id_cat_escolaridad" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($escolaridades as $escolaridad) : ?>
                                    <option value="<?php echo $escolaridad['id_cat_escolaridad']; ?>"><?php echo ucwords($escolaridad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_ocup">Ocupación</label>
                            <select class="form-control" name="id_cat_ocup" id="id_cat_ocup" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($ocupaciones as $ocupacion) : ?>
                                    <option value="<?php echo $ocupacion['id_cat_ocup']; ?>"><?php echo ucwords($ocupacion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="leer_escribir">¿Sabe leer y escribir?</label>
                            <select class="form-control" name="leer_escribir" id="leer_escribir" required>
                                <option value="">Escoge una opción</option>
                                <option value="Leer">Leer</option>
                                <option value="Escribir">Escribir</option>
                                <option value="Ambos">Ambos</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_disc">¿Tiene alguna discapacidad?</label>
                            <select class="form-control" name="id_cat_disc" id="id_cat_disc" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($discapacidades as $discapacidad) : ?>
                                    <option value="<?php echo $discapacidad['id_cat_disc']; ?>"><?php echo ucwords($discapacidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_grupo_vuln">Grupo Vulnerable</label>
                            <select class="form-control" name="id_cat_grupo_vuln" id="id_cat_grupo_vuln" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($grupos_vuln as $grupo_vuln) : ?>
                                    <option value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>                 
                  
                </div>
                    </div>
					<div id="victima_colectivo" style="display:none;">
					<div class="row" >
							<div class="col-md-3">
								<div class="form-group">
									<label for="nombreCol">Nombre Colectivo</label>
									<input type="text" class="form-control" name="nombreCol" id="nombreCol" placeholder="Nombre Colectivo" required>
								</div>
							</div>
							<div class="col-md-3">
						<div class="form-group">
								<label for="id_cat_ent_fedCol">Entidad</label>
							<select class="form-control"  name="id_cat_ent_fedCol" id="id_cat_ent_fedCol" required >
									<option value="">Escoge una opción</option>
									<?php foreach ($cat_entidades as $id_cat_ent_fed) : ?>
										<option value="<?php echo $id_cat_ent_fed['id_cat_ent_fed']; ?>" ><?php echo ucwords($id_cat_ent_fed['descripcion']); ?></option>
									<?php endforeach; ?>
								</select>
						</div>
					</div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_munCol">Municipio</label>
                            <select class="form-control" name="id_cat_munCol" id="id_cat_munCol" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($municipios as $municipio) : ?>
                                    <option value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="beneficiadasCol">No.Personas Beneficiadas</label>
                            <input type="number" class="form-control" min="1" max="130" maxlength="4" name="beneficiadasCol" id="beneficiadasCol" value="0" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">Asistentes Hombres</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_hombres" id="asistentes_hombres" value="0" >
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">Asistentes Mujeres</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_mujeres" id="asistentes_mujeres" value="0" >
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">Asistentes No Binarios</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_nobinario" id="asistentes_nobinario" value="0" >
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">Asistentes Otros</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_otros" id="asistentes_otros" value="0" >
                        </div>
                    </div>
						</div>
                    </div>
				<br><br>				
				<div class="panel-heading">
					<strong>
						<span class="glyphicon glyphicon-search"></span>
						<span>Datos de Victima Directa</span>
					</strong>
				</div>
				<hr>
				<div class="row" >
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="nombrePD">Nombre</label>
                            <input type="text" class="form-control" name="nombrePD" placeholder="Nombre(s)" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="paternoPD">Apellido Paterno</label>
                            <input type="text" class="form-control" name="paternoPD" placeholder="Apellido Paterno" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="maternoPD">Apellido Materno</label>
                            <input type="text" class="form-control" name="maternoPD" placeholder="Apellido Materno" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_genPD">Género</label>
                            <select class="form-control" name="id_cat_genPD" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($generos as $genero) : ?>
                                    <option value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="edadPD">Edad</label>
                            <input type="number" class="form-control" min="1" max="130" maxlength="4" name="edadPD" required>
                        </div>
                    </div>
                     <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_comunPD">Comunidad</label>
                            <select class="form-control" name="id_cat_comunPD" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($comunidades as $comunidad) : ?>
                                    <option value="<?php echo $comunidad['id_cat_comun']; ?>"><?php echo ucwords($comunidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_nacionalidadPD">Nacionalidad</label>
                            <select class="form-control" name="id_cat_nacionalidadPD" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($nacionalidades as $nacionalidad) : ?>
                                    <option value="<?php echo $nacionalidad['id_cat_nacionalidad']; ?>"><?php echo ucwords($nacionalidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-2">
						<div class="form-group">
								<label for="id_cat_ent_fedPD">Entidad</label>
							<select class="form-control"  name="id_cat_ent_fedPD" id="id_cat_ent_fedPD" required >
									<option value="">Escoge una opción</option>
									<?php foreach ($cat_entidades as $id_cat_ent_fed) : ?>
										<option value="<?php echo $id_cat_ent_fed['id_cat_ent_fed']; ?>" ><?php echo ucwords($id_cat_ent_fed['descripcion']); ?></option>
									<?php endforeach; ?>
								</select>
						</div>
					</div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_munPD">Municipio</label>
                            <select class="form-control" name="id_cat_munPD" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($municipios as $municipio) : ?>
                                    <option value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_escolaridadPD">Escolaridad</label>
                            <select class="form-control" name="id_cat_escolaridadPD" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($escolaridades as $escolaridad) : ?>
                                    <option value="<?php echo $escolaridad['id_cat_escolaridad']; ?>"><?php echo ucwords($escolaridad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_ocupPD">Ocupación</label>
                            <select class="form-control" name="id_cat_ocupPD" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($ocupaciones as $ocupacion) : ?>
                                    <option value="<?php echo $ocupacion['id_cat_ocup']; ?>"><?php echo ucwords($ocupacion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="leer_escribirPD">¿Sabe leer y escribir?</label>
                            <select class="form-control" name="leer_escribirPD" required>
                                <option value="">Escoge una opción</option>
                                <option value="Leer">Leer</option>
                                <option value="Escribir">Escribir</option>
                                <option value="Ambos">Ambos</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_discPD">¿Tiene alguna discapacidad?</label>
                            <select class="form-control" name="id_cat_discPD" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($discapacidades as $discapacidad) : ?>
                                    <option value="<?php echo $discapacidad['id_cat_disc']; ?>"><?php echo ucwords($discapacidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_grupo_vulnPD">Grupo Vulnerable</label>
                            <select class="form-control" name="id_cat_grupo_vulnPD" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($grupos_vuln as $grupo_vuln) : ?>
                                    <option value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>                 
                  
                </div>
				
				<div class="panel-heading">
					<strong>
						<span class="glyphicon glyphicon-globe"></span>
						<span>Datos de Lugar de Desaparición</span>
					</strong>
				</div>
			
				<div class="row">
					<div class="col-md-3">
					<div class="form-group">
                            <label for="fecha_entrega_informe">Fecha de desaparicion</label>
                            <input type="date" class="form-control" name="fecha_desparicion" required>
                        </div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
                            <label for="id_cat_ent_fed_desaparicion">Entidad de Desaparición</label>
						<select class="form-control"  name="id_cat_ent_fed_desaparicion" id="id_cat_ent_fed_desaparicion" required >
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_entidades as $id_cat_ent_fed) : ?>
                                    <option value="<?php echo $id_cat_ent_fed['id_cat_ent_fed']; ?>" ><?php echo ucwords($id_cat_ent_fed['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
                            <label for="id_cat_mun_desaparicion">Muicipio de Desaparición</label>
						 <select class="form-control" name="id_cat_mun_desaparicion" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($municipios as $municipio) : ?>
                                    <option value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
                            <label for="localidad_desaparicion">Localidad de Desaparición</label>
						<input type="text" class="form-control" name="localidad_desaparicion" >
					</div>
			
		</div>
		<br><br>		
		<br><br>		
				<div class="form-group clearfix">
                    <a href="actividades_ud.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_actividad" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
	
		
<?php include_once('layouts/footer.php'); ?>