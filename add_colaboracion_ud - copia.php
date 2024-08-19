<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
$page_title = 'Agregar Colaboracion';
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

?>

<?php
if (isset($_POST['add_colaboracion'])) {	
	$colaboracion = array();
	
    if (empty($errors)) {
		
        $solicitante = remove_junk($db->escape($_POST['solicitante']));
		$oficio_adjunto   = remove_junk($db->escape($_POST['oficio_adjunto']));
        $desaparecido_nombre = remove_junk($db->escape($_POST['desaparecido_nombre']));
        $desaparecido_paterno = remove_junk($db->escape($_POST['desaparecido_paterno']));
        $desaparecido_materno = remove_junk($db->escape($_POST['desaparecido_materno']));
        $id_cat_ent_fed = remove_junk($db->escape($_POST['id_cat_ent_fed']));
        $municipio = remove_junk($db->escape($_POST['municipio']));
        $localidad = remove_junk($db->escape($_POST['localidad']));
        $fecha_desparicion = remove_junk($db->escape($_POST['fecha_desparicion']));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
		
		$id_cat_gen = remove_junk($db->escape($_POST['id_cat_gen']));
        $edad = remove_junk($db->escape($_POST['edad']));
        $id_cat_nacionalidad = remove_junk($db->escape($_POST['id_cat_nacionalidad']));
        $id_cat_escolaridad = remove_junk($db->escape($_POST['id_cat_escolaridad']));
        $id_cat_ocup = remove_junk($db->escape($_POST['id_cat_ocup']));
        $leer_escribir = remove_junk($db->escape($_POST['leer_escribir']));
        $id_cat_disc = remove_junk($db->escape($_POST['id_cat_disc']));
        $id_cat_grupo_vuln = remove_junk($db->escape($_POST['id_cat_grupo_vuln']));
        $id_cat_comun = remove_junk($db->escape($_POST['id_cat_comun']));
        $id_cat_ent_fed_origen = remove_junk($db->escape($_POST['id_cat_ent_fed_origen']));
        $id_cat_mun_origen = remove_junk($db->escape($_POST['id_cat_mun_origen']));
        $motivo_colaboracion = remove_junk($db->escape($_POST['motivo_colaboracion']));
        
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
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-COL';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-COL';
        $carpeta = 'uploads/colaboraciones/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['oficio_adjunto']['name'];
        $size = $_FILES['oficio_adjunto']['size'];
        $type = $_FILES['oficio_adjunto']['type'];
        $temp = $_FILES['oficio_adjunto']['tmp_name'];

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
		
//se obtienen los nom,bre de archivos 		
		foreach($_FILES["adjunto"]['name'] as $key => $tmp_name)
		{
			//condicional si el fuchero existe
			if($_FILES["adjunto"]["name"][$key]) {
				// Nombres de archivos de temporales
				$archivonombre = $_FILES["adjunto"]["name"][$key]; 
				$fuente = $_FILES["adjunto"]["tmp_name"][$key]; 
				array_push($colaboracion,$archivonombre);					
				
				if(!file_exists($carpeta)){
					mkdir($carpeta, 0777) or die("Hubo un error al crear el directorio de almacenamiento");	
				}
				
				$dir=opendir($carpeta);
				$target_path = $carpeta.'/'.$archivonombre; //indicamos la ruta de destino de los archivos
				
		
				if(move_uploaded_file($fuente, $target_path)) {	
					//echo "Los archivos $archivonombre se han cargado de forma correcta.<br>";
					} else {	
					//echo "Se ha producido un error, por favor revise los archivos e intentelo de nuevo.<br>";
				}
				closedir($dir); //Cerramos la conexion con la carpeta destino
			}
		}
		/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}


		
		$dbh1 = new PDO('mysql:host=localhost;dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');
		
		$query = "INSERT INTO colaboraciones (";
		$query .= "folio, solicitante, oficio_solicitud, desaparecido_nombre, desaparecido_paterno, desaparecido_materno, fecha_desparicion, id_cat_ent_fed,municipio, localidad, observaciones, ";
		$query .= " id_cat_gen,edad,id_cat_nacionalidad,id_cat_escolaridad,id_cat_ocup,leer_escribir,id_cat_disc,id_cat_grupo_vuln,id_cat_comun,motivo_colaboracion,id_cat_ent_fed_origen,id_cat_mun_origen,user_creador, fecha_creacion) VALUES (";
		$query .= " '{$folio}','{$solicitante}','{$name}','{$desaparecido_nombre}','{$desaparecido_paterno}','{$desaparecido_materno}','{$fecha_desparicion}','{$id_cat_ent_fed}','{$municipio}','{$localidad}','{$observaciones}',
		'{$id_cat_gen}',{$edad}, '{$id_cat_nacionalidad}', '{$id_cat_escolaridad}', '{$id_cat_ocup}', '{$leer_escribir}', '{$id_cat_disc}', '{$id_cat_grupo_vuln}', '{$id_cat_comun}',
		'{$motivo_colaboracion}', '{$id_cat_ent_fed_origen}', '{$id_cat_mun_origen}', '$id_user',NOW()";
		$query .= ")";
		
		$query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";
		
		 if ($dbh1->query($query) && $db->query($query2)) {
            //sucess
			$id_colaboraciones = $dbh1->lastInsertId();
			insertAccion($user['id_user'], '"'.$user['username'].'" agregó una Colaboración, Folio: '.$folio.'.', 1);
			
				
			$dependencia = $_POST['dependencia'];				

			for ($i = 0; $i < sizeof($colaboracion); $i = $i + 1) {		
				$queryInsert = "INSERT INTO rel_colaboracion_oficios (id_colaboraciones,autoridad,documento_oficio,tipo_documento) VALUES('$id_colaboraciones','$dependencia[$i]','$colaboracion[$i]','env')";
				if ($db->query($queryInsert)) {
						//echo 'insertado';
						insertAccion($user['id_user'], '"'.$user['username'].'" agregó el oficio de colaboracion para '.$dependencia[$i].', del Folio: '.$folio.'.', 1);
				} else {
					//echo 'falla';
				}
			}
			 
            $session->msg('s', " Los datos de acompañamiento se han sido agregado con éxito.");
            redirect('colaboraciones_ud.php', false);
        } else {
            //faile
            $session->msg('d', ' No se pudieron agregar los datos de acompañamiento.');
            redirect('add_colaboracion_ud.php', false);
        }
		
		
    } else {
        $session->msg("d", $errors);
        redirect('add_colaboracion_ud.php', false);
    }
}
?>
<script type="text/javascript">	
		
	$(document).ready(function() {
		
		
		$("#addRow").click(function() {	
			var html = '';
				html += '<div id="inputFormRow">';
				html += '	<div class="col-md-4">';
				html += '		<input class="form-control" style="font-size:15px;" name="dependencia[]" type="text">';
				html += '	</div>';
				html += '	<div class="col-md-4">';
				html += '		<input type="file" accept="application/pdf" class="form-control" name="adjunto[]" >';
				html += '	</div>';
				html += '	<div class="col-md-2">';
				html += '	<button type="button" class="btn btn-outline-danger" id="removeRow" > ';
				html += '   	<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">';
				html += '			<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>';
				html += '			<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>';
				html += '		</svg>';
				html += '  	</button>';			
				html += '	</div> <br><br>';
				html += '</div> ';

				$('#newRow').append(html);
		});
		
		
		$(document).on('click', '#removeRow', function() {
				$(this).closest('#inputFormRow').remove();
			});
	
	});
	
	
