<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Expediente Laboral';
require_once('includes/load.php');

page_require_level(1);
$areas = find_all_area_orden('area');
$tipo_int = find_all('cat_tipo_integrante');
$escolaridades = find_all('cat_escolaridad');
$area_con = find_all('cat_area_conocimiento');
$idP =  (int)$_GET['id'];
$rel_currs = find_all_exp_lab($idP);
?>
<?php
$e_detalle = find_by_id('detalles_usuario', $idP, 'id_det_usuario');
$e_detalle2 = find_by_id("rel_curriculum_laboral", $idP, 'id_detalle_usuario');
if (!$e_detalle) {
    $session->msg("d", "id de usuario no encontrado.");
    redirect('detalles_usuario.php');
}
$user = current_user();
$nivel = $user['user_level'];
$usuario = $e_detalle['id_det_usuario'];
?>

<?php
if (isset($_POST['exp_laboral'])) {

    $puesto = $_POST['puesto'];
    $institucion = $_POST['institucion'];
    $inicio = $_POST['inicio'];
    $conclusion = $_POST['conclusion'];
    $texto = "";

    for ($i = 0; $i < sizeof($puesto); $i = $i + 1) {

        $query = "INSERT INTO rel_curriculum_laboral (";
        $query .= "id_detalle_usuario, puesto, institucion, inicio, conclusion";
        $query .= ") VALUES (";
        $query .= " '{$idP}','{$puesto[$i]}','{$institucion[$i]}','{$inicio[$i]}','{$conclusion[$i]}' ";
        $query .= ")";
        $texto = $texto . $query;
        $x=$db->query($query);
    }
    if (isset($x)) {
        //sucess
        $session->msg('s', "Expediente laboral ha sido agregado.");
        insertAccion($user['id_user'], '"' . $user['username'] . '" agregó expediente laboral ' . $name . '(id:' . (int)$e_detalle2['id_rel_cur_lab'] . ').', 2);
        redirect('exp_laboral.php?id=' . (int)$e_detalle2['id_detalle_usuario'], false);
    } else {
        //failed
        $session->msg('d', 'Lamentablemente no se ha actualizado el expediente laboral, debido a que no hay cambios registrados en la descripción.');
        redirect('exp_laboral.php?id=' . (int)$e_detalle2['id_detalle_usuario'], false);
    }

    redirect('exp_laboral.php?id=' . $idP, false);
}

