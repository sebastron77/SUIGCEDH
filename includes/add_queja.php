
<script type="text/javascript" src="libs/js/quejoso.js"></script>
<script type="text/javascript" src="libs/js/agraviado.js"></script>
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Agregar Queja';
require_once('includes/load.php');
$user = current_user();
$detalle = $user['id_user'];
$id_queja = last_id_queja();
$id_folio = last_id_folios();
$nivel = $user['user_level'];
$nivel_user = $user['user_level'];

$cat_medios_pres = find_all_medio_pres();
$cat_autoridades = find_all_aut_res();
$cat_quejosos = find_all_quejosos();
$cat_agraviados = find_all('cat_agraviados');
$users = find_all('users');
$asigna_a = find_all_area_userQ();
$area = find_all_areas_quejas();
$cat_estatus_queja = find_all_estatus_procesal();
$cat_entidad = find_all_cat_entidad();
$cat_municipios = find_all_cat_municipios();
$cat_est_procesal = find_all('cat_est_procesal');
$cat_tipo_resolucion = find_all('cat_tipo_res');
$cat_tipo_ambito = find_all('cat_tipo_ambito');

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
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}
if ($nivel_user > 2 && $nivel_user < 5) :
    redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user == 7) :
    redirect('home.php');
endif;
if ($nivel_user == 19) :
    redirect('home.php');
