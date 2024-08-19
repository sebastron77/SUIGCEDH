<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Síntesis Diaria';
require_once('includes/load.php');

$id_folio = last_id_folios();
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

if (isset($_POST['add_sintesis_diaria'])) {

    if (empty($errors)) {

        $fecha = remove_junk($db->escape($_POST['fecha']));
        $tipo   = remove_junk($db->escape($_POST['tipo']));
        $link   = remove_junk($db->escape($_POST['link']));

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
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-SINTD';

        $query = "INSERT INTO sintesis_diaria (";
        $query .= "folio, fecha, tipo, link, usuario_creador, fecha_creacion";
        $query .= ") VALUES (";
        $query .= " '{$folio}','{$fecha}','{$tipo}','{$link}','{$id_user}',NOW()); ";

        $query2 = "INSERT INTO folios (";
        $query2 .= "folio, contador";
        $query2 .= ") VALUES (";
        $query2 .= " '{$folio}','{$no_folio1}'";
        $query2 .= ")";

        if ($db->query($query) && $db->query($query2)) {
            //sucess
            insertAccion($user['id_user'], '"' . $user['username'] . '" dio de alta Síntesis Diaria de Folio: -' . $folio . '-.', 1);
            $session->msg('s', " La Síntesis Diaria con folio '{$folio}' ha sido agregada con éxito.");
            redirect('sintesis_diaria.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la Síntesis Diaria.');
            redirect('add_sintesis_diaria.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_sintesis_diaria.php', false);
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
                <span>Agregar Síntesis Diaria</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_sintesis_diaria.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha</label><br>
                            <input class="form-control" type="date" name="fecha" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo">Tiempo de Sesión</label><br>
                            <select class="form-control" name="tipo" id="tipo" required>
                                <option value="">Escoge una opción</option>
                                <option value="Matutina">Matutina</option>
                                <option value="Vespertina">Vespertina</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="link">Link</label><br>
                            <input type="text" class="form-control" name="link" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group clearfix">
                        <a href="sintesis_diaria.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="add_sintesis_diaria" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>