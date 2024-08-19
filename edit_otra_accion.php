<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Otra Acción';
require_once('includes/load.php');

$difusiones = find_all_order('cat_tipo_difusion', 'id_cat_tipo_dif');
$difusion = find_by_id_accion((int)$_GET['id']);
$acciones = find_all_order('cat_otras_acciones', 'descripcion');
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

if (isset($_POST['edit_otra_accion'])) {

    if (empty($errors)) {
        $id = (int)$difusion['id_otra_accion'];
        $fecha = remove_junk($db->escape($_POST['fecha']));
        $accion   = remove_junk($db->escape($_POST['accion']));
        $tema   = remove_junk($db->escape($_POST['tema']));
        $area_solicita   = remove_junk($db->escape($_POST['area_solicita']));

        $year = date("Y");
        // Se crea el folio de canalizacion
        $folio_editar = $difusion['folio'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/difusiones_CS/' . $resultado;

        $name = $_FILES['adjunto']['name'];
        $size = $_FILES['adjunto']['size'];
        $type = $_FILES['adjunto']['type'];
        $temp = $_FILES['adjunto']['tmp_name'];

        if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else{
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }
        if ($name != '') {
            $sql = "UPDATE otras_acciones SET fecha='{$fecha}', accion='{$accion}', tema='{$tema}', area_solicita='{$area_solicita}', archivo='{$name}' WHERE id_otra_accion='{$db->escape($id)}'";
        }
        if ($name == '') {
            $sql = "UPDATE otras_acciones SET fecha='{$fecha}', accion='{$accion}', tema='{$tema}', area_solicita='{$area_solicita}' WHERE id_otra_accion='{$db->escape($id)}'";
        }

        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó Otra Acción de Folio: -' . $difusion['folio'], 2);
            $session->msg('s', " La Otra Acción con folio '" . $difusion['folio'] . "' ha sido acuatizado con éxito.");
            redirect('otras_acciones.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron cambios a la informacion.');
            redirect('edit_otra_accion.php?id=' . (int)$difusion['id_difusion'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_otra_accion.php', false);
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
                <span>Editar Otra Acción</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_otra_accion.php?id=<?php echo (int)$difusion['id_otra_accion']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha<span style="color:red;font-weight:bold"> *</span></label><br>
                            <input type="date" class="form-control" name="fecha" value="<?php echo $difusion['fecha'] ?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="accion">Tipo Acción<span style="color:red;font-weight:bold"> *</span></label><br>
                            <select class="form-control" name="accion" required>
                                <option value="">Selecciona un tipo</option>
                                <?php foreach ($acciones as $accion) : ?>
                                    <option <?php if ($difusion['accion'] === $accion['id_cat_otra_accion']) echo 'selected="selected"'; ?> value="<?php echo $accion['id_cat_otra_accion']; ?>"><?php echo ucwords($accion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tema">Tema<span style="color:red;font-weight:bold"> *</span></label><br>
                            <input type="text" class="form-control" name="tema" value="<?php echo $difusion['tema'] ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="area_solicita">Área Solicita</label>
                            <select class="form-control" name="area_solicita" required>
                                <option value="">Selecciona un tipo</option>
                                <?php foreach ($areas as $area) : ?>
                                    <option <?php if ($difusion['area_solicita'] === $area['id_area']) echo 'selected="selected"'; ?> value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <?php
                $folio_editar = $difusion['folio'];
                $resultado = str_replace("/", "-", $folio_editar);
                ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="adjunto">PDF</label>
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto" value="uploads/otras_acciones/<?php echo $resultado . '/' . $difusion['archivo']; ?>">
                            <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($difusion['archivo']); ?><?php ?></label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group clearfix">
                        <a href="otras_acciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="edit_otra_accion" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
			
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>