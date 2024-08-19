<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar';
require_once('includes/load.php');
$user = current_user();
$detalle = $user['id_user'];
$id_folio = last_id_folios();
$nivel_user = $user['user_level'];
$cat_ejes = find_all('cat_ejes_estrategicos');
$cat_agendas = find_all('cat_agendas');
$id_engtregable = last_id_entregable();


if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 17) {
    page_require_level_exacto(17);
}
if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 17) :
    redirect('home.php');
endif;

if ($nivel_user > 17 && $nivel_user < 21) :
    redirect('home.php');
endif;

$inticadores_pat = find_all_pat(18);
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_agenda_entregable'])) {
	 $req_fields = array('tipo_estregable', 'nombre_entragable', 'id_cat_ejes_estrategicos', 'id_cat_agendas');
    validate_fields($req_fields);
	if (empty($errors)) {
        $tipo_estregable   = remove_junk($db->escape($_POST['tipo_estregable']));
        $nombre_entragable   = remove_junk($db->escape($_POST['nombre_entragable']));
        $id_cat_ejes_estrategicos   = remove_junk($db->escape($_POST['id_cat_ejes_estrategicos']));
        $id_cat_agendas   = remove_junk($db->escape($_POST['id_cat_agendas']));
        $descripcion   = remove_junk($db->escape($_POST['descripcion']));
        $liga_acceso   = remove_junk($db->escape($_POST['liga_acceso']));
        $no_isbn   = remove_junk($db->escape($_POST['no_isbn']));
		$fecha_presentacion   = remove_junk($db->escape($_POST['fecha_presentacion']));
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));
		
		//Suma el valor del id anterior + 1, para generar ese id para el nuevo resguardo
        //La variable $no_folio sirve para el numero de folio
        if (count($id_engtregable) == 0) {
            $nuevo_id_engtregable = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_engtregable as $nuevo) {
                $nuevo_id_engtregable = (int) $nuevo['id_entregables'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['id_entregables'] + 1);
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
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-ENT';
		
		
		$query = "INSERT INTO entregables (";
		$query .= "folio,tipo_estregable,nombre_entragable,id_cat_ejes_estrategicos,id_cat_agendas,descripcion,liga_acceso,no_isbn,fecha_presentacion,id_indicadores_pat, id_user_creador,fecha_creacion ";			
		$query .= ") VALUES (";
		$query .= " '{$folio}','{$tipo_estregable}','{$nombre_entragable}','{$id_cat_ejes_estrategicos}','{$id_cat_agendas}','{$descripcion}','{$liga_acceso}','{$no_isbn}','{$fecha_presentacion}','{$id_indicadores_pat}',{$detalle},NOW() ";		
		$query .= ")";

		$query2 = "INSERT INTO folios (";
		$query2 .= "folio, contador";
		$query2 .= ") VALUES (";
		$query2 .= " '{$folio}','{$no_folio1}'";
		$query2 .= ")";
		
		 if ($db->query($query) && $db->query($query2)) {
            //sucess
            $session->msg('s', " El registro se ha agregado con éxito.");
            insertAccion($user['id_user'], '"'.$user['username'].'" agregó registro de Entregables, Folio: '.$folio.'.', 1);
            redirect('agenda_entregables.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar el registro.');
            redirect('add_agenda_entregable.php', false);
        }
		
	}else {
        $session->msg("d", ' No se pudo agregar el registros.'.$errors);
        redirect('add_agenda_entregable.php', false);
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
                <span>Agregar Entregable</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_agenda_entregable.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo">Tipo Entregable</label>
                            <select class="form-control" name="tipo_estregable" required>
                                <option value="Escoge una opción">Escoge una opción</option>
                                <option value="Interno">Interno</option>
                                <option value="Externo">Externo</option>
                            </select>
                        </div>
                    </div>
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_presentacion">Fecha de Publicación<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_presentacion"  required>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" name="nombre_entragable" required>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="isbn">ISBN</label>
                            <input type="text" class="form-control" name="no_isbn" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="eje">Eje Estratégico</label>
                            <select class="form-control" name="id_cat_ejes_estrategicos" required>
                                <option value="Escoge una opción">Escoge una opción</option>
								<?php foreach ($cat_ejes as $ejes) : ?>
                                    <option value="<?php echo $ejes['id_cat_ejes_estrategicos']; ?>"><?php echo ucwords($ejes['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="agenda">Agenda</label>
                            <select class="form-control" name="id_cat_agendas" required>
                                <option value="Escoge una opción">Escoge una opción</option>
								<?php foreach ($cat_agendas as $agendas) : ?>
                                    <option value="<?php echo $agendas['id_cat_agendas']; ?>"><?php echo ucwords($agendas['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" >
                                <option value="">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option   value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                        </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
							<textarea class="form-control" name="descripcion" cols="10" rows="4"></textarea>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="acceso">Hipervínculo de Publicación </label>
							<textarea class="form-control" name="liga_acceso" cols="10" rows="4"></textarea>
                        </div>
                    </div>

                    
                </div>
                <div class="form-group clearfix">
                    <a href="agenda_entregables.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_agenda_entregable" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>