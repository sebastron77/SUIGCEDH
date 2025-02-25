<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Historial de Salidas en el Inventario';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel_user = $user['user_level'];
$salidas_inv = find_all_salidas_inv();

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

$ejercicio = isset($_GET['ejercicio']) ? $_GET['ejercicio'] : date("Y");
$mes = $_GET['mes'];

if ($ejercicio != '' && $mes != '') {
    $salidas_inv = find_all_salidas_ejer_mes($ejercicio, $mes);
} else {
    $salidas_inv = find_all_salidas_inv();
}
?>

<script type="text/javascript">
    function changueAnio(mes) {
        // id = document.getElementById('id').value;
        ejercicio = document.getElementById('ejercicio').value;
        window.open("salidas_inv.php?ejercicio=" + ejercicio + "&mes=" + mes, "_self");
    }

    function generarPDF(mes, ejercicio) {
        // Realizar una solicitud AJAX al servidor al hacer clic en el botón
        var xhr = new XMLHttpRequest();
        var mesPDF = mes;
        var ejercicioPDF = ejercicio;
        xhr.open("GET", "generar_reporte_salidas_inv.php?&mes=" + mesPDF + "&ejercicio=" + ejercicioPDF, true);
        xhr.responseType = "blob"; // La respuesta será un archivo binario (el PDF)
        xhr.onload = function() {
            if (this.status === 200) {
                // Crear un enlace para descargar el PDF
                var blob = this.response;
                var link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                link.download = "reporte_salidas_inv.pdf"; // Nombre del archivo PDF
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        };
        xhr.send();
    }
</script>

<?php include_once('layouts/header.php'); ?>
<a href="solicitudes_inventario.php" class="btn btn-success">Regresar</a><br><br>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>HISTORIAL DE SALIDAS EN EL INVENTARIO</span>

                    <div class="row" style="margin-top: 20px; display: flex; justify-content: flex-start; gap: 0px;">
                        <div class="col-md-1" style="margin-top: 5px;">
                            <b>Buscar por: </b>
                        </div>
                        <div class="col-md-5" style="margin-left: -30px; width: 200px;">
                            <div class="form-group">
                                <!-- <input type="hidden" id="id" value="<?php echo $id_v; ?>"> -->
                                <select class="form-control" name="ejercicio" id="ejercicio">
                                    <option value="">Selecciona Ejercicio</option>
                                    <?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
                                        echo "<option value='" . $i . "'>" . $i . "</option>";
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <select class="form-control" name="mes" onchange="changueAnio(this.value)" style="width: 200px">
                                <option value="">Selecciona Mes</option>
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
                        <div class="col-md-1" style="margin-left: 500px">
                            <a href="add_salida_inv.php" class="btn btn-info pull-right">AGREGAR SALIDA</a>
                        </div>
                        <div class="col-md-1" style="margin-left: -75px; margin-top: 5px;">
                            <button class="botones" id="descargar-pdf" onclick="generarPDF(<?php echo $mes; ?>, <?php echo $ejercicio; ?>)" style="margin-left: 60%; margin-top: -5%;">
                                PDF
                            </button>
                        </div>
                    </div>
                </strong>
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 1%;">#</th>
                            <th class="text-center" style="width: 5%;">Categoría</th>
                            <th class="text-center" style="width: 2%;">Cantidad Salida</th>
                            <th class="text-center" style="width: 2%;">Cantidad Anterior</th>
                            <th class="text-center" style="width: 10%;">Área Salida</th>
                            <th class="text-center" style="width: 1%;">Fecha Salida</th>
                            <?php if ($nivel_user == 1 || $nivel_user == 14) : ?>
                                <th style="width: 1%;" class="text-center">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($salidas_inv as $sali_inv) :
                            $o_fecha = date("d/m/Y", strtotime($sali_inv['fecha_salida']));
                        ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td>
                                    <?php echo $sali_inv['descripcion'] ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $sali_inv['cantidad_salida'] ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $sali_inv['cantidad_anterior'] ?>
                                </td>
                                <td>
                                    <?php echo remove_junk(ucwords($sali_inv['nombre_area'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $o_fecha ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($nivel_user == 1 || $nivel_user == 14) : ?>
                                        <div class="btn-group">
                                            <a href="edit_salida_inv.php?id=<?php echo (int)$sali_inv['id_rel_salida_inv']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                                <span class="material-symbols-outlined" style="font-size: 22px; color: black; margin-top: 8px;">
                                                    edit
                                                </span>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>