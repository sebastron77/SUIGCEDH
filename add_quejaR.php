<?php
$page_title = 'Editar Queja';
require_once('includes/load.php');
?>
<?php
$e_detalle = find_by_id_quejaR((int) $_GET['id']);
if (!$e_detalle) {
    // $session->msg("d", "ID de queja no encontrado.");
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
$cat_estatus_queja = find_all_estatus_queja();
$cat_municipios = find_all_cat_municipios();

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
        $id_estatus_queja = remove_junk($db->escape($_POST['id_estatus_queja']));
        $dom_calle = remove_junk($db->escape($_POST['dom_calle']));
        $dom_numero = remove_junk($db->escape($_POST['dom_numero']));
        $dom_colonia = remove_junk($db->escape($_POST['dom_colonia']));
        $id_cat_mun = remove_junk($db->escape($_POST['id_cat_mun']));
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
		
		/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}

        if ($name != '') {
            $sql = "UPDATE quejas_dates SET fecha_presentacion='{$fecha_presentacion}', id_cat_med_pres='{$id_cat_med_pres}', id_cat_aut='{$id_cat_aut}', archivo='{$name}',
                    observaciones='{$observaciones}', id_cat_quejoso='$id_cat_quejoso', id_cat_agraviado='$id_cat_agraviado', id_user_asignado='$id_user_asignado', 
                    id_area_asignada='$id_area_asignada', id_estatus_queja='$id_estatus_queja', dom_calle='$dom_calle', dom_numero='$dom_numero', dom_colonia='$dom_colonia', 
                    id_cat_mun='$id_cat_mun', descripcion_hechos='$descripcion_hechos', fecha_actualizacion='$fecha_actualizacion' WHERE id_queja_date='{$db->escape($id)}'";
        }
        if ($name == '') {
            $sql = "UPDATE quejas_dates SET fecha_presentacion='{$fecha_presentacion}', id_cat_med_pres='{$id_cat_med_pres}', id_cat_aut='{$id_cat_aut}',
                    observaciones='{$observaciones}', id_cat_quejoso='$id_cat_quejoso', id_cat_agraviado='$id_cat_agraviado', id_user_asignado='$id_user_asignado', 
                    id_area_asignada='$id_area_asignada', id_estatus_queja='$id_estatus_queja', dom_calle='$dom_calle', dom_numero='$dom_numero', dom_colonia='$dom_colonia', 
                    id_cat_mun='$id_cat_mun', descripcion_hechos='$descripcion_hechos', fecha_actualizacion='$fecha_actualizacion' WHERE id_queja_date='{$db->escape($id)}'";
        }
        $sql2 = "UPDATE rel_queja_aut SET id_cat_aut='{$id_cat_aut}' WHERE id_queja_date='{$db->escape($id)}'";
        $result = $db->query($sql);
        $result2 = $db->query($sql2);

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
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar queja
                    <?php echo $e_detalle['folio_queja_p']; ?>
                </span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_quejaR.php?id=<?php echo (int) $e_detalle['id_queja_date_p']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_presentacion">Fecha de creación</label>
                            <input type="datetime-local" class="form-control" name="fecha_presentacion" value="<?php echo remove_junk($e_detalle['fecha_creacion']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_med_pres">Medio Presetación</label>
                            <select class="form-control" name="id_cat_med_pres">
                                <option value="5">En línea</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="id_cat_aut">Autoridad Responsable</label>
                            <select class="form-control" name="id_cat_aut">
                                <option value="<?php echo remove_junk($e_detalle['nombre_autoridad']); ?>"><?php echo remove_junk($e_detalle['nombre_autoridad']); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_quejoso">Quejoso</label>
                            <input class="form-control" type="text" value="<?php echo ucwords($e_detalle['nombre'] . " " . $e_detalle['paterno'] . " " . $e_detalle['materno']); ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_agraviado">Agraviado</label>
                            <input class="form-control" type="text" value="<?php echo ucwords($e_detalle['nombre'] . " " . $e_detalle['paterno'] . " " . $e_detalle['materno']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_user_asignado">Se asigna a</label>
                            <select class="form-control" name="id_user_asignado">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($asigna_a as $asigna) : ?>
                                    <option value="<?php echo $asigna['id_det_usuario']; ?>"><?php echo ucwords($asigna['nombre'] . " " . $asigna['apellidos']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area_asignada">Área a la que se asigna</label>
                            <select class="form-control" name="id_area_asignada">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($area as $a) : ?>
                                    <option value="<?php echo $a['id_area']; ?>"><?php echo ucwords($a['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_estatus_queja">Estatus de Queja</label>
                            <select class="form-control" name="id_estatus_queja">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_estatus_queja as $estatus) : ?>
                                    <option value="<?php echo $estatus['id_cat_est_queja']; ?>"><?php echo ucwords($estatus['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dom_calle">Calle</label>
                            <input type="text" class="form-control" name="dom_calle" placeholder="Calle" value="<?php echo $e_detalle['calle'] ?>">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="dom_numero">Núm. ext/int</label>
                            <input type="text" class="form-control" name="dom_numero" value="<?php echo $e_detalle['numero'] ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dom_colonia">Colonia</label>
                            <input type="text" class="form-control" name="dom_colonia" placeholder="Colonia" value="<?php echo $e_detalle['colonia'] ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_mun">Municipio</label>
                            <select class="form-control" name="id_cat_mun">
                                <option value="<?php echo $e_detalle['municipio'] ?>"><?php echo $e_detalle['municipio'] ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="adjunto">Archivo adjunto (si es necesario)</label>
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto" value="uploads/quejas/<?php echo $e_detalle['archivo']; ?>">
                            <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                <?php echo remove_junk($e_detalle['archivo']); ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="descripcion_hechos">Descripción de los hechos</label>
                            <textarea class="form-control" name="descripcion_hechos" id="descripcion_hechos" cols="30" rows="5" value="<?php echo $e_detalle['descripcion_hechos'] ?>"><?php echo $e_detalle['descripcion_hechos'] ?></textarea>
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
                    <a href="quejas_publicas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_queja" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>