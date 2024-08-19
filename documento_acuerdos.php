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
$anio= substr($e_detalle['folio_queja'],-6,4);

if (isset($_POST['procesal_queja'])) {

    if (empty($errors)) {
        $id = (int) $e_detalle['id_queja_date'];
        $tipo_acuerdo = remove_junk($db->escape($_POST['tipo_acuerdo']));
        $fecha_acuerdo = remove_junk($db->escape($_POST['fecha_acuerdo']));
        $sintesis_documento = remove_junk($db->escape($_POST['sintesis_documento']));
        $publico = remove_junk($db->escape($_POST['publico'] == 'on' ? 1 : 0));
      

        $folio_editar = $e_detalle['folio_queja'];
        

        if ($fecha_acuerdo) {

            $resultado = str_replace("/", "-", $folio_editar);
            $carpeta = 'uploads/quejas/' . $resultado . '/Acuerdos';

            $name = $_FILES['acuerdo_adjunto']['name'];
            $temp = $_FILES['acuerdo_adjunto']['tmp_name'];
            $name_publico = $_FILES['acuerdo_adjunto_publico']['name'];
            $temp2 = $_FILES['acuerdo_adjunto_publico']['tmp_name'];

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
            
            $query = "INSERT INTO rel_queja_acuerdos ( id_queja_date, tipo_acuerdo,fecha_acuerdo,acuerdo_adjunto,acuerdo_adjunto_publico,sintesis_documento,interno,publico,origen_acuerdo,user_creador,fecha_alta) 
                    VALUES ({$id},'{$tipo_acuerdo}','{$fecha_acuerdo}','{$name}','{$name_publico}','{$sintesis_documento}',0,{$publico},'Acuerdos',{$id_user},NOW());";

            
			if ($db->query($query)) {
            //sucess
            $session->msg('s', " Los datos de los acuerdos se han sido agregado con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó acuerdo a queja, Folio: ' . $folio_editar . '.', 1);
            ?>
							<script language="javascript">				
					parent.location.reload();
								
							</script>
			<?php
        } else {
            //faile
            $session->msg('d', ' No se pudieron agregar los datos de los acuerdos.');
            redirect('acuerdos_queja.php?id=' . (int) $e_detalle['id_queja_date'], false);
        }
		
		
        } else {
            //faile
            $session->msg('d', ' No se pudieron agregar los datos de los acuerdos.');
            //redirect('procesal_queja.php?id=' . (int) $e_detalle['id_queja_date'], false);
        }
    } else {
        $session->msg("d", $errors);
        //redirect('procesal_queja.php?id=' . (int) $e_detalle['id'], false);
    }
}


?>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<link rel="stylesheet" href="libs/css/main.css" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://harvesthq.github.io/chosen/chosen.css" rel="stylesheet" />

<?php header('Content-type: text/html; charset=utf-8');

?>
<form method="post" action="documento_acuerdos.php?id=<?php echo (int) $e_detalle['id_queja_date']; ?>" enctype="multipart/form-data">
<body style="background-color: #fff;">
 <input type="hidden" value="<?php echo (int) $e_detalle['id_queja_date']; ?>" name="id_queja_date" id="id_queja_date">
	 <hr style="height: 1px; background-color: #370494; opacity: 1;">
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7263F0" width="25px" height="25px" viewBox="0 0 24 24" >
                        <title>arrow-right-circle</title>
                        <path d="M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M6,13H14L10.5,16.5L11.92,17.92L17.84,12L11.92,6.08L10.5,7.5L14,11H6V13Z" />
                    </svg>
                    <span style="font-size: 20px; color: #7263F0">NUEVO ACUERDO DE LA QUEJA</span>
                </strong>
                <div class="row">
                     <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_tipo_resolucion">Nombre del Acuerdo</label>
                            <input type="text" class="form-control" name="tipo_acuerdo" required>
                        </div>
                    </div>
                    <div class="col-md-2">
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
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sintesis_documento">Síntesis del documento</label>
                            <textarea class="form-control" name="sintesis_documento" id="sintesis_documento" cols="10" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="col-md-2">
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
