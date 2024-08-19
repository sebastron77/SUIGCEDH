<?php
	require_once('includes/load.php');
	
	$id = $_POST['id'];

	$queryM = find_all_subarea_area((int)$id);
	
	$html = "<option value='0'>Seleccionar Subárea</option>";

	foreach($queryM as $rowM)
	{
		$html .= "<option value='".$rowM['id_area']."'>".$rowM['nombre_area']."</option>";
	}

	echo $html;
?>