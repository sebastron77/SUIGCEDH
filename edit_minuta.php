<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Minuta';
require_once('includes/load.php');
$user = current_user();
$e_minuta = find_by_id('minutas', (int)$_GET['id'], 'id_minutas');
$id_folio = last_id_folios();
$nivel_user = $user['user_level'];
$year = date("Y");
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
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['edit_minuta'])) {

    $req_fields = array('num_sesion', 'tipo_sesion', 'fecha_sesion', 'hora', 'lugar', 'num_asistentes');
    validate_fields($req_fields);

    if (empty($errors)) {
        $id = (int)$e_minuta['id_minutas'];
        $num_sesion = remove_junk($db->escape($_POST['num_sesion']));
        $tipo_sesion = remove_junk($db->escape($_POST['tipo_sesion']));
        $fecha_sesion = remove_junk($db->escape($_POST['fecha_sesion']));
        $hora = remove_junk($db->escape($_POST['hora']));
        $lugar = remove_junk(upper_case($db->escape($_POST['lugar'])));
        $num_asistentes = remove_junk(upper_case($db->escape($_POST['num_asistentes'])));
        $avance_acuerdos = remove_junk(upper_case($db->escape($_POST['avance_acuerdos'])));
        $observaciones = remove_junk(upper_case($db->escape($_POST['observaciones'])));

        $folio_editar = $e_minuta['folio'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/minutas/' . $resultado;

        $name = $_FILES['lista_asistencia']['name'];
        $size = $_FILES['lista_asistencia']['size'];
        $type = $_FILES['lista_asistencia']['type'];
        $temp = $_FILES['lista_asistencia']['tmp_name'];

        if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else{
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }

        $size2 = $_FILES['archivo_minuta']['size'];
        $name2 = $_FILES['archivo_minuta']['name'];
        $type2 = $_FILES['archivo_minuta']['type'];
        $temp2 = $_FILES['archivo_minuta']['tmp_name'];

        if (is_dir($carpeta)) {
            $move2 =  move_uploaded_file($temp2, $carpeta . "/" . $name2);
        } else{
            mkdir($carpeta, 0777, true);
            $move2 =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }

        if ($name2 != '' && $name != '') {
            $sql = "UPDATE minutas SET num_sesion='{$num_sesion}', tipo_sesion='{$tipo_sesion}', fecha_sesion='{$fecha_sesion}', hora='{$hora}', 
                    lugar='{$lugar}', num_asistentes='{$num_asistentes}', avance_acuerdos='{$avance_acuerdos}', archivo_minuta='{$name2}', 
                    lista_asistencia='{$name}', observaciones='{$observaciones}' 
                    WHERE id_minutas='{$db->escape($id)}'";
        }
        if ($name2 == '' && $name == '') {
            $sql = "UPDATE minutas SET num_sesion='{$num_sesion}', tipo_sesion='{$tipo_sesion}', fecha_sesion='{$fecha_sesion}', hora='{$hora}', 
                    lugar='{$lugar}', num_asistentes='{$num_asistentes}', avance_acuerdos='{$avance_acuerdos}', observaciones='{$observaciones}' 
                    WHERE id_minutas='{$db->escape($id)}'";
        }
        if ($name2 == '' && $name != '') {
            $sql = "UPDATE minutas SET num_sesion='{$num_sesion}', tipo_sesion='{$tipo_sesion}', fecha_sesion='{$fecha_sesion}', hora='{$hora}', 
                    lugar='{$lugar}', num_asistentes='{$num_asistentes}', avance_acuerdos='{$avance_acuerdos}', lista_asistencia='{$name}', 
                    observaciones='{$observaciones}' 
                    WHERE id_minutas='{$db->escape($id)}'";
        }
        if ($name2 != '' && $name == '') {
            $sql = "UPDATE minutas SET num_sesion='{$num_sesion}', tipo_sesion='{$tipo_sesion}', fecha_sesion='{$fecha_sesion}', hora='{$hora}', 
                    lugar='{$lugar}', num_asistentes='{$num_asistentes}', avance_acuerdos='{$avance_acuerdos}', archivo_minuta='{$name2}', 
                    observaciones='{$observaciones}' 
                    WHERE id_minutas='{$db->escape($id)}'";
        }
        
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', " La minuta ha sido editada con éxito.");
            insertAccion($user['id_user'], '"'.$user['username'].'" editó registro en minutas('.$id.'), Folio: '.$folio_editar.'.', 2);
            redirect('minutas.php?anio='. $year, false);
        } else {
            //failed
            $session->msg('d', ' No se pudo editar la minuta.');
            redirect('edit_minuta.php?id=' . (int)$e_minuta['id_minutas'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_minuta.php?id=' . (int)$e_minuta['id_minutas'], false);
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
                <span>Editar Minuta</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_minuta.php?id=<?php echo (int)$e_minuta['id_minutas']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="num_sesion">Número Sesión</label>
                            <input type="text" class="form-control" name="num_sesion" value="<?php echo remove_junk($e_minuta['num_sesion']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_sesion">Tipo Sesión</label>
                            <select class="form-control" name="tipo_sesion">
                                <option <?php if ($e_minuta['tipo_sesion'] === 'Ordinaria') echo 'selected="selected"'; ?> value="Ordinaria">Ordinaria</option>
                                <option <?php if ($e_minuta['tipo_sesion'] === 'Extraordinaria') echo 'selected="selected"'; ?> value="Extraordinaria">Extraordinaria</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_sesion">Fecha de Sesión</label>
                            <input type="date" class="form-control" name="fecha_sesion" value="<?php echo remove_junk($e_minuta['fecha_sesion']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="hora">Hora</label>
                            <input type="time" class="form-control" name="hora" value="<?php echo remove_junk($e_minuta['hora']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="num_asistentes">No. asistentes</label>
                            <input type="number" class="form-control" name="num_asistentes" value="<?php echo remove_junk($e_minuta['num_asistentes']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lugar">Lugar</label>
                            <input type="text" class="form-control" name="lugar" value="<?php echo remove_junk($e_minuta['lugar']); ?>" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="avance_acuerdos">Avance Acuerdos</label>
                            <textarea class="form-control" name="avance_acuerdos" rows="5"><?php echo $e_minuta['avance_acuerdos']?></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" rows="5"><?php echo $e_minuta['observaciones']?></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="archivo_minuta">Archivo Minuta</label>
                            <input type="file" accept="application/pdf" class="form-control" name="archivo_minuta" id="archivo_minuta" value="<?php echo $e_minuta['archivo_minuta'];?>">
                            <label style="font-size:12px; color:#E3054F;" >Archivo Actual: <?php echo remove_junk($e_minuta['archivo_minuta']); ?><?php ?></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="lista_asistencia">Lista de Asistencia</label>
                            <input type="file" accept="application/pdf" class="form-control" name="lista_asistencia" id="lista_asistencia" value="<?php echo $e_minuta['lista_asistencia'];?>">
                            <label style="font-size:12px; color:#E3054F;" >Archivo Actual: <?php echo remove_junk($e_minuta['lista_asistencia']); ?><?php ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="minutas.php?anio=<?php echo $year; ?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_minuta" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>