<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
$page_title = 'Agregar gestión';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
$id_folio = last_id_folios();

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level(7);
}
if ($nivel_user == 52) {
    page_require_level_exacto(52);
}
if ($nivel_user == 19) {
    redirect('home.php');
}
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 52) :
    redirect('home.php');
endif;
?>
<?php
if (isset($_POST['add_gestion'])) {
    $documento = array();
    if (empty($errors)) {
        $tipo_gestion = remove_junk($db->escape($_POST['tipo_gestion']));
        $descripcion = remove_junk($db->escape($_POST['descripcion']));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
        $liga = remove_junk($db->escape($_POST['liga']));
        date_default_timezone_set('America/Mexico_City');
        $fecha_subida = date('Y-m-d H:i:s');

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
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-GESTJ';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-GESTJ';
        $carpeta = 'uploads/gestiones/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        // $name = $_FILES['adjunto']['name'];
        // $size = $_FILES['adjunto']['size'];
        // $type = $_FILES['adjunto']['type'];
        // $temp = $_FILES['adjunto']['tmp_name'];

        // $move =  move_uploaded_file($temp, $carpeta . "/" . $name);

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
		/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}


        $dbh = new PDO('mysql:host=localhost;dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');

        $query = "INSERT INTO gestiones_jurisdiccionales (";
        $query .= "folio,tipo_gestion, liga, descripcion, observaciones, fecha_subida";
        $query .= ") VALUES (";
        $query .= " '{$folio}','{$tipo_gestion}','{$liga}','{$descripcion}','{$observaciones}','{$fecha_subida}'";
        $query .= ")";

        $query2 = "INSERT INTO folios (";
        $query2 .= "folio, contador";
        $query2 .= ") VALUES (";
        $query2 .= " '{$folio}','{$no_folio1}'";
        $query2 .= ")";

        $dbh->exec($query2);
        $dbh->exec($query);
        $id_gestion = $dbh->lastInsertId();
        for($i = 0; $i < sizeof($documento); $i = $i + 1){
            echo "VALOR: " . $i;
            echo $documento[$i];
        }
        for ($i = 0; $i < sizeof($documento); $i = $i + 1) {
            $queryInsert = "INSERT INTO rel_gestiones (id_gestion,documento) VALUES('$id_gestion','$documento[$i]')";
            if ($db->query($queryInsert)) {
                //echo 'insertado';                
                insertAccion($user['id_user'], '"' . $user['username'] . '" agregó el archivo para ' . $documento[$i] . ', del Folio: ' . $folio . '.', 1);
            } else {
                //echo 'falla';
            }
        }
        if ($queryInsert) {

            //sucess
            if ($tipo_gestion == 'Acciones de Inconstitucionalidad') {
                insertAccion($user['id_user'], '"' . $user['username'] . '" agregó Acción Inconst., Folio: ' . $folio . '.', 1);
            } elseif ($tipo_gestion == 'Controversias Constitucionales') {
                insertAccion($user['id_user'], '"' . $user['username'] . '" agregó Controversia Const., Folio: ' . $folio . '.', 1);
            } elseif ($tipo_gestion == 'Amicus Curiae') {
                insertAccion($user['id_user'], '"' . $user['username'] . '" agregó Amicus Curiae, Folio: ' . $folio . '.', 1);
            }

            

            $session->msg('s', "Registro creado con éxito");
            redirect('gestiones.php', false);
        } else {
            //failed
            $session->msg('d', 'Desafortunadamente no se pudo crear el registro.');
            redirect('gestiones.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('gestiones.php', false);
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
</script>
<?php include_once('layouts/header.php'); ?>
<div class="login-page3">
    <div class="text-center">
        <h3 style="margin-top: 20px; margin-bottom: 20px;">Agregar nuevo registro</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="add_gestion.php" class="clearfix" enctype="multipart/form-data">
        <div class="form-group">
            <label for="tipo_gestion" class="control-label">Tipo de Gestión Jurisdiccional</label>
            <select class="form-control" name="tipo_gestion" id="tipo_gestion" required>
                <?php if ((int)$_GET['a'] == 1) {
                    echo '<option selected="true" value="Acciones de Inconstitucionalidad">Acciones de Inconstitucionalidad</option>';
                } ?>
                <?php if ((int)$_GET['a'] == 2) {
                    echo '<option selected="true" value="Controversias Constitucionales">Controversias Constitucionales</option>';
                } ?>
                <?php if ((int)$_GET['a'] == 3) {
                    echo '<option selected="true" value="Amicus Curiae">Amicus Curiae</option>';
                } ?>
                <?php if ((int)$_GET['a'] == 4) {
                    echo '<option value="">Escoge una opción</option><option value="Acciones de Inconstitucionalidad">Acciones de Inconstitucionalidad</option><option value="Controversias Constitucionales">Controversias Constitucionales</option><option value="Amicus Curiae">Amicus Curiae</option><option value="Recursos e impugnaciones">Recursos e impugnaciones</option><option value="Otras gestiones">Otras gestiones</option>';
                } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" name="descripcion" cols="10" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="liga">Liga</label>
            <textarea class="form-control" name="liga" cols="10" rows="2"></textarea>
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
            <textarea class="form-control" name="observaciones" cols="10" rows="4"></textarea>
        </div>
        <div class="form-group clearfix">
            <a href="gestiones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="add_gestion" class="btn btn-info">Guardar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>