<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Histórico Interno de Puestos';
require_once('includes/load.php');

$areas = find_all_area_orden('area');
$cat_puestos = find_all('cat_puestos');
$idP = (int)$_GET['id'];
?>
<?php
$hist_puestos = find_by_hist_exp_int((int)$_GET['id']);
$e_detalle = find_by_id('detalles_usuario', $idP, 'id_det_usuario');
$e_detalle2 = find_by_id("rel_curriculum_laboral", $idP, 'id_detalle_usuario');
if (!$e_detalle) {
    $session->msg("d", "id de usuario no encontrado.");
    redirect('detalles_usuario.php');
}
$user = current_user();
$nivel_user = $user['user_level'];

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
if (isset($_POST['hist_puestos'])) {

    $id_cat_puestos = $_POST['id_cat_puestos'];
    $id_area = $_POST['id_area'];
    $clave = $_POST['clave'];
    $niv_puesto = $_POST['niv_puesto'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_conclusion = $_POST['fecha_conclusion'];
    $texto = "";

    for ($i = 0; $i < sizeof($id_cat_puestos); $i = $i + 1) {
        $query = "INSERT INTO rel_hist_exp_int (";
        $query .= "id_detalle_usuario, id_cat_puestos, id_area, clave, niv_puesto, fecha_inicio, fecha_conclusion, fecha_creacion";
        $query .= ") VALUES (";
        $query .= " '{$idP}','{$id_cat_puestos[$i]}','{$id_area[$i]}','{$clave[$i]}','{$niv_puesto[$i]}', '{$fecha_inicio[$i]}', '{$fecha_conclusion[$i]}',  
                    NOW()";
        $query .= ")";
        $texto = $texto . $query;
        $x = $db->query($query);
    }
    if (isset($x)) {
        //sucess
        $session->msg('s', "Registro del histórico laboral ha sido agregado con éxito.");
        insertAccion($user['id_user'], '"' . $user['username'] . '" agregó histórico laboral al trabajador: ' . $idP, 1);
        redirect('hist_puestos.php?id=' . $idP, false);
    } else {
        //failed
        $session->msg('d', 'Lamentablemente no se ha actualizado el histórico laboral, debido a que no hay cambios registrados o ha ocurrido un error.');
        redirect('hist_puestos.php?id=' . $idP, false);
    }

    redirect('hist_puestos.php?id=' . $idP, false);
}

?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#addRow").click(function() {
            var num = (document.getElementsByClassName("puesto").length) + 1;
            var html = '<hr style="margin-top: -1%; margin-left: 1%; width: 96.5%; border-width: 3px; border-color: #7263f0; opacity: 1"></hr>';

            html += '<div id="inputFormRow" style="margin-top: 4%;">';
            html += '   <d style="margin-bottom: 1%; margin-top: -3%">';
            html += '   <div class="col-md-5" style="margin-left: -15px; margin-top: 1px;">';
            html += '       <span class="material-symbols-rounded" style="margin-top: 1%; color: #3a3d44;">school</span>';
            html += '       <p style="font-size: 15px; font-weight: bold; margin-top: -22px; margin-left: 11%">EXPEDIENTE ACADÉMICO</p>';
            html += '   </div>';
            html += '   <div class="col-md-2" style="margin-left: -5%; margin-top: -1px;">';
            html += '       <button type="button" class="btn btn-outline-danger" id="removeRow" style="width: 50px; height: 30px"> ';
            html += '           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">';
            html += '           <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5  0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>';
            html += '           <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>';
            html += '           </svg>';
            html += '       </button>';
            html += '   </div> <br><br>';
            html += '   <div class="row">';
            html += '       <div class="col-md-6">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_puestos" class="control-label">Puesto</label>';
            html += '               <select class="form-control" name="id_cat_puestos[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($cat_puestos as $datos) : ?>';
            html += '                       <option value="<?php echo $datos['id_cat_puestos']; ?>"><?php echo ucwords($datos['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-6">';
            html += '           <div class="form-group">';
            html += '               <label for="id_area">Área</label>';
            html += '               <select class="form-control" name="id_area[]">';
            html += '                   <option value="0">Escoge una opción</option>';
            html += '                   <?php foreach ($areas as $area) : ?>';
            html += '                       <option value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '   <di class="row">';
            html += '       <div class="col-md-3">';
            html += '           <div class="form-group">';
            html += '               <label for="clave" class="control-label">Clave</label>';
            html += '               <input type="text" class="form-control" name="clave[]">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-3">';
            html += '           <div class="form-group">';
            html += '               <label for="niv_puesto" class="control-label">Nivel de Puesto</label>';
            html += '               <input type="text" class="form-control" name="niv_puesto[]">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-3">';
            html += '           <div class="form-group">';
            html += '               <label for="fecha_inicio">Fecha de Inicio</label>';
            html += '               <input type="date" class="form-control" name="fecha_inicio[]" required>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-3">';
            html += '           <div class="form-group">';
            html += '               <label for="fecha_conclusion">Fecha de Conclusión</label>';
            html += '               <input type="date" class="form-control" name="fecha_conclusion[]" required>';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '</div>';

            $('#newRow').append(html);
        });

        $(document).on('click', '#removeRow', function() {
            $(this).closest('#inputFormRow').remove();
        });
    });
