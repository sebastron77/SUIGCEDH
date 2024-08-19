<?php
require_once('includes/load.php');

$tipo_actuacion = $_POST['tipo_actuacion'];
$id_area = $_POST['id_area_asignada'];

if($tipo_actuacion=='q'){	
	$actuaciones = find_quejas_area($id_area);
}else if($tipo_actuacion=='c'){
	$actuaciones = find_orican_area($id_area,2);
}else if($tipo_actuacion=='o'){
	$actuaciones = find_orican_area($id_area,1);
}else if($tipo_actuacion=='a'){
}

$html = "<option value='0'>Seleccionar Actuación</option>";

foreach ($actuaciones as $rowM) {
    $html .= "<option value='" . $rowM['id'] . "'>" . $rowM['folio'] .  "</option>";
}
echo $html;
