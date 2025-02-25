<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Busqueja QUejas';
require_once('includes/load.php');


$user = current_user();
$id_user = $user['id_user'];
$busca_area = area_usuario($id_user);
$otro = $busca_area['nivel_grupo'];
$nivel = $user['user_level'];


if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 5) {
    page_require_level_exacto(5);
}
if ($nivel == 7) {
    page_require_level_exacto(7);
}
if ($nivel == 19) {
    page_require_level_exacto(19);
}

if ($nivel > 2 && $nivel < 5) :
    redirect('home.php');
endif;
if ($nivel > 5 && $nivel < 7) :
    redirect('home.php');
endif;
if ($nivel > 7 && $nivel < 19) :
    redirect('home.php');
endif;


header('Content-type: text/html; charset=utf-8');
if (isset($_POST['export_data'])) {

    if (empty($errors)) {
        $year = remove_junk($db->escape($_POST['years']));
		$id_cat_aut = remove_junk($db->escape($_POST['id_cat_aut']));
        $medio_presentacion = remove_junk($db->escape($_POST['medio_presentacion']));
        $nivel_estudios = remove_junk($db->escape($_POST['nivel_estudios']));
        $ocupacion = remove_junk($db->escape($_POST['ocupacion']));
        $edad = remove_junk($db->escape($_POST['edad']));
        $lengua = remove_junk($db->escape($_POST['lengua']));
        $grupo_vulnerable = remove_junk($db->escape($_POST['grupo_vulnerable']));
        $sexo = remove_junk($db->escape($_POST['sexo']));
        $entidad = remove_junk($db->escape($_POST['entidad']));
        $id_cat_mun = remove_junk($db->escape($_POST['id_cat_mun']));		
        $municipio_localidad = remove_junk($db->escape($_POST['municipio_localidad']));
        $nacionalidad = remove_junk($db->escape($_POST['nacionalidad']));
        $nombre_completo = remove_junk($db->escape($_POST['nombre_completo']));
        $id_area_creacion = remove_junk($db->escape($_POST['id_area_creacion']));
		

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");		

      $sql =" SELECT 
       o.folio,
       o.correo_electronico,
	   o.nombre_completo,
       ac.nombre_autoridad,
       cmp.descripcion as medio_presentacion,       
       ce.descripcion as nivel_estudios,
       co.descripcion as ocupacion,
       o.edad,
       o.lengua as dialecto,
       cg.descripcion as grupo_vulnerable,  
       cs.descripcion as genero,
       IFNULL(cef.descripcion,'') as entidad_federativa,
       IF(o.municipio_localidad > 1, cm2.`descripcion`,IFNULL(mp.`descripcion`,'')) as municipio,
       IF(o.municipio_localidad > 1, '',IFNULL(o.municipio_localidad,'')) as localidad,
       cn.descripcion as nacionalidad,
	   CONCAT(d.nombre,' ',d.apellidos) as nombre_creador,
	   a.nombre_area as area_creador,
	   o.creacion as fecha_creacion
FROM orientacion_canalizacion as o
LEFT JOIN cat_autoridades ac ON o.institucion_canaliza= ac.id_cat_aut
LEFT JOIN cat_medio_pres as cmp ON cmp.id_cat_med_pres =  o.medio_presentacion
LEFT JOIN cat_escolaridad ce ON o.nivel_estudios= ce.id_cat_escolaridad
LEFT JOIN cat_ocupaciones co ON o.ocupacion= co.id_cat_ocup
LEFT JOIN cat_grupos_vuln cg ON o.grupo_vulnerable= cg.id_cat_grupo_vuln
LEFT JOIN cat_genero cs ON o.sexo= cs.id_cat_gen
LEFT JOIN cat_entidad_fed cef ON o.entidad= cef.id_cat_ent_fed
LEFT JOIN cat_municipios as mp ON mp.id_cat_mun = o.id_cat_mun
LEFT JOIN cat_nacionalidades cn ON o.nacionalidad= cn.id_cat_nacionalidad
LEFT JOIN cat_municipios cm2 ON o.municipio_localidad= cm2.id_cat_mun
LEFT JOIN users as u ON u.id_user = o.id_creador
LEFT JOIN detalles_usuario as d ON d.id_det_usuario = u.id_detalle_user
LEFT JOIN cargos c ON d.id_cargo = c.id_cargos
LEFT JOIN area a ON c.id_area = a.id_area
WHERE tipo_solicitud = 1 ";
 /******************************* Datos Queja ************************************************/
 //ejercicio
 if((int)$year >0){
	$sql .= " AND o.folio LIKE '%/".$year."-%' ";
 } 
 //Autoridad responsable
 if((int)$id_cat_aut >0){
	$sql .= " AND o.institucion_canaliza = ".$id_cat_aut;
 } 
 //medio de presentacion
 if((int)$medio_presentacion >0){
	$sql .= " AND o.medio_presentacion = ".$medio_presentacion;
 } 
 //Nivel de estudiso
 if((int)$nivel_estudios >0){
	$sql .= " AND o.nivel_estudios = ".$nivel_estudios;
 } 
 //Ocupacion 
 if((int)$ocupacion >0){
	$sql .= " AND o.ocupacion = ".$ocupacion;
 }
 //dialecto 
 if($lengua != ''){
	$sql .= " AND o.lengua = '".$lengua."' ";
 }
 //Grupo Vulnerable 
 if((int)$grupo_vulnerable >0){
	$sql .= " AND o.grupo_vulnerable = ".$grupo_vulnerable;
 }
//Genero 
 if((int)$sexo >0){
	$sql .= " AND o.sexo = ".$sexo;
 }
 //Entidad Federativa
 if(!empty($entidad )){
	$sql .= " AND o.entidad = '".($entidad)."' ";;
 }  
 //Municipio 
 if((int)$id_cat_mun >0){
	$sql .= " AND o.id_cat_mun = ".$id_cat_mun;
 }
 //dialecto 
 if($municipio_localidad != ''){
	$sql .= " AND o.municipio_localidad = '".$municipio_localidad."' ";
 }
 //Nacionalidad 
 if((int)$nacionalidad >0){
	$sql .= " AND o.nacionalidad = ".$nacionalidad;
 }  
 //nombre completo 
 if($nombre_completo != ''){
	$sql .= " AND o.nombre_completo like '%".$nombre_completo."%' ";
 } 
 //area creacion 
 if((int)$id_area_creacion > 0){
	$sql .= " AND a.id_area = '".$id_area_creacion."' ";
 } 
 //Edades
 if((int)$edad >0){
	if((int)$edad == 1 ){
		$sql .= " AND (o.edad >0 AND o.edad <= 10 ) ";
	}else if((int)$edad == 2 ){
		$sql .= " AND (o.edad >10 AND o.edad <= 20 ) ";
	}else if((int)$edad == 3 ){
		$sql .= " AND (o.edad >20 AND o.edad <= 30 ) ";
	}else if((int)$edad == 4 ){
		$sql .= " AND (o.edad >30 AND o.edad <= 40 ) ";
	}else if((int)$edad == 5 ){
		$sql .= " AND (o.edad >40 AND o.edad <= 50 ) ";
	}else if((int)$edad == 6 ){
		$sql .= " AND (o.edad >60 AND o.edad <= 60 ) ";
	}else if((int)$edad == 7 ){
		$sql .= " AND (o.edad > 60  ) ";
	}
	
		
 }
 $sql .= " ORDER BY o.creacion ";
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
        header("Content-Disposition: attachment; filename=busqueja_orientaciones.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
        $filename = "busqueja_orientaciones.xls";
        $mostrar_columnas = false;

        foreach ($quejas as $resolucion) {
            if (!$mostrar_columnas) {
                echo implode("\t", array_keys($resolucion)) . "\n";
                $mostrar_columnas = true;
            }
            echo implode("\t", array_map("utf8_decode", array_values(($resolucion)))) . "\n";
        }
		
		insertAccion($user['id_user'], '"' . $user['username'] . '" generó reporte de Orientaciones.', 3);
		
    } else {
        ?>
				<p style="font-size: 15px;">
					Lo sentimos,su búsqueda no generó ningun resultado le pedimos por favor vuelva a intentarlo.
				</p>
				
				<a href="busquedaorientaciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="ACEPTAR">ACEPTAR </a>
				 
			<?php
    }
    exit;
}
       
    }    
 } 
?>	