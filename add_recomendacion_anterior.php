<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Recomendación Anterior a 2022';
require_once('includes/load.php');
$id_add_recomendacion_anterior = last_id_folios_recomendaciones();
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    redirect('home.php');
}
if ($nivel == 4) {
    redirect('home.php');
}
if ($nivel == 5) {
    page_require_level_exacto(5);
}
if ($nivel == 6) {
    redirect('home.php');
}
if ($nivel == 7) {
    redirect('home.php');
}
// $queja = find_by_id_queja((int)$_GET['id']);
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_recomendacion_anterior'])) {

    $req_fields = array('autoridad_responsable', 'servidor_publico', 'fecha_recomendacion', 'observaciones');
    validate_fields($req_fields);

    if (empty($errors)) {
        $folio_queja   = remove_junk($db->escape($_POST['folio_queja']));
        $autoridad_responsable   = remove_junk($db->escape($_POST['autoridad_responsable']));
        $servidor_publico   = remove_junk($db->escape($_POST['servidor_publico']));
        $fecha_acuerdo   = remove_junk($db->escape($_POST['fecha_recomendacion']));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));
        $folio_recomendacion   = remove_junk($db->escape($_POST['folio_recomendacion']));
        $acuerdo_adjunto   = remove_junk(($db->escape($_POST['recomendacion_adjunto'])));

        if (count($id_add_recomendacion_anterior) == 0) {
            $nuevo_id_add_recomendacion_anterior = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_add_recomendacion_anterior as $nuevo) {
                $nuevo_id_add_recomendacion_anterior = (int)$nuevo['id'] + 1;
                $no_folio1 = sprintf('%04d', (int)$nuevo['id'] + 1);
            }
        }
        // //Se crea el número de folio
        // $year = date("Y");
        // // Se crea el folio de acuerdo
        // $add_recomendacion_anterior = 'CEDH/' . $no_folio1 . '/' . $year . '-RECGEN';

        $folio_queja_original = $folio_recomendacion;
        $folio_carpeta = str_replace("/", "-", $folio_queja_original);
        $carpeta = 'uploads/recomendacionesAnteriores2022/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['recomendacion_adjunto']['name'];
        $size = $_FILES['recomendacion_adjunto']['size'];
        $type = $_FILES['recomendacion_adjunto']['type'];
        $temp = $_FILES['recomendacion_adjunto']['tmp_name'];

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);

        $name2 = $_FILES['recomendacion_adjunto_publico']['name'];
        $size2 = $_FILES['recomendacion_adjunto_publico']['size'];
        $type2 = $_FILES['recomendacion_adjunto_publico']['type'];
        $temp2 = $_FILES['recomendacion_adjunto_publico']['tmp_name'];

        $move2 =  move_uploaded_file($temp2, $carpeta . "/" . $name2);
/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}

        if ($move && $name != '' && $name2 != '') {
            $query = "INSERT INTO recomendaciones (";
            $query .= "folio_recomendacion,folio_queja,autoridad_responsable,servidor_publico,fecha_recomendacion,observaciones,recomendacion_adjunto,recomendacion_adjunto_publico";
            $query .= ") VALUES (";
            $query .= " '{$folio_recomendacion}','{$folio_queja}','{$autoridad_responsable}','{$servidor_publico}','{$fecha_acuerdo}','{$observaciones}','{$name}','{$name2}'";
            $query .= ")";

            $query2 = "INSERT INTO folios_recomendaciones (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio_recomendacion}','{$no_folio1}'";
            $query2 .= ")";
        } else {
            $query = "INSERT INTO recomendaciones (";
            $query .= "folio_recomendacion,folio_queja,autoridad_responsable,servidor_publico,fecha_recomendacion,observaciones";
            $query .= ") VALUES (";
            $query .= " '{$folio_recomendacion}','{$folio_queja}','{$autoridad_responsable}','{$servidor_publico}','{$fecha_acuerdo}','{$observaciones}'";
            $query .= ")";

            $query2 = "INSERT INTO folios_recomendaciones (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio_recomendacion}','{$no_folio1}'";
            $query2 .= ")";
        }
        if ($db->query($query) && $db->query($query2)) {
            //sucess
            $session->msg('s', " La recomendación ha sido agregada con éxito.");
            redirect('recomendaciones.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la recomendación.');
            redirect('add_recomendacion_anterior.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_recomendacion_anterior.php', false);
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
                <span>Agregar Recomendación Anterior a 2022</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_recomendacion_anterior.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="folio_queja">Folio Recomendación</label>
                            <input type="text" class="form-control" name="folio_recomendacion" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="folio_queja">Folio de Queja</label>
                            <input type="text" class="form-control" name="folio_queja" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="autoridad_responsable">Autoridad Responsable</label>
                            <input type="text" class="form-control" name="autoridad_responsable" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="servidor_publico">Servidor público</label>
                            <input type="text" class="form-control" name="servidor_publico" placeholder="Nombre Completo" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_recomendacion">Fecha Recomendación</label><br>
                            <input type="date" class="form-control" name="fecha_recomendacion">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="1"></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <span>
                                <label for="recomendacion_adjunto">Adjuntar Recomendación</label>
                                <input id="recomendacion_adjunto" type="file" accept="application/pdf" class="form-control" name="recomendacion_adjunto">
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <span>
                                <label for="recomendacion_adjunto_publico">Adjuntar Recomendación Versión Pública</label>
                                <input id="recomendacion_adjunto_publico" type="file" accept="application/pdf" class="form-control" name="recomendacion_adjunto_publico">
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <a href="recomendaciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_recomendacion_anterior" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>