<?php
	require_once('includes/load.php');
	
	$nombre_area2 = $_POST['nombre_area2'];

	$queryM = find_all_trabajadores_area($nombre_area2);
	
	$html = "<option value='0'>Seleccionar Trabajador</option>";

	foreach($queryM as $rowM)
	{
		$html .= "<option value='".$rowM['id_det_usuario']."'>".$rowM['nombre']. " " .$rowM['apellidos']."</option>";
	}

	echo $html;
?>