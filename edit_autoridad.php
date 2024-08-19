<?php
$page_title = 'Editar Autoridad';
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
$e_autoridad = find_by_id('cat_autoridades', (int)$_GET['id'], 'id_cat_aut');
if (!$e_autoridad) {
    $session->msg("d", "id de la autoridad no encontrado.");
    redirect('autoridades.php');
}
?>
<?php
if (isset($_POST['update'])) {

    $req_fields = array('autoridad-name');
    validate_fields($req_fields);
    if (empty($errors)) {
        $name = remove_junk($db->escape($_POST['autoridad-name']));
        $tipo_autoridad = $_POST['tipo_autoridad'];

        $query  = "UPDATE cat_autoridades SET ";
        $query .= "nombre_autoridad='{$name}',tipo_autoridad='{$tipo_autoridad}' ";
        $query .= "WHERE id_cat_aut='{$db->escape($e_autoridad['id_cat_aut'])}'";
        $result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Autoridad actualizada. ");
			insertAccion($user['id_user'], '"' . $user['username'] . '" editó la autoridad ID('.$e_autoridad['id_cat_aut'].') quedando "'.$name.'".', 2);
            redirect('edit_autoridad.php?id=' . (int)$e_autoridad['id_cat_aut'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado la autoridad!');
            redirect('edit_autoridad.php?id=' . (int)$e_autoridad['id_cat_aut'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_autoridad.php?id=' . (int)$e_autoridad['id_cat_aut'], false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Editar Autoridad</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="edit_autoridad.php?id=<?php echo (int)$e_autoridad['id_cat_aut']; ?>" class="clearfix">
        <div class="form-group">
            <label for="autoridad-name" class="control-label">Nombre de la autoridad</label>
            <input type="name" class="form-control" name="autoridad-name" value="<?php echo ucwords($e_autoridad['nombre_autoridad']); ?>">
        </div>

        <div class="form-group">
            <label for="tipo_autoridad">Área</label>
            <select class="form-control" name="tipo_autoridad">
                <?php foreach ($tipos as $tipo) : ?>
                    <option <?php if ($tipo['id_tipo_aut'] === $e_autoridad['tipo_autoridad']) echo 'selected="selected"'; ?> value="<?php echo $tipo['id_tipo_aut']; ?>"><?php echo ucwords($tipo['tipo']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group clearfix">
            <a href="autoridades.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>