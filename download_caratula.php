<html>
<head>
<title>Descargar Catatula de Queja</title>
</head>
<body>
<?php 


require_once('includes/load.php');
$user = current_user();

if (isset($_GET['id'])) {
	
	$id = $_GET['id'];	
	$e_detalle = find_by_id_queja($id);
	$derecho_vulnerado = find_derechoV($e_detalle['id_queja_date']);	
	
		
	

	$folio = str_replace("/", "-",$e_detalle['folio_queja']);

	
	header('Content-Encoding: UTF-8');
	header("Content-type: application/vnd.ms-word");
	header("Content-Disposition: attachment; Filename=catarula_".$folio.".doc");
	header("Pragma: no-cache");
	header("Expires: 0");		
?>
 

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
 
   <head>
      <meta http-equiv="Content-Type" content="text/html"  charset="Windows-1252" />
   </head>
 
   <body>
 
      <p style="  font-size: 27px;text-align: center;">   EXPEDIENTE: <?php echo $e_detalle['folio_queja'];?></p>
	  <br><br><br>
	  <div align="center">
		<img src="http://177.229.209.29/suigcedh/medios/aguila.png" style="margin: 0 auto; width:20px;">	
	  </div>
      <p style="  font-size: 20px;text-align: center;"><?php echo utf8_decode("COMISIÓN ESTATAL DE LOS DERECHOS HUMANOS DE MICHOACÁN DE OCAMPO")?></p>
	  <br>
	  <p style="  font-size: 24px;text-align: center;">   Expediente de Queja</p>
	  <br>
	   <table style="width: 100%; margin: 0 auto; text-align:center; font-size: 20px;">
		<tr>
			<td style="text-align:right; width: 40%;"><?php echo utf8_decode("Visitaduría:")?></td>
			<td style="border-bottom: 1px solid #ccc;text-align:left;"><?php echo utf8_decode($e_detalle['nombre_area'])?></td>
		</tr>
		<tr>
			<td style="text-align:right; ">Fecha de inicio:</td>
			<td style="border-bottom: 1px solid #ccc;text-align:left;">
			<?php 
			$date = new DateTime(remove_junk(ucwords($e_detalle['fecha_presentacion'])));
echo $date->format('d-m-Y'); ?> </td>
		</tr>
		<tr>
			<td style="text-align:right; ">Quejoso:</td>
			<td style="border-bottom: 1px solid #ccc;text-align:left;"><?php echo utf8_decode(remove_junk(ucwords(($e_detalle['nombre_quejoso'] . " " . $e_detalle['paterno_quejoso'] . " " . $e_detalle['materno_quejoso'])))) ?></td>
		</tr>
		<tr>
			<td style="text-align:right;">Agraviado:</td>
			<td style="border-bottom: 1px solid #ccc;text-align:left;"><?php echo utf8_decode(remove_junk(ucwords(($e_detalle['nombre_agraviado'] . " " . $e_detalle['paterno_agraviado'] . " " . $e_detalle['materno_agraviado'])))) ?></td>
		</tr>
		<tr>
			<td style="text-align:right; "> Autoridad Responsable:</td>
			<td style="border-bottom: 1px solid #ccc;text-align:left;"><?php echo utf8_decode(remove_junk(ucwords(($e_detalle['nombre_autoridad'])))) ?> </td>
		</tr>
		<tr>
			<td style="text-align:right; ">Derecho presuntamente vulnerado:</td>
			<td style="border-bottom: 1px solid #ccc;text-align:left;">
			<!--?php echo $derecho_vulnerado['descripcion']===null?'':utf8_decode($derecho_vulnerado['descripcion'])?-->
			</td>
		</tr>
		<tr>
			<td style="text-align:right; "><?php echo utf8_decode("Resolución:")?></td>
			<td style="border-bottom: 1px solid #ccc;text-align:left;"><?php echo remove_junk(ucwords(utf8_decode($e_detalle['tipo_resolucion']))) ?> </td>
		</tr>
		<tr>
			<td style="text-align:right; "><?php echo utf8_decode("Fecha de Conclusión:")?></td>
			<td style="border-bottom: 1px solid #ccc;text-align:left;"> </td>
		</tr>
		<tr>
			<td style="text-align:right; "> Observaciones:</td>
			<td style="border-bottom: 1px solid #ccc;text-align:left;"> </td>
		</tr>
		
      </table>
<br><br><br><br><br><br><br>	  
	  <div align="center">
      <table style="width: 80%; margin: 0 auto; text-align:center;">
		<tr>
			<td style="border: 1px solid #ccc;">Visitador(a) Regional</td>
			<td style="border: 1px solid #ccc;"> Visitador(a) Auxiliar</td>
		</tr>
      </table>
	</div>
   </body>
 
</html>
	<?php
	insertAccion($user['id_user'], '"'.$user['username'].'" descargó la catarula del expedientede, Folio: '.$e_detalle['folio_queja'].'.', 6);
	
}
?>
</body>
</html>

