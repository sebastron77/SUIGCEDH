<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Instrumentos Archivisticos';
require_once('includes/load.php');

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$id_folio = last_id_folios();
$documento_archivistico = find_all('cat_tipo_documento_archivistico');

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la lista de '.$page_title.' del Ejercicio '.$ejercicio, 5);
    page_require_level_exacto(7);
}
if ($nivel_user == 11) {
    page_require_level_exacto(11);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la lista de '.$page_title.' del Ejercicio '.$ejercicio, 5);
    page_require_level_exacto(53);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 11) :
    redirect('home.php');
endif;

if ($nivel_user > 11 && $nivel_user < 21) :
    redirect('home.php');
endif;

?>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_instrumentos_archivisticos'])) {

    if (empty($errors)) {
        $id_cat_tipo_documento_archivistico   = remove_junk($db->escape($_POST['id_cat_tipo_documento_archivistico']));
        $fecha_publicacion   = remove_junk(($db->escape($_POST['fecha_publicacion'])));
        $documento_instrumento   = remove_junk($db->escape($_POST['documento_instrumento']));
       

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int) $nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int) $nuevo['contador'] + 1);
            }
        }

        $year = date("Y");
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-INSARC';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-INSARC';
        $carpeta = 'uploads/instrumentos_archivisticos/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['documento_instrumento']['name'];
        $size = $_FILES['documento_instrumento']['size'];
        $type = $_FILES['documento_instrumento']['type'];
        $temp = $_FILES['documento_instrumento']['tmp_name'];

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
	/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}

       

            $query = "INSERT INTO instrumentos_archivisticos (";
            $query .= "folio, id_cat_tipo_documento_archivistico, fecha_publicacion, id_user_creador,fecha_creacion";
			if($name){
				$query .= ",documento_instrumento ";				
			}
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$id_cat_tipo_documento_archivistico}','{$fecha_publicacion}', '{$id_user}',NOW()";
			if($name){
				$query .= ",'{$name}' ";				
			}
            $query .= ")";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";
     

        if ($db->query($query) && $db->query($query2)) {
            //success
            $session->msg('s', " El Instrumentos Archivisticos ha sido agregado con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó el Instrumentos Archivisticos, Folio: ' . $folio . '.', 1);
            redirect('instrumentos_archivisticos.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar el Instrumentos Archivisticos.');
            redirect('add_instrumentos_archivisticos.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_instrumentos_archivisticos.php', false);
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
                <span><?php echo $page_title?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_instrumentos_archivisticos.php" enctype="multipart/form-data">
                <div class="row">
				<div class="col-md-4">
                        <div class="form-group">
                            <label for="id_cat_tipo_documento_archivistico">Tipo de Instrumento <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="id_cat_tipo_documento_archivistico" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($documento_archivistico as $datos) : ?>
                                    <option value="<?php echo $datos['id_cat_tipo_documento_archivistico']; ?>"><?php echo ucwords($datos['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_publicacion">Fecha de Publicacción</label>
                            <input type="date" class="form-control" name="fecha_publicacion" required>
                        </div>
                    </div>                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="documento_instrumento">Adjuntar Documento</label>
                            <input type="file" accept="application/pdf" class="form-control" name="documento_instrumento" id="documento_instrumento">
                        </div>
                    </div>                  
                </div>
                
                <div class="form-group clearfix">
                    <a href="instrumentos_archivisticos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_instrumentos_archivisticos" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>