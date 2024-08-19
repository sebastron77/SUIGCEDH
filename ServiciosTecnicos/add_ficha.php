<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Ficha Técnica - Área Médica';
error_reporting(E_ALL ^ E_NOTICE);
require_once('includes/load.php');
$user = current_user();
$detalle = $user['id_user'];
$id_folio = last_id_folios();
$pacientes = find_all_pacientes();
// page_require_level(4);
$id_user = $user['id_user'];
$busca_area = area_usuario($id_user);
$areas = find_all_area_orden('area');
$funciones = find_all_funcion_M();
$ocupaciones = find_all_order('cat_ocupaciones', 'descripcion');
$escolaridades = find_all('cat_escolaridad');
$visitadurias = find_all_visitadurias();
$autoridades = find_all_aut_res();
$generos = find_all('cat_genero');
$grupos = find_all_order('cat_grupos_vuln', 'id_cat_grupo_vuln');
$derechos_vuln = find_all_order('cat_der_vuln', 'descripcion');
$folios = find_all('folios');
// page_require_area(4);

if (isset($_GET['num_queja'])) {
    $opcion = $_GET['num_queja'];
    // echo $opcion;
    $prueba1 = find_by_id_paciente2($opcion);
}
?>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_ficha'])) {


    if (empty($errors)) {
        $funcion   = remove_junk($db->escape($_POST['funcion']));
        $num_queja   = remove_junk($db->escape($_POST['num_queja']));
        $visitaduria   = remove_junk($db->escape($_POST['visitaduria']));
        $area_solicitante   = remove_junk($db->escape($_POST['area_solicitante']));
        $derechos_vuln   = remove_junk(($db->escape($_POST['derechos_vuln'])));
        $fecha_intervencion   = remove_junk($db->escape($_POST['fecha_intervencion']));
        $resultado   = remove_junk($db->escape($_POST['resultado']));
        $documento_emitido   = remove_junk($db->escape($_POST['documento_emitido']));
        $nombre_especialista   = remove_junk($db->escape($_POST['nombre_especialista']));
        $clave_documento   = remove_junk($db->escape($_POST['clave_documento']));
        date_default_timezone_set('America/Mexico_City');
        $creacion = date('Y-m-d');

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int)$nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int)$nuevo['contador'] + 1);
            }
        }
        // Se crea el número de folio
        $year = date("Y");
        // Se crea el folio de convenio
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-FT';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-FT';
        $carpeta = 'uploads/fichastecnicas/medica/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['adjunto']['name'];
        $size = $_FILES['adjunto']['size'];
        $type = $_FILES['adjunto']['type'];
        $temp = $_FILES['adjunto']['tmp_name'];

        $ocup = $prueba1['id_ocupacion'];

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        if ($move && $name != '') {
            $query = "INSERT INTO fichas (";
            $query .= "folio,id_cat_funcion,num_queja,id_visitaduria,id_area_solicitante,id_cat_der_vuln,fecha_intervencion,resultado,documento_emitido,ficha_adjunto,fecha_creacion,tipo_ficha,nombre_especialista,clave_documento,quien_creo";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$funcion}','{$num_queja}','{$visitaduria}','{$area_solicitante}','{$derechos_vuln}','{$fecha_intervencion}','{$resultado}','{$documento_emitido}','{$name}','{$creacion}',1,'{$nombre_especialista}','{$clave_documento}','{$id_user}'";
            $query .= ")";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";
        } else {
            $query = "INSERT INTO fichas (";
            $query .= "folio,id_cat_funcion,num_queja,id_visitaduria,id_area_solicitante,id_cat_der_vuln,fecha_intervencion,resultado,documento_emitido,fecha_creacion,tipo_ficha,nombre_especialista,clave_documento,quien_creo";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$funcion}','{$num_queja}','{$visitaduria}','{$area_solicitante}','{$derechos_vuln}','{$fecha_intervencion}','{$resultado}','{$documento_emitido}','{$creacion}',1,'{$nombre_especialista}','{$clave_documento}','{$id_user}'";
            $query .= ")";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";
        }
        if ($db->query($query) && $db->query($query2)) {
            //sucess
            $session->msg('s', " La ficha ha sido agregada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó Ficha Médica, Folio: ' . $folio . '.', 1);
            redirect('fichas.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la ficha.');
            redirect('add_ficha.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_ficha.php', false);
    }
}
?>
<script type="text/javascript">
    function buscar() {
        var opcion = document.getElementById('num_queja').value;
        window.location.href = 'http://localhost/sistemageneralquejas/administrador/add_ficha.php?num_queja=' + opcion;
    }
