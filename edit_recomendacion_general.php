<?php
$page_title = 'Editar Recomendación General';
require_once('includes/load.php');

// page_require_level(4);
?>
<?php
$e_recomendacion = find_by_id('recomendaciones_generales', (int)$_GET['id'],'id_recom_general');
if (!$e_recomendacion) {
    $session->msg("d", "id de recomendación general no encontrado.");
    redirect('recomendaciones_generales.php');
}
$user = current_user();
$nivel = $user['user_level'];
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];
$cat_derecho_vuln = find_all_derecho_vuln();
$rel_recomendacion_der_vuln = find_recomendacion_der_vuln((int)$_GET['id'],'g');

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level(7);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}
if ($nivel_user == 52) {
    page_require_level_exacto(52);
}

if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 50) :
    redirect('home.php');
endif;
if ($nivel_user > 50 && $nivel_user < 52) :
    redirect('home.php');
endif;

?>

<?php
if (isset($_POST['edit_recomendacion_general'])) {
    if (empty($errors)) {
        $id_recom_general = (int)$e_recomendacion['id_recom_general'];
         $numero_recomendacion   = remove_junk($db->escape($_POST['numero_recomendacion']));
        $fecha_recomendacion   = remove_junk($db->escape($_POST['fecha_recomendacion']));
        $autoridad_responsable   = remove_junk($db->escape($_POST['autoridad_responsable']));
		$observaciones   = remove_junk($db->escape($_POST['observaciones']));
		$hecho_completo   = remove_junk(($db->escape($_POST['hecho_completo'])));
		$derecho_vuln = $_POST['id_cat_derecho_vuln'];
	
		$year = date("Y");
        $folio_carpeta = str_replace("/", "-", $numero_recomendacion);
        $carpeta = 'uploads/recomendacionesGenerales/' . $folio_carpeta;

       
	   if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['recomendacion_adjunto']['name'];
        $size = $_FILES['recomendacion_adjunto']['size'];
        $type = $_FILES['recomendacion_adjunto']['type'];
        $temp = $_FILES['recomendacion_adjunto']['tmp_name'];

        $name2 = $_FILES['recomendacion_adjunto_publico']['name'];
        $size2 = $_FILES['recomendacion_adjunto_publico']['size'];
        $type2 = $_FILES['recomendacion_adjunto_publico']['type'];
        $temp2 = $_FILES['recomendacion_adjunto_publico']['tmp_name'];

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

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        $move2 =  move_uploaded_file($temp2, $carpeta . "/" . $name2);
        $move3 =  move_uploaded_file($tempRecSint, $carpeta . "/" . $nameRecSint);
        $move4 =  move_uploaded_file($tempRecTrad, $carpeta . "/" . $nameRecTrad);
        $move5 =  move_uploaded_file($tempRecLF, $carpeta . "/" . $nameRecLF);
        $move6 =  move_uploaded_file($tempinfografia, $carpeta . "/" . $infografia);
	   
	   $sql="UPDATE recomendaciones_generales SET 
		numero_recomendacion='{$numero_recomendacion}',
		autoridad_responsable='{$autoridad_responsable}',
		fecha_recomendacion='{$fecha_recomendacion}',
		observaciones='{$observaciones}',
		hecho_completo='{$hecho_completo}' ";
		if ($name != '') { 			$sql .= ", recomendacion_adjunto='{$name}' "; 		}
		if ($name2 != '') { 			$sql .= ", recomendacion_adjunto_publico='{$name2}' "; 		}
		if ($nameRecSint != '') { 			$sql .= ", sintesis_rec='{$nameRecSint}' "; 		}
		if ($nameRecTrad != '') { 			$sql .= ", traduccion='{$nameRecTrad}' "; 		}
		if ($nameRecLF != '') { 			$sql .= ", lectura_facil='{$nameRecLF}' "; 		}
		if ($infografia != '') { 			$sql .= ", infografia='{$infografia}' "; 		}
	    $sql .= " WHERE id_recom_general='{$db->escape($id_recom_general)}'";
	    $result = $db->query($sql);
		if ($result && $db->affected_rows() === 1) {
            $cambios =true;
        } else {
            $cambios=false;
        }
       
		$query = "DELETE FROM rel_recomendacion_gral_der_vuln WHERE id_recom_general =" . $id_recom_general;
		$db->query($query) ; 
		for ($i = 0; $i < sizeof($derecho_vuln); $i++) {						
				$query2 = "INSERT INTO rel_recomendacion_gral_der_vuln(id_recom_general, id_cat_der_vuln) VALUES ($id_recom_general,$derecho_vuln[$i]); ";
											
				if ($db->query($query2)) {
						$cambios =true;					
				} else {
					$cambios=false;
				}
			}
			
		if($cambios){
			$session->msg('s', "Información Actualizada ");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó recomendación general, Num. Rec.: ' . $numero_recomendacion . '.', 2);
            redirect('recomendaciones_generales.php', false);
		}else {
            $session->msg('d', ' Lo siento no se actualizaron los datos.');
            redirect('edit_recomendacion_general.php?id=' . (int)$e_recomendacion['id_recom_general'], false);
        }
		
    } else {
        $session->msg("d", $errors);
        redirect('edit_recomendacion_general.php?id=' . (int)$e_recomendacion['id_recom_general'], false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>

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
                <span>Editar recomendación general <?php echo $e_recomendacion['numero_recomendacion']; ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_recomendacion_general.php?id=<?php echo (int)$e_recomendacion['id_recom_general']; ?>" enctype="multipart/form-data">
                 <div class="row">
                   <div class="col-md-3">
                        <div class="form-group">
                            <label for="numero_recomendacion">Folio Recomendación<span style="color:red; font-weight:bold;">*</span></label>
                            <input type="text" class="form-control" name="numero_recomendacion" value="<?php echo remove_junk($e_recomendacion['numero_recomendacion']); ?>" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_recomendacion">Fecha Recomendación<span style="color:red; font-weight:bold;">*</span></label>
                            <input type="date" class="form-control" name="fecha_recomendacion" value="<?php echo remove_junk($e_recomendacion['fecha_recomendacion']); ?>" required>
                        </div>
                    </div>
					
				
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="autoridad_responsable">Autoridad Responsable<span style="color:red; font-weight:bold;">*</span></label>
                            <input type="text" class="form-control" name="autoridad_responsable" placeholder="Autoridad(es) Responsable(es)" value="<?php echo remove_junk($e_recomendacion['autoridad_responsable']); ?>" required>
                        </div>
                    </div>                   
                </div>

                 <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <span>
                                <label for="recomendacion_adjunto">Adjuntar Recomendación</label>
                                <input id="recomendacion_adjunto" type="file" accept="application/pdf" class="form-control" name="recomendacion_adjunto" >
								<label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_recomendacion['recomendacion_adjunto']); ?><?php ?></label>
                            </span>
                        </div>
                    </div>               

                    <div class="col-md-3">
                        <div class="form-group">
                            <span>
                                <label for="recomendacion_adjunto_publico">Adjuntar Recomendación Versión Pública</label>
                                <input id="recomendacion_adjunto_publico" type="file" accept="application/pdf" class="form-control" name="recomendacion_adjunto_publico" >
								<label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_recomendacion['recomendacion_adjunto_publico']); ?><?php ?></label>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <span>
                                <label for="sintesis_rec">Adjuntar Síntesis</label>
                                <input id="sintesis_rec" type="file" accept="application/pdf" class="form-control" name="sintesis_rec">
								<label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_recomendacion['sintesis_rec']); ?><?php ?></label>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <span>
                                <label for="traduccion">Adjuntar Traducción</label>
                                <input id="traduccion" type="file" accept="application/pdf" class="form-control" name="traduccion">
								<label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_recomendacion['traduccion']); ?><?php ?></label>
                            </span>
                        </div>
                    </div>
					
                    <div class="col-md-3">
                        <div class="form-group">
                            <span>
                                <label for="lectura_facil">Adjuntar Lectura Fácil</label>
                                <input id="lectura_facil" type="file" accept="application/pdf" class="form-control" name="lectura_facil">
								<label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_recomendacion['lectura_facil']); ?><?php ?></label>
                            </span>
                        </div>
                    </div>
					 <div class="col-md-4">
                        <div class="form-group">
                            <span>
                                <label for="infografia">Adjuntar Infografía</label>
                                <input id="infografia" type="file" accept="image/x-png,image/gif,image/jpeg" class="form-control" name="infografia">
								<label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_recomendacion['infografia']); ?><?php ?></label>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="hecho_completo">Hecho Concreto <span style="color:red; font-weight:bold;">*</span></label>
                            <textarea class="form-control" name="hecho_completo" id="hecho_completo" cols="10" rows="3" required><?php echo remove_junk($e_recomendacion['hecho_completo']); ?></textarea>
                        </div>
                    </div>
				
					
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="3"><?php echo remove_junk($e_recomendacion['observaciones']); ?></textarea>
                        </div>
                    </div>
                </div>

				<div class="row">
				 <h3 style="font-weight:bold;">
                    <span class="material-symbols-outlined">checklist</span>
                    Derecho Vulnerado
                </h3>
				 <?php 
								$num=0;
								foreach ($rel_recomendacion_der_vuln as $datos_rec) : ?>
				<div id="inputFormRow">		
				<div class="col-md-4">
                        <div class="form-group">
                            
                            <select class="form-control" name="id_cat_derecho_vuln[]"  >
                                <option value="">Seleccione el Derecho Violentado</option>
                                <?php foreach ($cat_derecho_vuln as $derecho_vuln) : ?>
                                    <option <?php if ($derecho_vuln['id_cat_der_vuln'] === $datos_rec['id_cat_der_vuln']) echo 'selected="selected"'; ?> value="<?php echo $derecho_vuln['id_cat_der_vuln']; ?>">
                                        <?php echo ucwords($derecho_vuln['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
				</div>
				
			<?php if($num < 1){?>
				<div class="col-md-2">
					<div class="form-group">
					<button type="button" class="btn btn-success" id="addRow" name="addRow" >
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
						  <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
						  <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
						</svg>
					</button>
						
					</div>
				</div>	
			<?php }else{ ?>
			<div class="col-md-2">
					<button type="button" class="btn btn-outline-danger" id="removeRow" > 
				  	<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">
							<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
							<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>
						</svg>
				  	</button>			
					</div>
			<?php } ?>
		</div>
                                <?php $num++; endforeach; ?>
			<?php if($num==0){?>
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
			<?php }?>
				</div>
		<div class="row" id="newRow">
		</div>	
		
                <div class="form-group clearfix">
                    <a href="recomendaciones_generales.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_recomendacion_general" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>