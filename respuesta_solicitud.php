<?php
$page_title = 'Respuesta a Solicitud';
require_once('includes/load.php');
$areas = find_all('area');

$solicitud = find_by_id('solicitudes_informacion', (int)$_GET['id'], 'id_solicitudes_informacion');

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 10) {
    page_require_level_exacto(10);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
	
endif;if ($nivel_user > 7 && $nivel_user < 10) :
    redirect('home.php');
endif;
if ($nivel_user > 10) :
    redirect('home.php');
endif;


if (isset($_POST['update'])) {
	if (empty($errors)) {
		$id = (int)$solicitud['id_solicitudes_informacion'];

        $folio_solicitud = remove_junk($db->escape($_POST['folio_solicitud']));
			
        $fecha_respuesta   = remove_junk($db->escape($_POST['fecha_respuesta']));
		
		 $carpeta = 'uploads/solicitudes_informacion/' . $solicitud['folio_solicitud'];


        $name = $_FILES['adjunto']['name'];
        $size = $_FILES['adjunto']['size'];
        $type = $_FILES['adjunto']['type'];
        $temp = $_FILES['adjunto']['tmp_name'];

        $move = move_uploaded_file($temp, $carpeta . "/" . $name);
			
		if ($move && $name != '') {
            $sql = "UPDATE solicitudes_informacion SET 
			fecha_respuesta='{$fecha_respuesta}', 
			archivo_respuesta='{$name}' 			
			WHERE id_solicitudes_informacion='{$db->escape($id)}'";
			
			
			$result = $db->query($sql);
				if ($result && $db->affected_rows() === 1) {
				insertAccion($user['id_user'], '"' . $user['username'] . '" dió respuesta a la Solicitud de Información('.$id.') de Folio: -' . $solicitud['folio_solicitud'].'-', 2);
				$session->msg('s', " La Solicitud de Información con folio '" . $solicitud['folio_solicitud'] . "' ha sido respondida con éxito.");
				redirect('solicitudes_ut.php', false);
			} else {
				$session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron canmbios a la informacion.');
				redirect('solicitudes_ut.php', false);
			
			}
		}     
       

    } else {
        $session->msg("d", $errors);
        redirect('solicitudes_ut.php', false);
    }
}
?>


<style>
    .login-page2 {
        width: 550px;
        height: 570px;
        margin: 7% auto;
        padding: 0 20px;
        background-color: #FFFFFF;
        border-radius: 15px;
        margin-top: 30px;
    }

    .login-page2 .text-center {
        margin-bottom: 10px;
    }
</style>


<?php include_once('layouts/header.php'); ?>
<div class="login-page2">
    <div class="text-center">
        <h3>Agregar nueva área</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="respuesta_solicitud.php?id=<?php echo (int)$solicitud['id_solicitudes_informacion']; ?>"  class="clearfix" enctype="multipart/form-data">
        <div class="form-group">
            <label for="area-name" class="control-label">Folio Solicitud</label>
            <input type="name" class="form-control" name="area-name" value="<?php echo ucwords($solicitud['folio_solicitud']); ?>" readonly >
        </div>
        <div class="form-group">
            <label for="fecha_respuesta">Fecha de Respuesta<span style="color:red;font-weight:bold">*</span></label><br>
            <input type="date" class="form-control" name="fecha_respuesta" required>
        </div> 
		<div class="form-group">
            <label for="adjunto">Acuerdo de Respuesta<span style="color:red;font-weight:bold">*</span></label>
			<input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto" required>
        </div>
        <br>
        <div class="form-group clearfix">
            <a href="solicitudes_ut.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Guardar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>