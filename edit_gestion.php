<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
$page_title = 'Editar Gestión';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) :
    page_require_level(2);
endif;
if ($nivel_user == 7) :
    page_require_level_exacto(7);
endif;
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7) :
    redirect('home.php');
endif;
?>
<?php
$e_detalle = find_by_id('gestiones_jurisdiccionales', (int) $_GET['id'], 'id_gestion');
$documentos = find_documentos_gestion((int) $_GET['id']);
if (!$e_detalle) {
    $session->msg("d", "id de la gestión no encontrado.");
    redirect('gestiones.php');
}
$folio_editar = $e_detalle['folio'];
$resultado = str_replace("/", "-", $folio_editar);
?>
<?php
if (isset($_POST['update'])) {
    $documento = array();
    if (empty($errors)) {
        $tipo_gestion = remove_junk($db->escape($_POST['tipo_gestion']));
        $descripcion = remove_junk($db->escape($_POST['descripcion']));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
        date_default_timezone_set('America/Mexico_City');


        $carpeta = 'uploads/gestiones/' . $resultado;

        $name = $_FILES['adjunto']['name'];
        $size = $_FILES['adjunto']['size'];
        $type = $_FILES['adjunto']['type'];
        $temp = $_FILES['adjunto']['tmp_name'];

        if (is_dir($carpeta)) {
            //$move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else {
            mkdir($carpeta, 0777, true);
            //$move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }
        if ($name != '') {
            $query = "UPDATE gestiones_jurisdiccionales SET ";
            $query .= "tipo_gestion='{$tipo_gestion}', descripcion='{$descripcion}', observaciones='{$observaciones}'";
            $query .= "WHERE id_gestion='{$db->escape($e_detalle['id_gestion'])}'";
        }
        if ($name == '') {
            $query = "UPDATE gestiones_jurisdiccionales SET ";
            $query .= "tipo_gestion='{$tipo_gestion}', descripcion='{$descripcion}', observaciones='{$observaciones}'";
            $query .= "WHERE id_gestion='{$db->escape($e_detalle['id_gestion'])}'";
        }

        $result = $db->query($query);

        foreach ($_FILES["adjunto"]['name'] as $key => $tmp_name) {
            //condicional si el fuchero existe
            if ($_FILES["adjunto"]["name"][$key]) {
                // Nombres de archivos de temporales
                $archivonombre = $_FILES["adjunto"]["name"][$key];
                $fuente = $_FILES["adjunto"]["tmp_name"][$key];
                array_push($documento, $archivonombre);

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
        $id = $e_detalle['id_gestion'];
        for ($i = 0; $i < sizeof($documento); $i = $i + 1) {
            $queryInsert = "INSERT INTO rel_gestiones (id_gestion,documento) VALUES($id,'$documento[$i]')";
            if ($db->query($queryInsert)) {
                //echo 'insertado';                
                insertAccion($user['id_user'], '"' . $user['username'] . '" agregó el archivo para ' . $documento[$i] . ', del Folio: ' . $folio . '.', 1);
            } else {
                //echo 'falla';
            }
        }
        //$result2 = $db->query($query2);

        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Registro actualizado con éxito. ");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó gestión jurisdiccional, Folio: ' . $e_detalle['folio'] . '.', 2);
            redirect('gestiones.php', false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el registro!');
            redirect('gestiones.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_gestion.php?id=' . (int) $e_detalle['id_gestion'], false);
    }
}
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#addRow").click(function() {
            var html = '';
            html += '<div id="inputFormRow">';
            html += '	<div class="form-group">';
            html += '       <div class="col-md-10">'
            html += '		    <input type="file" accept="application/pdf" class="form-control" name="adjunto[]" id="adjunto" style="width: 106%; margin-left: -3%;">';
            html += '	    </div>';
            html += '	</div>';
            html += '	<div class="col-md-2">';
            html += '	    <button type="button" class="btn btn-outline-danger" id="removeRow" > ';
            html += '   	    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">';
            html += '			<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>';
            html += '			<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>';
            html += '		    </svg>';
            html += '  	    </button>';
            html += '	</div> <br><br>';
            html += '</div> ';

            $('#newRow').append(html);
        });

        $(document).on('click', '#removeRow', function() {
            $(this).closest('#inputFormRow').remove();
        });
    });

    function deteleOfi(id) {
        if (confirm('¿Seguro que deseas eliminar este oficio, recuerda que una ves eliminado no se puede recuperar? ')) {
            if (id > 0) {
                $.post("delete_gestion.php", {
                    id: id
                }, function(a) {
                    $('#of' + id).remove();
                });
            }
        }
    }
