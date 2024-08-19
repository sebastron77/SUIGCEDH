<?php
$page_title = 'Agregar Visita';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) {
    page_require_level(2);
}

if ($nivel_user == 13) {
    page_require_level_exacto(13);
}
if ($nivel_user > 2 && $nivel_user < 13) :
    redirect('home.php');
endif;
if ($nivel_user > 13 && $nivel_user < 53) :
    redirect('home.php');
endif;
?>
<?php
if (isset($_POST['add_visita_web'])) {

    $ejercicio = remove_junk($db->escape($_POST['ejercicio']));
    $mes = remove_junk($db->escape($_POST['mes']));
    $desktop = remove_junk($db->escape($_POST['desktop']));
    $movil = remove_junk($db->escape($_POST['movil']));
    $tablet = remove_junk($db->escape($_POST['tablet']));
    $vistas_a_pag = remove_junk($db->escape($_POST['vistas_a_pag']));
    $total_vistas = remove_junk($db->escape($_POST['total_vistas']));
    date_default_timezone_set('America/Mexico_City');
    $creacion = date('Y-m-d');

    $query  = "INSERT INTO visitas_web (";
    $query .= "ejercicio, mes, desktop, movil, tablet, vistas_a_pag, total_vistas, usuario_creador, fecha_creacion";
    $query .= ") VALUES (";
    $query .= " '{$ejercicio}', '{$mes}', '{$desktop}', '{$movil}', '{$tablet}', '{$vistas_a_pag}', '{$total_vistas}', '{$id_user}', 
                    '{$creacion}'";
    $query .= ")";
    if ($db->query($query)) {
        //sucess
        $session->msg('s', "¡Registro creado con éxito! ");
		insertAccion($user['id_user'], '"' . $user['username'] . '" dio de alta el Registro de Visitas WEB correspondiente al '.$mes.'/'.$ejercicio.' .', 1);
        redirect('add_visita_web.php', false);
    } else {
        //failed
        $session->msg('d', 'Desafortunadamente no se pudo crear el registro.');
        redirect('add_visita_web.php', false);
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
<?php include_once('layouts/header.php'); ?>
<div class="login-page" style="height: 480px;">
    <div class="text-center">
        <h2 style="margin-top: 20px; margin-bottom: 30px; color: #3a3d44">Agregar Visitas</h2>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="add_visita_web.php" class="clearfix">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ejercicio" class="control-label">Ejercicio</label>
                    <select class="form-control" name="ejercicio" id="ejercicio">
                        <option value="">Escoge una opción</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="mes" class="control-label">Mes</label>
                    <select class="form-control" name="mes" id="mes">
                        <option value="">Escoge una opción</option>
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="desktop">Desktop</label>
                    <input class="form-control monto" type="number" id="desktop" name="desktop" onchange="sumar();">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="movil">Móvil</label>
                    <input class="form-control monto" type="number" id="movil" name="movil" onchange="sumar();">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="tablet">Tablet</label>
                    <input class="form-control monto" type="number" id="tablet" name="tablet" onchange="sumar();">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="vistas_a_pag">Vistas a Página</label>
                    <input class="form-control" type="number" name="vistas_a_pag">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="total_vistas">Total Vistas</label>
                    <input class="form-control" type="number" name="total_vistas" id="total" readonly>
                </div>
            </div>
        </div>

        <div class="form-group clearfix">
            <a href="visitas_web.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="add_visita_web" class="btn btn-info">Guardar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>