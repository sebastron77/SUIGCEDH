<?php
$page_title = 'Agregar Visita';
require_once('includes/load.php');

$user = current_user();
$cat_areas =find_all_order('area','jerarquia');
$cat_tipo_accion =find_all_order('cat_tipo_accion_sistemas','descripcion');
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];
$inticadores_pat = find_all_pat_area(1,'acciones_sistemas');

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
if (isset($_POST['add_acciones_sistemas'])) {

    $id_cat_tipo_accion_sistemas = remove_junk($db->escape($_POST['id_cat_tipo_accion_sistemas']));
    $id_area = remove_junk($db->escape($_POST['id_area']));
    $fecha_accion = remove_junk($db->escape($_POST['fecha_accion']));
    $descripcion_accion = remove_junk($db->escape($_POST['descripcion_accion']));
    $quien_atendio = remove_junk($db->escape($_POST['quien_atendio']));
    $id_indicadores_pat = remove_junk($db->escape($_POST['id_indicadores_pat']));

    $query  = "INSERT INTO acciones_sistemas (";
    $query .= "id_cat_tipo_accion_sistemas, id_area, fecha_accion, descripcion_accion, quien_atendio, id_indicadores_pat,id_creador, fecha_creacion";
    $query .= ") VALUES (";
    $query .= " '{$id_cat_tipo_accion_sistemas}', '{$id_area}', '{$fecha_accion}', '{$descripcion_accion}', '{$quien_atendio}', {$id_indicadores_pat},'{$id_user}', NOW()";
    $query .= ")";
    if ($db->query($query)) {
        //sucess
        $session->msg('s', "¡Registro creado con éxito! ");
		insertAccion($user['id_user'], '"' . $user['username'] . '" dio de alta una Acción Relevante del área de sistemas.', 1);
        redirect('add_acciones_sistemas.php', false);
    } else {
        //failed
        $session->msg('d', 'Desafortunadamente no se pudo crear el registro.');
        redirect('add_acciones_sistemas.php', false);
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
                <span>Agregar Acción Relevante</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_acciones_sistemas.php" enctype="multipart/form-data">
                <div class="row">
				
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_accion">Fecha de la Acción</label>
                            <input type="date" class="form-control" name="fecha_accion" required>
                        </div>
                    </div>
					
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_tipo_accion_sistemas">Tipo de Acción</label>
                            <select class="form-control form-select" name="id_cat_tipo_accion_sistemas" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_tipo_accion as $datos) : 
										if((int)$datos['id_cat_tipo_accion_sistemas'] > 0 && $datos['estatus'] ==='1' ):
								?>
                                    <option value="<?php echo $datos['id_cat_tipo_accion_sistemas']; ?>"><?php echo ucwords($datos['descripcion']); ?></option>
									<?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					
					 <div class="col-md-5">
                        <div class="form-group">
                            <label for="descripcion_accion">Breve descipción de la acción o hecho:</label>
                            <textarea class="form-control" name="descripcion_accion" cols="10" rows="3"></textarea>
                        </div>
                    </div>
					
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area">Área en la que se realizo la Acción</label>
                            <select class="form-control form-select" name="id_area" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_areas as $datos) : 
										if((int)$datos['id_area'] > 0 && $datos['visible'] ==='1' ):
								?>
                                    <option value="<?php echo $datos['id_area']; ?>"><?php echo ucwords($datos['nombre_area']); ?></option>
									<?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="quien_atendio">¿Quien Atendio?</label>
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
                
                </div>

        <div class="form-group clearfix">
            <a href="acciones_sistemas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="add_acciones_sistemas" class="btn btn-info">Guardar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>