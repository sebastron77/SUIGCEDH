<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Ficha Técnica - Área Psicológica';
require_once('includes/load.php');
$id_folio = last_id_folios();
$user = current_user();
page_require_level(4);
$id_user = $user['id_user'];
$busca_area = area_usuario($id_user);
$otro = $busca_area['id_area'];
$areas = find_all_area_orden('area');
$funciones = find_all_funcion_P();
$ocupaciones = find_all_order('cat_ocupaciones', 'descripcion');
$escolaridades = find_all('cat_escolaridad');
$visitadurias = find_all_visitadurias();
$autoridades = find_all_aut_res();
$generos = find_all('cat_genero');
$grupos = find_all_order('cat_grupos_vuln', 'id_cat_grupo_vuln');
$derechos_vuln = find_all_order('cat_der_vuln', 'descripcion');
?>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_ficha_psic'])) {

    $req_fields = array('funcion', 'area_solicitante', 'visitaduria', 'ocupacion', 'escolaridad', 'hechos', 'autoridad', 'nombre_usuario', 'edad', 'sexo', 'grupo_vulnerable', 'fecha_intervencion', 'resultado', 'documento_emitido', 'nombre_especialista', 'clave_documento');
    validate_fields($req_fields);

    if (empty($errors)) {
        $funcion   = remove_junk($db->escape($_POST['funcion']));
        $num_queja   = remove_junk($db->escape($_POST['num_queja']));
        $visitaduria   = remove_junk($db->escape($_POST['visitaduria']));
        $area_solicitante   = remove_junk($db->escape($_POST['area_solicitante']));
        $ocupacion   = remove_junk(($db->escape($_POST['ocupacion'])));
        $escolaridad   = remove_junk(($db->escape($_POST['escolaridad'])));
        $hechos   = remove_junk(($db->escape($_POST['hechos'])));
        $autoridad   = remove_junk(($db->escape($_POST['autoridad'])));
        $nombre_usuario   = remove_junk($db->escape($_POST['nombre_usuario']));
        $edad   = remove_junk($db->escape($_POST['edad']));
        $sexo   = remove_junk($db->escape($_POST['sexo']));
        $grupo_vulnerable   = remove_junk($db->escape($_POST['grupo_vulnerable']));
        $fecha_intervencion   = remove_junk($db->escape($_POST['fecha_intervencion']));
        $resultado   = remove_junk($db->escape($_POST['resultado']));
        $documento_emitido   = remove_junk($db->escape($_POST['documento_emitido']));
        $adjunto   = remove_junk($db->escape($_POST['adjunto']));
        $protocolo_estambul   = remove_junk($db->escape($_POST['protocolo_estambul']));
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
        $carpeta = 'uploads/fichastecnicas/psic/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['adjunto']['name'];
        $size = $_FILES['adjunto']['size'];
        $type = $_FILES['adjunto']['type'];
        $temp = $_FILES['adjunto']['tmp_name'];

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        if ($move && $name != '') {
            $query = "INSERT INTO fichas (";
            $query .= "folio,id_cat_funcion,num_queja,id_visitaduria,id_area_solicitante,id_cat_ocup,id_cat_escolaridad,id_cat_der_vuln,id_cat_aut,nombre_usuario,edad,id_cat_gen,id_cat_grupo_vuln,fecha_intervencion,resultado,documento_emitido,ficha_adjunto,fecha_creacion,protocolo_estambul,nombre_especialista,clave_documento,tipo_ficha,quien_creo";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$funcion}','{$num_queja}','{$visitaduria}','{$area_solicitante}','{$ocupacion}','{$escolaridad}','{$hechos}','{$autoridad}','{$nombre_usuario}','{$edad}','{$sexo}','{$grupo_vulnerable}','{$fecha_intervencion}','{$resultado}','{$documento_emitido}','{$name}','{$creacion}','{$protocolo_estambul}','{$nombre_especialista}','{$clave_documento}',2,'{$id_user}'";
            $query .= ")";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";
        } else {
            $query = "INSERT INTO fichas (";
            $query .= "folio,id_cat_funcion,num_queja,id_visitaduria,id_area_solicitante,id_cat_ocup,id_cat_escolaridad,id_cat_der_vuln,id_cat_aut,nombre_usuario,edad,id_cat_gen,id_cat_grupo_vuln,fecha_intervencion,resultado,documento_emitido,fecha_creacion,protocolo_estambul,nombre_especialista,clave_documento,tipo_ficha,quien_creo";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$funcion}','{$num_queja}','{$visitaduria}','{$area_solicitante}','{$ocupacion}','{$escolaridad}','{$hechos}','{$autoridad}','{$nombre_usuario}','{$edad}','{$sexo}','{$grupo_vulnerable}','{$fecha_intervencion}','{$resultado}','{$documento_emitido}','{$creacion}','{$protocolo_estambul}','{$nombre_especialista}','{$clave_documento}',2,'{$id_user}'";
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
            redirect('fichas_psic.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la ficha.');
            redirect('add_ficha_psic.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_ficha_psic.php', false);
    }
}
?>
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Ficha Técnica - Área Psicológica</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_ficha_psic.php" enctype="multipart/form-data">
                <div class="row">
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
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="num_queja">No. de Queja</label>
                            <input type="text" class="form-control" name="num_queja">
                        </div>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ocupacion">Ocupacion</label>
                            <select class="form-control" name="ocupacion">
                                <option>Escoge una opción</option>
                                <?php foreach ($ocupaciones as $ocupacion) : ?>
                                    <option value="<?php echo $ocupacion['id_cat_ocup']; ?>"><?php echo ucwords($ocupacion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="escolaridad">Escolaridad</label>
                            <select class="form-control" name="escolaridad">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($escolaridades as $estudios) : ?>
                                    <option value="<?php echo $estudios['id_cat_escolaridad']; ?>"><?php echo ucwords($estudios['descripcion']); ?></option>
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="hechos">Presuntos hechos violatorios</label>
                            <select class="form-control" name="hechos">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($derechos_vuln as $derecho_vuln) : ?>
                                    <option value="<?php echo $derecho_vuln['id_cat_der_vuln']; ?>"><?php echo ucwords($derecho_vuln['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="autoridad">Autoridad señalada</label>
                            <select class="form-control" name="autoridad">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($autoridades as $autoridad) : ?>
                                    <option value="<?php echo $autoridad['id_cat_aut']; ?>"><?php echo ucwords($autoridad['nombre_autoridad']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_usuario">Nombre del usuario</label>
                            <input type="text" class="form-control" name="nombre_usuario" placeholder="Nombre Completo" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="edad">Edad</label>
                            <input type="number" class="form-control" min="1" max="120" name="edad" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Género</label>
                            <select class="form-control" name="sexo">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($generos as $genero) : ?>
                                    <option value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="grupo_vulnerable">Grupo Vulnerable</label>
                            <select class="form-control" name="grupo_vulnerable">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($grupos as $grupo) : ?>
                                    <option value="<?php echo $grupo['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo['descripcion']); ?></option>
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="protocolo_estambul">Protocolo de Estambul</label>
                            <select class="form-control" name="protocolo_estambul">
                                <option value="">Escoge una opción</option>
                                <option value="Aplicado">Aplicado</option>
                                <option value="No Aplicado">No Aplicado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="documento_emitido">Documento Emititdo</label>
                            <select class="form-control" name="documento_emitido">
                                <option value="">Escoge una opción</option>
                                <option value="Dictamen psicológico">Dictamen psicológico</option>
                                <option value="Informe psicológico">Informe psicológico</option>
                                <option value="No Aplica">No Aplica</option>
                            </select>
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
                            <label for="nombre_usuario">Clave del documento</label>
                            <input type="text" class="form-control" name="clave_documento" placeholder="Insertar la clave del documento">
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
                    <a href="fichas_psic.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_ficha_psic" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>