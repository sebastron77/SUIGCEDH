<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
require_once('includes/load.php');
$page_title = 'Editar Artículo Inventario';
error_reporting(E_ALL ^ E_NOTICE);
$user = current_user();
$nivel_user = $user['user_level'];

$e_cat_inv = find_by_id('cat_categorias_inv', (int)$_GET['id'], 'id_categoria_inv');
$categoria_padre = find_all_order_by('cat_categorias_inv', 'descripcion', 'nivel', 2);

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}
if ($nivel_user == 2) {
    page_require_level_exacto(2);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
}
if ($nivel_user > 2 && $nivel_user < 14) :
    redirect('home.php');
endif;
if ($nivel_user > 14) {
    redirect('home.php');
}
if (!$nivel_user) {
    redirect('home.php');
}
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_articulos_inv'])) {

    if (empty($errors)) {

        $id_categoria_inv = $e_cat_inv['id_categoria_inv'];
        $padre = remove_junk($db->escape($_POST['padre']));
        $descripcion = remove_junk($db->escape($_POST['descripcion']));

        $sql = "UPDATE cat_categorias_inv SET padre = '{$padre}', descripcion = '{$descripcion}' WHERE id_categoria_inv = '{$db->escape($id_categoria_inv)}'";
        $result = $db->query($sql);

        if ($result && $db->affected_rows() === 1) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó el artículo del inventario: ' . $id_categoria_inv, 2);
            $session->msg('s', " El artículo del inventario ha sido actualizado con éxito.");
            redirect('edit_articulos_inv.php?id=' . (int)$e_cat_inv['id_categoria_inv'], false);
        } else {
            $session->msg('d', ' Lo sentimos, no se actualizaron los datos, debido a que no se realizaron cambios a la información.');
            redirect('edit_articulos_inv.php?id=' . (int)$e_cat_inv['id_categoria_inv'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_articulos_inv.php?id=' . (int)$e_cat_inv['id_categoria_inv'], false);
    }
}
?>

<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="login-page" style="height: 480px;">
    <div class="panel panel-heading">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th" style="font-size: 18px;"></span>
                <span style="font-size: 20px;">Editar Artículo Inventario</span>
            </strong>
        </div>

        <div class="panel-body">
            <form method="post" action="edit_articulos_inv.php?id=<?php echo (int)$e_cat_inv['id_categoria_inv']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="padre">Categoría</label><br><br>
                            <select class="form-control" name="padre">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($categoria_padre as $cat_padre) : ?>
                                    <option <?php if ($e_cat_inv['padre'] == $cat_padre['id_categoria_inv']) echo 'selected="selected"'; ?> value="<?php echo $cat_padre['id_categoria_inv']; ?>"><?php echo ucwords($cat_padre['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="descripcion">Nombre de la Categoría</label><br><br>
                            <input class="form-control" type="text" name="descripcion" value="<?php echo $e_cat_inv['descripcion']; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group clearfix">
                        <button type="submit" name="edit_articulos_inv" class="btn btn-primary" value="subir" style="margin-top: -20%; margin-left: 42%;">Guardar</button>
                        <a href="subcategorias_inv.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar" style="margin-top: 40%; margin-left: -60%;">
                            Regresar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>