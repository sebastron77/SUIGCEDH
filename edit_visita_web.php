<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Editar Visita Web';
require_once('includes/load.php');

$visitas_web = find_by_id('visitas_web', (int)$_GET['id'], 'id_visitas');

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 13) {
    page_require_level(15);
}
if ($nivel > 2 && $nivel < 7) :
    redirect('home.php');
endif;
if ($nivel > 7  && $nivel < 13) :
    redirect('home.php');
endif;
if ($nivel > 13) :
    redirect('home.php');
endif;
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_visita_web'])) {

    if (empty($errors)) {
        $id = (int)$visitas_web['id_visitas'];
        $ejercicio = remove_junk($db->escape($_POST['ejercicio']));
        $mes = remove_junk($db->escape($_POST['mes']));
        $desktop = remove_junk($db->escape($_POST['desktop']));
        $movil = remove_junk($db->escape($_POST['movil']));
        $tablet = remove_junk($db->escape($_POST['tablet']));
        $vistas_a_pag = remove_junk($db->escape($_POST['vistas_a_pag']));
        $total_vistas = remove_junk($db->escape($_POST['total_vistas']));

        $sql = "UPDATE visitas_web SET ejercicio='{$ejercicio}', mes='{$mes}', desktop='{$desktop}', movil='{$movil}', tablet='{$tablet}', 
                vistas_a_pag='{$vistas_a_pag}', total_vistas='{$total_vistas}'
                WHERE id_visitas='{$db->escape($id)}'";


        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó la Visita Web de ID: -' . $visitas_web['id_visitas'], 2);
            $session->msg('s', " La Visita Web con ID '" . $visitas_web['id_visitas'] . "' ha sido actualizada con éxito.");
            redirect('visitas_web.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron cambios a la información.');
            redirect('edit_visita_web.php?id=' . (int)$visitas_web['id_visitas_web_especiales'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('visitas_web.php', false);
    }
}
?>

<script type="text/javascript">
    function sumar() {
        const $total = document.getElementById('total');
        let subtotal = 0;
        [...document.getElementsByClassName("monto")].forEach(function(element) {
            if (element.value !== '') {
                subtotal += parseFloat(element.value);
            }
        });
        $total.value = subtotal;
    }
</script>
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="login-page">
    <div class="panel-heading">
        <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Editar Visita Web</span>
        </strong>
    </div>
    <div class="panel-body">
        <form method="post" action="edit_visita_web.php?id=<?php echo (int)$visitas_web['id_visitas']; ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ejercicio" class="control-label">Ejercicio</label>
                        <select class="form-control" name="ejercicio" id="ejercicio">
                            <option value="">Escoge una opción</option>
                            <option <?php if ($visitas_web['ejercicio'] == '2022') echo 'selected="selected"'; ?> value="2022">2022</option>
                            <option <?php if ($visitas_web['ejercicio'] == '2023') echo 'selected="selected"'; ?> value="2023">2023</option>
                            <option <?php if ($visitas_web['ejercicio'] == '2024') echo 'selected="selected"'; ?> value="2024">2024</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="mes" class="control-label">Mes</label>
                        <select class="form-control" name="mes" id="mes">
                            <option value="">Escoge una opción</option>
                            <option <?php if ($visitas_web['mes'] == '1') echo 'selected="selected"'; ?> value="1">Enero</option>
                            <option <?php if ($visitas_web['mes'] == '2') echo 'selected="selected"'; ?> value="2">Febrero</option>
                            <option <?php if ($visitas_web['mes'] == '3') echo 'selected="selected"'; ?> value="3">Marzo</option>
                            <option <?php if ($visitas_web['mes'] == '4') echo 'selected="selected"'; ?> value="4">Abril</option>
                            <option <?php if ($visitas_web['mes'] == '5') echo 'selected="selected"'; ?> value="5">Mayo</option>
                            <option <?php if ($visitas_web['mes'] == '6') echo 'selected="selected"'; ?> value="6">Junio</option>
                            <option <?php if ($visitas_web['mes'] == '7') echo 'selected="selected"'; ?> value="7">Julio</option>
                            <option <?php if ($visitas_web['mes'] == '8') echo 'selected="selected"'; ?> value="8">Agosto</option>
                            <option <?php if ($visitas_web['mes'] == '9') echo 'selected="selected"'; ?> value="9">Septiembre</option>
                            <option <?php if ($visitas_web['mes'] == '10') echo 'selected="selected"'; ?> value="10">Octubre</option>
                            <option <?php if ($visitas_web['mes'] == '11') echo 'selected="selected"'; ?> value="11">Noviembre</option>
                            <option <?php if ($visitas_web['mes'] == '12') echo 'selected="selected"'; ?> value="12">Diciembre</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="desktop">Desktop</label>
                        <input class="form-control monto" type="number" id="desktop" name="desktop" onchange="sumar();" value="<?php echo $visitas_web['desktop'] ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="movil">Móvil</label>
                        <input class="form-control monto" type="number" id="movil" name="movil" onchange="sumar();" value="<?php echo $visitas_web['movil'] ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tablet">Tablet</label>
                        <input class="form-control monto" type="number" id="tablet" name="tablet" onchange="sumar();" value="<?php echo $visitas_web['tablet'] ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="vistas_a_pag">Vistas a Página</label>
                        <input class="form-control" type="number" name="vistas_a_pag" value="<?php echo $visitas_web['vistas_a_pag'] ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="total_vistas">Total Vistas</label>
                        <input class="form-control" type="number" name="total_vistas" id="total" value="<?php echo $visitas_web['total_vistas'] ?>" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group clearfix">
                    <a href="visitas_web.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_visita_web" class="btn btn-primary" value="subir">Guardar</button>
                </div>
        </form>
    </div>
</div>


<?php include_once('layouts/footer.php'); ?>