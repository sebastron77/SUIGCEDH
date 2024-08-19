
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Proyecto';
require_once('includes/load.php');
$id_folio = last_id_folios_general();
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

$responsables = find_area_usuario();

$id_user = $user['id_user'];
$areas = find_all('area');


$area_user = area_usuario2($id_user);
$area_creacion = $area_user['nombre_area'];

date_default_timezone_set('America/Mexico_City');
$creacion_sistema = date('Y-m-d');


$e_agenda = find_by_id_agenda((int)$_GET['id']);
$e_agenda_porcentaje = find_by_id_agenda_porcentaje((int)$_GET['id']);

if (!$e_agenda) {
    $session->msg("d", "id de agenda no encontrado.");
    redirect('agendas.php');
}

?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_agenda'])) {

    if (empty($errors)) {
        $id = (int)$e_agenda['id_agendas'];
        $fecha   = remove_junk($db->escape($_POST['fecha']));
        $actividad   = remove_junk($db->escape($_POST['actividad']));
        $area_responsable   = remove_junk($db->escape($_POST['area_responsable']));
        $responsable   = remove_junk($db->escape($_POST['responsable']));
        $area_supervisor   = remove_junk($db->escape($_POST['area_supervisor']));
        $supervisor   = remove_junk($db->escape($_POST['supervisor']));
        $fecha_limite   = remove_junk($db->escape($_POST['fecha_limite']));
        $fecha_entrega   = remove_junk(($db->escape($_POST['fecha_entrega'])));
        $entregables   = remove_junk(($db->escape($_POST['entregables'])));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));
        $porcentaje   = remove_junk($db->escape($_POST['porcentaje']));
        $descripcion_avance   = remove_junk($db->escape($_POST['descripcion_avance']));
        date_default_timezone_set('America/Mexico_City');
        $fecha_creacion_sistema = date('Y-m-d H:i:s');

        $sql = "UPDATE agendas SET fecha='{$fecha}', actividad='{$actividad}', area_responsable='{$area_responsable}', responsable='{$responsable}', area_supervisor='{$area_supervisor}', supervisor='{$supervisor}', fecha_limite='{$fecha_limite}', fecha_entrega='{$fecha_entrega}', entregables='{$entregables}', observaciones='{$observaciones}' WHERE id_agendas='{$db->escape($id)}'";

        $query2 = "INSERT INTO avance_agendas (";
        $query2 .= "id_agenda,porcentaje,descripcion_avance,area_creacion,fecha_hora_creacion";
        $query2 .= ") VALUES (";
        $query2 .= " '{$id}','{$porcentaje}','{$descripcion_avance}','{$area_creacion}','{$fecha_creacion_sistema}'";
        $query2 .= ")";

        $result = $db->query($sql);

        if (($result && $db->affected_rows() === 1) || ($db->query($query2))) {
            //sucess
            $session->msg('s', " El proyecto ha sido editado con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó agenda, el día: ' . $fecha_creacion_sistema . '.', 2);
            redirect('agendas.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo editar el proyecto.');
            redirect('edit_agenda.php?id=' . (int)$e_correspondencia['id'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_agenda.php', false);
    }
}
?>
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar proyecto</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_agenda.php?id=<?php echo (int)$e_agenda['id_agendas']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha</label><br>
                            <input type="date" value="<?php echo remove_junk($e_agenda['fecha']); ?>" class="form-control" name="fecha">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="actividad">Actividad (Descripción)</label>
                            <textarea class="form-control" value="<?php echo remove_junk($e_agenda['actividad']); ?>" name="actividad" cols="10" rows="5"><?php echo remove_junk($e_agenda['actividad']); ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="area_responsable">Área responsable</label>
                            <select class="form-control" id="area_responsable" name="area_responsable">
                                <?php foreach ($areas as $area) : ?>
                                    <option <?php if ($area['nombre_area'] === $e_agenda['area_responsable']) echo 'selected="selected"'; ?> value="<?php echo $area['nombre_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <?php $trabajadores = find_all_trabajadores_area($e_agenda['area_responsable']) ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="responsable_editar">Responsable</label>
                            <select class="form-control" id="responsable_editar" name="responsable">
                                <?php foreach ($trabajadores as $trabajador) : ?>
                                    <option <?php if ($trabajador['id_det_usuario'] === $e_agenda['responsable']) echo 'selected="selected"'; ?> value="<?php echo $trabajador['id_det_usuario']; ?>"><?php echo ucwords($trabajador['nombre'] . " " . $trabajador['apellidos']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="area_supervisor">Área supervisor</label>
                            <select class="form-control" id="area_supervisor" name="area_supervisor">
                                <?php foreach ($areas as $area) : ?>
                                    <!-- <option value="<?php echo $area['nombre_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option> -->
                                    <option <?php if ($area['nombre_area'] === $e_agenda['area_supervisor']) echo 'selected="selected"'; ?> value="<?php echo $area['nombre_area'];?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <?php $trabajadores2 = find_all_trabajadores_area($e_agenda['area_supervisor']) ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="supervisor_editar">Supervisor</label>
                            <select class="form-control" id="supervisor_editar" name="supervisor">
                                <?php foreach ($trabajadores2 as $trabajador2) : ?>
                                    <option <?php if ($trabajador2['id_det_usuario'] === $e_agenda['supervisor']) echo 'selected="selected"'; ?> value="<?php echo $trabajador2['id_det_usuario']; ?>"><?php echo ucwords($trabajador2['nombre'] . " " . $trabajador2['apellidos']); ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_limite">Fecha de límite</label><br>
                            <input type="date" value="<?php echo remove_junk($e_agenda['fecha_limite']); ?>" class="form-control" name="fecha_limite">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_entrega">Fecha de entrega</label><br>
                            <input type="date" value="<?php echo remove_junk($e_agenda['fecha_limite']); ?>" class="form-control" name="fecha_entrega">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="entregables">Entregables</label>
                            <!-- <input type="text" value="<?php echo remove_junk($e_agenda['entregables']); ?>" class="form-control" name="entregables" required>                             -->
                            <textarea class="form-control" value="<?php echo remove_junk($e_agenda['entregables']); ?>" name="entregables" cols="10" rows="3"><?php echo remove_junk($e_agenda['entregables']); ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" value="<?php echo remove_junk($e_agenda['observaciones']); ?>" name="observaciones" cols="10" rows="3"><?php echo remove_junk($e_agenda['observaciones']); ?></textarea>
                        </div>
                    </div>
                </div>
                <hr style="border-width:2px;">
                <h2><b>Información de avance del proyecto</b></h2>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="porcentaje">% de avance</label>
                            <input type="number" min="0" max="100" value="<?php echo remove_junk($e_agenda_porcentaje['porcentaje']); ?>" class="form-control" min="0" name="porcentaje" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="descripcion_avance">Descripción del avance</label>
                            <textarea class="form-control" value="<?php echo remove_junk($e_agenda_porcentaje['descripcion_avance']); ?>" name="descripcion_avance" cols="10" rows="3"><?php echo remove_junk($e_agenda_porcentaje['descripcion_avance']); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <a href="agendas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_agenda" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>