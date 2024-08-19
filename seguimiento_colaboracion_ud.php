<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
$page_title = 'Seguimiento a Colaboración';
require_once('includes/load.php');

// page_require_level(4);
?>
<?php
$e_detalle = find_by_id('colaboraciones', (int)$_GET['id'], 'id_colaboraciones');
if (!$e_detalle) {
    $session->msg("d", "id de colaboracion no encontrado.");
    redirect('colaboraciones_ud.php');
}
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$cat_entidades = find_all('cat_entidad_fed');
$oficos=find_oficios_colaboracion($e_detalle['id_colaboraciones'],'env');
$seguimiento=find_oficios_colaboracion($e_detalle['id_colaboraciones'],'seg');
$desaparecido = find_by_id('cat_persona_desaparecida', (int)$e_detalle['id_cat_persona_desaparecida'], 'id_cat_persona_desaparecida');
?>

<?php
if (isset($_POST['edit_colaboracion'])) {
   $colaboracion = array();
    if (empty($errors)) {
		$id_colaboraciones = $e_detalle['id_colaboraciones'];
		
		$carpeta = 'uploads/colaboraciones/' . str_replace("/", "-", $e_detalle['folio']);

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

		
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
				
		$dependencia = $_POST['dependencia'];	
		for ($i = 0; $i < sizeof($colaboracion); $i = $i + 1) {		
				$queryInsert = "INSERT INTO rel_colaboracion_oficios (id_colaboraciones,autoridad,documento_oficio,tipo_documento) VALUES('$id_colaboraciones','$dependencia[$i]','$colaboracion[$i]','seg')";
				if ($db->query($queryInsert)) {						
					insertAccion($user['id_user'], '"'.$user['username'].'" agregó el oficio de colaboracion de seguimiento para '.$dependencia[$i].', del Folio: '.$e_detalle['folio'].'.', 1);
					$acction = true;
				} else {
					//echo 'falla';
				}
			}
		if($acction){			
            $session->msg('s', " La Colaboración con folio '".$e_detalle['folio']."' ha sido acuatizado  en seguimiento a la con éxito.");            
            redirect('colaboraciones_ud.php', false);
		}else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron canmbios a la informacion.');
            redirect('colaboraciones_ud.php', false);
        }
		
    } else {
        $session->msg("d", $errors);
        redirect('colaboraciones_ud.php' , false);
    }
}
?>