</script>

<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<?php

?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Ficha Técnica - Área Médica</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_ficha.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="num_queja">Expediente</label>
                            <select class="form-control" name="num_queja" id="num_queja" onchange="return buscar();">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($pacientes as $paciente) : ?>
                                    <option value="<?php echo $paciente['folio_expediente']; ?>"><?php echo ucwords($paciente['folio']); ?></option>
                                    <?php if ($paciente['folio_expediente'] == $_GET['num_queja']) : ?>
                                        <option style="visibility: hidden; font-size:1%;" value="<?php echo $paciente['folio_expediente']; ?>" selected><?php echo ucwords($paciente['folio']); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="funcion">Función</label>
                            <select class="form-control" name="funcion">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($funciones as $funcion) : ?>
                                    <option value="<?php echo $funcion['id_cat_funcion']; ?>"><?php echo ucwords($funcion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="area_solicitante">Área Solicitante</label>
                            <select class="form-control" name="area_solicitante">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($areas as $area) : ?>
                                    <option value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="visitaduria">Visitaduria</label>
                            <select class="form-control" name="visitaduria">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($visitadurias as $visitaduria) : ?>
                                    <option value="<?php echo $visitaduria['id_area']; ?>"><?php echo ucwords($visitaduria['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_usuario">Nombre del usuario</label>
                            <input type="text" class="form-control" value="<?php echo $prueba1['nombre'] . " " . $prueba1['paterno'] . " " . $prueba1['materno'] ?>" readonly>
                            <input type="text" class="form-control" name="nombre_usuario" placeholder="Nombre Completo" value="<?php echo $prueba1['id_paciente']?>" hidden>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="edad">Edad</label>
                            <input type="number" class="form-control" min="1" max="120" name="edad" value="<?php echo $prueba1['edad'] ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sexo">Género</label>
                            <input type="text" class="form-control" value="<?php echo $prueba1['genero'] ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ocupacion">Ocupación</label>
                            <input type="text" class="form-control" value="<?php echo $prueba1['ocupacion'] ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="escolaridad">Escolaridad</label>
                            <input type="text" class="form-control" value="<?php echo $prueba1['escolaridad'] ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="grupo_vulnerable">Grupo Vulnerable</label>
                            <input type="text" class="form-control" value="<?php echo $prueba1['grupo_vulnerable'] ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="autoridad_responsable">Autoridad señalada</label>
                            <input type="text" class="form-control" value="<?php echo $prueba1['nombre_autoridad'] ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="derechos_vuln">Presunto Derecho Vulnerado</label>
                            <select class="form-control" name="derechos_vuln">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($derechos_vuln as $derecho_vuln) : ?>
                                    <option value="<?php echo $derecho_vuln['id_cat_der_vuln']; ?>"><?php echo ucwords($derecho_vuln['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_intervencion">Fecha de Intervención</label>
                            <input type="date" class="form-control" name="fecha_intervencion" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="resultado">Resultado</label>
                            <select class="form-control" name="resultado">
                                <option value="">Escoge una opción</option>
                                <option value="Positivo">Positivo</option>
                                <option value="Negativo">Negativo</option>
                                <option value="No Aplica">No Aplica</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="documento_emitido">Documento Emitido</label>
                            <select class="form-control" name="documento_emitido">
                                <option value="">Escoge una opción</option>
                                <option value="Certificado Médico">Certificado Médico</option>
                                <option value="Opinión Médica">Opinión Médica</option>
                                <option value="No Aplica">No Aplica</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="clave_documento">Clave del documento</label>
                            <input type="text" class="form-control" name="clave_documento" placeholder="Clave de documento">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_usuario">Especialista que emite</label>
                            <input type="text" class="form-control" name="nombre_especialista" placeholder="Nombre Completo del especialista" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="adjunto">Adjuntar documento emitido</label>
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto">
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="fichas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_ficha" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>