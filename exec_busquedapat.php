<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Busqueda Canalizaciones';
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
        $year = remove_junk($db->escape($_POST['ejercicio']));
        $id_area = remove_junk($db->escape($_POST['id_area']));

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");		

      $sql =" SELECT
  ejercicio,
	nombre_area,
	REPLACE(definicion_indicador,CHAR(13, 10),' ') as definicion_indicador ,
	REPLACE(nombre_indicador,CHAR(13, 10),' ') as nombre_indicador ,
	REPLACE(descripcion_metod_calculo,CHAR(13, 10),' ') as descripcion_metod_calculo,
	REPLACE(c.descripcion,CHAR(13, 10),' ')  as unidades_medida,
  valor_absoluto ,
  IFNULL(valor_real,0) as valor_real,
  IFNULL(fecha_actualización,'') as fecha_actualización
FROM indicadores_pat a
LEFT JOIN area b ON(a.id_area_responsable = b.id_area)
LEFT JOIN cat_unidades_medida c USING(id_cat_unidades_medida)
LEFT JOIN(
	SELECT
    	id_indicadores_pat ,
	  tipo ,
	  fecha_actualización,
	  SUM(
	  valor_enero +      
	  valor_febrero +
	  valor_marzo +
	  valor_abril +
	  valor_mayo +
	  valor_junio +
	  valor_julio +
	  valor_agosto +
	  valor_septiembre +
	  valor_octubre +
	  valor_noviembre +
	  valor_diciembre
      ) as valor_real 
	FROM rel_indicadores_calendarizacion
    WHERE tipo='Real' AND vigente=1 
    GROUP BY id_indicadores_pat,fecha_actualización
)d USING(id_indicadores_pat)
WHERE id_area>0
 ";
 /******************************* Datos Queja ************************************************/
 //ejercicio
 if((int)$year >0){
	$sql .= " AND ejercicio=".$year." ";
 } 
 //Autoridad responsable
 if((int)$id_area >0){
	$sql .= " AND id_area = ".$id_area;
 } 

 
 $sql .= " ORDER BY b.`jerarquia`; ";
$resultado = mysqli_query($conexion, $sql) or die;
$quejas = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $quejas[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($quejas)) {
         header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');        
        header("Content-Disposition: attachment; filename=busqueja_pat.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
        $filename = "busqueja_pat.xls";
        $mostrar_columnas = false;

        foreach ($quejas as $datos) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($datos)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($datos)) . "\n");
        }
		
		insertAccion($user['id_user'], '"' . $user['username'] . '" generó reporte de Canalizaiones.', 3);
		
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