<script type="text/javascript">	
		
	$(document).ready(function() {
		
		
		$("#addRow").click(function() {	
			var html = '';
				html += '<div id="inputFormRow">';
				html += '	<div class="col-md-4">';
				html += '		<select class="form-control"  name="dependencia[]" >' ;
				html += '				<option value="">Escoge una Autoridad</option>';
                html += '               <?php foreach ($oficos as $autoridad) : ?> ';
                html += '                    <option value="<?php echo $autoridad['id_rel_colaboracion_oficios']; ?>" ><?php echo ucwords($autoridad['autoridad']); ?></option> ';
                html += '                <?php endforeach; ?> ';
				html += '			</select>';
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
	
		function deteleOfi(id) {
			if(confirm('¿Seguro que deseas eliminar este oficio, recuerda que una ves eliminado no se puede recuperar? ')){
				if(id > 0){
					$.post("detele_colaboracion.php", {
						id: id
					}, function(a) {						
						$('#of'+id).remove();
					});
				 }
			}
		}
 
	
</script>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Seguimiento a Colaboración <?php echo $e_detalle['folio']; ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="seguimiento_colaboracion_ud.php?id=<?php echo (int)$e_detalle['id_colaboraciones']; ?>" class="clearfix" enctype="multipart/form-data">
              
			<div class="row">
                    <div class="col-md-3">
					<div class="form-group">
                            <label for="no_informe">Quien lo solicita</label>
						<input type="text" class="form-control" name="solicitante" value="<?php echo remove_junk($e_detalle['solicitante']); ?>" disabled>
					</div>
				</div>
			
				<div class="col-md-3">
					<div class="form-group">
						<label for="adjunto2">Oficio Solicitud</label><br>
						<label style="font-size:12px; color:#E3054F;" >Archivo Actual: <?php echo remove_junk($e_detalle['oficio_solicitud']); ?><?php ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="motivo_colaboracion">Motivo Colaboracion</label>
						<input type="text" class="form-control" name="motivo_colaboracion" value="<?php echo remove_junk($e_detalle['motivo_colaboracion']); ?>" disabled>
					</div>
				</div>
				<div class="col-md-3">
						<div class="form-group">
							<label for="id_cat_ent_fed_colaboracion">Entidad Federativa con la que se realiza la Colaboracion</label>
							<select class="form-control"  name="id_cat_ent_fed_colaboracion" id="id_cat_ent_fed_colaboracion"  disabled>
									<option value="0">Escoge una opción</option>
									<?php foreach ($cat_entidades as $id_cat_ent_fed) : ?>
										<option <?php if ($e_detalle['id_cat_ent_fed_colaboracion'] === $id_cat_ent_fed['id_cat_ent_fed']) echo 'selected="selected"'; ?>  value="<?php echo $id_cat_ent_fed['id_cat_ent_fed']; ?>" ><?php echo ucwords($id_cat_ent_fed['descripcion']); ?></option>
									<?php endforeach; ?>
								</select>
						</div>
					</div>
			</div>


			  
			<div class="panel-heading">
				<strong>
					<span class="glyphicon glyphicon-th"></span>
					<span>Datos Desaparecido</span>
				</strong>
			</div>
        
		<div class="row" >
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="nombrePD">Nombre</label>
                            <input type="text" class="form-control" name="nombrePD" placeholder="Nombre(s)" value="<?php echo remove_junk($desaparecido['nombre']); ?>" disabled>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="paternoPD">Apellido Paterno</label>
                            <input type="text" class="form-control" name="paternoPD" placeholder="Apellido Paterno" value="<?php echo remove_junk($desaparecido['paterno']); ?>" disabled>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="maternoPD">Apellido Materno</label>
                            <input type="text" class="form-control" name="maternoPD" placeholder="Apellido Materno" value="<?php echo remove_junk($desaparecido['materno']); ?>" disabled>
                        </div>
                    </div>
					<div class="col-md-3">
					<div class="form-group">
                            <label for="fecha_entrega_informe">Fecha de desaparicion</label>
                            <input type="date" class="form-control" name="fecha_desparicion"  value="<?php echo $desaparecido['fecha_desaparicion']; ?>" disabled>
                        </div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
                            <label for="id_cat_ent_fed_desaparicion">Entidad de Desaparición</label>
						<select class="form-control"  name="id_cat_ent_fed_desaparicion" id="id_cat_ent_fed_desaparicion" disabled >
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_entidades as $id_cat_ent_fed) : ?>
                                    <option <?php if ($desaparecido['id_cat_ent_fed_desaparicion'] === $id_cat_ent_fed['id_cat_ent_fed']) echo 'selected="selected"'; ?> value="<?php echo $id_cat_ent_fed['id_cat_ent_fed']; ?>" ><?php echo ucwords($id_cat_ent_fed['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
                            <label for="id_cat_mun_desaparicion">Muicipio de Desaparición</label>
						 <select class="form-control" name="id_cat_mun_desaparicion" disabled>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($municipios as $municipio) : ?>
                                    <option <?php if ($desaparecido['id_cat_mun_desaparicion'] === $municipio['id_cat_mun']) echo 'selected="selected"'; ?> value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
                            <label for="localidad_desaparicion">Localidad de Desaparición</label>
						<input type="text" class="form-control" name="localidad_desaparicion" value="<?php echo $desaparecido['localidad_desaparicion']; ?>" disabled>
					</div>
				</div>							
		</div>
		
		
		</div>
		<table class="table table-bordered table-striped" style="width: 50%;margin: 0 auto">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
							<th colspan="2" style="text-align:center;">Oficios Seguimiento de Colaboración </th>
                        </tr>
						<tr style="height: 10px;">
                            <th >Autoridad </th>
                            <th >Documento Oficio</th>
                        </tr>
                    </thead>
                    <tbody>
					<?php foreach ($seguimiento as $seg) : ?>
                        <tr>
                            <td><?php echo remove_junk(ucwords($seg['autoridad'])) ?></td>
                            <td style="text-align:center;">
								<a target="_blank" style="color: #0094FF;" href="uploads/colaboraciones/<?php echo $folio_carpeta . '/' . $seg['documento_oficio']; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                        <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z" />
                                    </svg>
                                </a>
								</td>
                        </tr>
						<?php endforeach; ?>
                    </tbody>
                </table>
		
		<div class="panel-heading" style="border: 0px">
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
							<select class="form-control"  name="dependencia[]" >
								<option value="">Escoge una Autoridad</option>
                                <?php foreach ($oficos as $autoridad) : ?>
                                    <option value="<?php echo $autoridad['autoridad']; ?>" ><?php echo ucwords($autoridad['autoridad']); ?></option>
                                <?php endforeach; ?>
							</select>
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
                    <button type="submit" name="edit_colaboracion" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>