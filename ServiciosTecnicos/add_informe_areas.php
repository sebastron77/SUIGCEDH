<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Informe de Actividades';
require_once('includes/load.php');

$user = current_user();
$detalle = $user['id_user'];
$id_folio = last_id_folios();
$id_info_a = last_id_info_areas();
$id_user = $user['id_user'];
$areas = find_all('area');
$area_user = area_usuario2($id_user);
$area_creacion = $area_user['nombre_area'];
?>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_informe_areas'])) {

    $req_fields = array('no_informe', 'fecha_informe', 'fecha_entrega');
    validate_fields($req_fields);

    if (empty($errors)) {
        $no_informe   = remove_junk($db->escape($_POST['no_informe']));
        $oficio_entrega   = remove_junk(($db->escape($_POST['oficio_entrega'])));
        $fecha_informe   = remove_junk($db->escape($_POST['fecha_informe']));
        $fecha_entrega   = remove_junk($db->escape($_POST['fecha_entrega']));
        $informe_adjunto   = remove_junk(($db->escape($_POST['informe_adjunto'])));

        if (count($id_info_a) == 0) {
            $nuevo_id_info_a = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_info_a as $nuevo) {
                $nuevo_id_info_a = (int) $nuevo['id_info_act_areas'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['id_info_act_areas'] + 1);
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

        $year = date("Y");
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-INF';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-INF';
        $carpeta = 'uploads/informesareas/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['oficio_entrega']['name'];
        $size = $_FILES['oficio_entrega']['size'];
        $type = $_FILES['oficio_entrega']['type'];
        $temp = $_FILES['oficio_entrega']['tmp_name'];

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);

        $name2 = $_FILES['informe_adjunto']['name'];
        $size2 = $_FILES['informe_adjunto']['size'];
        $type2 = $_FILES['informe_adjunto']['type'];
        $temp2 = $_FILES['informe_adjunto']['tmp_name'];

        $move2 =  move_uploaded_file($temp2, $carpeta . "/" . $name2);

            $query = "INSERT INTO informe_actividades_areas (";
            $query .= "folio, no_informe, fecha_informe, fecha_entrega, informe_adjunto, area_creacion";
			if($name){
				$query .= ",oficio_entrega ";				
			}
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$no_informe}','{$fecha_informe}','{$fecha_entrega}','{$name2}', '{$area_creacion}'";
			if($name){
				$query .= ",'{$name}' ";				
			}
            $query .= ")";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";
        if ($move && $name != '' && $name2 != '') {
        }

        if ($db->query($query) && $db->query($query2)) {
            //success
            $session->msg('s', " El informe de actividades ha sido agregado con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó informe de área, Folio: ' . $folio . '.', 1);
            redirect('informes_areas.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar el informe de actividades.');
            redirect('add_informe_areas.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_informe_areas.php', false);
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
                <span>Agregar Informe de Actividades</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_informe_areas.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="no_informe">Número de Informe</label>
                            <input type="text" class="form-control" name="no_informe" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="oficio_entrega">Adjuntar de oficio de entrega</label>
                            <input type="file" accept="application/pdf" class="form-control" name="oficio_entrega" id="oficio_entrega">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_informe">Fecha de informe</label>
                            <input type="date" class="form-control" name="fecha_informe" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_entrega">Fecha de entrega</label>
                            <input type="date" class="form-control" name="fecha_entrega" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="informe_adjunto">Adjuntar informe</label>
                            <input type="file" accept="application/pdf" class="form-control" name="informe_adjunto" id="informe_adjunto" required>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="informes_areas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_informe_areas" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>