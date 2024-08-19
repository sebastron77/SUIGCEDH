
<?php
	$mysqli = new mysqli('localhost', 'suigcedh', '9DvkVuZ915H!', 'suigcedh');
	
	$no_expediente = $_POST['no_expediente']==''?Null:$_POST['no_expediente'];
	
	$queryM = "SELECT pa.id_paciente,CONCAT(pa.nombre,' ', pa.paterno,' ', pa.materno) as nombre_paciente
FROM paciente pa 
LEFT JOIN folios f ON f.id_folio = pa.folio_expediente ";
if($no_expediente > 0){
	$queryM .= "WHERE folio_expediente=".$no_expediente;
}else{
	$queryM .= "WHERE folio_expediente is NUll";
}

	$resultadoM = $mysqli->query($queryM);
	
	$html= "<option value='0'>Seleccionar Paciente</option>";
	
	while($rowM = $resultadoM->fetch_assoc())
	{
		$html.= "<option value='".$rowM['id_paciente']."'>".$rowM['nombre_paciente']."</option>";
	}
	
	echo $html;
?>


