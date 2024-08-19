<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Difusión';
require_once('includes/load.php');

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 15) {
    page_require_level(15);
}
if ($nivel > 2 && $nivel < 7) :
    redirect('home.php');
endif;
if ($nivel > 7  && $nivel < 15) :
    redirect('home.php');
endif;
if ($nivel > 15) :
    redirect('home.php');
endif;

$id_folio = last_id_folios();
$id_difusion = last_id_table('difusion', 'id_difusion');
$difusiones = find_all_status('cat_tipo_difusion');
$inticadores_pat = find_all_pat(46);
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_difusion'])) {

    if (empty($errors)) {

        $fecha = remove_junk($db->escape($_POST['fecha']));
        $tipo_difusion   = remove_junk($db->escape($_POST['tipo_difusion']));
        $tema   = remove_junk($db->escape($_POST['tema']));
        $link   = remove_junk($db->escape($_POST['link']));
        $entrevistado = remove_junk($db->escape($_POST['entrevistado']));
        $medio = remove_junk($db->escape($_POST['medio']));
		$id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));

        //Suma el valor del id anterior + 1, para generar ese id para el nuevo resguardo
        //La variable $no_folio sirve para el numero de folio
        if (count($id_difusion) == 0) {
            $nuevo_id_difusion = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_difusion as $nuevo) {
                $nuevo_id_difusion = (int) $nuevo['id_difusion'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['id_difusion'] + 1);
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
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-DIF';
        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-DIF';
        $carpeta = 'uploads/difusiones_CS/' . $folio_carpeta;

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



        $query = "INSERT INTO difusion (";
        $query .= "folio,fecha,tipo_difusion,tema,link,pdf,entrevistado,medio,id_indicadores_pat,user_creador,fecha_creacion";
        $query .= ") VALUES (";
        $query .= " '{$folio}','{$fecha}','{$tipo_difusion}','{$tema}','{$link}','{$name}','{$entrevistado}','{$medio}','{$id_indicadores_pat}','{$id_user}',NOW()); ";

        $query2 = "INSERT INTO folios (";
        $query2 .= "folio, contador";
        $query2 .= ") VALUES (";
        $query2 .= " '{$folio}','{$no_folio1}'";
        $query2 .= ")";

        if ($db->query($query) && $db->query($query2)) {
            //sucess
            insertAccion($user['id_user'], '"' . $user['username'] . '" dio de alta una Difusión de Folio: -' . $folio . '-.', 1);
            $session->msg('s', " La Difusión con folio '{$folio}' ha sido agregado con éxito.");
            redirect('difusion.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la Difusión.');
            redirect('add_difusion.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_difusion.php', false);
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
                <span>Agregar Difusión</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_difusion.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha<span style="color:red;font-weight:bold"> *</span></label><br>
                            <input type="date" class="form-control" name="fecha" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_difusion">Tipo Difusión<span style="color:red;font-weight:bold"> *</span></label><br>
                            <select class="form-control" name="tipo_difusion">
                                <option value="">Selecciona una Área</option>
                                <?php foreach ($difusiones as $difusion) : ?>
                                    <option value="<?php echo $difusion['id_cat_tipo_dif']; ?>"><?php echo ucwords($difusion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tema">Tema<span style="color:red;font-weight:bold"> *</span></label><br>
                            <input type="text" class="form-control" name="tema" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="link">Link</label>
                            <input type="text" class="form-control" name="link">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="adjunto">Adjuntar PDF</label>
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="entrevistado">Entrevistado</label>
                            <input type="text" class="form-control" name="entrevistado">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="medio">Medio<span style="color:red;font-weight:bold"> *</span></label>
                            <input type="text" class="form-control" name="medio" required>
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

                <div class="row">
                    <div class="form-group clearfix">
                        <a href="difusion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="add_difusion" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>