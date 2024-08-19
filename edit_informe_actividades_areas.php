
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Informe de Actividades';
require_once('includes/load.php');
$user = current_user();
$detalle = $user['id_user'];
$e_informe = find_by_id('informe_actividades_areas', (int)$_GET['id'], 'id_info_act_areas');
$id_folio = last_id_folios_actividades_areas();
// page_require_level(2);.
?>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['edit_informe_actividades_areas'])) {

    $req_fields = array('no_informe', 'fecha_informe', 'fecha_entrega');
    validate_fields($req_fields);

    if (empty($errors)) {
        $id = (int)$e_informe['id_info_act_areas'];
        $no_informe   = remove_junk($db->escape($_POST['no_informe']));
        $fecha_informe   = remove_junk($db->escape($_POST['fecha_informe']));
        $fecha_entrega   = remove_junk($db->escape($_POST['fecha_entrega']));
        $oficio_entrega   = remove_junk($db->escape($_POST['oficio_entrega']));
        $informe_adjunto   = remove_junk(($db->escape($_POST['informe_adjunto'])));
        $liga_url   = remove_junk(($db->escape($_POST['liga_url'])));

        $folio_editar = $e_informe['folio'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/informesareas/' . $resultado;

        $name = $_FILES['oficio_entrega']['name'];
        $size = $_FILES['oficio_entrega']['size'];
        $type = $_FILES['oficio_entrega']['type'];
        $temp = $_FILES['oficio_entrega']['tmp_name'];

        if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else{
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }

        $name2 = $_FILES['informe_adjunto']['name'];
        $size2 = $_FILES['informe_adjunto']['size'];
        $type2 = $_FILES['informe_adjunto']['type'];
        $temp2 = $_FILES['informe_adjunto']['tmp_name'];

        if (is_dir($carpeta)) {
            $move2 =  move_uploaded_file($temp2, $carpeta . "/" . $name2);
        } else{
            mkdir($carpeta, 0777, true);
            $move2 =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }

        if ($name != '' && $name2 != '') {
            $sql = "UPDATE informe_actividades_areas SET no_informe='{$no_informe}', oficio_entrega='{$name}', fecha_informe='{$fecha_informe}', fecha_entrega='{$fecha_entrega}', informe_adjunto='{$name2}' WHERE id_info_act_areas='{$db->escape($id)}'";
        }
        if ($name == '' && $name2 == '') {
            $sql = "UPDATE informe_actividades_areas SET no_informe='{$no_informe}', fecha_informe='{$fecha_informe}', fecha_entrega='{$fecha_entrega}' WHERE id_info_act_areas='{$db->escape($id)}'";
        }
        if ($name != '' && $name2 == '') {
            $sql = "UPDATE informe_actividades_areas SET no_informe='{$no_informe}', oficio_entrega='{$name}', fecha_informe='{$fecha_informe}', fecha_entrega='{$fecha_entrega}' WHERE id_info_act_areas='{$db->escape($id)}'";
        }
        if ($name == '' && $name2 != '') {
            $sql = "UPDATE informe_actividades_areas SET no_informe='{$no_informe}', fecha_informe='{$fecha_informe}', fecha_entrega='{$fecha_entrega}', informe_adjunto='{$name2}' WHERE id_info_act_areas='{$db->escape($id)}'";
        }
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', " El informe de actividades ha sido editado con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó informe, Folio: ' . $folio . '.', 2);
            redirect('informes_areas.php?a='.$e_informe['area_creacion'], false);
        } else {
            //failed
            $session->msg('d', ' No se pudo editar el informe.');
            redirect('edit_informe_actividades_areas.php?id=' . (int)$e_informe['id_info_act_areas'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_informe_actividades_areas.php?id=' . (int)$e_informe['id_info_act_areas'], false);
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
                <span>Editar informe</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_informe_actividades_areas.php?id=<?php echo (int)$e_informe['id_info_act_areas']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="no_informe">Número de Informe</label>
                            <input type="text" class="form-control" name="no_informe" value="<?php echo remove_junk($e_informe['no_informe']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="oficio_entrega">Oficio de entrega</label>
                            <input type="file" accept="application/pdf" class="form-control" name="oficio_entrega" value="<?php echo remove_junk($e_informe['oficio_entrega']); ?>" id="oficio_entrega">
                            <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_informe['oficio_entrega']); ?><?php ?></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_informe">Fecha del informe</label>
                            <input type="date" class="form-control" name="fecha_informe" value="<?php echo remove_junk($e_informe['fecha_informe']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_entrega">Fecha del entrega</label>
                            <input type="date" class="form-control" name="fecha_entrega" value="<?php echo remove_junk($e_informe['fecha_entrega']); ?>" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="informe_adjunto">Informe</label>
                            <input type="file" accept="doc, .docx, .pdf" class="form-control" name="informe_adjunto" value="<?php echo remove_junk($e_informe['informe_adjunto']); ?>" id="caratula_informe">
                            <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_informe['informe_adjunto']); ?><?php ?></label>
                        </div>
                    </div>
					
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="observaciones_st">Observaciones de Revisión</label>
							<textarea class="form-control" name="observaciones_st" cols="10" rows="4" readonly><?php echo remove_junk($e_informe['observaciones_st']); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="informes_areas.php?a=<?php echo $e_informe['area_creacion']?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_informe_actividades_areas" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>