<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
require_once('includes/load.php');
?>
<?php
$e_detalle = find_by_tipo((int)$_GET['id']);
$tipo_solicitud = ((int)$e_detalle['tipo_solicitud']==1?'Orientación':'Canalización');
$tipo_carpeta = ((int)$e_detalle['tipo_solicitud']==1?'orientacion':'canalizacion');
$page_title = 'Acuerdos de '.$tipo_solicitud;
//echo $e_detalle['id_queja_date'];
if (!$e_detalle) {
    $session->msg("d", "ID de ".$tipo_solicitud." no encontrado.");
    //redirect('quejas.php');
}
$user = current_user();
$nivel = $user['user_level'];
$users = find_all('users');
$id_user = $user['id_user'];
$area = find_all_areas_quejas();
$acuerdos_quejas = find_acuerdo_orican((int) $_GET['id']);


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
    page_require_level(5);
}
if ($nivel == 6) {
    redirect('home.php');
}
if ($nivel == 7) {
    page_require_level(7);
}
?>
<?php


if (isset($_POST['acuerdo_orican'])) {

    if (empty($errors)) {

        $id = (int) $e_detalle['id_or_can'];

        $tipo_acuerdo = remove_junk($db->escape($_POST['tipo_acuerdo']));
        $fecha_acuerdo = remove_junk($db->escape($_POST['fecha_acuerdo']));
        $sintesis_documento = remove_junk($db->escape($_POST['sintesis_documento']));
        $publico = remove_junk($db->escape($_POST['publico'] == 'on' ? 1 : 0));

        $folio_editar = $e_detalle['folio'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/orientacioncanalizacion/'.$tipo_carpeta."/" . $resultado . '/Acuerdos';

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


        $query = "INSERT INTO rel_orientacion_canalizacion_acuerdos ( id_or_can, tipo_acuerdo,fecha_acuerdo,acuerdo_adjunto,acuerdo_adjunto_publico,sintesis_documento,publico,user_creador,fecha_alta) 
                    VALUES ({$id},'{$tipo_acuerdo}','{$fecha_acuerdo}','{$name}','{$name_publico}','{$sintesis_documento}',{$publico},{$id_user},NOW());";

        if ($db->query($query)) {
            //sucess
            $session->msg('s', " Los datos de los acuerdos se han sido agregado con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó acuerdo a la ".$tipo_solicitud.", Folio: ' . $folio_editar . '.', 1);
            redirect('acuerdos_orin_cana.php?id=' . (int) $e_detalle['id_or_can'], false);
        } else {
            //faile
            $session->msg('d', ' No se pudieron agregar los datos de los acuerdos.');
            redirect('acuerdos_orin_cana.php?id=' . (int) $e_detalle['id_or_can'], false);
        }
    } else {
        $session->msg("d", $errors);
    }
}
?>

<script type="text/javascript">	
		
		function showMe(box) {
			if(box.checked) {
				document.querySelector('#acuerdo_adjunto_publico').required = true;
			}else{
				document.querySelector('#acuerdo_adjunto_publico').required = false;
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
                <span><?php echo $tipo_solicitud ." ". $e_detalle['folio']; ?>
                </span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="acuerdos_orin_cana.php?id=<?php echo (int) $e_detalle['id_or_can']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_cat_aut">Nombre Completo</label>
                            <input type="text" class="form-control" name="id_cat_aut" value="<?php echo remove_junk($e_detalle['nombre_completo']); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_quejoso">Medio Presentación</label>
                            <input type="text" class="form-control" name="id_cat_quejoso" value="<?php echo remove_junk($e_detalle['med'] ); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area_asignada">Autoridad señalada como responsable</label>
                            <input type="text" class="form-control" name="id_cat_quejoso" value="<?php echo remove_junk($e_detalle['aut'] ); ?>" readonly>
                        </div>
                    </div>
                </div>

                <hr style="height: 1px; background-color: #370494; opacity: 1;">
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7263F0" width="25px" height="25px" viewBox="0 0 24 24" style="margin-top:-0.3%;">
                        <title>arrow-right-circle</title>
                        <path d="M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M6,13H14L10.5,16.5L11.92,17.92L17.84,12L11.92,6.08L10.5,7.5L14,11H6V13Z" />
                    </svg>
                    <span style="font-size: 20px; color: #7263F0">ACUERDOS DE LA <?php echo strtoupper($tipo_solicitud ); ?></span>
                </strong>

                <div class="row">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tipo Acuerdo</th>
                                <th scope="col">Fecha Acuerdo</th>
                                <th scope="col">Documentos</th>
                                <th scope="col">Síntesis</th>
                                <th scope="col">¿Es público?</th>
                                <th scope="col">Acciónes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $folio_editar = $e_detalle['folio'];
                            $resultado = str_replace("/", "-", $folio_editar);
                            $num = 1;
                            foreach ($acuerdos_quejas as $datos) :
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $num++ ?></th>
                                    <td><?php echo remove_junk(($datos['tipo_acuerdo'])) ?></td>
                                    <td><?php echo date("d-m-Y", strtotime(remove_junk($datos['fecha_acuerdo']))) ?></td>
                                    <td>
                                        &nbsp;&nbsp;&nbsp;
                                        <?php if (!$datos['acuerdo_adjunto'] == "") { ?>
                                            <a target="_blank" href="uploads/orientacioncanalizacion/<?php echo $tipo_carpeta."/".$resultado . '/Acuerdos/' . $datos['acuerdo_adjunto']; ?>" title="Ver Acuerdo">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                                    <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z" />
                                                </svg>
                                            </a>
                                        <?php } ?>
                                        <?php if (!$datos['acuerdo_adjunto_publico'] == "") { ?>
                                            &nbsp;&nbsp;&nbsp;
                                            <a target="_blank" href="uploads/orientacioncanalizacion/<?php echo $tipo_carpeta."/".$resultado . '/Acuerdos/' . $datos['acuerdo_adjunto_publico']; ?>" title="Ver Versión Publica del Acuerdo">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-medical" viewBox="0 0 16 16">
                                                    <path d="M7.5 5.5a.5.5 0 0 0-1 0v.634l-.549-.317a.5.5 0 1 0-.5.866L6 7l-.549.317a.5.5 0 1 0 .5.866l.549-.317V8.5a.5.5 0 1 0 1 0v-.634l.549.317a.5.5 0 1 0 .5-.866L8 7l.549-.317a.5.5 0 1 0-.5-.866l-.549.317V5.5zm-2 4.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z" />
                                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                                </svg>
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo remove_junk(($datos['sintesis_documento'])) ?></td>
                                    <td><?php echo remove_junk(($datos['publico'] == 1 ? "Sí" : "No")) ?></td>
                                    <td>
                                        <a href="delete_acuerdo_ori_can.php?id=<?php echo (int) $datos['id_rel_orientacion_canalizacion_acuerdos']; ?>&q=<?php echo (int) $e_detalle['id_or_can']; ?>" class="btn btn-delete btn-md" title="Eliminar" data-toggle="tooltip" onclick="return confirm('¿Seguro(a) que deseas eliminar el Acuerdo?');">
                                            <span class="glyphicon glyphicon-trash"></span><?php insertAccion($user['id_user'], '"' . $user['username'] . '" eliminó el acuerdo de queja, Folio: ' . $folio_editar . '.', 4); ?>
                                        </a>
                                    </td>

                                </tr>
                            <?php
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <br>
                <br>

                <hr style="height: 1px; background-color: #370494; opacity: 1;">
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7263F0" width="25px" height="25px" viewBox="0 0 24 24" style="margin-top:-0.3%;">
                        <title>arrow-right-circle</title>
                        <path d="M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M6,13H14L10.5,16.5L11.92,17.92L17.84,12L11.92,6.08L10.5,7.5L14,11H6V13Z" />
                    </svg>
                    <span style="font-size: 20px; color: #7263F0">NUEVO ACUERDO y/o DOCUMENTO DE LA <?php echo strtoupper($tipo_solicitud ); ?></span>
                </strong>

                <div class="row" style="border-radius: 10px 10px 10px 10px;">
				
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_tipo_resolucion">Tipo de Acuerdo</label>
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
                            <input id="acuerdo_adjunto_publico" type="file" accept="application/pdf" class="form-control" name="acuerdo_adjunto_publico" id="acuerdo_adjunto_publico"  required>
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
                                    <input type="checkbox" id="publico" name="publico" onclick="showMe(this)" checked>
                                    <span class="slider round"></span>
                                    <div>
                                        <p style="margin-left: 150%; margin-top: -3%; font-size: 14px;">No/Sí</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div id="newRow" style="width: 100%;"></div>
                </div>
                <div class="form-group clearfix">
                    <a href="<?php echo ((int)$e_detalle['tipo_solicitud']==1?'orientaciones':'canalizaciones')?>.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="acuerdo_orican" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>