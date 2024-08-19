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

$total_gv_lgbt = count_by_comLg('orientacion_canalizacion', 2);
$total_gv_lgbt2 = count_by_comLg2('orientacion_canalizacion', 2);
$total_der_mujer = count_by_derMuj('orientacion_canalizacion', 2);
$total_nna = count_by_nna('orientacion_canalizacion', 2);
$total_nna2 = count_by_nna2('orientacion_canalizacion', 2);
$total_disc = count_by_disc('orientacion_canalizacion', 2);
$total_mig = count_by_mig('orientacion_canalizacion', 2);
$total_vih = count_by_vih('orientacion_canalizacion', 2);
$total_gi = count_by_gi('orientacion_canalizacion', 2);
$total_perio = count_by_perio('orientacion_canalizacion', 2);
$total_ddh = count_by_ddh('orientacion_canalizacion', 2);
$total_am = count_by_am('orientacion_canalizacion', 2);
$total_int = count_by_int('orientacion_canalizacion', 2);
$total_otros = count_by_otros('orientacion_canalizacion', 2);
$total_na = count_by_na('orientacion_canalizacion', 2);
?>

<?php include_once('layouts/header.php'); ?>

<a href="tabla_estadistica_canalizacion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
  Regresar
</a>
<center>
  <button id="btnCrearPdf" style="margin-top: -30px;" class="btn btn-pdf btn-md">Guardar en PDF</button>
</center>
<!-- Debemos de tener Canvas en la página -->
<center>
  <div id="prueba">
    <center>
      <h2 style="margin-top: 10px;">Estadística de Canalizaciones (Por grupo vulnerable)</h2>
    </center>
    <div class=" row" style="display: flex; justify-content: center; align-items: center; margin-left:-100px">
      <!-- <div class="col-md-6" style="width: 50%; height: 20%;"> -->
      <div style="width:50%; float:left;">
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
    </div>
    <div class=" row" style="display: flex; justify-content: center; align-items: center;">
      <div style="width:40%; float:right; margin-left: 50px;  margin-top: 40px">
        <table class="table table-dark table-bordered table-striped">
          <thead>
            <tr style="height: 10px;" class="table-info">
              <th class="text-center" style="width: 70%;">Grupo Vulnerable</th>
              <th class="text-center" style="width: 30%;">Cantidad</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Comunidad LGBTIQ+</td>
              <td class="text-center"><?php echo $total_gv_lgbt['total'] + $total_gv_lgbt2['total'] ?></td>
            </tr>
            <tr>
              <td>Derechos de las mujeres</td>
              <td class="text-center"><?php echo $total_der_mujer['total'] ?></td>
            </tr>
            <tr>
              <td>Niñas, niños y adolescentes</td>
              <td class="text-center"><?php echo $total_nna['total'] + $total_nna2['total'] ?></td>
            </tr>
            <tr>
              <td>Personas con discapacidad</td>
              <td class="text-center"><?php echo $total_disc['total'] ?></td>
            </tr>
            <tr>
              <td>Personas migrantes</td>
              <td class="text-center"><?php echo $total_mig['total'] ?></td>
            </tr>
            <tr>
              <td>Personas que viven con VIH SIDA</td>
              <td class="text-center"><?php echo $total_vih['total'] ?></td>
            </tr>
            <tr>
              <td>Grupos indígenas</td>
              <td class="text-center"><?php echo $total_gi['total'] ?></td>
            </tr>
            <tr>
              <td>Periodistas</td>
              <td class="text-center"><?php echo $total_perio['total'] ?></td>
            </tr>
            <tr>
              <td>Defensores de los derechos humanos</td>
              <td class="text-center"><?php echo $total_ddh['total'] ?></td>
            </tr>
            <tr>
              <td>Adultos Mayores</td>
              <td class="text-center"><?php echo $total_am['total'] ?></td>
            </tr>
            <tr>
              <td>Internos</td>
              <td class="text-center"><?php echo $total_int['total'] ?></td>
            </tr>
            <tr>
              <td>Otros</td>
              <td class="text-center"><?php echo $total_otros['total'] ?></td>
            </tr>
            <tr>
              <td>No aplica</td>
              <td class="text-center"><?php echo $total_na['total'] ?></td>
            </tr>
            <tr>
              <td style="text-align:right;"><b>Total</b></td>
              <td class="text-center"><?php echo $total_gv_lgbt['total'] + $total_gv_lgbt2['total'] + $total_der_mujer['total'] + $total_nna['total'] +
                                        $total_nna2['total'] + $total_disc['total'] + $total_mig['total'] + $total_vih['total'] + $total_gi['total'] + $total_perio['total'] +
                                        $total_ddh['total'] + $total_am['total'] + $total_int['total'] + $total_otros['total'] + $total_na['total'] ?>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</center>


<?php include_once('layouts/footer.php'); ?>