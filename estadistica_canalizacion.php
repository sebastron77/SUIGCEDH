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
$total_orien_mujer = count_by_id_mujerC('orientacion_canalizacion', 2);
$total_orien_hombre = count_by_id_hombreC('orientacion_canalizacion', 2);
$total_orien_lgbt = count_by_id_lgbtC('orientacion_canalizacion', 2);

$total_gv_lgbt = count_by_comLgC('orientacion_canalizacion', 2);
$total_der_mujer = count_by_derMujC('orientacion_canalizacion', 2);
$total_nna = count_by_nnaC('orientacion_canalizacion', 2);
$total_disc = count_by_discC('orientacion_canalizacion', 2);
$total_mig = count_by_migC('orientacion_canalizacion', 2);
$total_vih = count_by_vihC('orientacion_canalizacion', 2);
$total_gi = count_by_giC('orientacion_canalizacion', 2);
$total_perio = count_by_perioC('orientacion_canalizacion', 2);
$total_ddh = count_by_ddhC('orientacion_canalizacion', 2);
$total_am = count_by_amC('orientacion_canalizacion', 2);
$total_int = count_by_intC('orientacion_canalizacion', 2);
$total_otros = count_by_otrosC('orientacion_canalizacion', 2);
$total_na = count_by_naC('orientacion_canalizacion', 2);

$total_asesorv = count_by_asesorvC('orientacion_canalizacion', 2);
$total_asistentev = count_by_asistentevC('orientacion_canalizacion', 2);
$total_comp = count_by_compC('orientacion_canalizacion', 2);
$total_escrito = count_by_escritoC('orientacion_canalizacion', 2);
$total_vt = count_by_vtC('orientacion_canalizacion', 2);
$total_ve = count_by_veC('orientacion_canalizacion', 2);
$total_cndh = count_by_cndhC('orientacion_canalizacion', 2);
?>

<?php include_once('layouts/header.php'); ?>

