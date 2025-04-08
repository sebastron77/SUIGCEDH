<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar';
require_once('includes/load.php');
$user = current_user();
$detalle = $user['id_user'];
$id_folio = last_id_folios();
$nivel_user = $user['user_level'];
$id_consejo = last_id_consejo();
// $inticadores_pat = find_all_pat(10);

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 3) {
    page_require_level_exacto(3);
}
if ($nivel_user == 5) {
    redirect('home.php');
}
if ($nivel_user == 7) {
    page_require_level(7);
}
if ($nivel_user == 21) {
    page_require_level_exacto(21);
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

?>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_consejo'])) {

    $req_fields = array('num_sesion', 'tipo_sesion', 'fecha_sesion', 'hora', 'lugar', 'num_asistentes');
    validate_fields($req_fields);

    if (empty($errors)) {
        $num_sesion   = remove_junk($db->escape($_POST['num_sesion']));
        $tipo_sesion   = remove_junk($db->escape($_POST['tipo_sesion']));
        $fecha_sesion   = remove_junk($db->escape($_POST['fecha_sesion']));
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));
        $hora   = remove_junk($db->escape($_POST['hora']));
        $lugar   = remove_junk(upper_case($db->escape($_POST['lugar'])));
        $num_asistentes   = remove_junk(upper_case($db->escape($_POST['num_asistentes'])));

		//Suma el valor del id anterior + 1, para generar ese id para el nuevo resguardo
        //La variable $no_folio sirve para el numero de folio
        if (count($id_consejo) == 0) {
            $nuevo_id_consejo = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_consejo as $nuevo) {
                $nuevo_id_consejo = (int) $nuevo['id_acta_consejo'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['id_acta_consejo'] + 1);
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
        // Se crea el folio orientacion
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-CONS';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-CONS';
        $carpeta = 'uploads/consejo/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name2 = $_FILES['acta_acuerdos']['name'];
        $size2 = $_FILES['acta_acuerdos']['size'];
        $type2 = $_FILES['acta_acuerdos']['type'];
        $temp2 = $_FILES['acta_acuerdos']['tmp_name'];

        $move2 =  move_uploaded_file($temp2, $carpeta . "/" . $name2);
		/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}


		$query = "INSERT INTO consejo (";
		$query .= "folio,num_sesion,tipo_sesion,fecha_sesion,hora,lugar,num_asistentes, id_user_creador,fecha_creacion,id_indicadores_pat ";		
		if($name2!= ''){ $query .= ",acta_acuerdos ";}
		$query .= ") VALUES (";
		$query .= " '{$folio}','{$num_sesion}','{$tipo_sesion}','{$fecha_sesion}','{$hora}','{$lugar}','{$num_asistentes}',{$detalle},NOW(),{$id_indicadores_pat} ";		
		if($name2!= ''){ $query .= ",'{$name2}' ";}
		$query .= ")";

		$query2 = "INSERT INTO folios (";
		$query2 .= "folio, contador";
		$query2 .= ") VALUES (";
		$query2 .= " '{$folio}','{$no_folio1}'";
		$query2 .= ")";

      

        if ($db->query($query) && $db->query($query2)) {
            //sucess
            $session->msg('s', " El registro se ha agregado con éxito.");
            insertAccion($user['id_user'], '"'.$user['username'].'" agregó registro en consejo, Folio: '.$folio.'.', 1);
            redirect('consejo.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar el registro.');
            redirect('add_consejo.php', false);
        }
    } else {
        $session->msg("d", ' No se pudo agregar el registros.'.$errors);
        redirect('add_consejo.php', false);
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
                <span>Agregar</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_consejo.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="num_sesion"># de Sesión</label>
                            <input type="text" class="form-control" name="num_sesion" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_sesion">Tipo de sesión</label>
                            <select class="form-control" name="tipo_sesion">
                                <option value="Escoge una opción">Escoge una opción</option>
                                <option value="Ordinaria">Ordinaria</option>
                                <option value="Extraordinaria">Extraordinaria</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_sesion">Fecha de Sesión</label>
                            <input type="date" class="form-control" name="fecha_sesion" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="hora">Hora</label>
                            <input type="time" class="form-control" name="hora" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="num_asistentes">Número de asistentes</label>
                            <input type="number" class="form-control" min="1" name="num_asistentes" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lugar">Lugar</label>
                            <input type="text" class="form-control" name="lugar" required>
                        </div>
                    </div> 
<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" >
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option   value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="acta_acuerdos">Acta de acuerdos (firmada)</label>
                            <input type="file" accept="application/pdf" class="form-control" name="acta_acuerdos" id="acta_acuerdos">
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="consejo.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_consejo" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>