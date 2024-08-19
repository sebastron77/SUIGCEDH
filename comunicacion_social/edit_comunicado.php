<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Comunicado Prensa';
require_once('includes/load.php');

$comunicados = find_by_id('comunicados', (int)$_GET['id'], 'id_comunicados');

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 15) {
    page_require_level(15);
}
if ($nivel > 2 && $nivel < 7) :
    redirect('home.php');
endif;
if ($nivel >7  && $nivel < 15) :
    redirect('home.php');
endif;
if ($nivel > 15 ) :
    redirect('home.php');
endif;
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['update'])) {

    if (empty($errors)) {
	$id = (int)$comunicados['id_comunicados'];
        $fecha_publicacion = remove_junk($db->escape($_POST['fecha_publicacion']));
        $tipo_nota   = remove_junk($db->escape($_POST['tipo_nota']));
        $nombre_nota   = remove_junk($db->escape($_POST['nombre_nota']));
        $url_acceso   = remove_junk($db->escape($_POST['url_acceso']));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
		
         $sql = "UPDATE comunicados SET 
			fecha_publicacion='{$fecha_publicacion}', 
			tipo_nota='{$tipo_nota}', 
			nombre_nota='{$nombre_nota}', 
			url_acceso='{$url_acceso}', 
			observaciones='{$observaciones}'			
			WHERE id_comunicados='{$db->escape($id)}'";

        $result = $db->query($sql);
            if ($result && $db->affected_rows() === 1) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" edito un Comunicado de Folio: -' . $comunicados['folio'] , 2);
            $session->msg('s', " El comunicado con folio '" . $comunicados['folio'] . "' ha sido acuatizado con éxito.");
            redirect('comunicados_prensa.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron canmbios a la informacion.');
            redirect('edit_comunicado.php?id=' . (int)$comunicados['id_comunicados'], false);
        }
       
    } else {
        $session->msg("d", $errors);
        redirect('edit_comunicado.php', false);
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
                <span>Agregar Comunicado</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_comunicado.php?id=<?php echo (int)$comunicados['id_comunicados']; ?>">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_publicacion">Fecha de Publicación<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_publicacion" value="<?php echo ucwords($comunicados['fecha_publicacion']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tipo_nota">Tipo de Comunicado <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="tipo_nota" required>
                                <option value="">Escoge una opción</option>
                                    <option <?php if ($comunicados['tipo_nota'] === 'Noticia') echo 'selected="selected"'; ?>  value="Noticia">Noticia</option>
                                    <option <?php if ($comunicados['tipo_nota'] === 'Comunicado') echo 'selected="selected"'; ?>  value="Comunicado">Comunicado</option>
                                    <option <?php if ($comunicados['tipo_nota'] === 'Pronunciamiento') echo 'selected="selected"'; ?>  value="Pronunciamiento">Pronunciamiento</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_nota">Título del Comunicado<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="nombre_nota" placeholder="Título del Comunicado" value="<?php echo ucwords($comunicados['nombre_nota']); ?>" required>
                        </div>
                    </div>
                    
                </div>

                <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                            <label for="url_acceso">Link Acceso<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="url_acceso" placeholder="Link Acceso" value="<?php echo ucwords($comunicados['url_acceso']); ?>" required>
                        </div>
                    </div>                 

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label><br>
                            <textarea name="observaciones" class="form-control" id="observaciones" cols="50" rows="5"><?php echo ucwords($comunicados['observaciones']); ?></textarea>
                        </div>
                    </div>
                   
                </div>
               
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="comunicados_prensa.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="update" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>