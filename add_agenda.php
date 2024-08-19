
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Proyecto';
require_once('includes/load.php');
$id_folio = last_id_folios_general();
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

$responsables = find_area_usuario();

$id_user = $user['id_user'];
$areas = find_all('area');
$area_user = area_usuario2($id_user);
$area_creacion = $area_user['nombre_area'];

date_default_timezone_set('America/Mexico_City');
$creacion_sistema = date('Y-m-d');

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_agenda'])) {

    if (empty($errors)) {
        $fecha   = remove_junk($db->escape($_POST['fecha']));
        $actividad   = remove_junk($db->escape($_POST['actividad']));
        $area_responsable   = remove_junk($db->escape($_POST['area_responsable']));
        $responsable   = remove_junk($db->escape($_POST['responsable']));
        $area_supervisor   = remove_junk($db->escape($_POST['area_supervisor']));
        $supervisor   = remove_junk($db->escape($_POST['supervisor']));
        $fecha_limite   = remove_junk($db->escape($_POST['fecha_limite']));
        $fecha_entrega   = remove_junk(($db->escape($_POST['fecha_entrega'])));
        $entregables   = remove_junk(($db->escape($_POST['entregables'])));
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));
        $porcentaje   = remove_junk($db->escape($_POST['porcentaje']));
        $descripcion_avance = remove_junk($db->escape($_POST['descripcion_avance']));
        date_default_timezone_set('America/Mexico_City');
        $fecha_creacion_sistema = date('Y-m-d H:i:s');

        $query = "INSERT INTO agendas (";
        $query .= "fecha,actividad,area_responsable,responsable,area_supervisor,supervisor,fecha_limite,fecha_entrega,entregables,observaciones,creacion_sistema,area_creacion";
        $query .= ") VALUES (";
        $query .= " '{$fecha}','{$actividad}','$area_responsable','{$responsable}','$area_supervisor','{$supervisor}','{$fecha_limite}','{$fecha_entrega}','{$entregables}','{$observaciones}','{$creacion_sistema}','{$area_creacion}'";
        $query .= ")";

        // Buscamos el último id que este en la tabla de agendas y le sumamos 1 para que quede el mismo que tendrá ese registro
        $query_id = "SELECT id_agendas FROM agendas ORDER BY id_agendas DESC LIMIT 1";
        $id_agenda = $db->query($query_id);
        $result = $db->fetch_assoc($id_agenda);
        $id_final = $result['id_agendas'] + 1;

        $query2 = "INSERT INTO avance_agendas (";
        $query2 .= "id_agenda,porcentaje,descripcion_avance,area_creacion,fecha_hora_creacion";
        $query2 .= ") VALUES (";
        $query2 .= " '{$id_final}','{$porcentaje}','{$descripcion_avance}','{$area_creacion}','{$fecha_creacion_sistema}'";
        $query2 .= ")";

        if ($db->query($query) && $db->query($query2)) {
            //sucess
            $session->msg('s', " El proyecto ha sido agregado con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó agenda, el día: ' . $fecha_creacion_sistema . '.', 1);
            redirect('agendas.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar el proyecto.');
            redirect('add_agenda.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_agenda.php', false);
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
                <span>Agregar proyecto</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_agenda.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha</label><br>
                            <input type="date" class="form-control" name="fecha">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="actividad">Actividad (Descripción)</label>
                            <textarea class="form-control" name="actividad" cols="10" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="area_responsable">Área responsable</label>
                            <select class="form-control" id="area_responsable" name="area_responsable">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($areas as $area) : ?>
                                    <option value="<?php echo $area['nombre_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <?php $trabajadores = find_all_trabajadores_area($area['nombre_area']) ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="responsable">Responsable</label>
                            <select class="form-control" id="responsable" name="responsable"></select>
                        </div>
                    </div>
                    <script>
                        $(function() {
                            $("#area_responsable").on("change", function() {
                                var variable = $(this).val();
                                $("#selected").html(variable);
                            })

                        });
                        $(function() {
                            $("#responsable").on("change", function() {
                                var variable2 = $(this).val();
                                $("#selected2").html(variable2);
                            })

                        });
                        $(function() {
                            $("#area_supervisor").on("change", function() {
                                var variable3 = $(this).val();
                                $("#selected3").html(variable3);
                            })

                        });
                        $(function() {
                            $("#supervisor").on("change", function() {
                                var variable4 = $(this).val();
                                $("#selected4").html(variable4);
                            })

                        });
                    </script>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="area_supervisor">Área supervisor</label>
                            <select class="form-control" id="area_supervisor" name="area_supervisor">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($areas as $area) : ?>
                                    <option value="<?php echo $area['nombre_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="supervisor">Supervisor</label>
                            <select class="form-control" id="supervisor" name="supervisor"></select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_limite">Fecha de límite</label><br>
                            <input type="date" class="form-control" name="fecha_limite">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_entrega">Fecha de entrega</label><br>
                            <input type="date" class="form-control" name="fecha_entrega">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="entregables">Entregables</label>
                            <!-- <input type="text" class="form-control" name="entregables" required> -->
                            <textarea class="form-control" name="entregables" cols="10" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" cols="10" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <hr style="border-width:2px;">
                <h2><b>Información de avance del proyecto</b></h2>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="porcentaje">% de avance</label>
                            <input type="number" min="0" max="100" class="form-control" min="0" name="porcentaje" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="descripcion_avance">Descripción del avance</label>
                            <textarea class="form-control" name="descripcion_avance" cols="10" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <a href="agendas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_agenda" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<?php include_once('layouts/footer.php'); ?>