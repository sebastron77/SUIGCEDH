<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar quejoso';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 5) {
    page_require_level_exacto(5);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 19) {
    page_require_level_exacto(19);
}
if ($nivel_user == 26) {
    page_require_level_exacto(26);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}
if ($nivel_user > 2 && $nivel_user < 5):
    redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 7):
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 19):
    redirect('home.php');
endif;
if ($nivel_user > 19 && $nivel_user < 26):
    redirect('home.php');
endif;
if ($nivel_user > 26 && $nivel_user < 50):
    redirect('home.php');
endif;

$generos = find_all('cat_genero');
$nacionalidades = find_all('cat_nacionalidades');
$municipios = find_all('cat_municipios');
$escolaridades = find_all('cat_escolaridad');
$ocupaciones = find_all('cat_ocupaciones');
$grupos_vuln = find_all('cat_grupos_vuln');
$discapacidades = find_all('cat_discapacidades');
$ppls = find_all('cat_ppl');
$comunidades = find_all('cat_comunidades');
?>

<?php /*header('Content-type: text/html; charset=utf-8');*/
if (isset($_POST['add_quejoso'])) {

    // $req_fields = array('nombre', 'paterno', 'materno', 'id_cat_gen', 'edad', 'id_cat_nacionalidad', 'id_cat_mun', 'id_cat_escolaridad', 'id_cat_ocup', 'id_cat_grupo_vuln', 'id_cat_comun', 'telefono');
    // validate_fields($req_fields);

    if (empty($errors)) {
        $pplTrue = isset($_POST['c1']) ? 1 : 0;
        $nombre = remove_junk($db->escape($_POST['nombre']));
        $paterno = remove_junk($db->escape($_POST['paterno']));
        $materno = remove_junk($db->escape($_POST['materno']));
        $id_cat_gen = remove_junk($db->escape($_POST['id_cat_gen']));
        $edad = remove_junk($db->escape($_POST['edad']));
        $id_cat_nacionalidad = remove_junk($db->escape($_POST['id_cat_nacionalidad']));
        $id_cat_mun = remove_junk($db->escape($_POST['id_cat_mun']));
        $id_cat_escolaridad = remove_junk($db->escape($_POST['id_cat_escolaridad']));
        $id_cat_ocup = remove_junk($db->escape($_POST['id_cat_ocup']));
        $leer_escribir = remove_junk($db->escape($_POST['leer_escribir']));
        $id_cat_grupo_vuln = remove_junk($db->escape($_POST['id_cat_grupo_vuln']));
        $id_cat_disc = remove_junk($db->escape($_POST['id_cat_disc']));
        $ppl = remove_junk($db->escape($_POST['ppl']));
        $id_cat_ppl = remove_junk($db->escape($_POST['id_cat_ppl']));
        $id_cat_comun = remove_junk($db->escape($_POST['id_cat_comun']));
        $telefono = remove_junk($db->escape($_POST['telefono']));
        $email = remove_junk($db->escape($_POST['email']));

        $nombreQ = remove_junk($db->escape($_POST['nombreQ']));
        $paternoQ = remove_junk($db->escape($_POST['paternoQ']));
        $maternoQ = remove_junk($db->escape($_POST['maternoQ']));
        $id_cat_genQ = remove_junk($db->escape($_POST['id_cat_genQ']));
        $edadQ = remove_junk($db->escape($_POST['edadQ']));
        $id_cat_nacionalidadQ = remove_junk($db->escape($_POST['id_cat_nacionalidadQ']));
        $id_cat_munQ = remove_junk($db->escape($_POST['id_cat_munQ']));
        $id_cat_escolaridadQ = remove_junk($db->escape($_POST['id_cat_escolaridadQ']));
        $id_cat_ocupQ = remove_junk($db->escape($_POST['id_cat_ocupQ']));
        $leer_escribirQ = remove_junk($db->escape($_POST['leer_escribirQ']));
        $id_cat_grupo_vulnQ = remove_junk($db->escape($_POST['id_cat_grupo_vulnQ']));
        $id_cat_discQ = remove_junk($db->escape($_POST['id_cat_discQ']));
        $id_cat_comunQ = remove_junk($db->escape($_POST['id_cat_comunQ']));
        $telefonoQ = remove_junk($db->escape($_POST['telefonoQ']));
        $emailQ = remove_junk($db->escape($_POST['emailQ']));
        $calleQ = remove_junk($db->escape($_POST['calleQ']));
        $numeroQ = remove_junk($db->escape($_POST['numeroQ']));
        $coloniaQ = remove_junk($db->escape($_POST['coloniaQ']));


        if (!$pplTrue) {
            $query2 = "INSERT INTO cat_quejosos (";
            $query2 .= "nombre,paterno,materno,id_cat_gen,edad,id_cat_nacionalidad,id_cat_mun,id_cat_escolaridad,id_cat_ocup,
                        leer_escribir,id_cat_grupo_vuln,id_cat_disc,id_cat_comun,telefono,email,calle_quejoso,numero_quejoso,colonia_quejoso";
            $query2 .= ") VALUES (";
            $query2 .= " '{$nombreQ}','{$paternoQ}','{$maternoQ}','{$id_cat_genQ}','{$edadQ}','{$id_cat_nacionalidadQ}',
                    '{$id_cat_munQ}','{$id_cat_escolaridadQ}','{$id_cat_ocupQ}','{$leer_escribirQ}','{$id_cat_grupo_vulnQ}',
                    '{$id_cat_discQ}',{$id_cat_comunQ},'{$telefonoQ}','{$emailQ}','{$calleQ}','{$numeroQ}','{$coloniaQ}'";
            $query2 .= ")";

            $query = "INSERT INTO cat_agraviados (";
            $query .= "nombre,paterno,materno,id_cat_gen,edad,id_cat_nacionalidad,id_cat_mun,id_cat_escolaridad,
                    id_cat_ocup,leer_escribir,id_cat_grupo_vuln,id_cat_disc,ppl,id_cat_ppl,id_cat_comun,telefono,email";
            $query .= ") VALUES (";
            $query .= " '{$nombreQ}','{$paternoQ}','{$maternoQ}','{$id_cat_genQ}','{$edadQ}','{$id_cat_nacionalidadQ}',
                    '{$id_cat_munQ}','{$id_cat_escolaridadQ}','{$id_cat_ocupQ}','{$leer_escribirQ}','{$id_cat_grupo_vulnQ}',
                    '{$id_cat_discQ}',0,6,{$id_cat_comunQ},'{$telefonoQ}','{$emailQ}'";
            $query .= ")";

            if (($db->query($query2)) && ($db->query($query))) {
                //sucess
                $session->msg('s', " El quejoso/agraviado ha sido agregado con éxito.");
                redirect('quejosos.php', false);
            } else {
                //failed
                $session->msg('d', ' No se pudo agregar el quejoso/agraviado.');
                redirect('add_quejoso.php', false);
            }

        } else {
            $query4 = "INSERT INTO cat_quejosos (";
            $query4 .= "nombre,paterno,materno,id_cat_gen,edad,id_cat_nacionalidad,id_cat_mun,id_cat_escolaridad,id_cat_ocup,
                        leer_escribir,id_cat_grupo_vuln,id_cat_disc,id_cat_comun,telefono,email,calle_quejoso,numero_quejoso,colonia_quejoso";
            $query4 .= ") VALUES (";
            $query4 .= " '{$nombreQ}','{$paternoQ}','{$maternoQ}','{$id_cat_genQ}','{$edadQ}','{$id_cat_nacionalidadQ}',
                    '{$id_cat_munQ}','{$id_cat_escolaridadQ}','{$id_cat_ocupQ}','{$leer_escribirQ}','{$id_cat_grupo_vulnQ}',
                    '{$id_cat_discQ}',{$id_cat_comunQ},'{$telefonoQ}','{$emailQ}','{$calleQ}','{$numeroQ}','{$coloniaQ}'";
            $query4 .= ")";

            $query3 = "INSERT INTO cat_agraviados (";
            $query3 .= "nombre,paterno,materno,id_cat_gen,edad,id_cat_nacionalidad,id_cat_mun,id_cat_escolaridad,
                    id_cat_ocup,leer_escribir,id_cat_grupo_vuln,id_cat_disc,ppl,id_cat_ppl,id_cat_comun,telefono,email";
            $query3 .= ") VALUES (";
            $query3 .= " '{$nombre}','{$paterno}','{$materno}','{$id_cat_gen}','{$edad}','{$id_cat_nacionalidad}',
                    '{$id_cat_mun}','{$id_cat_escolaridad}','{$id_cat_ocup}','{$leer_escribir}','{$id_cat_grupo_vuln}',
                    '{$id_cat_disc}',{$ppl},{$id_cat_ppl},{$id_cat_comun},'{$telefono}','{$email}'";
            $query3 .= ")";

            if (($db->query($query4)) && ($db->query($query3))) {
                //sucess
                $session->msg('s', " El ciudadano agraviado ha sido agregado con éxito.");
                redirect('quejosos.php', false);
            } else {
                //failed
                $session->msg('d', ' No se pudo agregar el ciudadano agraviada.');
                redirect('add_quejoso.php', false);
            }
        }    
    } else {
        $session->msg("d", $errors);
        redirect('add_quejoso.php', false);
    }
}
?>


