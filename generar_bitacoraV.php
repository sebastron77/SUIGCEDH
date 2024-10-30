<?php
error_reporting(E_ALL ^ E_NOTICE);
include("includes/config.php");
$page_title = 'Constancia';
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
$id_v = (int)$_GET['id'];
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
$bitacora_mes = find_all_bitacora_order($mes, $ejercicio, $id_v);
$info_vehiculo = find_by_id_vehiculo($id_v);
$info_vehiculo2 = find_info_bitacora_vehiculo($id_v, $mes, $ejercicio);
$servicios = find_info_servicios($id_v, $mes, $ejercicio);
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
        <p style="margin-top: -10px; font-size: 12px">BITÁCORA DE CONSUMO DE COMBUSTIBLE Y MANTENIMIENTO</p>
        <p style="margin-bottom: -5px; font-size: 12px">DATOS DEL VEHÍCULO</p>
    </div>
    <div class="rectangulo" style="margin-top: 5px;">
        <div class="contenedor">
            <p style="text-align: left;">UPP: <b>Comisión Estatal de los Derechos Humanos</b></p>
            <p style="text-align: right;">OFICINA: <b><?php echo $info_vehiculo['nombre_area']; ?></b></p>
        </div>
        <div class="contenedor" style="margin-top: -60px;">
            <p style="text-align: left;">TIPO DE VEHÍCULO: <b><?php echo $info_vehiculo['marca'] . ' ' . $info_vehiculo['modelo'] ?></b></p>
            <p style="text-align: center; margin-left: 250px;">N° DE PLACAS: <b><?php echo $info_vehiculo['placas']; ?></b></p>
            <p style="text-align: right;">MODELO: <b><?php echo $info_vehiculo['anio']; ?></b></p>
        </div>
        <div class="contenedor" style="margin-top: -60px;">
            <p style="text-align: left;">N° SERIE: <b><?php echo $info_vehiculo['no_serie'] ?></b></p>
            <p style="text-align: center; margin-left: 10px;">COMBUSTIBLE: <b><?php echo $info_vehiculo['combustible']; ?></b></p>
            <p style="text-align: right;">COLOR: <b><?php echo $info_vehiculo['color']; ?></b></p>
        </div>
        <div class="contenedor">
            <p style="text-align: left;">KILOMETRAJE INICIAL: <b><?php echo $info_vehiculo2['km_inicial'] ?></b></p>
            <p style="text-align: center; margin-left: 10px;">KILOMETRAJE FINAL: <b><?php echo $info_vehiculo2['km_final']; ?></b></p>
            <p style="text-align: right;">MES: <b><?php echo $meses[$mes] . '/' . $info_vehiculo2['ejercicio']; ?></b></p>
        </div>
    </div>
    <p style="text-align: center; font-weight: bold; margin-top: 10px; font-size: 12px;">REGISTRO DEL CONSUMO DE COMBUSTIBLE</p>

    <table style="width: 80%; border-collapse: collapse; margin-left: -30px;">
        <tr>
            <td style="width: 55%; vertical-align: top;">
                <table class="table table-bordered" style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <thead>
                        <tr style="font-size: 11px; border: 1px solid black;">
                            <th class="text-center" style="width: 1%; border: 1px solid black;">DÍA</th>
                            <th class="text-center" style="width: 5%; border: 1px solid black;">KILOMETRAJE</th>
                            <th class="text-center" style="width: 3%; border: 1px solid black;">LITROS</th>
                            <th class="text-center" style=" border-right: 0.5px solid black; border-top: 1px solid black; border-bottom: 1px solid black;">IMPORTE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="color: white; border: 1px solid black;">_</td>
                            <td style="color: white; border: 1px solid black;">_</td>
                            <td style="color: white; border: 1px solid black;">_</td>
                            <td style="color: white; border-right: 0.5px solid black; border-bottom: 1px solid black;">_</td>
                        </tr>
                        <?php foreach (array_slice($bitacora_mes, 0, 15) as $bitacora): ?>
                            <tr>
                                <td class="text-center" style="border: 1px solid black;"><?php echo $bitacora['dia_g']; ?></td>
                                <td class="text-center" style="border: 1px solid black;"><?php echo $bitacora['kilometraje_g']; ?></td>
                                <td class="text-center" style="border: 1px solid black;"><?php echo $bitacora['litros_g']; ?></td>
                                <td class="text-center" style="border-right: 0.5px solid black; border-bottom: 1px solid black;"><?php echo $bitacora['importe_g']; ?></td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </td>
            <td style="width: 55%; vertical-align: top;">
                <table class="table table-bordered" style="width: 100%; border-collapse: collapse; font-size: 11px; margin-left: -1.2px;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 1%; border: 1px solid black;">DÍA</th>
                            <th class="text-center" style="width: 5%; border: 1px solid black;">KILOMETRAJE</th>
                            <th class="text-center" style="width: 3%; border: 1px solid black;">LITROS</th>
                            <th class="text-center" style="width: 5%; border: 1px solid black;">IMPORTE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($bitacora_mes, 15, 31) as $bitacora) : ?>
                            <tr>
                                <td class="text-center" style="border: 1px solid black;"><?php echo $bitacora['dia_g']; ?></td>
                                <td class="text-center" style="border: 1px solid black;"><?php echo $bitacora['kilometraje_g']; ?></td>
                                <td class="text-center" style="border: 1px solid black;"><?php echo $bitacora['litros_g']; ?></td>
                                <td class="text-center" style="border: 1px solid black;"><?php echo $bitacora['importe_g']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </td>
            <!-- <td style="width: 40%; vertical-align: top; height: 100%;">
                <table class="table table-bordered" style="width: 100%; border-collapse: collapse; font-size: 11px; margin-left: -1.5px;">
                    <thead>
                        <tr style="border: 0.5px solid black;">
                            <th style="width: 5%; border: 1px solid black;">CONTABILIDAD CARGO/ABONO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border: 1px solid black;">
                            <td style="border: 1px solid black;">
                                <p style="margin-left: 10px; margin-top: 50px;">Total Litros: <b><?php echo $bitacora['t_litros']; ?></b></p>
                                <p style="margin-left: 10px;">Precio/Litro: <b><?php echo round(($bitacora['t_importes'] / $bitacora['t_litros']), 2); ?></b></p>
                                <p style="margin-left: 10px; margin-bottom: 50px;">Gasto Mensual: <b><?php echo $bitacora['t_importes']; ?></b></p>
                            </td>
                        </tr>
                        <tr style="border: 1px solid black;">
                            <td style="border: 1px solid black;">
                                <b>OBSERVACIONES</b>
                                <p style="margin-bottom: 5px; color:white;">.</p>
                                <?php foreach ($bitacora_mes as $bitacora): ?>
                                    <?php if ($bitacora['observaciones'] != ''): ?>
                                        <p>- <?php echo $bitacora['observaciones'] ?></p>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td> -->
        </tr>
    </table>
    <table style="font-size: 11px; margin-top: -43.94%; margin-left: 434.4%; margin-bottom: -50px !important;">
        <thead>
            <tr style="border: 0.5px solid black;">
                <th style="border: 1px solid black; height: 22.7px; width: 173px;">CONTABILIDAD CARGO/ABONO</th>
            </tr>
        </thead>
        <tbody>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black; height: 170px;">
                    <p style="margin-left: 10px;">Total Litros: <b><?php echo $bitacora['t_litros']; ?></b></p>
                    <p style="margin-left: 10px;">Precio/Litro: <b><?php echo round(($bitacora['t_importes'] / $bitacora['t_litros']), 2); ?></b></p>
                    <p style="margin-left: 10px;">Gasto Mensual: <b><?php echo $bitacora['t_importes']; ?></b></p>
                </td>
            </tr>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black; height: 266.5px;">
                    <p style="margin-top: -10px; text-align: center; font-weight: bold;">OBSERVACIONES</p>
                    <p style="color:white;">.</p>
                    <?php foreach ($bitacora_mes as $bitacora): ?>
                        <?php if ($bitacora['observaciones'] != ''): ?>
                            <p style="margin-left: 5px;">- <?php echo $bitacora['observaciones'] ?></p>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </td>
            </tr>
        </tbody>
    </table>
    <table style="margin-top: -58.5px !important; margin-left: -29px; width: 79.8%;">
        <tr>
            <td style="border-top: 0.5px solid black; border-bottom: 1px solid black; border-left: 1px solid black; width: 21.7%">
                <p style="writing-mode: vertical-rl; transform: rotate(270deg); margin-top: -58px; margin-left: -15px; font-weight: bold; font-size: 11px;">TOTALES <br><br>DEL MES</p>
            </td>
            <td style="border-top: 0.5px solid black; border-bottom: 1px solid black; border-left: 1px solid black;">
                <p style="margin-left: 5px; font-size: 11px; margin-top: 7.7px;">Kilómetros Recorridos: <b><?php echo $bitacora['t_km_final'] - $bitacora['t_km_inicial'] ?></b> Km</p>
                <p style="margin-left: 5px; font-size: 11px;"><b>(Entre)</b> Litros Cargados: <b><?php echo $bitacora['t_litros'] ?></b> L</p>
                <p style="margin-left: 5px; font-size: 11px;"><b>(Igual)</b> Rendimiento por litro: <b><?php echo round((($bitacora['t_km_final'] - $bitacora['t_km_inicial']) / $bitacora['t_litros']), 2) ?></b> Km/L</p>
            </td>
        </tr>
    </table>
    <p style="text-align: center; font-weight: bold; margin-top: 10px; font-size: 12px;">MANTENIMIENTO EFECTUADO</p>
    <table class="table table-bordered" style="margin-left: -30px; margin-top: -5px; width: 104%;">
        <thead>
            <tr style="font-size: 11px;">
                <th class="text-center" style="border: 1px solid black; width: 18.8%;">FECHA SERVICIO</th>
                <th class="text-center" style="border: 1px solid black; width: 19.1%;">TIPO SERVICIO</th>
                <th class="text-center" style="border: 1px solid black; width: 20.4%;">KM. ANTES SERVICIO</th>
                <th class="text-center" style="border: 1px solid black; width: 20.4%;">KM. PRÓXIMO SERVICIO</th>
                <th class="text-center" style="border: 1px solid black; width: 22.5%">OBSERVACIONES DEL <br><br> SERVICIO</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($servicios as $serv): ?>
                <tr style="font-size: 11px;">
                    <td class="text-center" style="border: 1px solid black;"><?php echo $serv['fecha_servicio']; ?></td>
                    <td style="border: 1px solid black;"><?php echo $serv['tipo_servicio']; ?></td>
                    <td class="text-center" style="border: 1px solid black;"><?php echo $serv['km_al_servicio']; ?></td>
                    <td class="text-center" style="border: 1px solid black;"><?php echo $serv['km_prox_servicio']; ?></td>
                    <td style="border: 1px solid black;"><?php echo $serv['observaciones']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
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