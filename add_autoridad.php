<?php
$page_title = 'Agregar autoridad';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}

if ($nivel_user == 50) {
    page_require_level_exacto(50);
}
?>
<?php

$tipos = find_all('tipo_autoridad');

if (isset($_POST['add_autoridad'])) {

    $req_fields = array('autoridad_name');
    validate_fields($req_fields);

    if (find_by_cargoName($_POST['autoridad_name']) === false) {
        $session->msg('d', '<b>¡Error!</b> El nombre de la autoridad ya existe en la base de datos');
        redirect('add_autoridad.php', false);
    }

    if (empty($errors)) {
        $name = remove_junk($db->escape($_POST['autoridad_name']));
        $tipo = remove_junk($db->escape($_POST['tipo_autoridad']));

        $query  = "INSERT INTO cat_autoridades (";
        $query .= "nombre_autoridad, tipo_autoridad";
        $query .= ") VALUES (";
        $query .= " '{$name}', '{$tipo}'";
        $query .= ")";
        if ($db->query($query)) {
            //sucess
            $session->msg('s', "Autoridad creada! ");
			insertAccion($user['id_user'], '"' . $user['username'] . '" agregó la Autoridad "'.$name.'".', 1);
            redirect('add_autoridad.php', false);
        } else {
            //failed
            $session->msg('d', 'Desafortunadamente no se pudo crear la autoridad.');
            redirect('add_autoridad.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_autoridad.php', false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3 style="margin-top: 20px; margin-bottom: 30px;">Agregar nueva autoridad</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="add_autoridad.php" class="clearfix">
        <div class="form-group">
            <label for="autoridad_name" class="control-label">Nombre de la autoridad</label>
            <input type="name" class="form-control" name="autoridad_name" required>
        </div>
        <div class="form-group">
            <label for="tipo_autoridad">Tipo de autoridad</label>
            <select class="form-control" name="tipo_autoridad">
                <?php foreach ($tipos as $tipo) : ?>
                    <option value="<?php echo $tipo['id_tipo_aut']; ?>"><?php echo $tipo['tipo']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group clearfix">
            <a href="autoridades.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="add_autoridad" class="btn btn-info">Guardar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>