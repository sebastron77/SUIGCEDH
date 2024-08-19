<?php
$page_title = 'Agregar cargo';
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
$areas = find_all('area');
?>
<?php
if (isset($_POST['add'])) {

    $req_fields = array('cargo-name');
    validate_fields($req_fields);

    // if (find_by_cargoName($_POST['cargo-name']) === false) {
    //     $session->msg('d', '<b>Error!</b> El nombre del cargo realmente existe en la base de datos');
    //     redirect('add_cargo.php', false);
    // }

    if (empty($errors)) {
        $name = remove_junk($db->escape($_POST['cargo-name']));
        $area = remove_junk($db->escape($_POST['area']));

        $query  = "INSERT INTO cargos (";
        $query .= "nombre_cargo, id_area, estatus_cargo";
        $query .= ") VALUES (";
        $query .= " '{$name}', '{$area}', 1";
        $query .= ")";
        if ($db->query($query)) {
            //sucess
            $session->msg('s', "Cargo creado! ");
            redirect('add_cargo.php', false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se pudo crear el cargo!');
            redirect('add_cargo.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_cargo.php', false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Agregar nuevo cargo</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="add_cargo.php" class="clearfix">
        <div class="form-group">
            <label for="cargo-name" class="control-label">Nombre del cargo</label>
            <input type="name" class="form-control" name="cargo-name" required>
        </div>
        <div class="form-group">
            <label for="level">Área</label>
            <select class="form-control" name="area">
                <?php foreach ($areas as $area) : ?>
                    <option value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group clearfix">
            <a href="cargos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="add" class="btn btn-info">Guardar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>