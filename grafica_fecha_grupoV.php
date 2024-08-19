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
        $results      = find_lgbt_by_dates($start_date, $end_date);
        $results1      = find_lgbt_by_dates2($start_date, $end_date);
        $results2      = find_ddm_by_dates($start_date, $end_date);
        $results3      = find_nna_by_dates($start_date, $end_date);
        $results32      = find_nna_by_dates2($start_date, $end_date);
        $results4      = find_pDiscapacidad_by_dates($start_date, $end_date);
        $results5      = find_pMigrantes_by_dates($start_date, $end_date);
        $results6      = find_vih_by_dates($start_date, $end_date);
        $results7      = find_gIndigenas_by_dates($start_date, $end_date);
        $results8      = find_periodistas_by_dates($start_date, $end_date);
        $results9      = find_ddh_by_dates($start_date, $end_date);
        $results10      = find_aMayores_by_dates($start_date, $end_date);
        $results11      = find_internos_by_dates($start_date, $end_date);
        $results12      = find_otros_by_dates($start_date, $end_date);
        $results13      = find_na_by_dates($start_date, $end_date);
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
                        <h3 style="margin-top: 2%;">Estadísticas de Orientaciones por grupo vulnerable del <?php if (isset($start_date)) {
                                                                                                                echo $start_date;
                                                                                                            } ?> al <?php if (isset($end_date)) {
                                                                                                                        echo $end_date;
                                                                                                                    } ?></h3>
                    </center>

                    <!-- <a href="pdf.php?start=<?php echo $start_date; ?>&end=<?php echo $end_date; ?>" class="btn btn-pdf btn-md" title="Descargar" data-toggle="tooltip">
                    Descargar en PDF
                    </a> -->

                    <br>
                    <div class="row" style="display: flex; justify-content: center; margin-left: -40%;">
                        <div class="col-md-6" style="width: 35%; height: 30%;">

                            <!-- Añadimos el script a la página -->

                            <canvas id="gVulnerableB"></canvas>
                            <!-- Incluímos Chart.js -->
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                            <!-- Añadimos el script a la página -->

                            <script>
                                var yValues = [<?php echo $results['totales'] + $results1['totales']; ?>, <?php echo $results2['totales']; ?>, <?php echo $results3['totales'] + $results32['totales']; ?>, <?php echo $results4['totales']; ?>,
                                    <?php echo $results5['totales']; ?>, <?php echo $results6['totales']; ?>, <?php echo $results7['totales']; ?>, <?php echo $results8['totales']; ?>,
                                    <?php echo $results9['totales']; ?>, <?php echo $results10['totales']; ?>, <?php echo $results11['totales']; ?>, <?php echo $results12['totales']; ?>, <?php echo $results13['totales']; ?>
                                ];

                                const ctx3 = document.getElementById('gVulnerableB');
                                const gVulnerableB = new Chart(ctx3, {
                                    type: 'bar',
                                    data: {
                                        labels: ['Comunidad LGBTIQ+', 'Derecho de las mujeres', 'Niñas, niños y adolescentes', 'Personas con discapacidad', 'Personas migrantes', 'Personas que viven con VIH SIDA', 'Grupos indígenas', 'Periodistas',
                                            'Defensores de los derechos humanos', 'Adultos mayores', 'Internos', 'Otros', 'No Aplica'
                                        ],
                                        datasets: [{
                                            label: 'Orientaciones por Grupo Vulnerable',
                                            data: yValues,
                                            backgroundColor: [
                                                '#3E5161', '#C5E2FB', '#90BBE0', '#5A87AD', '#6F90AD', '#6C6E58', '#3E423A', '#417378', '#A4CFBE', '#F4F7D9', '#AC89A6', '#51AFC2', '#427085'
                                            ],
                                            borderColor: [
                                                '#27333D', '#8BA0B3', '#627F99', '#3E5E78', '#405363', '#494A3B', '#22241F', '#2B4C4F', '#6F8C80', '#A9AB96', '#7D6479', '#397A87', '#2D4B59'
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
                                            max: 10000,
                                            stepSize: 10
                                        },

                                    }
                                });
                            </script>

                        </div>
                    </div>

                    <div class="row" style="display: flex; justify-content: center; align-items: center;">
                        <div class="col-md-6" style="margin-top: -1%;">
                            <table class="table table-bordered table-striped">
                                <br><br>
                                <thead>
                                    <tr style="height: 10px;" class="info">
                                        <th class="text-center" style="width: 70%;">Grupo Vulnerable</th>
                                        <th class="text-center" style="width: 30%;">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Comunidad LGBTIQ+</td>
                                        <?php if ($results['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results['totales'] + $results1['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Derechos de las mujeres</td>
                                        <?php if ($results2['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results2['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Niñas, niños y adolescentes</td>
                                        <?php if ($results3['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results3['totales'] + $results32['total'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Personas con discapacidad</td>
                                        <?php if ($results4['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results4['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Personas migrantes</td>
                                        <?php if ($results5['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results5['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Personas que viven con VIH SIDA</td>
                                        <?php if ($results6['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results6['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Grupos indígenas</td>
                                        <?php if ($results7['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results7['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Periodistas</td>
                                        <?php if ($results8['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results8['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Defensores de los derechos humanos</td>
                                        <?php if ($results9['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results9['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Adultos Mayores</td>
                                        <?php if ($results10['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results10['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Internos</td>
                                        <?php if ($results11['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results11['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Otros</td>
                                        <?php if ($results12['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results12['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>No aplica</td>
                                        <?php if ($results13['totales'] != 0) { ?>
                                            <td class="text-center"><?php echo $results13['totales'] ?></td>
                                        <?php } else { ?>
                                            <td class="text-center">0</td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td style="text-align:right;"><b>Total</b></td>
                                        <td>
                                            <?php echo $results['totales'] + $results2['totales'] + $results3['totales'] + $results4['totales'] + $results5['totales'] +
                                                $results6['totales'] + $results7['totales'] + $results8['totales'] + $results9['totales'] + $results10['totales'] + $results11['totales'] +
                                                $results12['totales'] + $results13['totales']
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