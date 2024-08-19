<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Estadísticas de Quejas';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
// page_require_area(7);
if ($nivel_user <= 3) {
    page_require_level(3);
}
if ($nivel_user == 5) {
    page_require_level_exacto(5);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
// if ($nivel_user > 3 && $nivel_user < 7) :
//     redirect('home.php');
// endif;
if ($nivel_user > 7) :
    redirect('home.php');
endif;

$all_quejas = total_porAutoridad('quejas');
?>

<?php include_once('layouts/header.php'); ?>

<a href="tabla_estadistica_quejas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
    Regresar
</a><br><br>
<center>
    <button id="btnCrearPdf" style="margin-top: -40px; background: #FE2C35; color: white; font-size: 12px;" class="btn btn-pdf btn-md">Guardar en PDF</button>
    <div id="prueba">
        <center>
            <h2 style="margin-top: 10px;">Estadística de Quejas (Por autoridad responsable)</h2><br>
        </center>
        <div class="row" style="display: flex; justify-content: center; align-items: center; margin-left:25%;">
            <div style="width:90%; float:left; margin-left: -170px;">
                <!-- <div class="col-md-6" style="width: 50%; height: 20%;"> -->
                <canvas id="mPresentacion"></canvas>
                <!-- Incluímos Chart.js -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                <?php
                $datos = array(); // Creación de arreglo con la cantidad de quejas de cada autoridad
                $autoridades = array(); // Creación de arreglo con todas las autoridades

                // Ciclo para recorrer todas las quejas
                foreach ($all_quejas as $a_quejas) {
                    // Condición para verificar que la cantidad de quejas sea mayor o igual a 20
                    if ($a_quejas['total'] < 20) {
                        array_push($datos, $a_quejas['total']); // Guarda los datos de las cantidades en el arreglo
                        array_push($autoridades, $a_quejas['autoridad_responsable']); // Guarda los datos de las autoridades en el arreglo
                    }
                }
                $arreglo = json_encode($datos); // Convierte a JSON los arreglos
                $arreglo2 = json_encode($autoridades); // Convierte a JSON los arreglos
                $lenght = count($datos); // Checa la cantidad de datos que hay en el arreglo
                ?>

                <?php for ($i = 0; $i < $lenght; $i++) { ?>
                    <!-- Recorre el tamaño del arreglo -->
                    <script>
                        var yValues = <?php echo $arreglo; ?>; // Son las cantidades de quejas que tiene cada autoridad responsable
                        const ctx5 = document.getElementById('mPresentacion');
                        const mPresentacion = new Chart(ctx5, {
                            type: 'bar',
                            data: {
                                labels: <?php echo $arreglo2; ?>, // Son las autoridades responsables
                                datasets: [{
                                    label: 'Quejas por Autoridad Responsable',
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
                                    max: 10000,
                                    stepSize: 10
                                },
                                responsive: true,
                                scales: {
                                    y: {
                                        ticks: {
                                            color: '#379CE2',
                                            beginAtZero: true
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            color: '#379CE2',
                                            beginAtZero: true
                                        }
                                    }
                                }
                            }
                        });
                    </script>
                <?php } ?>
            </div>
            <!-- </div> -->
            <!-- <div class="html2pdf__page-break"></div> -->
            <!-- <div class="col-md-6" style="width: 420px; height: 250px;"> -->
            <div style="width:40%; float:right; margin-left: 50px">
                <!-- Debemos de tener Canvas en la página -->
                <canvas id="mPresentacionC"></canvas>

                <!-- Incluímos Chart.js -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                <!-- Renderizamos la gráfica -->
                <script>
                    const miGrafo = new Chart(
                        document.getElementById('miGrafo'),
                        config
                    );
                </script>
                <!-- </div> -->
            </div>
        </div>
        <div class="row" style="display: flex; justify-content: center; align-items: center; margin-left: -30px;">
            <div style="width:60%; float:right; margin-left: 40px; margin-top: -10%;">
                <table class="table table-dark table-bordered table-striped">
                    <thead>
                        <tr style="height: 10px;" class="table-info">
                            <th class="text-center" style="width: 40%;">Autoridad Responsable</th>
                            <th class="text-center" style="width: 5%;">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_quejas as $a_quejas) : ?>
                            <?php if ($a_quejas['total'] < 20) : ?>
                                <tr>
                                    <td><?php echo remove_junk(ucwords($a_quejas['autoridad_responsable'])) ?></td>
                                    <td class="text-center"><?php echo remove_junk(ucwords($a_quejas['total'])) ?></td>
                                </tr>
                                <?php $suma = $suma + $a_quejas['total'] ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <tr>
                            <td style="text-align:right;"><b>Total</b></td>
                            <td><?php echo $suma; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</center>
<?php include_once('layouts/footer.php'); ?>