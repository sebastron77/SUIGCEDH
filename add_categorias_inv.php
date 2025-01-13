<?php
$page_title = 'Agregar Categoría Inventario';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

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
<?php
if (isset($_POST['add_categorias_inv'])) {

    $descripcion = remove_junk($db->escape($_POST['descripcion']));

    $query  = "INSERT INTO cat_categorias_inv (";
    $query .= "padre, descripcion, nivel, estatus";
    $query .= ") VALUES (";
    $query .= " 0, '{$descripcion}', 1, 1";
    $query .= ")";
    if ($db->query($query)) {
        //sucess
        $session->msg('s', "Categoría del inventario creada con éxito! ");
		insertAccion($user['id_user'], '"' . $user['username'] . '" creó categoría padre en inventario (' . $descripcion . ').', 1);
        redirect('add_categorias_inv.php', false);
    } else {
        //failed
        $session->msg('d', 'Desafortunadamente no se pudo crear el registro.');
        redirect('add_categorias_inv.php', false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page" style="height: 480px;">
    <div class="text-center">
        <h2 style="margin-top: 20px; margin-bottom: 30px; color: #3a3d44">Agregar Categoría de Inventario</h2>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="add_categorias_inv.php" class="clearfix">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="descripcion">Nombre de la Categoría</label><br><br>
                    <input class="form-control" type="text" name="descripcion">
                </div>
            </div>
        </div>

        <div class="form-group clearfix">
            <button type="submit" name="add_categorias_inv" class="btn btn-info" style="margin-top: -20%; margin-left: 42%;">Guardar</button>
            <a href="categorias_inv.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar" style="margin-top: 40%; margin-left: -60%;">
                Regresar
            </a>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>