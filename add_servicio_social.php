<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Servicio Social';
require_once('includes/load.php');
$id_folio = last_id_folios_general();
$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 3) {
    page_require_level(3);
}
if ($nivel_user == 5) {
    redirect('home.php');
}
if ($nivel_user == 7) {
    redirect('home.php');
}
if ($nivel_user == 21) {
    redirect('home.php');
}
if ($nivel_user == 19) {
    redirect('home.php');
}
if ($nivel_user > 3 && $nivel_user < 5) :
    redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7) :
    redirect('home.php');
endif;
if ($nivel_user > 19 && $nivel_user < 21) :
    redirect('home.php');
endif;

$generos = find_all('cat_genero');
$nacionalidades = find_all('cat_nacionalidades');
$municipios = find_all('cat_municipios');
$escolaridades = find_all('cat_escolaridad');
$grupos_vuln = find_all('cat_grupos_vuln');
$discapacidades = find_all('cat_discapacidades');
$comunidades = find_all('cat_comunidades');
$entidades = find_all('cat_entidad_fed');
$carreras = find_all_order('cat_carreras', 'descripcion');
$inticadores_pat = find_all_pat(10);
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_servicio_social'])) {

    if (empty($errors)) {
        $modalidad   = remove_junk($db->escape($_POST['modalidad']));
        $nombre_prestador   = remove_junk($db->escape($_POST['nombre_prestador']));
        $paterno_prestador   = remove_junk($db->escape($_POST['paterno_prestador']));
        $materno_prestador   = remove_junk($db->escape($_POST['materno_prestador']));
        $genero   = remove_junk($db->escape($_POST['genero']));
        $edad   = remove_junk(($db->escape($_POST['edad'])));
        $nacionalidad   = remove_junk(($db->escape($_POST['nacionalidad'])));
        $entidad   = remove_junk($db->escape($_POST['entidad']));
        $municipio   = remove_junk($db->escape($_POST['municipio']));
        $escolaridad   = remove_junk($db->escape($_POST['escolaridad']));
        $discapacidad   = remove_junk($db->escape($_POST['discapacidad']));
        $grupo_vulnerable   = remove_junk($db->escape($_POST['grupo_vulnerable']));
        $comunidad   = remove_junk($db->escape($_POST['comunidad']));
        $carrera   = remove_junk($db->escape($_POST['carrera']));
        $institucion   = remove_junk($db->escape($_POST['institucion']));
        $fecha_inicio   = remove_junk($db->escape($_POST['fecha_inicio']));
        $fecha_termino   = remove_junk($db->escape($_POST['fecha_termino']));
        $total_horas   = remove_junk($db->escape($_POST['total_horas']));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));
        $creacion = date('Y-m-d H:i:s');



        $name1 = $_FILES['carta_presentacion']['name'];
        $size1 = $_FILES['carta_presentacion']['size'];
        $type1 = $_FILES['carta_presentacion']['type'];
        $temp1 = $_FILES['carta_presentacion']['tmp_name'];

        $name2 = $_FILES['oficio_aceptacion']['name'];
        $size2 = $_FILES['oficio_aceptacion']['size'];
        $type2 = $_FILES['oficio_aceptacion']['type'];
        $temp2 = $_FILES['oficio_aceptacion']['tmp_name'];

        $name3 = $_FILES['informe_bim1']['name'];
        $size3 = $_FILES['informe_bim1']['size'];
        $type3 = $_FILES['informe_bim1']['type'];
        $temp3 = $_FILES['informe_bim1']['tmp_name'];

        $name4 = $_FILES['informe_bim2']['name'];
        $size4 = $_FILES['informe_bim2']['size'];
        $type4 = $_FILES['informe_bim2']['type'];
        $temp4 = $_FILES['informe_bim2']['tmp_name'];

        $name5 = $_FILES['informe_bim3']['name'];
        $size5 = $_FILES['informe_bim3']['size'];
        $type5 = $_FILES['informe_bim3']['type'];
        $temp5 = $_FILES['informe_bim3']['tmp_name'];

        $name6 = $_FILES['informe_global']['name'];
        $size6 = $_FILES['informe_global']['size'];
        $type6 = $_FILES['informe_global']['type'];
        $temp6 = $_FILES['informe_global']['tmp_name'];

        $name7 = $_FILES['evaluacion_uReceptora']['name'];
        $size7 = $_FILES['evaluacion_uReceptora']['size'];
        $type7 = $_FILES['evaluacion_uReceptora']['type'];
        $temp7 = $_FILES['evaluacion_uReceptora']['tmp_name'];

        $name8 = $_FILES['oficio_terminacion']['name'];
        $size8 = $_FILES['oficio_terminacion']['size'];
        $type8 = $_FILES['oficio_terminacion']['type'];
        $temp8 = $_FILES['oficio_terminacion']['tmp_name'];

		$name9 = $_FILES['oficio_asignacion_area']['name'];
        $size9 = $_FILES['oficio_asignacion_area']['size'];
        $type9 = $_FILES['oficio_asignacion_area']['type'];
        $temp9 = $_FILES['oficio_asignacion_area']['tmp_name'];
		
		$name10 = $_FILES['oficio_obligaciones_prestador']['name'];
        $size10 = $_FILES['oficio_obligaciones_prestador']['size'];
        $type10 = $_FILES['oficio_obligaciones_prestador']['type'];
        $temp10 = $_FILES['oficio_obligaciones_prestador']['tmp_name'];

        $dbh = new PDO('mysql:host=localhost;dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');
        // set the PDO error mode to exception
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "INSERT INTO servicio_social (";
        $query .= "modalidad,nombre_prestador,paterno_prestador,materno_prestador,genero,edad,nacionalidad,entidad,municipio,escolaridad,
                        discapacidad,grupo_vulnerable,comunidad,carrera,institucion,fecha_inicio,fecha_termino,total_horas,id_indicadores_pat,
                        observaciones,carta_presentacion,oficio_aceptacion,informe_bim1,informe_bim2,informe_bim3,informe_global,
                        evaluacion_uReceptora,oficio_terminacion,oficio_asignacion_area,oficio_obligaciones_prestador,user_creador,fecha_creacion";
        $query .= ") VALUES (";
        $query .= " '{$modalidad}','{$nombre_prestador}','{$paterno_prestador}','{$materno_prestador}','{$genero}','{$edad}','{$nacionalidad}','{$entidad}',
                        '{$municipio}','{$escolaridad}','{$discapacidad}','{$grupo_vulnerable}',
                        '{$comunidad}','{$carrera}','{$institucion}','{$fecha_inicio}','{$fecha_termino}','{$total_horas}',{$id_indicadores_pat},
                        '{$observaciones}','{$name1}','{$name2}','{$name3}','{$name4}','{$name5}','{$name6}','{$name7}','{$name8}','{$name9}','{$name10}','{$id_user}','{$creacion}'";
        $query .= ")";

        $dbh->exec($query);
        $id_ss = $dbh->lastInsertId();

        $carpeta = 'uploads/servicioSocial/' . $id_ss;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $move1 =  move_uploaded_file($temp1, $carpeta . "/" . $name1);
        $move2 =  move_uploaded_file($temp2, $carpeta . "/" . $name2);
        $move3 =  move_uploaded_file($temp3, $carpeta . "/" . $name3);
        $move4 =  move_uploaded_file($temp4, $carpeta . "/" . $name4);
        $move5 =  move_uploaded_file($temp5, $carpeta . "/" . $name5);
        $move6 =  move_uploaded_file($temp6, $carpeta . "/" . $name6);
        $move7 =  move_uploaded_file($temp7, $carpeta . "/" . $name7);
        $move8 =  move_uploaded_file($temp8, $carpeta . "/" . $name8);
