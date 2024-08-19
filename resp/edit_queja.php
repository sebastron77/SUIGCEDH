<script type="text/javascript" src="libs/js/quejoso.js"></script>
<script type="text/javascript" src="libs/js/agraviado.js"></script>
<?php
$page_title = 'Editar Queja';
require_once('includes/load.php');
?>
<?php
$e_detalle = find_by_id_queja((int) $_GET['id']);
if (!$e_detalle) {
    redirect('quejas.php');
}
$user = current_user();
$nivel = $user['user_level'];

$cat_medios_pres = find_all_medio_pres();
$cat_autoridades = find_all_aut_res();
$cat_quejosos = find_all_quejosos();
$cat_agraviados = find_all('cat_agraviados');
$users = find_all('users');
$asigna_a = find_all_area_userQ();
$area = find_all_areas_quejas();
$cat_est_procesal = find_all_estatus_procesal();
$cat_municipios = find_all_cat_municipios();
$cat_entidad = find_all_cat_entidad();
$cat_tipo_ambito = find_all('cat_tipo_ambito');

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    redirect('home.php');
}
if ($nivel == 4) {
    redirect('home.php');
}
if ($nivel == 5) {
    page_require_level(5);
}
if ($nivel == 6) {
    redirect('home.php');
}
if ($nivel == 7) {
    page_require_level(7);
}
?>

