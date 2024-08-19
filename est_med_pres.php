<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Estadística Medio Presentación';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
$medios_pres = med_pres((int)$_GET['id']);

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
    <div id="prueba">
      <?php if((int)$_GET['id'] == 1): ?>
      <center>
        <h3 style="margin-top: 10px; color: #3a3d44;">Estadística de Orientaciones (Por medio de presentación)</h3><br>
      </center>
      <?php endif; ?>
      <?php if((int)$_GET['id'] == 2): ?>
      <center>
        <h3 style="margin-top: 10px; color: #3a3d44;">Estadística de Canalizaciones (Por medio de presentación)</h3><br>
      </center>
      <?php endif; ?>
      <div class="row" style="display: flex; justify-content: center; align-items: center; margin-left:-150px;">
        <div style="width:45%; float:left;">
          <canvas id="mPresentacion"></canvas>
          <!-- Incluímos Chart.js -->
          <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>

          <!-- Añadimos el script a la página -->

          <script>
            var yValues = [<?php foreach ($medios_pres as $med_pres) : ?><?php echo $med_pres['total']; ?>, <?php endforeach; ?>];
            Chart.defaults.font.family = "Montserrat";
            Chart.defaults.font.size = 12;
            const ctx5 = document.getElementById('mPresentacion');
            const mPresentacion = new Chart(ctx5, {
              type: 'bar',
              data: {
                labels: [<?php foreach ($medios_pres as $med_pres) : ?> '<?php echo $med_pres['descripcion']; ?>', <?php endforeach; ?>],
                datasets: [{
                  label: 'Orientaciones por Medio de Presentación',
                  data: yValues,
                  backgroundColor: [
                    '#3E5161', '#C5E2FB', '#90BBE0', '#5A87AD', '#6F90AD', '#6C6E58', '#3E423A', '#417378', '#A4CFBE', '#F4F7D9', '#AC89A6', '#51AFC2', '#427085'
                  ],
                  borderColor: [
                    '#27333D', '#8BA0B3', '#627F99', '#3E5E78', '#405363', '#494A3B', '#22241F', '#2B4C4F', '#6F8C80', '#A9AB96', '#7D6479', '#397A87', '#2D4B59'
                  ],
                  borderWidth: 2,
                }]
              },
              options: {
                legend: {
                  display: false,
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
              },
            });
          </script>
        </div>
      </div>
      <div class="row" style="display: flex; justify-content: center; align-items: center;">
        <div style="width: 40%; float: right; margin-left: 50px; margin-top: 40px">
          <table class="table table-bordered table-striped" style='font-family: "Montserrat", sans-serif; font-size: 14px;'>
            <thead class="thead-purple">
              <tr style="height: 10px;">
                <th class="text-center" style="width: 70%;">Medio de Presentación</th>
                <th class="text-center" style="width: 30%;">Cantidad</th>
              </tr>
            </thead>
            <tbody style="background: white;">
              <?php $total=0; 
			  foreach ($medios_pres as $med_pres) : ?>
                <tr>
                  <td>
                    <?php echo remove_junk(ucwords($med_pres['descripcion'])) ?>
                  </td>
                  <td>
                    <?php echo remove_junk(ucwords($med_pres['total'])) ?>
                    <?php $total += (int) $med_pres['total']; ?>
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
</center>
<?php include_once('layouts/footer.php'); ?>