<script type="text/javascript">
    function showMe(it, box) {
        var vis = (box.checked) ? "block" : "none";
        document.getElementById(it).style.display = vis;
    }
</script>
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar promovente</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_quejoso.php">
                <h1>Agregar datos promovente</h1>
                <div class="row" style="margin-top: 2%">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="nombreQ">Nombre</label>
                            <input type="text" class="form-control" name="nombreQ" placeholder="Nombre(s)" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="paternoQ">Apellido Paterno</label>
                            <input type="text" class="form-control" name="paternoQ" placeholder="Apellido Paterno"
                                required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="maternoQ">Apellido Materno</label>
                            <input type="text" class="form-control" name="maternoQ" placeholder="Apellido Materno"
                                required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="id_cat_genQ">Género</label>
                            <select class="form-control" name="id_cat_genQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($generos as $genero): ?>
                                    <option value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="edadQ">Edad</label>
                            <input type="number" class="form-control" min="1" max="130" maxlength="4" name="edadQ"
                                required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="telefonoQ">Teléfono</label>
                            <input type="text" class="form-control" maxlength="10" name="telefonoQ" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="emailQ">Email</label>
                            <input type="text" class="form-control" name="emailQ" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_nacionalidadQ">Nacionalidad</label>
                            <select class="form-control" name="id_cat_nacionalidadQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($nacionalidades as $nacionalidad): ?>
                                    <option value="<?php echo $nacionalidad['id_cat_nacionalidad']; ?>"><?php echo ucwords($nacionalidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_munQ">Municipio</label>
                            <select class="form-control" name="id_cat_munQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($municipios as $municipio): ?>
                                    <option value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_escolaridadQ">Escolaridad</label>
                            <select class="form-control" name="id_cat_escolaridadQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($escolaridades as $escolaridad): ?>
                                    <option value="<?php echo $escolaridad['id_cat_escolaridad']; ?>"><?php echo ucwords($escolaridad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_ocupQ">Ocupación</label>
                            <select class="form-control" name="id_cat_ocupQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($ocupaciones as $ocupacion): ?>
                                    <option value="<?php echo $ocupacion['id_cat_ocup']; ?>"><?php echo ucwords($ocupacion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="leer_escribirQ">¿Sabe leer y escribir?</label>
                            <select class="form-control" name="leer_escribirQ">
                                <option value="">Escoge una opción</option>
                                <option value="Leer">Leer</option>
                                <option value="Escribir">Escribir</option>
                                <option value="Ambos">Ambos</option>
								<option value="Sin Dato">Sin Dato</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_discQ">¿Tiene alguna discapacidad?</label>
                            <select class="form-control" name="id_cat_discQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($discapacidades as $discapacidad): ?>
                                    <option value="<?php echo $discapacidad['id_cat_disc']; ?>"><?php echo ucwords($discapacidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_cat_grupo_vulnQ">Grupo Vulnerable</label>
                            <select class="form-control" name="id_cat_grupo_vulnQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($grupos_vuln as $grupo_vuln): ?>
                                    <option value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_comunQ">Comunidad</label>
                            <select class="form-control" name="id_cat_comunQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($comunidades as $comunidad): ?>
                                    <option value="<?php echo $comunidad['id_cat_comun']; ?>"><?php echo ucwords($comunidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="calleQ"> Calle</label>
                            <input type="text" class="form-control" name="calleQ" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="numeroQ">Núm.</label>
                            <input type="text" class="form-control" name="numeroQ" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="coloniaQ">Colonia</label>
                            <input type="text" class="form-control" name="coloniaQ" required>
                        </div>
                    </div>
                </div>

                <p class="text-left" style="font-weight: bold; font-size: 17px; margin-top:40px;">¿Quien presenta la queja
                    es el "agraviado/a"?</p>
                <label class="switch" style="float:left;">
                    <div class="row">
                        <input type="checkbox" id="c1" name="c1" onclick="showMe('div1', this)" checked>
                        <span class="slider round"></span>
                        <div>
                            <p style="margin-left: 150%; margin-top: -3%; font-size: 14px;">Sí/No</p>
                        </div>
                    </div>
                </label><br><br>

                <div id="div1" style="display:'';">
                    <h1>Agregar datos agraviado</h1>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" name="nombre" placeholder="Nombre(s)">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="paterno">Apellido Paterno</label>
                                <input type="text" class="form-control" name="paterno" placeholder="Apellido Paterno">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="materno">Apellido Materno</label>
                                <input type="text" class="form-control" name="materno" placeholder="Apellido Materno">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="id_cat_gen">Género</label>
                                <select class="form-control" name="id_cat_gen">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($generos as $genero): ?>
                                        <option value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="edad">Edad</label>
                                <input type="number" class="form-control" min="1" max="130" maxlength="4" name="edad">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" class="form-control" maxlength="10" name="telefono">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" name="email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_nacionalidad">Nacionalidad</label>
                                <select class="form-control" name="id_cat_nacionalidad">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($nacionalidades as $nacionalidad): ?>
                                        <option value="<?php echo $nacionalidad['id_cat_nacionalidad']; ?>"><?php echo ucwords($nacionalidad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_mun">Municipio</label>
                                <select class="form-control" name="id_cat_mun">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($municipios as $municipio): ?>
                                        <option value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_escolaridad">Escolaridad</label>
                                <select class="form-control" name="id_cat_escolaridad">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($escolaridades as $escolaridad): ?>
                                        <option value="<?php echo $escolaridad['id_cat_escolaridad']; ?>"><?php echo ucwords($escolaridad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_ocup">Ocupación</label>
                                <select class="form-control" name="id_cat_ocup">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($ocupaciones as $ocupacion): ?>
                                        <option value="<?php echo $ocupacion['id_cat_ocup']; ?>"><?php echo ucwords($ocupacion['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="leer_escribir">¿Sabe leer y escribir?</label>
                                <select class="form-control" name="leer_escribir">
                                    <option value="">Escoge una opción</option>
                                    <option value="Leer">Leer</option>
                                    <option value="Escribir">Escribir</option>
                                    <option value="Ambos">Ambos</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_disc">¿Tiene alguna discapacidad?</label>
                                <select class="form-control" name="id_cat_disc">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($discapacidades as $discapacidad): ?>
                                        <option value="<?php echo $discapacidad['id_cat_disc']; ?>"><?php echo ucwords($discapacidad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_cat_grupo_vuln">Grupo Vulnerable</label>
                                <select class="form-control" name="id_cat_grupo_vuln">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($grupos_vuln as $grupo_vuln): ?>
                                        <option value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_cat_comun">Comunidad</label>
                                <select class="form-control" name="id_cat_comun">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($comunidades as $comunidad): ?>
                                        <option value="<?php echo $comunidad['id_cat_comun']; ?>"><?php echo ucwords($comunidad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ppl">Persona Priv. de la Libertad</label>
                                <select class="form-control" name="ppl">
                                    <option value="">Escoge una opción</option>
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_cat_ppl">Si es PPL, ¿Quién presenta la queja?</label>
                                <select class="form-control" name="id_cat_ppl">
                                    <option value="6">Escoge una opción</option>
                                    <?php foreach ($ppls as $ppl): ?>
                                        <option value="<?php echo $ppl['id_cat_ppl']; ?>"><?php echo ucwords($ppl['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <a href="quejosos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_quejoso" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include_once('layouts/footer.php'); ?>