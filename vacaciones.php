<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Vacaciones del Personal';
require_once('includes/load.php');

$idP =  (int)$_GET['id'];
$e_detalle = find_by_id('detalles_usuario', $idP, 'id_det_usuario');
// if (!$e_detalle) {
//     $session->msg("d", "id de usuario no encontrado.");
//     redirect('detalles_usuario.php');
// }

$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$periodos = find_all('cat_periodos_vac');
$vacaciones = find_all_vac($idP);

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}
if ($nivel_user == 2) {
    page_require_level_exacto(2);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
}
if ($nivel_user > 2 && $nivel_user < 14) :
    redirect('home.php');
endif;
if ($nivel_user > 14) :
    redirect('home.php');
endif;
?>

<?php
if (isset($_POST['vacaciones'])) {
    //--------------------INSERT 1--------------------
    $id_cat_periodo_vac = $_POST['id_cat_periodo_vac'];
    $derecho_vacas = $_POST['derecho_vacas'];
    $observaciones = $_POST['observaciones'];
    $ejercicio = $_POST['ejercicio'];

    //--------------------INSERT 2--------------------
    $semana1_1 = $_POST['semana1_1'];
    $semana1_2 = $_POST['semana1_2'];

    date_default_timezone_set('America/Mexico_City');
    $fecha_creacion = date('Y-m-d');

    $dbh = new PDO('mysql:host=localhost; dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "INSERT INTO rel_vacaciones (";
    $query .= "id_detalle_usuario, id_cat_periodo_vac, derecho_vacas, observaciones, ejercicio, usuario_creador, fecha_creacion";
    $query .= ") VALUES (";
    $query .= " '{$idP}', '{$id_cat_periodo_vac}', '{$derecho_vacas}', '{$observaciones}', '{$ejercicio}', '{$id_user}', '{$fecha_creacion}'";
    $query .= ")";


    if ($dbh->exec($query)) {
        $id_rv = $dbh->lastInsertId();

        if ($derecho_vacas != 0) {
            for ($i = 0; $i < sizeof($semana1_1); $i = $i + 1) {
                if ($semana1_1[$i] != '') {
                    $query2 = "INSERT INTO rel_periodos_vac (";
                    $query2 .= " id_rel_vacaciones, semana1_1, semana1_2, usuario_creador, fecha_creacion";
                    $query2 .= ") VALUES (";
                    $query2 .= " '{$id_rv}', '{$semana1_1[$i]}', '{$semana1_2[$i]}', '{$id_user}', '{$fecha_creacion}'";
                    $query2 .= ")";
                    $db->query($query2);
                    $q2 = 1;
                }
            }
        }
        if (($id_rv != 0) || ($q2 == 1)) {
            //sucess
            $session->msg('s', "Periodo vacacional agregado con éxito. ");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó periodo vacacional al usuario de id:' . (int)$idP, 1);
            redirect('vacaciones.php?id=' . $idP, false);
        } else {
            //failed
            $session->msg('d', 'Desafortunadamente no se pudo crear el registro.');
            redirect('detalles_usuario.php', false);
        }
    }
}
?>
<?php include_once('layouts/header.php'); ?>

<script type="text/javascript">
    cont = 2;
    $(document).ready(function() {
        $("#addRow").click(function() {
            var html = '';

            html += '<div id="inputFormRow" style="margin-left: -2.5%">';
            html += '   <div class="row">';
            html += '       <div class="col-md-6">';
            html += '           <span style="font-weight: bold; margin-left: 37%; margin-bottom: 1%;"> - Semana/Día ' + cont + ' -</span><br>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="col-md-3">';
            html += '       <div class="form-group">';
            html += '           <label for="semana1_1" style="margin-left: 35%;">Del día</label>';
            html += '           <input type="date" class="form-control" name="semana1_1[]" style="width: 115%">';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="col-md-3">';
            html += '       <div class="form-group">';
            html += '           <label for="semana1_2" style="margin-left: 35%;">Al día</label>';
            html += '           <input type="date" class="form-control" name="semana1_2[]" style="width: 115%">';
            html += '       </div>';
            html += '   </div>';
            html += '	<div class="col-md-2" style="margin-top: 2%">';
            html += '	    <button type="button" class="btn btn-danger" id="removeRow" style="margin-top: 15%">';
            html += '           <span class="material-symbols-outlined" style="color: white;">event_busy</span>';
            html += '  	    </button>';
            html += '	</div><br><br>';
            html += '</div> ';
            cont = cont + 1;
            $('#newRow').append(html);
        });
        $(document).on('click', '#removeRow', function() {
            $(this).closest('#inputFormRow').remove();
        });
    });
</script>
<style>
    /* Estilos para el tooltip */
    .info-button .tooltip {
        visibility: hidden;
        width: 220px;
        background-color: rgb(94, 94, 94);
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        /* Posición del tooltip */
        left: 50%;
        margin-left: -110px;
        opacity: 1.5;
        transition: opacity 0.3s;
    }

    .info-button .tooltip::after {
        content: "";
        position: absolute;
        top: 100%;
        /* Flecha apuntando hacia arriba */
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent transparent;
    }

    .info-button:hover .tooltip {
        visibility: visible;
        opacity: 1;
    }

    .round-button {
        background-color: #7263f0;
        color: white;
        border: none;
        padding: 10px;
        /* Espacio interior */
        text-align: center;
        text-decoration: none;
        font-size: 15px;
        margin-left: 5px;
        margin-top: -3px;
        cursor: pointer;
        border-radius: 100%;
        width: 20px;
        height: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: relative;
        vertical-align: middle;
    }

    /* Efecto al pasar el ratón */
    .round-button:hover {
        background-color: #5449B3;
        /* Color de fondo al pasar el ratón */
    }
</style>
<div class="row">
    <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
    <div class="col-md-5">
        <div class="panel login-page4" style="margin-left: 0%;">
            <div class="panel-heading">
                <strong style="font-size: 16px; font-family: 'Montserrat', sans-serif">
                    <span class="glyphicon glyphicon-th"></span>
                    PERIODO VACACIONAL DE: <?php echo upper_case(ucwords($e_detalle['nombre'] . " " . $e_detalle['apellidos'])); ?>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="vacaciones.php?id=<?php echo (int)$e_detalle['id_det_usuario']; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="id_cat_periodo_vac">Periodo Vacacional <span style="color:red; font-weight:bold;">*</span></label>
                                <select class="form-control" name="id_cat_periodo_vac" id="id_cat_periodo_vac" required>
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($periodos as $periodo) : ?>
                                        <option value="<?php echo $periodo['id_cat_periodo_vac']; ?>">
                                            <?php echo ucwords($periodo['descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="ejercicio">Ejercicio <span style="color:red; font-weight:bold;">*</span></label>
                                <select class="form-control" name="ejercicio" onchange="changueAnio(this.value)" required>
                                    <option value="">Ejercicio (Año)</option>
                                    <?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
                                        echo "<option value='" . $i . "'>" . $i . "</option>";
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="derecho_vacas">¿Derecho a vacaciones? <span style="color:red; font-weight:bold;">*</span></label>
                                <select class="form-control" name="derecho_vacas" id="derecho_vacas" required>
                                    <option value="">Escoge una opción</option>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="observaciones">Observaciones</label>
                                <textarea type="text" class="form-control" name="observaciones" id="observaciones" cols="30" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <span style="font-weight: bold; margin-left: 37%; margin-bottom: 1%;"> - Semana/Día 1 -</span>
                            <button class="info-button round-button">?
                                <span class="tooltip" style="font-size: 15px">Las fechas pueden quedarse en blanco si el trabajador no tiene derecho a periodo vacacional.</span>
                            </button><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="semana1_1" style="margin-left: 35%;">Del día</label>
                                <input type="date" class="form-control" name="semana1_1[]">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="semana1_2" style="margin-left: 35%;">Al día</label>
                                <input type="date" class="form-control" name="semana1_2[]">
                            </div>
                        </div>

                        <div class="col-md-1" style="margin-top: 4%;">
                            <button type="button" class="btn btn-success" id="addRow" name="addRow" style="width: 40px">
                                <span class="material-symbols-outlined" style="color: white">
                                    calendar_add_on
                                </span>
                            </button>
                        </div>
                        <div class="col-md-5" style="margin-top: 5%;">
                            <p style="margin-bottom: 2%; margin-left: 0%; font-weight: bold; color: #157347;">Agregar otra fecha</p>
                        </div>
                        <!-- </div> -->
                    </div>
                    <div class="row" id="newRow" style="margin-top: 3%;">
                    </div>
                    <div class="form-group clearfix">
                        <a href="detalles_usuario.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="vacaciones" class="btn btn-info">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7 panel-body" style="height: 100%; margin-top: -5px;">
        <table class="table table-bordered table-striped" style="width: 100%; float: left;" id="tblProductos">
            <thead class="thead-purple" style="margin-top: -50px;">
                <tr style="height: 10px;">
                    <th colspan="7" style="text-align:center; font-size: 14px;">Periodos Vacacionales</th>
                </tr>
                <tr style="height: 10px;">
                    <th class="text-center" style="width: 7%; font-size: 13px;">Periodo</th>
                    <th class="text-center" style="width: 1%; font-size: 13px;">Ejercicio</th>
                    <th class="text-center" style="width: 1%; font-size: 13px;">Derecho</th>
                    <th class="text-center" style="width: 5%; font-size: 13px;">Del</th>
                    <th class="text-center" style="width: 5%; font-size: 13px;">Al</th>
                    <th class="text-center" style="width: 15%; font-size: 13px;">Observaciones</th>
                    <th class="text-center" style="width: 1%; font-size: 13px;">Acciones</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($vacaciones as $vac) : ?>
                    <tr>
                        <td style="font-size: 15px;"><?php echo $vac['cat_periodo']; ?></td>
                        <td class="text-center" style="font-size: 15px;"><?php echo $vac['ejercicio']; ?></td>
                        <td class="text-center" style="font-size: 15px;"><?php echo $vac['derecho_vacas'] == '0' ? 'No' : 'Sí'; ?></td>
                        <td class="text-center" style="font-size: 15px;"><?php echo $vac['derecho_vacas'] == '1' ? ($vac['semana1_1'] ? $newDate = date("d-m-Y", strtotime($vac['semana1_1'])) : '-') : '-'  ?></td>
                        <td class="text-center" style="font-size: 15px;"><?php echo $vac['derecho_vacas'] == '1' ? ($vac['semana1_1'] ? $newDate = date("d-m-Y", strtotime($vac['semana1_2'])) : '-') : '-'  ?></td>
                        <td class="text-center" style="font-size: 15px;"><?php echo $vac['observaciones']; ?></td>
                        <td style="font-size: 14px;" class="text-center">
                            <!-- <?php if ($vac['derecho_vacas'] == '0'): ?> -->
                            <a href="edit_vacaciones.php?idrv=<?php echo (int)$vac['id_rel_vacaciones']; ?>" class=" btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 30px; width: 30px;"><span class="material-symbols-rounded" style="font-size: 22px; color: black; margin-top: -1.5px; margin-left: -5px;">edit</span>
                            </a>
                            <!-- <?php endif; ?> -->
                            <!-- <?php if ($vac['derecho_vacas'] == '1'): ?> -->
                            <a href="edit_vacaciones.php?idrv=<?php echo (int)$vac['id_rel_vacaciones']; ?>&idrpv=<?php echo (int)$vac['id_rel_periodo_vac']; ?>"" class=" btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 30px; width: 30px;"><span class="material-symbols-rounded" style="font-size: 22px; color: black; margin-top: -1.5px; margin-left: -5px;">edit</span>
                            </a>
                            <!-- <?php endif; ?> -->
                            <a href="delete_vacaciones.php?idrv=<?php echo (int)$vac['id_rel_vacaciones']; ?>&idrpv=<?php echo (int)$vac['id_rel_periodo_vac']; ?>&idT=<?php echo (int)$idP; ?>" class=" btn btn-dark btn-md" title="Editar" data-toggle="tooltip" style="height: 30px; width: 30px;"><span class="material-symbols-rounded" style="font-size: 22px; color: white; margin-top: -1.5px; margin-left: -5px;">delete</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>