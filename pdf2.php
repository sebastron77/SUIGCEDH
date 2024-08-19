<?php
include("includes/config.php");
$page_title = 'Correspondencia Interna';
require_once('includes/load.php');
require_once('dompdf/autoload.inc.php');

use Dompdf\Dompdf;
use Dompdf\Options;

ob_start(); //Linea para que deje descargar el PDF

$user = current_user();
$id_user = $user['id'];
$area_user = area_usuario2($id_user);
$area = $area_user['nombre_area'];
$cargo_user = cargo_usuario($id_user);
$cargo_quien_envia = $cargo_user['nombre_cargo'];
$nombre_user = nombre_usuario($id_user);
$nombre_completo = $nombre_user['nombre'] . " " . $nombre_user['apellidos'];


$id_correspondencia = $_GET['id'];

$correspondencias = correspondencia_pdf($id_correspondencia);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
</head>

<style>
  body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 15px;
  }
  .footer {
      position: absolute;
      bottom: 0;
      color: black;
      font-size: 10px;
  }
</style>

<body>
  <!-- <div class="container">     -->
  <div>
    <div style="background: #D8D8D8; width: 100%; height: 30px;">
      <img src="http://localhost/sistemageneral/administrador/Logo-CEDH-pdf.png" style="margin-top: -30px; margin-left: 20px;" width="120" height="160"><br><br>
    </div>
  </div><br>
  <div>
    <div>
      <p style="text-align: right; margin-right: 15px;"><b>Comisión Estatal de los Derechos Humanos</b></p>
      <?php foreach ($correspondencias as $correspondencia) : ?>

        <table border="0.5" style="border-collapse: collapse; width: 40%;" align="right">
          <tr>
            <td style="width: 30%;"><b>Área:</b></td>
            <td style="width: 70%;"><b><?php echo $correspondencia['area_creacion']; ?></b></td>
          </tr>
          <tr>
            <td style="width: 30%;"><b>Número de oficio:</b></td>
            <td style="width: 70%;"><b><?php echo $correspondencia['folio']; ?></b></td>
          </tr>
          <tr>
            <td style="width: 30%;"><b>Expediente:</b></td>
            <td style="width: 70%;"><b><?php echo $correspondencia['expediente']; ?></b></td>
          </tr>
          <tr>
            <td style="width: 30%;"><b>Asunto:</b></td>
            <td style="width: 70%;"><b><?php echo $correspondencia['asunto']; ?></b></td>
          </tr>
        </table>
    </div>
    <div style=" height: 100px; margin-top: 20px;">
      <?php
        $fecha = $correspondencia['fecha_emision'];
        $dia = substr($fecha, 8, 10);
        $mes = substr($fecha, 5, -3);
        $anio = substr($fecha, 0, 4);

        if ($mes == '01') { $nom_mes = 'enero'; };
        if ($mes == '02') { $nom_mes = 'febrero'; };
        if ($mes == '03') { $nom_mes = 'marzo'; };
        if ($mes == '04') { $nom_mes = 'abril'; };
        if ($mes == '05') { $nom_mes = 'mayo'; };
        if ($mes == '06') { $nom_mes = 'junio'; };
        if ($mes == '07') { $nom_mes = 'julio'; };
        if ($mes == '08') { $nom_mes = 'agosto'; };
        if ($mes == '09') { $nom_mes = 'septiembre'; };
        if ($mes == '10') { $nom_mes = 'octubre'; };
        if ($mes == '11') { $nom_mes = 'noviembre'; };
        if ($mes == '12') { $nom_mes = 'diciembre'; };
      ?>
    </div>
    <?php if(strlen($correspondencia['asunto']) > 60):?>
    <div style="text-align: right; border-top: 5%">
    <?php endif;?>
    <?php if(strlen($correspondencia['asunto']) < 60):?>
    <div style="text-align: right;">
    <?php endif;?>
      <p>Morelia, Michoacán a <?php echo $dia; ?> de <?php echo $nom_mes; ?> de <?php echo $anio; ?></p>
    </div>
    <div>
      <?php $cargo = cargo_trabajador_pdf($correspondencia['se_turna_a_trabajador']) ?>
      <?php $nombre = nombre_trabajador_pdf($correspondencia['se_turna_a_trabajador']) ?>
      <div class="row">
        <p style="text-align: left;"><b><?php echo strtoupper($nombre['nombre'] . " " . $nombre['apellidos']) ?></b></p>
      </div>
      <div class="row" style="margin-top: -15px;">        
        <p style="text-align: left;"><b><?php echo nl2br(strtoupper($cargo['nombre_cargo'])); ?></b></p>
        <p style="text-align: left;"><b><?php echo "PRESENTE."; ?></b></p>
      </div>
    </div>
    <div>
      <p style="text-align: left; text-align: justify; line-height: 170%; white-space: pre-line;"><?php echo $correspondencia['cuerpo_oficio']?></p>
    </div>
    <div style="margin-top:20%;">
      <p style="text-align: center;"><b>ATENTAMENTE</b></p>
      <p style="text-align: center;"><b><?php echo $nombre_completo ?></b></p>
      <p style="text-align: center;"><b><?php echo $cargo_quien_envia ?></b></p>
    </div>
  </div>
<?php endforeach; ?>
<footer  style="width:100%; margin-left: 0px;"  >


<footer class="footer">
  <div class="row">
    <div class="col-md-6">
      <img src="http://localhost/sistemageneral/administrador/footer.png" alt="" srcset="" style="margin-top: 24%;">
      <p style="text-align: left;"><?php echo "C.c.p. " . $correspondencia['con_copia_para'] ?></p>
    </div>
    <div class="col-md-6">
      <p style="text-align: right; margin-top: -10px; font-family: 'Questrial', sans-serif;">Fernando Montes de Oca #108. Chapultepec Nte.<br>CP. 58260. Morelia, Mich.<br>Tel. 443 1 13 35 00 Lada sin costo 800 640 31 88</p>
    </div>
  </div>
</footer>
</body>

<?php
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$dompdf = new DOMPDF($options);
$dompdf->loadHtml(ob_get_clean());
$dompdf->setPaper("letter");
$dompdf->render();
//$pdf->image();
$pdf = $dompdf->output();
$filename = "oficio.pdf";
file_put_contents($filename, $pdf);
$dompdf->stream($filename);

?>