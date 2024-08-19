<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Busqueda Eventos';
require_once('includes/load.php');


$user = current_user();
$id_user = $user['id_user'];
$busca_area = area_usuario($id_user);
$otro = $busca_area['nivel_grupo'];
$nivel = $user['user_level'];



header('Content-type: text/html; charset=utf-8');
if (isset($_POST['export_data'])) {

    if (empty($errors)) {
        $year = remove_junk($db->escape($_POST['years']));
        $mes = remove_junk($db->escape($_POST['mes']));
        $id_area = remove_junk($db->escape($_POST['id_area']));
        $tipo_evento = remove_junk($db->escape($_POST['tipo_evento']));
        $modalidad = remove_junk($db->escape($_POST['modalidad']));
		

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");		
 
 //se detecta si se busca grupo vulnerable
 
	$sql = " SELECT 
	folio
	nombre_evento ,
  tipo_evento ,
  REPLACE(REPLACE(REPLACE(quien_solicita,'\r\n',' '),  CHAR(13, 10), ' '),'\t',' ') as quien_solicita,
  fecha ,
  hora ,
  lugar ,
  no_asistentes, 
  modalidad ,
  depto_org ,
  REPLACE(REPLACE(REPLACE(quien_asiste,'\r\n',' '),  CHAR(13, 10), ' '),'\t',' ') as quien_asiste ,
  REPLACE(REPLACE(REPLACE(invitacion,'\r\n',' '),  CHAR(13, 10), ' '),'\t',' ') as invitacion , 
  a.`nombre_area` as area_creadora , 
  CONCAT(du.`nombre`,' ',apellidos) as creador, 
  fecha_creacion
  FROM eventos e
  LEFT JOIN `area` a ON(a.`id_area`=e.area_creacion )
  LEFT JOIN `users` u ON(e.`user_creador`=u.`id_user`)
  LEFT JOIN `detalles_usuario` du ON(u.`id_detalle_user`=du.`id_det_usuario`)
  WHERE id_evento > 0 ";
 
 //ejercicio
 if((int)$year >0){
	$sql .= " AND YEAR(fecha)= '".$year."' ";
 } 
 //Mes
 if((int)$mes >0){
	$sql .= " AND MONTH(fecha) =  ".$mes;
 }  
 //ärea
 if((int)$id_area >0){
	$sql .= " AND e.area_creacion =  ".$id_area;
 } 
   //tipo_evento 
 if($tipo_evento != ''){
	$sql .= " AND tipo_evento = '".$tipo_evento."' ";
 }
    //modalidad 
 if($modalidad != ''){
	$sql .= " AND modalidad = '".$modalidad."' ";
 }
 $sql .= " ORDER BY fecha ";
 //echo $sql;
$resultado = mysqli_query($conexion, $sql) or die;
$quejas = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $quejas[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($quejas)) {
        header('Content-Encoding: UTF-8');
		header("Content-type: application/vnd.ms-excel; name='excel'");
		//header( "Content-type: application/vnd.ms-excel; charset=UTF-8" );
        header("Content-Disposition: attachment; filename=busqueja_eventos.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
        $filename = "busqueja_eventos.xls";
        $mostrar_columnas = false;

        foreach ($quejas as $resolucion) {
            if (!$mostrar_columnas) {
                echo implode("\t", array_keys($resolucion)) . "\n";
                $mostrar_columnas = true;
            }
            echo implode("\t", array_map("utf8_decode", array_values(($resolucion)))) . "\n";
        }
		
		insertAccion($user['id_user'], '"' . $user['username'] . '" generó reporte de Capacitaciones.', 3);
		
    } else {
        ?>
				<p style="font-size: 15px;">
					Lo sentimos,su búsqueda no generó ningun resultado le pedimos por favor vuelva a intentarlo.
				</p>
				
				<a href="busquedaeventos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="ACEPTAR">ACEPTAR </a>
				 
			<?php
    }
    exit;
}
}}      
        
 
?>	