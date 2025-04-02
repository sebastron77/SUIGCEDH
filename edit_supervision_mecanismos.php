<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Supervisión de Mecanismos';
require_once('includes/load.php');

$e_supervision = find_by_id('supervision_mecanismos', (int)$_GET['id'], 'id_supervision_mecanismos');
if (!$e_supervision) {
    $session->msg("d", "id de supervision no encontrado.");
    redirect('supervision_mecanismos.php');
}
$user = current_user();
$nivel_user = $user['user_level'];
$supervision = find_by_id('supervision_mecanismos', (int)$_GET['id'], 'id_supervision_mecanismos');
$evidencias = find_all_by('rel_supervision_evidencias', (int)$_GET['id'], 'id_supervision_mecanismos');
$existe = ($evidencias ? 1 : 0);
$resultado = str_replace("/", "-", $e_supervision['folio']);

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

if (isset($_POST['edit_supervision_mecanismos'])) {
    if (empty($errors)) {
        $id = (int)$supervision['id_supervision_mecanismos'];
        $evidencias = array();
        $fecha_visita = remove_junk($db->escape($_POST['fecha_visita']));
        $nombre_actividad = remove_junk($db->escape($_POST['nombre_actividad']));
        $institucion_visitada = remove_junk(($db->escape($_POST['institucion_visitada'])));
        $quien_atendio = remove_junk(($db->escape($_POST['quien_atendio'])));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));

        $folio_editar = $supervision['folio'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/supervisiones_mec/evidencia/' . $resultado;

        $name = $_FILES['invitacion']['name'];
        $size = $_FILES['invitacion']['size'];
        $type = $_FILES['invitacion']['type'];
        $temp = $_FILES['invitacion']['tmp_name'];

        //Verificamos que exista la carpeta y si sí, guardamos el pdf
        if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else {
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }
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

        $sql = "UPDATE supervision_mecanismos SET fecha_visita='{$fecha_visita}', nombre_actividad='{$nombre_actividad}', 
                institucion_visitada='{$institucion_visitada}', quien_atendio='{$quien_atendio}', observaciones='{$observaciones}'
                WHERE id_supervision_mecanismos='{$db->escape($id)}'";

        for ($i = 0; $i < sizeof($evidencias); $i = $i + 1) {
            if ($evidencias[$i] !== '') {
                $queryInsert4 = "INSERT INTO rel_supervision_evidencias (id_supervision_mecanismos, nombre_documento) 
                                    VALUES('{$db->escape($id)}', '$evidencias[$i]')";
                $db->query($queryInsert4);
            }
        }

        $result = $db->query($sql);

        if (($result || $db->affected_rows() === 1) || ($result && $db->affected_rows() === 1)) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó Supervisión de Mecanismos(' . $id . ') de Folio: ' . $supervision['folio'], 2);
            $session->msg('s', " La Supervisión de Mecanismos con folio '" . $supervision['folio'] . "' ha sido acuatizada con éxito.");
            redirect('supervision_mecanismos.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron cambios a la información.');
            redirect('edit_supervision_mecanismos.php?id=' . (int)$supervision['id_supervision_mecanismos'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('supervision_mecanismos.php', false);
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

<?php include_once('layouts/header.php'); ?>
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
            <form method="post" action="edit_supervision_mecanismos.php?id=<?php echo (int)$supervision['id_supervision_mecanismos']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_visita">Fecha Supervisión</label><br>
                            <input type="date" class="form-control" name="fecha_visita" value="<?php echo ucwords($supervision['fecha_visita']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_actividad">Nombre de Actividad</label>
                            <input type="text" class="form-control" name="nombre_actividad" value="<?php echo ucwords($supervision['nombre_actividad']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="institucion_visitada">Institución Visitada</label>
                            <input type="text" class="form-control" name="institucion_visitada" value="<?php echo ucwords($supervision['institucion_visitada']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quien_atendio">¿Quién Atendió?<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="quien_atendio" value="<?php echo ucwords($supervision['quien_atendio']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="5"><?php echo ucwords($supervision['observaciones']); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div id="inputFormRow">
                        <h3 style="font-weight:bold; color: #000;">
                            <span class="material-symbols-outlined">checklist</span>
                            <span>Evidencias</span>
                        </h3>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="no_informe">Documento Evidencia</label>
                                <?php if ($existe == 0) { ?>
                                    <input id="documento_evidencia" type="file" class="form-control" name="documento_evidencia[]">
                                <?php } ?>
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
                <div class="row" id="newRow">
                    <?php foreach ($evidencias as $evidencia) : ?>
                        <div id="inputFormRow">
                            <div class="col-md-3">
                                <a target="_blank" style="color:#3D94FF" href="uploads/supervisiones_mec/evidencia/<?php echo $resultado . '/' . $evidencia['nombre_documento']; ?>"><?php echo $evidencia['nombre_documento']; ?></a></td>
                            </div>
                            <div class="col-md-3">
                                <a href="delete_archivo_supervision.php?id=<?php echo $evidencia['id_rel_supervision_evidencias']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este archivo?');">
                                    <span class="material-symbols-outlined">
                                        delete
                                    </span>
                                </a>
                            </div> <br><br>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="form-group clearfix">
                    <a href="supervision_mecanismos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_supervision_mecanismos" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>