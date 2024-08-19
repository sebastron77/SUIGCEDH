<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Convenio';
require_once('includes/load.php');

$id_folio = last_id_folios();
$id_convenio = last_id_convenio();
$cat_tipo_ambito = find_all('cat_tipo_ambito');
$cat_tipo_institucion = find_all('cat_tipo_institucion');
$inticadores_pat = find_all_pat(10);

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    page_require_level(3);
}
if ($nivel == 4) {
    redirect('home.php');
}
if ($nivel == 5) {
    redirect('home.php');
}
if ($nivel == 6) {
    redirect('home.php');
}
if ($nivel == 7) {
    page_require_level(7);
}
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_convenio'])) {

    $req_fields = array('id_tipo_ambito', 'nombre_institucion', 'id_tipo_institucion');
    validate_fields($req_fields);


    if (empty($errors)) {

        $fecha_convenio = remove_junk($db->escape($_POST['fecha_convenio']));
        $id_tipo_ambito   = remove_junk($db->escape($_POST['id_tipo_ambito']));
        $nombre_institucion   = remove_junk($db->escape($_POST['nombre_institucion']));
        $id_tipo_institucion   = remove_junk($db->escape($_POST['id_tipo_institucion']));
        $indefinido = isset($_POST['indefinido']) ? 1 : 0;
        $fecha_vigencia = remove_junk($db->escape($_POST['fecha_vigencia']));
        $descripcion_convenio   = remove_junk(($db->escape($_POST['descripcion_convenio'])));
        $nombre_contacto   = remove_junk(($db->escape($_POST['nombre_contacto'])));
        $cargo_contacto   = remove_junk(($db->escape($_POST['cargo_contacto'])));
        $direccion_institucion   = remove_junk(($db->escape($_POST['direccion_institucion'])));
        $telefono_contacto   = remove_junk(($db->escape($_POST['telefono_contacto'])));
        $id_indicadores_pat   = remove_junk(($db->escape($_POST['id_indicadores_pat'])));
        $creacion = date('Y-m-d H:i:s');
        //Suma el valor del id anterior + 1, para generar ese id para el nuevo resguardo
        //La variable $no_folio sirve para el numero de folio
        if (count($id_convenio) == 0) {
            $nuevo_id_convenio = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_convenio as $nuevo) {
                $nuevo_id_convenio = (int) $nuevo['id_convenio'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['id_convenio'] + 1);
            }
        }

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int) $nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int) $nuevo['contador'] + 1);
            }
        }
        //Se crea el número de folio
        $year = date("Y");
        // Se crea el folio de canalizacion
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-CONV';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-CONV';
        $carpeta = 'uploads/convenios/' . $folio_carpeta;

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


        if ($move && $name != '') {
            $query = "INSERT INTO convenios (";
            $query .= "folio_solicitud,fecha_convenio,nombre_institucion,id_cat_tipo_ambito,id_tipo_institucion,descripcion_convenio,nombre_contacto,cargo_contacto,
					   direccion_institucion,telefono_contacto,documento_convenio,indefinido,fecha_vigencia,id_user_creador,fecha_creacion,id_indicadores_pat";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$fecha_convenio}','{$nombre_institucion}','{$id_tipo_ambito}','{$id_tipo_institucion}','{$descripcion_convenio}','{$nombre_contacto}','{$cargo_contacto}',
					'{$direccion_institucion}','{$telefono_contacto}','{$name}',{$indefinido},";
            $query .=  ($indefinido == 0 ? "'{$fecha_vigencia}'," : "null,");
            $query .= "{$id_user},'{$creacion}',{$id_indicadores_pat})";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";

            if ($db->query($query) && $db->query($query2)) {
                //sucess
                insertAccion($user['id_user'], '"' . $user['username'] . '" creo un Convenio de Folio: -' . $folio . '- con la Institución -' . $nombre_institucion . '-.', 1);
                $session->msg('s', " El convenio con folio '{$folio}' ha sido agregado con éxito.");
                redirect('convenios.php', false);
            } else {
                //failed
                $session->msg('d', ' No se pudo agregar el convenio.');
                redirect('add_convenio.php', false);
            }
        } else {
            $session->msg("d", "Error en el nombre del archivo");
            redirect('add_convenio.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_convenio.php', false);
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
                <span>Agregar convenio</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_convenio.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_convenio">Fecha de convenio<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_convenio">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_tipo_ambito">Ámbito institucional <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="id_tipo_ambito" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_tipo_ambito as $ambito) : ?>
                                    <option value="<?php echo $ambito['id_cat_tipo_ambito']; ?>"><?php echo ucwords($ambito['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_institucion">Institución<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="nombre_institucion" placeholder="Nombre de la institución" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_institucion">Tipo de institución<span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="id_tipo_institucion" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_tipo_institucion as $institucion) : ?>
                                    <option value="<?php echo $institucion['id_tipo_institucion']; ?>"><?php echo ucwords($institucion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="indefinido">¿Es Indefinido?<span style="color:red;font-weight:bold">*</span></label><br>
                            <label class="switch" style="float:left;">
                                <div class="row">
                                    <input type="checkbox" id="indefinido" name="indefinido">
                                    <span class="slider round"></span>
                                    <div>
                                        <p style="margin-left: 150%; margin-top: -3%; font-size: 14px;">No/Sí</p>
                                    </div>
                                </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_vigencia">Fecha Vigencia</label><br>
                            <input type="date" class="form-control" name="fecha_vigencia">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="descripcion_convenio">Descripción del convenio<span style="color:red;font-weight:bold">*</span></label><br>
                            <textarea name="descripcion_convenio" class="form-control" id="descripcion_convenio" cols="50" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="adjunto">Adjuntar Convenio<span style="color:red;font-weight:bold">*</span></label>
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto">
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" >
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h3 style="margin-top: 1%; font-weight:bold;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#3a3d44" height="32px" width="32px" viewBox="0 0 24 24">
                            <path d="M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm4-2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5ZM9 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 9 8Zm1 2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5Z" />
                            <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2ZM1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H8.96c.026-.163.04-.33.04-.5C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1.006 1.006 0 0 1 1 12V4Z" />
                        </svg>
                        Datos contacto
                    </h3>
                </div>
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_contacto">Nombre del Contacto<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="nombre_contacto" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cargo_contacto">Cargo del Contacto en la Intitución<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="cargo_contacto" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="direccion_institucion">Dirección de la institución<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="direccion_institucion" required>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="telefono_contacto">Teléfono<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="telefono_contacto" maxlength="10" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="convenios.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="add_convenio" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>