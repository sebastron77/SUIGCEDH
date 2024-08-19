<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Estadísticas de Canalizaciones';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
// page_require_area(7);
if ($nivel_user <= 3) {
  page_require_level(3);
}
if ($nivel_user == 7) {
  page_require_level_exacto(7);
}
if ($nivel_user == 5) {
  page_require_level_exacto(5);
}
// if ($nivel_user > 3 && $nivel_user < 7) :
//   redirect('home.php');
// endif;
if ($nivel_user > 7) :
  redirect('home.php');
endif;

$total_asesorv = count_by_asesorv('orientacion_canalizacion', 2);
$total_asistentev = count_by_asistentev('orientacion_canalizacion', 2);
$total_comp = count_by_comp('orientacion_canalizacion', 2);
$total_escrito = count_by_escrito('orientacion_canalizacion', 2);
$total_vt = count_by_vt('orientacion_canalizacion', 2);
$total_ve = count_by_ve('orientacion_canalizacion', 2);
$total_cndh = count_by_cndh('orientacion_canalizacion', 2);
?>

<?php include_once('layouts/header.php'); ?>

<a href="tabla_estadistica_canalizacion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
  Regresar
</a><br><br>
<center>
  <button id="btnCrearPdf" style="margin-top: -40px; background: #FE2C35; color: white; font-size: 12px;" class="btn btn-pdf btn-md">Guardar en PDF</button>
  <div id="prueba">
    <center>
      <h2 style="margin-top: 10px;">Estadística de Canalizaciones (Por medio de presentación)</h2><br>
    </center>
    <div class="row" style="display: flex; justify-content: center; align-items: center; margin-left:25%;">
      <div style="width:60%; float:left;">
        <!-- <div class="col-md-6" style="width: 50%; height: 20%;"> -->
        <canvas id="mPresentacion"></canvas>
        <!-- Incluímos Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Añadimos el script a la página -->

        <script>
          var yValues = [<?php echo $total_asesorv['total']; ?>, <?php echo $total_asistentev['total']; ?>, <?php echo $total_comp['total']; ?>, <?php echo $total_escrito['total']; ?>,
            <?php echo $total_vt['total']; ?>, <?php echo $total_ve['total']; ?>, <?php echo $total_cndh['total']; ?>
          ];
          const ctx5 = document.getElementById('mPresentacion');
          const mPresentacion = new Chart(ctx5, {
            type: 'bar',
            data: {
              labels: ['Asesor Virtual', 'Asistente Virtual', 'Comparecencia', 'Escrito', 'Vía telefónica', 'Vía electrónica', 'Comisión Nacional de los Derechos Humanos'],
              datasets: [{
                label: 'Orientaciones por Medio de Presentación',
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
      </div>
      <!-- </div> -->

      <!-- <div class="col-md-6" style="width: 420px; height: 250px;"> -->
      <div style="width:40%; float:right;">
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
    <div class="row" style="display: flex; justify-content: center; align-items: center; margin-top: 20px;">
      <div style="width:40%; float:right; margin-left: 50px;">
        <table class="table table-dark table-bordered table-striped">
          <thead>
            <tr style="height: 10px;" class="table-info">
              <th class="text-center" style="width: 70%;">Medio de presentación</th>
              <th class="text-center" style="width: 30%;">Cantidad</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Asesor Virtual</td>
              <td class="text-center"><?php echo $total_asesorv['total'] ?></td>
            </tr>
            <tr>
              <td>Asistente Virtual</td>
              <td class="text-center"><?php echo $total_asistentev['total'] ?></td>
            </tr>
            <tr>
              <td>Comparecencia</td>
              <td class="text-center"><?php echo $total_comp['total'] ?></td>
            </tr>
            <tr>
              <td>Escrito</td>
              <td class="text-center"><?php echo $total_escrito['total'] ?></td>
            </tr>
            <tr>
              <td>Vía telefónica</td>
              <td class="text-center"><?php echo $total_vt['total'] ?></td>
            </tr>
            <tr>
              <td>Vía electrónica</td>
              <td class="text-center"><?php echo $total_ve['total'] ?></td>
            </tr>
            <tr>
              <td>Comisión Nacional de los Derechos Humanos</td>
              <td class="text-center"><?php echo $total_cndh['total'] ?></td>
            </tr>
            <tr>
              <td style="text-align:right;"><b>Total</b></td>
              <td class="text-center"><?php echo $total_asesorv['total'] + $total_asistentev['total'] + $total_comp['total'] + $total_escrito['total'] +
                                        $total_vt['total'] + $total_ve['total'] + $total_cndh['total'] ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</center>
<?php include_once('layouts/footer.php'); ?>