?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#addRow").click(function() {
            var html = '<hr style="margin-top: -1%; margin-left: 1%; width: 96.5%; border-width: 3px; border-color: #7263f0; opacity: 1"></hr>';

            html += '<div id="inputFormRow" style="margin-top: 4%;">';
            html += '   <div style="margin-bottom: 1%; margin-top: -3%">';
            html += '	    <div class="col-md-5" style="margin-left: -15px; margin-top: 1px;">';
            html += '           <span class="material-symbols-rounded" style="margin-top: 1%; color: #3a3d44;">school</span>';
            html += '           <p style="font-size: 15px; font-weight: bold; margin-top: -22px; margin-left: 11%">EXPEDIENTE ACADÉMICO</p>';
            html += '       </div>';
            html += '	    <div class="col-md-2" style="margin-left: -5%; margin-top: -1px;">';
            html += '	        <button type="button" class="btn btn-outline-danger" id="removeRow" style="width: 50px; height: 30px"> ';
            html += '       	    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">';
            html += '	    		<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5  0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>';
            html += '	    		<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>';
            html += '	    	    </svg>';
            html += '  	        </button>';
            html += '	    </div> <br><br>';
            html += '   </div>';
            html += '   <div class="row">';
            html += '      <div class="col-md-4">';
            html += '          <div class="form-group">';
            html += '              <label for="puesto">Puesto</label>';
            html += '                  <input type="text" class="form-control" name="puesto[]" id="puesto">';
            html += '          </div>';
            html += '      </div>';
            html += '      <div class="col-md-4">';
            html += '          <div class="form-group">';
            html += '              <label for="institucion">Institución</label>';
            html += '              <input type="text" class="form-control" name="institucion[]" id="institucion">';
            html += '          </div>';
            html += '      </div>';
            html += '      <div class="col-md-4">';
            html += '          <div class="form-group">';
            html += '              <label for="inicio">Fecha de Inicio</label>';
            html += '              <input type="date" class="form-control" name="inicio[]" id="inicio">';
            html += '          </div>';
            html += '      </div>';
            html += '      <div class="col-md-4">';
            html += '          <div class="form-group">';
            html += '              <label for="conclusion">Fecha de Conclusión</label>';
            html += '              <input type="date" class="form-control" name="conclusion[]" id="conclusion">';
            html += '          </div>';
            html += '      </div>';
            html += '      <div class="col-md-4">';
            html += '          <div class="form-group">';
            html += '              <label for="conclusion" style="visibility:hidden">Fecha de Conclusión</label>';
            html += '              <input type="file" style="visibility:hidden" accept="application/pdf" class="form-control" name="archivo_comprobatorio[]">';
            html += '          </div>';
            html += '      </div>';
            html += '   </div>';
            html += '</div>';
            html += '';

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
                    INFORMACIÓN DE: <?php echo upper_case(ucwords($e_detalle['nombre'] . " " . $e_detalle['apellidos'])); ?>
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
                    <p style="margin-top: 1%; margin-bottom: 2%; margin-left: 0%; font-weight: bold; color: #157347;">"Agregar más a Expediente Laboral"</p>
                </div>
            </div>
            <div class="panel-body">
                <form method="post" action="exp_laboral.php?id=<?php echo (int)$e_detalle['id_det_usuario']; ?>" enctype="multipart/form-data">
                    <div style="margin-bottom: 1%; margin-top: -3%">
                        <span class="material-symbols-rounded" style="margin-top: 2%; color: #3a3d44;">school</span>
                        <p style="font-size: 15px; font-weight: bold; margin-top: -23px; margin-left: 4%">EXPEDIENTE LABORAL</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="puesto">Puesto</label>
                                <input type="text" class="form-control" name="puesto[]" id="puesto" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="institucion">Institución</label>
                                <input type="text" class="form-control" name="institucion[]" id="institucion" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inicio">Fecha Inicio</label>
                                <input type="date" class="form-control" name="inicio[]" id="inicio">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="conclusion">Fecha Conclusión</label>
                                <input type="date" class="form-control" name="conclusion[]" id="conclusion">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="newRow" style="margin-top: 3%;">
                    </div>
                    <div class="form-group clearfix">
                        <a href="detalles_usuario.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="exp_laboral" class="btn btn-info">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 panel-body" style="height: 100%; margin-top: -5px;">
        <table class="table table-bordered table-striped" style="width: 100%; float: left;" id="tblProductos">
            <thead class="thead-purple" style="margin-top: -50px;">
                <tr style="height: 10px;">
                    <th colspan="5" style="text-align:center; font-size: 14px;">Expediente</th>
                </tr>
                <tr style="height: 10px;">
                    <th style="width: 170px; font-size: 14px;">Puesto</th>
                    <th style="width: 170px; font-size: 14px;">Institución</th>
                    <th style="width: 80px; font-size: 14px;">Inicio</th>
                    <th style="width: 20px; font-size: 14px;">Conclusión</th>
                    <th style="width: 5px; font-size: 14px;">Editar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rel_currs as $cur) : ?>
                    <tr>
                        <td style="font-size: 14px;"><?php echo remove_junk(ucwords($cur['puesto'])) ?></td>
                        <td style="font-size: 14px;"><?php echo remove_junk(ucwords($cur['institucion'])) ?></td>
                        <td style="font-size: 14px;"><?php echo remove_junk(ucwords($cur['inicio'])) ?></td>
                        <td style="font-size: 14px;"><?php echo remove_junk(ucwords($cur['conclusion'])) ?></td>
                        <td style="font-size: 14px;" class="text-center">
                            <a href="edit_exp_laboral.php?id=<?php echo (int)$cur['id_rel_cur_lab']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 30px; width: 30px;"><span class="material-symbols-rounded" style="font-size: 18px; color: black; margin-top: 1px; margin-left: -3px;">edit</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>y
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>