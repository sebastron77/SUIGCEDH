<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
$page_title = 'Agregar Monitoreo de Política Pública';
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
if ($nivel_user == 23) :
    page_require_level_exacto(23);
endif;
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 23) :
    redirect('home.php');
endif;

$autoridades = find_autoridades_monitoreo();
$id_folio = last_id_folios();
$inticadores_pat = find_all_pat_area(36,'monitoreo_politicas');

?>

<?php
if (isset($_POST['add_monitoreo_politicas'])) {	
	$oficios = array();
	
    if (empty($errors)) {
		
        $ejercicio = remove_junk($db->escape($_POST['ejercicio']));
        $nombre_monitoreo = remove_junk($db->escape($_POST['nombre_monitoreo']));
		$fecha_inicio   = remove_junk($db->escape($_POST['fecha_inicio']));
        $quien_atendio = remove_junk($db->escape($_POST['quien_atendio']));
        $objetivo = remove_junk($db->escape($_POST['objetivo']));
		$observaciones = remove_junk($db->escape($_POST['observaciones']));
		$id_indicadores_pat   = remove_junk(($db->escape($_POST['id_indicadores_pat'])));		
		
        
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
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-MONPOL';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-MONPOL';
        $carpeta = 'uploads/monitoreo_politicas/' . $folio_carpeta;

      
//se obtienen los nom,bre de archivos 		
		foreach($_FILES["adjunto"]['name'] as $key => $tmp_name)
		{
			//condicional si el fuchero existe
			if($_FILES["adjunto"]["name"][$key]) {
				// Nombres de archivos de temporales
				$archivonombre = $_FILES["adjunto"]["name"][$key]; 
				$fuente = $_FILES["adjunto"]["tmp_name"][$key]; 
				array_push($oficios,$archivonombre);					
				
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
			
		$query = "INSERT INTO monitoreo_politicas (";
		$query .= "folio, ejercicio,nombre_monitoreo, fecha_inicio, objetivo, observaciones,quien_atendio, id_indicadores_pat,id_user_creador,fecha_creacion) VALUES (";
		$query .= " '{$folio}','{$ejercicio}','{$nombre_monitoreo}','{$fecha_inicio}','{$objetivo}','{$observaciones}','{$quien_atendio}',{$id_indicadores_pat},'$id_user',NOW()";
		$query .= ")";
		
		$query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";
		
		 if ($dbh1->query($query) && $db->query($query2)) {
            //sucess
			$id_monitoreo_politicas = $dbh1->lastInsertId();
			insertAccion($user['id_user'], '"'.$user['username'].'" agregó un Monitoreo de Políticas Públicas, Folio: '.$folio.' ', 1);
			
				
			$contactos_politicas = $_POST['id_contactos_politicas'];				

			for ($i = 0; $i < sizeof($oficios); $i = $i + 1) {		
				$queryInsert = "INSERT INTO rel_monitoreo_oficios (id_monitoreo_politicas,id_contactos_politicas,documento_oficio,tipo_documento) VALUES('$id_monitoreo_politicas','$contactos_politicas[$i]','$oficios[$i]','env')";
				if ($db->query($queryInsert)) {
						//echo 'insertado';
						insertAccion($user['id_user'], '"'.$user['username'].'" agregó el oficio de monitoreo para el Contacto de Monitoreo, ID: '.$contactos_politicas[$i].', del Folio: '.$folio.'.', 1);
				} else {
					//echo 'falla';
				}
			}
			 
            $session->msg('s', " Los datos de  Monitoreo de Políticas Públicas se han sido agregado con éxito.");
            redirect('monitoreo_politicas.php', false);
        } else {
            //faile
            $session->msg('d', ' No se pudieron agregar los datos de  Monitoreo de Políticas Públicas.'.$query);
            redirect('add_monitoreo_politicas.php', false);
        }
		
    } else {
        $session->msg("d", $errors);
        redirect('add_monitoreo_politicas.php', false);
    }
}
?>
<script type="text/javascript">	
		
	$(document).ready(function() {
		
		$("#addRow").click(function() {	
		var numElem = $("[name='id_cat_aut[]']").size();		
		var numnext= numElem+1;
			var html = '';
				html += '<div id="inputFormRow">';
				html += '	<div class="col-md-3">';
				html += '		<select class="form-control" name="id_cat_aut[]" onchange="viewContacto(this.value,'+numnext+')" id="id_cat_aut'+numnext+'" required> ';
				html += '				<option value="">Escoge una opción</option>';
				  <?php foreach ($autoridades as $datos) : ?>
                html += '                   <option value="<?php echo $datos['id_cat_aut']; ?>"><?php echo ucwords($datos['nombre_autoridad']); ?></option>';
                               <?php endforeach; ?>
				html += '			</select>';
				html += '	</div>';
				html += '				<div class="col-md-3">';
				html += '	<div class="form-group">';
				html += '		<select class="form-control" name="id_contactos_politicas[]" id="id_contactos_politicas'+numnext+'" required>';
				html += '			<option value="">Seleccionar Contacto</option>		';				
				html += '		</select>';
				html += '	</div>';
				html += '	</div>';
				html += '	<div class="col-md-3">';
				html += '		<input type="file" accept="application/pdf" class="form-control" name="adjunto[]" >';
				html += '	</div>';
				html += '	<div class="col-md-3">';
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
	
	
	function viewContacto(id_aut,id){
		var idElemn= "id_cat_aut"+id;
		$("#"+idElemn+" option:selected").each(function() {
                id_aut = $(this).val();
                $.post("get_contacto.php", {
                    id_cat_aut: id_aut
                }, function(data) {
                    $("#id_contactos_politicas"+id).html(data);
                });
        });				
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
                <span>Agregar Monitoreo de Política Pública</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_monitoreo_politicas.php" class="clearfix" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
					<div class="form-group">
                            <label for="ejercicio">Ejercicio del Monitoreo</label>
						<select class="form-control" name="ejercicio" required>
								<option value="">Selecciona Ejercicio</option>																								
								<?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
								echo "<option value='".$i."'>".$i."</option>";
								}?>																									
							</select>
					</div>
				</div> 
				<div class="col-md-3">
					<div class="form-group">
                            <label for="nombre_monitoreo">Nombre del Monitoreo</label>
						<input type="text" class="form-control" name="nombre_monitoreo" required>
					</div>
				</div>
			
				<div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha de inicio del Monitoreo</label>
                            <input type="date" class="form-control" name="fecha_inicio" required>
                        </div>
                    </div>
			
					<div class="col-md-3">
						<div class="form-group">
							<label for="quien_atendio">¿Quién Atendió?</label>
							<input type="text" class="form-control" name="quien_atendio" required>
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="form-group">
							<label for="objetivo">Objetivo</label>
							<textarea class="form-control" name="objetivo" cols="8" rows="4"></textarea>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="observaciones">Observaciones</label>
							<textarea class="form-control" name="observaciones" cols="8" rows="4"></textarea>
						</div>
					</div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" required>
                                <option value="">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
				</div>
			
		
			
		<div class="panel-heading">
			<strong>
				<span class="glyphicon glyphicon-th"></span>
				<span>Oficios Enviados</span>
			</strong>
		</div>
		<div class="row">
		<div id="inputFormRow">
			<div class="col-md-3">
				<div class="form-group">
					<label for="id_cat_aut">Dependencia</label>
					<select class="form-control" name="id_cat_aut[]" id="id_cat_aut1" onchange="viewContacto(this.value,1)" required>
						<option value="">Escoge una opción</option>
						<?php foreach ($autoridades as $datos) : ?>
							<option value="<?php echo $datos['id_cat_aut']; ?>"><?php echo ucwords($datos['nombre_autoridad']); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="id_contactos_politicas">Contacto</label>
					<select class="form-control" name="id_contactos_politicas[]" id="id_contactos_politicas1" required>
						<option value="">Seleccionar Contacto</option>						
					</select>
				</div>
			</div>
			
				<div class="col-md-3">
					<div class="form-group">
						<label for="adjunto">Oficio</label>
						<input type="file" accept="application/pdf" class="form-control" name="adjunto[]" >
					</div>
				</div>
				<div class="col-md-3">
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
                    <a href="monitoreo_politicas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_monitoreo_politicas" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
	
		
<?php include_once('layouts/footer.php'); ?>