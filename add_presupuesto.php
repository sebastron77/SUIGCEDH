
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Presupuesto';
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
}

if ($nivel_user > 2 && $nivel_user < 14) :
    redirect('home.php');	
endif;

if ($nivel_user > 14 ) :
    redirect('home.php');
endif;

$id_folio = last_id_folios();
$cat_caipitulo = find_all('cat_capitulo_presupuestal');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php 
if (isset($_POST['add_presupuesto'])) {

    $req_fields = array('paciente', 'estatus', 'atendio', 'no_sesion');
    validate_fields($req_fields);

    if (empty($errors)) {
        $ejercicio   = remove_junk($db->escape($_POST['ejercicio']));
        $monto_aprobado = remove_junk($db->escape($_POST['monto_aprobado']));
		
		$id_cat_partida_presupuestal = $_POST['id_cat_partida_presupuestal'];
		$monto_aprobado_cap = $_POST['monto_aprobado_cap'];
        
        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int)$nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int)$nuevo['contador'] + 1);
            }
        }

        $year = date("Y");
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-PESUP';


$dbh = new PDO('mysql:host=localhost;dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');

       $query = "INSERT INTO presupuesto (";
				$query .= "folio,ejercicio,tipo_evento,monto_aprobado,id_user_creador,fecha_creacion ";
				$query .= ") VALUES (";
				$query .= " '{$folio}',{$ejercicio},{$monto_aprobado},{$id_user},NOW()";
				$query .= ")";
	
				$query2 = "INSERT INTO folios (";
				$query2 .= "folio, contador";
				$query2 .= ") VALUES (";
				$query2 .= " '{$folio}','{$no_folio}'";
				$query2 .= ")";
				
				$dbh->exec($query);
				if ($db->query($query2) ) {
				$id_presupuesto = $dbh->lastInsertId();
				
				if($id_presupuesto > 0){	
					for ($i = 0; $i < sizeof($asistentes); $i = $i + 1) {
						if($id_cat_grupo_vuln[$i] !== '' && $id_cat_grupo_vuln[$i] > 0){
							$queryInsert4 = "INSERT INTO rel_capacitacion_grupos (id_presupuesto,id_cat_grupo_vuln,no_asistentes) VALUES('$id_presupuesto','$id_cat_grupo_vuln[$i]','$asistentes[$i]')";
							$db->query($queryInsert4);
						}
					}
					//sucess
					$session->msg('s', " La capacitación ha sido agregada con éxito.");
					insertAccion($user['id_user'], '"' . $user['username'] . '" agregó capacitación, Folio: ' . $folio . '.', 1);
					redirect('capacitaciones.php?a='.$area_informe, false);
				}else{
					$session->msg('d', ' No se pudo agregar la capacitación,debido a que no se genero ID de la misma'.$query);
					redirect('add_capacitacion.php?a='.$area_informe, false);
				}
				
			} else {
				//failed
				$session->msg('d', ' No se pudo agregar la capacitación.');
				redirect('add_capacitacion.php?a='.$area_informe, false);
			}
			
    } else {
        $session->msg("d", $errors);
        redirect('add_presupuesto.php', false);
    }
}
?>

 <script type="text/javascript">	
		
	$(document).ready(function() {
		
		
		$("#addRow").click(function() {	
		var partida = document.getElementsByClassName("partida").length;
			var html = '';
				html += '<div id="inputFormRow">';
				html += '	<div class="col-md-3">';
				html += '		 <select class="form-control" name="id_cat_capitulo_presupuestal[]" id="id_cat_capitulo_presupuestal_'+(partida+1)+'" onchange="showPartida(this);" required>';
                html += '	                <option value="">Escoge una opción</option>';
                                <?php foreach ($cat_caipitulo as $datos) : ?>
                html += '		                     <option value="<?php echo $datos['id_cat_capitulo_presupuestal']; ?>"><?php echo ucwords($datos['clave'])."  ".ucwords($datos['descripcion']); ?></option>';
                                <?php endforeach; ?>
                html += '		             </select>';
				html += '	</div>';
				
				html += '	<div class="col-md-3">';
				html += '		<select class="form-control partida" name="id_cat_partida_presupuestal[]" id="id_cat_partida_presupuestal_'+(partida+1)+'" >';
                html += '	                <option value="">Escoge una opción</option> ';                               
                html += '	            </select>';
				html += '	</div>';
				
				html += '	<div class="col-md-3">';
				html += '		<input type="number"  class="form-control" name="monto_aprobado_cap[]" >';			
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
	
	
	function showPartida(capitulo){
		 var arrayDeCadenas = capitulo.id.split("_");
		//alert("#id_cat_partida_presupuestal_"+arrayDeCadenas[arrayDeCadenas.length-1]);
		
		$.post("get_partida_presupuestal.php", {
                    id_capitulo: capitulo.value
                }, function(data) {
                    $("#id_cat_partida_presupuestal_"+arrayDeCadenas[arrayDeCadenas.length-1]).html(data);
                });
	}
	
</script>
<?php  include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Presupuesto</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_presupuesto.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
							<label for="ejercicio" class="control-label">Ejercicio</label>
							<select class="form-control" name="ejercicio" id="ejercicio" required>
								<option value="">Escoge una opción</option>
								<option value="2022">2022</option>
								<option value="2023">2023</option>
								<option value="2024">2024</option>
							</select>
						</div>
                    </div>
					 <div class="col-md-4">
                        <div class="form-group">
                            <label for="no_sesion">Monto Aprobado</label>
                            <input class="form-control" type="number" min="1" name="monto_aprobado" required>
                        </div>
                    </div> 
                </div>
				
				<div class="row">
				 <h3 style="font-weight:bold;">
                    <span class="material-symbols-outlined">checklist</span>
                    Capítulos
                </h3>
				<div id="inputFormRow">
				<div class="col-md-3">
					<div class="form-group">
						<label for="adjunto">Caítulo</label>
						<select class="form-control" name="id_cat_capitulo_presupuestal[]" id="id_cat_capitulo_presupuestal_1" onchange="showPartida(this);" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_caipitulo as $datos) : ?>
                                    <option value="<?php echo $datos['id_cat_capitulo_presupuestal']; ?>"><?php echo ucwords($datos['clave'])."  ".ucwords($datos['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
						
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="adjunto">Partida</label>
						<select class="form-control partida" name="id_cat_partida_presupuestal[]" id="id_cat_partida_presupuestal_1" required>
                                <option value="">Escoge una opción</option>                                
                            </select>
						
					</div>
				</div>
				 
			<div class="col-md-3">
					<div class="form-group">
                            <label for="no_informe">Monto Aprobado</label>
						<input type="number"  class="form-control" name="monto_aprobado_cap[]" required>						
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
				
                <div class="form-group clearfix">
                    <a href="sesiones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_presupuesto" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>