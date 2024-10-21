<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
require_once('includes/load.php');
$page_title = 'Agregar Bitácora';
// error_reporting(E_ALL ^ E_NOTICE);
$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

$vehiculo = find_by_id_vehiculo((int)$_GET['id']);
$cat_combustible = find_all_order('cat_combustible', 'descripcion');
$bitacora = find_ultimo_km((int)$_GET['id']);
$id_v = (int)$_GET['id'];

// $otro = find_total_litros($id_v, $mes);

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
if ($nivel_user > 14) {
    redirect('home.php');
}
if (!$nivel_user) {
    redirect('home.php');
}
?>
<?php
header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_bitacora_vehiculo'])) {
    if (empty($errors)) {
        $id_vehiculo = $id_v;
        $ejercicio = $_POST['ejercicio'];
        $mes = $_POST['mes'];
        $km_inicial = $_POST['km_inicial'];
        $km_final = $_POST['km_final'];
        $dia_g = $_POST['dia_g'];
        $kilometraje_g = $_POST['kilometraje_g'];
        $texto = "";

        for ($i = 0; $i < sizeof($dia_g); $i = $i + 1) {

            $litros_g = $_POST['litros_g'];
            $importe_g = $_POST['importe_g'];
            $litros_g2 = $_POST['litros_g'][$i];
            $importe_g2 = $_POST['importe_g'][$i];

            date_default_timezone_set('America/Mexico_City');
            $creacion = date('Y-m-d');
            $observaciones = remove_junk($db->escape($_POST['observaciones']));

            $query = "INSERT INTO rel_bitacora_vehiculo (";
            $query .= "id_vehiculo, ejercicio, mes, km_inicial, km_final, dia_g, kilometraje_g, litros_g, importe_g, observaciones, usuario_creador,  
                        fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$id_vehiculo}', '{$ejercicio}', '{$mes}', '{$km_inicial}', '{$km_final}', '{$dia_g[$i]}', '{$kilometraje_g[$i]}', '{$litros_g[$i]}', 
                        '{$importe_g[$i]}', '{$observaciones}', '$id_user', '$creacion') ";
            $texto = $texto . $query;
            $x = $db->query($query);
        }

        if (isset($x)) {
            //sucess
            $session->msg('s', "La bitácora ha sido agregada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó bitácora de ' . $mes . ' ' . $año, 1);
            redirect('control_vehiculos.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la bitácora al sistema.');
            redirect('control_vehiculos.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('control_vehiculos.php', false);
    }
}
?>

<script>
    $(document).ready(function() {

        $("#addRow").click(function() {
            var num = (document.getElementsByClassName("puesto").length) + 1;
            var html = '<div id="inputFormRow" style="margin-top: 0%;">';
            html += '       <div class="row">';
            html += '           <div class="col-md-1">';
            html += '               <div class="form-group">';
            html += '                   <label for="dia_g">Día</label>';
            html += '                   <input type="number" min="0" class="form-control" name="dia_g[]">';
            html += '               </div>';
            html += '           </div>';
            html += '           <div class="col-md-1">';
            html += '               <div class="form-group">';
            html += '                   <label for="kilometraje_g">Kilometraje</label>';
            html += '                   <input type="number" min="0" class="form-control" name="kilometraje_g[]">';
            html += '               </div>';
            html += '           </div>';
            html += '           <div class="col-md-1">';
            html += '               <div class="form-group">';
            html += '                   <label for="litros_g">Litros</label>';
            html += '                   <input type="number" min="0" class="form-control" name="litros_g[]">';
            html += '               </div>';
            html += '           </div>';
            html += '           <div class="col-md-1">';
            html += '               <div class="form-group">';
            html += '                   <label for="importe_g">Importe</label>';
            html += '                   <input type="number" min="0" class="form-control" name="importe_g[]">';
            html += '               </div>';
            html += '           </div>';
            html += '	        <div class="col-md-2" style="margin-left: 0%; margin-top: 1.5%;">';
            html += '	            <button type="button" class="btn btn-outline-danger" id="removeRow" style="width: 50px; height: 30px"> ';
            html += '       	        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">';
            html += '	    		    <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5  0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>';
            html += '	    		    <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>';
            html += '	    	        </svg>';
            html += '  	            </button>';
            html += '	        </div>';
            html += '   </div>';
            $('#newRow').append(html);
        });

        $(document).on('click', '#removeRow', function() {
            $(this).closest('#inputFormRow').remove();
        });

        function obtenSuma() {
            $("#total_l").val();
        }

    });
</script>

<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Bitácora de Vehículo</span>
            </strong>
        </div>
        <div class="panel-body">
            <div class="row">
                <b style="margin-top: -10px; margin-bottom: 10px;">DATOS DEL VEHÍCULO</b>
                <div class="col-md-3">
                    <p><b>Área asignada: </b> <?php echo $vehiculo['nombre_area'] ?></p>
                </div>
                <div class="col-md-3">
                    <p><b>Tipo Vehículo: </b> <?php echo $vehiculo['marca'] . ' ' . $vehiculo['modelo'] ?></p>
                </div>
                <div class="col-md-3">
                    <p><b>Placas: </b> <?php echo $vehiculo['placas'] ?></p>
                </div>
                <div class="col-md-3">
                    <p><b>Modelo: </b> <?php echo $vehiculo['anio'] ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <p><b>No. Serie: </b> <?php echo $vehiculo['no_serie'] ?></p>
                </div>
                <div class="col-md-3">
                    <p><b>Tipo Combustible: </b> <?php echo $vehiculo['combustible'] ?></p>
                </div>
                <div class="col-md-3">
                    <p><b>Color: </b> <?php echo $vehiculo['color'] ?></p>
                </div>
            </div>
            <form method="post" action="add_bitacora_vehiculo.php?id=<?php echo $id_v; ?>" id="add_bitacora_vehiculo" enctype="multipart/form-data">
                <div class="row">
                    <b style="margin-top: 30px; margin-bottom: 15px;">REGISTRO DEL CONSUMO DE COMBUSTIBLE</b>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="ejercicio">Ejercicio</label>
                            <select class="form-control" id="ejercicio" name="ejercicio" required>
                                <option value="">Escoge una opción</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                                <option value="2022">2022</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="mes">Mes</label>
                            <select class="form-control" id="mes" name="mes" required>
                                <option value="">Escoge una opción</option>
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cantidad_compra">Km. Inicial</label>
                            <input type="number" min="10" class="form-control" name="km_inicial" value="<?php echo $bitacora['km_final']; ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cantidad_compra">Km. Final</label>
                            <input type="number" min="10" class="form-control" name="km_final">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" cols="30" rows="5" id="observaciones" placeholder="EL USO DE ESTE VEHÍCULO ES ÚNICAMENTE PARA LAS ACTIVIDADES QUE MARCA EL REGLAMENTO INTERNO."></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="dia_g">Día</label>
                            <input type="number" min="0" class="form-control" name="dia_g[]">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="kilometraje_g">Kilometraje</label>
                            <input type="number" min="0" class="form-control" name="kilometraje_g[]">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="litros_g">Litros</label>
                            <input type="number" min="0" class="form-control" step="0.01" name="litros_g[]">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="importe_g">Importe</label>
                            <input type="number" class="form-control" min="0" step="0.01" inputmode="decimal" name="importe_g[]">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-success" id="addRow" name="addRow" style="margin-top: 18%;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
                                <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
                                <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="col-md-2">
                        <p style="margin-top: 12%; margin-bottom: 2%; margin-left: -35%; font-weight: bold; color: #157347;">"Agregar más días"</p>
                    </div>
                </div>
                <div class="row" id="newRow">
                </div>
                <div class="form-group clearfix" style="margin-top: 2%;">
                    <a href="bitacora_vehiculo.php?id=<?php echo $id_v; ?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_bitacora_vehiculo" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>