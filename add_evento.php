<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Evento';
require_once('includes/load.php');
$id_folio = last_id_folios_general();
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

$area_user = area_usuario2($id_user);
//$area = $area_user['id_area'];
$area = isset($_GET['a']) ? $_GET['a'] : '0';
$inticadores_pat = find_all_pat_area($area, 'eventos');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_evento'])) {

    $req_fields = array('nombre_evento', 'tipo_evento', 'quien_solicita', 'fecha', 'hora', 'lugar', 'no_asistentes', 'modalidad', 'depto_org', 'quien_asiste');
    validate_fields($req_fields);

    if (empty($errors)) {
        $evidencias = array();
        $nombre   = remove_junk($db->escape($_POST['nombre_evento']));
        $solicita   = remove_junk($db->escape($_POST['quien_solicita']));
        $tipo_evento   = remove_junk($db->escape($_POST['tipo_evento']));
        $fecha   = remove_junk($db->escape($_POST['fecha']));
        $hora   = remove_junk($db->escape($_POST['hora']));
        $lugar   = remove_junk(($db->escape($_POST['lugar'])));
        $asistentes   = remove_junk(($db->escape($_POST['no_asistentes'])));
        $modalidad   = remove_junk($db->escape($_POST['modalidad']));
        $depto   = remove_junk($db->escape($_POST['depto_org']));
        $quien_asiste   = remove_junk($db->escape($_POST['quien_asiste']));
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));
        $creacion = date('Y-m-d H:i:s');

        if (count($id_folio) == 0) {
            $nuevo_id_queja = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_queja = (int) $nuevo['contador'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['contador'] + 1);
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
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-EVEN';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-EVEN';
        $carpeta = 'uploads/eventos/invitaciones/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['invitacion']['name'];
        $size = $_FILES['invitacion']['size'];
        $type = $_FILES['invitacion']['type'];
        $temp = $_FILES['invitacion']['tmp_name'];

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);

        foreach ($_FILES["documento_evidencia"]['name'] as $key => $tmp_name) {
            //condicional si el fuchero existe
            if ($_FILES["documento_evidencia"]["name"][$key]) {
                // Nombres de archivos de temporales
                $archivonombre = $_FILES["documento_evidencia"]["name"][$key];
                $fuente = $_FILES["documento_evidencia"]["tmp_name"][$key];
                array_push($evidencias, $archivonombre);

                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777) or die("Hubo un error al crear el directorio de almacenamiento");
                }

                $dir = opendir($carpeta);
                $target_path = $carpeta . '/' . $archivonombre; //indicamos la ruta de destino de los archivos


                if (move_uploaded_file($fuente, $target_path)) {
                } else {
                }
                closedir($dir); //Cerramos la conexion con la carpeta destino
            }
        }


        /*creo archivo index para que no se muestre el Index Of*/
        $source = 'uploads/index.php';
        if (copy($source, $carpeta . '/index.php')) {
            echo "El archivo ha sido copiado exitosamente.";
        } else {
            echo "Ha ocurrido un error al copiar el archivo.";
        }


        $dbh = new PDO('mysql:host=localhost;dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');

        $query = "INSERT INTO eventos (";
        $query .= "folio,nombre_evento,tipo_evento,quien_solicita,fecha,hora,lugar,no_asistentes,modalidad,depto_org,quien_asiste,invitacion,area_creacion,user_creador,fecha_creacion";
        if ($inticadores_pat) {
            $query .= ",id_indicadores_pat ";
        }
        $query .= ") VALUES (";
        $query .= " '{$folio}','{$nombre}','{$tipo_evento}','{$solicita}','{$fecha}','{$hora}','{$lugar}','{$asistentes}','{$modalidad}','{$depto}','{$quien_asiste}','{$name}','{$area}','{$id_user}','{$creacion}'";
        if ($inticadores_pat) {
            $query .= ",{$id_indicadores_pat} ";
        }
        $query .= ")";

        $query2 = "INSERT INTO folios (";
        $query2 .= "folio, contador";
        $query2 .= ") VALUES (";
        $query2 .= " '{$folio}','{$no_folio}'";
        $query2 .= ")";


        if ($dbh->exec($query) && $db->query($query2)) {
            $id_evento = $dbh->lastInsertId();

            if ($id_evento > 0) {
                for ($i = 0; $i < sizeof($evidencias); $i = $i + 1) {
                    if ($evidencias[$i] !== '') {
                        $queryInsert4 = "INSERT INTO rel_eventos_evidencias (id_evento,nombre_documento) VALUES('$id_evento','$evidencias[$i]')";
                        $db->query($queryInsert4);
                    }
                }
                //sucess
                $session->msg('s', " El evento ha sido agregado con éxito.");
                insertAccion($user['id_user'], '"' . $user['username'] . '" agregó evento, Folio: ' . $folio . '.', 1);
                redirect('eventos.php?a=' . $area, false);
            } else {
                $session->msg('d', ' No se pudo agregar la capacitación,debido a que no se genero ID de la misma' . $query);
                redirect('add_capacitacion.php?a=' . $area_informe, false);
            }
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar el evento.');
            redirect('add_evento.php' . $area, false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_evento.php' . $area, false);
    }
}
?>
<script type="text/javascript">
    $(document).ready(function() {


        $("#addRow").click(function() {
            var partida = document.getElementsByClassName("partida").length;
            var html = '';
            html += '<div id="inputFormRow">';
            html += '	<div class="col-md-3">';
            html += '		<input id="documento_evidencia" type="file"  class="form-control" name="documento_evidencia[]">';
            html += '	</div>';

            html += '	<div class="col-md-3">';
            html += '	<button type="button" class="btn btn-outline-danger" id="removeRow" > ';
            html += '   	<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">';
            html += '			<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>';
            html += '			<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>';
            html += '		</svg>';
            html += '  	</button>';
            html += '	</div> <br><br>';
            html += '</div> ';

            $('#newRow').append(html);
        });


        $(document).on('click', '#removeRow', function() {
            $(this).closest('#inputFormRow').remove();
        });



    });
