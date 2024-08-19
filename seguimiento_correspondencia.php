<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Seguimiento Correspondencia';
require_once('includes/load.php');

$user = current_user();
$detalle = $user['id_user'];
$e_correspondencia = find_by_id_correspondencia((int)$_GET['id']);

$areas = find_all('area');
//$trabajadores = find_all_trabajadores();
$trabajadores = find_all_trabajadores_area($e_correspondencia['id_area_turnada']);
$area_ingreso = isset($_GET['a']) ? $_GET['a'] : '0';

?>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['seguimiento_correspondencia'])) {

    
    if (empty($errors)) {
        $id = (int)$e_correspondencia['id_correspondencia'];
        $accion_realizada   = remove_junk($db->escape($_POST['accion_realizada']));
        $fecha   = remove_junk($db->escape($_POST['fecha']));
        $quien_realizo   = remove_junk($db->escape($_POST['quien_realizo']));

        $folio_editar = $e_correspondencia['folio'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/correspondencia/' . $resultado;

        $name = $_FILES['oficio']['name'];
        $size = $_FILES['oficio']['size'];
        $type = $_FILES['oficio']['type'];
        $temp = $_FILES['oficio']['tmp_name'];

        if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else {
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }

        if ($name != '') {
            $sql = "UPDATE correspondencia SET accion_realizada='{$accion_realizada}',fecha='{$fecha}',oficio='{$name}',quien_realizo='{$quien_realizo}' WHERE id_correspondencia='{$db->escape($id)}'";
        }
        if ($name == '') {
            $sql = "UPDATE correspondencia SET accion_realizada='{$accion_realizada}',fecha='{$fecha}',quien_realizo='{$quien_realizo}' WHERE id_correspondencia='{$db->escape($id)}'";
        }
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', " La correspondencia ha sido editada con éxito.");
            redirect('correspondencia.php?a='.$area_ingreso, false);
        } else {
            //failed
            $session->msg('d', ' No se pudo editar la correspondencia.');
            redirect('seguimiento_correspondencia.php?id=' . (int)$e_correspondencia['id_correspondencia'].'&a='.$area_ingreso.'', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('seguimiento_correspondencia.php?id=' . (int)$e_correspondencia['id_correspondencia'].'&a='.$area_ingreso.'', false);
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
                <span>Seguimiento de correspondencia</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="seguimiento_correspondencia.php?id=<?php echo (int)$e_correspondencia['id_correspondencia']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_recibido">Fecha de Recepción</label>
                            <input type="date" class="form-control" name="fecha_recibido" value="<?php echo remove_junk($e_correspondencia['fecha_recibido']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="num_oficio_recepcion">Número de Oficio de Recepción</label>
                            <input type="text" class="form-control" name="num_oficio_recepcion" value="<?php echo remove_junk($e_correspondencia['num_oficio_recepcion']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_remitente">Nombre de Remitente</label>
                            <input type="text" class="form-control" name="nombre_remitente" value="<?php echo remove_junk($e_correspondencia['nombre_remitente']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_institucion">Nombre de Institución</label>
                            <input type="text" class="form-control" name="nombre_institucion" value="<?php echo remove_junk($e_correspondencia['nombre_institucion']); ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cargo_funcionario">Cargo de Funcionario</label>
                            <input type="text" class="form-control" name="cargo_funcionario" value="<?php echo remove_junk($e_correspondencia['cargo_funcionario']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="asunto">Asunto</label>
                            <input type="text" class="form-control" name="asunto" value="<?php echo remove_junk($e_correspondencia['asunto']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="medio_recepcion">Medio de Recepción</label>
                            <input type="text" class="form-control" name="asunto" value="<?php echo remove_junk($e_correspondencia['medio_recepcion']); ?>" readonly>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="medio_entrega">Medio de Entrega</label><br>
                            <input type="text" class="form-control" name="medio_entrega" value="<?php echo remove_junk($e_correspondencia['medio_entrega']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="se_turna_a_area">Área a la que se turna</label><br>
							<input readonly type="text" class="form-control" name="medio_entrega" 
							<?php foreach ($areas as $area) : ?>
                                    <?php if ($area['id_area'] === $e_correspondencia['id_area_turnada']) echo ' value="'.$area['nombre_area'].'"' ?>
                                <?php endforeach; ?>
                            >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_en_que_se_turna">Fecha en que se turna oficio</label>
                            <input type="date" class="form-control" value="<?php echo remove_junk($e_correspondencia['fecha_en_que_se_turna']); ?>" name="fecha_en_que_se_turna" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_espera_respuesta">Fecha en que se espera respuesta</label>
                            <input type="date" class="form-control" value="<?php echo remove_junk($e_correspondencia['fecha_espera_respuesta']); ?>" name="fecha_espera_respuesta" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_tramite">Tipo de Trámite</label><br>
                            <input type="text" class="form-control" value="<?php echo remove_junk($e_correspondencia['tipo_tramite']); ?>" name="tipo_tramite" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" value="<?php echo remove_junk($e_correspondencia['observaciones']); ?>" name="observaciones" id="observaciones" cols="10" rows="3" readonly><?php echo remove_junk($e_correspondencia['observaciones']); ?></textarea>
                        </div>
                    </div>
                </div>
                <hr style="margin-top: 5px;height:2px;border-width:0;background-color:#aaaaaa">
                <!-- <h4>Seguimiento de Correspondencia</h4> -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="accion_realizada">Acción Realizada</label><br>
                            <input type="text" class="form-control" value="<?php echo remove_junk($e_correspondencia['accion_realizada']); ?>" name="accion_realizada"
                            >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha">Fecha del seguimiento</label>
                            <input type="date" class="form-control" value="<?php echo remove_junk($e_correspondencia['fecha']); ?>" name="fecha">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="oficio">Adjuntar Oficio</label>
                            <input type="file" accept="application/pdf" class="form-control" name="oficio" value="<?php echo remove_junk($e_correspondencia['oficio']); ?>" id="oficio">
                            <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_correspondencia['oficio']); ?><?php ?></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="level">Quién realizó seguimiento: </label> <label style="font-size:12px; color:#E3054F;"> <?php echo remove_junk($e_correspondencia['quien_realizo']) . ' (actual)'; ?><?php ?></label>
                            <select class="form-control" name="quien_realizo">
							<option value="">Escoge una opción</option>
                                <?php foreach ($trabajadores as $trabajador) : ?>
                                    <option value="<?php echo $trabajador['nombre'] . ' ' . $trabajador['apellidos']; ?>"><?php echo ucwords($trabajador['nombre']); ?> <?php echo ucwords($trabajador['apellidos']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <!-- <label style="font-size:12px; color:#E3054F;">Persona Actual: <?php echo remove_junk($e_correspondencia['quien_realizo']); ?><?php ?></label> -->
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-group clearfix">
                    <a href="correspondencia.php?a=<?php echo $area_ingreso?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="seguimiento_correspondencia" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>