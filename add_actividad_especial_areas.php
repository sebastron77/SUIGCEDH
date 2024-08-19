<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Agregar Actividad Especial ';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 4) {
    page_require_level_exacto(4);
}
if ($nivel_user == 9) {
    page_require_level_exacto(9);
}
if ($nivel_user == 10) {
    page_require_level_exacto(10);
}
if ($nivel_user == 12) {
    page_require_level_exacto(12);
}
if ($nivel_user == 22) {
    page_require_level_exacto(22);
}
if ($nivel_user == 24) {
    page_require_level_exacto(24);
}
if ($nivel_user > 2 && $nivel_user < 4) :
    redirect('home.php');
endif;
if ($nivel_user > 4 && $nivel_user <9) :
    redirect('home.php');
endif;
if ($nivel_user > 9 && $nivel_user <10) :
    redirect('home.php');
endif;
if ($nivel_user > 10 && $nivel_user <12) :
    redirect('home.php');
endif;
if ($nivel_user > 12 && $nivel_user <22) :
    redirect('home.php');
endif;
if ($nivel_user > 22 && $nivel_user <24) :
    redirect('home.php');
endif;

$id_folio = last_id_folios();
$id_actividades = last_id_table('actividades_especiales_areas', 'id_actividades_especiales_areas');
$area = isset($_GET['a']) ? $_GET['a'] : '0';
$inticadores_pat = find_all_pat_area($area,'actividades_especiales_areas');
$solicitud = find_by_solicitud($area);
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_actividad'])) {

    if (empty($errors)) {

        $tema_actividad = remove_junk($db->escape($_POST['tema_actividad']));
        $fecha_actividad   = remove_junk($db->escape($_POST['fecha_actividad']));
        $quien_atendio   = remove_junk($db->escape($_POST['quien_atendio']));
        $descripcion   = remove_junk($db->escape($_POST['descripcion']));
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));
		
        //Suma el valor del id anterior + 1, para generar ese id para el nuevo resguardo
        //La variable $no_folio sirve para el numero de folio
        if (count($id_actividades) == 0) {
            $nuevo_id_actividades = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_actividades as $nuevo) {
                $nuevo_id_actividades = (int) $nuevo['id_actividades_especiales_areas'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['id_actividades_especiales_areas'] + 1);
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

		$folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-ACTESP';
        $carpeta = 'uploads/actividades_especiales/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['adjunto']['name'];
        $size = $_FILES['adjunto']['size'];
        $type = $_FILES['adjunto']['type'];
        $temp = $_FILES['adjunto']['tmp_name'];
		$move =  move_uploaded_file($temp, $carpeta . "/" . $name);
		/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}

        
            $query = "INSERT INTO actividades_especiales_areas (";
            $query .= "folio,id_area_responsable,fecha_actividad,tema_actividad,quien_atendio,descripcion,documento,id_indicadores_pat,id_user_creador,fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$folio}',{$area},'{$fecha_actividad}','{$tema_actividad}','{$quien_atendio}','{$descripcion}','{$name}','{$id_indicadores_pat}','{$id_user}',NOW()); ";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";

            if ($db->query($query) && $db->query($query2)) {
                //sucess
                insertAccion($user['id_user'], '"' . $user['username'] . '" dio de alta una Actividad Especial de Folio: -' . $folio . '-.', 1);
                $session->msg('s', " La Actividad Especial con folio '{$folio}' ha sido agregado con éxito.");
                redirect('actividad_especial_areas.php?a='.$area, false);
            } else {
                //failed
                $session->msg('d', ' No se pudo agregar la Actividad Especial.');
                redirect('add_actividad_especial_areas.php?a?='.$area, false);
            }
       
    } else {
        $session->msg("d", $errors);
        redirect('add_actividad_especial_areas.php?a='.$area, false);
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
                <span>Agregar Actividad Especial de la <?php echo $solicitud['nombre_area'];?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_actividad_especial_areas.php?a=<?php echo $area?>" enctype="multipart/form-data">
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
                            </select
                        </div>
                    </div>
                    
                </div>
                           
				  <div class="row">
					 <div class="col-md-5">
                        <div class="form-group">
                            <label for="descripcion">Descripción Actividad</label>
                            <textarea class="form-control" name="descripcion" cols="10" rows="3" required ></textarea>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="adjunto">Adjuntar archivo</label>
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto" required>
                        </div>
                    </div>
			   </div>

				
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="actividad_especial_areas.php?a=<?php echo $area?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="add_actividad" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>