<?php
$page_title = 'Editar Cargo';
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
    page_require_level_exacto(1);
	$acceso_permitido=true;
}
if ($nivel_user == 2) {
    page_require_level_exacto(2);
	$acceso_permitido=true;
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
	$acceso_permitido=true;
}


if ($nivel_user > 2 && $nivel_user < 7):
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 14):
    redirect('home.php');
endif;
?>
<?php
$areas = find_all('area');
$e_cargo = find_by_id('cargos', (int)$_GET['id'], 'id_cargos');
if (!$e_cargo) {
    $session->msg("d", "id del cargo no encontrado.");
    redirect('cargos.php');
}
?>
<?php
if (isset($_POST['update'])) {

    $req_fields = array('cargo-name', 'area');
    validate_fields($req_fields);
    if (empty($errors)) {
        $name = remove_junk($db->escape($_POST['cargo-name']));
        $area = $_POST['area'];

        $query  = "UPDATE cargos SET ";
        $query .= "nombre_cargo='{$name}',id_area='{$area}' ";
        $query .= "WHERE id_cargos='{$db->escape($e_cargo['id_cargos'])}'";
        $result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Cargo actualizado! ");
            redirect('edit_cargo.php?id=' . (int)$e_cargo['id_cargos'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el cargo!');
            redirect('edit_cargo.php?id=' . (int)$e_cargo['id_cargos'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_cargo.php?id=' . (int)$e_cargo['id_cargos'], false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Editar Cargo</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="edit_cargo.php?id=<?php echo (int)$e_cargo['id_cargos']; ?>" class="clearfix">
        <div class="form-group">
            <label for="cargo-name" class="control-label">Nombre del cargo</label>
            <input type="name" class="form-control" name="cargo-name" value="<?php echo ucwords($e_cargo['nombre_cargo']); ?>">
        </div>

        <div class="form-group">
            <label for="area">Área</label>
            <select class="form-control" name="area">
                <?php foreach ($areas as $area) : ?>
                    <option <?php if ($area['id_area'] === $e_cargo['id_area']) echo 'selected="selected"'; ?> value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group clearfix">
            <a href="cargos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>