/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}

        $session->msg('s', " El servicio social ha sido agregado con éxito.");
        insertAccion($user['id_user'], '"' . $user['username'] . '" agregó servicio social.', 1);
        redirect('servicio_social.php', false);
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
                <span>Agregar servicio social</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_servicio_social.php" enctype="multipart/form-data">
		<div class="panel-heading">
			<strong>
				<span class="glyphicon glyphicon-user"></span>
				<span>Datos Generales</span>
			</strong>
		</div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="modalidad">Modalidad</label>
                            <select class="form-control" name="modalidad" required>
                                <option value="">Escoge una opción</option>
                                <option value="Servicio Social">Servicio Social</option>
                                <option value="Prácticas Profesionales">Prácticas Profesionales</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_prestador">Nombre</label>
                            <input type="text" class="form-control" name="nombre_prestador" placeholder="Prestador" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="paterno_prestador">Apellido Paterno</label>
                            <input type="text" class="form-control" name="paterno_prestador" placeholder="Prestador" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="materno_prestador">Apellido Materno</label>
                            <input type="text" class="form-control" name="materno_prestador" placeholder="Prestador" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="genero">Género</label>
                            <select class="form-control" name="genero" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($generos as $gen) : ?>
                                    <option value="<?php echo $gen['id_cat_gen']; ?>"><?php echo ucwords($gen['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="edad">Edad</label>
                            <input type="number" class="form-control" min="1" max="130" maxlength="4" name="edad" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="nacionalidad">Nacionalidad</label>
                            <select class="form-control" name="nacionalidad" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($nacionalidades as $nacion) : ?>
                                    <option value="<?php echo $nacion['id_cat_nacionalidad']; ?>"><?php echo ucwords($nacion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="entidad">Entidad</label>
                            <select class="form-control" name="entidad" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($entidades as $ent) : ?>
                                    <option value="<?php echo $ent['id_cat_ent_fed']; ?>"><?php echo ucwords($ent['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="municipio">Municipio</label>
                            <select class="form-control" name="municipio" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($municipios as $id_cat_municipio) : ?>
                                    <option value="<?php echo $id_cat_municipio['id_cat_mun']; ?>"><?php echo ucwords($id_cat_municipio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="escolaridad">Escolaridad</label>
                            <select class="form-control" name="escolaridad" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($escolaridades as $estudios) : ?>
                                    <option value="<?php echo $estudios['id_cat_escolaridad']; ?>"><?php echo ucwords($estudios['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="discapacidad">Discapacidad</label>
                            <select class="form-control" name="discapacidad" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($discapacidades as $disc) : ?>
                                    <option value="<?php echo $disc['id_cat_disc']; ?>"><?php echo ucwords($disc['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="grupo_vulnerable">Grupo Vulnerable</label>
                            <select class="form-control" name="grupo_vulnerable" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($grupos_vuln as $gv) : ?>
                                    <option value="<?php echo $gv['id_cat_grupo_vuln']; ?>"><?php echo ucwords($gv['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                   
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="comunidad">Comunidades</label>
                            <select class="form-control" name="comunidad" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($comunidades as $com) : ?>
                                    <option value="<?php echo $com['id_cat_comun']; ?>"><?php echo ucwords($com['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" required>
                                <option value="">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                </div>
				<div class="panel-heading">
			<strong>
				<span class="glyphicon glyphicon-briefcase"></span>
				<span>Datos Institución Educativa</span>
			</strong>
		</div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="carrera">Carrera</label>
                            <select class="form-control" name="carrera" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($carreras as $car) : ?>
                                    <option value="<?php echo $car['id_cat_carrera']; ?>"><?php echo ucwords($car['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="institucion">Institución</label>
                            <input type="text" class="form-control" name="institucion" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_termino">Fecha Término</label>
                            <input type="date" class="form-control" name="fecha_termino" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="total_horas">Total Hrs.</label>
                            <input type="number" class="form-control" name="total_horas" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="30" rows="3"></textarea>
                        </div>
		</div>
                    </div><div class="panel-heading">
			<strong>
				<span class="glyphicon glyphicon-folder-open"></span>
				<span>Documentación</span>
			</strong>
		</div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="carta_presentacion">Carta Presentación</label>
                            <input type="file" accept="application/pdf" class="form-control" name="carta_presentacion" id="carta_presentacion">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="oficio_aceptacion">Oficio Aceptacion</label>
                            <input type="file" accept="application/pdf" class="form-control" name="oficio_aceptacion" id="oficio_aceptacion">
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="oficio_asignacion_area">Oficio Asignación a Área</label>
                            <input type="file" accept="application/pdf" class="form-control" name="oficio_asignacion_area" id="oficio_asignacion_area">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="oficio_obligaciones_prestador">Obligaciones de la persona prestadora</label>
                            <input type="file" accept="application/pdf" class="form-control" name="oficio_obligaciones_prestador" id="oficio_obligaciones_prestador">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="informe_bim1">Informe Bimestral 1</label>
                            <input type="file" accept="application/pdf" class="form-control" name="informe_bim1" id="informe_bim1">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="informe_bim2">Informe Bimestral 2</label>
                            <input type="file" accept="application/pdf" class="form-control" name="informe_bim2" id="informe_bim2">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="informe_bim3">Informe Bimestral 3</label>
                            <input type="file" accept="application/pdf" class="form-control" name="informe_bim3" id="informe_bim3">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="informe_global">Informe Global</label>
                            <input type="file" accept="application/pdf" class="form-control" name="informe_global" id="informe_global">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="evaluacion_uReceptora">Eval. Unidad Receptora</label>
                            <input type="file" accept="application/pdf" class="form-control" name="evaluacion_uReceptora" id="evaluacion_uReceptora">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="oficio_terminacion">Oficio de Terminación</label>
                            <input type="file" accept="application/pdf" class="form-control" name="oficio_terminacion" id="oficio_terminacion">
                        </div>
                    </div>
                </div>
				<div class="row">
                    
                    
                </div>

                <div class="form-group clearfix">
                    <a href="servicio_social.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_servicio_social" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>