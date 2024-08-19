<?php
$page_title = 'Resolución a Recurso';
require_once('includes/load.php');
$areas = find_all('area');

$recurso = find_by_id('recursos_decuncias', (int)$_GET['id'], 'id_recursos_decuncias');

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
		$id = (int)$recurso['id_recursos_decuncias'];			
        $fecha_resolucion   = remove_junk($db->escape($_POST['fecha_resolucion']));
        $sentido_resolucion   = remove_junk($db->escape($_POST['sentido_resolucion']));
		
		
            $sql = "UPDATE recursos_decuncias SET 
			tramite=false, 
			fecha_resolucion='{$fecha_resolucion}', 
			sentido_resolucion='{$sentido_resolucion}' 			
			WHERE id_recursos_decuncias='{$db->escape($id)}'";
			
			
			$result = $db->query($sql);
				if ($result && $db->affected_rows() === 1) {
				insertAccion($user['id_user'], '"' . $user['username'] . '" dió resolución al Recurso de Revisión('.$id.') de Folio: -' . $recurso['folio_accion'].'-', 2);
				$session->msg('s', "Al Recurso de Revisión con folio '" . $recurso['folio_accion'] . "' se le ha dado Resolución con éxito.");
				redirect('recursos_ut.php', false);
			} else {
				$session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron canmbios a la informacion.');
				redirect('recursos_ut.php', false);
			
			}
		    
       

    } else {
        $session->msg("d", $errors);
        redirect('recursos_ut.php', false);
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
    <form method="post" action="resolucion_recurso.php?id=<?php echo (int)$recurso['id_recursos_decuncias']; ?>"  class="clearfix" enctype="multipart/form-data">
        <div class="form-group">
            <label for="area-name" class="control-label">Folio Recurso</label>
            <input type="name" class="form-control" name="area-name" value="<?php echo ucwords($recurso['folio_accion']); ?>" readonly >
        </div>
        <div class="form-group">
            <label for="fecha_resolucion">Fecha de Resolución<span style="color:red;font-weight:bold">*</span></label><br>
            <input type="date" class="form-control" name="fecha_resolucion" required>
        </div> 
		<div class="form-group">
            <label for="level">Sentido de la Resolución</label>
            <select class="form-control" name="sentido_resolucion">              
                <option value="">Seleccione un Opción</option>
                <option value="SOBRESEÍDA">SOBRESEÍDA</option>
                <option value="FUNDADA">FUNDADA</option>
                <option value="INFUNDADA">INFUNDADA</option>
                <option value="PARCIALMENTE FUNDADA">PARCIALMENTE FUNDADA</option>
            </select>
        </div>
        <br>
        <div class="form-group clearfix">
            <a href="recursos_ut.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Guardar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>