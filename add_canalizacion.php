<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Canalización';
require_once('includes/load.php');
$user = current_user();
$id_ori_canal = last_id_oricanal();
$id_folio = last_id_folios();


$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$niv_estudios = find_all('cat_escolaridad');
$ocupaciones = find_all('cat_ocupaciones');
$grupos_vuln = find_all('cat_grupos_vuln');
$generos = find_all('cat_genero');
$autoridades = find_all_autoridades();
$entidades = find_all('cat_entidad_fed');
$nacionalidad = find_all('cat_nacionalidades');
$medios_pres = find_all('cat_medio_pres');
$cat_municipios = find_all_cat_municipios();
$dates_user = muestra_area($id_user);

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 5) {
    page_require_level_exacto(5);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}
if ($nivel_user > 2 && $nivel_user < 5) :
    redirect('home.php');
endif;

?>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_canalizacion'])) {

    $req_fields = array('nombre', 'nestudios', 'ocupacion', 'edad', 'tel', 'sexo', 'calle', 'colonia', 'cpostal', 'id_cat_mun', 'localidad', 'entidad', 'nacionalidad', 'medio', 'grupo_vulnerable', 'lengua', 'institucion_canaliza');
    validate_fields($req_fields);

    if (empty($errors)) {
        $correo = remove_junk($db->escape($_POST['correo']));
        $nombre = remove_junk($db->escape($_POST['nombre']));
        $nestudios = remove_junk($db->escape($_POST['nestudios']));
        $ocupacion = remove_junk($db->escape($_POST['ocupacion']));
        $edad = remove_junk(upper_case($db->escape($_POST['edad'])));
        $tel = remove_junk(upper_case($db->escape($_POST['tel'])));
        $ext = remove_junk($db->escape($_POST['ext']));
        $sexo = remove_junk($db->escape($_POST['sexo']));
        $calle = remove_junk($db->escape($_POST['calle']));
        $colonia = remove_junk($db->escape($_POST['colonia']));
        $cpostal = remove_junk($db->escape($_POST['cpostal']));
        //$municipio = remove_junk($db->escape($_POST['municipio']));
        $id_cat_mun = remove_junk($db->escape($_POST['id_cat_mun']));
        $localidad = remove_junk($db->escape($_POST['localidad']));
        $entidad = remove_junk($db->escape($_POST['entidad']));
        $nacionalidad = remove_junk($db->escape($_POST['nacionalidad']));
        $medio = remove_junk($db->escape($_POST['medio']));
        $grupo_vulnerable = remove_junk($db->escape($_POST['grupo_vulnerable']));
        $lengua = remove_junk($db->escape($_POST['lengua']));
        $institucion_canaliza = remove_junk($db->escape($_POST['institucion_canaliza']));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
		$id_indicadores_pat=0;
        // $adjunto = remove_junk($db->escape($_POST['adjunto']));
        // $adjunto = remove_junk($db->escape($_POST['adjunto']));
        date_default_timezone_set('America/Mexico_City');
        $creacion = date('Y-m-d');

        //Suma el valor del id anterior + 1, para generar ese id para el nuevo resguardo
        //La variable $no_folio sirve para el numero de folio
        if (count($id_ori_canal) == 0) {
            $nuevo_id_ori_canal = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_ori_canal as $nuevo) {
                $nuevo_id_ori_canal = (int) $nuevo['id_or_can'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['id_or_can'] + 1);
            }
        }

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                //$nuevo_id_folio = (int)$nuevo['id'] + 1;
                //$no_folio1 = sprintf('%04d', (int)$nuevo['id'] + 1);
                $nuevo_id_folio = (int) $nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int) $nuevo['contador'] + 1);
            }
        }
        //Se crea el número de folio
        $year = date("Y");
        // Se crea el folio de canalizacion
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-C';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-C';
        $carpeta = 'uploads/orientacioncanalizacion/canalizacion/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['adjunto']['name'];
        $size = $_FILES['adjunto']['size'];
        $type = $_FILES['adjunto']['type'];
        $temp = $_FILES['adjunto']['tmp_name'];

        $move = move_uploaded_file($temp, $carpeta . "/" . $name);
		/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}
	$inticadores_pat = find_all_pat_area($dates_user['id_area'],'rel_orientacion_canalizacion_pat');
	foreach ($inticadores_pat as $datos) : 
		if(strpos($datos['nombre_indicador'],'Canalizaciones') >0){
			$id_indicadores_pat=$datos['id_indicadores_pat'];
		}
	endforeach;

	$dbh = new PDO('mysql:host=localhost;dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');
        if ($move && $name != '') {
            $query = "INSERT INTO orientacion_canalizacion (";
            $query .= "folio,correo_electronico,nombre_completo,nivel_estudios,ocupacion,edad,telefono,extension,sexo,calle_numero,colonia,codigo_postal,municipio_localidad,id_cat_mun,entidad,nacionalidad,tipo_solicitud,medio_presentacion,institucion_canaliza,grupo_vulnerable,lengua,observaciones,adjunto,id_creador,creacion";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$correo}','{$nombre}','{$nestudios}','{$ocupacion}','{$edad}','{$tel}','{$ext}','{$sexo}','{$calle}','{$colonia}','{$cpostal}','{$localidad}','{$id_cat_mun}','{$entidad}','{$nacionalidad}','2','{$medio}','{$institucion_canaliza}','{$grupo_vulnerable}','{$lengua}','{$observaciones}','{$name}','{$id_user}','{$creacion}'";
            $query .= ")";
            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";
        } else {
            $query = "INSERT INTO orientacion_canalizacion (";
            $query .= "folio,correo_electronico,nombre_completo,nivel_estudios,ocupacion,edad,telefono,extension,sexo,calle_numero,colonia,codigo_postal,municipio_localidad,id_cat_mun,entidad,nacionalidad,tipo_solicitud,medio_presentacion,institucion_canaliza,grupo_vulnerable,lengua,observaciones,adjunto,id_creador,creacion";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$correo}','{$nombre}','{$nestudios}','{$ocupacion}','{$edad}','{$tel}','{$ext}','{$sexo}','{$calle}','{$colonia}','{$cpostal}','{$localidad}','{$id_cat_mun}','{$entidad}','{$nacionalidad}','2','{$medio}','{$institucion_canaliza}','{$grupo_vulnerable}','{$lengua}','{$observaciones}','{$name}','{$id_user}','{$creacion}'";
            $query .= ")";
            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";
        }
		$dbh->exec($query);
        if ($db->query($query2)) {
			$id_or_can = $dbh->lastInsertId();
			if($id_indicadores_pat >0){
				$sql = "INSERT INTO rel_orientacion_canalizacion_pat (";
				$sql .= "id_or_can,tipo_solicitud,id_indicadores_pat,id_area_creadora,id_user,fecha_creacion) VALUES (";
				$sql .= " '{$id_or_can}','2','{$id_indicadores_pat}',{$dates_user['id_area']},'{$id_user}',NOW() )";
				$db->query($sql);
			}
            //sucess
            $session->msg('s', " La canalización ha sido agregada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó canalización, Folio: ' . $folio . '.', 1);
            redirect('canalizaciones.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la canalización.');
            redirect('add_canalizacion.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_canalizacion.php', false);
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
                <span>Agregar canalización</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_canalizacion.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="correo">Correo Electrónico</label>
                            <input type="text" class="form-control" name="correo" placeholder="ejemplo@correo.com" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre">Nombre Completo</label>
                            <input type="text" class="form-control" name="nombre" placeholder="Nombre Completo" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="nestudios">Nivel de Estudios</label>
                            <select class="form-control" name="nestudios" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($niv_estudios as $estudios) : ?>
                                    <option value="<?php echo $estudios['id_cat_escolaridad']; ?>"><?php echo ucwords($estudios['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ocupacion">Ocupacion</label>
                            <select class="form-control" name="ocupacion" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($ocupaciones as $ocupacion) : ?>
                                    <option value="<?php echo $ocupacion['id_cat_ocup']; ?>"><?php echo ucwords($ocupacion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="edad">Edad</label>
                            <input type="number" min="1" max="120" class="form-control" name="edad" placeholder="Edad" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tel">Teléfono</label>
                            <input type="text" maxlength="10" class="form-control" name="tel" placeholder="Teléfono" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="ext">Ext</label>
                            <input type="text" maxlength="3" class="form-control" name="ext" placeholder="Extensión">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="lengua">Dialecto</label>
                            <input type="text" class="form-control" name="lengua" placeholder="Lengua hablada" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="grupo_vulnerable">Grupo Vulnerable</label>
                            <select class="form-control" name="grupo_vulnerable" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($grupos_vuln as $grupo_vuln) : ?>
                                    <option value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sexo">Género</label>
                            <select class="form-control" name="sexo" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($generos as $genero) : ?>
                                    <option value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="calle">Calle y número</label>
                            <input type="text" class="form-control" name="calle" placeholder="Calle y número" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="colonia">Colonia</label>
                            <input type="text" class="form-control" name="colonia" placeholder="Colonia" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cpostal">Código Postal</label>
                            <input type="text" maxlength="5" class="form-control" name="cpostal" placeholder="Código Postal" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="institucion_canaliza">Autoridad señalada</label>
                            <select class="form-control" name="institucion_canaliza" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($autoridades as $autoridad) : ?>
                                    <option value="<?php echo $autoridad['id_cat_aut']; ?>"><?php echo ucwords($autoridad['nombre_autoridad']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_mun">Municipio</label>
                            <select class="form-control" name="id_cat_mun" required>
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
                            <input type="text" class="form-control" name="localidad" placeholder="Localidad" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="entidad">Entidad</label>
                            <select class="form-control" name="entidad" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($entidades as $entidad) : ?>
                                    <option value="<?php echo $entidad['id_cat_ent_fed']; ?>"><?php echo ucwords($entidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="nacionalidad">Nacionalidad</label>
                            <select class="form-control" name="nacionalidad">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($nacionalidad as $nacion) : ?>
                                    <option value="<?php echo $nacion['id_cat_nacionalidad']; ?>"><?php echo ucwords($nacion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="medio">Medio de presentación</label>
                            <select class="form-control" name="medio" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($medios_pres as $medio) : ?>
                                    <option value="<?php echo $medio['id_cat_med_pres']; ?>"><?php echo ucwords($medio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="adjunto">Subir acta de canalización (si es necesario)</label>
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label><br>
                            <textarea name="observaciones" class="form-control" id="observaciones" cols="50" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="canalizaciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_canalizacion" class="btn btn-primary" onclick="return confirm('Tu canalización será guardada, verifica el folio generado para asignarlo de manera correcta a su expediente. Da clic en Aceptar para continuar.');">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>