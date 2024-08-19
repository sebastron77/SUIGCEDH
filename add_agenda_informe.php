<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Inorme';
require_once('includes/load.php');
$user = current_user();
$id_user = $user['id_user'];
$id_folio = last_id_folios();
$nivel_user = $user['user_level'];
$cat_ejes = find_all('cat_ejes_estrategicos');
$cat_agendas = find_all('cat_agendas');
$id_table = last_id_table('informes_especiales','id_informes_especiales');


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

if (isset($_POST['add_agenda_informe'])) {
	 
	if (empty($errors)) {
        $ejercicio   = remove_junk($db->escape($_POST['ejercicio']));
        $tipo_informe   = remove_junk($db->escape($_POST['tipo_informe']));
        $nombre_informe   = remove_junk($db->escape($_POST['nombre_informe']));
        $id_cat_ejes_estrategicos   = remove_junk($db->escape($_POST['id_cat_ejes_estrategicos']));
        $id_cat_agendas   = remove_junk($db->escape($_POST['id_cat_agendas']));
        $descripcion   = remove_junk($db->escape($_POST['descripcion']));
        $link_informe   = remove_junk($db->escape($_POST['link_informe']));
        $no_isbn   = remove_junk($db->escape($_POST['no_isbn']));
        $derecho_involucrado   = remove_junk($db->escape($_POST['derecho_involucrado']));
		$fecha_presentacion   = remove_junk($db->escape($_POST['fecha_presentacion']));
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));
		
		//Suma el valor del id anterior + 1, para generar ese id para el nuevo resguardo
        //La variable $no_folio sirve para el numero de folio
        if (count($id_table) == 0) {
            $nuevo_id= 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_table as $nuevo) {
                $nuevo_id = (int) $nuevo['id_informes_especiales'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['id_informes_especiales'] + 1);
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
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-INFES';
		
		
		$query = "INSERT INTO informes_especiales (";
		$query .= "folio,ejercicio,tipo_informe,id_cat_ejes_estrategicos,id_cat_agendas,nombre_informe,derecho_involucrado,descripcion,link_informe,no_isbn,fecha_presentacion,id_indicadores_pat, id_user_creador,fecha_creacion ";			
		$query .= ") VALUES (";
		$query .= " '{$folio}',{$ejercicio},'{$tipo_informe}','{$id_cat_ejes_estrategicos}','{$id_cat_agendas}','{$nombre_informe}','{$derecho_involucrado}','{$descripcion}','{$link_informe}','{$no_isbn}',{$fecha_presentacion},{$id_indicadores_pat},{$id_user},NOW() ";		
		$query .= ")";

		$query2 = "INSERT INTO folios (";
		$query2 .= "folio, contador";
		$query2 .= ") VALUES (";
		$query2 .= " '{$folio}','{$no_folio1}'";
		$query2 .= ")";
		
		 if ($db->query($query) && $db->query($query2)) {
            //sucess
            $session->msg('s', " El registro se ha agregado con éxito.");
            insertAccion($user['id_user'], '"'.$user['username'].'" agregó registro de Informe Especial, Folio: '.$folio.'.', 1);
            redirect('agenda_informes.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar el registro.');
            redirect('add_agenda_informe.php', false);
        }
		
	}else {
        $session->msg("d", ' No se pudo agregar el registros.'.$errors);
        redirect('add_agenda_informe.php', false);
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
                <span>Agregar Informe Especial</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_agenda_informe.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="ejercicio">Ejercicio</label>
                            <select class="form-control" name="ejercicio" required>
                                <option value="">Escoge una opción</option>
								<?php  for($i = (date("Y")-2); $i <(date("Y")+3); $i++){ ?>
									<option value="<?php echo  $i;?>"><?php echo  $i;?></option>
								<?php  } ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_informe">Tipo Informme</label>
                            <select class="form-control" name="tipo_informe" required>
                                <option value="">Escoge una opción</option>
                                <option value="Especial">Especial</option>
                                <option value="General">General</option>
                            </select>
                        </div>
                    </div>
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_presentacion">Fecha de Publicación<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_presentacion" value="<?php echo ($e_detalles['fecha_presentacion']); ?>" required>
                        </div>
                    </div>
					<div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre_informe">Nombre Informe</label>
                            <input type="text" class="form-control" name="nombre_informe" required>
                        </div>
                    </div>
					
                   </div>
					
                    <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="eje">Eje Estratégico</label>
                            <select class="form-control" name="id_cat_ejes_estrategicos" required>
                                <option value="">Escoge una opción</option>
								<?php foreach ($cat_ejes as $ejes) : ?>
                                    <option value="<?php echo $ejes['id_cat_ejes_estrategicos']; ?>"><?php echo ucwords($ejes['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="agenda">Agenda</label>
                            <select class="form-control" name="id_cat_agendas" required>
                                <option value="">Escoge una opción</option>
								<?php foreach ($cat_agendas as $agendas) : ?>
                                    <option value="<?php echo $agendas['id_cat_agendas']; ?>"><?php echo ucwords($agendas['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="link_informe">Hipervínculo de Publicación</label>
                            <input type="text" class="form-control" name="link_informe" required>
                        </div>
                    </div>
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="isbn">ISBN</label>
                            <input type="text" class="form-control" name="no_isbn" >
                        </div>
                    </div>
					 </div>
					 
                <div class="row">
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
							<textarea class="form-control" name="descripcion" cols="10" rows="4"></textarea>
                        </div>
                    </div>	
						<div class="col-md-4">
                        <div class="form-group">
                            <label for="derecho_involucrado">Derecho Involucrado</label>
							<textarea class="form-control" name="derecho_involucrado" cols="10" rows="4"></textarea>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" >
                                <option value="">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                        </div>					
                </div>
				
				
                <div class="form-group clearfix">
                    <a href="agenda_informes.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_agenda_informe" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>