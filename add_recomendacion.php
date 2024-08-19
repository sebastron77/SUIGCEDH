<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Recomendación';
require_once('includes/load.php');
$id_folio_acuerdo = last_id_folios();
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$cat_derecho_vuln = find_all_derecho_vuln();
if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    redirect('home.php');
}
if ($nivel == 4) {
    redirect('home.php');
}
if ($nivel == 5) {
    page_require_level_exacto(5);

}if ($nivel == 50) {
    page_require_level_exacto(50);
}
if ($nivel == 6) {
    redirect('home.php');
}
if ($nivel == 7) {
    redirect('home.php');
}
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_recomendacion'])) {

    $req_fields = array('servidor_publico', 'fecha_recomendacion', 'observaciones');
    validate_fields($req_fields);

    if (empty($errors)) {
        $numero_recomendacion   = remove_junk($db->escape($_POST['numero_recomendacion']));
        $folio_queja   = remove_junk($db->escape($_POST['folio_queja']));
        $servidor_publico   = remove_junk($db->escape($_POST['servidor_publico']));
        $fecha_acuerdo   = remove_junk($db->escape($_POST['fecha_recomendacion']));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));
        $acuerdo_adjunto   = remove_junk(($db->escape($_POST['recomendacion_adjunto'])));
        $hecho_completo   = remove_junk(($db->escape($_POST['hecho_completo'])));

		$cat_derecho_vuln = $_POST['id_cat_derecho_vuln'];
		
        //Se crea el número de folio
        $year = date("Y");
        $folio_carpeta = str_replace("/", "-", $numero_recomendacion);
        $carpeta = 'uploads/recomendaciones/' . $folio_carpeta . '/';

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['recomendacion_adjunto']['name'];
        $size = $_FILES['recomendacion_adjunto']['size'];
        $type = $_FILES['recomendacion_adjunto']['type'];
        $temp = $_FILES['recomendacion_adjunto']['tmp_name'];

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);

        $name2 = $_FILES['recomendacion_adjunto_publico']['name'];
        $size2 = $_FILES['recomendacion_adjunto_publico']['size'];
        $type2 = $_FILES['recomendacion_adjunto_publico']['type'];
        $temp2 = $_FILES['recomendacion_adjunto_publico']['tmp_name'];

        $move2 =  move_uploaded_file($temp2, $carpeta . "/" . $name2);

        $nameRecSint = $_FILES['sintesis_rec']['name'];
        $sizeRecSint = $_FILES['sintesis_rec']['size'];
        $typeRecSint = $_FILES['sintesis_rec']['type'];
        $tempRecSint = $_FILES['sintesis_rec']['tmp_name'];

        $nameRecTrad = $_FILES['traduccion']['name'];
        $sizeRecTrad = $_FILES['traduccion']['size'];
        $typeRecTrad = $_FILES['traduccion']['type'];
        $tempRecTrad = $_FILES['traduccion']['tmp_name'];

        $nameRecLF = $_FILES['lectura_facil']['name'];
        $sizeRecLF = $_FILES['lectura_facil']['size'];
        $typeRecLF = $_FILES['lectura_facil']['type'];
        $tempRecLF = $_FILES['lectura_facil']['tmp_name'];
		
		$infografia = $_FILES['infografia']['name'];
		$sizeinfografia = $_FILES['infografia']['size'];
        $typeinfografia = $_FILES['infografia']['type'];
        $tempinfografia = $_FILES['infografia']['tmp_name'];
	
	
	

        $move3 =  move_uploaded_file($tempRecSint, $carpeta . "/" . $nameRecSint);
        $move4 =  move_uploaded_file($tempRecTrad, $carpeta . "/" . $nameRecTrad);
        $move5 =  move_uploaded_file($tempRecLF, $carpeta . "/" . $nameRecLF);
        $move6 =  move_uploaded_file($tempinfografia, $carpeta . "/" . $infografia);
