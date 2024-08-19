
<?php
	$mysqli = new mysqli('localhost', 'suigcedh', '9DvkVuZ915H!', 'suigcedh');
	
	$id_area = $_POST['id_area']==''?Null:$_POST['id_area'];
	
	$queryM = "SELECT id_det_usuario, CONCAT(nombre,' ',apellidos) as nombre_usuario  
FROM `cargos` a
LEFT JOIN `detalles_usuario` b ON(a.`id_cargos`=b.`id_cargo`) ";

if($id_area > 0){
 $queryM .= " WHERE a.`id_area`=".$id_area;
}
 $queryM .= " AND estatus_detalle=true   ORDER BY nombre ";
echo $queryM;	
	$resultadoM = $mysqli->query($queryM);
	
	$html= "<option value='0'>Escoge una opción</option>";
	
	while($rowM = $resultadoM->fetch_assoc())
	{
		$html.= "<option value='".$rowM['id_det_usuario']."'>".$rowM['nombre_usuario']."</option>";
	}
	
	echo $html;
?>