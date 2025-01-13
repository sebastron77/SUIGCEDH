<?php
error_reporting(E_ALL ^ E_NOTICE);
include("includes/config.php");
$page_title = 'Reporte Entradas Inventario';
$results = '';
require_once('includes/load.php');
require_once('dompdf/autoload.inc.php');

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$dompdf = new DOMPDF($options);

ob_start(); //Linea para que deje descargar el PDF
$user = current_user();
$nivel_user = $user['user_level'];
$id_detalle_usuario = $user['id_detalle_user'];

$mes = (int)$_GET['mes'];
$meses = array(
    1 => 'Enero',
    2 => 'Febrero',
    3 => 'Marzo',
    4 => 'Abril',
    5 => 'Mayo',
    6 => 'Junio',
    7 => 'Julio',
    8 => 'Agosto',
    9 => 'Septiembre',
    10 => 'Octubre',
    11 => 'Noviembre',
    12 => 'Diciembre'
);
$ejercicio = (int)$_GET['ejercicio'];
$entradas_inv = find_all_entradas_ejer_mes($ejercicio, $mes);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8">
    <title>Reporte</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
</head>
<style>
    body {
        font-family: 'Montserrat', sans-serif;
        color: black;
    }

    .rectangulo {
        display: inline-block;
        width: 103%;
        height: 120px;
        border-radius: 1px;
        border: 0.5px solid black;
        margin-left: -16px;
        padding: 5px;
    }

    .contenedor {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: -30px;
        ;
    }

    .centrar-header {
        text-align: center;
        font-weight: bold;
        margin-top: -40px;
    }

    table th,
    table td {
        /* padding: 2px; */
        line-height: 0.6 !important;
        /* border-color: red !important; */
    }
</style>

<body>
    <div class="centrar-header">
        <p style="font-size: 14px;">Comisión Estatal de los Derechos Humanos</p>
        <p style="margin-top: -10px; font-size: 14px">Coordinación Administrativa</p>
        <p style="margin-top: -10px; font-size: 12px">Adiciones al inventario en el mes de <?php echo $meses[$mes]; ?> del <?php echo $ejercicio; ?></p>
    </div>
    <div class="panel-body">
        <table class="datatable table table-bordered table-striped" style="width: 75%; margin-left: -40%;">
            <thead class="thead-purple">
                <tr style="height: 20px; font-size: 11px;">
                    <th class="text-center" style="width: 1%;">#</th>
                    <th class="text-center" style="width: 5%;">Categoría</th>
                    <th class="text-center" style="width: 5%;">Marca</th>
                    <th class="text-center" style="width: 1%;">Cantidad Compra</th>
                    <th class="text-center" style="width: 3%;">Precio Unitario</th>
                    <th class="text-center" style="width: 3%;">Fecha Compra</th>
                    <th class="text-center" style="width: 10%;">Especificaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entradas_inv as $ent_inv) : ?>
                    <?php $o_fecha = date("d/m/Y", strtotime($ent_inv['fecha_compra'])); ?>
                    <tr style="font-size: 11px;">
                        <td class="text-center"><?php echo count_id(); ?></td>
                        <td>
                            <?php echo remove_junk(ucwords($ent_inv['descripcion'])) ?>
                        </td>
                        <td>
                            <?php echo remove_junk(ucwords($ent_inv['marca'])) ?>
                        </td>
                        <td class="text-center">
                            <?php echo remove_junk(ucwords($ent_inv['cantidad_compra'])) ?>
                        </td>
                        <td class="text-center">
                            <?php echo '$' . remove_junk(ucwords($ent_inv['precio_unitario'])) ?>
                        </td>
                        <td class="text-center">
                            <?php echo remove_junk(ucwords($o_fecha)) ?>
                        </td>
                        <td>
                            <?php echo remove_junk(ucwords($ent_inv['especificaciones'])) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
<?php if (isset($db)) {
    $db->db_disconnect();
} ?>

<?php

$dompdf->loadHtml(ob_get_clean());
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();
$pdf = $dompdf->output();
$filename = "acuse.pdf";
file_put_contents($filename, $pdf);
$dompdf->stream($filename);
?>