</script>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-5">
        <div class="login-page3" style="margin-left: 1%; width: 100%;">
            <div class="text-center">
                <h3>Editar Gestión</h3>
            </div>
            <?php echo display_msg($msg); ?>
            <form method="post" action="edit_gestion.php?id=<?php echo (int) $e_detalle['id_gestion']; ?>" class="clearfix" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="tipo_gestion" class="control-label">Tipo de Gestión Jurisdiccional</label>
                    <select class="form-control" name="tipo_gestion" id="tipo_gestion">
                        <option <?php if ($e_detalle['tipo_gestion'] === 'Acciones de Inconstitucionalidad')
                                    echo 'selected="selected"'; ?> value="Acciones de Inconstitucionalidad">Acciones de Inconstitucionalidad
                        </option>
                        <option <?php if ($e_detalle['tipo_gestion'] === 'Controversias Constitucionales')
                                    echo 'selected="selected"'; ?> value="Controversias Constitucionales">Controversias Constitucionales
                        </option>
                        <option <?php if ($e_detalle['tipo_gestion'] === 'Amicus Curiae')
                                    echo 'selected="selected"'; ?> value="Amicus Curiae">Amicus Curiae</option>
                        <option <?php if ($e_detalle['tipo_gestion'] === 'Otros')
                                    echo 'selected="selected"'; ?> value="Otros">
                            Otros</option>
                            <option <?php if ($e_detalle['tipo_gestion'] === 'Recursos e impugnaciones')
                                    echo 'selected="selected"'; ?> value="Recursos e impugnaciones">
                            Recursos e impugnaciones</option>
                            <option <?php if ($e_detalle['tipo_gestion'] === 'Otras gestiones')
                                    echo 'selected="selected"'; ?> value="Otras gestiones">
                            Otras gestiones</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control" name="descripcion" cols="10" rows="4"><?php echo $e_detalle['descripcion'] ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="liga">Liga</label>
                    <textarea class="form-control" name="liga" cols="10" rows="2"><?php echo $e_detalle['liga'] ?></textarea>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="adjunto">Documento</label>
                        <div class="col-md-10">
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto[]" id="adjunto" style="width: 100%">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success" id="addRow" name="addRow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
                                    <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
                                    <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
                                </svg>
                            </button>
                        </div>
                    </div><br>
                    <div class="row" id="newRow">
                    </div>
                </div>
                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea class="form-control" name="observaciones" cols="10" rows="4"><?php echo $e_detalle['observaciones'] ?></textarea>
                </div>
                <div class="form-group clearfix">
                    <a href="gestiones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="update" class="btn btn-info">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-7 panel-body" style="height: 70%; margin-top: -5px;">
        <table class="table table-bordered table-striped" style="width: 100%;" id="tblProductos">
            <thead class="thead-purple">
                <tr style="height: 10px;">
                    <th colspan="3" style="text-align:center;">Documentos actuales de la Gestión Jurisdiccional</th>
                </tr>
                <tr style="height: 10px;">
                    <th>Documento</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($documentos as $envios) : ?>
                    <tr id="of<?php echo $envios['id_rel_gestion'] ?>">
                        <td style="text-align:center;">
                            <a target="_blank" style="color: #0094FF;" href="uploads/gestiones/<?php echo $resultado . '/' . $envios['documento']; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                    <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z" />
                                </svg>
                            </a>
                        </td>
                        <td style="text-align:center;">
                            <button type="button" class="btn btn-outline-danger" id="bttDelete" onclick="deteleOfi(<?php echo $envios['id_rel_gestion'] ?>)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">
                                    <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
                                    <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>
                                </svg>
                            </button>
                        </td>
                    </tr>
                <?php $i++;
                endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>