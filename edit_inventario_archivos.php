<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Editar Inventario de Archivos';
require_once('includes/load.php');

$inventario_archivos = find_by_id('inventario_archivos', (int)$_GET['id'], 'id_inventario_archivos');
$inticadores_pat = find_all_pat(14);

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];


if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 11) {
    page_require_level_exacto(11);
}
if ($nivel_user > 2 && $nivel_user < 11) :
    redirect('home.php');
endif;

if ($nivel_user > 11 && $nivel_user < 53) :
    redirect('home.php');
endif;
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_inventario_archivos'])) {

    if (empty($errors)) {
        $id = (int)$inventario_archivos['id_inventario_archivos'];
        $ejercicio = remove_junk($db->escape($_POST['ejercicio']));
        $mes = remove_junk($db->escape($_POST['mes']));
        $total_archivos = remove_junk($db->escape($_POST['total_archivos']));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
		$id_indicadores_pat = remove_junk($db->escape($_POST['id_indicadores_pat']));
		$fecha_completa = $ejercicio."-".$mes."-"."01";

        $sql = "UPDATE inventario_archivos SET fecha_completa='{$fecha_completa}', ejercicio='{$ejercicio}', mes='{$mes}', total_archivos='{$total_archivos}', observaciones='{$observaciones}',id_indicadores_pat='{$id_indicadores_pat}'
                WHERE id_inventario_archivos='{$db->escape($id)}'";


        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó el Registro de Expedientes Digitalizados de ID: -' . $inventario_archivos['id_inventario_archivos'], 2);
            $session->msg('s', " Los Registro de Expedientes Digitalizados con Folio '" . $inventario_archivos['folio'] . "' ha sido actualizada con éxito.");
            redirect('inventario_archivos.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron cambios a la información.');
            redirect('edit_inventario_archivos.php?id=' . (int)$inventario_archivos['id_inventario_archivos_especiales'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('inventario_archivos.php', false);
    }
}
?>

<?php 
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="login-page" style="height: 580px;">
    <div class="panel-body">
        <form method="post" action="edit_inventario_archivos.php?id=<?php echo (int)$inventario_archivos['id_inventario_archivos']; ?>">
		<br>
            <label  class="control-label">Editar Inventario de Archivos <?php echo $inventario_archivos['folio']; ?></label>
			<br> <br> <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ejercicio" class="control-label">Ejercicio</label>
                        <select class="form-control" name="ejercicio" id="ejercicio">
                            <option value="">Escoge una opción</option>
                            <option <?php if ($inventario_archivos['ejercicio'] == '2022') echo 'selected="selected"'; ?> value="2022">2022</option>
                            <option <?php if ($inventario_archivos['ejercicio'] == '2023') echo 'selected="selected"'; ?> value="2023">2023</option>
                            <option <?php if ($inventario_archivos['ejercicio'] == '2024') echo 'selected="selected"'; ?> value="2024">2024</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="mes" class="control-label">Mes</label>
                        <select class="form-control" name="mes" id="mes">
                            <option value="">Escoge una opción</option>
                            <option <?php if ($inventario_archivos['mes'] == '1') echo 'selected="selected"'; ?> value="1">Enero</option>
                            <option <?php if ($inventario_archivos['mes'] == '2') echo 'selected="selected"'; ?> value="2">Febrero</option>
                            <option <?php if ($inventario_archivos['mes'] == '3') echo 'selected="selected"'; ?> value="3">Marzo</option>
                            <option <?php if ($inventario_archivos['mes'] == '4') echo 'selected="selected"'; ?> value="4">Abril</option>
                            <option <?php if ($inventario_archivos['mes'] == '5') echo 'selected="selected"'; ?> value="5">Mayo</option>
                            <option <?php if ($inventario_archivos['mes'] == '6') echo 'selected="selected"'; ?> value="6">Junio</option>
                            <option <?php if ($inventario_archivos['mes'] == '7') echo 'selected="selected"'; ?> value="7">Julio</option>
                            <option <?php if ($inventario_archivos['mes'] == '8') echo 'selected="selected"'; ?> value="8">Agosto</option>
                            <option <?php if ($inventario_archivos['mes'] == '9') echo 'selected="selected"'; ?> value="9">Septiembre</option>
                            <option <?php if ($inventario_archivos['mes'] == '10') echo 'selected="selected"'; ?> value="10">Octubre</option>
                            <option <?php if ($inventario_archivos['mes'] == '11') echo 'selected="selected"'; ?> value="11">Noviembre</option>
                            <option <?php if ($inventario_archivos['mes'] == '12') echo 'selected="selected"'; ?> value="12">Diciembre</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <label for="total_archivos">Valor Absoluto</label>
                        <input class="form-control monto" type="number" id="total_archivos" name="total_archivos"value="<?php echo $inventario_archivos['total_archivos'] ?>">
                    </div>
                </div>
				<div class="col-md-10">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" required>
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option <?php if ($inventario_archivos['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?>  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                 <div class="col-md-10">
                        <div class="form-group">
                            <label for="observaciones">Notas de Digitalización</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="6"><?php echo $inventario_archivos['observaciones'] ?></textarea>
                        </div>
                    </div>
            </div>
            <div class="row">
                <div class="form-group clearfix">
                    <a href="inventario_archivos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_inventario_archivos" class="btn btn-primary" value="subir">Guardar</button>
                </div>
        </form>
    </div>
</div>


<?php include_once('layouts/footer.php'); ?>