<?php
if (isset($_POST['edit_queja'])) {
    if (empty($errors)) {
        $id = (int) $e_detalle['id_queja_date'];
        $fecha_presentacion = remove_junk($db->escape($_POST['fecha_presentacion']));
        $id_cat_med_pres = remove_junk($db->escape($_POST['id_cat_med_pres']));
        $id_cat_aut = remove_junk($db->escape($_POST['id_cat_aut']));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
        $id_cat_quejoso = remove_junk($db->escape($_POST['id_cat_quejoso']));
        $id_cat_agraviado = remove_junk($db->escape($_POST['id_cat_agraviado']));
        $id_user_asignado = remove_junk($db->escape($_POST['id_user_asignado']));
        $id_area_asignada = remove_junk($db->escape($_POST['id_area_asignada']));
        $id_cat_est_procesal = remove_junk($db->escape($_POST['id_cat_est_procesal']));
        $dom_calle = remove_junk($db->escape($_POST['dom_calle']));
        $dom_numero = remove_junk($db->escape($_POST['dom_numero']));
        $dom_colonia = remove_junk($db->escape($_POST['dom_colonia']));
        $id_cat_mun = remove_junk($db->escape($_POST['id_cat_mun']));
        $revision_presidencia = remove_junk($db->escape($_POST['revision_presidencia']));
        // $transferir = remove_junk($db->escape($_POST['transferir']));
        $descripcion_hechos = remove_junk($db->escape($_POST['descripcion_hechos']));
        date_default_timezone_set('America/Mexico_City');
        $fecha_actualizacion = date('Y-m-d H:i:s');

        $folio_editar = $e_detalle['folio_queja'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/quejas/' . $resultado;

        $name = $_FILES['adjunto']['name'];
        $size = $_FILES['adjunto']['size'];
        $type = $_FILES['adjunto']['type'];
        $temp = $_FILES['adjunto']['tmp_name'];

        if (is_dir($carpeta)) {
            $move = move_uploaded_file($temp, $carpeta . "/" . $name);
        } else {
            mkdir($carpeta, 0777, true);
            $move = move_uploaded_file($temp, $carpeta . "/" . $name);
        }

        $nombre = $e_detalle['nombre_quejoso'];
        $paterno = $e_detalle['paterno_quejoso'];
        $materno = $e_detalle['materno_quejoso'];
        $email = $e_detalle['email'];
        $telefono = $e_detalle['telefono'];
        $id_cat_ocup = $e_detalle['id_cat_ocup'];
        $id_cat_grupo_vuln = $e_detalle['id_cat_grupo_vuln'];
        $id_cat_escolaridad = $e_detalle['id_cat_escolaridad'];
        $edad = $e_detalle['edad'];
        $id_cat_gen = $e_detalle['id_cat_gen'];
        $nacionalidad = $e_detalle['id_cat_nacionalidad'];
        $med_pres = 5;

        if ($name != '') {
            $sql = "UPDATE quejas_dates SET fecha_presentacion='{$fecha_presentacion}', id_cat_med_pres='{$id_cat_med_pres}', id_cat_aut='{$id_cat_aut}', archivo='{$name}',
                    observaciones='{$observaciones}', id_cat_quejoso='$id_cat_quejoso', id_cat_agraviado='$id_cat_agraviado', id_user_asignado='$id_user_asignado', 
                    id_area_asignada='$id_area_asignada', id_estatus_queja=NULL, estado_procesal='{$id_cat_est_procesal}', dom_calle='$dom_calle', dom_numero='$dom_numero', dom_colonia='$dom_colonia', 
                    id_cat_mun='$id_cat_mun', descripcion_hechos='$descripcion_hechos', fecha_actualizacion='$fecha_actualizacion', notificacion = 0, revision_presidencia='$revision_presidencia' WHERE id_queja_date='{$db->escape($id)}'";
        }
        if ($name == '') {
            $sql = "UPDATE quejas_dates SET fecha_presentacion='{$fecha_presentacion}', id_cat_med_pres='{$id_cat_med_pres}', id_cat_aut='{$id_cat_aut}',
                    observaciones='{$observaciones}', id_cat_quejoso='$id_cat_quejoso', id_cat_agraviado='$id_cat_agraviado', id_user_asignado='$id_user_asignado', 
                    id_area_asignada='$id_area_asignada', id_estatus_queja=NULL, estado_procesal='{$id_cat_est_procesal}', dom_calle='$dom_calle', dom_numero='$dom_numero', dom_colonia='$dom_colonia', 
                    id_cat_mun='$id_cat_mun', descripcion_hechos='$descripcion_hechos', fecha_actualizacion='$fecha_actualizacion', notificacion = 0, revision_presidencia='$revision_presidencia' WHERE id_queja_date='{$db->escape($id)}'";
        }

        // $carpeta = 'uploads/quejas/' . $folio2 . '/imagenes';

        // if (!is_dir($carpeta)) {
        //     mkdir($carpeta, 0777, true);
        // }

        if (isset($_FILES['imagen'])) {

            $cantidad = count($_FILES["imagen"]["tmp_name"]);

            for ($i = 0; $i < $cantidad; $i++) {
                //Comprobamos si el fichero es una imagen
                if ($_FILES['imagen']['type'][$i] == 'image/png' || $_FILES['imagen']['type'][$i] == 'image/jpeg') {
                    $folio1 = $e_detalle['folio_queja'];
                    $folio2 = str_replace("/", "-", $folio1);
                    $carpetai = 'uploads/quejas/' . $folio2 . '/imagenes';
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

        $sql2 = "UPDATE rel_queja_aut SET id_cat_aut='{$id_cat_aut}' WHERE id_queja_date='{$db->escape($id)}'";
        $result = $db->query($sql);
        $result2 = $db->query($sql2);
        insertAccion($user['id_user'], '"' . $user['username'] . '" editó la queja, Folio: ' . $folio_editar . '.', 2);

        if (($result && $db->affected_rows() === 1) && ($result2 && $db->affected_rows() === 1)) {
            $session->msg('s', "Información Actualizada ");
            redirect('quejas.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos.');
            redirect('edit_queja.php?id=' . (int) $e_detalle['id'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_queja.php?id=' . (int) $e_detalle['id'], false);
    }
}
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar queja
                    <?php echo $e_detalle['folio_queja'] ?>
                </span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_queja.php?id=<?php echo (int) $e_detalle['id_queja_date']; ?>" enctype="multipart/form-data">
                <h3 style="font-weight:bold;">
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
                            <input type="datetime-local" class="form-control" name="fecha_presentacion" value="<?php echo remove_junk($e_detalle['fecha_presentacion']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_med_pres">Medio Presetación <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="id_cat_med_pres" required>
                                <?php foreach ($cat_medios_pres as $medio_pres) : ?>
                                    <option <?php if ($medio_pres['id_cat_med_pres'] === $e_detalle['id_cat_med_pres'])
                                                echo 'selected="selected"'; ?> value="<?php echo $medio_pres['id_cat_med_pres']; ?>">
                                        <?php echo ucwords($medio_pres['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_cat_aut">Autoridad Responsable <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="id_cat_aut">
                                <?php foreach ($cat_autoridades as $autoridad) : ?>
                                    <option <?php if ($autoridad['id_cat_aut'] === $e_detalle['id_cat_aut'])
                                                echo 'selected="selected"'; ?> value="<?php echo $autoridad['id_cat_aut']; ?>"><?php echo ucwords($autoridad['nombre_autoridad']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_quejoso">Quejoso <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="id_cat_quejoso">
                                <?php foreach ($cat_quejosos as $quejoso) : ?>
                                    <option <?php if ($quejoso['id_cat_quejoso'] === $e_detalle['id_cat_quejoso'])
                                                echo 'selected="selected"'; ?> value="<?php echo $quejoso['id_cat_quejoso']; ?>"><?php
                                                                                                                                    echo ucwords($quejoso['nombre'] . " " . $quejoso['paterno'] . " " . $quejoso['materno']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_agraviado">Agraviado <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="id_cat_agraviado">
                                <?php foreach ($cat_agraviados as $agraviado) : ?>
                                    <option <?php if ($agraviado['id_cat_agrav'] === $e_detalle['id_cat_agraviado'])
                                                echo 'selected="selected"'; ?> value="<?php echo $agraviado['id_cat_agrav']; ?>"><?php
                                                                                                                                    echo ucwords($agraviado['nombre'] . " " . $agraviado['paterno'] . " " . $agraviado['materno']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area_asignada">Área a la que se asigna <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" id="id_area_asignada" name="id_area_asignada">
                                <?php foreach ($area as $a) : ?>
                                    <option <?php if ($a['id_area'] === $e_detalle['id_area_asignada']) echo 'selected="selected"'; ?> 
                                                    value="<?php echo $a['id_area']; ?>"><?php echo ucwords($a['nombre_area']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <?php $asigna_a = find_all_trabajadores_area($e_detalle['id_area_asignada']) ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_user_asignado">Se asigna a <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" id="id_user_asignado" name="id_user_asignado">
                                <?php foreach ($asigna_a as $asigna) : ?>
                                    <option <?php if ($asigna['id_det_usuario'] === $e_detalle['id_user_asignado'])
                                                echo 'selected="selected"'; ?> value="<?php echo $asigna['id_det_usuario']; ?>">
                                                <?php echo ucwords($asigna['nombre'] . " " . $asigna['apellidos']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
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
                            <label for="id_cat_est_procesal">Estado Procesal <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="id_cat_est_procesal">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_est_procesal as $estatus) : ?>
                                    <option <?php if ($estatus['id_cat_est_procesal'] === $e_detalle['id_cat_est_procesal'])
                                                echo 'selected="selected"'; ?> value="<?php echo $estatus['id_cat_est_procesal']; ?>"><?php
                                                                                                                                        echo ucwords($estatus['descripcion']) ?>
                                    </option>
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
                            <input type="text" class="form-control" name="dom_calle" placeholder="Calle" value="<?php echo $e_detalle['dom_calle'] ?>">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="dom_numero">Núm. ext/int</label>
                            <input type="text" class="form-control" name="dom_numero" value="<?php echo $e_detalle['dom_numero'] ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="dom_colonia">Colonia</label>
                            <input type="text" class="form-control" name="dom_colonia" placeholder="Colonia" value="<?php echo $e_detalle['dom_colonia'] ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ent_fed">Entidad Federativa</label>
                            <select class="form-control" name="ent_fed">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_entidad as $id_cat_ent_fed) : ?>
                                    <option <?php if ($id_cat_ent_fed['descripcion'] === $e_detalle['ent_fed'])
                                                echo 'selected="selected"'; ?> value="<?php echo $id_cat_ent_fed['descripcion']; ?>"><?php echo ucwords($id_cat_ent_fed['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_mun">Municipio</label>
                            <select class="form-control" name="id_cat_mun">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_municipios as $municipio) : ?>
                                    <option <?php if ($municipio['id_cat_mun'] === $e_detalle['id_cat_mun'])
                                                echo 'selected="selected"'; ?> value="<?php echo $municipio['id_cat_mun']; ?>"><?php
                                                                                                                                echo ucwords($municipio['descripcion']) ?>
                                    </option>
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
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto" value="uploads/quejas/<?php echo $e_detalle['archivo']; ?>">
                            <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                <?php echo remove_junk($e_detalle['archivo']); ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_tipo_ambito">Tipo Ámbito <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="id_tipo_ambito" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_tipo_ambito as $ambito) : ?>
                                    <option <?php if ($ambito['id_cat_tipo_ambito'] === $e_detalle['id_tipo_ambito'])
                                                echo 'selected="selected"'; ?> value="<?php echo $ambito['id_cat_tipo_ambito']; ?>"><?php echo ucwords($ambito['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="imagen">Añadir imagen(es): </label>
                        <input class="form-control" name="imagen[]" id="imagen" type="file" multiple />
                    </div>
                    <div class="col-md-3">
                        <label for="revision_presidencia">Enviar a análisis: </label>
                        <select class="form-control" name="revision_presidencia">
                            <option value="">Escoge una opción</option>
                            <option <?php if ($e_detalle['revision_presidencia'] === '1') echo 'selected="selected"'; ?> value="1">Sí</option>
                            <option <?php if ($e_detalle['revision_presidencia'] === '0') echo 'selected="selected"'; ?> value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="descripcion_hechos">Descripción de los hechos <span style="color:red;font-weight:bold">*</span></label>
                            <textarea class="form-control" name="descripcion_hechos" id="descripcion_hechos" cols="30" rows="5" required value="<?php echo $e_detalle['descripcion_hechos'] ?>"><?php echo $e_detalle['descripcion_hechos'] ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Notas Internas</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="30" rows="5" value="<?php echo $e_detalle['observaciones'] ?>"><?php echo $e_detalle['observaciones'] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="quejas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_queja" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>