<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Capacitación';
require_once('includes/load.php');

$id_table = last_id_table('capacitaciones', 'id_capacitacion');
$id_folio = last_id_folios();
$areas_all = find_all('area');
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

$area_user = area_usuario2($id_user);
$area = $area_user['id_area'];
$area_informe = isset($_GET['a']) ? $_GET['a'] : '1';
$grupos_vuln = find_all('cat_grupos_vuln');

$inticadores_pat = find_all_pat_area($area_informe, 'capacitaciones');
$cat_publico_objetivo = find_all('cat_publico_objetivo');
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_capacitacion'])) {

    $req_fields = array('nombre_capacitacion', 'tipo_evento', 'quien_solicita', 'fecha', 'hora', 'lugar',  'modalidad', 'capacitador');
    validate_fields($req_fields);

    if (empty($errors)) {
        $nombre   = remove_junk($db->escape($_POST['nombre_capacitacion']));
        $solicita   = remove_junk($db->escape($_POST['quien_solicita']));
        $tipo_capacitacion   = remove_junk($db->escape($_POST['tipo_capacitacion']));
        $tipo_evento   = remove_junk($db->escape($_POST['tipo_evento']));
        $fecha   = remove_junk($db->escape($_POST['fecha']));
        $hora   = remove_junk($db->escape($_POST['hora']));
        $lugar   = remove_junk(($db->escape($_POST['lugar'])));
        $duracion   = remove_junk(($db->escape($_POST['duracion'])));
        $id_cat_mun   = remove_junk(($db->escape($_POST['id_cat_mun'])));
        $asistentes_otros   = remove_junk(($db->escape($_POST['asistentes_otros'])));
        $asistentes_nobinario   = remove_junk(($db->escape($_POST['asistentes_nobinario'])));
        $asistentes_mujeres   = remove_junk(($db->escape($_POST['asistentes_mujeres'])));
        $asistentes_hombres   = remove_junk(($db->escape($_POST['asistentes_hombres'])));
        $asistentes_10   = remove_junk(($db->escape($_POST['asistentes_10'])));
        $asistentes_20   = remove_junk(($db->escape($_POST['asistentes_20'])));
        $asistentes_30   = remove_junk(($db->escape($_POST['asistentes_30'])));
        $asistentes_40   = remove_junk(($db->escape($_POST['asistentes_40'])));
        $asistentes_50   = remove_junk(($db->escape($_POST['asistentes_50'])));
        $asistentes_60   = remove_junk(($db->escape($_POST['asistentes_60'])));
        $asistentes_70   = remove_junk(($db->escape($_POST['asistentes_70'])));
        $asistentes_80   = remove_junk(($db->escape($_POST['asistentes_80'])));
        $modalidad   = remove_junk($db->escape($_POST['modalidad']));
        // $depto   = remove_junk($db->escape($_POST['id_area']));
        $capacitador   = remove_junk($db->escape($_POST['capacitador']));
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));

        $asistentes = $_POST['asistentes'];
        $id_cat_grupo_vuln = $_POST['id_cat_grupo_vuln'];

        $total_asistentes = (int)$asistentes_otros + (int)$asistentes_nobinario + (int)$asistentes_mujeres + (int)$asistentes_hombres;
        $total_edades = (int)$asistentes_10 + (int)$asistentes_20 + (int)$asistentes_30 + (int)$asistentes_40 +
            (int)$asistentes_50 + (int)$asistentes_60 + (int)$asistentes_70 + (int)$asistentes_80;


        if ($total_asistentes == $total_edades) {
            if (count($id_table) == 0) {
                $nuevo_id_ori_canal = 1;
                $no_folio = sprintf('%04d', 1);
            } else {
                foreach ($id_table as $nuevo) {
                    $nuevo_id_ori_canal = (int) $nuevo['id_capacitacion'] + 1;
                    $no_folio = sprintf('%04d', (int) $nuevo['id_capacitacion'] + 1);
                }
            }

            if (count($id_folio) == 0) {
                $nuevo_id_folio = 1;
                $no_folio = sprintf('%04d', 1);
            } else {
                foreach ($id_folio as $nuevo) {
                    $nuevo_id_folio = (int) $nuevo['contador'] + 1;
                    $no_folio = sprintf('%04d', (int) $nuevo['contador'] + 1);
                }
            }

            $year = date("Y");
            $folio = 'CEDH/' . $no_folio . '/' . $year . '-CAP';


            $dbh = new PDO('mysql:host=localhost;dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');

            $query = "INSERT INTO capacitaciones (";
            $query .= "nombre_capacitacion,tipo_capacitacion,tipo_evento,quien_solicita,fecha,hora,lugar,no_asistentes,duracion,id_cat_mun,
							asistentes_otros,asistentes_nobinario,asistentes_mujeres,asistentes_hombres,
							asistentes_10,asistentes_20,asistentes_30,asistentes_40,
							asistentes_50,asistentes_60,asistentes_70,asistentes_80,
							modalidad,id_area,capacitador,folio,area_creacion,user_creador";
            if ($inticadores_pat) {
                $query .= ",id_indicadores_pat ";
            }
            $query .= ") VALUES (";
            $query .= " '{$nombre}','{$tipo_capacitacion}','{$tipo_evento}','{$solicita}','{$fecha}','{$hora}','{$lugar}',{$total_asistentes},{$duracion},{$id_cat_mun},
						'{$asistentes_otros}','{$asistentes_nobinario}','{$asistentes_mujeres}','{$asistentes_hombres}',
						'{$asistentes_10}','{$asistentes_20}','{$asistentes_30}','{$asistentes_40}',
						'{$asistentes_50}','{$asistentes_60}','{$asistentes_70}','{$asistentes_80}',
						'{$modalidad}','{$area_informe}','{$capacitador}','{$folio}','{$area_informe}','{$id_user}'";
            if ($inticadores_pat) {
                $query .= ",{$id_indicadores_pat} ";
            }
            $query .= ")";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio}'";
            $query2 .= ")";

            $dbh->exec($query);
            if ($db->query($query2)) {
                $id_capacitacion = $dbh->lastInsertId();

                if ($id_capacitacion > 0) {
                    for ($i = 0; $i < sizeof($asistentes); $i = $i + 1) {
                        if ($id_cat_grupo_vuln[$i] !== '' && $id_cat_grupo_vuln[$i] > 0) {
                            $queryInsert4 = "INSERT INTO rel_capacitacion_grupos (id_capacitacion,id_cat_grupo_vuln,no_asistentes) VALUES('$id_capacitacion','$id_cat_grupo_vuln[$i]','$asistentes[$i]')";
                            $db->query($queryInsert4);
                        }
                    }
                    //sucess
                    $session->msg('s', " La capacitación ha sido agregada con éxito.");
                    insertAccion($user['id_user'], '"' . $user['username'] . '" agregó capacitación, Folio: ' . $folio . '.', 1);
                    redirect('capacitaciones.php?a=' . $area_informe, false);
                } else {
                    $session->msg('d', ' No se pudo agregar la capacitación,debido a que no se genero ID de la misma' . $query);
                    redirect('add_capacitacion.php?a=' . $area_informe, false);
                }
            } else {
                //failed
                $session->msg('d', ' No se pudo agregar la capacitación.');
                redirect('add_capacitacion.php?a=' . $area_informe, false);
            }
        } else {
            $session->msg("d", "Lo Sentimos, la información no pudo ser dada de alta debido a que la suma de Asistentes no es igual que la Suma de los Rangos de edades. Por favor intentelo nuevamente");
            redirect('add_capacitacion.php?a=' . $area_informe, false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_capacitacion.php?a=' . $area_informe, false);
    }
}
?>

