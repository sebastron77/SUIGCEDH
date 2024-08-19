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

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
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
	
</script>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_queja_publica'])) {

    // $req_fields = array(
    // 'nombreQ', 'paternoQ', 'maternoQ', 'id_cat_genQ', 'edadQ', 'id_cat_escolaridadQ', 'id_cat_grupo_vulnQ', 'id_cat_nacionalidadQ',
    // 'emailQ', 'telefonoQ', 'calleQ', 'coloniaQ', 'descripcion_hechos', 'ent_fed', 'id_cat_mun', 'localidad', 'autoridad_responsable'
    // );
    // validate_fields($req_fields);

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

      /*  if (count($id_queja) == 0) {
            $nuevo_id_queja = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_queja as $nuevo) {
                $nuevo_id_queja = (int) $nuevo['id_queja_date'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['id_queja_date'] + 1);
            }
        }*/

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


        $dbh = new PDO('mysql:host=localhost;dbname=gestorcedh', 'root', '1711alrep');
        // set the PDO error mode to exception
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query2 = "INSERT INTO cat_quejosos (nombre,paterno,materno,id_cat_gen,edad,id_cat_nacionalidad,id_cat_mun,id_cat_escolaridad,id_cat_ocup,
                                leer_escribir,id_cat_grupo_vuln,id_cat_disc,id_cat_comun,telefono,email,calle_quejoso,numero_quejoso,colonia_quejoso) 
                                VALUES ('{$nombreQ}','{$paternoQ}','{$maternoQ}','{$id_cat_genQ}','{$edadQ}','{$id_cat_nacionalidadQ}','{$id_cat_munQ}',
                                        '{$id_cat_escolaridadQ}','{$id_cat_ocupQ}','{$leer_escribirQ}','{$id_cat_grupo_vulnQ}','{$id_cat_discQ}','{$id_cat_comunQ}',
                                        '{$telefonoQ}','{$emailQ}','{$calleQ}','{$numeroQ}','{$coloniaQ}')";

        $dbh->exec($query2);
        $id_quejoso = $dbh->lastInsertId();



        $dbh2 = new PDO('mysql:host=localhost;dbname=gestorcedh', 'root', '1711alrep');

        $query3 = "INSERT INTO cat_agraviados (nombre,paterno,materno,id_cat_gen,edad,id_cat_nacionalidad,id_cat_mun,id_cat_escolaridad,id_cat_ocup,
                                leer_escribir,id_cat_grupo_vuln,id_cat_disc,ppl,id_cat_ppl,id_cat_comun,telefono,email) 
                                VALUES ('{$nombreQ}','{$paternoQ}','{$maternoQ}','{$id_cat_genQ}','{$edadQ}','{$id_cat_nacionalidadQ}','{$id_cat_munQ}',
                                        '{$id_cat_escolaridadQ}','{$id_cat_ocupQ}','{$leer_escribirQ}','{$id_cat_grupo_vulnQ}','{$id_cat_discQ}',0,6,'{$id_cat_comunQ}',
                                        '{$telefonoQ}','{$emailQ}')";

        $dbh->exec($query3);
        $id_agraviado = $dbh->lastInsertId();

        $query = "INSERT INTO quejas_dates (folio_queja, fecha_presentacion, id_cat_med_pres, id_cat_aut, estado_procesal,id_cat_quejoso, id_cat_agraviado, fecha_creacion, 
                                id_area_asignada, id_estatus_queja, archivo, dom_calle, dom_numero, dom_colonia, ent_fed, localidad, id_cat_mun, descripcion_hechos)
                                VALUES ('{$folio}','{$fecha_creacion}',5,'{$autoridad_responsable}',1,{$id_quejoso},{$id_agraviado},'{$fecha_creacion}',3,9,'{$name}',
                                        '{$dom_calle}','{$dom_numero}','{$dom_colonia}','{$ent_fed}','{$localidad}', '{$id_cat_mun}', '{$descripcion_hechos}')";

        $query4 = "INSERT INTO folios (";
        $query4 .= "folio, contador";
        $query4 .= ") VALUES (";
        $query4 .= " '{$folio}','{$no_folio1}'";
        $query4 .= ")";

        if ($db->query($query) && $db->query($query4)) {
            //sucess
            $session->msg('s', " Su queja ha sido agregada con éxito.");
            redirect('add_queja_publica.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar su queja. Vuelva a intentarlo.');
            redirect('add_queja_publica.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_queja_publica.php', false);
    }
}
?>
<?php header('Content-type: text/html; charset=utf-8');?>


	<body style="font-family: 'Questrial', sans-serif; background-color: #F2F3F8;">
		<form method="post" action="add_queja_publica.php" enctype="multipart/form-data">
			<nav class="title-main headerp" style="z-index: 20">
			<li class="title-tex" style="">
				<img src="medios/LOGO-CEDH-H.png" alt="CEDH" width="150px">
			</li>			
			<li class="title-tex" style="">Presenta tu queja</li>
			</nav>
			<br /><br /><br /><br /><br />
			
			<div class="contpub" style="margin-top: 110px;">
				<div class="panel panel-default" style="width:80%;margin: 0 auto;;">
        
        <div class="panel-body" style="">
            <strong>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="25px" height="25px" fill="#7263F0">
                    <title>comment-text-multiple-outline</title>
                    <path d="M12,23A1,1 0 0,1 11,22V19H7A2,2 0 0,1 5,17V7A2,2 0 0,1 7,5H21A2,2 0 0,1 23,7V17A2,2 0 0,1 21,19H16.9L13.2,22.71C13,22.89 12.76,23 12.5,23H12M13,17V20.08L16.08,17H21V7H7V17H13M3,15H1V3A2,2 0 0,1 3,1H19V3H3V15M9,9H19V11H9V9M9,13H17V15H9V13Z" />
                </svg>
                <span style="font-size: 20px; color: #7263F0">HECHOS OCURRIDOS</span>
				<h2 class="divider line glow" ></h2>
            </strong>
            <form method="post" action="add_queja_publica.php" enctype="multipart/form-data">
                <div class="row" style="margin-top: 10px;">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="descripcion_hechos">Descripción de los hechos</label>
                            <textarea class="form-control" name="descripcion_hechos" id="descripcion_hechos" cols="30" rows="5"></textarea>
                        </div>
                    </div>
                </div>
				
                <div class="row">
				<div class="col-md-4">
                        <div class="form-group">
                            <label for="ent_fed">Entidad Federativa</label>
                            <select class="form-control form-select" name="ent_fed" required>
                                <option value="">Escoge una opción</option>
                                <option value="Michoacán">Michoacán</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_cat_mun">Municipio</label>
                            <select class="form-control form-select" name="id_cat_mun">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_municipios as $id_cat_municipio) : ?>
                                    <option value="<?php echo $id_cat_municipio['id_cat_mun']; ?>"><?php echo ucwords($id_cat_municipio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					 <div class="col-md-4">
                        <div class="form-group">
                            <label for="localidad">Localidad</label>
                            <input type="text" class="form-control" name="localidad" placeholder="Localidad" required>
                        </div>
                    </div>
				</div>
				
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dom_calle">Calle</label>
                            <input type="text" class="form-control" name="dom_calle" placeholder="Calle" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dom_numero">Núm.</label>
                            <input type="text" class="form-control" name="dom_numero" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dom_colonia">Colonia</label>
                            <input type="text" class="form-control" name="dom_colonia" placeholder="Colonia" required>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                   
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="autoridad_responsable">Autoridad Responsable</label>
                            <select class="form-control form-select" name="autoridad_responsable">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_autoridades as $autoridades) : ?>
                                    <option value="<?php echo $autoridades['id_cat_aut']; ?>"><?php echo ucwords($autoridades['nombre_autoridad']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="adjunto">Archivo adjunto (si es necesario)</label>
                            <input type="file" accept="application/pdf" class="form-control form-select" name="adjunto" id="adjunto">
                        </div>
                    </div>
                </div>
				<br>
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="margin-top:-0.3%;" width="25px" height="25px" fill="#7263F0">
                        <title>account</title>
                        <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                    </svg>
                    <span style="font-size: 20px; color: #7263F0">DATOS QUEJOSO</span>
					<h2 class="divider line glow"  ></h2>
                </strong><br>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombreQ">Nombre</label>
                            <input type="text" class="form-control" name="nombreQ" placeholder="Nombre(s)" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="paternoQ">Apellido Paterno</label>
                            <input type="text" class="form-control" name="paternoQ" placeholder="Apellido Paterno" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="maternoQ">Apellido Materno</label>
                            <input type="text" class="form-control" name="maternoQ" placeholder="Apellido Materno" required>
                        </div>
                    </div>
				</div>
				
                 <div class="row">   
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_genQ">Género</label>
                            <select class="form-control form-select" name="id_cat_genQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($generos as $genero) : ?>
                                    <option value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="edadQ">Edad</label>
                            <input type="number" class="form-control" min="1" max="130" maxlength="4" name="edadQ" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_cat_escolaridadQ">Escolaridad</label>
                            <select class="form-control form-select" name="id_cat_escolaridadQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($escolaridades as $escolaridad) : ?>
                                    <option value="<?php echo $escolaridad['id_cat_escolaridad']; ?>"><?php echo ucwords($escolaridad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="id_cat_ocupQ">Ocupación</label>
                            <select class="form-control form-select" name="id_cat_ocupQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($ocupaciones as $ocupacion) : ?>
                                    <option value="<?php echo $ocupacion['id_cat_ocup']; ?>"><?php echo ucwords($ocupacion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>				
				
                <div class="row">                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_cat_grupo_vulnQ">Grupo Vulnerable</label>
                            <select class="form-control form-select" name="id_cat_grupo_vulnQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($grupos_vuln as $grupo_vuln) : ?>
                                    <option value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="id_cat_comunQ">Comunidad Indígena</label>
                            <select class="form-control form-select" name="id_cat_comunQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($comunidades as $comunidad) : ?>
                                    <option value="<?php echo $comunidad['id_cat_comun']; ?>"><?php echo ucwords($comunidad['descripcion']); ?></option>
                                <?php endforeach; ?>
							</select>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="leer_escribirQ">¿Sabe leer y escribir?</label>
                            <select class="form-control form-select" name="leer_escribirQ">
                                <option value="">Escoge una opción</option>
                                <option value="Leer">Leer</option>
                                <option value="Escribir">Escribir</option>
                                <option value="Ambos">Ambos</option>
                            </select>
                        </div>
                    </div>
				</div>
				<div class="row">
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="id_cat_discQ">¿Tiene alguna discapacidad?</label>
                            <select class="form-control form-select" name="id_cat_discQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($discapacidades as $discapacidad) : ?>
                                    <option value="<?php echo $discapacidad['id_cat_disc']; ?>"><?php echo ucwords($discapacidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="emailQ">Email</label>
                            <input type="text" class="form-control" name="emailQ">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_cat_nacionalidadQ">Nacionalidad</label>
                            <select class="form-control form-select" name="id_cat_nacionalidadQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($nacionalidades as $nacionalidad) : ?>
                                    <option value="<?php echo $nacionalidad['id_cat_nacionalidad']; ?>"><?php echo ucwords($nacionalidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>                                       
                </div>
				
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="calleQ"> Calle</label>
                            <input type="text" class="form-control" name="calleQ">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="numeroQ">Núm.</label>
                            <input type="text" class="form-control" name="numeroQ">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="coloniaQ">Colonia</label>
                            <input type="text" class="form-control" name="coloniaQ">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="codigo_postalQ">Código Postal</label>
                            <input type="text" class="form-control" name="codigo_postalQ">
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="telefonoQ">Teléfono</label>
                            <input type="text" class="form-control" maxlength="10" name="telefonoQ">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_cat_munQ">Municipio</label>
                            <select class="form-control form-select" name="id_cat_munQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($municipios as $municipio) : ?>
                                    <option value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                </div>
				<br>
                <div class="form-group clearfix">
                    
                    <button style="background: #300285; border-color:#300285;" type="submit" name="add_queja_publica" class="btn btn-primary">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sd-card" viewBox="0 0 16 16">
						  <path d="M6.25 3.5a.75.75 0 0 0-1.5 0v2a.75.75 0 0 0 1.5 0v-2zm2 0a.75.75 0 0 0-1.5 0v2a.75.75 0 0 0 1.5 0v-2zm2 0a.75.75 0 0 0-1.5 0v2a.75.75 0 0 0 1.5 0v-2zm2 0a.75.75 0 0 0-1.5 0v2a.75.75 0 0 0 1.5 0v-2z"/>
						  <path fill-rule="evenodd" d="M5.914 0H12.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5V3.914c0-.398.158-.78.44-1.06L4.853.439A1.5 1.5 0 0 1 5.914 0zM13 1.5a.5.5 0 0 0-.5-.5H5.914a.5.5 0 0 0-.353.146L3.146 3.561A.5.5 0 0 0 3 3.914V14.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13z"/>
						</svg>
						Guardar
						</button>
                </div>
            </form>
        </div>
    </div>
			</div>
		</form>
	

		<div class="form-footer" >2023 ©&nbsp; Coordinación de Sietmas Informaticos; Comisión Estatal de los Derechos Humanos de Michoacán</div>

	
	</body>

</html>