endif;
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_queja'])) {

    $req_fields = array(
        'fecha_presentacion', 'id_cat_med_pres', 'id_cat_aut', 'id_user_asignado', 'id_area_asignada', 'dom_calle',
        'dom_numero', 'dom_colonia', 'descripcion_hechos'
    );
    validate_fields($req_fields);

    if (empty($errors)) {
        $fecha_presentacion = remove_junk($db->escape($_POST['fecha_presentacion']));
        $id_cat_med_pres = remove_junk($db->escape($_POST['id_cat_med_pres']));
        $id_cat_aut = remove_junk($db->escape($_POST['id_cat_aut']));
        $id_cat_quejoso = remove_junk($db->escape($_POST['quejoso']));
        $id_cat_agraviado = remove_junk($db->escape($_POST['agraviado']));
        $id_user_asignado = remove_junk($db->escape($_POST['id_user_asignado']));
        $id_area_asignada = remove_junk($db->escape($_POST['id_area_asignada']));
        $dom_calle = remove_junk($db->escape($_POST['dom_calle']));
        $dom_numero = remove_junk($db->escape($_POST['dom_numero']));
        $dom_colonia = remove_junk($db->escape($_POST['dom_colonia']));
        $descripcion_hechos = remove_junk($db->escape($_POST['descripcion_hechos']));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
        // $id_estatus_queja = remove_junk($db->escape($_POST['id_estatus_queja']));
        $ent_fed = remove_junk($db->escape($_POST['ent_fed']));
        $id_cat_mun = remove_junk($db->escape($_POST['id_cat_mun']));
        $localidad = remove_junk($db->escape($_POST['localidad']));
        $estado_procesal = remove_junk($db->escape($_POST['estado_procesal']));
        $id_tipo_ambito = remove_junk($db->escape($_POST['id_tipo_ambito']));
        date_default_timezone_set('America/Mexico_City');
        $creacion = date('Y-m-d H:i:s');

        if (count($id_queja) == 0) {
            $nuevo_id_queja = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_queja as $nuevo) {
                $nuevo_id_queja = (int) $nuevo['contador'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['contador'] + 1);
            }
        }

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

        if (isset($_FILES['imagen'])) {

            $cantidad = count($_FILES["imagen"]["tmp_name"]);

            for ($i = 0; $i < $cantidad; $i++) {
                //Comprobamos si el fichero es una imagen
                if ($_FILES['imagen']['type'][$i] == 'image/png' || $_FILES['imagen']['type'][$i] == 'image/jpeg' || $_FILES['imagen']['type'][$i] == 'image/tiff') {
                    $carpetai = 'uploads/quejas/' . $folio_carpeta . '/imagenes';
                    if (!is_dir($carpetai)) {
                        mkdir($carpetai, 0777, true);
                    }
                    //Subimos el fichero al servidor
                    $namei = $_FILES['imagen']['name'];
                    $sizei = $_FILES['imagen']['size'];
                    $typei = $_FILES['imagen']['type'];
                    $tempi = $_FILES['imagen']['tmp_name'];
                    $movei = move_uploaded_file($_FILES["imagen"]["tmp_name"][$i],  $carpetai . "/" . $_FILES["imagen"]["name"][$i]);
                    // move_uploaded_file();
                    $validar = true;
                } else $validar = false;
            }
        }


        $dbh = new PDO('mysql:host=localhost; dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "INSERT INTO quejas_dates (folio_queja,fecha_presentacion,id_cat_med_pres,id_cat_aut,observaciones,id_cat_quejoso,id_cat_agraviado,id_user_creador,
                    fecha_creacion,id_user_asignado,id_area_asignada,fecha_vencimiento,id_estatus_queja,archivo,dom_calle,dom_numero,dom_colonia,ent_fed,id_cat_mun,localidad,
                    descripcion_hechos,estado_procesal,id_tipo_ambito,id_tipo_resolucion,notificacion) 
                    VALUES ('{$folio}','{$fecha_presentacion}','{$id_cat_med_pres}','{$id_cat_aut}','{$observaciones}','{$id_cat_quejoso}','{$id_cat_agraviado}','{$detalle}','{$creacion}',
                    '{$id_user_asignado}','{$id_area_asignada}',NULL,NULL,'{$name}','{$dom_calle}','{$dom_numero}','{$dom_colonia}','{$ent_fed}',
                    '{$id_cat_mun}','{$localidad}', '{$descripcion_hechos}', '{$estado_procesal}', $id_tipo_ambito,1,0)";

        $query3 = "INSERT INTO folios (";
        $query3 .= "folio, contador";
        $query3 .= ") VALUES (";
        $query3 .= " '{$folio}','{$no_folio1}'";
        $query3 .= ")";

        //------------------BUSCA EL ID INSERTADO------------------
        $dbh->exec($query3);
        $dbh->exec($query);
        $id_insertado = $dbh->lastInsertId();

        $query2 = "INSERT INTO rel_queja_aut(id_cat_aut, id_queja_date) VALUES ({$id_cat_aut}, {$id_insertado})";

        if ($db->query($query2)) {
            //sucess
            $session->msg('s', " La queja ha sido agregada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó queja, Folio: ' . $folio . '.', 1);
            redirect('quejas.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la queja.');
            redirect('add_queja.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_queja.php', false);
    }
}
?>
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading" style="    height: 50px;">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Queja</span>
            </strong>
        </div>


        <div class="panel-heading" style="text-align: right; height: 80px;">
            <button type="button" class="btn btn-success" onclick="javascript:window.open('./add_quejoso_On.php','popup','width=600,height=600');">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-add" viewBox="0 0 16 16">
                    <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0Zm-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"></path>
                    <path d="M8.256 14a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Z"></path>
                </svg>
                Agregar Quejoso
            </button>
        </div>

        <div class="panel-body">
            <form method="post" action="add_queja.php" enctype="multipart/form-data">
                <h3 style="margin-top: -4%; font-weight:bold;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#3a3d44" width="25px" height="25px" viewBox="0 0 24 24">
                        <title>cog</title>
                        <path d="M12,15.5A3.5,3.5 0 0,1 8.5,12A3.5,3.5 0 0,1 12,8.5A3.5,3.5 0 0,1 15.5,12A3.5,3.5 0 0,1 12,15.5M19.43,12.97C19.47,12.65 19.5,12.33 19.5,12C19.5,11.67 19.47,11.34 19.43,11L21.54,9.37C21.73,9.22 21.78,8.95 21.66,8.73L19.66,5.27C19.54,5.05 19.27,4.96 19.05,5.05L16.56,6.05C16.04,5.66 15.5,5.32 14.87,5.07L14.5,2.42C14.46,2.18 14.25,2 14,2H10C9.75,2 9.54,2.18 9.5,2.42L9.13,5.07C8.5,5.32 7.96,5.66 7.44,6.05L4.95,5.05C4.73,4.96 4.46,5.05 4.34,5.27L2.34,8.73C2.21,8.95 2.27,9.22 2.46,9.37L4.57,11C4.53,11.34 4.5,11.67 4.5,12C4.5,12.33 4.53,12.65 4.57,12.97L2.46,14.63C2.27,14.78 2.21,15.05 2.34,15.27L4.34,18.73C4.46,18.95 4.73,19.03 4.95,18.95L7.44,17.94C7.96,18.34 8.5,18.68 9.13,18.93L9.5,21.58C9.54,21.82 9.75,22 10,22H14C14.25,22 14.46,21.82 14.5,21.58L14.87,18.93C15.5,18.67 16.04,18.34 16.56,17.94L19.05,18.95C19.27,19.03 19.54,18.95 19.66,18.73L21.66,15.27C21.78,15.05 21.73,14.78 21.54,14.63L19.43,12.97Z" />
                    </svg>
                    Generales de la Queja
                </h3>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_presentacion">Fecha de presentación <span style="color:red;font-weight:bold">*</span></label>
                            <input type="datetime-local" class="form-control" name="fecha_presentacion" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_med_pres">Medio Presetación <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="id_cat_med_pres" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_medios_pres as $medio_pres) : ?>
                                    <option value="<?php echo $medio_pres['id_cat_med_pres']; ?>"><?php echo ucwords($medio_pres['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_cat_aut">Autoridad Responsable <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="id_cat_aut" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_autoridades as $autoridades) : ?>
                                    <option value="<?php echo $autoridades['id_cat_aut']; ?>"><?php echo ucwords($autoridades['nombre_autoridad']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_quejoso">Quejoso <span style="color:red;font-weight:bold">*</span>
                                <div style="margin-left: 10px;" class="popup caja" onclick="myFunction()">
                                    <p style="font-size: 13px;">?</p>
                                    <span class="popuptext" id="myPopup">Ingresa nombre de quejoso y selecciónalo de la lista desplegada. Si no se encuentra registrado, deberás agregarlo en el botón "Agregar Quejoso".</span>
                                </div>
                            </label>
                            <div class="input_container">
                                <input class="form-control" autocomplete="off" type="text" id="id_cat_quejoso" onkeyup="autocompletar()" required>
                                <input type="hidden" id="quejoso" name="quejoso">
                                <ul id="lista_id" style="position: absolute; z-index: 100; background: white;"></ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_agrav">Agraviado <span style="color:red;font-weight:bold">*
                                    <div style="margin-left: 10px;" class="popup caja" onclick="myFunction2()">
                                        <p style="font-size: 13px;">?</p>
                                        <span class="popuptext" id="myPopup2">Ingresa nombre de agraviado y selecciónalo de la lista desplegada. Si no se encuentra registrado, deberás agregarlo en el botón "Agregar Quejoso".</span>
                                    </div>
                                </span></label>
                            <div class="input_container2">
                                <input class="form-control" autocomplete="off" type="text" id="id_cat_agrav" onkeyup="autocompletar2()" required>
                                <input type="hidden" id="agraviado" name="agraviado">
                                <ul id="lista_id2" style="position: absolute; z-index: 100; background: white;"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area_asignada">Área a la que se asigna <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" id="id_area_asignada" name="id_area_asignada" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($area as $a) : ?>
                                    <option value="<?php echo $a['id_area']; ?>"><?php echo ucwords($a['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <?php $trabajadores = find_all_trabajadores_area($a['id_area']) ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_user_asignado">Se asigna a</label>
                            <select class="form-control" id="id_user_asignado" name="id_user_asignado"></select>
                        </div>
                    </div>
                    <script>
                        $(function() {
                            $("#id_area_asignada").on("change", function() {
                                var variable = $(this).val();
                                $("#selected").html(variable);
                            })

                        });
                        $(function() {
                            $("#id_user_asignado").on("change", function() {
                                var variable2 = $(this).val();
                                $("#selected2").html(variable2);
                            })
                        });
                    </script>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="estado_procesal">Estado Procesal <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="estado_procesal" required>

                                <?php foreach ($cat_est_procesal as $est_pros) : ?>
                                    <option value="<?php echo $est_pros['id_cat_est_procesal']; ?>">
                                        <?php echo ucwords($est_pros['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h3 style="margin-top: 1%; font-weight:bold;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#3a3d44" height="25px" width="25px" viewBox="0 0 24 24">
                            <title>earth</title>
                            <path d="M17.9,17.39C17.64,16.59 16.89,16 16,16H15V13A1,1 0 0,0 14,12H8V10H10A1,1 0 0,0 11,9V7H13A2,2 0 0,0 15,5V4.59C17.93,5.77 20,8.64 20,12C20,14.08 19.2,15.97 17.9,17.39M11,19.93C7.05,19.44 4,16.08 4,12C4,11.38 4.08,10.78 4.21,10.21L9,15V16A2,2 0 0,0 11,18M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
                        </svg>
                        Datos donde ocurrieron los hechos
                    </h3>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="dom_calle">Calle</label>
                            <input type="text" class="form-control" name="dom_calle" placeholder="Calle" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="dom_numero">Núm.</label>
                            <div style="margin-left: 10px;" class="popup caja" onclick="myFunction3()">
                                <p style="font-size: 13px;">?</p>
                                <span class="popuptext" id="myPopup3">Ingresa únicamente el NÚMERO de domicilio donde ocurrieron los hechos. Si hay más datos, ingresalos en el apartado de "Notas Internas".</span>
                            </div>
                            <input type="text" class="form-control" name="dom_numero" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="dom_colonia">Colonia</label>
                            <input type="text" class="form-control" name="dom_colonia" placeholder="Colonia" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ent_fed">Entidad Federativa</label>
                            <select class="form-control" name="ent_fed" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_entidad as $id_cat_ent_fed) : ?>
                                    <option value="<?php echo $id_cat_ent_fed['descripcion']; ?>"><?php echo ucwords($id_cat_ent_fed['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_mun">Municipio</label>
                            <select class="form-control" name="id_cat_mun">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_municipios as $id_cat_municipio) : ?>
                                    <option value="<?php echo $id_cat_municipio['id_cat_mun']; ?>"><?php echo ucwords($id_cat_municipio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="localidad">Localidad</label>
                            <input type="text" class="form-control" name="localidad" placeholder="Localidad">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="adjunto">Archivo adjunto (si es necesario)</label>
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_tipo_ambito">Tipo Ámbito <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="id_tipo_ambito" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_tipo_ambito as $ambito) : ?>
                                    <option value="<?php echo $ambito['id_cat_tipo_ambito']; ?>"><?php echo ucwords($ambito['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="imagen">Añadir imagen(es): </label>
                        <input class="form-control" name="imagen[]" id="imagen" type="file" multiple />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="descripcion_hechos">Descripción de los hechos <span style="color:red;font-weight:bold">*</span></label>
                            <textarea class="form-control" name="descripcion_hechos" id="descripcion_hechos" cols="30" rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Notas Internas</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="30" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="quejas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button style="background: #300285; border-color:#300285;" type="submit" name="add_queja" class="btn btn-primary" onclick="return confirm('La queja será guardada. Verifica el folio generado por el sistema para que lo asignes de manera correcta a su expediente. Da clic en Aceptar para continuar.');">Guardar</button>
                </div>
            </form>
            <!-- <?php if (isset($_FILES['imagen']) && $validar == true) { ?>
                <?php $cantidad = count($_FILES["imagen"]["tmp_name"]);

                        for ($i = 0; $i < $cantidad; $i++) { ?>
                    <h1><?php echo $_FILES["imagen"]["name"][$i] ?></h1>
                    <img src="<?php echo $_FILES["imagen"]["name"][$i] ?>" width="100">
            <?php }
                    } ?> -->
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>