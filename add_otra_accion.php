<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Otra Acción';
require_once('includes/load.php');


$id_folio = last_id_folios();
$id_difusion = last_id_table('difusion', 'id_difusion');
$otras_acciones = find_all('cat_otras_acciones');
$acciones = find_all('cat_otras_acciones');
$areas = find_all_order('area', 'nombre_area');

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 15) {
    page_require_level(15);
}
if ($nivel > 2 && $nivel < 7) :
    redirect('home.php');
endif;
if ($nivel > 7  && $nivel < 15) :
    redirect('home.php');
endif;
if ($nivel > 15) :
    redirect('home.php');
endif;
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_otra_accion'])) {

    if (empty($errors)) {

        $fecha = remove_junk($db->escape($_POST['fecha']));
        $accion   = remove_junk($db->escape($_POST['accion']));
        $tema   = remove_junk($db->escape($_POST['tema']));
        $area_solicita   = remove_junk($db->escape($_POST['area_solicita']));

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
        // Se crea el folio de canalizacion
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-OA';
        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-OA';
        $carpeta = 'uploads/otras_acciones/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['adjunto']['name'];
        $size = $_FILES['adjunto']['size'];
        $type = $_FILES['adjunto']['type'];
        $temp = $_FILES['adjunto']['tmp_name'];

        $move = move_uploaded_file($temp, $carpeta . "/" . $name);
/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}


        $query = "INSERT INTO otras_acciones (";
        $query .= "folio, fecha, accion, tema, area_solicita, archivo, usuario_creador, fecha_creacion";
        $query .= ") VALUES (";
        $query .= " '{$folio}','{$fecha}','{$accion}','{$tema}','{$area_solicita}','{$name}','{$id_user}',NOW()); ";

        $query2 = "INSERT INTO folios (";
        $query2 .= "folio, contador";
        $query2 .= ") VALUES (";
        $query2 .= " '{$folio}','{$no_folio1}'";
        $query2 .= ")";

        if ($db->query($query) && $db->query($query2)) {
            //sucess
            insertAccion($user['id_user'], '"' . $user['username'] . '" dio de alta Otra Acción de Folio: -' . $folio . '-.', 1);
            $session->msg('s', " La Otra Acción con folio '{$folio}' ha sido agregado con éxito.");
            redirect('otras_acciones.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la Otra Acción.');
            redirect('add_otra_accion.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_otra_accion.php', false);
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
                <span>Agregar Otra Acción</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_otra_accion.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha<span style="color:red;font-weight:bold"> *</span></label><br>
                            <input type="date" class="form-control" name="fecha" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="accion">Acción<span style="color:red;font-weight:bold"> *</span></label><br>
                            <select class="form-control" name="accion">
                                <option value="">Selecciona Tipo de Acción</option>
                                <?php foreach ($acciones as $tipo_accion) : ?>
                                    <option value="<?php echo $tipo_accion['id_cat_otra_accion']; ?>"><?php echo ucwords($tipo_accion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="tema">Tema<span style="color:red;font-weight:bold"> *</span></label><br>
                            <input type="text" class="form-control" name="tema" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="area_solicita">Área que Solicita<span style="color:red;font-weight:bold"> *</span></label><br>
                            <select class="form-control" name="area_solicita">
                                <option value="">Seleccionar Área</option>
                                <?php foreach ($areas as $area) : ?>
                                    <option value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="adjunto">Adjuntar Archivo</label>
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group clearfix">
                        <a href="otras_acciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="add_otra_accion" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>