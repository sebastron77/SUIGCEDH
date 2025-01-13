<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Busqueja Vacaciones';
require_once('includes/load.php');


$user = current_user();
$id_user = $user['id_user'];
$busca_area = area_usuario($id_user);
$otro = $busca_area['nivel_grupo'];
$nivel = $user['user_level'];

if ($nivel == 1) {
    page_require_level_exacto(1);
}
if ($nivel == 2) {
    page_require_level_exacto(2);
}
if ($nivel == 14) {
    page_require_level_exacto(14);
}
if ($nivel == 29) {
    page_require_level_exacto(29);
}
if ($nivel > 2 && $nivel < 14) {
    redirect('home.php');
}
if ($nivel > 14 && $nivel < 29) {
    redirect('home.php');
}
if ($nivel > 29) {
    redirect('home.php');
}

header('Content-type: text/html; charset=utf-8');

if (isset($_POST['export_data'])) {

    if (empty($errors)) {
        $periodo = remove_junk($db->escape($_POST['periodo']));
        $id_area = remove_junk($db->escape($_POST['id_area']));
        $derecho = remove_junk($db->escape($_POST['derecho']));
        $semana1 = remove_junk($db->escape($_POST['semana1']));
        $semana2 = remove_junk($db->escape($_POST['semana2']));

        $conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
        mysqli_set_charset($conexion, "utf8");
        mysqli_select_db($conexion, "suigcedh");

        $sql = "SELECT 
                d.id_det_usuario, 
				d.nombre, 
				d.apellidos,  
				rv.ejercicio, 
				pv.descripcion as periodo,
				IF(rv.derecho_vacas = 0, 'No', 'Sí') as derecho, 
                 REPLACE(REPLACE(REPLACE(rv.observaciones,'\r',''),'\t',''),'\n','_') as observaciones, 
				 rpv.semana1_1 as del_dia, 
				 rpv.semana1_2 as al_dia, 
				 a.nombre_area

                FROM rel_vacaciones rv
                LEFT JOIN rel_periodos_vac rpv ON rv.id_rel_vacaciones = rpv.id_rel_vacaciones
                LEFT JOIN detalles_usuario as d ON d.id_det_usuario = rv.id_detalle_usuario
                LEFT JOIN cat_periodos_vac as pv ON pv.id_cat_periodo_vac = rv.id_cat_periodo_vac
                LEFT JOIN area as a ON a.id_area = d.id_area
                WHERE  CAST(rv.ejercicio AS UNSIGNED) = YEAR(CURDATE())";

        /******************************* Datos Queja ************************************************/
        //ejercicio
        if ((int)$periodo > 0) {
            $sql .= " AND rv.id_cat_periodo_vac = " . $periodo;
        }
        //área asignada
        if ($derecho != '') {
            $sql .= " AND rv.derecho_vacas = " . $derecho;
        }
        //fecha_acuerdo
        if ($id_area != NULL) {
            $sql .= " AND a.id_area = '" . $id_area . "' ";
        }

        //fecha_acuerdo
        if ($semana1 != '') {
            $sql .= " AND rpv.semana1_1 >=  '" . $semana1 . "' ";
        }

        //fecha_acuerdo
        if ($semana2 != '') {
            $sql .= " AND rpv.semana1_2 <=  '" . $semana2 . "' ";
        }


        $sql .= " ORDER BY d.nombre,semana1_1 ";
		//echo $sql;
        $resultado = mysqli_query($conexion, $sql) or die;
        $vacaciones = array();
        while ($rows = mysqli_fetch_assoc($resultado)) {
            $vacaciones[] = $rows;
        }

        mysqli_close($conexion);

        if (isset($_POST["export_data"])) {
            if (!empty($vacaciones)) {
                header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
                header("Content-Disposition: attachment; filename=vacaciones_vigentes.xls");
                $filename = "vacaciones_vigentes.xls";
                $mostrar_columnas = false;

                foreach ($vacaciones as $datos) {
                    if (!$mostrar_columnas) {
                        echo utf8_decode(implode("\t", array_keys($datos)) . "\n");
                        $mostrar_columnas = true;
                    }
                    echo utf8_decode(implode("\t", array_values($datos)) . "\n");
                }
                insertAccion($user['id_user'], '"' . $user['username'] . '" generó reporte de vacaciones.', 3);
            } else {
?>
                <p style="font-size: 17px; font-family: 'Montserrat'">
                    Lo sentimos, su búsqueda no generó ningún resultado ya que no hay coincidencias. Le pedimos por favor vuelva a intentarlo o verifique su información.
                </p>
                
<a href="busquedavacaciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="ACEPTAR">ACEPTAR </a>
<?php
            }
            exit;
        }
    }
}
?>
<?php include_once('layouts/footer.php'); ?>