<?php
$page_title = 'Editar Evento';
require_once('includes/load.php');
?>
<?php
$e_evento = find_by_id('eventos_presidencia', (int) $_GET['id'], 'id_eventos_presidencia');
if (!$e_evento) {
    $session->msg("d", "ID de evento no encontrado.");
    redirect('eventos_presidencia.php');
}
$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level(7);
}
if ($nivel_user == 52) {
    page_require_level_exacto(52);
}

if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 52) :
    redirect('home.php');
endif;
?>

<?php
if (isset($_POST['edit_evento_pres'])) {
    $req_fields = array('nombre_evento', 'tipo_evento', 'ambito_evento', 'fecha', 'hora', 'lugar', 'modalidad', 'depto_org');
    validate_fields($req_fields);

    if (empty($errors)) {
        $id = (int)$e_evento['id_eventos_presidencia'];
        $nombre_evento = remove_junk($db->escape($_POST['nombre_evento']));
        $tipo_evento = remove_junk($db->escape($_POST['tipo_evento']));
        $ambito_evento = remove_junk($db->escape($_POST['ambito_evento']));
        $fecha = remove_junk($db->escape($_POST['fecha']));
        $hora = remove_junk($db->escape($_POST['hora']));
        $lugar = remove_junk(($db->escape($_POST['lugar'])));
        $modalidad = remove_junk($db->escape($_POST['modalidad']));
        $depto_org = remove_junk($db->escape($_POST['depto_org']));
        
        $folio_editar = $e_evento['folio'];

        $sql = "UPDATE eventos_presidencia SET nombre_evento='{$nombre_evento}', tipo_evento='{$tipo_evento}', ambito_evento='{$ambito_evento}', fecha='{$fecha}', hora='{$hora}', lugar='{$lugar}', depto_org='{$depto_org}', modalidad='{$modalidad}' WHERE id_eventos_presidencia='{$db->escape($id)}'";
        $result = $db->query($sql);

        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Información Actualizada ");
            insertAccion($user['id_user'], '"'.$user['username'].'" editó registro en eventos, Folio: '.$folio_editar.'.', 2);
            redirect('eventos_pres.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos.');
            redirect('edit_evento_pres.php?id=' . (int) $e_evento['id_eventos_presidencia'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_evento_pres.php?id=' . (int) $e_evento['id_eventos_presidencia'], false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar Evento
                    <?php echo $e_evento['folio']; ?>
                </span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_evento_pres.php?id=<?php echo (int) $e_evento['id_eventos_presidencia']; ?>"
                enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre_evento">Nombre del Evento</label>
                            <input type="text" class="form-control" name="nombre_evento"
                                value="<?php echo remove_junk($e_evento['nombre_evento']); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_evento">Tipo de evento</label>
                            <select class="form-control" name="tipo_evento">
                                <option value="Capacitación" <?php if ($e_evento['tipo_evento'] === 'Capacitación')
                                    echo 'selected="selected"'; ?>>Capacitación</option>
                                <option value="Conferencia" <?php if ($e_evento['tipo_evento'] === 'Conferencia')
                                    echo 'selected="selected"'; ?>>Conferencia</option>
                                <option value="Curso" <?php if ($e_evento['tipo_evento'] === 'Curso')
                                    echo 'selected="selected"'; ?>>Curso</option>
                                <option value="Taller" <?php if ($e_evento['tipo_evento'] === 'Taller')
                                    echo 'selected="selected"'; ?>>Taller</option>
                                <option value="Plática" <?php if ($e_evento['tipo_evento'] === 'Plática')
                                    echo 'selected="selected"'; ?>>Plática</option>
                                <option value="Diplomado" <?php if ($e_evento['tipo_evento'] === 'Diplomado')
                                    echo 'selected="selected"'; ?>>Diplomado</option>
                                <option value="Foro" <?php if ($e_evento['tipo_evento'] === 'Foro')
                                    echo 'selected="selected"'; ?>>Foro</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="ambito_evento">Ámbito de evento</label>
                            <select class="form-control" name="ambito_evento">
				<option value="Actividad Interna" <?php if ($e_evento['ambito_evento'] === 'Actividad Interna')
                                    echo 'selected="selected"'; ?>>Actividad Interna</option>

                                <option value="Municipal" <?php if ($e_evento['ambito_evento'] === 'Municipal')
                                    echo 'selected="selected"'; ?>>Municipal</option>
                                <option value="Estatal" <?php if ($e_evento['ambito_evento'] === 'Estatal')
                                    echo 'selected="selected"'; ?>>Estatal</option>
                                <option value="Federal" <?php if ($e_evento['ambito_evento'] === 'Federal')
                                    echo 'selected="selected"'; ?>>Federal</option>
                                <option value="Internacional" <?php if ($e_evento['ambito_evento'] === 'Internacional')
                                    echo 'selected="selected"'; ?>>Internacional</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="modalidad">Modalidad</label>
                            <select class="form-control" name="modalidad">
                                <option value="Presencial" <?php if ($e_evento['modalidad'] === 'Presencial')
                                    echo 'selected="selected"'; ?>>Presencial</option>
                                <option value="En línea" <?php if ($e_evento['modalidad'] === 'En línea')
                                    echo 'selected="selected"'; ?>>En línea</option>
                                <option value="Híbrido" <?php if ($e_evento['modalidad'] === 'Híbrido')
                                    echo 'selected="selected"'; ?>>Híbrido</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha</label><br>
                            <input type="date" class="form-control" name="fecha"
                                value="<?php echo remove_junk($e_evento['fecha']); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="hora">Hora</label><br>
                            <input type="time" class="form-control" name="hora"
                                value="<?php echo remove_junk($e_evento['hora']); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lugar">Lugar</label>
                            <input type="text" class="form-control" name="lugar"
                                value="<?php echo remove_junk($e_evento['lugar']); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="depto_org">Departamento/Organización</label>
                            <input type="text" class="form-control" name="depto_org"
                                value="<?php echo remove_junk(($e_evento['depto_org'])); ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="eventos_pres.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_evento_pres" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>