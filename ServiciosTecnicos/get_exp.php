
<?php
	$mysqli = new mysqli('localhost', 'suigcedh', '9DvkVuZ915H!', 'suigcedh');
	
	$tipo_expediente = $_POST['tipo_expediente'];
	
	$queryM = "SELECT id_folio, folio FROM folios WHERE folio LIKE '%{$tipo_expediente}' ORDER BY id_folio";
	$resultadoM = $mysqli->query($queryM);
	
	$html= "<option value='0'>Seleccionar Folio</option>";
	
	while($rowM = $resultadoM->fetch_assoc())
	{
		$html.= "<option value='".$rowM['id_folio']."'>".$rowM['folio']."</option>";
	}
	
	echo $html;
?>