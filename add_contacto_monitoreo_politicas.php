
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Contacto';
require_once('includes/load.php');


$user = current_user();
$id_user = $user['id_user'];
$generos = find_all('cat_genero');
$autoridades = find_all('cat_autoridades');
?>

<?php
if (isset($_POST['add_contacto_monitoreo_politicas'])) {
    if (empty($errors)) {

        $nombres = remove_junk($db->escape($_POST['nombres']));
        $apellidos = remove_junk($db->escape($_POST['apellidos']));
        $id_cat_gen = remove_junk($db->escape($_POST['id_cat_gen']));
        $cargo_desempelado = remove_junk($db->escape($_POST['cargo_desempelado']));
        $telefono = remove_junk($db->escape($_POST['telefono']));
        $email = remove_junk($db->escape($_POST['email']));
        $id_cat_aut = remove_junk($db->escape($_POST['id_cat_aut']));

        $query = "INSERT INTO contactos_politicas (";
        $query .= "nombres, apellidos, id_cat_gen, cargo_desempelado, telefono, email, id_cat_aut, id_user_creador,  fecha_creacion";	
        $query .= ") VALUES (";
        $query .= "'{$nombres}', '{$apellidos}', '{$id_cat_gen}', '{$cargo_desempelado}', '{$telefono}', '{$email}', '{$id_cat_aut}', '{$id_user}', NOW()";		
        $query .= ")";

        if ($db->query($query)) {
            $session->msg('s', " El/la Contacto ha sido agregado con éxito.");
            insertAccion($user['id_user'], '"'.$user['username'].'" agregó registro de Contacto en Politicas Públicas.', 1);
            redirect('directorio_monitoreo_politicas.php', false);
        } else {
            $session->msg('d', ' No se pudo agregar el/la Contacto .');
            redirect('add_contacto_monitoreo_politicas.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_contacto_monitoreo_politicas.php', false);
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
                <span>Agregar Contacto</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_contacto_monitoreo_politicas.php">                
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombres">Nombre</label>
                            <input type="text" class="form-control" name="nombres" placeholder="Nombre(s)" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="apellidos">Apellidos</label>
                            <input type="text" class="form-control" name="apellidos" placeholder="Apellidos" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="id_cat_gen">Género</label>
                            <select class="form-control" name="id_cat_gen" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($generos as $genero) : ?>
                                    <option value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>                   
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" maxlength="10" name="telefono" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" name="email" required>
                        </div>
                    </div>
                </div>
                <div class="row">
				
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cargo_desempelado">Cargo</label>
                            <input type="text" class="form-control" name="cargo_desempelado" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_aut">Dependencia</label>
                            <select class="form-control" name="id_cat_aut" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($autoridades as $datos) : ?>
                                    <option value="<?php echo $datos['id_cat_aut']; ?>"><?php echo ucwords($datos['nombre_autoridad']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                                    
                   
                </div>

                <div class="form-group clearfix">
                    <a href="directorio_monitoreo_politicas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_contacto_monitoreo_politicas" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include_once('layouts/footer.php'); ?>