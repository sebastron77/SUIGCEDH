<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Proyectos';
require_once('includes/load.php');


$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 25) {
    page_require_level(25);
}
if ($nivel > 2  && $nivel < 25) :
    redirect('home.php');
endif;
if ($nivel > 25) :
    redirect('home.php');
endif;
$id_folio = last_id_folios();
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_proyectos'])) {

    if (empty($errors)) {

        $ejercicio = remove_junk($db->escape($_POST['ejercicio']));
        $mes   = remove_junk($db->escape($_POST['mes']));
		$fecha_completa = $ejercicio."-".$mes."-"."01";
        $no_pendientes_estudio   = remove_junk($db->escape($_POST['no_pendientes_estudio']));
		$listado_pendientes_estudio = remove_junk($db->escape($_POST['listado_pendientes_estudio']));
        $no_emision_resolucion   = remove_junk($db->escape($_POST['no_emision_resolucion']));
        $listado_emision_resolucion   = remove_junk($db->escape($_POST['listado_emision_resolucion']));
		$observaciones = remove_junk($db->escape($_POST['observaciones']));

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
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-PROY';

        $query = "INSERT INTO proyectos (";
        $query .= "folio, ejercicio	,mes,fecha_completa,no_pendientes_estudio,listado_pendientes_estudio,no_emision_resolucion,listado_emision_resolucion,observaciones,id_user_creador,fecha_creacion";
        $query .= ") VALUES (";
        $query .= "'{$folio}', '{$ejercicio}','{$mes}','{$fecha_completa}','{$no_pendientes_estudio}','{$listado_pendientes_estudio}','{$no_emision_resolucion}','{$listado_emision_resolucion}','{$observaciones}','{$id_user}',NOW());";        

        $query2 = "INSERT INTO folios (";
        $query2 .= "folio, contador";
        $query2 .= ") VALUES (";
        $query2 .= " '{$folio}','{$no_folio1}'";
        $query2 .= ")";

        if ($db->query($query) && $db->query($query2)) {
            //sucess
            insertAccion($user['id_user'], '"' . $user['username'] . '" dio de alta los datos de Proyectos con Folio: -' . $folio . '-.', 1);
            $session->msg('s', " Los datos de Proyectos con folio '{$folio}' ha sido agregada con éxito.");
            redirect('proyectos.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar los datos de Proyectos.');
            redirect('add_proyectos.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_proyectos.php', false);
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
                <span>Agregar Proyectos</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_proyectos.php" enctype="multipart/form-data">
                <div class="row">
				<div class="col-md-3">
                <div class="form-group">
                    <label for="ejercicio" class="control-label">Ejercicio</label>
                    <select class="form-control" name="ejercicio" id="ejercicio" required>
                        <option value="">Escoge una opción</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024" selected="selected">2024</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="mes" class="control-label">Mes</label>
                    <select class="form-control" name="mes" id="mes" required>
                        <option value="">Escoge una opción</option>
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>
                </div>
            </div>
            </div>
            <div class="row">
				               
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_pendientes_estudio">Total Pendientes de estudio para Resolución</label><br>
                            <input type="text" class="form-control" name="no_pendientes_estudio" value="0" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_emision_resolucion">Total de Emisión de Resolución</label><br>
                            <input type="text" class="form-control" name="no_emision_resolucion" value="0">
                        </div>
                    </div>
			</div>
            <div class="row">
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="listado_pendientes_estudio">Listado de Expedientes Pendientes de estudio para Resolución</label>
							<textarea class="form-control" name="listado_pendientes_estudio" cols="10" rows="5" required></textarea>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="listado_emision_resolucion">Listado de Expedientes de Emisión de Resolución</label>
							<textarea class="form-control" name="listado_emision_resolucion" cols="10" rows="5" ></textarea>
                        </div>
                    </div>
                    </div>
					
            <div class="row">
				<div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
							<textarea class="form-control" name="observaciones" cols="10" rows="4"></textarea>
                        </div>
                    </div>
			</div>
			
            </div>
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="proyectos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="add_proyectos" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>