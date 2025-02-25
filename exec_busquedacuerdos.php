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


$area = find_all_areas_quejas();
header('Content-type: text/html; charset=utf-8');
if (isset($_POST['export_data'])) {

    if (empty($errors)) {
		$year = remove_junk($db->escape($_POST['years']));
        $id_area_asignada = remove_junk($db->escape($_POST['id_area_asignada']));
        $fecha_acuerdo = remove_junk($db->escape($_POST['fecha_acuerdo']));
		
$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");		

      $sql =" SELECT 
        q.folio_queja,
        au.nombre_autoridad,
        a.nombre_area as nombre_area_asignada,
        IFNULL(tipo_acuerdo,'SIn Acuerdo') as tipo_acuerdo,
        fecha_acuerdo,
       REPLACE(sintesis_documento,'\r\n',' ') as  sintesis_documento,
        IF(tipo_acuerdo is NULL,'',IF(publico=1,'Si','No')) as publico,
        origen_acuerdo,
		IF(q.id_user_creador is NULL,'Queja creada en Línea',CONCAT(d.nombre,' ',d.apellidos)) as nombre_creador_de_queja,
	    a2.nombre_area as area_creador_de_queja       
		
 FROM quejas_dates q
 LEFT JOIN rel_queja_acuerdos rqa USING(id_queja_date)
      LEFT JOIN cat_medio_pres mp ON mp.id_cat_med_pres = q.id_cat_med_pres 
      LEFT JOIN cat_autoridades au ON au.id_cat_aut = q.id_cat_aut
      LEFT JOIN users u ON u.id_user = q.id_user_asignado
      LEFT JOIN area a ON a.id_area = q.id_area_asignada     
      LEFT JOIN cat_tipo_res tr ON tr.id_cat_tipo_res = q.id_tipo_resolucion
      LEFT JOIN cat_tipo_ambito ta ON ta.id_cat_tipo_ambito = q.id_tipo_ambito
      LEFT JOIN rel_queja_der_gral rqdg ON rqdg.id_queja_date = q.id_queja_date
      LEFT JOIN cat_derecho_general cdg ON cdg.id_cat_derecho_general = rqdg.id_cat_derecho_general
      LEFT JOIN rel_queja_der_vuln rqdv ON rqdv.id_queja_date = q.id_queja_date
      LEFT JOIN cat_der_vuln cdv ON cdv.id_cat_der_vuln = rqdv.id_cat_der_vuln
	  LEFT JOIN users as u2 ON u2.id_user = q.id_user_creador
	LEFT JOIN detalles_usuario as d ON d.id_det_usuario = u2.id_detalle_user
	LEFT JOIN cargos c ON d.id_cargo = c.id_cargos
	LEFT JOIN area a2 ON c.id_area = a2.id_area
 WHERE q.id_queja_date > 0 ";
 /******************************* Datos Queja ************************************************/
 //ejercicio
 if((int)$year >0){
	$sql .= " AND q.folio_queja LIKE '%/".$year."-%' ";
 } 
 //área asignada
 if((int)$id_area_asignada >0){
	$sql .= " AND q.id_area_asignada = ".$id_area_asignada;
 }  
  //fecha_acuerdo
 if($fecha_acuerdo != NULL){
	$sql .= " AND rqa.fecha_acuerdo = '".$fecha_acuerdo."' ";
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
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=acuerdos_quejas.xls");
        $filename = "acuerdos_quejas.xls";
        $mostrar_columnas = false;

        foreach ($quejas as $datos) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($datos)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($datos)) . "\n");
        }
	insertAccion($user['id_user'], '"' . $user['username'] . '" generó reporte de Acuerdos/Quejas.', 3);
	
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