</script>
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
            <form method="post" action="add_evento.php?a=<?php echo $area ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_evento">Nombre del evento</label>
                            <input type="text" class="form-control" name="nombre_evento" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_evento">Tipo de evento</label>
                            <select class="form-control" name="tipo_evento">
                                <option value="">Escoge una opción</option>
                                <option value="Foro">Foro</option>
                                <option value="Rueda de Prensa">Rueda de Prensa</option>
                                <option value="Representación">Representación</option>
                                <option value="Mesa de Diálogo">Mesa de Diálogo</option>
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
                            <input type="number" min="1" class="form-control" max="10000" name="no_asistentes" required>
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
                            <input type="text" class="form-control" name="depto_org">
                        </div>
                    </div>
                </div>


                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quien_asiste">¿Quién asiste? (separado por comas)</label>
                            <textarea name="quien_asiste" class="form-control" id="quien_asiste" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <span>
                                <label for="invitacion">Invitación</label>
                                <input id="invitacion" type="file" accept="application/pdf" class="form-control" name="invitacion">
                            </span>
                        </div>
                    </div>
                    <?php if ($inticadores_pat) { ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_indicadores_pat">Definición del Indicador</label>
                                <select class="form-control form-select" name="id_indicadores_pat" required>
                                    <option value="">Selecciona Indicador</option>
                                    <?php foreach ($inticadores_pat as $datos) : ?>
                                        <option value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="row">
                    <div id="inputFormRow">
                        <h3 style="font-weight:bold;    color: #000;">
                            <span class="material-symbols-outlined">checklist</span>
                            <span>Evidencias</span>
                        </h3>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="no_informe">Documento Evidencia</label>
                                <input id="documento_evidencia" type="file" class="form-control" name="documento_evidencia[]">

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <button type="button" class="btn btn-success" id="addRow" name="addRow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
                                        <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
                                        <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
                                    </svg>
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="newRow"></div>
                <br>
                <div class="form-group clearfix">
                    <a href="eventos.php?a=<?php echo $area ?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_evento" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>