<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Estadísticas de Orientaciones';
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
//   redirect('home.php');
// endif;
if ($nivel_user > 7) :
  redirect('home.php');
endif;

$total_mujeres = count_by_id_mujer('orientacion_canalizacion', 1);
$total_hombres = count_by_id_hombre('orientacion_canalizacion', 1);
$total_lgbtiq = count_by_id_lgbt('orientacion_canalizacion', 1);
$total_lgbt = count_by_id_lgbt2('orientacion_canalizacion', 1);
?>

<?php include_once('layouts/header.php'); ?>

<a href="tabla_estadistica_orientacion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
  Regresar
</a>
<center>
  <button id="btnCrearPdf" style="margin-top: -30px; background: #FE2C35; color: white; font-size: 12px;" class="btn btn-pdf btn-md">Guardar en PDF</button>
</center>
<!-- Debemos de tener Canvas en la página -->
<div id="prueba">
<center>
  <h2 style="margin-top: 15px;">Estadística de Orientaciones (Por género)</h2>
  <div class="row" style="display: flex; justify-content: center; align-items: center;">
    <!-- <div class="col-md-6" style="width: 40%; height: 20%;"> -->
    <div style="width:40%; float:left;">
      <canvas id="myChart"></canvas>
      <!-- Incluímos Chart.js -->
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

      <!-- Añadimos el script a la página -->

      <script>
        var yValues = [<?php echo $total_mujeres['total']; ?>, <?php echo $total_hombres['total']; ?>, <?php echo $total_lgbtiq['total'] + $total_lgbt['total']; ?>];

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
            <?php if ($total_mujeres['total'] != 0) { ?>
              <td class="text-center"><?php echo $total_mujeres['total'] ?></td>
            <?php } else { ?>
              <td class="text-center">0</td>
            <?php } ?>
          </tr>
          <tr>
            <td>Hombre</td>
            <td class="text-center"><?php echo $total_hombres['total'] ?></td>
          </tr>
          <tr>
            <td>LGBTIQ+</td>
            <td class="text-center"><?php echo $total_lgbtiq['total'] + $total_lgbt['total'] ?></td>
          </tr>
          <tr>
            <td style="text-align:right;"><b>Total</b></td>
            <td>
              <?php echo $total_mujeres['total'] + $total_hombres['total'] + $total_lgbtiq['total'] + $total_lgbt['total'] ?>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  </div>
  </div>
</center>

<?php include_once('layouts/footer.php'); ?>