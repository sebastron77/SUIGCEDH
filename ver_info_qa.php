<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<link rel="stylesheet" href="libs/css/main.css" />

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
<script src="html2pdf.bundle.min.js"></script>
<script src="script.js"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

<link href="https://harvesthq.github.io/chosen/chosen.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Queja';
require_once('includes/load.php');
?>
<?php
$e_detalle_q = find_by_id_cat_quejoso((int) $_GET['id']);
$e_detalle_a = find_by_id_cat_agraviado((int) $_GET['id']);
$tipo = $_GET['t'];
$user = current_user();
$nivel = $user['user_level'];
$cat_est_procesal = find_all('cat_est_procesal');
$cat_municipios = find_all_cat_municipios();

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 5) {
    page_require_level_exacto(5);
}
if ($nivel == 7) {
    page_require_level_exacto(7);
}
if ($nivel == 19) {
    page_require_level_exacto(19);
}
if ($nivel == 21) {
    page_require_level_exacto(21);
}
if ($nivel == 26) {
    page_require_level_exacto(26);
}
if ($nivel == 50) {
    page_require_level_exacto(50);
}
if ($nivel == 53) {
    page_require_level_exacto(53);
}

if ($nivel > 2 && $nivel < 5) :
    redirect('home.php');
endif;
if ($nivel > 5 && $nivel < 7) :
    redirect('home.php');
endif;
if ($nivel > 7 && $nivel < 19) :
    redirect('home.php');
endif;
if ($nivel > 19 && $nivel < 21) :
    redirect('home.php');
endif;
if ($nivel > 21 && $nivel < 26) :
    redirect('home.php');
endif;
if ($nivel > 26 && $nivel < 50) :
    redirect('home.php');
endif;
if ($nivel > 50 && $nivel < 53) :
    redirect('home.php');
endif;

if ($nivel == 7 || $nivel == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$a_capacitacion['folio'], 5);   
}

?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<?php header('Content-type: text/html; charset=utf-8');
// include_once('layouts/header.php'); 
?>
<div class="row">
    <div id="Generales" class="tabcontent">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <strong>
                        <span class="glyphicon glyphicon-th"></span>
                        <?php if ($tipo == 'q') : ?>
                            <span>Información general del quejoso</span>
                        <?php endif; ?>
                        <?php if ($tipo == 'a') : ?>
                            <span>Información general del agraviado</span>
                        <?php endif; ?>
                    </strong>
                </div>

                <div class="panel-body">
                    <table style="color: #3a3d44">
                        <?php if ($tipo == 'q') : ?>
                            <tr>
                                <td style="width: 3.5%">
                                    <span style="font-weight: bold;">Nombre quejoso: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_q['nombre'] . " " . $e_detalle_q['paterno'] . " " . $e_detalle_q['materno']))) ?><br><br>
                                </td>
                                <td style="width: 2%">
                                    <span style="font-weight: bold;">Genero: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_q['genero']))) ?><br><br>
                                </td>
                                <td style="width: 2%">
                                    <span style="font-weight: bold;">Nacionalidad: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_q['nacionalidad']))) ?><br><br>
                                </td>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Edad: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_q['edad']))) ?><br><br>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 3.5%">
                                    <span style="font-weight: bold;">Municipio: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_q['municipio']))) ?> <br><br>
                                </td>
                                <td style="width: 2%">
                                    <span style="font-weight: bold;">Escolaridad: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_q['escolaridad']))) ?> <br><br>
                                </td>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Ocupación: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_q['ocupacion']))) ?> <br><br>
                                </td>
                                <td style="width: 2%">
                                    <span style="font-weight: bold;">¿Sabe leer y/o escribir?: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_q['leer_escribir']))) ?> <br><br>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Grupo vulnerable: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_q['grupo_vuln']))) ?><br><br>
                                </td>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Discapacidad: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_q['discapacidad']))) ?><br><br>
                                </td>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Comunidad: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_q['comunidad']))) ?><br><br>
                                </td>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Telefono: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_q['telefono']))) ?><br><br>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Email: </span>
                                    <?php echo remove_junk((($e_detalle_q['email']))) ?>
                                </td>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Calle: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_q['calle_quejoso']))) ?>
                                </td>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Número: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_q['numero_quejoso']))) ?>
                                </td>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Colonia: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_q['colonia_quejoso']))) ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($tipo == 'a') : ?>
                            <tr>
                                <td style="width: 3.5%">
                                    <span style="font-weight: bold;">Nombre quejoso: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_a['nombre'] . " " . $e_detalle_a['paterno'] . " " . $e_detalle_a['materno']))) ?><br><br>
                                </td>
                                <td style="width: 2%">
                                    <span style="font-weight: bold;">Genero: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_a['genero']))) ?><br><br>
                                </td>
                                <td style="width: 2%">
                                    <span style="font-weight: bold;">Nacionalidad: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_a['nacionalidad']))) ?><br><br>
                                </td>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Edad: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_a['edad']))) ?><br><br>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 3.5%">
                                    <span style="font-weight: bold;">Municipio: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_a['municipio']))) ?> <br><br>
                                </td>
                                <td style="width: 2%">
                                    <span style="font-weight: bold;">Escolaridad: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_a['escolaridad']))) ?> <br><br>
                                </td>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Ocupación: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_a['ocupacion']))) ?> <br><br>
                                </td>
                                <td style="width: 2%">
                                    <span style="font-weight: bold;">¿Sabe leer y/o escribir?: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_a['leer_escribir']))) ?> <br><br>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Grupo vulnerable: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_a['grupo_vuln']))) ?><br><br>
                                </td>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Discapacidad: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_a['discapacidad']))) ?><br><br>
                                </td>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Comunidad: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_a['comunidad']))) ?><br><br>
                                </td>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Telefono: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_a['telefono']))) ?><br><br>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Email: </span>
                                    <?php echo remove_junk((($e_detalle_a['email']))) ?>
                                </td>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">PPL: </span>
                                    <?php
                                    if ($e_detalle_a['ppl'] == 0)
                                        echo 'No';
                                    else {
                                        echo 'Sí';
                                    }
                                    ?>
                                </td>
                                <td style="width: 1%">
                                    <span style="font-weight: bold;">Si es PPL, ¿Quién presenta la queja?: </span>
                                    <?php echo remove_junk(ucwords(($e_detalle_a['catppl']))) ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </table>


                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>