
<?php
	$mysqli = new mysqli('localhost', 'suigcedh', '9DvkVuZ915H!', 'suigcedh');
	
	$id_cat_aut = $_POST['id_cat_aut']==''?Null:$_POST['id_cat_aut'];
	
	$queryM = "SELECT  id_contactos_politicas,CONCAT(nombres,' ',apellidos) as nombre_contacto
FROM `contactos_politicas`";
if($id_cat_aut > 0){
	$queryM .= "WHERE id_cat_aut=".$id_cat_aut;
}else{
	$queryM .= "WHERE id_cat_aut is NUll";
}
	$queryM .= " ORDER BY apellidos; ";

	$resultadoM = $mysqli->query($queryM);
	
	$html= "<option value='0'>Seleccionar Contacto</option>";
	
	while($rowM = $resultadoM->fetch_assoc())
	{
		$html.= "<option value='".$rowM['id_contactos_politicas']."'>".$rowM['nombre_contacto']."</option>";
	}
	
	echo $html;
?>