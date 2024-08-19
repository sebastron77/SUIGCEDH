
<?php
$page_title = 'Remitir queja';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
$area = find_all_areas_quejas();

if ($nivel_user <= 2) :
    page_require_level(2);
endif;

if ($nivel_user == 19) :
    page_require_level_exacto(19);
endif;

if ($nivel_user > 2 && $nivel_user < 19) :
    redirect('home.php');
endif;
if ($nivel_user > 19) :
    redirect('home.php');
endif;
?>
<?php
$e_detalle = find_by_id('quejas_dates', (int) $_GET['id'],'id_queja_date');
if (!$e_detalle) {
    $session->msg("d", "id de la queja no encontrado.");
    redirect('mediacion.php');
}
?>
<?php
if (isset($_POST['update'])) {

    if (empty($errors)) {
        $id_area_asignada = remove_junk($db->escape($_POST['id_area_asignada']));
        $id_user_asignado = remove_junk($db->escape($_POST['id_user_asignado']));

            $query = "UPDATE quejas_dates SET ";
            $query .= "id_area_asignada='{$id_area_asignada}', id_user_asignado='{$id_user_asignado}' ";
            $query .= "WHERE id_queja_date='{$db->escape($e_detalle['id_queja_date'])}'";
        
        $result = $db->query($query);
        if ($result && $db->affected_rows() === 1 ) {
            //sucess
            $session->msg('s', "Se remitio de forma exitosa el Expediente '".$e_detalle['folio_queja']."'. ");
            insertAccion($user['id_user'], '"' . $user['username'] . '" remitió el expedienete, Folio: ' . $e_detalle['folio_queja'] . '.', 2);
            redirect('mediacion.php', false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el registro!');
            redirect('mediacion.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('remitir_queja.php?id=' . (int) $e_detalle['id_queja_date'], false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page3">
    <div class="text-center">
        <h3>Remitir Queja</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="remitir_queja.php?id=<?php echo (int) $e_detalle['id_queja_date']; ?>" class="clearfix" enctype="multipart/form-data">
        <div class="form-group">
            <label for="id_area_asignada">Área a la que se asigna <span style="color:red;font-weight:bold">*</span></label>
			<select class="form-control" id="id_area_asignada" name="id_area_asignada" required>
				<option value="">Escoge una opción</option>
				<?php foreach ($area as $a) : ?>
					<option value="<?php echo $a['id_area']; ?>"><?php echo ucwords($a['nombre_area']); ?></option>
				<?php endforeach; ?>
			</select>
        </div>
		<?php $trabajadores = find_all_trabajadores_area($a['id_area']) ?>
        <div class="form-group">
            <label for="id_user_asignado">Se asigna a</label>
            <select class="form-control" id="id_user_asignado" name="id_user_asignado"></select>
        </div>
       
        <div class="form-group clearfix">
            <a href="mediacion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>