<?php
$page_title = 'Editar Hecho Vulnerado';
require_once('includes/load.php');
$user = current_user();
page_require_level(15);
?>
<?php
$a_catalogo = find_by_id('cat_tipo_difusion', (int)$_GET['id'], 'id_cat_tipo_dif');
if (!$a_catalogo) {
    $session->msg("d", "El Tipos de Difusión no existe, verifique el ID.");
    redirect('cat_tipo_difusion.php');
}
?>
<?php
if (isset($_POST['update'])) {

    $req_fields = array('descripcion');
    validate_fields($req_fields);
    if (empty($errors)) {
        $name = remove_junk($db->escape(($_POST['descripcion'])));

        $estatus = $_POST['estatus'];

        $query  = "UPDATE cat_tipo_difusion SET ";
        $query .= "descripcion='{$name}' ";
        $query .= "WHERE id_cat_tipo_dif='{$db->escape($a_catalogo['id_cat_tipo_dif'])}'";

        $result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Tipos de Difusión actualizado! '" . ($name) . "'");
            insertAccion($user['id_user'], '"' . $user['username'] . '" edito el Tipos de Difusión ' . $name . '(id:' . (int)$a_catalogo['id_cat_tipo_dif'] . ').', 2);
            redirect('edit_tipo_difusion.php?id=' . (int)$a_catalogo['id_cat_tipo_dif'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el Tipos de Difusión!');
            redirect('edit_tipo_difusion.php?id=' . (int)$a_catalogo['id_cat_tipo_dif'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_tipo_difusion.php?id=' . (int)$a_catalogo['id_cat_tipo_dif'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Editar cat_tipo_difusion</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="edit_tipo_difusion.php?id=<?php echo (int)$a_catalogo['id_cat_tipo_dif']; ?>" class="clearfix">
        <div class="form-group">
            <label for="area-name" class="control-label">Nombre del Tipo de Difusión</label>
            <input type="name" class="form-control" name="descripcion" value="<?php echo ucwords($a_catalogo['descripcion']); ?>" required>

        </div>
        <div class="form-group clearfix">
            <a href="cat_tipo_difusion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>