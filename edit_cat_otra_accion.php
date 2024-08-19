<?php
$page_title = 'Editar Otras Acciones';
require_once('includes/load.php');
$user = current_user();
page_require_level(15);
?>
<?php
$a_catalogo = find_by_id('cat_otras_acciones', (int)$_GET['id'], 'id_cat_otra_accion');
if (!$a_catalogo) {
    $session->msg("d", "La Otra Acción no existe, verifique el ID.");
    redirect('cat_otra_accion.php');
}
?>
<?php
if (isset($_POST['update'])) {

    if (empty($errors)) {
        $name = remove_junk($db->escape(($_POST['descripcion'])));

        $estatus = $_POST['estatus'];

        $query  = "UPDATE cat_otras_acciones SET ";
        $query .= "descripcion='{$name}' ";
        $query .= "WHERE id_cat_otra_accion='{$db->escape($a_catalogo['id_cat_otra_accion'])}'";

        $result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Otras Acciones actualizado! '" . ($name) . "'");
            insertAccion($user['id_user'], '"' . $user['username'] . '" edito Otras Acciones ' . $name . '(id:' . (int)$a_catalogo['id_cat_tipo_dif'] . ').', 2);
            redirect('edit_cat_otra_accion.php?id=' . (int)$a_catalogo['id_cat_otra_accion'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado Otras Acciones!');
            redirect('edit_cat_otra_accion.php?id=' . (int)$a_catalogo['id_cat_otra_accion'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_cat_otra_accion.php?id=' . (int)$a_catalogo['id_cat_otra_accion'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Editar Otras Acciones</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="edit_cat_otra_accion.php?id=<?php echo (int)$a_catalogo['id_cat_otra_accion']; ?>" class="clearfix">
        <div class="form-group">
            <label for="descripcion" class="control-label">Nombre de la Otra Acción</label>
            <input type="name" class="form-control" name="descripcion" value="<?php echo ucwords($a_catalogo['descripcion']); ?>">

        </div>
        <div class="form-group clearfix">
            <a href="cat_otra_accion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>


<?php include_once('layouts/footer.php'); ?>