</script>

<?php include_once('layouts/header.php'); ?>
<div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
<div class="row">
    <div class="col-md-6">
        <div class="panel login-page5" style="margin-left: 0%;">
            <div class="panel-heading" style=" margin-top: 2%;">
                <strong style="font-size: 16px; font-family: 'Montserrat', sans-serif;">
                    <span class="glyphicon glyphicon-th"></span>
                    HISTÓRICO DE: <?php echo upper_case(ucwords($e_detalle['nombre'] . " " . $e_detalle['apellidos'])); ?>
                </strong>
            </div>
            <div class="row" style="margin-top: 3%; margin-bottom: 2%; margin-left: 1%;">
                <div class="col-md-1">
                    <button type="button" class="btn btn-success" id="addRow" name="addRow" style="width: 50px">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
                            <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
                            <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
                        </svg>
                    </button>
                </div>
                <div class="col-md-10">
                    <p style="margin-top: 1%; margin-bottom: 2%; margin-left: 0%; font-weight: bold; color: #157347;">"Agregar más a histórico"</p>
                </div>
            </div>
            <div class="panel-body">
                <form method="post" action="hist_puestos.php?id=<?php echo $idP; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_cat_puestos" class="control-label">Puesto</label>
                                <select class="form-control" name="id_cat_puestos[]">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cat_puestos as $datos) : ?>
                                        <option value="<?php echo $datos['id_cat_puestos']; ?>"><?php echo ucwords($datos['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_area">Área</label>
                                <select class="form-control" name="id_area[]">
                                    <option value="0">Escoge una opción</option>
                                    <?php foreach ($areas as $area) : ?>
                                        <option value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="clave" class="control-label">Clave</label>
                                <input type="text" class="form-control" name="clave[]">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="niv_puesto" class="control-label">Nivel de Puesto</label>
                                <input type="text" class="form-control" name="niv_puesto[]">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_inicio">Fecha de Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio[]" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_conclusion">Fecha de Conclusión</label>
                                <input type="date" class="form-control" name="fecha_conclusion[]" required>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="newRow" style="margin-top: 3%;"></div>
                    <div class="form-group clearfix">
                        <a href="detalles_usuario.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="hist_puestos" class="btn btn-info">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 panel-body" style="height: 100%; margin-top: -5px;">
        <table class="table table-bordered table-striped" style="width: 100%; float: left;" id="tblProductos">
            <thead class="thead-purple" style="margin-top: -50px;">
                <tr style="height: 10px;">
                    <th colspan="7" style="text-align:center; font-size: 16px;">Histórico de Expediente Interno</th>
                </tr>
                <tr style="height: 10px;">
                    <th class="text-center" style="width: 10%; font-size: 14px;">Puesto</th>
                    <th class="text-center" style="width: 10%; font-size: 14px;">Área</th>
                    <th class="text-center" style="width: 3%; font-size: 14px;">Clave</th>
                    <th class="text-center" style="width: 1%; font-size: 14px;">Nivel</th>
                    <th class="text-center" style="width: 3%; font-size: 14px;">Inicio</th>
                    <th class="text-center" style="width: 3%; font-size: 14px;">Conclusión</th>
                    <th class="text-center" style="width: 1%; font-size: 14px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hist_puestos as $hist) : ?>
                    <tr>
                        <td style="font-size: 13.5px;"><?php echo $hist['puesto'] ?></td>
                        <td style="font-size: 13.5px;"><?php echo $hist['nombre_area'] ?></td>
                        <td style="font-size: 13.5px;"><?php echo $hist['clave'] ?></td>
                        <td class="text-center" style="font-size: 13.5px;"><?php echo $hist['niv_puesto'] ?></td>
                        <td class="text-center" style="font-size: 13.5px;"><?php echo $hist['fecha_inicio'] ?></td>
                        <td class="text-center" style="font-size: 13.5px;"><?php echo $hist['fecha_conclusion'] ?></td>
                        <td style="font-size: 13.5px;" class="text-center">
                            <a href="edit_hist_puestos.php?id=<?php echo (int)$hist['id_rel_hist_exp_int']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 30px; width: 30px;"><span class="material-symbols-rounded" style="font-size: 18px; color: black; margin-top: 1px; margin-left: -3px;">edit</span>
                            </a>
                            <a href="delete_hist_puestos.php?iddel=<?php echo (int)$hist['id_rel_hist_exp_int']; ?>&id=<?php echo (int)$idP;?>" class="btn btn-dark btn-md" title="Eliminar" data-toggle="tooltip" style="height: 30px; width: 30px;"><span class="material-symbols-rounded" style="font-size: 22px; color: white; margin-top: -1.5px; margin-left: -5px;" onclick="return confirm('¿Seguro que deseas eliminar este registro?');">delete</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>