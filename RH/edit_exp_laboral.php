<?php
$page_title = 'Editar Experiencia Laboral';
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}

if ($nivel_user == 50) {
    page_require_level_exacto(50);
}

$e_laboral = find_by_id('rel_curriculum_laboral',(int)$_GET['id'],'id_rel_cur_lab');
$detalle = find_by_id('detalles_usuario',$e_laboral['id_detalle_usuario'],'id_det_usuario');
if (!$e_laboral) {
    $session->msg("d", "La información no existe, verifique el ID.");
    redirect('edit_exp_laboral.php?id=' . (int)$e_laboral['id_rel_cur_lab']);
}
?>
<?php
if (isset($_POST['edit_exp_laboral'])) {

    if (empty($errors)) {
        $puesto = remove_junk($db->escape(($_POST['puesto'])));
        $institucion = remove_junk($db->escape(($_POST['institucion'])));
        $inicio = remove_junk($db->escape(($_POST['inicio'])));
        $conclusion = remove_junk($db->escape(($_POST['conclusion'])));

        $query  = "UPDATE rel_curriculum_laboral SET ";
        $query .= "puesto='{$puesto}', institucion='{$institucion}', inicio='{$inicio}', conclusion='{$conclusion}' ";
        $query .= "WHERE id_rel_cur_lab='{$db->escape($e_laboral['id_rel_cur_lab'])}'";
        $result = $db->query($query);

        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Expediente laboral ha sido actualizado.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" edito expediente laboral ' . $name . '(id:' . (int)$e_laboral['id_rel_cur_lab'] . ').', 2);
            redirect('edit_exp_laboral.php?id=' . (int)$e_laboral['id_rel_cur_lab'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el expediente laboral, debido a que no hay cambios registrados en la descripción.');
            redirect('edit_exp_laboral.php?id=' . (int)$e_laboral['id_rel_cur_lab'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_exp_laboral.php?id=' . (int)$e_laboral['id_rel_cur_lab'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
<div class="row login-page6" style="margin-left: 30%; margin-top: 5%;">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th" style="font-size: 17px"></span>
                <span style="font-size: 16px">Editar Expediente Laboral: <?php echo $detalle['nombre'] . " " . $detalle['apellidos'] ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_exp_laboral.php?id=<?php echo (int) $e_laboral['id_rel_cur_lab']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="puesto">Puesto</label>
                            <input type="text" class="form-control" name="puesto" value="<?php echo remove_junk($e_laboral['puesto']); ?>">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="institucion">Institución</label>
                            <input type="text" class="form-control" name="institucion" value="<?php echo remove_junk($e_laboral['institucion']); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inicio">Fecha de inicio</label>
                            <input type="date" class="form-control" name="inicio" value="<?php echo remove_junk($e_laboral['inicio']); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="conclusion">Fecha de Conclusión</label>
                            <input type="date" class="form-control" name="conclusion" value="<?php echo remove_junk($e_laboral['conclusion']); ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="exp_laboral.php?id=<?php echo $e_laboral['id_detalle_usuario'];?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_exp_laboral" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    
</div>

<?php include_once('layouts/footer.php'); ?>