<script type="text/javascript" src="libs/js/paciente.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Sesión';
require_once('includes/load.php');
$user = current_user();
$detalle = $user['id_user'];
$id_folio = last_id_folios();
// page_require_level(4);
$id_user = $user['id_user'];
// page_require_area(4);
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_sesion'])) {

    $req_fields = array('paciente', 'estatus', 'atendio', 'no_sesion');
    validate_fields($req_fields);

    if (empty($errors)) {
        $paciente   = remove_junk($db->escape($_POST['paciente']));
        $fecha_atencion = remove_junk($db->escape($_POST['fecha_atencion']));
        $estatus   = remove_junk($db->escape($_POST['estatus']));
        $nota_sesion   = remove_junk($db->escape($_POST['nota_sesion']));
        $atendio   = remove_junk($db->escape($_POST['atendio']));
        $id_paciente   = remove_junk($db->escape($_POST['paciente']));
        $no_sesion   = remove_junk($db->escape($_POST['no_sesion']));
        date_default_timezone_set('America/Mexico_City');
        $creacion = date('Y-m-d');

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int)$nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int)$nuevo['contador'] + 1);
            }
        }

        $year = date("Y");
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-SST';

        $query = "INSERT INTO sesiones (";
        $query .= "id_paciente, folio, fecha_atencion, estatus, nota_sesion, atendio, fecha_creacion, usuario_creador, no_sesion";
        $query .= ") VALUES (";
        $query .= " '{$id_paciente}', '{$folio}', '{$fecha_atencion}', '{$estatus}', '{$nota_sesion}', '{$atendio}', '{$creacion}', '{$id_user}', '{$no_sesion}'";
        $query .= ")";

        $query2 = "INSERT INTO folios (";
        $query2 .= "folio, contador";
        $query2 .= ") VALUES (";
        $query2 .= " '{$folio}','{$no_folio1}'";
        $query2 .= ")";

        if ($db->query($query) && $db->query($query2)) {
            //sucess
            $session->msg('s', " La sesión ha sido agregada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó Sesión, Folio: ' . $folio . '.', 1);
            redirect('sesiones.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la sesión.');
            redirect('add_sesion.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_sesion.php', false);
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
                <span>Agregar Sesión</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_sesion.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_atencion">Fecha de atención</label>
                            <input type="date" class="form-control" name="fecha_atencion" id="fecha_atencion">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_paciente">Paciente <span style="color:red;font-weight:bold">*</span>
                                <div style="margin-left: 10px;" class="popup caja" onclick="myFunction()">
                                    <p style="font-size: 13px;">?</p>
                                    <span class="popuptext" id="myPopup">Ingresa nombre del paciente y selecciónalo de la lista desplegada. Si no se encuentra registrado, deberás agregarlo en apartado de Pacientes.</span>
                                </div>
                            </label>
                            <div class="input_container">
                                <input class="form-control" autocomplete="off" type="text" id="id_paciente" onkeyup="autocompletar()" required>
                                <input type="hidden" id="paciente" name="paciente">
                                <ul id="lista_id" style="position: absolute; z-index: 100; background: #ffffff;"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="estatus">Estatus</label>
                            <select class="form-control" name="estatus">
                                <option value="">Escoge una opción</option>
                                <option value="Primera Sesión">Primera Sesión</option>
                                <option value="Tratamiento">Tratamiento</option>
                                <option value="Abandono Total">Abandono Total</option>
                                <option value="Alta de Tratamiento">Alta de Tratamiento</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="atendio">Atendió</label>
                            <input type="text" class="form-control" name="atendio">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="no_sesion">No. sesión</label>
                            <input class="form-control" type="number" min="1" max="200" name="no_sesion">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nota_sesion">Notas de sesión</label>
                            <textarea class="form-control" name="nota_sesion" id="nota_sesion" cols="10" rows="5"></textarea>
                        </div>
                    </div>
                    
                </div>
                <div class="form-group clearfix">
                    <a href="sesiones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_sesion" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>