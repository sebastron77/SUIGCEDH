<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Síntesis Diaria';
require_once('includes/load.php');

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

$sintesis = find_by_id('sintesis_diaria',(int)$_GET['id'],'id_sintesis_diaria');

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

if (isset($_POST['edit_sintesis_diaria'])) {

    if (empty($errors)) {
        $id = (int)$sintesis['id_sintesis_diaria'];
        $fecha = remove_junk($db->escape($_POST['fecha']));
        $tipo   = remove_junk($db->escape($_POST['tipo']));
        $link   = remove_junk($db->escape($_POST['link']));

        $sql = "UPDATE sintesis_diaria SET fecha='{$fecha}', tipo='{$tipo}', link='{$link}' WHERE id_sintesis_diaria='{$db->escape($id)}'";

        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó síntesis Diaria de Folio: -' . $sintesis['folio'], 2);
            $session->msg('s', " La Síntesis Diaria con folio '" . $sintesis['folio'] . "' ha sido acuatizada con éxito.");
            redirect('sintesis_diaria.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron cambios a la informacion.');
            redirect('edit_sintesis_diaria.php?id=' . (int)$sintesis['id_difusion'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_sintesis_diaria.php', false);
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
                <span>Editar Síntesis Diaria</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_sintesis_diaria.php?id=<?php echo (int)$sintesis['id_sintesis_diaria']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha</label><br>
                            <input type="date" class="form-control" name="fecha" value="<?php echo $sintesis['fecha'] ?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="accion">Tiempo de Sesión</label><br>
                            <select class="form-control" name="tipo" id="tipo" required>
                                <option value="">Escoge una opción</option>
                                <option <?php if ($sintesis['tipo'] === 'Matutina') echo 'selected="selected"'; ?>  value="Matutina">Matutina</option>
                                <option <?php if ($sintesis['tipo'] === 'Vespertina') echo 'selected="selected"'; ?>  value="Vespertina">Vespertina</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="link">Link</label><br>
                            <input type="text" class="form-control" name="link" value="<?php echo $sintesis['link'] ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group clearfix">
                        <a href="sintesis_diaria.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="edit_sintesis_diaria" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>