
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Estado Procesal de Queja';
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
$id_user = $user['id_user'];

$cat_medios_pres = find_all_medio_pres();
$cat_autoridades = find_all_aut_res();
$cat_quejosos = find_all_quejosos();
$users = find_all('users');
$area = find_all_areas_quejas();
$cat_municipios = find_all_cat_municipios();
$cat_hecho_vuln = find_all_hecho_vuln();
$cat_derecho_vuln = find_all_derecho_vuln();
$cat_derecho_gral = find_all_derecho_gral();
$cat_est_procesal = find_all('cat_est_procesal');
$hecho_vulnrado = find_by_violentados('rel_queja_hechos', 'cat_hecho_vuln', $e_detalle['id_queja_date']);
$rel_queja_hechos = ($hecho_vulnrado['id_cat_hecho_vuln'] ? 0 : $hecho_vulnrado['id_cat_hecho_vuln']);
$derecho_vulnrado = find_by_violentados('rel_queja_der_vuln', 'cat_der_vuln', $e_detalle['id_queja_date']);
$rel_queja_der_vuln = $derecho_vulnrado['id_cat_der_vuln'];
$derecho_general = find_by_violentados('rel_queja_der_gral', 'cat_derecho_general', $e_detalle['id_queja_date']);
$rel_queja_der_gral = $derecho_general['id_cat_derecho_general'];


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
if (isset($_POST['procesal_queja'])) {

    if (empty($errors)) {
        $id = (int) $e_detalle['id_queja_date'];
        $estado_procesal = remove_junk($db->escape($_POST['estado_procesal']));
        $fecha_acuerdo = remove_junk($db->escape($_POST['fecha_acuerdo']));
        $sintesis_documento = remove_junk($db->escape($_POST['sintesis_documento']));
        $publico = remove_junk($db->escape($_POST['publico'] == 'on' ? 1 : 0));

        $id_cat_hecho_vuln = remove_junk($db->escape($_POST['id_cat_hecho_vuln']));
        $id_cat_derecho_general = remove_junk($db->escape($_POST['id_cat_derecho_general']));
        $id_cat_der_vuln = remove_junk($db->escape($_POST['id_cat_derecho_vuln']));

        $folio_editar = $e_detalle['folio_queja'];

        $query = "DELETE FROM rel_queja_der_gral WHERE id_queja_date =" . $id;
        if ($db->query($query)) {
          //  echo "Registro eliminado con éxito.";
        } else {
          //  echo "ERROR: No se pudo eliminar registro $consulta. ";
        }

        $query = "DELETE FROM rel_queja_der_vuln WHERE id_queja_date =" . $id;
        if ($db->query($query)) {
            //echo "Registro eliminado con éxito.";
        } else {
           // echo "ERROR: No se pudo eliminar registro $consulta. ";
        }



        $query = "INSERT INTO rel_queja_der_gral (id_queja_date, id_cat_derecho_general) VALUES($id, $id_cat_derecho_general);";
        if ($db->query($query)) {
            //echo "Registro ingresado con éxito.";
        } else {
        }
        $query = "INSERT INTO rel_queja_der_vuln (id_queja_date, id_cat_der_vuln) VALUES($id, $id_cat_der_vuln);";
        if ($db->query($query)) {
        } else {
        }

        echo $query;

        insertAccion($user['id_user'], '\"' . $user['username'] . '\" actualizó los Derechos Presuntamente Violentados del expediene ' . $folio_editar . '.', 2);

        if ($fecha_acuerdo) {

            $resultado = str_replace("/", "-", $folio_editar);
            $carpeta = 'uploads/quejas/' . $resultado . '/Acuerdos';

            $name = $_FILES['acuerdo_adjunto']['name'];
            $temp = $_FILES['acuerdo_adjunto']['tmp_name'];
            $name_publico = $_FILES['acuerdo_adjunto_publico']['name'];
            $temp2 = $_FILES['acuerdo_adjunto_publico']['tmp_name'];

            if (is_dir($carpeta)) {
                $move = move_uploaded_file($temp, $carpeta . "/" . $name);
                $move2 = move_uploaded_file($temp2, $carpeta . "/" . $name_publico);
            } else {
                mkdir($carpeta, 0777, true);
                $move = move_uploaded_file($temp, $carpeta . "/" . $name);
                $move2 = move_uploaded_file($temp2, $carpeta . "/" . $name_publico);
            }

            $Procesal = find_by_id('cat_est_procesal', (int) $estado_procesal, 'id_cat_est_procesal');

            $estadoProcesal = $Procesal['descripcion'];


            $query = "INSERT INTO rel_queja_acuerdos ( id_queja_date, tipo_acuerdo,fecha_acuerdo,acuerdo_adjunto,acuerdo_adjunto_publico,sintesis_documento,publico,origen_acuerdo,user_creador,fecha_alta) 
            VALUES ({$id},'{$estadoProcesal}','{$fecha_acuerdo}','{$name}','{$name_publico}','{$sintesis_documento}',{$publico},'Procesal',{$id_user},NOW());";

            if ($db->query($query)) {
                //sucess
                $session->msg('s', " Los datos de los acuerdos se han sido agregado con éxito.");
                $sql = "UPDATE quejas_dates SET estado_procesal='{$estado_procesal}', fecha_actualizacion=NOW() WHERE id_queja_date='{$db->escape($id)}'";

                $result = $db->query($sql);
                if ($result) {
                    $session->msg('s', "Información Actualizada ");
                    insertAccion($user['id_user'], '\"' . $user['username'] . '\" agregó el acuerdo \"' . $estadoProcesal . '\" al expediente ' . $folio_editar . '.', 1);
                    insertAccion($user['id_user'], '\"' . $user['username'] . '\" actualizó el Estado procesal a \"' . $estadoProcesal . '\" al expediente ' . $folio_editar . '.', 2);
                    redirect('quejas.php', false);
                } else {
                    $session->msg('d', ' Lo siento no se actualizaron los datos.');
                    redirect('edit_queja.php?id=' . (int) $e_detalle['id'], false);
                }
            }
        } else {
            //faile
            $session->msg('d', ' No se pudieron agregar los datos de los acuerdos.');
            redirect('procesal_queja.php?id=' . (int) $e_detalle['id_queja_date'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('procesal_queja.php?id=' . (int) $e_detalle['id'], false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Queja
                    <?php echo $e_detalle['folio_queja']; ?>
                </span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="procesal_queja.php?id=<?php echo (int) $e_detalle['id_queja_date']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_aut">Autoridad Responsable</label>
                            <input type="text" class="form-control" name="id_cat_aut" value="<?php echo remove_junk($e_detalle['nombre_autoridad']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_quejoso">Nombre del Quejoso</label>
                            <input type="text" class="form-control" name="id_cat_quejoso" value="<?php echo remove_junk($e_detalle['nombre_quejoso'] . " " . $e_detalle['paterno_quejoso'] . " " . $e_detalle['materno_quejoso']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area_asignada">Área a la que se asignó la queja</label>
                            <input type="text" class="form-control" name="id_user_asignado" value="<?php foreach ($area as $a) {
                                                                                                        if ($a['id_area'] === $e_detalle['id_area_asignada'])
                                                                                                            echo $a['nombre_area'];
                                                                                                    } ?>" readonly>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_mun">Municipio</label>
                            <input type="text" class="form-control" name="id_cat_mun" value="<?php foreach ($cat_municipios as $municipio) {
                                                                                                    if ($municipio['id_cat_mun'] === $e_detalle['id_cat_mun'])
                                                                                                        echo ucwords($municipio['descripcion']);
                                                                                                } ?>" readonly>
                        </div>
                    </div>
                </div>

                <hr style="height: 1px; background-color: #370494; opacity: 1;">
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7263F0" width="25px" height="25px" viewBox="0 0 24 24" style="margin-top:-0.3%;">
                        <title>arrow-right-circle</title>
                        <path d="M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M6,13H14L10.5,16.5L11.92,17.92L17.84,12L11.92,6.08L10.5,7.5L14,11H6V13Z" />
                    </svg>
                    <span style="font-size: 20px; color: #7263F0">Derechos Presuntamente Violentados</span>
                </strong>
                <div class="row">


                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="derecho_general">Derecho general <span style="color:red; font-weight:bold;">*</span></label>
                            <select class="form-control" name="id_cat_derecho_general" id="id_cat_derecho_general" required>
                                <option value="">Seleccione el Derecho General</option>
                                <?php foreach ($cat_derecho_gral as $derecho_gral) : ?>
                                    <option <?php if ($derecho_gral['id_cat_derecho_general'] === $rel_queja_der_gral)
                                                echo 'selected="selected"'; ?> value="<?php echo $derecho_gral['id_cat_derecho_general']; ?>">
                                        <?php echo ucwords($derecho_gral['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="derecho_violentado">Derecho violentado <span style="color:red; font-weight:bold;">*</span></label>
                            <select class="form-control" name="id_cat_derecho_vuln" id="id_cat_derecho_vuln" required>
                                <option value="">Seleccione el Derecho Violentado</option>
                                <?php foreach ($cat_derecho_vuln as $derecho_vuln) : ?>
                                    <option <?php if ($derecho_vuln['id_cat_der_vuln'] === $rel_queja_der_vuln)
                                                echo 'selected="selected"'; ?> value="<?php echo $derecho_vuln['id_cat_der_vuln']; ?>">

                                        <?php echo ucwords($derecho_vuln['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <br><br><br><br>
                </div>

                <hr style="height: 1px; background-color: #370494; opacity: 1;">
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7263F0" width="25px" height="25px" viewBox="0 0 24 24" style="margin-top:-0.3%;">
                        <title>arrow-right-circle</title>
                        <path d="M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M6,13H14L10.5,16.5L11.92,17.92L17.84,12L11.92,6.08L10.5,7.5L14,11H6V13Z" />
                    </svg>
                    <span style="font-size: 20px; color: #7263F0">ESTADO PROCESAL DE LA QUEJA</span>
                </strong>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="estado_procesal">Estado Procesal</label>
                            <select class="form-control" name="estado_procesal" id="estado_procesal" required>
                                <option value="">Seleccione el Estado Procesal</option>
                                <?php foreach ($cat_est_procesal as $est_pros) : ?>
                                    <option <?php if ($est_pros['id_cat_est_procesal'] == $e_detalle['est_pro'])
                                                echo 'selected="selected"'; ?> value="<?php echo $est_pros['id_cat_est_procesal']; ?>">
                                        <?php echo ucwords($est_pros['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_tipo_resolucion">Fecha de Acuerdo</label>
                            <input type="date" class="form-control" name="fecha_acuerdo" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_tipo_resolucion">Documento de Acuerdo</label>
                            <input id="acuerdo_adjunto" type="file" accept="application/pdf" class="form-control" name="acuerdo_adjunto" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_tipo_resolucion">Documento de Acuerdo en Versión Pública</label>
                            <input id="acuerdo_adjunto_publico" type="file" accept="application/pdf" class="form-control" name="acuerdo_adjunto_publico" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sintesis_documento">Síntesis del documento</label>
                            <textarea class="form-control" name="sintesis_documento" id="sintesis_documento" cols="10" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="publico">¿El Acuerdo será público?</label><br>
                            <label class="switch" style="float:left;">
                                <div class="row">
                                    <input type="checkbox" id="publico" name="publico" checked>
                                    <span class="slider round"></span>
                                    <div>
                                        <p style="margin-left: 150%; margin-top: -3%; font-size: 14px;">No/Sí</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                </div>
                <div class="form-group clearfix">
                    <a href="quejas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="procesal_queja" class="btn btn-primary" value="subir">Guardar</button>
                </div>
        </div>
        </form>
    </div>
</div>
</div>
<script>
</script>
<?php include_once('layouts/footer.php'); ?>