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
        $results      = find_hombre_by_datesC($start_date, $end_date);
        $results2      = find_mujer_by_datesC($start_date, $end_date);
        $results3      = find_lgbtiq_by_datesC($start_date, $end_date);
        $results32      = find_lgbtiq_by_dates2C($start_date, $end_date);
    else :
        $session->msg("d", $errors);
        redirect('tabla_estadistica_orientacion.php', false);
    endif;
} else {
    $session->msg("d", "Select dates");
    redirect('tabla_estadistica_orientacion.php', false);
}
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
                        <h3 style="margin-top: 2%;">Estadísticas de Canalizaciones por género del <?php if (isset($start_date)) {
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
                                var yValues = [<?php echo $results['totales']; ?>, <?php echo $results2['totales']; ?>, <?php echo $results3['totales'] + $results32['totales']; ?>];

                                const ctx = document.getElementById('myChart');
                                const myChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: ['Hombres', 'Mujeres', 'LGBTIQ+'],
                                        datasets: [{
                                            label: 'Orientaciones por Género',
                                            data: yValues,
                                            backgroundColor: [
                                                '#024554',
                                                '#53736A',
                                                '#C2C0A6'
                                            ],
                                            borderColor: [
                                                '#011B21',
                                                '#32453F',
                                                '#787767'
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
                                        <th class="text-center" style="width: 70%;">Género</th>
                                        <th class="text-center" style="width: 30%;">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Hombres</td>
                                        <?php if ($results['totales'] != 0) { ?>
                                        <td class="text-center"><?php echo $results['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Mujeres</td>
                                        <?php if ($results2['totales'] != 0) { ?>
                                        <td class="text-center"><?php echo $results2['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>LGBTIQ+</td>
                                        <?php if (($results3['totales'] != 0) || ($results32['totales'] != 0)) { ?>
                                        <td class="text-center"><?php echo $results3['totales'] + $results32['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td style="text-align:right;"><b>Total</b></td>
                                        <td>
                                            <?php echo $results['totales'] + $results2['totales'] + $results3['totales'] + $results32['totales'] ?>
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