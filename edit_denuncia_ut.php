<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Denuncia';
require_once('includes/load.php');


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
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_denuncia_ut'])) {

    if (empty($errors)) {
		
		$id = (int)$recurso['id_recursos_decuncias'];
        $folio_accion = remove_junk($db->escape($_POST['folio_accion']));
        $fecha_notificacion   = remove_junk($db->escape($_POST['fecha_notificacion']));
        $articulo_causal   = remove_junk($db->escape($_POST['articulo_causal']));
        
            $sql = "UPDATE recursos_decuncias SET 
			folio_accion='{$folio_accion}', 
			fecha_notificacion='{$fecha_notificacion}', 
			articulo_causal='{$articulo_causal}'			
			WHERE id_recursos_decuncias='{$db->escape($id)}'";
			
			
			$result = $db->query($sql);
				if ($result && $db->affected_rows() === 1) {
				insertAccion($user['id_user'], '"' . $user['username'] . '" edito la Denuncia('.$id.') con Folio: -' . $recurso['folio_accion'], 2);
				$session->msg('s', " La Denuncia con folio '" . $recurso['folio_accion'] . "' ha sido acuatizado con éxito.");
				redirect('denuncias_ut.php', false);
			} else {
				$session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron canmbios a la informacion.');
				redirect('edit_denuncia_ut.php?id=' . (int)$recurso['folio_accion'], false);
			}
       

    } else {
        $session->msg("d", $errors);
        redirect('edit_denuncia_ut.php', false);
    }
}
?>
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar Denuncia</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_denuncia_ut.php?id=<?php echo (int)$recurso['id_recursos_decuncias']; ?>" >
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_notificacion">Fecha de Notifiación<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_notificacion" value="<?php echo ucwords($recurso['fecha_notificacion']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="folio_accion">Folio Denuncia<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="folio_accion" placeholder="Folio Recurso" value="<?php echo ucwords($recurso['folio_accion']); ?>" required>
                        </div>
                    </div>
					 <div class="col-md-4">
                        <div class="form-group">
                            <label for="articulo_causal">Causal Artículo 53 de La Ley de Transparencia</label>
                            <textarea class="form-control" name="articulo_causal"  cols="10" rows="3" required><?php echo ucwords($recurso['articulo_causal']); ?></textarea>
                        </div>
                    </div>

                   
                </div>

                
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="denuncias_ut.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="edit_denuncia_ut" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>