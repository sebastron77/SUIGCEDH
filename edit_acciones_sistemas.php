<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Editar Visita Web';
require_once('includes/load.php');

$acciones_sistemas = find_by_id('acciones_sistemas', (int)$_GET['id'], 'id_acciones_sistemas');
$cat_areas =find_all_order('area','jerarquia');
$cat_tipo_accion =find_all_order('cat_tipo_accion_sistemas','descripcion');
$inticadores_pat = find_all_pat_area(1,'acciones_sistemas');
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 13) {
    page_require_level(15);
}
if ($nivel > 2 && $nivel < 7) :
    redirect('home.php');
endif;
if ($nivel > 7  && $nivel < 13) :
    redirect('home.php');
endif;
if ($nivel > 13) :
    redirect('home.php');
endif;
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_acciones_sistemas'])) {

    if (empty($errors)) {
        $id = (int)$acciones_sistemas['id_acciones_sistemas'];
        $id_cat_tipo_accion_sistemas = remove_junk($db->escape($_POST['id_cat_tipo_accion_sistemas']));
        $id_area = remove_junk($db->escape($_POST['id_area']));
        $fecha_accion = remove_junk($db->escape($_POST['fecha_accion']));
        $descripcion_accion = remove_junk($db->escape($_POST['descripcion_accion']));
        $quien_atendio = remove_junk($db->escape($_POST['quien_atendio']));
        $id_indicadores_pat = remove_junk($db->escape($_POST['id_indicadores_pat']));

        $sql = "UPDATE acciones_sistemas SET 
				id_cat_tipo_accion_sistemas='{$id_cat_tipo_accion_sistemas}', 
				id_area='{$id_area}', 
				fecha_accion='{$fecha_accion}', 
				descripcion_accion='{$descripcion_accion}', 
				id_indicadores_pat='{$id_indicadores_pat}', 
				quien_atendio='{$quien_atendio}'
                WHERE id_acciones_sistemas='{$db->escape($id)}'";


        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó la Acción Relevante del área de sistemas de ID: -' . $acciones_sistemas['id_acciones_sistemas'], 2);
            $session->msg('s', " La  Acción Relevante del área de sistemas  ha sido actualizada con éxito.");
            redirect('acciones_sistemas.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron cambios a la información.');
            redirect('edit_acciones_sistemas.php?id=' . (int)$acciones_sistemas['id_acciones_sistemas'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('acciones_sistemas.php', false);
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
                <span>Editar Acción Relevante</span>
            </strong>
        </div>
        <div class="panel-body">
        <form method="post" action="edit_acciones_sistemas.php?id=<?php echo (int)$acciones_sistemas['id_acciones_sistemas']; ?>">
            <div class="row">
                <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_accion">Fecha de la Acción</label>
                            <input type="date" class="form-control" name="fecha_accion" value="<?php echo $acciones_sistemas['fecha_accion'] ?>" required>
                        </div>
                    </div>
					
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_tipo_accion_sistemas">Tipo de Acción</label>
                            <select class="form-control form-select" name="id_cat_tipo_accion_sistemas" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_tipo_accion as $datos) : 
										if((int)$datos['id_cat_tipo_accion_sistemas'] > 0 && $datos['estatus'] ==='1' ): ?>
                                    <option  <?php if ($acciones_sistemas['id_cat_tipo_accion_sistemas'] == $datos['id_cat_tipo_accion_sistemas']) echo 'selected="selected"'; ?>  value="<?php echo $datos['id_cat_tipo_accion_sistemas']; ?>"><?php echo ucwords($datos['descripcion']); ?></option>
									<?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					
					 <div class="col-md-5">
                        <div class="form-group">
                            <label for="descripcion_accion">Breve descipción de la acción o hecho:</label>
                            <textarea class="form-control" name="descripcion_accion" cols="10" rows="3"><?php echo $acciones_sistemas['descripcion_accion'] ?></textarea>
                        </div>
                    </div>
					
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area">Área en la que se realizo la Acción</label>
                            <select class="form-control form-select" name="id_area" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_areas as $datos) : 
										if((int)$datos['id_area'] > 0 && $datos['visible'] ==='1' ):?>
                                    <option  <?php if ($acciones_sistemas['id_area'] == $datos['id_area']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_area']; ?>"><?php echo ucwords($datos['nombre_area']); ?></option>
									<?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="quien_atendio">¿Quien Atendio?</label>
                            <input type="text" class="form-control" name="quien_atendio" value="<?php echo $acciones_sistemas['quien_atendio'] ?>"  required>
                        </div>
                    </div>
					
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" required>
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  <?php if ($acciones_sistemas['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select
                        </div>
                    </div>
            </div>
            <div class="row">
                <div class="form-group clearfix">
                    <a href="acciones_sistemas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_acciones_sistemas" class="btn btn-primary" value="subir">Guardar</button>
                </div>
        </form>
    </div>
</div>


<?php include_once('layouts/footer.php'); ?>