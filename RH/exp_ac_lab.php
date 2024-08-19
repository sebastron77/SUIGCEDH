<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Editar Expediente Académico';
require_once('includes/load.php');

page_require_level(1);
$areas = find_all_area_orden('area');
$tipo_int = find_all('cat_tipo_integrante');
$escolaridades = find_all('cat_escolaridad');
$area_con = find_all('cat_area_conocimiento');
$idP =  (int)$_GET['id'];
$rel_currs = find_all_curr($idP);
$rel_arCons = find_all_arCon($idP);
?>
<?php
$e_detalle = find_by_id('detalles_usuario', $idP, 'id_det_usuario');
$e_detalle2 = find_by_id("rel_curriculum_academico", $idP, 'id_rel_detalle_usuario');
$e_detalle3 = find_by_id("rel_area_conocimiento", (int)$_GET['id'], 'id_detalle_usuario');
if (!$e_detalle) {
    $session->msg("d", "id de usuario no encontrado.");
    redirect('detalles_usuario.php');
}
$user = current_user();
$nivel = $user['user_level'];
?>

<?php
if (isset($_POST['exp_ac_lab'])) {
    // if (empty($e_detalle2['id_rel_cur_acad']) || empty($e_detalle3['id_rel_area_con'])) {

        // if (empty($e_detalle2['id_rel_cur_acad'])) {
            $comprobatorios = array();

            $carpeta = 'uploads/personal/expediente/' . $e_detalle['id_det_usuario'];

            if (!is_dir($carpeta)) {
                mkdir($carpeta, 0777, true);
            } else {
                $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
            }


            //se obtienen los nom,bre de archivos 		
            foreach ($_FILES["archivo_comprobatorio"]['name'] as $key => $tmp_name) {
                //condicional si el fuchero existe
                if ($_FILES["archivo_comprobatorio"]["name"][$key]) {
                    // Nombres de archivos de temporales
                    $archivonombre = $_FILES["archivo_comprobatorio"]["name"][$key];
                    $fuente = $_FILES["archivo_comprobatorio"]["tmp_name"][$key];
                    array_push($comprobatorios, $archivonombre);

                    if (!file_exists($carpeta)) {
                        mkdir($carpeta, 0777) or die("Hubo un error al crear el directorio de almacenamiento");
                    }

                    $dir = opendir($carpeta);
                    $target_path = $carpeta . '/' . $archivonombre; //indicamos la ruta de destino de los archivos


                    if (move_uploaded_file($fuente, $target_path)) {
                        //echo "Los archivos $archivonombre se han cargado de forma correcta.<br>";
                    } else {
                        //echo "Se ha producido un error, por favor revise los archivos e intentelo de nuevo.<br>";
                    }
                    closedir($dir); //Cerramos la conexion con la carpeta destino
                }
            }

            $estudios = $_POST['estudios'];
            $institucion = $_POST['institucion'];
            $grado = $_POST['grado'];
            $cedula_profesional = $_POST['cedula_profesional'];
            $observaciones = $_POST['observaciones'];
            $texto = "";

            for ($i = 0; $i < sizeof($estudios); $i = $i + 1) {
                $query = "INSERT INTO rel_curriculum_academico (";
                $query .= "id_rel_detalle_usuario,estudios,institucion,grado,cedula_profesional,observaciones,archivo_comprobatorio";
                $query .= ") VALUES (";
                $query .= " '{$idP}','{$estudios[$i]}','{$institucion[$i]}','{$grado[$i]}','{$cedula_profesional[$i]}','{$observaciones[$i]}','$colaboracion[$i]' ";
                $query .= ")";
                $texto = $texto . $query;
                $db->query($query);
            }
        // }

        // if (empty($e_detalle3['id_rel_area_con'])) {
            $tipo_area = $_POST['tipo_area'];
            $nombre_carrera = $_POST['nombre_carrera'];
            $especialidad = $_POST['especialidad'];
            $texto2 = "";
            for ($i = 0; $i < sizeof($tipo_area); $i = $i + 1) {
                //query
                $query2 = "INSERT INTO rel_area_conocimiento (";
                $query2 .= "id_detalle_usuario,tipo_area,nombre_carrera,especialidad";
                $query2 .= ") VALUES (";
                $query2 .= " '{$idP}','{$tipo_area[$i]}','{$nombre_carrera[$i]}','{$especialidad[$i]}'";
                $query2 .= ")";
                $texto2 = $texto2 . $query2;
                $db->query($query2);
            }
        // }
        redirect('exp_ac_lab.php?id=' . $idP, false);
    // }
}
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#addRow").click(function() {
            var html = '<hr style="margin-top: -1%; margin-left: 1%; width: 96.5%; border-width: 3px; border-color: #7263f0; opacity: 1"></hr>';

            html += '<div id="inputFormRow" style="margin-top: 4%;">';
            html += '   <div style="margin-bottom: 1%; margin-top: -3%">';
            html += '	    <div class="col-md-5" style="margin-left: -15px; margin-top: 1px;">';
            html += '           <span class="material-symbols-rounded" style="margin-top: 1%; color: #3a3d44;">school</span>';
            html += '           <p style="font-size: 15px; font-weight: bold; margin-top: -22px; margin-left: 11%">EXPEDIENTE ACADÉMICO</p>';
            html += '       </div>';
            html += '	    <div class="col-md-2" style="margin-left: -5%; margin-top: -1px;">';
            html += '	        <button type="button" class="btn btn-outline-danger" id="removeRow" style="width: 50px; height: 30px"> ';
            html += '       	    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">';
            html += '	    		<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5  0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>';
            html += '	    		<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>';
            html += '	    	    </svg>';
            html += '  	        </button>';
            html += '	    </div> <br><br>';
            html += '   </div>';
            html += '   <div class="row">';
            html += '      <div class="col-md-4">';
            html += '          <div class="form-group">';
            html += '              <label for="estudios">Estudios</label>';
            html += '                  <input type="text" class="form-control" name="estudios[]" id="estudios">';
            html += '          </div>';
            html += '      </div>';
            html += '      <div class="col-md-4">';
            html += '          <div class="form-group">';
            html += '              <label for="institucion">Institución</label>';
            html += '              <input type="text" class="form-control" name="institucion[]" id="institucion">';
            html += '          </div>';
            html += '      </div>';
            html += '      <div class="col-md-4">';
            html += '          <div class="form-group">';
            html += '              <label for="grado">Grado</label>';
            html += '              <select class="form-control" name="grado[]" id="grado">';
            html += '                  <option value="">Escoge una opción</option>';
            html += '                  <?php foreach ($escolaridades as $escolaridad) : ?>';
            html += '                  <option value="<?php echo $escolaridad['id_cat_escolaridad']; ?>"><?php echo ucwords($escolaridad['descripcion']) ?></option>';
            html += '                  <?php endforeach; ?>';
            html += '              </select>';
            html += '          </div>';
            html += '      </div>';
            html += '   </div>';
            html += '   <div class="row">';
            html += '      <div class="col-md-4">';
            html += '          <div class="form-group">';
            html += '              <label for="cedula_profesional">Cédula Profesional</label>';
            html += '              <input type="text" class="form-control" name="cedula_profesional[]" id="cedula_profesional">';
            html += '          </div>';
            html += '      </div>';
            html += '       <div class="col-md-4">';
            html += '           <label for="archivo_comprobatorio">Cédula (Archivo)</label>';
            []
            html += '           <input type="file" accept="application/pdf" class="form-control" name="archivo_comprobatorio[]" id="archivo_comprobatorio">';
            html += '       </div>';
            html += '       <div class="col-md-4">';
            html += '           <div class="form-group">';
            html += '               <label for="observaciones">Observaciones</label>';
            html += '               <textarea type="text" class="form-control" name="observaciones[]" id="observaciones" cols="30" rows="3"></textarea>';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div style="margin-bottom: 1%; margin-top: -1%">';
            html += '       <span class="material-symbols-rounded" style="margin-top: 2%; color: #3a3d44;">emoji_objects</span>';
            html += '       <p style="font-size: 15px; font-weight: bold; margin-top: -22px; margin-left: 4%">ÁREA DEL CONOCIMIENTO</p>';
            html += '   </div>';
            html += '   <div class="row" style="margin-bottom: 5px">';
            html += '       <div class="col-md-4">';
            html += '           <label for="tipo_area" class="control-label">Área del Conocimiento</label>';
            html += '           <select class="form-control" name="tipo_area[]" id="tipo_area">';
            html += '               <option value="">Escoge una opción</option>';
            html += '               <?php foreach ($area_con as $con) : ?>';
            html += '               <option value="<?php echo $con['id_cat_area_con']; ?>">';
            html += '               <?php echo ucwords($con['descripcion']) ?>';
            html += '               </option>';
            html += '               <?php endforeach; ?>';
            html += '           </select>';
            html += '       </div>';
            html += '       <div class="col-md-4">';
            html += '           <div class="form-group">';
            html += '               <label for="nombre_carrera" class="control-label">Nombre Carrera</label>';
            html += '               <input type="text" class="form-control" name="nombre_carrera[]" id="nombre_carrera">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-4">';
            html += '           <div class="form-group">';
            html += '               <label for="especialidad" class="control-label">Especialidad</label>';
            html += '               <input type="text" class="form-control" name="especialidad[]" id="especialidad">';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '</div>';
            html += '';

            $('#newRow').append(html);
        });

        $(document).on('click', '#removeRow', function() {
            $(this).closest('#inputFormRow').remove();
        });
    });
