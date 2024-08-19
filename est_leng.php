<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Estadística Lengua';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
$lenguas = lengua((int)$_GET['id']);

if ($nivel_user <= 3) {
    page_require_level(3);
}
if ($nivel_user == 5) {
    page_require_level_exacto(5);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}

if ($nivel_user > 7 && $nivel_user < 50) :
  redirect('home.php');
endif;
?>

<?php include_once('layouts/header.php'); ?>
<?php if((int)$_GET['id'] == 1): ?>
<a href="tabla_estadistica_orientacion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar" style="margin-bottom: 15px; margin-top: -15px;">
    Regresar
</a>
<?php endif; ?>
<?php if((int)$_GET['id'] == 2): ?>
<a href="tabla_estadistica_canalizacion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar" style="margin-bottom: 15px; margin-top: -15px;">
    Regresar
</a>
<?php endif; ?>
<div class="panel-body">
    <center>
        <button id="btnCrearPdf" style="margin-top: -15px; background: #FE2C35; color: white; font-size: 12px;" class="btn btn-pdf btn-md">Guardar en PDF</button>
    </center>
    <!-- Debemos de tener Canvas en la página -->
    <div id="prueba">
        <center>
            <?php if((int)$_GET['id'] == 1): ?>
            <h3 style="margin-top: 10px; color: #3a3d44;">Estadística de Orientaciones (Por Lengua)</h2>
            <?php endif; ?>
            <?php if((int)$_GET['id'] == 2): ?>
            <h3 style="margin-top: 10px; color: #3a3d44;">Estadística de Canalizaciones (Por Lengua)</h2>
            <?php endif; ?>
                <div class="row" style="display: flex; justify-content: center; align-items: center;">
                    <!-- <div class="col-md-6" style="width: 40%; height: 20%;"> -->
                    <div style="width:40%; float:left;">
                        <canvas id="myChart"></canvas>
                        <!-- Incluímos Chart.js -->
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                        <!-- Añadimos el script a la página -->

                        <script>
                            var yValues = [<?php foreach ($lenguas as $lengua) : ?><?php echo $lengua['total']; ?>, <?php endforeach; ?>];
                            Chart.defaults.font.family = "Montserrat";
                            Chart.defaults.font.size = 12;
                            const ctx = document.getElementById('myChart');
                            const myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: [<?php foreach ($lenguas as $lengua) : ?> '<?php echo $lengua['lengua']; ?>', <?php endforeach; ?>],
                                    datasets: [{
                                        label: 'Orientaciones por Género',
                                        data: yValues,
                                        backgroundColor: [
                                            '#024554',
                                            '#53736A',
                                            '#C2C0A6'
                                        ],

                                        borderColor: [
                                            '#024554',
                                            '#53736A',
                                            '#C2C0A6'
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
                                    scales: {
                                        y: {
                                            ticks: {
                                                color: '#3a3d44',
                                                beginAtZero: true
                                            }
                                        },
                                        x: {
                                            ticks: {
                                                color: '#3a3d44',
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                }
                            });
                        </script>
                    </div>
                </div>
                <div class=" row" style="display: flex; justify-content: center; align-items: center;">
                    <div style="width:40%; float:right; margin-left: 50px;  margin-top: 40px">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-purple">
                                <tr style="height: 10px;">
                                    <th class="text-center" style="width: 70%;">Lenguaje</th>
                                    <th class="text-center" style="width: 30%;">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody style="background: white;">
                                <?php $total=0;  foreach ($lenguas as $lengua) : ?>
                                    <tr>
                                        <td>
                                            <?php echo remove_junk(ucwords($lengua['lengua'])) ?>
                                        </td>
                                        <td>
                                            <?php echo remove_junk(ucwords($lengua['total'])) ?>
                                            <?php $total = $total + $lengua['total']; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td style="text-align:right;"><b>Total</b></td>
                                    <td>
                                        <?php echo $total; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
    </div>
</div>
</div>
</center>

<?php include_once('layouts/footer.php'); ?>