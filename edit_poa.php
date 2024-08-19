
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar POA';
require_once('includes/load.php');
$user = current_user();
$detalle = $user['id_user'];
$e_poa = find_by_id('poa', (int)$_GET['id'], 'id_poa');
$id_folio = last_id_folios();
$area=3;
// page_require_level(2);

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

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
    redirect('home.php');    
}
if ($nivel == 6) {
    redirect('home.php');
}
if ($nivel == 7) {
    page_require_level(7);
}

$inticadores_pat = find_all_pat($area);
?>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['edit_poa'])) {

    $req_fields = array('anio_ejercicio', 'fecha_recepcion');
    validate_fields($req_fields);

    if (empty($errors)) {
        $id = (int)$e_poa['id_poa'];
        $anio_ejercicio   = remove_junk($db->escape($_POST['anio_ejercicio']));
        $oficio_recibido   = remove_junk($db->escape($_POST['oficio_recibido']));
        $documento_poa   = remove_junk($db->escape($_POST['documento_poa']));
        $fecha_recepcion   = remove_junk($db->escape($_POST['fecha_recepcion']));
        $oficio_entrega   = remove_junk(($db->escape($_POST['oficio_entrega'])));
        $id_indicadores_pat   = remove_junk(($db->escape($_POST['id_indicadores_pat'])));

        $folio_editar = $e_poa['folio'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/poa/' . $resultado;

        $name = $_FILES['oficio_recibido']['name'];
        $size = $_FILES['oficio_recibido']['size'];
        $type = $_FILES['oficio_recibido']['type'];
        $temp = $_FILES['oficio_recibido']['tmp_name'];

        if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else{
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }

        $name2 = $_FILES['documento_poa']['name'];
        $size2 = $_FILES['documento_poa']['size'];
        $type2 = $_FILES['documento_poa']['type'];
        $temp2 = $_FILES['documento_poa']['tmp_name'];

        if (is_dir($carpeta)) {
            $move2 =  move_uploaded_file($temp2, $carpeta . "/" . $name2);
        } else{
            mkdir($carpeta, 0777, true);
            $move2 =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }

        $name3 = $_FILES['oficio_entrega']['name'];
        $size3 = $_FILES['oficio_entrega']['size'];
        $type3 = $_FILES['oficio_entrega']['type'];
        $temp3 = $_FILES['oficio_entrega']['tmp_name'];

        if (is_dir($carpeta)) {
            $move3 =  move_uploaded_file($temp3, $carpeta . "/" . $name3);
        } else{
            mkdir($carpeta, 0777, true);
            $move3 =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }

            $sql = "UPDATE poa SET anio_ejercicio='{$anio_ejercicio}',fecha_recepcion='{$fecha_recepcion}',id_indicadores_pat={$id_indicadores_pat}  ";
			
			if ($name != '') {
				$sql .= ", oficio_recibido='{$name}' ";
			}
			if ($name2 != '') {
				$sql .= ", documento_poa='{$name2}' ";
			}
			if ($name3 != '') {
				$sql .= ", oficio_entrega='{$name3}' ";
			}
            $sql .= "  WHERE id_poa='{$db->escape($id)}'";
        

        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', " El POA ha sido editado con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó POA, Folio: ' . $e_poa['folio'] . '.', 2);
            redirect('poa.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo editar el POA.');
            redirect('edit_poa.php?id=' . (int)$e_poa['id_poa'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_poa.php?id=' . (int)$e_poa['id_poa'], false);
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
                <span>Editar POA</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_poa.php?id=<?php echo (int)$e_poa['id_poa']; ?>" enctype="multipart/form-data">
            <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="form-group">
                            <label for="anio_ejercicio">Año o Ejercicio Fiscal</label>
                            <select class="form-control" name="anio_ejercicio" required>
                                <option <?php if ($e_poa['anio_ejercicio'] == '2012') echo 'selected="selected"'; ?> value="2012">2012</option>
                                <option <?php if ($e_poa['anio_ejercicio'] == '2013') echo 'selected="selected"'; ?> value="2013">2013</option>
                                <option <?php if ($e_poa['anio_ejercicio'] == '2014') echo 'selected="selected"'; ?> value="2014">2014</option>
                                <option <?php if ($e_poa['anio_ejercicio'] == '2015') echo 'selected="selected"'; ?> value="2015">2015</option>
                                <option <?php if ($e_poa['anio_ejercicio'] == '2016') echo 'selected="selected"'; ?> value="2016">2016</option>
                                <option <?php if ($e_poa['anio_ejercicio'] == '2017') echo 'selected="selected"'; ?> value="2017">2017</option>
                                <option <?php if ($e_poa['anio_ejercicio'] == '2018') echo 'selected="selected"'; ?> value="2018">2018</option>
                                <option <?php if ($e_poa['anio_ejercicio'] == '2019') echo 'selected="selected"'; ?> value="2019">2019</option>
                                <option <?php if ($e_poa['anio_ejercicio'] == '2020') echo 'selected="selected"'; ?> value="2020">2020</option>
                                <option <?php if ($e_poa['anio_ejercicio'] == '2021') echo 'selected="selected"'; ?> value="2021">2021</option>
                                <option <?php if ($e_poa['anio_ejercicio'] == '2022') echo 'selected="selected"'; ?> value="2022">2022</option>
                                <option <?php if ($e_poa['anio_ejercicio'] == '2023') echo 'selected="selected"'; ?> value="2023">2023</option>
                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_recepcion">Fecha de Recepción</label>
                            <input type="date" class="form-control" name="fecha_recepcion" value="<?php echo remove_junk($e_poa['fecha_recepcion']); ?>"  required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" required>
                                <option value="">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  <?php if ($e_poa['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="oficio_recibido">Adjuntar oficio recibido</label><br>
                            <input type="file" accept="application/pdf" class="form-control" name="oficio_recibido" value="<?php echo remove_junk($e_poa['oficio_recibido']); ?>"  id="oficio_recibido">
                            <label style="font-size:12px; color:#E3054F;" for="oficio_recibido">Archivo Actual: <?php echo remove_junk($e_poa['oficio_recibido']); ?><?php ?></label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="documento_poa">POA</label>
                            <input type="file" accept="application/pdf" class="form-control" name="documento_poa" value="<?php echo remove_junk($e_poa['documento_poa']); ?>"  id="poa">
                            <label style="font-size:12px; color:#E3054F;" for="documento_poa">Archivo Actual: <?php echo remove_junk($e_poa['documento_poa']); ?><?php ?></label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="oficio_entrega">Adjuntar Oficio de Entrega</label>
                            <input type="file" accept="application/pdf" class="form-control" name="oficio_entrega" value="<?php echo remove_junk($e_poa['oficio_entrega']); ?>"  id="oficio_entrega">
                            <label style="font-size:12px; color:#E3054F;" for="oficio_entrega">Archivo Actual: <?php echo remove_junk($e_poa['oficio_entrega']); ?><?php ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="poa.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_poa" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>