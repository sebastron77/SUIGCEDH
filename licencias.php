<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Licencias del Personal';
require_once('includes/load.php');

$idP =  (int)$_GET['id'];
$e_detalle = find_by_id('detalles_usuario', $idP, 'id_det_usuario');
if (!$e_detalle) {
    $session->msg("d", "id de usuario no encontrado.");
    redirect('detalles_usuario.php');
}

$user = current_user();
$nivel_user = $user['user_level'];
$tipo_licencias = find_all('cat_licencias');
$consec = find_by_id_consec($idP);
$licencias = find_all_lic($idP);

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
if (isset($_POST['licencias'])) {
    $comprobatorios = array();

    $carpeta = 'uploads/personal/licencias/' . $e_detalle['id_det_usuario'];

    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    } else {
        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
    }

    //se obtienen los nom,bre de archivos 		
    foreach ($_FILES["documento"]['name'] as $key => $tmp_name) {
        //condicional si el fuchero existe
        if ($_FILES["documento"]["name"][$key]) {
            // Nombres de archivos de temporales
            $archivonombre = $_FILES["documento"]["name"][$key];
            $fuente = $_FILES["documento"]["tmp_name"][$key];
            array_push($comprobatorios, $archivonombre);

            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777) or die("Hubo un error al crear el directorio de almacenamiento");
            }

            $dir = opendir($carpeta);
            $target_path = $carpeta . '/' . $archivonombre; //indicamos la ruta de destino de los archivos


            if (move_uploaded_file($fuente, $target_path)) {
            } else {
            }
            closedir($dir); //Cerramos la conexion con la carpeta destino
        }
    }

    $no_consec = $consec['no_consec'] + 1;

    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_termino = $_POST['fecha_termino'];
    // $no_dias = $_POST['no_dias'];
    $tipo_licencia = $_POST['tipo_licencia'];
    $observaciones = $_POST['observaciones'];
    date_default_timezone_set('America/Mexico_City');
    $fecha_creacion = date('Y-m-d');

    $year = date("Y");
    $folio_carpeta = str_replace("/", "-", $idP);
    $carpeta = 'uploads/personal/licencias/' . $folio_carpeta;


    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }

    $name = $_FILES['documento']['name'];
    $size = $_FILES['documento']['size'];
    $type = $_FILES['documento']['type'];
    $temp = $_FILES['documento']['tmp_name'];
    $move =  move_uploaded_file($temp, $carpeta . "/" . $name);

    $dias = abs((strtotime($fecha_termino) - strtotime($fecha_inicio)) / 86400);
    $dias2 = $dias + 1;

    $query2 = "INSERT INTO rel_licencias_personal (";
    $query2 .= "id_detalle_usuario, no_consec, id_cat_licencia, fecha_inicio, fecha_termino, no_dias, observaciones, documento, fecha_creacion,id_user_creador,terminado";
    $query2 .= ") VALUES (";
    $query2 .= " '{$idP}', '{$no_consec}', '{$tipo_licencia}', '{$fecha_inicio}', '{$fecha_termino}', '{$dias2}', '{$observaciones}', '{$name}', 
                '{$fecha_creacion}',{$user['id_user']},0";
    $query2 .= ")";
    $texto2 = $texto2 . $query2;
    $db->query($query2);
    insertAccion($user['id_user'], '"' . $user['username'] . '" agregó permiso de licencia al usuario de id:' . (int)$idP, 1);
    redirect('detalles_usuario.php', false);
}
?>
<style>
    /* Estilos para el botón */
    .info-button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 4px;
        position: relative;
    }

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
        font-size: 16px;
        margin-left: 5px;
        margin-top: 5px;
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
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
    <div class="col-md-6">
        <div class="panel login-page4" style="margin-left: 0%;">
            <div class="panel-heading">
                <strong style="font-size: 16px; font-family: 'Montserrat', sans-serif">
                    <span class="glyphicon glyphicon-th"></span>
                    LICENCIAS DE: <?php echo upper_case(ucwords($e_detalle['nombre'] . " " . $e_detalle['apellidos'])); ?>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="licencias.php?id=<?php echo (int)$e_detalle['id_det_usuario']; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fecha_inicio">Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fecha_termino">Fecha Conclusión</label>
                                <input type="date" class="form-control" name="fecha_termino" id="fecha_termino">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_licencia">Tipo de Licencia</label>
                                <select class="form-control" name="tipo_licencia" id="tipo_licencia">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($tipo_licencias as $t_licencia) : ?>
                                        <option value="<?php echo $t_licencia['id_cat_licencia']; ?>">
                                            <?php echo ucwords($t_licencia['descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="observaciones">Observaciones</label>
                                <textarea type="text" class="form-control" name="observaciones" id="observaciones" cols="30" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="documento">Documento</label>
                            <input type="file" accept="application/pdf" class="form-control" name="documento" id="documento">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <a href="detalles_usuario.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>

                        <button type="submit" name="licencias" class="btn btn-info">Agregar</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 panel-body" style="height: 100%; margin-top: -5px;">
        <table class="table table-bordered table-striped" style="width: 100%; float: left;" id="tblProductos">
            <thead class="thead-purple" style="margin-top: -50px;">
                <tr style="height: 10px;">
                    <th colspan="6" style="text-align:center; font-size: 14px;">Licencias </th>
                </tr>
                <tr style="height: 10px;">
                    <th class="text-center" style="width: 10%; font-size: 13px;">Fecha Inicio</th>
                    <th class="text-center" style="width: 10%; font-size: 13px;">Fecha Conclusión</th>
                    <th class="text-center" style="width: 5%; font-size: 13px;">No. Días</th>
                    <th class="text-center" style="width: 7%; font-size: 13px;">Días Restantes</th>
                    <th class="text-center" style="width: 10%; font-size: 13px;">Tipo Licencia</th>
                    <th class="text-center" style="width: 1%; font-size: 13px;">Acciones</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($licencias as $lic) : ?>
                    <?php 
                        $diferencia = round((strtotime($lic['fecha_termino']) - strtotime(date("Y-m-d"))) / 86400); ?>
                    <tr>
                        <td class="text-center" style="font-size: 15px;"><?php echo $newDate = date("d-m-Y", strtotime($lic['fecha_inicio'])); ?></td>
                        <td class="text-center" style="font-size: 15px;"><?php echo $newDate = date("d-m-Y", strtotime($lic['fecha_termino'])); ?></td>
                        <td class="text-center" style="font-size: 15px;"><?php echo ucwords($lic['no_dias']) ?></td>
                        <?php if ($diferencia < 0 && $lic['terminado'] == '0') : ?>
                            <td class="text-center" style="font-size: 15px; font-weight: bold; color: #DC1530;"><?php echo 'Vencida'; ?>
                                <button class="info-button round-button">?
                                    <span class="tooltip" style="font-size: 15px">Permiso vencido. Si el trabajador ya ha vuelto concluye el permiso en el botón "Cumplimiento de permiso".</span>
                                </button>
                            </td>
                        <?php endif; ?>
                        <?php if ($diferencia < 0 && $lic['terminado'] == '1') : ?>
                            <td class="text-center" style="font-size: 16px; font-weight: bold; color: #004C94;"><?php echo 'Cumplida'; ?></td>
                        <?php endif; ?>
                        <?php if ($diferencia >= 0 && $diferencia <= 3) : ?>
                            <td class="text-center" style="font-size: 16px; font-weight: bold; color: #FF5E00;"><?php echo $diferencia; ?></td>
                        <?php endif; ?>
                        <?php if ($diferencia >= 4 && $diferencia <= 15) : ?>
                            <td class="text-center" style="font-size: 16px; color: #FFDD00; text-shadow: -0.68px -0.68px 0 #524700, 0.68px -0.68px 0 #524700,
                            -0.68px 0.68px 0 #524700, 0.68px 0.68px 0 #524700;"><?php echo $diferencia; ?></td>
                        <?php endif; ?>
                        <?php if ($diferencia > 15) : ?>
                            <td class="text-center" style="font-size: 16px; font-weight: bold; color: #009D00;"><?php echo $diferencia; ?></td>
                        <?php endif; ?>
                        <td style="font-size: 15px;"><?php echo ucwords($lic['tipo_lic']) ?></td>
                        <td style="font-size: 14px;" class="text-center">

                            <a href="edit_licencia.php?id=<?php echo (int)$lic['id_rel_licencia_personal']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 30px; width: 30px;"><span class="material-symbols-rounded" style="font-size: 22px; color: black; margin-top: -1.5px; margin-left: -5px;">edit</span>
                            </a>

                            <?php if ($diferencia < 0 && $lic['terminado'] == '0') : ?>
                                <a href="concluir_permiso.php?id=<?php echo (int)$lic['id_rel_licencia_personal']; ?>&det=<?php echo $idP; ?>" class="btn btn-success btn-md" title="Cumplimiento de permiso" data-toggle="tooltip" style="height: 30px; width: 30px; background: #045700;"><span class="material-symbols-rounded" style="font-size: 22px; color: white; margin-top: -1.5px; margin-left: -5px;">event_available</span>
                                </a>
                            <?php endif; ?>
                            <a href="delete_licencia.php?idrl=<?php echo (int)$lic['id_rel_licencia_personal']; ?>&idT=<?php echo (int)$idP; ?>" class=" btn btn-dark btn-md" title="Eliminar" data-toggle="tooltip" style="height: 30px; width: 30px;"><span class="material-symbols-rounded" style="font-size: 22px; color: white; margin-top: -1.5px; margin-left: -5px;">delete</span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    num.oninput = function() {
        if (this.value.length > 4) {
            this.value = this.value.slice(0, 4);
        }
    }
</script>
<?php include_once('layouts/footer.php'); ?>