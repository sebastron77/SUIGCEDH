<?php
$page_title = 'Agregar Sistemas y Herramientas Informáticos';
require_once('includes/load.php');

$user = current_user();
$id_folio = last_id_folios();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];
$inticadores_pat = find_all_pat(1);
if ($nivel_user <= 2) {
    page_require_level(2);
}

if ($nivel_user == 13) {
    page_require_level_exacto(13);
}
if ($nivel_user > 2 && $nivel_user < 13) :
    redirect('home.php');
endif;
if ($nivel_user > 13 && $nivel_user < 53) :
    redirect('home.php');
endif;
?>
<?php
if (isset($_POST['add_herramientas_sistemas'])) {

    $fecha_inicio_operacion = remove_junk($db->escape($_POST['fecha_inicio_operacion']));
    $nombre_aplicativo = remove_junk($db->escape($_POST['nombre_aplicativo']));
    $descripcion_aplicativo = remove_junk($db->escape($_POST['descripcion_aplicativo']));
    $status = remove_junk($db->escape($_POST['status']));
    $id_indicadores_pat = remove_junk($db->escape($_POST['id_indicadores_pat']));

	 if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int)$nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int)$nuevo['contador'] + 1);
            }
        }
        // Se crea el número de folio
        $year = date("Y");
        // Se crea el folio de convenio
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-HERSIS';
		

    $query  = "INSERT INTO herramientas_sistemas (";
    $query .= "folio, fecha_inicio_operacion, nombre_aplicativo, descripcion_aplicativo, status,id_indicadores_pat, id_user_creador, fecha_creacion";
    $query .= ") VALUES (";
    $query .= " '{$folio}', '{$fecha_inicio_operacion}', '{$nombre_aplicativo}', '{$descripcion_aplicativo}', '{$status}','{$id_indicadores_pat}', '{$id_user}', NOW()";
    $query .= ")";
	
	 $query2 = "INSERT INTO folios (folio, contador) VALUES ( '{$folio}','{$no_folio1}')";
	 
    if ($db->query($query) && $db->query($query2)) {
        //sucess
        $session->msg('s', "¡Registro creado con éxito! ");
		insertAccion($user['id_user'], '"' . $user['username'] . '" dio de alta un Sistemas y Herramientas Informáticos del área de sistemas. Folio:'.$folio, 1);
        redirect('herramientas_sistemas.php', false);
    } else {
        //failed
        $session->msg('d', 'Desafortunadamente no se pudo crear el registro.');
        redirect('add_herramientas_sistemas.php', false);
    }
}
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar SISTEMAS Y HERRAMIENTAS INFORMÁTICOS</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_herramientas_sistemas.php" enctype="multipart/form-data">
                <div class="row">
				
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_inicio_operacion">Fecha de Inicio de Operaciones</label>
                            <input type="date" class="form-control" name="fecha_inicio_operacion" required>
                        </div>
                    </div>
					
					   <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_aplicativo">Nombre Sistema</label>
                            <input type="text" class="form-control" name="nombre_aplicativo" required>
                        </div>
                    </div>
					
					 <div class="col-md-5">
                        <div class="form-group">
                            <label for="descripcion_aplicativo">Concepto y Utilidad</label>
                            <textarea class="form-control" name="descripcion_aplicativo" cols="10" rows="3"></textarea>
                        </div>
                    </div>
					
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Estado que se encuentra</label>
                            <select class="form-control form-select" name="status" required>
                                <option value="">Escoge una opción</option>                                
                                    <option value="Vigente">Vigente</option>
                                    <option value="Vigente">Histório</option>
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
                </div>
                
                </div>

        <div class="form-group clearfix">
            <a href="herramientas_sistemas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="add_herramientas_sistemas" class="btn btn-info">Guardar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>