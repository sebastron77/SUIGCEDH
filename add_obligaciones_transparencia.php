<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Recepción de Obligaciones de Transparencia';
require_once('includes/load.php');

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 10) {
    page_require_level_exacto(10);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
	
endif;if ($nivel_user > 7 && $nivel_user < 10) :
    redirect('home.php');
endif;
if ($nivel_user > 10) :
    redirect('home.php');
endif;
$id_folio = last_id_folios();
$inticadores_pat = find_all_pat(13);
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_obligaciones_transparencia'])) {

    if (empty($errors)) {

        $ejercicio = remove_junk($db->escape($_POST['ejercicio']));
        $trimestre_revisado   = remove_junk($db->escape($_POST['trimestre_revisado']));			
        $fecha_recepcion   = remove_junk($db->escape($_POST['fecha_recepcion']));			
        $total_formatos   = remove_junk($db->escape($_POST['total_formatos']));			
        $ficha_recepcion   = remove_junk($db->escape($_POST['ficha_recepcion']));			
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));			
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));			

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
        // Se crea el folio orientacion
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-FOT';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-FOT';
        $carpeta = 'uploads/obligaciones_transparencia/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['ficha_recepcion']['name'];
        $size = $_FILES['ficha_recepcion']['size'];
        $type = $_FILES['ficha_recepcion']['type'];
        $temp = $_FILES['ficha_recepcion']['tmp_name'];

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
		/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}



            $query = "INSERT INTO obligaciones_transparencia (";
            $query .= "folio,ejercicio,trimestre_revisado,fecha_recepcion,total_formatos,ficha_recepcion,observaciones,id_indicadores_pat,id_user_creador,fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$ejercicio}','{$trimestre_revisado}','{$fecha_recepcion}','{$total_formatos}','{$name}','{$observaciones}','{$id_indicadores_pat}',{$id_user},NOW())";
			
            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";

			if ($db->query($query) && $db->query($query2)) {
                //sucess
                insertAccion($user['id_user'], '"' . $user['username'] . '" dió de alta la Recepción de Obligaciones de Transparencia con Folio: -' . $folio . '-.', 1);
                $session->msg('s', " La Recepción de Obligaciones de Transparencia con folio '{$folio}' ha sido agregado con éxito.");
                redirect('obligaciones_transparencia.php', false);
            } else {
                //failed
                $session->msg('d', ' No se pudo agregar la Sesión de Comite de Transparencia.');
                redirect('add_obligaciones_transparencia.php', false);
            }
       
    } else {
        $session->msg("d", $errors);
        redirect('add_obligaciones_transparencia.php', false);
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
                <span>Agregar Recepción de Obligaciones de Transparencia</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_obligaciones_transparencia.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ejercicio" class="control-label">Ejercicio</label>
							<select class="form-control" name="ejercicio" id="ejercicio" required>
								<option value="">Escoge una opción</option>                        
								<option value="2023">2023</option>
								<option value="2024">2024</option>
							</select>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="trimestre_revisado" class="control-label">Trimestre</label>
							<select class="form-control" name="trimestre_revisado" id="trimestre_revisado" required>
								<option value="">Escoge una opción</option>                        
								<option value="1er Trimestre">1er Trimestre</option>
								<option value="2do Trimestre">2do Trimestre</option>
								<option value="3er Trimestre">3er Trimestre</option>
								<option value="4to Trimestre">4to Trimestre</option>
							</select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_recepcion">Fecha de Recepción<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_recepcion" id="fecha_recepcion" required>
                        </div>
                    </div>
					<div class="col-md-2">
                <div class="form-group">
                    <label for="desktop">Total de Formatos Revisados</label>
                    <input class="form-control monto" type="number" id="total_formatos" name="total_formatos" required>
                </div>
            </div>
                    <div class="col-md-3">
					      <div class="form-group">
                            <label for="ficha_recepcion">Ficha Recepción</label>
                            <input type="file" accept="application/pdf" class="form-control" name="ficha_recepcion" id="ficha_recepcion" required>
                        </div>
                    </div>					
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" required >
                                <option value="">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option   value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                        </div>
		
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones"  cols="16" rows="6" ></textarea>
                        </div>
                    </div>

                   
                </div>

                
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="obligaciones_transparencia.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="add_obligaciones_transparencia" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>