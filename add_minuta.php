<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Minuta';
require_once('includes/load.php');
$user = current_user();
$detalle = $user['id_user'];
$id_folio = last_id_folios_general();
$nivel_user = $user['user_level'];
$id_minuta = last_sesion_minuta();
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
if (isset($_POST['add_minuta'])) {
    $req_fields = array('num_sesion', 'tipo_sesion', 'fecha_sesion', 'hora', 'lugar', 'num_asistentes');
    validate_fields($req_fields);

    if (empty($errors)) {
        $num_sesion = remove_junk($db->escape($_POST['num_sesion']));
        $tipo_sesion = remove_junk($db->escape($_POST['tipo_sesion']));
        $fecha_sesion = remove_junk($db->escape($_POST['fecha_sesion']));
        $hora = remove_junk($db->escape($_POST['hora']));
        $lugar = remove_junk(upper_case($db->escape($_POST['lugar'])));
        $num_asistentes = remove_junk(upper_case($db->escape($_POST['num_asistentes'])));
        $avance_acuerdos = remove_junk(upper_case($db->escape($_POST['avance_acuerdos'])));
        $observaciones = remove_junk(upper_case($db->escape($_POST['observaciones'])));

        //Suma el valor del id anterior + 1, para generar ese id para el nuevo resguardo
        //La variable $no_folio sirve para el numero de folio
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
        // Se crea el folio orientacion
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-MIN';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-MIN';
        $carpeta = 'uploads/minutas/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }        

        $name = $_FILES['lista_asistencia']['name'];
        $size = $_FILES['lista_asistencia']['size'];
        $type = $_FILES['lista_asistencia']['type'];
        $temp = $_FILES['lista_asistencia']['tmp_name'];

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        /*creo archivo index para que no se muestre el Index Of*/
        $source = 'uploads/index.php';
        if (copy($source, $carpeta . '/index.php')) {
            echo "El archivo ha sido copiado exitosamente.";
        } else {
            echo "Ha ocurrido un error al copiar el archivo.";
        }

        $name2 = $_FILES['archivo_minuta']['name'];
        $size2 = $_FILES['archivo_minuta']['size'];
        $type2 = $_FILES['archivo_minuta']['type'];
        $temp2 = $_FILES['archivo_minuta']['tmp_name'];

        $move2 =  move_uploaded_file($temp2, $carpeta . "/" . $name2);
        /*creo archivo index para que no se muestre el Index Of*/
        $source = 'uploads/index.php';
        if (copy($source, $carpeta . '/index.php')) {
            echo "El archivo ha sido copiado exitosamente.";
        } else {
            echo "Ha ocurrido un error al copiar el archivo.";
        }

        $query = "INSERT INTO minutas (";
        $query .= "folio, num_sesion, tipo_sesion, fecha_sesion, hora, lugar, num_asistentes, avance_acuerdos, archivo_minuta, lista_asistencia, observaciones, 
                    user_creador, fecha_creacion";
        $query .= ") VALUES (";
        $query .= " '{$folio}', '{$num_sesion}', '{$tipo_sesion}', '{$fecha_sesion}', '{$hora}', '{$lugar}', '{$num_asistentes}', '{$avance_acuerdos}', 
                    '{$name2}', '{$name}', '{$observaciones}', '{$detalle}', NOW()";
        $query .= ")";

        $query2 = "INSERT INTO folios (";
		$query2 .= "folio, contador";
		$query2 .= ") VALUES (";
		$query2 .= " '{$folio}','{$no_folio1}'";
		$query2 .= ")";

        if ($db->query($query) && $db->query($query2)) {
            //sucess
            $session->msg('s', " El registro se ha agregado con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó registro en minuta, Num. Sesión: ' . $num_sesion . ' del año ' . $year . '.', 1);
            redirect('minutas.php?anio='. $year, false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar el registro.');
            redirect('add_minuta.php', false);
        }
    } else {
        $session->msg("d", ' No se pudo agregar el registros.' . $errors);
        redirect('add_minuta.php', false);
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
                <span>Agregar</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_minuta.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="num_sesion"># de Sesión</label>
                            <input type="text" class="form-control" name="num_sesion" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_sesion">Tipo de sesión</label>
                            <select class="form-control" name="tipo_sesion" required>
                                <option value="">Escoge una opción</option>
                                <option value="Ordinaria">Ordinaria</option>
                                <option value="Extraordinaria">Extraordinaria</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_sesion">Fecha de Sesión</label>
                            <input type="date" class="form-control" name="fecha_sesion" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="hora">Hora</label>
                            <input type="time" class="form-control" name="hora" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="num_asistentes">Núm. de asistentes</label>
                            <input type="number" class="form-control" min="1" name="num_asistentes" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lugar">Lugar</label>
                            <input type="text" class="form-control" name="lugar" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="avance_acuerdos">Avance Acuerdos</label>
                            <textarea class="form-control" name="avance_acuerdos" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="archivo_minuta">Archivo Minuta</label>
                            <input type="file" accept="application/pdf" class="form-control" name="archivo_minuta" id="archivo_minuta">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="lista_asistencia">Lista de Asistencia</label>
                            <input type="file" accept="application/pdf" class="form-control" name="lista_asistencia" id="lista_asistencia">
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="minutas.php?anio=<?php echo $year; ?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_minuta" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>