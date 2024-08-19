<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Ficha Técnica - Área Psicológica';
error_reporting(E_ALL ^ E_NOTICE);
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];
$detalle = $user['id_user'];


if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 4) {
    page_require_level_exacto(4);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 9) {
    page_require_level_exacto(9);
}
if ($nivel_user == 22) {
    page_require_level_exacto(22);
}

if ($nivel_user > 3 && $nivel_user < 4) :
    redirect('home.php');
	
endif;
if ($nivel_user > 4 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 9) :
    redirect('home.php');
endif;
if ($nivel_user > 9 && $nivel_user < 22) :
    redirect('home.php');
endif;
if ($nivel_user > 22) :
    redirect('home.php');
endif;


$id_folio = last_id_folios();
$pacientes = find_all_expedientes();
$id_user = $user['id_user'];
$busca_area = area_usuario($id_user);
$areas = find_all_area_orden('area');
$funciones = find_all_funcion_P();
$ocupaciones = find_all_order('cat_ocupaciones', 'descripcion');
$escolaridades = find_all('cat_escolaridad');
$visitadurias = find_all_visitadurias();
$autoridades = find_all_aut_res();
$generos = find_all('cat_genero');
$grupos = find_all_order('cat_grupos_vuln', 'id_cat_grupo_vuln');
$derechos_vuln = find_all_order('cat_der_vuln', 'descripcion');
$folios = find_all('folios');
$inticadores_pat_ST = find_all_pat_area(16,'fichas');
$inticadores_pat_AM = find_all_pat_area(17,'fichas');

$nombre_usuario ="";
$edad_usuario = ""  ;
$genero_usuario = ""  ;
$ocupacion_usuario = ""  ;
$escolaridad_usuario = ""  ;
$grupo_usuario = ""  ;


// page_require_area(4);

if (isset($_GET['num_queja'])) {
    $opcion = $_GET['num_queja'];
    // echo $opcion;
    $prueba1 = find_by_id_paciente2($opcion);
	$nombre_usuario = $prueba1['nombre'] . " " . $prueba1['paterno'] . " " . $prueba1['materno'] ;
	$edad_usuario = $prueba1['edad']  ;
	$genero_usuario = $prueba1['genero']  ;
	$ocupacion_usuario = $prueba1['ocupacion']  ;
	$escolaridad_usuario = $prueba1['escolaridad']  ;
	$grupo_usuario = $prueba1['grupo_vulnerable']  ;
}
?>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_ficha_psic'])) {


    if (empty($errors)) {
        $funcion   = remove_junk($db->escape($_POST['funcion']));
        $id_paciente   = remove_junk($db->escape($_POST['nombre_usuario']));
        $area_solicitante   = remove_junk($db->escape($_POST['area_solicitante']));
        $fecha_intervencion   = remove_junk($db->escape($_POST['fecha_intervencion']));
        $resultado   = remove_junk($db->escape($_POST['resultado']));
        $documento_emitido   = remove_junk($db->escape($_POST['documento_emitido']));
        $nombre_especialista   = remove_junk($db->escape($_POST['nombre_especialista']));
        $clave_documento   = remove_junk($db->escape($_POST['clave_documento']));
        $protocolo_estambul   = remove_junk($db->escape($_POST['protocolo_estambul']));
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));
        date_default_timezone_set('America/Mexico_City');
        $creacion = date('Y-m-d');

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int)$nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int)$nuevo['contador'] + 1);
            }
        }
        // Se crea el número de folio
        $year = date("Y");
        // Se crea el folio de convenio
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-FT';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-FT';
        $carpeta = 'uploads/fichastecnicas/psic/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['adjunto']['name'];
        $size = $_FILES['adjunto']['size'];
        $type = $_FILES['adjunto']['type'];
        $temp = $_FILES['adjunto']['tmp_name'];

        $ocup = $prueba1['id_ocupacion'];

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
		/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}


        if ($move && $name != '') {
            $query = "INSERT INTO fichas (";
            $query .= "folio,id_cat_funcion,id_paciente,id_area_solicitante,fecha_intervencion,resultado,documento_emitido,ficha_adjunto,fecha_creacion,tipo_ficha,nombre_especialista,protocolo_estambul,clave_documento,id_indicadores_pat,user_creador";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$funcion}','{$id_paciente}','{$area_solicitante}','{$fecha_intervencion}','{$resultado}','{$documento_emitido}','{$name}','{$creacion}',2,'{$nombre_especialista}','{$protocolo_estambul}','{$clave_documento}','{$id_indicadores_pat}','{$id_user}'";
            $query .= ")";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";
        } else {
            $query = "INSERT INTO fichas (";
            $query .= "folio,id_cat_funcion,id_paciente,id_area_solicitante,fecha_intervencion,resultado,documento_emitido,fecha_creacion,tipo_ficha,nombre_especialista,protocolo_estambul,clave_documento,id_indicadores_pat,user_creador";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$funcion}','{$id_paciente}','{$area_solicitante}','{$fecha_intervencion}','{$resultado}','{$documento_emitido}','{$creacion}',2,'{$nombre_especialista}','{$protocolo_estambul}','{$clave_documento}','{$id_indicadores_pat}','{$id_user}'";
            $query .= ")";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";
        }
        if ($db->query($query) && $db->query($query2)) {
            //sucess
            $session->msg('s', " La ficha ha sido agregada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó Ficha Psic, Folio: ' . $folio . '.', 1);
            redirect('fichas_psic.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la ficha.');
            redirect('add_ficha_psic.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_ficha_psic.php', false);
    }
}
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#num_queja").change(function() {
            $("#num_queja option:selected").each(function() {
                no_expediente = $(this).val();
                $.post("get_paciente.php", {
                    no_expediente: no_expediente
                }, function(data) {
                    $("#id_paciente").html(data);
                });
            });
        })
		
		  $("#id_paciente").change(function() {
            $("#id_paciente option:selected").each(function() {
                id_paciente = $(this).val();
					window.location.href = 'add_ficha_psic.php?num_queja=' + $(this).val();
            });
        })
});
</script>

