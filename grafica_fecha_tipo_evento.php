<?php
error_reporting(E_ERROR); //OCULTA LOS WARNINGS
$page_title = 'Reporte de resguardos';
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
        $results      = find_capacitacion_tipo_evento_by_dates($start_date, $end_date);
        $results2      = find_conferencia_tipo_evento_by_dates($start_date, $end_date);
        $results3      = find_curso_tipo_evento_by_dates($start_date, $end_date);
        $results4      = find_taller_tipo_evento_by_dates($start_date, $end_date);
        $results5      = find_platica_tipo_evento_by_dates($start_date, $end_date);
        $results6      = find_diplomado_tipo_evento_by_dates($start_date, $end_date);
        $results7      = find_foro_tipo_evento_by_dates($start_date, $end_date);
    else :
        $session->msg("d", $errors);
        redirect('tabla_estadistica_capacitacion.php', false);
    endif;
} else {
    $session->msg("d", "Select dates");
    redirect('tabla_estadistica_capacitacion.php', false);
}
?>

<!doctype html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8">
    <title>Reporte de resguardos</title>
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
                <button id="btnCrearPdf" style="margin-top: 1%" class="btn btn-pdf btn-md">Guardar en PDF</button>
                <div id="prueba">
                    <center>
                        <h3 style="margin-top: 3%;">Estadísticas por tipo de evento del <?php if (isset($start_date)) {
                                                                                            echo $start_date;
                                                                                        } ?> a <?php if (isset($end_date)) {
                                                                                                echo $end_date;
                                                                                            } ?></h3>
                    </center>
                    <div class="row" style="display: flex; justify-content: center; align-items: center; margin-left: -20%;">
                        <div class="col-md-6" style="width: 35%; height: 20%;">
                            <canvas id="myChart"></canvas>
                            <!-- Incluímos Chart.js -->
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                            <!-- Añadimos el script a la página -->

                            <script>
                                var yValues = [<?php echo $results['totales']; ?>, <?php echo $results2['totales']; ?>, <?php echo $results3['totales']; ?>,
                                    <?php echo $results4['totales']; ?>, <?php echo $results5['totales']; ?>, <?php echo $results6['totales']; ?>, <?php echo $results7['totales']; ?>
                                ];

                                const ctx = document.getElementById('myChart');
                                const myChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: ['Capacitación', 'Conferencia', 'Curso', 'Taller', 'Platica', 'Diplomado', 'Foro'],
                                        datasets: [{
                                            label: 'Capacitaciones por tipo de evento',
                                            data: yValues,
                                            backgroundColor: [
                                                '#60A685',
                                                '#91D9B7',
                                                '#ACF2D1',
                                                '#01401C',
                                                '#2F734C',
                                                '#015948',
                                                '#02A686'
                                            ],
                                            borderColor: [
                                                '#467860',
                                                '#71A88E',
                                                '#709E89',
                                                '#012912',
                                                '#204F35',
                                                '#014033',
                                                '#018066'
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
                        <div class="col-md-6" style="margin-top: 3%;">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr style="height: 10px;" class="info">
                                        <th class="text-center" style="width: 70%;">Tipo de Evento</th>
                                        <th class="text-center" style="width: 30%;">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Capacitación</td>
                                        <?php if ($results['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results['totales']; ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Conferencia</td>
                                        <?php if ($results2['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results2['totales']; ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Curso</td>
                                        <?php if ($results3['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results3['totales']; ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Taller</td>
                                        <?php if ($results4['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results4['totales']; ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Plática</td>
                                        <?php if ($results5['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results5['totales']; ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Diplomado</td>
                                        <?php if ($results6['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results6['totales']; ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Foro</td>
                                        <?php if ($results7['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results7['totales']; ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td style="text-align:right;"><b>Total</b></td>
                                        <td>
                                            <?php echo $results['totales'] + $results2['totales'] + $results3['totales'] + $results4['totales'] + $results5['totales'] +
                                                $results6['totales'] + $results7['totales'];
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
        redirect('tabla_estadistica_capacitacion.php', false);
    endif;
    ?>
</body>

</html>
<?php if (isset($db)) {
    $db->db_disconnect();
} ?>