</script>
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Colaboración</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_colaboracion_ud.php" class="clearfix" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
					<div class="form-group">
                            <label for="no_informe">Quien lo solicita</label>
						<input type="text" class="form-control" name="solicitante" required>
					</div>
				</div>
			
				<div class="col-md-4">
					<div class="form-group">
						<label for="adjunto">Oficio Solicitud</label>
						<input type="file" accept="application/pdf" class="form-control" name="oficio_adjunto" id="oficio_adjunto" required>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="motivo_colaboracion">Motivo Colaboracion</label>
						<input type="text" class="form-control" name="motivo_colaboracion"  required>
					</div>
				</div>
			</div>
			
			<div class="panel-heading">
				<strong>
					<span class="glyphicon glyphicon-th"></span>
					<span>Datos Desaparecido</span>
				</strong>
			</div>
        
				
        <div class="row">
                    <div class="col-md-2">
					<div class="form-group">
                            <label for="no_informe">Nombre</label>
						<input type="text" class="form-control" name="desaparecido_nombre" required>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
                            <label for="no_informe">Apellido Paterno</label>
						<input type="text" class="form-control" name="desaparecido_paterno" required>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
                            <label for="no_informe">Apellido Materno</label>
						<input type="text" class="form-control" name="desaparecido_materno" required>
					</div>
				</div>
       <div class="col-md-2">
					<div class="form-group">
						<label for="id_cat_gen">Género</label>
						<select class="form-control" name="id_cat_gen">
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
						<input type="number" class="form-control" min="1" max="130" maxlength="4" name="edad" >
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
                            <label for="no_informe">Entidad de Origen del Desaparecido</label>
						<select class="form-control"  name="id_cat_ent_fed_origen" id="id_cat_ent_fed_origen" onchange="javascript:showMpio(this.value)">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_entidades as $id_cat_ent_fed) : ?>
                                    <option  value="<?php echo $id_cat_ent_fed['id_cat_ent_fed']; ?>" ><?php echo ucwords($id_cat_ent_fed['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
					</div>
				</div>
        </div>
				
		<div class="row"> 
		<div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_mun_origen" class="control-label">Municipio</label>
                                <select class="form-control" name="id_cat_mun_origen">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($municipios as $municipio): ?>
                                        <option  value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
				<div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_nacionalidad">Nacionalidad</label>
                                <select class="form-control" name="id_cat_nacionalidad">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($nacionalidades as $nacionalidad) : ?>
                                        <option  value="<?php echo $nacionalidad['id_cat_nacionalidad']; ?>"><?php echo ucwords($nacionalidad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
						
						<div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_escolaridad">Escolaridad</label>
                                <select class="form-control" name="id_cat_escolaridad">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($escolaridades as $escolaridad) : ?>
                                        <option  value="<?php echo $escolaridad['id_cat_escolaridad']; ?>"><?php echo ucwords($escolaridad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_ocup">Ocupación</label>
                                <select class="form-control" name="id_cat_ocup">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($ocupaciones as $ocupacion) : ?>
                                        <option  value="<?php echo $ocupacion['id_cat_ocup']; ?>"><?php echo ucwords($ocupacion['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="leer_escribir">¿Sabe leer y escribir?</label>
                                <select class="form-control" name="leer_escribir">
                                    <option value="">Escoge una opción</option>
                                    <option value="Leer"> Leer</option>
                                    <option value="Escribir">Escribir</option>
                                    <option value="Ambos">Ambos</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_disc">¿Tiene alguna discapacidad?</label>
                                <select class="form-control" name="id_cat_disc">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($discapacidades as $discapacidad) : ?>
                                        <option  value="<?php echo $discapacidad['id_cat_disc']; ?>"><?php echo ucwords($discapacidad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
						
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_grupo_vuln">Grupo Vulnerable</label>
                                <select class="form-control" name="id_cat_grupo_vuln">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($grupos_vuln as $grupo_vuln) : ?>
                                        <option value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_comun">Comunidad</label>
                                <select class="form-control" name="id_cat_comun">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($comunidades as $comunidad) : ?>
                                        <option  value="<?php echo $comunidad['id_cat_comun']; ?>"><?php echo ucwords($comunidad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
		</div>
        <br>
				
		<div class="row">
				<div class="col-md-4">
					<div class="form-group">
                            <label for="no_informe">Entidad de Desaparición</label>
						<select class="form-control"  name="id_cat_ent_fed" id="id_cat_ent_fed" onchange="javascript:showMpio(this.value)">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_entidades as $id_cat_ent_fed) : ?>
                                    <option value="<?php echo $id_cat_ent_fed['id_cat_ent_fed']; ?>" ><?php echo ucwords($id_cat_ent_fed['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
                            <label for="no_informe">Muicipio de Desaparición</label>
						<input type="text" class="form-control" name="municipio" required>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
                            <label for="no_informe">Localidad de Desaparición</label>
						<input type="text" class="form-control" name="localidad" >
					</div>
				</div>
		</div>	
		<div class="row">							
				<div class="col-md-4">
					<div class="form-group">
                            <label for="fecha_entrega_informe">Fecha de desaparicion</label>
                            <input type="date" class="form-control" name="fecha_desparicion" required>
                        </div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="observaciones">Observaciones</label>
						<textarea class="form-control" name="observaciones" cols="10" rows="5"></textarea>
					</div>
				</div>
		</div>
		<div class="panel-heading">
			<strong>
				<span class="glyphicon glyphicon-th"></span>
				<span>Oficios Colaboración</span>
			</strong>
		</div>
		<div class="row">
		<div id="inputFormRow">
			<div class="col-md-4">
					<div class="form-group">
                            <label for="no_informe">Autoridad</label>
						<input type="text" class="form-control" name="dependencia[]" >						
					</div>
				</div>
			
				<div class="col-md-4">
					<div class="form-group">
						<label for="adjunto">Oficio</label>
						<input type="file" accept="application/pdf" class="form-control" name="adjunto[]" >
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
					<button type="button" class="btn btn-success" id="addRow" name="addRow" >
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
						  <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
						  <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
						</svg>
					</button>
						
					</div>
				</div>	
		</div>
		</div>
		
		<div class="row" id="newRow">
		</div>	
						
				<br><br>		
			<div class="form-group clearfix">
                    <a href="colaboraciones_ud.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_colaboracion" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
	
		
<?php include_once('layouts/footer.php'); ?>