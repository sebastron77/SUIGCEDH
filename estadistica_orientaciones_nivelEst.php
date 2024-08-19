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

$total_sin_est = count_by_sin_est('orientacion_canalizacion', 1);
$total_primaria = count_by_primaria('orientacion_canalizacion', 1);
$total_secundaria = count_by_secundaria('orientacion_canalizacion', 1);
$total_preparatoria = count_by_preparatoria('orientacion_canalizacion', 1);
$total_licenciatura = count_by_licenciatura('orientacion_canalizacion', 1);
$total_especialidad = count_by_especialidad('orientacion_canalizacion', 1);
$total_maestria = count_by_maestria('orientacion_canalizacion', 1);
$total_doctorado = count_by_doctorado('orientacion_canalizacion', 1);
$total_posdoctorado = count_by_posdoctorado('orientacion_canalizacion', 1);
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
  <h2 style="margin-top: 15px;">Estadística de Orientaciones (Por nivel de estudios)</h2>
  <div class="row" style="display: flex; justify-content: center; align-items: center;">
    <!-- <div class="col-md-6" style="width: 40%; height: 20%;"> -->
    <div style="width:40%; float:left;">
      <canvas id="myChart"></canvas>
      <!-- Incluímos Chart.js -->
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

      <!-- Añadimos el script a la página -->

      <script>
        var yValues = [<?php echo $total_sin_est['total']; ?>, <?php echo $total_primaria['total']; ?>, <?php echo $total_secundaria['total']; ?>,
        <?php echo $total_preparatoria['total']; ?>, <?php echo $total_licenciatura['total']; ?>, <?php echo $total_especialidad['total']; ?>,
        <?php echo $total_maestria['total']; ?>, <?php echo $total_doctorado['total']; ?>, <?php echo $total_posdoctorado['total']; ?>];

        const ctx = document.getElementById('myChart');
        const myChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: ['Sin Estudios', 'Primaria', 'Secundaria', 'Preparatoria', 'Licenciatura', 'Especialidad', 'Maestría', 'Doctorado', 'Posdoctorado'],
            datasets: [{
              label: 'Orientaciones por Género',
              data: yValues,
              backgroundColor: [
                '#7A8A28', '#9FC983', '#7DB37F', '#B4CCBD', '#354A45', '#195947', '#688C82', '#7DDA87', '#D78D4E'
              ],
              borderColor: [
                '#4C5719', '#73915E', '#577D59', '#728278', '#253330', '#0F362B', '#394D47', '#508C56', '#D78D4E'
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
            <th class="text-center" style="width: 70%;">Nivel de estudios</th>
            <th class="text-center" style="width: 30%;">Cantidad</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Sin Estudios</td>
            <td class="text-center"><?php echo $total_sin_est['total'] ?></td>
          </tr>
          <tr>
            <td>Primaria</td>
            <td class="text-center"><?php echo $total_primaria['total'] ?></td>
          </tr>
          <tr>
            <td>Secundaria</td>
            <td class="text-center"><?php echo $total_secundaria['total'] ?></td>
          </tr>
          <tr>
            <td>Preparatoria</td>
            <td class="text-center"><?php echo $total_preparatoria['total'] ?></td>
          </tr>
          <tr>
            <td>Licenciatura</td>
            <td class="text-center"><?php echo $total_licenciatura['total'] ?></td>
          </tr>
          <tr>
            <td>Especialidad</td>
            <td class="text-center"><?php echo $total_especialidad['total'] ?></td>
          </tr>
          <tr>
            <td>Maestría</td>
            <td class="text-center"><?php echo $total_maestria['total'] ?></td>
          </tr>
          <tr>
            <td>Doctorado</td>
            <td class="text-center"><?php echo $total_doctorado['total'] ?></td>
          </tr>
          <tr>
            <td>Posdoctorado</td>
            <td class="text-center"><?php echo $total_posdoctorado['total'] ?></td>
          </tr>
          <tr>
            <td style="text-align:right;"><b>Total</b></td>
            <td>
              <?php echo $total_sin_est['total'] + $total_primaria['total'] + $total_secundaria['total'] + $total_preparatoria['total'] + $total_licenciatura['total'] + $total_especialidad['total'] + 
              $total_maestria['total'] + $total_doctorado['total'] + $total_posdoctorado['total'] ?>
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