<?php
$page_title = 'Convertir Queja';
require_once('includes/load.php');
?>
<?php
$e_detalle = find_by_id_queja((int) $_GET['id']);
if (!$e_detalle) {
    $session->msg("d", "ID de queja no encontrado.");
    redirect('quejas.php');
}
$user = current_user();
$nivel = $user['user_level'];
$detalle = $user['id_user'];
$id_ori_canal = last_id_oricanal();
$id_folio = last_id_folios();
$entidades = find_all('cat_entidad_fed');

$cat_medios_pres = find_all_medio_pres();
$cat_autoridades = find_all_aut_res();
$cat_quejosos = find_all_quejosos();
$cat_agraviados = find_all('cat_agraviados');
$users = find_all('users');
$asigna_a = find_all_area_userQ();
$area = find_all_areas_quejas();
$cat_estatus_queja = find_all_estatus_procesal();
$cat_municipios = find_all_cat_municipios();
$cat_tipo_resolucion = find_all('cat_tipo_res');

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
if (isset($_POST['convertir_queja'])) {
    if (empty($errors)) {
        $id = (int) $e_detalle['id_queja_date'];
        $solicitud = remove_junk($db->escape($_POST['solicitud']));
        $lengua = remove_junk($db->escape($_POST['lengua']));
        //$localidad = remove_junk($db->escape($_POST['localidad']));
        $cod_post = remove_junk($db->escape($_POST['cod_post']));
        $entidad = remove_junk($db->escape($_POST['entidad']));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));

        date_default_timezone_set('America/Mexico_City');
        $fecha_actualizacion = date('Y-m-d');

        $nombre = $e_detalle['nombre_quejoso'];
        $paterno = $e_detalle['paterno_quejoso'];
        $materno = $e_detalle['materno_quejoso'];
        $nombreC = $e_detalle['nombre_quejoso'] . ' ' . $paterno = $e_detalle['paterno_quejoso'] . ' ' . $materno = $e_detalle['materno_quejoso'];;
        $email = $e_detalle['email'];
        $telefono = $e_detalle['telefono'];
        $id_cat_ocup = $e_detalle['id_cat_ocup'];
        $id_cat_grupo_vuln = $e_detalle['id_cat_grupo_vuln'];
        $id_cat_escolaridad = $e_detalle['id_cat_escolaridad'];
        $edad = $e_detalle['edad'];
        $id_cat_gen = $e_detalle['id_cat_gen'];
        $nacionalidad = $e_detalle['id_cat_nacionalidad'];
        $med_pres = 5;
        $calle_num = $e_detalle['dom_calle'] . ' #' . $e_detalle['dom_numero'];
        $colonia = $e_detalle['dom_colonia'];
        $municipio = $e_detalle['id_cat_mun'];
        $localidad = $e_detalle['localidad'];
        $autoridad = $e_detalle['id_cat_aut'];

        $folio_editar = $e_detalle['folio_queja'];
        $resultado = str_replace("/", "-", $folio_editar);

        if ($solicitud == 1) {
            $folio_editar;
            $nuevo_folio = str_replace("Q", "O", $folio_editar);
        }
        if ($solicitud == 2) {
            $folio_editar;
            $nuevo_folio = str_replace("Q", "C", $folio_editar);
        }


        //Suma el valor del id anterior + 1, para generar ese id para el nuevo resguardo
        //La variable $no_folio sirve para el numero de folio
        // if (count($id_ori_canal) == 0) {
        //     $nuevo_id_ori_canal = 1;
        //     $no_folio = sprintf('%04d', 1);
        // } else {
        //     foreach ($id_ori_canal as $nuevo) {
        //         $nuevo_id_ori_canal = (int) $nuevo['id_or_can'] + 1;
        //         $no_folio = sprintf('%04d', (int) $nuevo['id_or_can'] + 1);
        //     }
        // }

        // if (count($id_folio) == 0) {
        //     $nuevo_id_folio = 1;
        //     $no_folio1 = sprintf('%04d', 1);
        // } else {
        //     foreach ($id_folio as $nuevo) {
        //         $nuevo_id_folio = (int) $nuevo['contador'] + 1;
        //         $no_folio1 = sprintf('%04d', (int) $nuevo['contador'] + 1);
        //     }
        // }

        //Se crea el número de folio
        // $year = date("Y");
        // Se crea el folio orientacion
        // $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-O';

        // $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-O';
        $folio_carpeta = str_replace("/", "-", $nuevo_folio);
        if($solicitud == 1){
            $carpeta = 'uploads/orientacioncanalizacion/orientacion/' . $folio_carpeta;
        }
        if($solicitud == 2){
            $carpeta = 'uploads/orientacioncanalizacion/canalizacion/' . $folio_carpeta;
        }

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['adjunto']['name'];
        $size = $_FILES['adjunto']['size'];
        $type = $_FILES['adjunto']['type'];
        $temp = $_FILES['adjunto']['tmp_name'];

        $move = move_uploaded_file($temp, $carpeta . "/" . $name);

        $id_t_folios = $e_detalle['id_folio'];

        if ($move && $name != '') {
            $query = "INSERT INTO orientacion_canalizacion (";
            $query .= "folio,correo_electronico,nombre_completo,nivel_estudios,ocupacion,edad,telefono,extension,sexo,calle_numero,colonia,codigo_postal,id_cat_mun,municipio_localidad,entidad,
                        nacionalidad,tipo_solicitud,medio_presentacion,institucion_canaliza,grupo_vulnerable,lengua,observaciones,adjunto,id_creador,creacion";
            $query .= ") VALUES (";
            $query .= " '{$nuevo_folio}','{$email}','{$nombreC}','{$id_cat_escolaridad}','{$id_cat_ocup}','{$edad}','{$telefono}','0','{$id_cat_gen}','{$calle_num}','{$colonia}','{$cod_post}','{$municipio}','{$localidad}',
                        '{$entidad}','{$nacionalidad}','{$solicitud}','{$med_pres}','{$autoridad}','{$id_cat_grupo_vuln}','{$lengua}','{$observaciones}','{$name}','{$detalle}','{$fecha_actualizacion}'";
            $query .= ")";

            $query2 = "UPDATE folios SET folio='{$nuevo_folio}' WHERE id_folio='{$db->escape($id_t_folios)}'";
            $query3 = "DELETE FROM quejas_dates WHERE id_queja_date = '{$db->escape($id)}'";
        } else {
            $query = "INSERT INTO orientacion_canalizacion (";
            $query .= "folio,correo_electronico,nombre_completo,nivel_estudios,ocupacion,edad,telefono,extension,sexo,calle_numero,colonia,codigo_postal,id_cat_mun,municipio_localidad,entidad,
                        nacionalidad,tipo_solicitud,medio_presentacion,institucion_canaliza,grupo_vulnerable,lengua,observaciones,adjunto,id_creador,creacion";
            $query .= ") VALUES (";
            $query .= " '{$nuevo_folio}','{$email}','{$nombreC}','{$id_cat_escolaridad}','{$id_cat_ocup}','{$edad}','{$telefono}','0','{$id_cat_gen}','{$calle_num}','{$colonia}','{$cod_post}','{$municipio}','{$localidad}',
                        '{$entidad}','{$nacionalidad}','{$solicitud}','{$med_pres}','{$autoridad}','{$id_cat_grupo_vuln}','{$lengua}','{$observaciones}','{$name}','{$detalle}','{$fecha_actualizacion}'";
            $query .= ")";

            $query2 = "UPDATE folios SET folio='$nuevo_folio' WHERE id_folio='{$db->escape($id_t_folios)}'";
            $query3 = "DELETE FROM quejas_dates WHERE id_queja_date='{$db->escape($id)}'";
        }

        if ($db->query($query) && $db->query($query2) && $db->query($query3)) {
            //sucess
            $session->msg('s', " La orientación ha sido agregada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó orientación, Folio: ' . $folio . '.', 1);
            redirect('solicitudes_quejas.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la orientación.');
            redirect('solicitudes_quejas.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('solicitudes_quejas.php', false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Conversión a Orientación/Canalización</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="convertir_queja.php?id=<?php echo (int) $e_detalle['id_queja_date']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_quejoso">Nombre del Quejoso</label>
                            <input type="text" class="form-control" name="nombre_quejoso" value="<?php echo remove_junk($e_detalle['nombre_quejoso'] . ' ' . $e_detalle['paterno_quejoso'] . ' ' . $e_detalle['materno_quejoso']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ocupacion">Ocupación del Quejoso</label>
                            <input type="text" class="form-control" name="ocupacion" value="<?php echo remove_junk($e_detalle['ocup']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="email">Correo</label>
                            <input type="text" class="form-control" name="email" value="<?php echo remove_junk($e_detalle['email']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" value="<?php echo remove_junk($e_detalle['telefono']); ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="escolaridad">Nivel de Estudios</label>
                            <input type="text" class="form-control" value="<?php echo remove_junk($e_detalle['escolaridad']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="edad">Edad</label>
                            <input type="text" class="form-control" value="<?php echo remove_junk($e_detalle['edad']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="gv">Grupo vulnerable</label>
                            <input type="text" class="form-control" value="<?php echo remove_junk($e_detalle['gv']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="genero">Género</label>
                            <input type="text" class="form-control" value="<?php echo remove_junk($e_detalle['genero']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="genero">Nacionalidad</label>
                            <input type="text" class="form-control" value="<?php echo remove_junk($e_detalle['nacionalidad']); ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="calle">Calle y Num.</label>
                            <input type="text" class="form-control" value="<?php echo remove_junk($e_detalle['dom_calle'] . ' #' . $e_detalle['dom_numero']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="colonia">Colonia</label>
                            <input type="text" class="form-control" value="<?php echo remove_junk($e_detalle['dom_colonia']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="municipio">Municipio</label>
                            <input type="text" class="form-control" value="<?php echo remove_junk($e_detalle['localidad']); ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="mp">Medio de Presentación</label>
                            <input type="text" class="form-control" value="<?php echo remove_junk($e_detalle['medio_pres']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="autr">Autoridad Responsable</label>
                            <input type="text" class="form-control" value="<?php echo remove_junk($e_detalle['nombre_autoridad']); ?>" readonly>
                        </div>
                    </div>
                </div>

                <hr style="height: 1px; background-color: #370494; opacity: 1;">
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7263F0" width="25px" height="25px" viewBox="0 0 24 24" style="margin-top:-0.3%;">
                        <title>arrow-right-circle</title>
                        <path d="M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M6,13H14L10.5,16.5L11.92,17.92L17.84,12L11.92,6.08L10.5,7.5L14,11H6V13Z" />
                    </svg>
                    <span style="font-size: 20px; color: #7263F0">CONVERTIR QUEJA A ORIENTACIÓN/CANALIZACIÓN</span><br><br>
                    <span style="font-size: 15px;">* Completa la siguiente información</span><br><br>
                </strong>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="solicitud">Tipo de Solicitud</label>
                            <select class="form-control" id="solicitud" name="solicitud">
                                <option value="">Escoge una opción</option>
                                <option value="1">Orientación</option>
                                <option value="2">Canalización</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="lengua">Lengua</label>
                            <input type="text" class="form-control" name="lengua" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cod_post">Código Postal</label>
                            <input type="text" class="form-control" name="cod_post" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="entidad">Entidad</label>
                            <select class="form-control" name="entidad">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($entidades as $entidad) : ?>
                                    <option value="<?php echo $entidad['id_cat_ent_fed']; ?>"><?php echo ucwords($entidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="adjunto">Archivo adjunto (si es necesario)</label>
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label><br>
                            <textarea name="observaciones" class="form-control" id="observaciones" cols="50" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <a href="quejas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
                <button type="submit" name="convertir_queja" class="btn btn-primary" value="subir">Guardar</button>
        </div>
        </form>
    </div>
</div>
</div>

<?php include_once('layouts/footer.php'); ?>