<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Estadística Personal-Sueldo';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
$monto = isset($_GET['id']) ? $_GET['id'] : 'bruto';
$solicitudes = persSueldo("monto_".$monto);

?>

<?php include_once('layouts/header.php'); ?>
<script>
$(document).ready(function(){
  $("#sueldo").change(function(){
	
	//alert($(this).val());
	window.open("est_personal_sueldo.php?id="+$(this).val(),"_self");
  });
});
</script>
  <a href="tabla_estadistica_personal.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar" style="margin-bottom: 15px; margin-top: -15px;">
    Regresar  
	</a>
<div class="panel-body">
  <center>
    <button id="btnCrearPdf" style="margin-top: -15px; background: #FE2C35; color: white; font-size: 12px;" class="btn btn-pdf btn-md">Guardar en PDF</button>
  </center>
  <br>
  <!-- Debemos de tener Canvas en la página -->
  <div id="prueba">
    <center>
		
<div class="row" style="TEXT-ALIGN: center; width: 25%;margin: 0 auto">
	<div class="panel panel-default">
			<div class="col-md-12">
                        <div class="form-group">
                            <label for="autoridad">Tipo Sueldo</label>
                             <select class="form-control" name="sueldo" id="sueldo"  >
                                <option value="0">Escoge una opción</option>
                                <option value="bruto">Sueldo Bruto</option>
                                <option value="neto">Sueldo Neto</option>
                            </select>
                        </div>
            </div>
    </div>
</div>
        <h3 style="margin-top: 10px; color: #3a3d44;">Estadística de Personal (Por Sueldo <?php echo $monto?>)</h2>
             
          <div class="row" style="display: flex; justify-content: center; align-items: center;">
            <!-- <div class="col-md-6" style="width: 40%; height: 20%;"> -->
            <div style="width:40%; float:left;">
              <canvas id="myChart"></canvas>
              <!-- Incluímos Chart.js -->
              <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

              <!-- Añadimos el script a la página -->

              <script>
                var yValues = [<?php foreach ($solicitudes as $datos) : ?><?php echo $datos['total']; ?>, <?php endforeach; ?>];
                Chart.defaults.font.family = "Montserrat";
                Chart.defaults.font.size = 12;
                const ctx = document.getElementById('myChart');
                const myChart = new Chart(ctx, {
                  type: 'bar',
                  data: {
                    labels: [<?php foreach ($solicitudes as $datos) : ?> '<?php echo $datos['monto']; ?>', <?php endforeach; ?>],
                    datasets: [{
                      label: 'Personal por Sueldo',
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
                    <th class="text-center" style="width: 70%;">Monto <?php echo $monto?></th>
                    <th class="text-center" style="width: 30%;">Cantidad</th>
                  </tr>
                </thead>
                <tbody style="background: white;">
                  <?php $total = 0;
                  foreach ($solicitudes as $datos) : ?>
                    <tr>
                      <td>
                        <?php echo remove_junk(ucwords($datos['monto'])) ?>
                      </td>
                      <td>
                        <?php echo remove_junk(ucwords($datos['total'])) ?>
                        <?php $total = $total + $datos['total']; ?>
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