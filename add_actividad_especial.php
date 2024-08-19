<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Agregar Actividad Especial';
require_once('includes/load.php');

$cat_ejes = find_all('cat_ejes_estrategicos');
$cat_agendas = find_all('cat_agendas');

$id_folio = last_id_folios();
$id_actividades = last_id_table('actividades_especiales', 'id_actividades_especiales');

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
if ($nivel >7  && $nivel < 15) :
    redirect('home.php');
endif;
if ($nivel > 15 ) :
    redirect('home.php');
endif;
$inticadores_pat = find_all_pat(46);
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_actividad'])) {

    if (empty($errors)) {

        $fecha_actividad = remove_junk($db->escape($_POST['fecha_actividad']));
        $tema_actividad   = remove_junk($db->escape($_POST['tema_actividad']));
        $lugar_actividad   = remove_junk($db->escape($_POST['lugar_actividad']));
        $id_cat_ejes_estrategicos   = remove_junk($db->escape($_POST['id_cat_ejes_estrategicos']));
        $id_cat_agendas   = remove_junk($db->escape($_POST['id_cat_agendas']));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
        $asistentes_otros = remove_junk($db->escape($_POST['asistentes_otros']));
        $asistentes_nobinario = remove_junk($db->escape($_POST['asistentes_nobinario']));
        $asistentes_mujeres = remove_junk($db->escape($_POST['asistentes_mujeres']));
        $asistentes_hombres = remove_junk($db->escape($_POST['asistentes_hombres']));
        $id_indicadores_pat = remove_junk($db->escape($_POST['id_indicadores_pat']));
		
        //Suma el valor del id anterior + 1, para generar ese id para el nuevo resguardo
        //La variable $no_folio sirve para el numero de folio
        if (count($id_actividades) == 0) {
            $nuevo_id_actividades = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_actividades as $nuevo) {
                $nuevo_id_actividades = (int) $nuevo['id_actividades_especiales'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['id_actividades_especiales'] + 1);
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
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-ACTESP';

        
            $query = "INSERT INTO actividades_especiales (";
            $query .= "folio,fecha_actividad,tema_actividad,lugar_actividad,id_cat_ejes_estrategicos,id_cat_agendas,observaciones,asistentes_hombres,asistentes_mujeres,asistentes_nobinario,asistentes_otros,id_indicadores_pat,user_creador,fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$fecha_actividad}','{$tema_actividad}','{$lugar_actividad}','{$id_cat_ejes_estrategicos}','{$id_cat_agendas}','{$observaciones}','{$asistentes_hombres}','{$asistentes_mujeres}','{$asistentes_nobinario}','{$asistentes_otros}','{$id_indicadores_pat}','{$id_user}',NOW()); ";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";

            if ($db->query($query) && $db->query($query2)) {
                //sucess
                insertAccion($user['id_user'], '"' . $user['username'] . '" dio de alta una Actividad Especial de Folio: -' . $folio . '-.', 1);
                $session->msg('s', " La Actividad Especial con folio '{$folio}' ha sido agregado con éxito.");
                redirect('actividad_especial.php', false);
            } else {
                //failed
                $session->msg('d', ' No se pudo agregar la Actividad Especial.');
                redirect('add_actividad_especial.php', false);
            }
       
    } else {
        $session->msg("d", $errors);
        redirect('add_actividad_especial.php', false);
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
                <span>Agregar Actividad Especial</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_actividad_especial.php" >
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_actividad">Fecha de la Actividad<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_actividad" required>
                        </div>
                    </div>					
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tema_actividad">Tema de la Actividad<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="tema_actividad" placeholder="Tema Actividad" required>
                        </div>
                    </div>                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="lugar_actividad">Lugar de la Actividad<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="lugar_actividad" placeholder="Lugar de Actividad" required>
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
                            </select
                        </div>
                    </div>
                    
                </div>
                           
				  <div class="row">
					<div class="col-md-3">
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
					<div class="col-md-3">
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
					<div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label><br>
                            <textarea name="observaciones" class="form-control" id="observaciones" cols="50" rows="2"></textarea>
                        </div>
                    </div>
			   </div>

				<div class="row">
				 <h3 style="font-weight:bold;">
                    <span class="material-symbols-outlined">checklist</span>
                    Asistentes
                </h3>
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">Hombres</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_hombres" value="0" >
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">Mujeres</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_mujeres" value="0" >
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">No Binarios</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_nobinario" value="0" >
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">Otros</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_otros" value="0" >
                        </div>
                    </div>
				</div>
					
                                                  
               
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="actividad_especial.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="add_actividad" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>