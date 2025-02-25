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

$cat_medios_pres = find_all_medio_pres();
$cat_autoridades = find_all_aut_res();
$area = find_all_areas_quejas();
$cat_est_procesal = find_all('cat_est_procesal');

$generos = find_all('cat_genero');
$nacionalidades = find_all('cat_nacionalidades');
$municipios = find_all('cat_municipios');
$escolaridades = find_all('cat_escolaridad');
$ocupaciones = find_all('cat_ocupaciones');
$grupos_vuln = find_all('cat_grupos_vuln');
$discapacidades = find_all('cat_discapacidades');
$comunidades = find_all('cat_comunidades');

$nombre_quejoso='';
$paterno_quejoso='';
$materno_quejoso='';

header('Content-type: text/html; charset=utf-8');
if (isset($_POST['export_data'])) {

    if (empty($errors)) {
		$year = remove_junk($db->escape($_POST['years']));
        $id_cat_aut = remove_junk($db->escape($_POST['id_cat_aut']));
        $id_cat_med_pres = remove_junk($db->escape($_POST['id_cat_med_pres']));
        $id_user_asignado = remove_junk($db->escape($_POST['id_user_asignado']));
        $id_area_asignada = remove_junk($db->escape($_POST['id_area_asignada']));
        $estado_procesal = remove_junk($db->escape($_POST['estado_procesal']));
        $id_tipo_resolucion = remove_junk($db->escape($_POST['id_tipo_resolucion']));
        $id_tipo_ambito = remove_junk($db->escape($_POST['id_tipo_ambito']));
        $ent_fed = remove_junk($db->escape($_POST['ent_fed']));
        $id_cat_munQ = remove_junk($db->escape($_POST['id_cat_munQ']));
        $id_cat_derecho_general = remove_junk($db->escape($_POST['id_cat_derecho_general']));
        $id_cat_der_vuln = remove_junk($db->escape($_POST['id_cat_derecho_vuln']));
        $mediacion = remove_junk($db->escape($_POST['mediacion']));
        $medida_cautelar = remove_junk($db->escape($_POST['medida_cautelar']));
        $id_area_creacion = remove_junk($db->escape($_POST['id_area_creacion']));
		
		
		
        $id_cat_genQ = remove_junk($db->escape($_POST['id_cat_genQ']));
        $id_cat_escolaridadQ = remove_junk($db->escape($_POST['id_cat_escolaridadQ']));
        $id_cat_ocupQ = remove_junk($db->escape($_POST['id_cat_ocupQ']));
        $edadQ = remove_junk($db->escape($_POST['edadQ']));
        $leer_escribirQ = remove_junk($db->escape($_POST['leer_escribirQ']));
        $id_cat_discQ = remove_junk($db->escape($_POST['id_cat_discQ']));
        $id_cat_grupo_vulnQ = remove_junk($db->escape($_POST['id_cat_grupo_vulnQ']));
        $id_cat_comunQ = remove_junk($db->escape($_POST['id_cat_comunQ']));
        $id_cat_nacionalidadQ = remove_junk($db->escape($_POST['id_cat_nacionalidadQ']));
        $id_cat_munP = remove_junk($db->escape($_POST['id_cat_munP']));
        $quejoso = remove_junk($db->escape($_POST['nombre_quejoso']));
		
		if($quejoso != ''){
			$datos_quejos=  explode('/', $quejoso);
			$numero_datos = sizeof($datos_quejos);
			for($i=0; $i<$numero_datos;$i++){
				if($i==0) $nombre_quejoso = $datos_quejos[$i];
				if($i==1) $paterno_quejoso = $datos_quejos[$i];
				if($i==2) $materno_quejoso = $datos_quejos[$i];
			}	
		}
		

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");		

      $sql =" SELECT  
        q.folio_queja,
        mp.descripcion as medio_pres,
        au.nombre_autoridad,
        a.nombre_area as nombre_area_asignada,
        CONCAT(du.nombre,' ',du.apellidos) as usuario_asignado,
        tr.descripcion as tipo_resolucion,     
        ta.descripcion as tipo_ambito,               
        etp.descripcion as estado_procesal,
		IFNULL(cdg.descripcion,'') as derecho_general,
        IFNULL(cdv.descripcion,'') as derecho_vulnerado,
        cm.descripcion as munnicipio_queja, 
        q.ent_fed as entidad_federativa,
        
		CONCAT(cq.nombre,' ',cq.paterno,' ',cq.materno) as nombre_quejoso,
        cg.descripcion as genero_quejoso,          
        ce.descripcion as escolaridad_quejoso,   
        oc.descripcion as ocupacion_quejoso, 
        cq.edad as edad_quejoso, 
        cq.leer_escribir as leer_escribir_quejoso,
        cd.descripcion as nombre_discapacidad_quejoso,        
        cgv.descripcion as grupo_vulnerable_quejoso,
		cmds.descripcion as comunidades_indigenas_quejoso,
        cn.`descripcion` as nacionalidad_quejoso,
        cmpio.descripcion as municipio_quejos,
		
		
		
		CONCAT(ca.nombre,' ',ca.paterno,' ',ca.materno) as nombre_agraviado,
       cga.descripcion as genero_agraviado,  
       cea.descripcion as escolaridad_agraviado,   
        oca.descripcion as ocupacion_agraviado, 
        ca.edad as edad_agraviado, 
        ca.leer_escribir as leer_escribir_agraviado,
        cda.descripcion as nombre_discapacidad_agraviado,        
        cgva.descripcion as grupo_vulnerable_agraviado,
		cmdsa.descripcion as comunidades_indigenas_agraviado,
        cna.`descripcion` as nacionalidad_agraviado,
        cmpioa.descripcion as municipio_agraviado,
		
		
		IF(mediacion=1,'Si','No') as en_mediacion,
		IF(medida_cautelar=1,'Si','No') as con_medida_cautelar,
		q.fecha_presentacion,
        q.fecha_creacion,
		IF(q.id_user_creador is NULL,'Queja creada en Línea',CONCAT(d.nombre,' ',d.apellidos)) as nombre_creador,
	    a2.nombre_area as area_creador        
		
 FROM quejas_dates q
      LEFT JOIN cat_medio_pres mp ON mp.id_cat_med_pres = q.id_cat_med_pres 
      LEFT JOIN cat_autoridades au ON au.id_cat_aut = q.id_cat_aut
      /*LEFT JOIN users u ON u.id_user = q.id_user_asignado*/
      LEFT JOIN area a ON a.id_area = q.id_area_asignada
	  LEFT JOIN detalles_usuario as du ON du.id_det_usuario = q.id_user_asignado
      LEFT JOIN cat_quejosos cq ON cq.id_cat_quejoso = q.id_cat_quejoso
      LEFT JOIN cat_agraviados ca ON ca.id_cat_agrav = q.id_cat_agraviado
      LEFT JOIN cat_tipo_res tr ON tr.id_cat_tipo_res = q.id_tipo_resolucion
      LEFT JOIN cat_tipo_ambito ta ON ta.id_cat_tipo_ambito = q.id_tipo_ambito
      LEFT JOIN cat_municipios cm ON cm.id_cat_mun = q.id_cat_mun
      LEFT JOIN cat_ocupaciones oc ON oc.id_cat_ocup = cq.id_cat_ocup
      LEFT JOIN cat_escolaridad ce ON ce.id_cat_escolaridad = cq.id_cat_escolaridad
      LEFT JOIN cat_grupos_vuln cgv ON cgv.id_cat_grupo_vuln = cq.id_cat_grupo_vuln
      LEFT JOIN cat_genero cg ON cg.id_cat_gen = cq.id_cat_gen
      LEFT JOIN cat_nacionalidades cn ON cn.id_cat_nacionalidad = cq.id_cat_nacionalidad
      LEFT JOIN cat_est_procesal etp ON q.estado_procesal = etp.id_cat_est_procesal
      LEFT JOIN cat_entidad_fed cetf ON q.ent_fed = cetf.descripcion
      LEFT JOIN cat_discapacidades cd ON cq.id_cat_disc = cd.id_cat_disc
      LEFT JOIN cat_comunidades cmds ON cmds.id_cat_comun = cq.id_cat_comun
      LEFT JOIN cat_municipios cmpio ON cmpio.id_cat_mun = cq.id_cat_mun 
	  LEFT JOIN users as u2 ON u2.id_user = q.id_user_creador
	LEFT JOIN detalles_usuario as d ON d.id_det_usuario = u2.id_detalle_user
	LEFT JOIN cargos c ON d.id_cargo = c.id_cargos
	LEFT JOIN area a2 ON c.id_area = a2.id_area 
	
	 LEFT JOIN cat_ocupaciones oca ON oca.id_cat_ocup = ca.id_cat_ocup
      LEFT JOIN cat_escolaridad cea ON cea.id_cat_escolaridad = ca.id_cat_escolaridad
      LEFT JOIN cat_grupos_vuln cgva ON cgva.id_cat_grupo_vuln = ca.id_cat_grupo_vuln
      LEFT JOIN cat_genero cga ON cga.id_cat_gen = ca.id_cat_gen
      LEFT JOIN cat_nacionalidades cna ON cna.id_cat_nacionalidad = ca.id_cat_nacionalidad
      LEFT JOIN cat_discapacidades cda ON cda.id_cat_disc = ca.id_cat_disc
      LEFT JOIN cat_comunidades cmdsa ON cmdsa.id_cat_comun = ca.id_cat_comun
      LEFT JOIN cat_municipios cmpioa ON cmpioa.id_cat_mun = ca.id_cat_mun  
	  
	  ";
	if((int)$id_cat_derecho_general >0 ){
		$sql .=" LEFT JOIN rel_queja_der_gral rqdg ON rqdg.id_queja_date = q.id_queja_date
				LEFT JOIN cat_derecho_general cdg ON cdg.id_cat_derecho_general = rqdg.id_cat_derecho_general ";
	}else{	      
    $sql .=" LEFT JOIN ( SELECT DISTINCT a.id_queja_date, GROUP_CONCAT(cdg.descripcion SEPARATOR '. ')   as descripcion          
						FROM rel_queja_der_gral a 
						LEFT JOIN cat_derecho_general cdg ON cdg.id_cat_derecho_general = a.id_cat_derecho_general 
						GROUP by a.id_queja_date 
			)cdg ON cdg.id_queja_date = q.id_queja_date  ";
	}
	
	if((int)$id_cat_der_vuln >0	  ){
		$sql .=" LEFT JOIN rel_queja_der_vuln rqdv ON rqdv.id_queja_date = q.id_queja_date
				LEFT JOIN cat_der_vuln cdv ON cdv.id_cat_der_vuln = rqdv.id_cat_der_vuln ";
	}else{
		$sql .= " LEFT JOIN ( SELECT DISTINCT b.id_queja_date, GROUP_CONCAT(cdv.descripcion SEPARATOR '. ') as descripcion            
						FROM rel_queja_der_vuln b 
						LEFT JOIN cat_der_vuln cdv ON cdv.id_cat_der_vuln = b.id_cat_der_vuln  
						GROUP by b.id_queja_date 
				)cdv ON cdv.id_queja_date = q.id_queja_date  ";
	}
 $sql .=" WHERE q.id_queja_date > 0 ";
 /******************************* Datos Queja ************************************************/
 //ejercicio
 if((int)$year >0){
	$sql .= " AND q.folio_queja LIKE '%/".$year."-%' ";
 } 
 //Autoridad responsable
 if((int)$id_cat_aut >0){
	$sql .= " AND q.id_cat_aut = ".$id_cat_aut;
 } 
 //medio de presentacion
 if((int)$id_cat_med_pres >0){
	$sql .= " AND q.id_cat_med_pres = ".$id_cat_med_pres;
 } 
 //área asignada
 if((int)$id_area_asignada >0){
	$sql .= " AND q.id_area_asignada = ".$id_area_asignada;
 } 
 //usuario asignado
 if((int)$id_user_asignado >0){
	$sql .= " AND q.id_user_asignado = ".$id_user_asignado;
 } 
 //Estado procesal
 if((int)$estado_procesal >0){
	$sql .= " AND q.estado_procesal = ".$estado_procesal;
 } 
 //tipo resolucion
 if((int)$id_tipo_resolucion >0){
	$sql .= " AND q.id_tipo_resolucion = ".$id_tipo_resolucion;
 } 
 //tipo ambito
 if((int)$id_tipo_ambito >0){
	$sql .= " AND q.id_tipo_ambito = ".$id_tipo_ambito;
 }  
 //Entidad Federativa
 if(!empty($ent_fed )){
	$sql .= " AND q.ent_fed = '".($ent_fed)."' ";;
 }  
 //Municipio 
 if((int)$id_cat_munQ >0){
	$sql .= " AND q.id_cat_mun = ".$id_cat_munQ;
 }
 //Si paso por Mediación 
 if(!empty($mediacion )){
	$sql .= " AND q.mediacion = ".$mediacion;
 }
 //Si se genero medida cautelar 
 if(!empty($medida_cautelar )){
	$sql .= " AND q.medida_cautelar = ".$medida_cautelar;
 }
  //area creacion 
 if((int)$id_area_creacion > 0){
	$sql .= " AND a2.id_area = '".$id_area_creacion."' ";
 } 
 //Derecho General
 if((int)$id_cat_derecho_general >0){
	$sql .= " AND rqdg.id_cat_derecho_general = ".$id_cat_derecho_general;
 } 
  //Derecho Vulnerado
 if((int)$id_cat_der_vuln >0){
	$sql .= " AND rqdv.id_cat_der_vuln = ".$id_cat_der_vuln;
 }
