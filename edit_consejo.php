<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Consejo';
require_once('includes/load.php');
$user = current_user();
$detalle = $user['id_user'];
$a_consejo = find_by_id_consejo((int)$_GET['id']);
$id_folio = last_id_folios();
$nivel = $user['user_level'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 3) {
    page_require_level_exacto(3);
}
if ($nivel_user == 5) {
    redirect('home.php');
}
if ($nivel_user == 7) {
    page_require_level(7);
}
if ($nivel_user == 21) {
    page_require_level_exacto(21);
}
if ($nivel_user == 19) {
    redirect('home.php');
}
if ($nivel_user > 3 && $nivel_user < 5) :
    redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7) :
    redirect('home.php');
endif;
if ($nivel_user > 19 && $nivel_user < 21) :
    redirect('home.php');
endif;
$inticadores_pat = find_all_pat(10);
?>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['edit_consejo'])) {

    $req_fields = array('num_sesion', 'tipo_sesion', 'fecha_sesion', 'hora', 'lugar', 'num_asistentes');
    validate_fields($req_fields);

    if (empty($errors)) {
        $id = (int)$a_consejo['id_acta_consejo'];
        $num_sesion   = remove_junk($db->escape($_POST['num_sesion']));
        $tipo_sesion   = remove_junk($db->escape($_POST['tipo_sesion']));
        $fecha_sesion   = remove_junk($db->escape($_POST['fecha_sesion']));
        $hora   = remove_junk($db->escape($_POST['hora']));
        $lugar   = remove_junk(upper_case($db->escape($_POST['lugar'])));
        $num_asistentes   = remove_junk(upper_case($db->escape($_POST['num_asistentes'])));
        $orden_dia   = remove_junk($db->escape($_POST['orden_dia']));
        $acta_acuerdos   = remove_junk($db->escape($_POST['acta_acuerdos']));
		$id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));

        $folio_editar = $a_consejo['folio'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/consejo/' . $resultado;

        $name = $_FILES['orden_dia']['name'];
        $size = $_FILES['orden_dia']['size'];
        $type = $_FILES['orden_dia']['type'];
        $temp = $_FILES['orden_dia']['tmp_name'];

        if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else{
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }

        $name2 = $_FILES['acta_acuerdos']['name'];
        $size2 = $_FILES['acta_acuerdos']['size'];
        $type2 = $_FILES['acta_acuerdos']['type'];
        $temp2 = $_FILES['acta_acuerdos']['tmp_name'];

        if (is_dir($carpeta)) {
            $move2 =  move_uploaded_file($temp2, $carpeta . "/" . $name2);
        } else{
            mkdir($carpeta, 0777, true);
            $move2 =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }

        if ( $name2 != '') {
            $sql = "UPDATE consejo SET num_sesion='{$num_sesion}', tipo_sesion='{$tipo_sesion}', fecha_sesion='{$fecha_sesion}', hora='{$hora}', lugar='{$lugar}', num_asistentes='{$num_asistentes}',  id_indicadores_pat={$id_indicadores_pat}, acta_acuerdos='{$name2}' WHERE id_acta_consejo='{$db->escape($id)}'";
        }
        if ( $name2 == '') {
            $sql = "UPDATE consejo SET num_sesion='{$num_sesion}', tipo_sesion='{$tipo_sesion}', fecha_sesion='{$fecha_sesion}', hora='{$hora}', lugar='{$lugar}', num_asistentes='{$num_asistentes}',id_indicadores_pat={$id_indicadores_pat} WHERE id_acta_consejo='{$db->escape($id)}'";
        }
        
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', " El consejo ha sido editado con éxito.");
            insertAccion($user['id_user'], '"'.$user['username'].'" editó registro en consejo('.$id.'), Folio: '.$folio_editar.'.', 2);
            redirect('consejo.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo editar el consejo.');
            redirect('edit_consejo.php?id=' . (int)$a_consejo['id_acta_consejo'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_consejo.php?id=' . (int)$a_consejo['id_acta_consejo'], false);
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
                <span>Editar consejo</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_consejo.php?id=<?php echo (int)$a_consejo['id_acta_consejo']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="num_sesion">Número Sesión</label>
                            <input type="text" class="form-control" name="num_sesion" value="<?php echo remove_junk($a_consejo['num_sesion']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_sesion">Tipo Sesión</label>
                            <select class="form-control" name="tipo_sesion">
                                <option <?php if ($a_consejo['tipo_sesion'] === 'Ordinaria') echo 'selected="selected"'; ?> value="Ordinaria">Ordinaria</option>
                                <option <?php if ($a_consejo['tipo_sesion'] === 'Extraordinaria') echo 'selected="selected"'; ?> value="Extraordinaria">Extraordinaria</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_sesion">Fecha de Sesión</label>
                            <input type="date" class="form-control" name="fecha_sesion" value="<?php echo remove_junk($a_consejo['fecha_sesion']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="hora">Hora</label>
                            <input type="time" class="form-control" name="hora" value="<?php echo remove_junk($a_consejo['hora']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="num_asistentes">Número de asistentes</label>
                            <input type="number" class="form-control" name="num_asistentes" value="<?php echo remove_junk($a_consejo['num_asistentes']); ?>" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lugar">Lugar</label>
                            <input type="text" class="form-control" name="lugar" value="<?php echo remove_junk($a_consejo['lugar']); ?>" required>
                        </div>
                    </div>                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" required>
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  <?php if ($a_consejo['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="acta_acuerdos">Acta de acuerdos (firmada)</label>
                            <input type="file" accept="application/pdf" class="form-control" name="acta_acuerdos" value="<?php echo remove_junk($a_consejo['acta_acuerdos']); ?>" id="acta_acuerdos">
                            <label style="font-size:12px; color:#E3054F;" >Archivo Actual: <?php echo remove_junk($a_consejo['acta_acuerdos']); ?><?php ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="consejo.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_consejo" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>