<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<?php

?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Ficha Técnica - Área Psicológica</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_ficha_psic.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="num_queja">No. Expediente</label>
                            <select class="form-control" name="num_queja" id="num_queja" readonly>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($pacientes as $paciente) : ?>
                                    <option value="<?php echo $paciente['folio_expediente']; ?>"><?php echo ucwords($paciente['folio']); ?></option>
                                    <?php if ($paciente['folio_expediente'] == $prueba1['folio_expediente']) : ?>
                                        <option style="visibility: hidden; font-size:1%;" value="<?php echo $paciente['folio_expediente']; ?>" selected><?php echo ucwords($paciente['folio']); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_paciente">Paciente</label>
                            <select class="form-control" name="id_paciente" id="id_paciente" >
                                <option value="">Escoge una opción</option>
                                
                            </select>
                        </div>
                    </div>					
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_intervencion">Fecha de Intervención</label>
                            <input type="date" class="form-control" name="fecha_intervencion" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="funcion">Función</label>
                            <select class="form-control" name="funcion" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($funciones as $funcion) : ?>
                                    <option value="<?php echo $funcion['id_cat_funcion']; ?>"><?php echo ucwords($funcion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
									
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="area_solicitante">Área Solicitante</label>
                            <select class="form-control" name="area_solicitante" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($areas as $area) : ?>
                                    <option value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					</div>	
								
                <div class="row">                   
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="resultado">Resultado</label>
                            <select class="form-control" name="resultado" required>
                                <option value="">Escoge una opción</option>
                                <option value="Positivo">Positivo</option>
                                <option value="Negativo">Negativo</option>
                                <option value="No Aplica">No Aplica</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="documento_emitido">Documento Emitido</label>
                            <select class="form-control" name="documento_emitido">
                                <option value="">Escoge una opción</option>
                                <option value="Dictamen Psicológico">Dictamen Psicológico</option>
                                <option value="Informe Psicológico">Informe Psicológico</option>
                                <option value="No Aplica">No Aplica</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="protocolo_estambul">Protocolo de Estambul</label>
                            <select class="form-control" name="protocolo_estambul">
                                <option value="">Escoge una opción</option>
                                <option value="Aplicado">Aplicado</option>
                                <option value="No Aplicado">No Aplicado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="clave_documento">Clave del documento</label>
                            <input type="text" class="form-control" name="clave_documento" placeholder="Clave de documento">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_usuario">Especialista que emite</label>
                            <input type="text" class="form-control" name="nombre_especialista" placeholder="Nombre Completo del especialista" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="adjunto">Adjuntar documento emitido</label>
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto">
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" >
                                <option value="">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat_ST as $datos) : ?>
                                    <option  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
								<?php foreach ($inticadores_pat_AM as $datos) : ?>
                                    <option  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                        </div>
                </div>
				
				
				<div class="panel-heading">
					<strong>
						<span class="glyphicon glyphicon-user"></span>
						<span>Datos del Paciente</span>
					</strong>
				</div><hr>
				
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_usuario">Nombre del usuario</label>
                            <input type="text" class="form-control" value="<?php echo $nombre_usuario;?>" readonly>
                            <input type="text" class="form-control" name="nombre_usuario" placeholder="Nombre Completo" value="<?php echo $prueba1['id_paciente']?>" hidden>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="edad">Edad</label>
                            <input type="number" class="form-control" min="1" max="120" name="edad" value="<?php echo $prueba1['edad'] ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sexo">Género</label>
                            <input type="text" class="form-control" value="<?php echo $genero_usuario; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ocupacion">Ocupación</label>
                            <input type="text" class="form-control" value="<?php echo $ocupacion_usuario; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="escolaridad">Escolaridad</label>
                            <input type="text" class="form-control" value="<?php echo $escolaridad_usuario; ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="grupo_vulnerable">Grupo Vulnerable</label>
                            <input type="text" class="form-control" value="<?php echo $grupo_usuario; ?>" readonly>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    
                </div>
                <div class="form-group clearfix">
                    <a href="fichas_psic.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_ficha_psic" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>