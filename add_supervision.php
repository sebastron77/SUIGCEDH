<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Supervisión de Mecanismos';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];
$id_folio = last_id_folios_general();

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo ' . $page_title, 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 17) {
    page_require_level_exacto(17);
}
if ($nivel_user == 36) {
    page_require_level_exacto(36);
}
if ($nivel_user == 53) {
    insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo ' . $page_title, 5);
    page_require_level_exacto(53);
}

if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7  && $nivel_user < 17) :
    redirect('home.php');
endif;
if ($nivel_user > 17 && $nivel_user < 36) :
    redirect('home.php');
endif;
if ($nivel_user > 36 && $nivel_user < 53) :
    redirect('home.php');
endif;
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_supervision'])) {

    if (empty($errors)) {
        $evidencias = array();
        $fecha_visita   = remove_junk($db->escape($_POST['fecha_visita']));
        $nombre_actividad   = remove_junk($db->escape($_POST['nombre_actividad']));
        $institucion_visitada   = remove_junk(($db->escape($_POST['institucion_visitada'])));
        $quien_atendio   = remove_junk(($db->escape($_POST['quien_atendio'])));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));

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
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-SUPMEC';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-SUPMEC';
        $carpeta = 'uploads/supervisiones_mec/evidencia/' . $folio_carpeta;

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

        $query = "INSERT INTO supervision_mecanismos (";
        $query .= "folio, fecha_visita, nombre_actividad, institucion_visitada, quien_atendio, observaciones, user_creador, fecha_creacion";
        $query .= ") VALUES (";
        $query .= " '{$folio}', '{$fecha_visita}', '{$nombre_actividad}', '{$institucion_visitada}', '{$quien_atendio}', '{$observaciones}', 
                    '{$id_user}', NOW()); ";

        $query2 = "INSERT INTO folios (";
        $query2 .= "folio, contador";
        $query2 .= ") VALUES (";
        $query2 .= " '{$folio}', '{$no_folio1}'";
        $query2 .= ")";

        if ($dbh->query($query) && $db->query($query2)) {
            $id_supervicion_mecanismos = $dbh->lastInsertId();

            if ($id_supervicion_mecanismos > 0) {
                for ($i = 0; $i < sizeof($evidencias); $i = $i + 1) {
                    if ($evidencias[$i] !== '') {
                        $queryInsert4 = "INSERT INTO rel_supervicion_evidencias (id_supervision_mecanismos, nombre_documento) 
                                            VALUES('$id_supervicion_mecanismos', '$evidencias[$i]')";
                        $db->query($queryInsert4);
                    }
                }
                //sucess
                $session->msg('s', " La supervisión ha sido agregada con éxito.");
                insertAccion($user['id_user'], '"' . $user['username'] . '" agregó supervicion_mec, Folio: ' . $folio . '.', 1);
                redirect('supervision_mecanismos.php', false);
            } else {
                $session->msg('d', ' No se pudo agregar la supervisión, debido a que no se genero ID de la misma' . $queryInsert4);
                redirect('add_supervision.php', false);
            }
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la supervisión.');
            redirect('add_supervision.php' . $area, false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_supervision.php' . $area, false);
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
<?php
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Supervisión de Mecanismos</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_supervision.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_visita">Fecha de Visita</label><span style="color:red; font-weight: bold; font-size: 15px;"> *</span><br>
                            <input type="date" class="form-control" name="fecha_visita" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_actividad">Nombre de Actividad<span style="color:red; font-weight: bold; font-size: 15px;"> *</span></label>
                            <input type="text" class="form-control" name="nombre_actividad" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="institucion_visitada">Institución Visitada<span style="color:red; font-weight: bold; font-size: 15px;"> *</span></label>
                            <input type="text" class="form-control" name="institucion_visitada" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="quien_atendio">¿Quién Atendió?<span style="color:red; font-weight: bold; font-size: 15px;"> *</span></label>
                            <input type="text" class="form-control" name="quien_atendio" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="5"></textarea>
                        </div>
                    </div>
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
                                <button type="button" class="btn btn-success" id="addRow" name="addRow" style="margin-top: 30px;">
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
                <div class="form-group clearfix">
                    <a href="supervision_mecanismos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_supervision" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>