<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Búsqueda Trabajadores';
require_once('includes/load.php');


$user = current_user();
$id_user = $user['id_user'];
$busca_area = area_usuario($id_user);
$nivel = $user['user_level'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 14) {
    page_require_level_exacto(14);
}

if ($nivel > 2 && $nivel < 14) {
    redirect('home.php');
}

if (!$nivel) {
    redirect('home.php');
}

header('Content-type: text/html; charset=utf-8');

if (isset($_POST['export_data'])) {

    if (empty($errors)) {

        $area = remove_junk($db->escape($_POST['area']));
        $genero = remove_junk($db->escape($_POST['genero']));
        $puesto = remove_junk($db->escape($_POST['puesto']));
        $integrante = remove_junk($db->escape($_POST['integrante']));
        $ss = remove_junk($db->escape($_POST['ss']));
        $conocimiento = remove_junk($db->escape($_POST['conocimiento']));
        $escolaridad = remove_junk($db->escape($_POST['escolaridad']));
        $sueldo = remove_junk($db->escape($_POST['sueldo']));
        $gv = remove_junk($db->escape($_POST['gv']));
        $activo = remove_junk($db->escape($_POST['activo']));

        $conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
        mysqli_set_charset($conexion, "utf8");
        mysqli_select_db($conexion, "suigcedh");

        $sql = "SELECT 
                    CONCAT(d.nombre, ' ', d.apellidos) as trabajador, a.nombre_area, g.descripcion as genero, p.descripcion as puesto,
                    i.descripcion as tipo_integrante, d.monto_bruto,
                    CASE 
                        WHEN d.tiene_seguro = 0 THEN 'No' 
                        WHEN d.tiene_seguro = 1 THEN 'Sí' 
                        WHEN d.tiene_seguro IS NULL THEN 'Sin dato' 
                    END AS tiene_seguro, 
                    ac.descripcion as area_conocimiento, 
                    e.descripcion as escolaridad, gv.descripcion as grupo_vuln, IF(d.estatus_detalle = 0, 'Inactivo', 'Activo') as estatus

                FROM detalles_usuario d
                LEFT JOIN area a ON a.id_area = d.id_area
                LEFT JOIN cat_genero g ON g.id_cat_gen = d.id_cat_gen
                LEFT JOIN cat_puestos p ON p.id_cat_puestos = d.id_cat_puestos
                LEFT JOIN cat_tipo_integrante i ON i.id_tipo_integrante = d.id_tipo_integrante";

        if ((int)$conocimiento > 0) {
            $sql .= " LEFT JOIN rel_area_conocimiento as rac ON rac.id_detalle_usuario = d.id_det_usuario
                    LEFT JOIN cat_area_conocimiento as ac ON ac.id_cat_area_con = rac.tipo_area";
        } else {
            $sql .= " LEFT JOIN (SELECT DISTINCT rac.id_rel_area_con, rac.id_detalle_usuario, GROUP_CONCAT(ac.descripcion SEPARATOR '.') as descripcion
                    FROM rel_area_conocimiento rac
                    LEFT JOIN cat_area_conocimiento as ac ON ac.id_cat_area_con = rac.tipo_area GROUP BY rac.id_detalle_usuario)ac ON ac.id_detalle_usuario = d.id_det_usuario";
        }

        if ((int)$escolaridad > 0) {
            $sql .= " LEFT JOIN rel_curriculum_academico as  ca ON ca.id_rel_detalle_usuario = d.id_det_usuario
                    LEFT JOIN cat_escolaridad as e ON e.id_cat_escolaridad = ca.grado";
        } else {
            $sql .= " LEFT JOIN (SELECT DISTINCT ca.id_rel_cur_acad, ca.grado, ca.id_rel_detalle_usuario, GROUP_CONCAT(e.descripcion SEPARATOR '.') as descripcion
                    FROM rel_curriculum_academico ca
                    LEFT JOIN cat_escolaridad as e ON e.id_cat_escolaridad = ca.grado GROUP BY ca.id_rel_detalle_usuario)e ON e.id_rel_detalle_usuario = d.id_det_usuario";
        }

        if ((int)$gv > 0) {
            $sql .= " LEFT JOIN rel_detalle_gv as dg ON dg.id_detalle_usuario = d.id_det_usuario
                    LEFT JOIN cat_grupos_vuln as gv ON gv.id_cat_grupo_vuln = dg.id_cat_grupo_vuln";
        } else {
            $sql .= " LEFT JOIN (SELECT DISTINCT dg.id_rel_detalle_gv, dg.id_detalle_usuario, GROUP_CONCAT(gv.descripcion SEPARATOR '.') as descripcion
                    FROM rel_detalle_gv dg
                    LEFT JOIN cat_grupos_vuln as gv ON gv.id_cat_grupo_vuln = dg.id_cat_grupo_vuln GROUP BY dg.id_detalle_usuario)gv ON gv.id_detalle_usuario = d.id_det_usuario";
        }


        $sql .= " WHERE CAST(d.id_det_usuario as UNSIGNED) > 0";

        /******************************* Datos Queja ************************************************/
        /*
        sueldo
        activo
        */
        if ((int)$area > 0) {
            $sql .= " AND a.id_area = " . $area;
        }
        if ((int)$genero > 0) {
            $sql .= " AND g.id_cat_genero = " . $genero;
        }
        if ((int)$puesto > 0) {
            $sql .= " AND p.id_cat_puestos = " . $puesto;
        }
        if ((int)$conocimiento > 0) {
            $sql .= " AND ac.id_cat_area_con = " . $conocimiento;
        }
        if ((int)$escolaridad > 0) {
            $sql .= " AND e.id_cat_escolaridad = " . $escolaridad;
        }
        if ((int)$gv > 0) {
            $sql .= " AND gv.id_cat_grupo_vuln = " . $gv;
        }
        if ((int)$integrante > 0) {
            $sql .= " AND i.id_tipo_integrante = " . $integrante;
        }
        if ($ss != '') {
            $sql .= " AND d.tiene_seguro = " . $ss;
        }
        // if ($sueldo != '') {
        //     $sql .= " AND d.tiene_seguro = " . $sueldo;
        // }
        if ($sueldo == 1) {
            $sql .= " AND (CAST(REPLACE(d.monto_bruto, ',', '') AS FLOAT) BETWEEN 1000.00 AND 10000.00)";
        }
        if ($sueldo == 2) {
            $sql .= " AND (CAST(REPLACE(d.monto_bruto, ',', '') AS FLOAT) BETWEEN 10001.00 AND 20000.00)";
        }
        if ($sueldo == 3) {
            $sql .= " AND (CAST(REPLACE(d.monto_bruto, ',', '') AS FLOAT) BETWEEN 20001.00 AND 30000.00)";
        }
        if ($sueldo == 4) {
            $sql .= " AND (CAST(REPLACE(d.monto_bruto, ',', '') AS FLOAT) BETWEEN 30001.00 AND 40000.00)";
        }
        if ($sueldo == 5) {
            $sql .= " AND (CAST(REPLACE(d.monto_bruto, ',', '') AS FLOAT) BETWEEN 40001.00 AND 50000.00)";
        }
        if ($sueldo == 6) {
            $sql .= " AND (CAST(REPLACE(d.monto_bruto, ',', '') AS FLOAT) BETWEEN 50001.00 AND 100000.00)";
        }
        if ($activo != '') {
            $sql .= " AND d.estatus_detalle = " . $activo;
        }
        // $sql .= " ORDER BY rv.fecha_creacion ";
        // echo $sql;
        $resultado = mysqli_query($conexion, $sql) or die;
        $trabajadores = array();
        while ($rows = mysqli_fetch_assoc($resultado)) {
            $trabajadores[] = $rows;
        }

        mysqli_close($conexion);

        if (isset($_POST["export_data"])) {
            if (!empty($trabajadores)) {
                header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
                header("Content-Disposition: attachment; filename=reporte_trabajadores.xls");
                $filename = "reporte_trabajadores.xls";
                $mostrar_columnas = false;

                foreach ($trabajadores as $datos) {
                    if (!$mostrar_columnas) {
                        echo utf8_decode(implode("\t", array_keys($datos)) . "\n");
                        $mostrar_columnas = true;
                    }
                    echo utf8_decode(implode("\t", array_values($datos)) . "\n");
                }
                insertAccion($user['id_user'], '"' . $user['username'] . '" generó reporte de trabajadores.', 3);
            } else {
?>
                <p style="font-size: 17px; font-family: 'Montserrat'">
                    Lo sentimos, su búsqueda no generó ningún resultado ya que no hay coincidencias. Le pedimos por favor vuelva a intentarlo o verifique su información.
                </p>

                <a href="busquedapersonal.php" class="btn btn-md btn-success" data-toggle="tooltip" title="ACEPTAR">ACEPTAR </a>
<?php
            }
            exit;
        }
    }
}
?>
<?php include_once('layouts/footer.php'); ?>