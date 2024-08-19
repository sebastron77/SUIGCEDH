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
$total_orien_mujer = count_by_id_mujer('orientacion_canalizacion', 2);
$total_orien_hombre = count_by_id_hombre('orientacion_canalizacion', 2);
$total_orien_lgbt = count_by_id_lgbt('orientacion_canalizacion', 2);
?>

<?php include_once('layouts/header.php'); ?>

<a href="tabla_estadistica_canalizacion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
  Regresar
</a><br><br>
<!-- Debemos de tener Canvas en la página -->
<center>
  <button id="btnCrearPdf" style="margin-top: -30px;" class="btn btn-pdf btn-md">Guardar en PDF</button>
  <div id="prueba">
    <center>
      <h2 style="margin-top: 10px;">Estadística de Canalizaciones (Por género)</h2>
    </center>
    <div class="row" style="display: flex; justify-content: center; align-items: center;">
      <!-- <div class="col-md-6" style="width: 40%; height: 20%;"> -->
      <div style="width:40%; float:left;">
        <canvas id="myChart"></canvas>
        <!-- Incluímos Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Añadimos el script a la página -->

        <script>
          var yValues = [<?php echo $total_orien_hombre['total']; ?>, <?php echo $total_orien_mujer['total']; ?>, <?php echo $total_orien_lgbt['total']; ?>];

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

      <!-- </div> -->
    </div>
    <div class=" row" style="display: flex; justify-content: center; align-items: center;">
      <div style="width:40%; float:right; margin-left: 50px;  margin-top: 40px">
        <table class="table table-dark table-bordered table-striped">
          <thead>
            <tr style="height: 10px;" class="table-info">
              <th class="text-center" style="width: 70%;">Género</th>
              <th class="text-center" style="width: 30%;">Cantidad</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Mujer</td>
              <?php if ($total_orien_mujer['total'] != 0) { ?>
                <td class="text-center"><?php echo $total_orien_mujer['total'] ?></td>
              <?php } else { ?>
                <td class="text-center">0</td>
              <?php } ?>
            </tr>
            <tr>
              <td>Hombre</td>
              <?php if ($total_orien_hombre['total'] != 0) { ?>
                <td class="text-center"><?php echo $total_orien_hombre['total'] ?></td>
              <?php } else { ?>
                <td class="text-center">0</td>
              <?php } ?>
            </tr>
            <tr>
              <td>LGBTIQ+</td>
              <?php if ($total_orien_lgbt['total'] != 0) { ?>
                <td class="text-center"><?php echo $total_orien_lgbt['total'] ?></td>
              <?php } else { ?>
                <td class="text-center">0</td>
              <?php } ?>
            </tr>
            <tr>
              <td style="text-align:right;"><b>Total</b></td>
              <td class="text-center"><?php echo $total_orien_mujer['total'] + $total_orien_hombre['total'] + $total_orien_lgbt['total'] ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</center>

<?php include_once('layouts/footer.php'); ?>