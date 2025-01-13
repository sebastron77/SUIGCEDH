<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
require_once('includes/load.php');
$page_title = 'Editar Salida del Inventario';
error_reporting(E_ALL ^ E_NOTICE);
$user = current_user();
$nivel_user = $user['user_level'];

$desglose_articulos = find_by_id_edit_salidas_inv((int)$_GET['id']);
$areas = find_all_order('area', 'nombre_area');
$categorias_articulos = find_all_order('cat_categorias_inv', 'descripcion');

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

if (isset($_POST['edit_salida_inv'])) {

    if (empty($errors)) {

        $id_rel_salida_inv = $desglose_articulos['id_rel_salida_inv'];
        $id_area_asigna = remove_junk($db->escape($_POST['id_area_asigna']));
        $fecha_salida = remove_junk($db->escape($_POST['fecha_salida']));

        $sql = "UPDATE rel_salidas_inv SET id_area_asigna = '{$id_area_asigna}', fecha_salida = '{$fecha_salida}' 
                WHERE id_rel_salida_inv = '{$db->escape($id_rel_salida_inv)}'";
        $result = $db->query($sql);

        if ($result && $db->affected_rows() === 1) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó la salida del artículo del inventario: ' . $id_rel_salida_inv, 2);
            $session->msg('s', " La salida del artículo del inventario ha sido actualizada con éxito.");
            redirect('edit_salida_inv.php?id=' . (int)$desglose_articulos['id_rel_salida_inv'], false);
        } else {
            $session->msg('d', ' Lo sentimos, no se actualizaron los datos debido a que no se realizaron cambios a la información.');
            redirect('edit_salida_inv.php?id=' . (int)$desglose_articulos['id_rel_salida_inv'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_salida_inv.php?id=' . (int)$desglose_articulos['id_rel_salida_inv'], false);
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
                <span>Editar Salida del Inventario</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_salida_inv.php?id=<?php echo (int)$desglose_articulos['id_rel_salida_inv']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_categoria_inv">Categoría del Artículo</label>
                            <select class="form-control" id="id_categoria_inv" name="id_categoria_inv" disabled>
                                <?php foreach ($categorias_articulos as $c_articulo) : ?>
                                    <option <?php if ($desglose_articulos['padre'] == $c_articulo['id_categoria_inv']) echo 'selected="selected"'; ?> value="<?php echo $c_articulo['id_categoria_inv']; ?>"><?php echo ucwords($c_articulo['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_categoria_inv2">Subcategoría del Artículo / Artículo</label>
                            <select class="form-control" id="id_categoria_inv2" name="id_categoria_inv2" disabled>
                                <?php foreach ($categorias_articulos as $c_articulo) : ?>
                                    <option <?php if ($desglose_articulos['subpadre'] == $c_articulo['id_categoria_inv']) echo 'selected="selected"'; ?> value="<?php echo $c_articulo['id_categoria_inv']; ?>"><?php echo ucwords($c_articulo['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_categoria_inv3">Artículo</label>
                            <select class="form-control" id="id_categoria_inv3" name="id_categoria_inv3" disabled>
                                <?php foreach ($categorias_articulos as $c_articulo) : ?>
                                    <option <?php if ($desglose_articulos['id_categoria_inv'] == $c_articulo['id_categoria_inv']) echo 'selected="selected"'; ?> value="<?php echo $c_articulo['id_categoria_inv']; ?>"><?php echo ucwords($c_articulo['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cantidad_salida">Cantidad de Salida</label>
                            <input type="number" value='<?php echo $desglose_articulos['cantidad_salida']; ?>' class="form-control" name="cantidad_salida" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area_asigna">Área a la que se asigna</label>
                            <select class="form-control" id="id_area_asigna" name="id_area_asigna" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($areas as $area) : ?>
                                    <option <?php if ($desglose_articulos['id_area_asigna'] == $area['id_area']) echo 'selected="selected"'; ?> value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_salida">Fecha de asignación</label>
                            <input type="date" class="form-control" name="fecha_salida" value="<?php echo $desglose_articulos['fecha_salida']?>">
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="salidas_inv.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_salida_inv" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>