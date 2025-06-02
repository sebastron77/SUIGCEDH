<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Presenta tu Queja';
require_once('includes/load.php');
$user = current_user();
$id_queja = last_id_quejaR();
$id_folio = last_id_folios();

$cat_autoridades = find_all_aut_res();
$cat_municipios = find_all_cat_municipios();
$generos = find_all('cat_genero');
$escolaridades = find_all('cat_escolaridad');
$ocupaciones = find_all('cat_ocupaciones');
$grupos_vuln = find_all('cat_grupos_vuln');
$nacionalidades = find_all('cat_nacionalidades');
$municipios = find_all('cat_municipios');
$discapacidades = find_all('cat_discapacidades');
$comunidades = find_all('cat_comunidades');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Presenta tu queja</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="libs/css/main.css">
    <link rel="stylesheet" href="libs/css/publico.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style type="text/css">
    </style>
</head>

<script type="text/javascript">
    history.forward();

    function nobackbutton() {

        window.location.hash = "no-back-button";
        window.location.hash = "Again-No-back-button" //chrome
        window.onhashchange = function() {
            window.location.hash = "no-back-button";
        }
    }
</script>
<?php header('Content-type: text/html; charset=utf-8'); ?>

<body style="font-family: 'Questrial', sans-serif; background-color: #F2F3F8;" onload="nobackbutton();">
    <form method="post" action="exce_queja_publica.php" enctype="multipart/form-data">
        <nav class="title-main headerp">
            <div class="nav-content">
                <img src="medios/Logo_blanco.png" alt="CEDH" class="title-logo" />
                <span class="title-tex">PRESENTA TU QUEJA</span>
            </div>
        </nav>
        <br /><br /><br /><br /><br />

        <div class="contpub" style="margin-top: 110px;">
            <div class="panel panel-default" style="width:80%;margin: 0 auto;;">
                <div class="panel-body">
                    <div class="cont desc">
                        <div style="font-weight: bold; text-align: center;">
                            <?php header('Content-type: text/html; charset=utf-8');
                            if (isset($_POST['add_queja_publica'])) {
                                if (empty($errors)) {
                                    //DATOS QUEJOSO Y AGRAVIADO
                                    $nombreQ = remove_junk($db->escape($_POST['nombreQ']));
                                    $paternoQ = remove_junk($db->escape($_POST['paternoQ']));
                                    $maternoQ = remove_junk($db->escape($_POST['maternoQ']));
                                    $id_cat_genQ = remove_junk($db->escape($_POST['id_cat_genQ']));
                                    $edadQ = remove_junk($db->escape($_POST['edadQ']));
                                    $id_cat_nacionalidadQ = remove_junk($db->escape($_POST['id_cat_nacionalidadQ']));
                                    $id_cat_munQ = remove_junk($db->escape($_POST['id_cat_munQ']));
                                    $id_cat_escolaridadQ = remove_junk($db->escape($_POST['id_cat_escolaridadQ']));
                                    $id_cat_ocupQ = remove_junk($db->escape($_POST['id_cat_ocupQ']));
                                    $id_cat_grupo_vulnQ = remove_junk($db->escape($_POST['id_cat_grupo_vulnQ']));
                                    $telefonoQ = remove_junk($db->escape($_POST['telefonoQ']));
                                    $emailQ = remove_junk($db->escape($_POST['emailQ']));
                                    $calleQ = remove_junk($db->escape($_POST['calleQ']));
                                    $numeroQ = remove_junk($db->escape($_POST['numeroQ']));
                                    $coloniaQ = remove_junk($db->escape($_POST['coloniaQ']));
                                    $codigo_postalQ = remove_junk($db->escape($_POST['codigo_postalQ']));
                                    $leer_escribirQ = remove_junk($db->escape($_POST['leer_escribirQ']));
                                    $id_cat_discQ = remove_junk($db->escape($_POST['id_cat_discQ']));
                                    $id_cat_comunQ = remove_junk($db->escape($_POST['id_cat_comunQ']));

                                    //DATOS QUEJA
                                    $dom_calle = remove_junk($db->escape($_POST['dom_calle']));
                                    $dom_numero = remove_junk($db->escape($_POST['dom_numero']));
                                    $dom_colonia = remove_junk($db->escape($_POST['dom_colonia']));
                                    $descripcion_hechos = remove_junk($db->escape($_POST['descripcion_hechos']));
                                    $ent_fed = remove_junk($db->escape($_POST['ent_fed']));
                                    $localidad = remove_junk($db->escape($_POST['localidad']));
                                    $id_cat_mun = remove_junk($db->escape($_POST['id_cat_mun']));
                                    $autoridad_responsable = remove_junk($db->escape($_POST['autoridad_responsable']));
                                    date_default_timezone_set('America/Mexico_City');
                                    $fecha_creacion = date('Y-m-d H:i:s');

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
                                    $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-Q';

                                    $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-Q';
                                    $carpeta = 'uploads/quejas/' . $folio_carpeta;

                                    if (!is_dir($carpeta)) {
                                        mkdir($carpeta, 0777, true);
                                    }

                                    $name = $_FILES['adjunto']['name'];
                                    $size = $_FILES['adjunto']['size'];
                                    $type = $_FILES['adjunto']['type'];
                                    $temp = $_FILES['adjunto']['tmp_name'];

                                    $move = move_uploaded_file($temp, $carpeta . "/" . $name);

                                    $dbh = new PDO('mysql:host=localhost;dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');
                                    // set the PDO error mode to exception
                                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                    $query2 = "INSERT INTO cat_quejosos (nombre,paterno,materno,id_cat_gen,edad,id_cat_nacionalidad,id_cat_mun,
                                                            id_cat_escolaridad,id_cat_ocup,leer_escribir,id_cat_grupo_vuln,id_cat_disc,id_cat_comun,telefono,
                                                            email,calle_quejoso,numero_quejoso,colonia_quejoso) 
                                                VALUES ('{$nombreQ}','{$paternoQ}','{$maternoQ}','{$id_cat_genQ}','{$edadQ}','{$id_cat_nacionalidadQ}',
                                                        '{$id_cat_munQ}','{$id_cat_escolaridadQ}','{$id_cat_ocupQ}','{$leer_escribirQ}','{$id_cat_grupo_vulnQ}',
                                                        '{$id_cat_discQ}','{$id_cat_comunQ}','{$telefonoQ}','{$emailQ}','{$calleQ}','{$numeroQ}','{$coloniaQ}')";

                                    $dbh->exec($query2);
                                    $id_quejoso = $dbh->lastInsertId();

                                    $dbh2 = new PDO('mysql:host=localhost;dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');

                                    $query3 = "INSERT INTO cat_agraviados (nombre,paterno,materno,id_cat_gen,edad,id_cat_nacionalidad,id_cat_mun,
                                                            id_cat_escolaridad,id_cat_ocup, leer_escribir,id_cat_grupo_vuln,id_cat_disc,ppl,id_cat_ppl,
                                                            id_cat_comun,telefono,email) 
                                                VALUES ('{$nombreQ}','{$paternoQ}','{$maternoQ}','{$id_cat_genQ}','{$edadQ}','{$id_cat_nacionalidadQ}',
                                                        '{$id_cat_munQ}','{$id_cat_escolaridadQ}','{$id_cat_ocupQ}','{$leer_escribirQ}','{$id_cat_grupo_vulnQ}',
                                                        '{$id_cat_discQ}',0,6,'{$id_cat_comunQ}','{$telefonoQ}','{$emailQ}')";

                                    $dbh->exec($query3);
                                    $id_agraviado = $dbh->lastInsertId();

                                    $query = "INSERT INTO quejas_dates (folio_queja, fecha_presentacion, id_cat_med_pres, id_cat_aut, estado_procesal, 
                                                            id_cat_quejoso, id_cat_agraviado, fecha_creacion,id_area_asignada,id_user_asignado,
                                                            id_estatus_queja, archivo, dom_calle, dom_numero, dom_colonia, ent_fed, localidad, id_cat_mun, 
                                                            descripcion_hechos,id_tipo_resolucion,notificacion)
                                                VALUES ('{$folio}','{$fecha_creacion}',5,'{$autoridad_responsable}',1,{$id_quejoso},{$id_agraviado},
                                                        '{$fecha_creacion}',4,3,0,'{$name}','{$dom_calle}','{$dom_numero}','{$dom_colonia}','{$ent_fed}',
                                                        '{$localidad}', '{$id_cat_mun}', '{$descripcion_hechos}',1,1)";

                                    $query4 = "INSERT INTO folios (";
                                    $query4 .= "folio, contador";
                                    $query4 .= ") VALUES (";
                                    $query4 .= " '{$folio}','{$no_folio1}'";
                                    $query4 .= ")";

                                    if ($db->query($query) && $db->query($query4)) {
                                        //sucess                            
                            ?>
                                        <p style="font-size: 15px;">
                                            El número de expediente asignado a tu queja es <span class="texto-danger"><?php echo $folio; ?></span>
                                            <br />Te pedimos asistas a las oficinas más cercanas a tu municipio a ratificar tu queja, con el folio que se te asignó, 
                                            <br />tal como se establece en artículo 88 de la Ley de la Comisión Estatal de los Derechos Humanos de Michoacán de Ocampo.
                                        </p>
                                    <?php
                                    } else {
                                        //failed            
                                    ?>
                                        <p style="font-size: 15px;">
                                            Lo sentimos, no se pudo agregar su queja. Por favor, vuelva a intentarlo.
                                        </p>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <p style="font-size: 15px;">
                                        Lo sentimos, hubo error en la recepción de la información, por lo que no se pudo agregar su queja. Por favor, vuelva a intentarlo.
                                    </p>
                            <?php
                                }
                            }
                            ?>
                            <a href="add_queja_publica.php" class="btn btn-md" style="background: green; color: white;" data-toggle="tooltip" title="ACEPTAR">ACEPTAR</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
    </form>
    <div class="form-footer">2023 ©&nbsp; Coordinación de Sistemas Informáticos; Comisión Estatal de los Derechos Humanos de Michoacán</div>
</body>

</html>