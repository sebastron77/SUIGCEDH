<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Entrega de Insumos';
require_once('includes/load.php');

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 4) {
    page_require_level_exacto(4);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 9) {
    page_require_level_exacto(9);
}
if ($nivel_user == 22) {
    page_require_level_exacto(22);
}

if ($nivel_user > 2 && $nivel_user < 4) :
    redirect('home.php');
endif;
if ($nivel_user > 4 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 22) :
    redirect('home.php');
endif;
if ($nivel_user > 22 && $nivel_user < 53) :
    redirect('home.php');
endif;

$id_folio = last_id_folios();
$inticadores_pat = find_all_pat_area(16,'insumos');
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_insumos'])) {

    if (empty($errors)) {
        
        $fecha_entrega   = remove_junk($db->escape($_POST['fecha_entrega']));			
        $tema_actividad   = remove_junk($db->escape($_POST['tema_actividad']));			
        $total_insumos_entregado   = remove_junk($db->escape($_POST['total_insumos_entregado']));			
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
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-INS';


            $query = "INSERT INTO insumos (";
            $query .= "folio,fecha_entrega,tema_actividad,total_insumos_entregado,observaciones,id_indicadores_pat,id_user_creador,fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$fecha_entrega}','{$tema_actividad}','{$total_insumos_entregado}','{$observaciones}','{$id_indicadores_pat}',{$id_user},NOW())";
			
            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";

			if ($db->query($query) && $db->query($query2)) {
                //sucess
                insertAccion($user['id_user'], '"' . $user['username'] . '" dió de alta la Entrega de Insumos con Folio: -' . $folio . '-.', 1);
                $session->msg('s', " La Entrega de Insumos con folio '{$folio}' ha sido agregado con éxito.");
                redirect('insumos.php', false);
            } else {
                //failed
                $session->msg('d', ' No se pudo agregar la Entrega de Insumos.');
                redirect('add_insumos.php', false);
            }
       
    } else {
        $session->msg("d", $errors);
        redirect('insumos.php', false);
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
                <span>Agregar Entrega de Insumos</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_insumos.php" >
                <div class="row">
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_entrega">Fecha de Entrega<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_entrega" id="fecha_entrega" required>
                        </div>
                    </div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="desktop">Nombre de la Actividad</label>
							<input class="form-control monto" type="text" id="tema_actividad" name="tema_actividad" required>
						</div>                
					</div>                
					<div class="col-md-2">
						<div class="form-group">
							<label for="desktop">Total de Insumos Entregados</label>
							<input class="form-control monto" type="number" id="total_insumos_entregado" name="total_insumos_entregado" required>
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
                        <a href="insumos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="add_insumos" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>