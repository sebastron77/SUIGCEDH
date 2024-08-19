<script type="text/javascript" src="libs/js/paciente.js"></script>
<?php
$page_title = 'Editar Sesión';
require_once('includes/load.php');
?>
<?php
$e_detalle = find_by_id('sesiones', (int) $_GET['id'], 'id_sesion');
if (!$e_detalle) {
    redirect('sesiones.php');
}
$user = current_user();
$nivel = $user['user_level'];
$pacientes = find_all('paciente');

?>

<?php
if (isset($_POST['edit_sesion'])) {
    $id = (int) $e_detalle['id_sesion'];
    $id_paciente   = remove_junk($db->escape($_POST['id_paciente']));
    $fecha_atencion = remove_junk($db->escape($_POST['fecha_atencion']));
    $estatus   = remove_junk($db->escape($_POST['estatus']));
    $nota_sesion   = remove_junk($db->escape($_POST['nota_sesion']));
    $atendio   = remove_junk($db->escape($_POST['atendio']));
    $no_sesion   = remove_junk($db->escape($_POST['no_sesion']));
    date_default_timezone_set('America/Mexico_City');
    $fecha_actualizacion = date('Y-m-d H:i:s');

    $sql = "UPDATE sesiones SET id_paciente='$id_paciente', fecha_atencion='{$fecha_atencion}', estatus='{$estatus}', nota_sesion='{$nota_sesion}', atendio='{$atendio}', no_sesion='{$no_sesion}' ";
    $sql .= " WHERE id_sesion='{$db->escape($id)}'";

    $result = $db->query($sql);

    if ($result && $db->affected_rows() === 1) {
        $session->msg('s', "Información Actualizada ");
        insertAccion($user['id_user'], '"' . $user['username'] . '" editó registro de sesión.', 2);
        redirect('edit_sesion.php?id=' . (int) $e_detalle['id_sesion'], false);
    } else {
        $session->msg('d', ' Lo siento no se actualizaron los datos.');
        redirect('edit_sesion.php?id=' . (int) $e_detalle['id_sesion'], false);
    }
}

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar queja
                    <?php echo $e_detalle['folio'] ?>
                </span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_sesion.php?id=<?php echo (int) $e_detalle['id_sesion']; ?>" enctype="multipart/form-data">
                <h3 style="font-weight:bold;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#3a3d44" width="25px" height="25px" viewBox="0 0 24 24">
                        <title>cog</title>
                        <path d="M12,15.5A3.5,3.5 0 0,1 8.5,12A3.5,3.5 0 0,1 12,8.5A3.5,3.5 0 0,1 15.5,12A3.5,3.5 0 0,1 12,15.5M19.43,12.97C19.47,12.65 19.5,12.33 19.5,12C19.5,11.67 19.47,11.34 19.43,11L21.54,9.37C21.73,9.22 21.78,8.95 21.66,8.73L19.66,5.27C19.54,5.05 19.27,4.96 19.05,5.05L16.56,6.05C16.04,5.66 15.5,5.32 14.87,5.07L14.5,2.42C14.46,2.18 14.25,2 14,2H10C9.75,2 9.54,2.18 9.5,2.42L9.13,5.07C8.5,5.32 7.96,5.66 7.44,6.05L4.95,5.05C4.73,4.96 4.46,5.05 4.34,5.27L2.34,8.73C2.21,8.95 2.27,9.22 2.46,9.37L4.57,11C4.53,11.34 4.5,11.67 4.5,12C4.5,12.33 4.53,12.65 4.57,12.97L2.46,14.63C2.27,14.78 2.21,15.05 2.34,15.27L4.34,18.73C4.46,18.95 4.73,19.03 4.95,18.95L7.44,17.94C7.96,18.34 8.5,18.68 9.13,18.93L9.5,21.58C9.54,21.82 9.75,22 10,22H14C14.25,22 14.46,21.82 14.5,21.58L14.87,18.93C15.5,18.67 16.04,18.34 16.56,17.94L19.05,18.95C19.27,19.03 19.54,18.95 19.66,18.73L21.66,15.27C21.78,15.05 21.73,14.78 21.54,14.63L19.43,12.97Z" />
                    </svg>
                    Generales de la Queja
                </h3>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_atencion">Fecha de atención</label>
                            <input type="date" class="form-control" name="fecha_atencion" value="<?php echo remove_junk($e_detalle['fecha_atencion']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_paciente">Paciente</label>
                            <select class="form-control" name="id_paciente">
                                <?php foreach ($pacientes as $paciente1) : ?>
                                    <option <?php if ($paciente1['id_paciente'] === $e_detalle['id_paciente'])
                                                echo 'selected="selected"'; ?> value="<?php echo $paciente1['id_paciente']; ?>">
                                        <?php
                                        echo ucwords($paciente1['nombre'] . " " . $paciente1['paterno'] . " " . $paciente1['materno']);
                                        ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="estatus">Estatus</label>
                            <select class="form-control" name="estatus">
                                <option <?php if ($e_detalle['estatus'] == 'Primera Sesión') echo 'selected="selected"'; ?> value="Primera Sesión">
                                    Primera Sesión
                                </option>
                                <option <?php if ($e_detalle['estatus'] == 'Tratamiento') echo 'selected="selected"'; ?> value="Tratamiento">
                                    Tratamiento
                                </option>
                                <option <?php if ($e_detalle['estatus'] == 'Abandono Total') echo 'selected="selected"'; ?> value="Abandono Total">
                                    Abandono Total
                                </option>
                                <option <?php if ($e_detalle['estatus'] == 'Alta de Tratamiento') echo 'selected="selected"'; ?> value="Alta de Tratamiento">
                                    Alta de Tratamiento
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="atendio">Fecha de atención</label>
                            <input type="text" class="form-control" name="atendio" value="<?php echo remove_junk($e_detalle['atendio']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="no_sesion">No. Sesión</label>
                            <input type="text" class="form-control" name="no_sesion" value="<?php echo remove_junk($e_detalle['no_sesion']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nota_sesion">Notas de sesión</label>
                            <textarea class="form-control" name="nota_sesion" id="nota_sesion" cols="10" rows="5"><?php echo remove_junk($e_detalle['nota_sesion']); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="sesiones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_sesion" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>