<!-- Debemos de tener Canvas en la página -->
<center>
  <h2 style="margin-top: -10px;">Estadística de Canalizaciones (Por género)</h2>
  <div class="row" style="display: flex; justify-content: center; align-items: center;">
    <div style="width:40%; float:left;">
      <!-- <div class="col-md-6" style="width: 40%; height: 20%;"> -->
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
                '#8C142A',
                '#654033',
                '#5C0E13'
              ],
              borderColor: [
                '#630E1E',
                '#4F3228',
                '#3B090C'
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
    <!-- </div> -->

    <!-- <div class="col-md-6" style="width: 250px; height: 100px;"> -->
    <div style="width:40%; float:right;">
      <!-- Debemos de tener Canvas en la página -->
      <canvas id="miGrafo"></canvas>

      <!-- Incluímos Chart.js -->
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

      <!-- Añadimos el script a la página -->
      <script>
        var yValues = [<?php echo $total_orien_hombre['total']; ?>, <?php echo $total_orien_mujer['total']; ?>, <?php echo $total_orien_lgbt['total']; ?>];
        const ctx2 = document.getElementById('miGrafo');
        const miGrafo = new Chart(ctx2, {
          type: 'pie',
          data: {
            labels: ['Hombres', 'Mujeres', 'LGBTIQ+'],
            datasets: [{
              data: yValues,
              backgroundColor: [
                '#8C142A',
                '#654033',
                '#5C0E13'
              ],
              hoverOffset: 4
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

      <!-- Renderizamos la gráfica -->
      <script>
        const miGrafo = new Chart(
          document.getElementById('miGrafo'),
          config
        );
      </script>
    </div>
    <!-- </div> -->
  </div>
</center>
<!-- <div style="margin-top: 120px;"> -->
<hr style="margin-top: 120px; height:2px;border-width:0;background-color:#aaaaaa">
<!-- </div> -->



<center>
  <h2 style="margin-top: -10px;">Estadística de Canalizaciones (Por grupo vulnerable)</h2>
  <div class="row" style="display: flex; justify-content: center; align-items: center;">
    <div class="col-md-6" style="width: 50%; height: 20%;">
      <canvas id="gVulnerableB"></canvas>
      <!-- Incluímos Chart.js -->
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

      <!-- Añadimos el script a la página -->

      <script>
        var yValues = [<?php echo $total_gv_lgbt['total']; ?>, <?php echo $total_der_mujer['total']; ?>, <?php echo $total_nna['total']; ?>, <?php echo $total_disc['total']; ?>,
          <?php echo $total_mig['total']; ?>, <?php echo $total_vih['total']; ?>, <?php echo $total_gi['total']; ?>, <?php echo $total_perio['total']; ?>,
          <?php echo $total_ddh['total']; ?>, <?php echo $total_am['total']; ?>, <?php echo $total_int['total']; ?>, <?php echo $total_otros['total']; ?>, <?php echo $total_na['total']; ?>
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
                '#638BBF', '#8BB4D9', '#022607', '#6AA673', '#498C4D', '#8C888C', '#3588A9', '#2BB79A', '#5D6754', '#1E402A', '#487357', '#65736A', '#3C5282'
              ],
              borderColor: [
                '#3E5778', '#5A758C', '#011704', '#4E7A55', '#36693A', '#595759', '#245E75', '#1E8570', '#41473B', '#142B1C', '#324F3C', '#48524B', '#2A3A5C'
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

    <div class="col-md-6" style="width: 420px; height: 250px;">
      <!-- Debemos de tener Canvas en la página -->
      <canvas id="gVulnerableC"></canvas>

      <!-- Incluímos Chart.js -->
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

      <!-- Añadimos el script a la página -->
      <script>
        var yValues = [<?php echo $total_gv_lgbt['total']; ?>, <?php echo $total_der_mujer['total']; ?>, <?php echo $total_nna['total']; ?>, <?php echo $total_disc['total']; ?>,
          <?php echo $total_mig['total']; ?>, <?php echo $total_vih['total']; ?>, <?php echo $total_gi['total']; ?>, <?php echo $total_perio['total']; ?>,
          <?php echo $total_ddh['total']; ?>, <?php echo $total_am['total']; ?>, <?php echo $total_int['total']; ?>, <?php echo $total_otros['total']; ?>, <?php echo $total_na['total']; ?>
        ];
        const ctx4 = document.getElementById('gVulnerableC');
        const gVulnerableC = new Chart(ctx4, {
          type: 'pie',
          data: {
            labels: ['Comunidad LGBTIQ+', 'Derecho de las mujeres', 'Niñas, niños y adolescentes', 'Personas con discapacidad', 'Personas migrantes', 'Personas que viven con VIH SIDA', 'Grupos indígenas', 'Periodistas',
              'Defensores de los derechos humanos', 'Adultos mayores', 'Internos', 'Otros', 'No Aplica'
            ],
            datasets: [{
              data: yValues,
              backgroundColor: [
                '#638BBF', '#8BB4D9', '#022607', '#6AA673', '#498C4D', '#8C888C', '#3588A9', '#2BB79A', '#5D6754', '#1E402A', '#487357', '#65736A', '#3C5282'
              ],
              hoverOffset: 4
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

      <!-- Renderizamos la gráfica -->
      <script>
        const miGrafo = new Chart(
          document.getElementById('miGrafo'),
          config
        );
      </script>
    </div>
  </div>
</center>

<hr style="margin-top: 140px; height:2px;border-width:0;background-color:#aaaaaa">



<center>
  <h2 style="margin-top: -10px;">Estadística de Canalizaciones (Por medio de presentación)</h2>
  <div class="row" style="display: flex; justify-content: center; align-items: center;">
    <div class="col-md-6" style="width: 50%; height: 20%;">
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
                '#06314F', '#0B588F', '#02465B', '#01133B', '#023E73', '#6EC0DB', '#7A9FBF'
              ],
              borderColor: [
                '#042136', '#07395C', '#012936', '#011030', '#022F57', '#467B8C', '#50687D'
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
            responsive: true
          }
        });
      </script>

    </div>

    <div class="col-md-6" style="width: 420px; height: 250px;">
      <!-- Debemos de tener Canvas en la página -->
      <canvas id="mPresentacionC"></canvas>

      <!-- Incluímos Chart.js -->
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

      <!-- Añadimos el script a la página -->
      <script>
        var yValues = [<?php echo $total_asesorv['total']; ?>, <?php echo $total_asistentev['total']; ?>, <?php echo $total_comp['total']; ?>, <?php echo $total_escrito['total']; ?>,
          <?php echo $total_vt['total']; ?>, <?php echo $total_ve['total']; ?>, <?php echo $total_cndh['total']; ?>
        ];
        const ctx6 = document.getElementById('mPresentacionC');
        const mPresentacionC = new Chart(ctx6, {
          type: 'pie',
          data: {
            labels: ['Asesor Virtual', 'Asistente Virtual', 'Comparecencia', 'Escrito', 'Vía telefónica', 'Vía electrónica', 'Comisión Nacional de los Derechos Humanos'],
            datasets: [{
              data: yValues,
              backgroundColor: [
                '#06314F', '#0B588F', '#02465B', '#01133B', '#023E73', '#6EC0DB', '#7A9FBF'
              ],
              hoverOffset: 4
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

      <!-- Renderizamos la gráfica -->
      <script>
        const miGrafo = new Chart(
          document.getElementById('miGrafo'),
          config
        );
      </script>
    </div>
  </div>
</center>

<hr style="margin-top: 140px; height:2px;border-width:0;background-color:#aaaaaa">
<?php include_once('layouts/footer.php'); ?>