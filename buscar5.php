<?php
	require_once('includes/load.php');
	
	
	$id_cat_municipios = $_POST['id_cat_municipios'];

	$queryM = find_all_localidades($id_cat_municipios);
	
	$html = "<option value='0'>Seleccionar localidad</option>";

	foreach($queryM as $rowM)
	{
		$html .= "<option value='".$rowM['id_cat_localidades']."'>".$rowM['nnombre_localidad']."</option>";
	}

	echo $html;
?>