
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Supervisión de Buzones';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 6) {
    page_require_level_exacto(6);
}
if ($nivel_user == 24) {
    page_require_level_exacto(24);
}

if ($nivel_user > 2 && $nivel_user < 6) :
    redirect('home.php');
endif;
if ($nivel_user > 24 && $nivel_user < 53) :
    redirect('home.php');
endif;


$id_folio = last_id_folios_general();

$inticadores_pat = find_all_pat_area(39,'supervision_buzones');
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_supervision_buzones'])) {

    if (empty($errors)) {
        $fecha_supervision   = remove_junk($db->escape($_POST['fecha_supervision']));
        $lugar_supervision   = remove_junk($db->escape($_POST['lugar_supervision']));
        $numero_quejas   = remove_junk(($db->escape($_POST['numero_quejas'])));
        $quien_atendio   = remove_junk(($db->escape($_POST['quien_atendio'])));
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
        // Se crea el folio de capacitacion
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-SUPBZ';

        
		
	 $query = "INSERT INTO supervision_buzones (";
            $query .= "folio,fecha_supervision,lugar_supervision,numero_quejas,quien_atendio,observaciones,id_indicadores_pat,id_user_creador,fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$fecha_supervision}','{$lugar_supervision}',{$numero_quejas},'{$quien_atendio}','{$observaciones}','{$id_indicadores_pat}','{$id_user}',NOW()); ";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";

            if ($db->query($query) && $db->query($query2)) {
                //sucess
                insertAccion($user['id_user'], '"' . $user['username'] . '" dio de alta una Supervisión de Buzones. Folio: ' . $folio . '.', 1);
                $session->msg('s', " LaSupervisión de Buzones con folio '{$folio}' ha sido agregado con éxito.");
                redirect('supervision_buzones.php', false);
            } else {
                //failed
                $session->msg('d', ' No se pudo agregar la Actividad Especial.');
                redirect('supervision_buzones.php', false);
            }      
    } else {
        $session->msg("d", $errors);
        redirect('supervision_buzones.php', false);
    }
}
?>

<?php 
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Supervisión de Buzones</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_supervision_buzones.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_supervision">Fecha Supervisión</label><br>
                            <input type="date" class="form-control" name="fecha_supervision" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lugar_supervision">Lugar de Supervisión</label>
                            <input type="text" class="form-control" name="lugar_supervision" required>
                        </div>
                    </div>
					
					 <div class="col-md-2">
                        <div class="form-group">
                            <label for="numero_quejas">No. de Quejas Captadas</label>
                            <input type="number" min="0" class="form-control" max="1000" name="numero_quejas" required>
                        </div>
                    </div>
					 <div class="col-md-4">
                        <div class="form-group">
                            <label for="quien_atendio">¿Quién Atendió?<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="quien_atendio" required>
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
			
                            
	
					<div class="col-md-5">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="5"></textarea>
                        </div>
                    </div>
				</div>

      

              
                <div class="form-group clearfix">
                    <a href="supervision_buzones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_supervision_buzones" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>