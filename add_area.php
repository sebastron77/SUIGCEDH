<?php
$page_title = 'Agregar área';
require_once('includes/load.php');
$areas = find_all('area');

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
if (isset($_POST['add'])) {

    $req_fields = array('area-name');
    validate_fields($req_fields);

    if (find_by_areaName($_POST['area-name']) === false) {
        $session->msg('d', '<b>Error!</b> El nombre de área realmente existe en la base de datos');
        redirect('add_area.php', false);
    }

    if (empty($errors)) {
        $name = remove_junk($db->escape($_POST['area-name']));
        $abreviatura = remove_junk($db->escape($_POST['abreviatura']));
        $estatus = remove_junk($db->escape($_POST['estatus']));

        $query  = "INSERT INTO area (";
        $query .= "nombre_area, abreviatura,estatus_area";
        $query .= ") VALUES (";
        $query .= " '{$name}', '{$abreviatura}','{$estatus}'";
        $query .= ")";
        if ($db->query($query)) {
            //sucess
            $session->msg('s', "Área creada! ");
			insertAccion($user['id_user'], '"' . $user['username'] . '" agregó el área ' . $name . '.', 1);
            redirect('add_area.php', false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se pudo crear el área!');
            redirect('add_area.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_area.php', false);
    }
}
?>


<style>
    .login-page2 {
        width: 350px;
        height: 470px;
        margin: 7% auto;
        padding: 0 20px;
        background-color: #FFFFFF;
        border-radius: 15px;
        margin-top: 30px;
    }

    .login-page2 .text-center {
        margin-bottom: 10px;
    }
</style>


<?php include_once('layouts/header.php'); ?>
<div class="login-page2">
    <div class="text-center">
        <h3>Agregar nueva área</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="add_area.php" class="clearfix">
        <div class="form-group">
            <label for="area-name" class="control-label">Nombre del área</label>
            <input type="name" class="form-control" name="area-name" required>
        </div>
        <div class="form-group">
            <label for="abreviatura" class="control-label">Abreviación de nombre del área</label>
            <input type="name" class="form-control" name="abreviatura" required>
        </div>
        <div class="form-group">
            <label for="estatus">Área Padre</label>
            <select class="form-control" name="estatus">
                <?php foreach ($areas as $area) : ?>
                    <option value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="form-group">
            <label for="estatus">Estatus</label>
            <select class="form-control" name="estatus">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        <div class="form-group clearfix">
            <a href="areas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="add" class="btn btn-info">Guardar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>