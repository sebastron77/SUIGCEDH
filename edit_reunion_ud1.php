<?php
$page_title = 'Editar Reunión de Trajo';
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}

if ($nivel_user == 50) {
    page_require_level_exacto(50);
}


$e_comunidad = find_by_id('reuniones_trabajo_ud', (int)$_GET['id'], 'id_reuniones_trabajo_ud');
if (!$e_comunidad) {
    $session->msg("d", "La Reunión de Trabajo no existe, verifique el ID.");
    redirect('cat_comunidad.php');
}
?>
<?php
if (isset($_POST['update'])) {

    $req_fields = array('descripcion');
    validate_fields($req_fields);
    if (empty($errors)) {
        $name = remove_junk($db->escape(($_POST['descripcion'])));
		
        $estatus = $_POST['estatus'];

        $query  = "UPDATE cat_area_conocimiento SET ";
        $query .= "descripcion='{$name}' ";
        $query .= "WHERE id_cat_area_con='{$db->escape($e_comunidad['id_cat_area_con'])}'";
		 
		$result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Área del Conocimiento ha actualizada! '".($name)."'");
			insertAccion($user['id_user'],'"'.$user['username'].'" edito el área del conocimiento '.$name.'(id:'.(int)$e_comunidad['id_cat_area_con'].').',2);
            redirect('edit_area_conocimiento.php?id=' . (int)$e_comunidad['id_cat_area_con'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el área!');
            redirect('edit_area_conocimiento.php?id=' . (int)$e_comunidad['id_cat_area_con'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_area_conocimiento.php?id=' . (int)$e_comunidad['id_cat_area_con'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8'); include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Editar Área del Conocimiento</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="edit_area_conocimiento.php?id=<?php echo (int)$e_comunidad['id_cat_area_con']; ?>" class="clearfix">
        <div class="row">
				<div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha">Fecha</label><br>
                            <input type="date" class="form-control" name="fecha_reunion" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="hora">Hora</label><br>
                            <input type="time" class="form-control" name="hora_reunion" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="lugar">Lugar</label>
                            <input type="text" class="form-control" name="lugar_reunion" required>
                        </div>
                    </div>         
					<div class="col-md-3">
						<div class="form-group">
							<label for="quien_atendio">¿Quién Atendió?</label>
							<input type="text" class="form-control" name="quien_atendio" required>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="no_asistentes">No.Personas Asistentes</label>
							<input type="number" class="form-control" min="1" max="1300" maxlength="4" name="no_asistentes" required >
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="form-group">
							<label for="acciones">Acciones a Realizar Derivadas de la Reunión</label>
							<textarea class="form-control" name="acciones_realizar" cols="8" rows="4"></textarea>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="observaciones">Observaciones</label>
							<textarea class="form-control" name="observaciones" cols="8" rows="4"></textarea>
						</div>
					</div>
				</div>
				
				<div class="panel-heading">
			<strong>
				<span class="glyphicon glyphicon-user"></span>
				<span>Asistentes a la Reunion</span>
			</strong>
        <div class="form-group clearfix">
            <a href="reuniones_ud.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>