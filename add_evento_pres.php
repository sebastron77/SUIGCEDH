<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Evento';
require_once('includes/load.php');
$id_folio = last_id_folios();
$user = current_user();
$nivel_user = $user['user_level'];
// $id_user = $user['id'];

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
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_evento_pres'])) {

    $req_fields = array('nombre_evento', 'tipo_evento', 'ambito_evento', 'fecha', 'hora', 'lugar', 'modalidad', 'depto_org');
    validate_fields($req_fields);

    if (empty($errors)) {
        $nombre_evento   = remove_junk($db->escape($_POST['nombre_evento']));
        $tipo_evento   = remove_junk($db->escape($_POST['tipo_evento']));
        $ambito_evento   = remove_junk($db->escape($_POST['ambito_evento']));
        $fecha   = remove_junk($db->escape($_POST['fecha']));
        $hora   = remove_junk($db->escape($_POST['hora']));
        $lugar   = remove_junk(($db->escape($_POST['lugar'])));
        $modalidad   = remove_junk($db->escape($_POST['modalidad']));
        $depto_org   = remove_junk($db->escape($_POST['depto_org']));
        date_default_timezone_set('America/Mexico_City');
        $fecha_creacion = date('Y-m-d H:i:s');

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int)$nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int)$nuevo['contador'] + 1);
            }
        }
        //Se crea el número de folio
        $year = date("Y");
        // Se crea el folio de capacitacion
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-EVENP';

        
        $query = "INSERT INTO eventos_presidencia (";
        $query .= "folio, nombre_evento, tipo_evento, ambito_evento, fecha,hora, lugar, depto_org, modalidad, fecha_creacion";
        $query .= ") VALUES (";
        $query .= " '{$folio}', '{$nombre_evento}', '{$tipo_evento}', '{$ambito_evento}', '{$fecha}', '{$hora}', '{$lugar}', '{$depto_org}', '{$modalidad}', '{$fecha_creacion}'";
        $query .= ")";

        $query2 = "INSERT INTO folios (";
        $query2 .= "folio, contador";
        $query2 .= ") VALUES (";
        $query2 .= " '{$folio}','{$no_folio1}'";
        $query2 .= ")";
        
        if ($db->query($query) && $db->query($query2)) {
            //sucess
            $session->msg('s', " El evento ha sido agregado con éxito.");
            insertAccion($user['id_user'], '"'.$user['username'].'" agregó registro en eventos, Folio: '.$folio.'.', 1);
            redirect('eventos_pres.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar el evento.');
            redirect('add_evento_pres.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_evento_pres.php', false);
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
                <span>Agregar evento</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_evento_pres.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre_evento">Nombre del evento</label>
                            <input type="text" class="form-control" name="nombre_evento" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_evento">Tipo de evento</label>
                            <select class="form-control" name="tipo_evento">
                                <option value="Escoge una opción">Escoge una opción</option>
                                <option value="Conferencia">Conferencia</option>
                                <option value="Rueda de Prensa">Rueda de Prensa</option>
                                <option value="Representación">Representación</option>
                                <option value="Mesa de Diálogo">Mesa de Diálogo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="ambito_evento">Ámbito del evento</label>
                            <select class="form-control" name="ambito_evento">
                                <option value="Escoge una opción">Escoge una opción</option>
				<option value="Actividad Interna">Actividad Interna</option>
                                <option value="Municipal">Municipal</option>
                                <option value="Estatal">Estatal</option>
                                <option value="Federal">Federal</option>
                                <option value="Internacional">Internacional</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="modalidad">Modalidad</label>
                            <select class="form-control" name="modalidad">
                                <option value="Escoge una opción">Escoge una opción</option>
                                <option value="Presencial">Presencial</option>
                                <option value="En línea">En línea</option>
                                <option value="Híbrido">Híbrido</option>
                            </select>
                        </div>
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha</label><br>
                            <input type="date" class="form-control" name="fecha">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="hora">Hora</label><br>
                            <input type="time" class="form-control" name="hora">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lugar">Lugar</label>
                            <input type="text" class="form-control" name="lugar" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="depto_org">Departamento/Organización</label>
                            <input type="text" class="form-control" name="depto_org">
                        </div>
                    </div>
                </div>

                <div class="row">
                </div>

                <div class="form-group clearfix">
                    <a href="eventos_pres.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_evento_pres" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>