// echo $sql;

/******************************* Datos Quejoso/Promovente ************************************************/
		
//Genero 
 if((int)$id_cat_genQ >0){
	$sql .= " AND cq.id_cat_gen = ".$id_cat_genQ;
 }
 //Escolaridad 
 if((int)$id_cat_escolaridadQ >0){
	$sql .= " AND cq.id_cat_escolaridad = ".$id_cat_escolaridadQ;
 }
 //Ocupacion 
 if((int)$id_cat_ocupQ >0){
	$sql .= " AND cq.id_cat_ocup = ".$id_cat_ocupQ;
 }
 //Leer y Escribior
 if(!empty($leer_escribirQ )){
	$sql .= " AND cq.leer_escribir = '".($leer_escribirQ)."' ";;
 } 
 //Discapacidad 
 if((int)$id_cat_discQ >0){
	$sql .= " AND cq.id_cat_disc = ".$id_cat_discQ;
 }
 //Grupo Vulnerable 
 if((int)$id_cat_grupo_vulnQ >0){
	$sql .= " AND cq.id_cat_grupo_vuln = ".$id_cat_grupo_vulnQ;
 }
 //Comunidad Indigena 
 if((int)$id_cat_comunQ >0){
	$sql .= " AND cq.id_cat_comun = ".$id_cat_comunQ;
 }
 //Nacionalidad 
 if((int)$id_cat_nacionalidadQ >0){
	$sql .= " AND cq.id_cat_nacionalidad = ".$id_cat_nacionalidadQ;
 }
 //Municipio 
 if((int)$id_cat_munP >0){
	$sql .= " AND cq.id_cat_mun = ".$id_cat_munP;
 }
 //nombre Quejos 
 if($nombre_quejoso != ''){
	$sql .= " AND cq.nombre like '%".$nombre_quejoso."%' ";
 } 
 //paterno Quejos 
 if($paterno_quejoso != ''){
	$sql .= " AND cq.paterno like '%".$paterno_quejoso."%' ";
 } 
 //materno Quejos 
 if($materno_quejoso != ''){
	$sql .= " AND cq.materno like '%".$materno_quejoso."%' ";
 }
 //Edades
 if((int)$edadQ >0){
	if((int)$edadQ == 1 ){
		$sql .= " AND (cq.edad >0 AND cq.edad <= 10 ) ";
	}else if((int)$edadQ == 2 ){
		$sql .= " AND (cq.edad >10 AND cq.edad <= 20 ) ";
	}else if((int)$edadQ == 3 ){
		$sql .= " AND (cq.edad >20 AND cq.edad <= 30 ) ";
	}else if((int)$edadQ == 4 ){
		$sql .= " AND (cq.edad >30 AND cq.edad <= 40 ) ";
	}else if((int)$edadQ == 5 ){
		$sql .= " AND (cq.edad >40 AND cq.edad <= 50 ) ";
	}else if((int)$edadQ == 6 ){
		$sql .= " AND (cq.edad >60 AND cq.edad <= 60 ) ";
	}else if((int)$edadQ == 7 ){
		$sql .= " AND (cq.edad > 60  ) ";
	}
	
		
 }
 
 

 $sql .= " ORDER BY q.fecha_creacion ";
 
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
        header("Content-Disposition: attachment; filename=busqueja_quejas.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
        $filename = "busqueja_quejas.xls";
        $mostrar_columnas = false;

        foreach ($quejas as $resolucion) {
            if (!$mostrar_columnas) {
                echo implode("\t", array_keys($resolucion)) . "\n";
                $mostrar_columnas = true;
            }
            echo implode("\t", array_map("utf8_decode", array_values(($resolucion)))) . "\n";
        }
		
		insertAccion($user['id_user'], '"' . $user['username'] . '" generó reporte de Quejas.', 3);
		
    } else {
        ?>
				<p style="font-size: 15px;">
					Lo sentimos,su búsqueda no generó ningun resultado le pedimos por favor vuelva a intentarlo.
				</p>
				
				<a href="busquedaquejas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="ACEPTAR">ACEPTAR </a>
				 
			<?php
    }
    exit;
}
       
    }    
 } 
?>	