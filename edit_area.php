<?php
$page_title = 'Editar Área';
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
$e_area = find_by_id('area', (int)$_GET['id'], 'id_area');
if (!$e_area) {
    $session->msg("d", "id del área no encontrado.");
    redirect('areas.php');
}
?>
<?php
if (isset($_POST['update'])) {

    $req_fields = array('area-name', 'abreviacion','estatus');
    validate_fields($req_fields);
    if (empty($errors)) {
        $name = remove_junk($db->escape($_POST['area-name']));
        $abreviacion = remove_junk($db->escape($_POST['abreviacion']));
        $estatus = $_POST['estatus'];

        $query  = "UPDATE area SET ";
        $query .= "nombre_area='{$name}', abreviatura='{$abreviacion}',estatus_area='{$estatus}'";
        $query .= "WHERE id_area='{$db->escape($e_area['id_area'])}'";
        $result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Área ha actualizada! ");
			insertAccion($user['id_user'], '"' . $user['username'] . '" editó el area ID('.$e_area['id_area'].') quedando "'.$name.'".', 2);
            redirect('edit_area.php?id=' . (int)$e_area['id_area'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el área!');
            redirect('edit_area.php?id=' . (int)$e_area['id_area'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_area.php?id=' . (int)$e_area['id_area'], false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Editar Área</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="edit_area.php?id=<?php echo (int)$e_area['id_area']; ?>" class="clearfix">
        <div class="form-group">
            <label for="area-name" class="control-label">Nombre del área</label>
            <input type="name" class="form-control" name="area-name" value="<?php echo ucwords($e_area['nombre_area']); ?>">
            <label for="abreviacion" class="control-label">Abreviación del área</label>
            <input type="name" class="form-control" name="abreviacion" value="<?php echo ucwords($e_area['abreviatura']); ?>">
        </div>
        <?php if ($e_area['id_area'] != '1'): ?>
            <div class="form-group">
                <label for="estatus">Estatus Área</label>
                <select class="form-control" name="estatus">
                    <option <?php if ($e_area['estatus_area'] === '1') echo 'selected="selected"'; ?> value="1"> Activo </option>
                    <option <?php if ($e_area['estatus_area'] === '0') echo 'selected="selected"'; ?> value="0">Inactivo</option>
                    <!-- <option <?php if ($e_group['estatus_area'] === '0') echo 'selected="selected"'; ?> value="0">Inactivo</option> -->
                </select>
            </div>
        <?php endif; ?>
        <div class="form-group clearfix">
            <a href="areas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>