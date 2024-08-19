<?php
$page_title = 'Editar Área';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
$date = date('Y-m-d');

?>
<?php
$e_certificacion = find_by_id('certificaciones', (int)$_GET['id'], 'id_certificaciones');
if (!$e_certificacion) {
    $session->msg("d", "id de la certificaion no encontrado.");
    redirect('areas.php');
}
?>
<?php
if (isset($_POST['update'])) {

    $req_fields = array('avance_proceso', 'fecha_actualizacion_avance');
    validate_fields($req_fields);
    if (empty($errors)) {
        $avance_proceso = remove_junk($db->escape($_POST['avance_proceso']));
        $fecha_actualizacion_avance = remove_junk($db->escape($_POST['fecha_actualizacion_avance']));

        $query  = "UPDATE certificaciones SET ";
        $query .= "avance_proceso='{$avance_proceso}', fecha_actualizacion_avance='{$fecha_actualizacion_avance}'";
        $query .= "WHERE id_certificaciones='{$db->escape($e_certificacion['id_certificaciones'])}'";
        $result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "El Avance del Proceso de Certificación se ha actualizado! ");
			insertAccion($user['id_user'], '"' . $user['username'] . '" actualizó el Avance del Proceso de Certificación('.$e_certificacion['id_certificaciones'].') quedando con el "'.$avance_proceso.'%".', 2);
            redirect('certificaciones.php', false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el área!');
            redirect('certificaciones.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('certificaciones.php', false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">   
    <?php echo display_msg($msg); ?>
    <form method="post" action="edit_certificaciones_avance.php?id=<?php echo (int)$e_certificacion['id_certificaciones']; ?>" class="clearfix">
        <div class="form-group">
			<br>
            <label  class="control-label">Avance de la Certificación <?php echo $e_certificacion['folio']; ?></label>
			<br> <br> <br>
            <label for="avance_proceso" class="control-label">Avance</label>
            <input type="name" class="form-control" name="avance_proceso" value="<?php echo ucwords($e_certificacion['avance_proceso']); ?>">
			<br> <br>
            <label for="fecha_actualizacion_avance" class="control-label">Abreviación del área</label>
            <input type="date" class="form-control" name="fecha_actualizacion_avance" value="<?php echo $date;?>"required>
        </div>
			<br>
        <div class="form-group clearfix">
            <a href="certificaciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>