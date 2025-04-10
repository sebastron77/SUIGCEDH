<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Diseño';
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
if ($nivel >7  && $nivel < 15) :
    redirect('home.php');
endif;
if ($nivel > 15 ) :
    redirect('home.php');
endif;

$areas = find_all_order('area', 'jerarquia');
$id_folio = last_id_folios();
$disenios = find_all('cat_tipo_disenios');
$id_disenios = last_id_table('disenios', 'id_disenios');
$inticadores_pat = find_all_pat(46);
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_disenio'])) {

    if (empty($errors)) {

        $fecha_solicitud = remove_junk($db->escape($_POST['fecha_solicitud']));
        $fecha_entrega   = remove_junk($db->escape($_POST['fecha_entrega']));
        $id_cat_tipo_disenios   = remove_junk($db->escape($_POST['id_cat_tipo_disenios']));
        $tema_disenio   = remove_junk($db->escape($_POST['tema_disenio']));
        $id_area_solicitud   = remove_junk($db->escape($_POST['id_area_solicitud']));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
        $id_indicadores_pat = remove_junk($db->escape($_POST['id_indicadores_pat']));
		
        //Suma el valor del id anterior + 1, para generar ese id para el nuevo resguardo
        //La variable $no_folio sirve para el numero de folio
        if (count($id_disenios) == 0) {
            $nuevo_id_disenios = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_disenios as $nuevo) {
                $nuevo_id_disenios = (int) $nuevo['id_disenios'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['id_disenios'] + 1);
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
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-DIS';

        
            $query = "INSERT INTO disenios (";
            $query .= "folio,fecha_solicitud,fecha_entrega,id_cat_tipo_disenios,tema_disenio,id_area_solicitud,observaciones,id_indicadores_pat,user_creador,fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$fecha_solicitud}','{$fecha_entrega}','{$id_cat_tipo_disenios}','{$tema_disenio}','{$id_area_solicitud}','{$observaciones}','{$id_indicadores_pat}','{$id_user}',NOW()); ";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";

            if ($db->query($query) && $db->query($query2)) {
                //sucess
                insertAccion($user['id_user'], '"' . $user['username'] . '" dio de alta un Diseño de Folio: -' . $folio . '-.', 1);
                $session->msg('s', " El Diseño con folio '{$folio}' ha sido agregado con éxito.");
                redirect('disenios.php', false);
            } else {
                //failed
                $session->msg('d', ' No se pudo agregar el diseño.');
                redirect('add_disenio.php', false);
            }
       
    } else {
        $session->msg("d", $errors);
        redirect('add_disenio.php', false);
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
                <span>Agregar Diseño</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_disenio.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_solicitud">Fecha de Solicitud<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_solicitud" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_entrega">Fecha de Entrega<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_entrega" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_tipo_disenios">Tipo de Diseño <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="id_cat_tipo_disenios" required>
                                <option value="">Escoge una opción</option>
								<?php foreach ($disenios as $datos) : 
									if($datos['estatus']==1):
									?>
                                    <option value="<?php echo $datos['id_cat_tipo_disenios']; ?>"><?php echo ucwords($datos['descripcion']); ?></option>
								
                                <?php endif; endforeach; ?>                                    
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tema_disenio">Tema de Diseño<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="tema_disenio" placeholder="Tema Diseño" required>
                        </div>
                    </div>
                    
                </div>

                <div class="row">
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area_solicitud">Área que solicita</label>
                            <select class="form-control" name="id_area_solicitud">
							<option value="">Selecciona una Área</option>
                                <?php foreach ($areas as $area) : ?>
                                    <option value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
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
            

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label><br>
                            <textarea name="observaciones" class="form-control" id="observaciones" cols="50" rows="5"></textarea>
                        </div>
                    </div>
                   
                </div>
               
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="disenios.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="add_disenio" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>