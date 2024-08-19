<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar SEsión Comite de Transparencia';
require_once('includes/load.php');

$user = current_user();
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
	
endif;
if ($nivel_user > 7 && $nivel_user < 10) :
    redirect('home.php');
endif;
if ($nivel_user > 10) :
    redirect('home.php');
endif;
$id_folio = last_id_folios();
$inticadores_pat = find_all_pat(13);
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_solicitud_ut'])) {

    if (empty($errors)) {

        $no_sesion = remove_junk($db->escape($_POST['no_sesion']));
        $fecha_sesion   = remove_junk($db->escape($_POST['fecha_sesion']));			
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));			
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));			

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
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-SCUT';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-SCUT';
        $carpeta = 'uploads/sesiones_comite/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['acta_sesion']['name'];
        $size = $_FILES['acta_sesion']['size'];
        $type = $_FILES['acta_sesion']['type'];
        $temp = $_FILES['acta_sesion']['tmp_name'];

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);

/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}

            $query = "INSERT INTO comite_transparencia (";
            $query .= "folio,fecha_sesion,no_sesion,acta_sesion,id_indicadores_pat,observaciones,id_user_creador,fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$fecha_sesion}','{$no_sesion}','{$name}','{$id_indicadores_pat}','{$observaciones}',{$id_user},NOW())";
			
            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";

			if ($db->query($query) && $db->query($query2)) {
                //sucess
                insertAccion($user['id_user'], '"' . $user['username'] . '" dió de alta la Sesión de Comite de Transparencia con Folio: -' . $folio . '-.', 1);
                $session->msg('s', " La Sesión de Comite de Transparencia con folio '{$folio}' ha sido agregado con éxito.");
                redirect('sesiones_comite_ut.php', false);
            } else {
                //failed
                $session->msg('d', ' No se pudo agregar la Sesión de Comite de Transparencia.');
                redirect('add_sesion_comite_ut.php', false);
            }
       
    } else {
        $session->msg("d", $errors);
        redirect('add_sesion_comite_ut.php', false);
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
                <span>Agregar Sesión del Comite</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_sesion_comite_ut.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_sesion">No. Sesión<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="no_sesion" placeholder="No.Sesión" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_sesion">Fecha de Sesión<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_sesion" required>
                        </div>
                    </div>
                    <div class="col-md-3">
					      <div class="form-group">
                            <label for="acta_sesion">Acta</label>
                            <input type="file" accept="application/pdf" class="form-control" name="acta_sesion" id="acta_sesion" required>
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
                </div>
				
                <div class="row">
		
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones"  cols="16" rows="6" ></textarea>
                        </div>
                    </div>

                   
                </div>

                
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="sesiones_comite_ut.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="add_solicitud_ut" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>