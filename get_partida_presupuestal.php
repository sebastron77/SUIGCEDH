
<?php
	$mysqli = new mysqli('localhost', 'suigcedh', '9DvkVuZ915H!', 'suigcedh');
	
	$id_capitulo = $_POST['id_capitulo']==''?0:$_POST['id_capitulo'];
	
	$queryM = "SELECT id_cat_partida_presupuestal,clave,descripcion
FROM cat_partida_presupuestal  
WHERE id_cat_capitulo_presupuestal=".$id_capitulo." AND estatus=1; ";

	$resultadoM = $mysqli->query($queryM);
	
	$html= "<option value=''>Escoge una opción</option>";
	
	while($rowM = $resultadoM->fetch_assoc())
	{
		$html.= "<option value='".$rowM['id_cat_partida_presupuestal']."'>".$rowM['clave']."  ".$rowM['descripcion']."</option>";
	}
	
	echo $html;
?>


