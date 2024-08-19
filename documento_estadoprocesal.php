<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Busqueja Orientaciones';
require_once('includes/load.php');

$e_detalle = find_by_id_queja((int) $_GET['id']);

if (!$e_detalle) {
    $session->msg("d", "ID de queja no encontrado.");
    redirect('quejas.php');
}

$user = current_user();
$id_user = $user['id_user'];
$busca_area = area_usuario($id_user);
$otro = $busca_area['nivel_grupo'];
$nivel = $user['user_level'];
$cat_est_procesal = find_all('cat_est_procesal');
$cat_tipo_resolucion = find_all('cat_tipo_res');
//$Procesal_act = find_by_id('cat_est_procesal', $e_detalle['estado_procesal'], 'id_cat_est_procesal');
$estadoProcesal_act = (((int)$e_detalle['id_cat_est_procesal']===8)?($e_detalle['estado_procesal'].' - '.$e_detalle['tipo_resolucion']):$e_detalle['estado_procesal']);
$anio= substr($e_detalle['folio_queja'],-6,4);

if (isset($_POST['procesal_queja'])) {

    if (empty($errors)) {
        $id = (int) $e_detalle['id_queja_date'];
        $estado_procesal = remove_junk($db->escape($_POST['estado_procesal']));
		$id_tipo_resolucion =((int)$estado_procesal === 8?remove_junk($db->escape($_POST['id_tipo_resolucion'])):1);
		$observaciones_resolucion =((int)$estado_procesal === 8?remove_junk($db->escape($_POST['observaciones_resolucion'])):'');
        
        $fecha_acuerdo = remove_junk($db->escape($_POST['fecha_acuerdo']));
        $sintesis_documento = remove_junk($db->escape($_POST['sintesis_documento']));
        $publico = remove_junk($db->escape($_POST['publico'] == 'on' ? 1 : 0));
      

        $folio_editar = $e_detalle['folio_queja'];
        

        if ($fecha_acuerdo) {

            $resultado = str_replace("/", "-", $folio_editar);
            $carpeta = 'uploads/quejas/' . $resultado . '/';

            $name = $_FILES['acuerdo_adjunto']['name'];
            $temp = $_FILES['acuerdo_adjunto']['tmp_name'];
            $name_publico = $_FILES['acuerdo_adjunto_publico']['name'];
            $temp2 = $_FILES['acuerdo_adjunto_publico']['tmp_name'];

           

            $Procesal = find_by_id('cat_est_procesal', (int) $estado_procesal, 'id_cat_est_procesal');

            $estadoProcesal = $Procesal['descripcion'];
            $nombre_procesal = $Procesal['descripcion'];
			$origen_acuerdo="Procesal";
			
			$sql="UPDATE quejas_dates SET fecha_actualizacion=NOW(), fecha_estado_procesal='{$fecha_acuerdo}',estado_procesal='{$estado_procesal}' ";

			if((int)$estado_procesal === 8){
				$tipo_resolucion = find_by_id('cat_tipo_res', $id_tipo_resolucion, 'id_cat_tipo_res');
				$sql .=", id_tipo_resolucion='{$id_tipo_resolucion}' ";
				
				if((int)$id_tipo_resolucion === 2){
				//-------------------------------------------------------------Incompetencia-------------------------------------------------------------
					$sql .=" ,incompetencia='1',causa_incomp='{$observaciones_resolucion}', fecha_acuerdo_incomp='{$fecha_acuerdo}',a_quien_se_traslada='{$observaciones_resolucion}' ";
					$carpeta .= "Incompetencia";
				}else if((int)$id_tipo_resolucion === 3){
				//-------------------------------------------------------------Sin Materia-------------------------------------------------------------
					$sql .=" , descripcion_sin_materia='{$observaciones_resolucion}',archivo_sin_materia='{$name}' ";
					$carpeta .= "Sin_Materia";
					
				}else if((int)$id_tipo_resolucion === 6){
				//-------------------------------------------------------------Desechamiento-------------------------------------------------------------
					$sql .=" , desechamiento=1,razon_desecha='$observaciones_resolucion' ";
					$carpeta .= "Desechamiento";
					
				}else if((int)$id_tipo_resolucion === 7){
				//-------------------------------------------------------------Falta de interes-------------------------------------------------------------
					$sql .=" , descripcion_falta_interes='{$observaciones_resolucion}',archivo_falta_interes='{$name}',fecha_falta_interes='{$fecha_acuerdo}' ";
					$carpeta .= "Falta_de_Interes";
					
				}else if((int)$id_tipo_resolucion === 8){
				//-------------------------------------------------------------Acumulación-------------------------------------------------------------
					$sql .=" , descripcion_acumulacion='$observaciones_resolucion', archivo_acumulacion='$name',fecha_acumulacion='{$fecha_acuerdo}' ";
					$carpeta .= "Acumulacion";
					
				}else if((int)$id_tipo_resolucion === 9){
				//-------------------------------------------------------------Conciliación/Mediación-------------------------------------..------------------------
					$sql .=" , fecha_cm='{$fecha_acuerdo}', descripcion_cm='$observaciones_resolucion', archivo_cm='$name' ";
					$carpeta .= "Conciliacion_Mediacion";
					
				}else if((int)$id_tipo_resolucion === 10){
				//-------------------------------------------------------------Desistimiento-------------------------------------------------------------
					$sql .=" , fecha_desistimiento='{$fecha_acuerdo}',archivo_desistimiento='{$name}'";
					$carpeta .= "Desistimiento";
					
				}else if((int)$id_tipo_resolucion === 11){
				//-------------------------------------------------------------Improcedencia-------------------------------------------------------------
					$sql .=" , fecha_improcedencia='{$fecha_acuerdo}', descripcion_improcedencia='$observaciones_resolucion',  archivo_improcedencia='$name' ";
					$carpeta .= "Improcedencia";
					
				}
				
				
				$sql .=" WHERE id_queja_date='{$db->escape($id)}'";
				//echo $sql;
				//$sql="UPDATE quejas_dates SET fecha_actualizacion=NOW(), id_tipo_resolucion='$id_tipo_resolucion' WHERE id_queja_date='{$db->escape($id)}'";
				$result = $db->query($sql);
				 if (($result && $db->affected_rows() === 1) ) {
					insertAccion($user['id_user'], '"' . $user['username'] . '" dió seguimiento/resolución a queja como "'.$tipo_resolucion['descripcion'].'", Folio: ' . $folio_editar . '.', 2);            
				} 
				$estadoProcesal = "Acuerdo de ".$tipo_resolucion['descripcion'];				
				$origen_acuerdo="Resolución";
				
			}else{
				
				$sql .=" WHERE id_queja_date='{$db->escape($id)}'";
				$result = $db->query($sql);
				 if (($result && $db->affected_rows() === 1) ) {
					insertAccion($user['id_user'], '"' . $user['username'] . '" actualizó el Estado Procesal de la queja como "'.$nombre_procesal.'", Folio: ' . $folio_editar . '.', 2);            
				} 
					$origen_acuerdo="Procesal";
					$carpeta .= "Acuerdos";
			}
			
			 if (is_dir($carpeta)) {
                $move = move_uploaded_file($temp, $carpeta . "/" . $name);
                $move2 = move_uploaded_file($temp2, $carpeta . "/" . $name_publico);
            } else {
                mkdir($carpeta, 0777, true);
                $move = move_uploaded_file($temp, $carpeta . "/" . $name);
                $move2 = move_uploaded_file($temp2, $carpeta . "/" . $name_publico);
            }
			
			/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}
			
            $query = "INSERT INTO rel_queja_acuerdos ( id_queja_date, tipo_acuerdo,fecha_acuerdo,acuerdo_adjunto,acuerdo_adjunto_publico,sintesis_documento,publico,origen_acuerdo,user_creador,fecha_alta ";
			if((int)$estado_procesal === 8){ 
				$query .= ",id_cat_tipo_res,observaciones_resolucion";				
			}
			$query .= " ) ";
            $query .= "VALUES ({$id},'{$estadoProcesal}','{$fecha_acuerdo}','{$name}','{$name_publico}','{$sintesis_documento}',{$publico},'{$origen_acuerdo}',{$id_user},NOW() ";
			if((int)$estado_procesal === 8){ 
				$query .= ",{$id_tipo_resolucion},'{$observaciones_resolucion}' ";				
			}
			$query .= " ); ";
			//echo $query;
	
            if ($db->query($query)) {
                //sucess
                $session->msg('s', " Los datos de los acuerdos se han sido agregado con éxito.");
                $sql = "UPDATE quejas_dates SET  fecha_actualizacion=NOW() WHERE id_queja_date='{$db->escape($id)}'";

                $result = $db->query($sql);
                if ($result) {
                    $session->msg('s', "Información Actualizada ");
                    insertAccion($user['id_user'], '\"' . $user['username'] . '\" agregó el acuerdo \"' . $estadoProcesal . '\" al expediente ' . $folio_editar . '.', 1);
                    insertAccion($user['id_user'], '\"' . $user['username'] . '\" actualizó el Estado procesal a \"' . $nombre_procesal . '\" al expediente ' . $folio_editar . '.', 2);
                    //redirect('quejas.php', false);
					?>
							<script language="javascript">				
					parent.location.reload();
								
							</script>
			<?php
                } else {
                    $session->msg('d', ' Lo siento no se actualizaron los datos.');
                }
            }
        } else {
            //faile
            $session->msg('d', ' No se pudieron agregar los datos de los acuerdos.');
        }
    } else {
        $session->msg("d", $errors);
        redirect('procesal_queja.php?id=' . (int) $e_detalle['id'], false);
    }
}

