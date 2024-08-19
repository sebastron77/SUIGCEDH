<?php
include("includes/config.php");
$page_title = 'Constancia';
$results = '';
require_once('includes/load.php');
require_once('dompdf/autoload.inc.php');

use Dompdf\Dompdf;
use Dompdf\Options;

ob_start(); //Linea para que deje descargar el PDF
// $id_resguardo = (int)$_GET['id'];

$start_date = $_GET['start'];
$end_date = $_GET['end'];

$results      = find_presencial_by_dates($start_date, $end_date);
$results2      = find_en_linea_by_dates($start_date, $end_date);
$results3      = find_hibrido_by_dates($start_date, $end_date);
// $resguardos = resguardo_pdf($id_resguardo);
// $folio = busca_folio((int)$_GET['id']);
//$currentsite = getcwd();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta charset="UTF-8">
  <title>Reporte</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
  <?php if ($results) : ?>
    <div class="page-break">
      <center>
        <h2 style="margin-top: 2%;">Estadísticas por modalidad del <?php if (isset($start_date)) {
                                                                      echo $start_date;
                                                                    } ?> a <?php if (isset($end_date)) {
                                                                                      echo $end_date;
                                                                                    } ?></h2>



        <div class="row" style="display: flex; justify-content: center; align-items: center;">
          <div class="col-md-6" style="width: 35%; height: 20%;">
            <canvas id="myChart"></canvas>
            <!-- Incluímos Chart.js -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <!-- Añadimos el script a la página -->

            <script>
              var yValues = [<?php echo $results['totales']; ?>, <?php echo $results2['totales']; ?>, <?php echo $results3['totales']; ?>];

              const ctx = document.getElementById('myChart');
              const myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                  labels: ['Presencial', 'En línea', 'Híbrido'],
                  datasets: [{
                    label: 'Capacitaciones por modalidad',
                    data: yValues,
                    backgroundColor: [
                      '#60A685',
                      '#8FBADB',
                      '#EBE88A'
                    ],
                    borderColor: [
                      '#467860',
                      '#65849C',
                      '#BFBD71'
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


          <div class="col-md-6" style="margin-top: 7%;">
            <table class="table table-bordered table-striped">
              <thead>
                <tr style="height: 10px;" class="info">
                  <th class="text-center" style="width: 70%;">Modalidad</th>
                  <th class="text-center" style="width: 30%;">Cantidad</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Presencial</td>
                  <?php if ($results['totales'] != 0) { ?>
                    <td class="text-center"><?php echo $results['totales']; ?></td>
                  <?php } else { ?>
                    <td class="text-center">0</td>
                  <?php } ?>
                </tr>
                <tr>
                  <td>En línea</td>
                  <?php if ($results2['totales'] != 0) { ?>
                    <td class="text-center"><?php echo $results2['totales']; ?></td>
                  <?php } else { ?>
                    <td class="text-center">0</td>
                  <?php } ?>
                </tr>
                <tr>
                  <td>Híbrido</td>
                  <?php if ($results3['totales'] != 0) { ?>
                    <td class="text-center"><?php echo $results3['totales']; ?></td>
                  <?php } else { ?>
                    <td class="text-center">0</td>
                  <?php } ?>
                </tr>
                <tr>
                  <td style="text-align:right;"><b>Total</b></td>
                  <td>
                    <?php echo $results['totales'] + $results2['totales'] + $results3['totales'];
                    ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </center>
    </div>

  <?php
  else :
    $session->msg("d", "No se encontraron datos. ");
    redirect('tabla_estadistica_capcapacitacion_tipo_evento.php', false);
  endif;
  ?>
</body>

</html>
<?php if (isset($db)) {
  $db->db_disconnect();
} ?>

<?php
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$dompdf = new DOMPDF($options);
$dompdf->loadHtml(ob_get_clean());
$dompdf->setPaper("letter");
$dompdf->render();
//$pdf->image();
$pdf = $dompdf->output();
$filename = "grafica.pdf";
file_put_contents($filename, $pdf);
$dompdf->stream($filename);

?>