<script type="text/javascript">
    $(document).ready(function() {


        $("#addRow").click(function() {
            var html = '';
            html += '<div id="inputFormRow">';
            html += '	<div class="col-md-4">';
            html += '		 <input type="number"  class="form-control" max="1000" name="asistentes[]" > ';
            html += '	</div>';
            html += '	<div class="col-md-4">';
            html += '		<select class="form-control" name="id_cat_grupo_vuln[]">';
            html += '                <option value="">Escoge una opción</option>';
            <?php foreach ($grupos_vuln as $grupo_vuln) : ?>
                html += '                   <option value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>';
            <?php endforeach; ?>
            html += '            </select>';
            html += '	</div>';
            html += '	<div class="col-md-2">';
            html += '	<button type="button" class="btn btn-outline-danger" id="removeRow" > ';
            html += '   	<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">';
            html += '			<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>';
            html += '			<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>';
            html += '		</svg>';
            html += '  	</button>';
            html += '	</div> <br><br>';
            html += '</div> ';

            $('#newRow').append(html);
        });


        $(document).on('click', '#removeRow', function() {
            $(this).closest('#inputFormRow').remove();
        });

    });
</script>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar capacitación</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" name="add_capacitacion" id="add_capacitacion" action="add_capacitacion.php?a=<?php echo $area_informe ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="nombre_capacitacion">Tipo de Divulgación</label>
                            <select class="form-control" name="tipo_capacitacion" required>
                                <option value="">Escoge una opción</option>
                                <option value="Impartida">Impartida</option>
                                <option value="Tomada">Tomada</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_capacitacion">Nombre de la capacitación</label>
                            <input type="text" class="form-control" name="nombre_capacitacion" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_evento">Tipo de evento</label>
                            <select class="form-control" name="tipo_evento" required>
                                <option value="">Escoge una opción</option>
                                <option value="Capacitación">Capacitación</option>
                                <option value="Conferencia">Conferencia</option>
                                <option value="Curso">Curso</option>
                                <option value="Divulgación">Divulgación</option>
                                <option value="Taller">Taller</option>
                                <option value="Plática">Plática</option>
                                <option value="Diplomado">Diplomado</option>
                                <option value="Foro">Foro</option>
                                <option value="Conversatorios">Conversatorios</option>
                                <option value="Congreso">Congreso</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="modalidad">Modalidad</label>
                            <select class="form-control" name="modalidad" required>
                                <option value="">Escoge una opción</option>
                                <option value="Presencial">Presencial</option>
                                <option value="En línea">En línea</option>
                                <option value="Híbrido">Híbrido</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="quien_solicita">¿Quién lo solicita?</label>
                            <input type="text" class="form-control" name="quien_solicita" placeholder="Nombre Completo" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha</label><br>
                            <input type="date" class="form-control" name="fecha">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="hora">Hora</label><br>
                            <input type="time" class="form-control" name="hora">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="duracion">Duración(Hrs)</label>
                            <input type="number" class="form-control" name="duracion" value="0" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="lugar">Lugar</label>
                            <input type="text" class="form-control" name="lugar" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="municipio">Municipio</label>
                            <select class="form-control" name="id_cat_mun">
                                <option value="0">Escoge una opción</option>
                                <?php foreach ($cat_municipios as $municipio) : ?>
                                    <option value="<?php echo $municipio['id_cat_mun']; ?>"><?php
                                                                                            echo ucwords($municipio['descripcion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="capacitador">Capacitador</label>
                            <input type="text" class="form-control" name="capacitador" placeholder="Nombre Completo" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="id_cat_publico_objetivo">Público Objetivo</label>
                        <div class="form-group">
                            <select class="form-control" name="id_cat_publico_objetivo">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_publico_objetivo as $datos) : ?>
                                    <option value="<?php echo $datos['id_cat_publico_objetivo']; ?>"><?php echo ($datos['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>
                    <?php if ($inticadores_pat) { ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_indicadores_pat">Definición del Indicador</label>
                                <select class="form-control form-select" name="id_indicadores_pat" required>
                                    <option value="">Selecciona Indicador</option>
                                    <?php foreach ($inticadores_pat as $datos) : ?>
                                        <option value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="row">
                    <h3 style="font-weight:bold;">
                        <span class="material-symbols-outlined">checklist</span>
                        Asistentes
                    </h3>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">Hombres</label>
                            <input type="number" class="form-control" max="10000" name="asistentes_hombres" id="asistentes_hombres" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">Mujeres</label>
                            <input type="number" class="form-control" max="10000" name="asistentes_mujeres" id="asistentes_mujeres" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">No Binarios</label>
                            <input type="number" class="form-control" max="10000" name="asistentes_nobinario" id="asistentes_nobinario" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">Otros</label>
                            <input type="number" class="form-control" max="10000" name="asistentes_otros" id="asistentes_otros" value="0">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">De 0 a 11 años</label>
                            <input type="number" class="form-control" max="10000" name="asistentes_10" id="asistentes_10" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">De 12 a 17 años</label>
                            <input type="number" class="form-control" max="10000" name="asistentes_20" id="asistentes_20" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">De 18 a 30 años</label>
                            <input type="number" class="form-control" max="10000" name="asistentes_30" id="asistentes_30" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">De 31 a 40 años</label>
                            <input type="number" class="form-control" max="10000" name="asistentes_40" id="asistentes_40" value="0">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="no_asistentes">De 41 a 50 años</label>
                                <input type="number" class="form-control" max="10000" name="asistentes_50" id="asistentes_50" value="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="no_asistentes">De 51 a 60 años</label>
                                <input type="number" class="form-control" max="10000" name="asistentes_60" id="asistentes_60" value="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="no_asistentes">60 o más</label>
                                <input type="number" class="form-control" max="10000" name="asistentes_70" id="asistentes_70" value="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="no_asistentes">Sin Dato</label>
                                <input type="number" class="form-control" max="10000" name="asistentes_80" id="asistentes_80" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <h3 style="font-weight:bold;">
                            <span class="material-symbols-outlined">checklist</span>
                            Grupos Vulnerables
                        </h3>
                        <div id="inputFormRow">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="no_informe">No. Asistentes</label>
                                    <input type="number" class="form-control" max="10000" name="asistentes[]">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="adjunto">Grupo Vulnerable</label>
                                    <select class="form-control" name="id_cat_grupo_vuln[]">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($grupos_vuln as $grupo_vuln) : ?>
                                            <option value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button type="button" class="btn btn-success" id="addRow" name="addRow">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
                                            <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
                                            <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
                                        </svg>
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="newRow">
                    </div>

                    <div class="form-group clearfix">
                        <a href="capacitaciones.php?a=<?php echo $area_informe ?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="sumbit" name="add_capacitacion" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>