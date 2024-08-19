
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Capacitación';
require_once('includes/load.php');
$id_folio = last_id_folios();
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

$area_user = area_usuario2($id_user);
$area = $area_user['nombre_area'];
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_capacitacion'])) {

    $req_fields = array('nombre_capacitacion', 'tipo_evento', 'quien_solicita', 'fecha', 'hora', 'lugar', 'no_asistentes', 'modalidad', 'depto_org', 'capacitador');
    validate_fields($req_fields);

    if (empty($errors)) {
        $nombre   = remove_junk($db->escape($_POST['nombre_capacitacion']));
        $solicita   = remove_junk($db->escape($_POST['quien_solicita']));
        $tipo_evento   = remove_junk($db->escape($_POST['tipo_evento']));
        $fecha   = remove_junk($db->escape($_POST['fecha']));
        $hora   = remove_junk($db->escape($_POST['hora']));
        $lugar   = remove_junk(($db->escape($_POST['lugar'])));
        $asistentes   = remove_junk(($db->escape($_POST['no_asistentes'])));
        $modalidad   = remove_junk($db->escape($_POST['modalidad']));
        $depto   = remove_junk($db->escape($_POST['depto_org']));
        $capacitador   = remove_junk($db->escape($_POST['capacitador']));
        $curriculum   = remove_junk($db->escape($_POST['curriculum']));
        $constancia   = remove_junk($db->escape($_POST['constancia']));

        if (count($id_ori_canal) == 0) {
            $nuevo_id_ori_canal = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_ori_canal as $nuevo) {
                $nuevo_id_ori_canal = (int) $nuevo['id'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['id'] + 1);
            }
        }

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int) $nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int) $nuevo['contador'] + 1);
            }
        }
        //Se crea el número de folio
        $year = date("Y");
        // Se crea el folio de capacitacion
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-CAP';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-CAP';
        $carpeta = 'uploads/capacitaciones/curriculums/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['curriculum']['name'];
        $size = $_FILES['curriculum']['size'];
        $type = $_FILES['curriculum']['type'];
        $temp = $_FILES['curriculum']['tmp_name'];

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);

        if ($move && $name != '') {
            $query = "INSERT INTO capacitaciones (";
            $query .= "nombre_capacitacion,tipo_evento,quien_solicita,fecha,hora,lugar,no_asistentes,modalidad,depto_org,capacitador,curriculum,constancia,folio,area_creacion";
            $query .= ") VALUES (";
            $query .= " '{$nombre}','{$tipo_evento}','{$solicita}','{$fecha}','{$hora}','{$lugar}','{$asistentes}','{$modalidad}','{$depto}','{$capacitador}','{$name}','{$constancia}','{$folio}','{$area}'";
            $query .= ")";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";
        } else {
            $query = "INSERT INTO capacitaciones (";
            $query .= "nombre_capacitacion,tipo_evento,quien_solicita,fecha,hora,lugar,no_asistentes,modalidad,depto_org,capacitador,curriculum,constancia,folio,area_creacion";
            $query .= ") VALUES (";
            $query .= " '{$nombre}','{$tipo_evento}','{$solicita}','{$fecha}','{$hora}','{$lugar}','{$asistentes}','{$modalidad}','{$depto}','{$capacitador}','{$name}','{$constancia}','{$folio}','{$area}'";
            $query .= ")";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";
        }
        if ($db->query($query) && $db->query($query2)) {
            //sucess
            $session->msg('s', " La capacitación ha sido agregada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó capacitación, Folio: ' . $folio . '.', 1);
            redirect('capacitaciones.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la capacitación.');
            redirect('add_capacitacion.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_capacitacion.php', false);
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
                <span>Agregar capacitación</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_capacitacion.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_capacitacion">Nombre de la capacitación</label>
                            <input type="text" class="form-control" name="nombre_capacitacion" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_evento">Tipo de evento</label>
                            <select class="form-control" name="tipo_evento">
                                <option value="">Escoge una opción</option>
                                <option value="Capacitación">Capacitación</option>
                                <option value="Conferencia">Conferencia</option>
                                <option value="Curso">Curso</option>
                                <option value="Taller">Taller</option>
                                <option value="Plática">Plática</option>
                                <option value="Diplomado">Diplomado</option>
                                <option value="Foro">Foro</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quien_solicita">¿Quién lo solicita?</label>
                            <input type="text" class="form-control" name="quien_solicita" placeholder="Nombre Completo" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha</label><br>
                            <input type="date" class="form-control" name="fecha">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="hora">Hora</label><br>
                            <input type="time" class="form-control" name="hora">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="lugar">Lugar</label>
                            <input type="text" class="form-control" name="lugar" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="no_asistentes">No. de asistentes</label>
                            <input type="number" min="1" class="form-control" max="1000" name="no_asistentes" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="modalidad">Modalidad</label>
                            <select class="form-control" name="modalidad">
                                <option value="">Escoge una opción</option>
                                <option value="Presencial">Presencial</option>
                                <option value="En línea">En línea</option>
                                <option value="Híbrido">Híbrido</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="depto_org">Departamento/Organización</label>
                            <input type="text" class="form-control" name="depto_org" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="capacitador">Capacitador</label>
                            <input type="text" class="form-control" name="capacitador" placeholder="Nombre Completo" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <span>
                                <label for="curriculum">Curriculum</label>
                                <input id="curriculum" type="file" accept="application/pdf" class="form-control" name="curriculum">
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <a href="capacitaciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_capacitacion" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>