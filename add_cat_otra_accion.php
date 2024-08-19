<?php
$page_title = 'Agregar Otra Acción';
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}

if ($nivel_user == 15) {
    page_require_level_exacto(15);
}
?>

<?php
if (isset($_POST['add'])) {

    validate_fields($req_fields);
    if (empty($errors)) {
        $name = remove_junk($db->escape(($_POST['otra_accion'])));
        $estatus = remove_junk($db->escape($_POST['estatus']));

        $query  = "INSERT INTO cat_otras_acciones (";
        $query .= "descripcion, estatus";
        $query .= ") VALUES (";
        $query .= " '{$name}', '{$estatus}'";
        $query .= ")";
        if ($db->query($query)) {
            //sucess
            $session->msg('s', "Tipo de Difusión creada. ");
            insertAccion($user['id_user'], '"' . $user['username'] . '" creo Otra Acción -' . $name . '-.', 1);
            redirect('add_cat_otra_accion.php', false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se pudo dar de alta Otra Acción!');
            redirect('add_cat_otra_accion.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_cat_otra_accion.php', false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Agregar Otra Acción</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="add_cat_otra_accion.php" class="clearfix">
        <div class="form-group">
            <label for="otra_accion" class="control-label">Nombre de la Otra Acción</label>
            <input type="name" class="form-control" name="otra_accion" required>

            <div class="form-group">
                <label for="estatus">Estatus</label>
                <select class="form-control" name="estatus">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
                </select>
            </div>
            <div class="form-group clearfix">
                <a href="cat_otra_accion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
                <button type="submit" name="add" class="btn btn-info">Actualizar</button>
            </div>
    </form>
</div>


<?php include_once('layouts/footer.php'); ?>