<?php
$page_title = 'Editar Área del Conocimiento';
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}

if ($nivel_user == 50) {
    page_require_level_exacto(50);
}

$e_area_con = find_by_id_ar_con((int)$_GET['id']);
$escolaridades = find_all('cat_escolaridad');
$area_con = find_all('cat_area_conocimiento');
if (!$e_area_con) {
    $session->msg("d", "La información no existe, verifique el ID.");
    redirect('edit_area_con.php?id=' . (int)$e_area_con['id_rel_area_con']);
}
?>
<?php
if (isset($_POST['edit_area_con'])) {
    if (empty($errors)) {
        $tipo_area = remove_junk($db->escape(($_POST['tipo_area'])));
        $nombre_carrera = remove_junk($db->escape(($_POST['nombre_carrera'])));
        $especialidad = remove_junk($db->escape(($_POST['especialidad'])));

        $query  = "UPDATE rel_area_conocimiento SET ";
        $query .= "tipo_area='{$tipo_area}', nombre_carrera='{$nombre_carrera}', especialidad='{$especialidad}' ";
        $query .= "WHERE id_rel_area_con='{$db->escape($e_area_con['id_rel_area_con'])}'";

        $result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Área del conocimiento ha sido actualizado.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" edito área del conocimiento (id:' . (int)$e_area_con['id_rel_area_con'] . ').', 2);
            redirect('edit_area_con.php?id=' . (int)$e_area_con['id_rel_area_con'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el área del conocimiento, debido a que no hay cambios registrados.');
            redirect('edit_area_con.php?id=' . (int)$e_area_con['id_rel_area_con'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_area_con.php?id=' . (int)$e_area_con['id_rel_area_con'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar Área del Conocimiento: <?php echo $e_area_con['nombre'] . " " . $e_area_con['apellidos'] ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_area_con.php?id=<?php echo (int) $e_area_con['id_rel_area_con']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tipo_area">Tipo de Área</label>
                            <select class="form-control" name="tipo_area">
                                <?php foreach ($area_con as $area) : ?>
                                    <option <?php if ($area['id_cat_area_con'] === $e_area_con['tipo_area']) echo 'selected="selected"'; ?> value="<?php echo $area['id_cat_area_con']; ?>"><?php echo ucwords($area['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_carrera">Nombre Carrera</label>
                            <input type="text" class="form-control" name="nombre_carrera" value="<?php echo remove_junk($e_area_con['nombre_carrera']); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="especialidad">Especialidad</label>
                            <input type="text" class="form-control" name="especialidad" value="<?php echo remove_junk($e_area_con['especialidad']); ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="exp_ac_lab.php?id=<?php echo $e_area_con['id_detalle_usuario']; ?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_area_con" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>