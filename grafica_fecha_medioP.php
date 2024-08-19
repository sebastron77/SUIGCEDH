<?php
error_reporting(E_ERROR); //OCULTA LOS WARNINGS
$page_title = 'Reporte';
$results = '';
require_once('includes/load.php');

page_require_level(5);
?>
<?php
if (isset($_POST['submit'])) {
    $req_dates = array('start-date', 'end-date');
    validate_fields($req_dates);

    if (empty($errors)) :
        $start_date   = remove_junk($db->escape($_POST['start-date']));
        $end_date     = remove_junk($db->escape($_POST['end-date']));
        $results      = find_asesorV_by_dates($start_date, $end_date);
        $results2      = find_asistenteV_by_dates($start_date, $end_date);
        $results3      = find_comparecencia_by_dates($start_date, $end_date);
        $results4      = find_escrito_by_dates($start_date, $end_date);
        $results5      = find_vTelefonica_by_dates($start_date, $end_date);
        $results6      = find_vElectronica_by_dates($start_date, $end_date);
        $results7      = find_cndh_by_dates($start_date, $end_date);
    else :
        $session->msg("d", $errors);
        redirect('tabla_estadistica_orientacion.php', false);
    endif;
} else {
    $session->msg("d", "Select dates");
    redirect('tabla_estadistica_orientacion.php', false);
}

$total_asesorv = count_by_asesorv('orientacion_canalizacion', 1);
$total_asistentev = count_by_asistentev('orientacion_canalizacion', 1);
$total_comp = count_by_comp('orientacion_canalizacion', 1);
$total_escrito = count_by_escrito('orientacion_canalizacion', 1);
$total_vt = count_by_vt('orientacion_canalizacion', 1);
$total_ve = count_by_ve('orientacion_canalizacion', 1);
$total_cndh = count_by_cndh('orientacion_canalizacion', 1);
?>

<!doctype html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8">
    <title>Reporte</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
    <script src="html2pdf.bundle.min.js"></script>
    <script src="script.js"></script>
    <!-- Algunos estilos -->
    <link rel="stylesheet" href="style.css">

</head>
<style>
    .btn-pdf {
        background-color: #d84244;
        color: #fff
    }

    .btn-pdf:hover,
    .btn-pdf:focus,
    .btn-pdf.focus,
    .btn-pdf:active,
    .btn-pdf.active {
        background-color: #8a2022;
        color: #fff
    }
</style>

<body>
    <?php if ($results) : ?>
        <div class="page-break">
            <center>
                <button id="btnCrearPdf" style="margin-top: 3%" class="btn btn-pdf btn-md">Guardar en PDF</button>
                <div id="prueba">

                    <center>
                        <h3 style="margin-top: 2%;">Estadísticas de Orientaciones por medio de presentación del <?php if (isset($start_date)) {
                                                                                                                    echo $start_date;
                                                                                                                } ?> al <?php if (isset($end_date)) {
                                                                                                        echo $end_date;
                                                                                                    } ?></h3>
                    </center>

                    <!-- <a href="pdf.php?start=<?php echo $start_date; ?>&end=<?php echo $end_date; ?>" class="btn btn-pdf btn-md" title="Descargar" data-toggle="tooltip">
                    Descargar en PDF
                    </a> -->

                    <br>
                    <div class="row" style="display: flex; justify-content: center; margin-left: -20%;">
                        <div class="col-md-6" style="width: 30%; height: 30%;">
                            <canvas id="myChart"></canvas>
                            <!-- Incluímos Chart.js -->
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                            <!-- Añadimos el script a la página -->

                            <script>
                                var yValues = [<?php echo $results['totales']; ?>, <?php echo $results2['totales']; ?>, <?php echo $results3['totales']; ?>, <?php echo $results4['totales']; ?>
                                    <?php echo $results5['totales']; ?>, <?php echo $results6['totales']; ?>, <?php echo $results7['totales']; ?>
                                ];

                                const ctx = document.getElementById('myChart');
                                const myChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: ['Asesor Virtual', 'Asistente Virtual', 'Comparecencia', 'Escrito', 'Vía telefónica', 'Vía electrónica', 'Comisión Nacional de los Derechos Humanos'],
                                        datasets: [{
                                            label: 'Orientaciones por medio de presentación',
                                            data: yValues,
                                            backgroundColor: [
                                                '#7A8A28', '#9FC983', '#7DB37F', '#B4CCBD', '#354A45', '#195947', '#688C82'
                                            ],
                                            borderColor: [
                                                '#4C5719', '#73915E', '#577D59', '#728278', '#253330', '#0F362B', '#394D47'
                                            ],
                                            borderWidth: 2
                                        }]
                                    },
                                    options: {
                                        legend: {
                                            display: false
                                        },
                                        // El salto entre cada valor de Y
                                        ticks: {
                                            min: 0,
                                            max: 6000,
                                            stepSize: 1
                                        },

                                    }
                                });
                            </script>

                        </div>
                    </div>

                    <div class="row" style="display: flex; justify-content: center; align-items: center;">
                        <div class="col-md-6" style="margin-top: 5%;">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr style="height: 10px;" class="info">
                                        <th class="text-center" style="width: 70%;">Medio de presentación</th>
                                        <th class="text-center" style="width: 30%;">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Asesor Virtual</td>
                                        <?php if ($results['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Asistente Virtual</td>
                                        <?php if ($results2['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results2['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Comparecencia</td>
                                        <?php if ($results3['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results3['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Escrito</td>
                                        <?php if ($results4['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results4['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Vía telefónica</td>
                                        <?php if ($results5['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results5['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Vía electrónica</td>
                                        <?php if ($results6['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results6['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Comisión Nacional de los Derechos Humanos</td>
                                        <?php if ($results7['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results7['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td style="text-align:right;"><b>Total</b></td>
                                        <td>
                                            <?php echo $results['totales'] + $results2['totales'] + $results3['totales'] + $results4['totales'] + $results5['totales'] +
                                                $results6['totales'] + $results7['totales']
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </center>
        </div>

    <?php
    else :
        $session->msg("d", "No se encontraron datos. ");
        redirect('tabla_estadistica_orientacion.php', false);
    endif;
    ?>
</body>

</html>
<?php if (isset($db)) {
    $db->db_disconnect();
} ?>