?>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<link rel="stylesheet" href="libs/css/main.css" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://harvesthq.github.io/chosen/chosen.css" rel="stylesheet" />
<script>
    function showInp() {
        var getSelectValue = document.getElementById("estado_procesal").value;
		if(getSelectValue == 8){
			//alert(getSelectValue);
			document.getElementById('resolucion').style.display = 'block';
			document.getElementById('obsresolucion').style.display = 'block';
			document.querySelector('#id_tipo_resolucion').required = true;
			document.querySelector('#observaciones_resolucion').required = true;
					/*		document.querySelector('#publico').checked = true;	*/			
		}else{
			document.querySelector('#id_tipo_resolucion').required = false;
			document.querySelector('#observaciones_resolucion').required = false;
			document.getElementById('resolucion').style.display = 'none';
			document.getElementById('obsresolucion').style.display = 'none';
		}
	}
</script>		
<?php header('Content-type: text/html; charset=utf-8');

?>
<form method="post" action="documento_estadoprocesal.php?id=<?php echo (int) $e_detalle['id_queja_date']; ?>" enctype="multipart/form-data">
<body style="background-color: #fff;">
 <input type="hidden" value="<?php echo (int) $e_detalle['id_queja_date']; ?>" name="id_queja_date" id="id_queja_date">
	 <hr style="height: 1px; background-color: #370494; opacity: 1;">
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7263F0" width="25px" height="25px" viewBox="0 0 24 24" >
                        <title>arrow-right-circle</title>
                        <path d="M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M6,13H14L10.5,16.5L11.92,17.92L17.84,12L11.92,6.08L10.5,7.5L14,11H6V13Z" />
                    </svg>
                    <span style="font-size: 20px; color: #7263F0">ACUERDO ESTADO PROCESAL DE LA QUEJA</span>
                </strong>
                <div class="row">
				<br>
                    <div class="col-md-12">
                        <div class="form-group">
							<label for="procesal">Estado Procesal Actual: &nbsp;&nbsp;<span style="color:red;font-weight:bold"><?php echo $estadoProcesal_act; ?></span></label>
						</div >
					</div >
                </div >
                <div class="row" >
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="estado_procesal">Estado Procesal</label>
                            <select class="form-control" name="estado_procesal" id="estado_procesal" required onchange="showInp()">
                                <option value="">Seleccione el Estado Procesal</option>
                                <?php foreach ($cat_est_procesal as $est_pros) : 
									if((int)$est_pros['id_cat_est_procesal'] < 9): //no muetra ni Acuerd de No Violacion ni Recomendacion?>
                                    <option  value="<?php echo $est_pros['id_cat_est_procesal']; ?>">
                                        <?php echo ucwords($est_pros['descripcion']); ?></option>
                                <?php endif; endforeach; ?>
                            </select>
                        </div>
                    </div> 
					
					<div class="col-md-1" style="display:none" id="resolucion">
                        <div class="form-group">
                           <label for="id_tipo_resolucion">Tipo de Resolución</label>
                            <select class="form-control" id="id_tipo_resolucion" name="id_tipo_resolucion" >
                                <option value="">Escoge una opción</option>                                
                                <?php foreach ($cat_tipo_resolucion as $tipo_res) : 
										if((int)$tipo_res['id_cat_tipo_res'] > 1 && (int)$tipo_res['id_cat_tipo_res'] <4 || (int)$tipo_res['id_cat_tipo_res'] >5): //no muetra ni Acuerd de No Violacion ni Recomendacion
								?>
                                    <option  value="<?php echo $tipo_res['id_cat_tipo_res']; ?>"><?php echo ucwords($tipo_res['descripcion']); ?></option>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="id_tipo_resolucion">Fecha de Acuerdo</label>
                            <input type="date" class="form-control" name="fecha_acuerdo" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_tipo_resolucion">Documento de Acuerdo</label>
                            <input id="acuerdo_adjunto" type="file" accept="application/pdf" class="form-control" name="acuerdo_adjunto" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_tipo_resolucion">Documento de Acuerdo en Versión Pública</label>
                            <input id="acuerdo_adjunto_publico" type="file" accept="application/pdf" class="form-control" name="acuerdo_adjunto_publico" required>
                        </div>
                    </div>
                    <div class="col-md-2" id="obsresolucion" style="display:none">
                        <div class="form-group">
                            <label for="observaciones_resolucion">Observaciones de la Resolución</label>
                            <textarea class="form-control" name="observaciones_resolucion" id="observaciones_resolucion" cols="10" rows="3" required></textarea>
                        </div>
                    </div>
						
						<div class="col-md-2">
                        <div class="form-group">
                            <label for="sintesis_documento">Síntesis del documento</label>
                            <textarea class="form-control" name="sintesis_documento" id="sintesis_documento" cols="10" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="publico">¿El Acuerdo será público?</label><br>
                            <label class="switch" style="float:left;">
                                <div class="row">
                                    <input type="checkbox" id="publico" name="publico" checked>
                                    <span class="slider round"></span>
                                    <div>
                                        <p style="margin-left: 150%; margin-top: -3%; font-size: 14px;">No/Sí</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                </div>
				 <div class="form-group clearfix">                  
                    <button type="submit" name="procesal_queja" class="btn btn-primary" value="subir">Guardar</button>
                </div>
				</body>
</form>