/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}

		$dbh = new PDO('mysql:host=localhost; dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		
        if ($move && $name != '') {
            $query = "INSERT INTO recomendaciones (";
            $query .= "numero_recomendacion,folio_queja,servidor_publico,fecha_recomendacion,observaciones,recomendacion_adjunto,recomendacion_adjunto_publico,sintesis_rec,traduccion,lectura_facil,hecho_completo,infografia,id_user,fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$numero_recomendacion}','{$folio_queja}','{$servidor_publico}','{$fecha_acuerdo}','{$observaciones}','{$name}','{$name2}','{$nameRecSint}','{$nameRecTrad}','{$nameRecLF}','{$hecho_completo}','{$infografia}',{$$id_user},NOW() ";
            $query .= ")";
        } else {
            $query = "INSERT INTO recomendaciones (";
            $query .= "numero_recomendacion,folio_queja,servidor_publico,fecha_recomendacion,observaciones,sintesis_rec,traduccion,lectura_facil,hecho_completo,infografia,id_user,fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$numero_recomendacion}','{$folio_queja}','{$servidor_publico}','{$fecha_acuerdo}','{$observaciones}','{$nameRecSint}','{$nameRecTrad}','{$nameRecLF}','{$hecho_completo}','{$infografia}',{$$id_user},NOW()";
            $query .= ")";
        }
		$dbh->exec($query);
        $id_insertado = $dbh->lastInsertId();
		$sql="";
		for ($i = 0; $i < sizeof($cat_derecho_vuln); $i++) {						
				$query2 = "INSERT INTO rel_recomendacion_der_vuln(id_recomendacion, id_cat_der_vuln) VALUES ($id_insertado,$cat_derecho_vuln[$i]); ";
						//echo $sql.=sizeof($cat_derecho_vuln);						
						//echo $sql.=$query2;						
				if ($db->query($query2)) {
						//echo 'insertado';						
				} else {
					//echo 'falla';
				}
			}		
        
            $session->msg('s', " La recomendación ha sido agregada con éxito.".$sql);
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó recomendación, Núm. Rec.: ' . $numero_recomendacion . '.', 1);
            redirect('recomendaciones_antes.php', false);
       
    } else {
        $session->msg("d", $errors);
        redirect('add_recomendacion.php', false);
    }
}
?>
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<script type="text/javascript">	
		
	$(document).ready(function() {
		
		
		$("#addRow").click(function() {	
			var html = '';
				html += '<div id="inputFormRow">';
				html += '	<div class="col-md-4">';
				html += '		<select class="form-control" name="id_cat_derecho_vuln[]">';
                html += '                <option value="">Seleccione el Derecho Violentado</option>';
                               <?php foreach ($cat_derecho_vuln as $datos) : ?>
                html += '                   <option value="<?php echo $datos['id_cat_der_vuln']; ?>"><?php echo ucwords($datos['descripcion']); ?></option>';
                               <?php endforeach; ?>
                html += '            </select>';
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

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Recomendación</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_recomendacion.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="folio_queja">Folio de Queja<span style="color:red; font-weight:bold;">*</span></label>
                            <input type="text" class="form-control" name="folio_queja" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="numero_recomendacion">Núm. Recomendación<span style="color:red; font-weight:bold;">*</span></label>
                            <input type="text" class="form-control" name="numero_recomendacion" required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="servidor_publico">Servidor público<span style="color:red; font-weight:bold;">*</span></label>
                            <input type="text" class="form-control" name="servidor_publico" placeholder="Nombre Completo" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_recomendacion">Fecha de Recomendación<span style="color:red; font-weight:bold;">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_recomendacion" required>
                        </div>
                    </div>
                 </div>
				
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <span>
                                <label for="recomendacion_adjunto">Adjuntar Recomendación</label>
                                <input id="recomendacion_adjunto" type="file" accept="application/pdf" class="form-control" name="recomendacion_adjunto" required>
                            </span>
                        </div>
                    </div>               

                    <div class="col-md-3">
                        <div class="form-group">
                            <span>
                                <label for="recomendacion_adjunto_publico">Adjuntar Recomendación Versión Pública</label>
                                <input id="recomendacion_adjunto_publico" type="file" accept="application/pdf" class="form-control" name="recomendacion_adjunto_publico" required>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <span>
                                <label for="sintesis_rec">Adjuntar Síntesis</label>
                                <input id="sintesis_rec" type="file" accept="application/pdf" class="form-control" name="sintesis_rec">
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <span>
                                <label for="traduccion">Adjuntar Traducción</label>
                                <input id="traduccion" type="file" accept="application/pdf" class="form-control" name="traduccion">
                            </span>
                        </div>
                    </div>
					 </div>
				
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <span>
                                <label for="lectura_facil">Adjuntar Lectura Fácil</label>
                                <input id="lectura_facil" type="file" accept="application/pdf" class="form-control" name="lectura_facil">
                            </span>
                        </div>
                    </div>
					 <div class="col-md-3">
                        <div class="form-group">
                            <span>
                                <label for="infografia">Adjuntar Infografía</label>
                                <input id="infografia" type="file" accept="image/x-png,image/gif,image/jpeg" class="form-control" name="infografia">
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="hecho_completo">Hecho Concreto <span style="color:red; font-weight:bold;">*</span></label>
                            <textarea class="form-control" name="hecho_completo" id="hecho_completo" cols="10" rows="3" required></textarea>
                        </div>
                    </div>
				
					
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="3"></textarea>
                        </div>
                    </div>
                </div>


		<div class="row">
				 <h3 style="font-weight:bold;">
                    <span class="material-symbols-outlined">checklist</span>
                    Derecho Vulnerado
                </h3>
				<div id="inputFormRow">
			
				<div class="col-md-4">
                        <div class="form-group">
                            
                            <select class="form-control" name="id_cat_derecho_vuln[]"  >
                                <option value="">Seleccione el Derecho Violentado</option>
                                <?php foreach ($cat_derecho_vuln as $derecho_vuln) : ?>
                                    <option value="<?php echo $derecho_vuln['id_cat_der_vuln']; ?>">
                                        <?php echo ucwords($derecho_vuln['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
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

                <div class="form-group clearfix">
                    <a href="recomendaciones_antes.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_recomendacion" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>