</script>

<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
    <div class="col-md-7">
        <div class="panel login-page4" style="margin-left: 0%;">
            <div class="panel-heading">
                <strong style="font-size: 16px; font-family: 'Montserrat', sans-serif">
                    <span class="glyphicon glyphicon-th"></span>
                    INFORMACIÓN DE: <?php echo upper_case(ucwords($e_detalle['nombre'] . " " . $e_detalle['apellidos'])); ?>
                </strong>
            </div>
            <div class="row" style="margin-top: 1%; margin-bottom: 2%; margin-left: 1%;">
                <div class="col-md-1">
                    <button type="button" class="btn btn-success" id="addRow" name="addRow" style="width: 50px">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
                            <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
                            <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
                        </svg>
                    </button>
                </div>
                <div class="col-md-10">
                    <p style="margin-top: 1%; margin-bottom: 2%; margin-left: 0%; font-weight: bold; color: #157347;">"Agregar más a Expediente Académico y Área del Conocimiento"</p>
                </div>
            </div>
            <div class="panel-body">
                <form method="post" action="exp_ac_lab.php?id=<?php echo (int)$e_detalle['id_det_usuario']; ?>" enctype="multipart/form-data">
                    <div style="margin-bottom: 1%; margin-top: -3%">
                        <span class="material-symbols-rounded" style="margin-top: 2%; color: #3a3d44;">school</span>
                        <p style="font-size: 15px; font-weight: bold; margin-top: -23px; margin-left: 4%">EXPEDIENTE ACADÉMICO</p>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="estudios">Estudios</label>
                                <input type="text" class="form-control" name="estudios[]" id="estudios">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="institucion">Institución</label>
                                <input type="text" class="form-control" name="institucion[]" id="institucion">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="grado">Grado</label>
                                <select class="form-control" name="grado[]" id="grado">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($escolaridades as $escolaridad) : ?>
                                        <option value="<?php echo $escolaridad['id_cat_escolaridad']; ?>">
                                            <?php echo ucwords($escolaridad['descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cedula_profesional">Cédula Profesional</label>
                                <input type="text" class="form-control" name="cedula_profesional[]" id="cedula_profesional">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="archivo_comprobatorio">Cédula (Archivo)</label>
                            <input type="file" accept="application/pdf" class="form-control" name="archivo_comprobatorio[]" id="archivo_comprobatorio">
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="observaciones">Observaciones</label>
                                <textarea type="text" class="form-control" name="observaciones[]" id="observaciones" cols="30" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1%; margin-top: -1%">
                        <span class="material-symbols-rounded" style="margin-top: 2%; color: #3a3d44;">emoji_objects</span>
                        <p style="font-size: 15px; font-weight: bold; margin-top: -23px; margin-left: 4%">ÁREA DEL CONOCIMIENTO</p>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="tipo_area" class="control-label">Área del Conocimiento</label>
                            <select class="form-control" name="tipo_area[]" id="tipo_area">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($area_con as $con) : ?>
                                    <option value="<?php echo $con['id_cat_area_con']; ?>">
                                        <?php echo ucwords($con['descripcion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre_carrera" class="control-label">Nombre Carrera</label>
                                <input type="text" class="form-control" name="nombre_carrera[]" id="nombre_carrera">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="especialidad" class="control-label">Especialidad</label>
                                <input type="text" class="form-control" name="especialidad[]" id="especialidad">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="newRow" style="margin-top: 3%;">
                    </div>
                    <div class="form-group clearfix">
                        <a href="detalles_usuario.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="exp_ac_lab" class="btn btn-info">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-5 panel-body" style="height: 100%; margin-top: -5px;">
        <table class="table table-bordered table-striped" style="width: 50%; float: left;" id="tblProductos">
            <thead class="thead-purple" style="margin-top: -50px;">
                <tr style="height: 10px;">
                    <th colspan="4" style="text-align:center; font-size: 14px;">Expediente</th>
                </tr>
                <tr style="height: 10px;">
                    <th style="width: 55%; font-size: 14px;">Estudios</th>
                    <th style="width: 1%; font-size: 14px;">Editar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rel_currs as $cur) : ?>
                    <tr>
                        <td style="font-size: 14px;"><?php echo remove_junk(ucwords($cur['estudios'] . " (" . $cur['grado'] . ")")) ?></td>
                        <td style="font-size: 14px;" class="text-center">
                            <a href="edit_estudios.php?id=<?php echo (int)$cur['id_rel_cur_acad']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 30px; width: 30px;"><span class="material-symbols-rounded" style="font-size: 18px; color: black; margin-top: 1px; margin-left: -3px;">edit</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <table class="table table-bordered table-striped" style="width: 50%;" id="tblProductos">
            <thead class="thead-purple" style="margin-top: -50px;">
                <tr style="height: 10px;">
                    <th colspan="4" style="text-align:center; font-size: 14px;">Expediente</th>
                </tr>
                <tr style="height: 10px;">
                    <th style="width: 55%; font-size: 14px;">Área del Conocimiento</th>
                    <th style="width: 1%; font-size: 14px;">Editar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rel_arCons as $areaCon) : ?>
                    <tr>
                        <td style="font-size: 14px;"><?php echo remove_junk(ucwords($areaCon['descripcion'])) ?></td>
                        <td style="font-size: 14px;" class="text-center">
                            <a href="edit_area_con.php?id=<?php echo (int)$areaCon['id_rel_area_con']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 30px; width: 30px;"><span class="material-symbols-rounded" style="font-size: 18px; color: black; margin-top: 1px; margin-left: -3px;">edit</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>