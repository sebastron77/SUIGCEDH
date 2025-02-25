<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Busqueda Capacitaciones';
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
        $tipo_capacitacion = remove_junk($db->escape($_POST['tipo_capacitacion']));
        $tipo_evento = remove_junk($db->escape($_POST['tipo_evento']));
        $modalidad = remove_junk($db->escape($_POST['modalidad']));
        $asistentes = remove_junk($db->escape($_POST['asistentes']));
        $edad = remove_junk($db->escape($_POST['edad']));
        $id_cat_grupo_vuln = remove_junk($db->escape($_POST['id_cat_grupo_vuln']));
		

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");		
 
 //se detecta si se busca grupo vulnerable
 if((int)$id_cat_grupo_vuln >0){
	$sql = " SELECT
		  tipo_capacitacion ,
		  tipo_evento ,
		  quien_solicita ,
		  fecha ,
		  hora,
		  lugar,
		  asistentes_otros ,
		  asistentes_nobinario ,
		  asistentes_mujeres ,
		  asistentes_hombres ,
		  asistentes_10  as De_0_a_11_anios ,
		  asistentes_20  as De_12_a_17_anios,
		  asistentes_30  as De_18_a_30_anios,
		  asistentes_40  as De_31_a_40_anios,
		  asistentes_50  as De_41_a_50_anios,
		  asistentes_60  as De_51_a_60_anios,
		  asistentes_70  as De_60_o_mas,
		  asistentes_80  as Sin_Dato,
		  modalidad ,
			y.descripcion as nombre_grupo,
			c.no_asistentes,
			b2.nombre_area as nombre_area_creacion,
     CONCAT(d.`nombre`,' ',d.`apellidos`) as usuario_creador,
fecha_creacion
		FROM `rel_capacitacion_grupos` c
		LEFT JOIN capacitaciones a USING(id_capacitacion)
		LEFT JOIN area b USING(id_area)
		LEFT JOIN `users` c ON(a.`user_creador`= c.`id_user`)
		LEFT JOIN `detalles_usuario` d ON(c.`id_detalle_user`= d.`id_det_usuario`) 
LEFT JOIN `area` b2 ON(a.`area_creacion`=b2.`id_area`) 
		LEFT JOIN cat_grupos_vuln y USING(id_cat_grupo_vuln) ";
 }else{
	 $sql = "SELECT
		  tipo_capacitacion ,
		  tipo_evento ,
		  quien_solicita ,
		  fecha ,
		  asistentes_otros ,
		  asistentes_nobinario ,
		  asistentes_mujeres ,
		  asistentes_hombres ,
		   asistentes_10  as De_0_a_11_anios ,
		  asistentes_20  as De_12_a_17_anios,
		  asistentes_30  as De_18_a_30_anios,
		  asistentes_40  as De_31_a_40_anios,
		  asistentes_50  as De_41_a_50_anios,
		  asistentes_60  as De_51_a_60_anios,
		  asistentes_70  as De_60_o_mas,
		  asistentes_80  as Sin_Dato,
		  modalidad ,
			IFNULL(nombre_grupo,'') as nombre_grupo,
			IFNULL(c.no_asistentes,'') as no_asistentes,
			b2.nombre_area as nombre_area_creacion,
     CONCAT(d.`nombre`,' ',d.`apellidos`) as usuario_creador,
fecha_creacion
		FROM capacitaciones a 
		LEFT JOIN area b USING(id_area)
		LEFT JOIN `users` c ON(a.`user_creador`= c.`id_user`)
		LEFT JOIN `detalles_usuario` d ON(c.`id_detalle_user`= d.`id_det_usuario`) 
LEFT JOIN `area` b2 ON(a.`area_creacion`=b2.`id_area`) 
		LEFT JOIN (
			SELECT
				id_capacitacion,
				GROUP_CONCAT(descripcion ) as nombre_grupo,
				GROUP_CONCAT(no_asistentes) as no_asistentes
			FROM rel_capacitacion_grupos z
			LEFT JOIN cat_grupos_vuln y USING(id_cat_grupo_vuln)
		GROUP BY id_capacitacion    
		) c USING(id_capacitacion) ";
 }
 
 $sql .= " WHERE id_capacitacion > 0 ";
 
 //ejercicio
 if((int)$year >0){
	$sql .= " AND YEAR(fecha)= '".$year."' ";
 } 
 //Mes
 if((int)$mes >0){
	$sql .= " AND MONTH(fecha) =  ".$mes;
 } 
 //area que impartio
 if((int)$id_area >0){
	$sql .= " AND area_creacion = ".$id_area;
 } 
 //tipo_capacitacion 
 if($tipo_capacitacion != ''){
	$sql .= " AND tipo_capacitacion = '".$tipo_capacitacion."' ";
 }
  //tipo_evento 
 if($tipo_evento != ''){
	$sql .= " AND tipo_evento = '".$tipo_evento."' ";
 }
    //modalidad 
 if($modalidad != ''){
	$sql .= " AND modalidad = '".$modalidad."' ";
 }  
 //asistentes 
 if($asistentes != ''){
	$sql .= " AND ".$asistentes." > 0 ";
 }
 //edad 
 if($edad != ''){
	$sql .= " AND ".$edad." > 0 ";
 }
 //id_cat_grupo_vuln
 if((int)$id_cat_grupo_vuln >0){
	$sql .= " AND id_cat_grupo_vuln = ".$id_cat_grupo_vuln." ";
 } 
 $sql .= " ORDER BY fecha ";
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
        header("Content-Disposition: attachment; filename=busqueja_capacitaciones.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
        $filename = "busqueja_capacitaciones.xls";
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
				
				<a href="busquedacanalizaciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="ACEPTAR">ACEPTAR </a>
				 
			<?php
    }
    exit;
}
       
    }    
 } 
?>	