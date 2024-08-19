
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Contacto';
require_once('includes/load.php');


$user = current_user();
$id_user = $user['id_user'];
$generos = find_all('cat_genero');
$autoridades = find_all('cat_autoridades');
$contacto = find_by_id('contactos_politicas', (int)$_GET['id'], 'id_contactos_politicas');
?>

<?php
if (isset($_POST['edit_contacto_monitoreo_politicas'])) {
    if (empty($errors)) {
	$id = (int)$contacto['id_contactos_politicas'];
        $nombres = remove_junk($db->escape($_POST['nombres']));
        $apellidos = remove_junk($db->escape($_POST['apellidos']));
        $id_cat_gen = remove_junk($db->escape($_POST['id_cat_gen']));
        $cargo_desempelado = remove_junk($db->escape($_POST['cargo_desempelado']));
        $telefono = remove_junk($db->escape($_POST['telefono']));
        $email = remove_junk($db->escape($_POST['email']));
        $id_cat_aut = remove_junk($db->escape($_POST['id_cat_aut']));

		$sql = "UPDATE contactos_politicas SET 
			nombres='{$nombres}', 
			apellidos='{$apellidos}', 
			id_cat_gen='{$id_cat_gen}', 
			cargo_desempelado='{$cargo_desempelado}', 
			telefono='{$telefono}', 
			email='{$email}', 
			id_cat_aut='{$id_cat_aut}'			
			WHERE id_contactos_politicas='{$db->escape($id)}'";
			
        $result = $db->query($sql);
            if ($result && $db->affected_rows() === 1) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" edito los Datos de Contacto en Politicas Públicas,ID:' . $contacto['id_contactos_politicas'] , 2);
            $session->msg('s', " El Contacto en Politicas Públicas con ID '" . $contacto['id_contactos_politicas'] . "' ha sido acuatizado con éxito.");
            redirect('directorio_monitoreo_politicas.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron canmbios a la informacion.');
            redirect('edit_contacto_monitoreo_politicas.php?id=' . (int)$contacto['id_contactos_politicas'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_contacto_monitoreo_politicas.php?id='.(int)$contacto['id_contactos_politicas'], false);
    }
}
?>

<?php
header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php');
?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar Contacto</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_contacto_monitoreo_politicas.php?id=<?php echo (int)($contacto['id_contactos_politicas']); ?>">                
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombres">Nombre</label>
                            <input type="text" class="form-control" name="nombres" placeholder="Nombre(s)" value="<?php echo ucwords($contacto['nombres']); ?>"  required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="apellidos">Apellidos</label>
                            <input type="text" class="form-control" name="apellidos" placeholder="Apellidos" value="<?php echo ucwords($contacto['apellidos']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="id_cat_gen">Género</label>
                            <select class="form-control" name="id_cat_gen" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($generos as $genero) : ?>
                                    <option  <?php if ($contacto['id_cat_gen'] === $genero['id_cat_gen']) echo 'selected="selected"'; ?>  value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>                   
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" maxlength="10" name="telefono" value="<?php echo ucwords($contacto['telefono']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" name="email" value="<?php echo ($contacto['email']); ?>" required>
                        </div>
                    </div>
                </div>
                <div class="row">
				
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cargo_desempelado">Cargo</label>
                            <input type="text" class="form-control" name="cargo_desempelado" value="<?php echo ucwords($contacto['cargo_desempelado']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_aut">Dependencia</label>
                            <select class="form-control" name="id_cat_aut" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($autoridades as $datos) : ?>
                                    <option  <?php if ($contacto['id_cat_aut'] === $datos['id_cat_aut']) echo 'selected="selected"'; ?>  value="<?php echo $datos['id_cat_aut']; ?>"><?php echo ucwords($datos['nombre_autoridad']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                                    
                   
                </div>

                <div class="form-group clearfix">
                    <a href="directorio_monitoreo_politicas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_contacto_monitoreo_politicas" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include_once('layouts/footer.php'); ?>