<?php
$page_title = 'Orientaciones';
require_once('includes/load.php');
?>
<?php
// page_require_level(5);
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$all_orientaciones = find_all_orientaciones($ejercicio);
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 5) {
    page_require_level_exacto(5);
}
if ($nivel == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title.' del Ejercicio '.$ejercicio, 5);
    page_require_level_exacto(7);
}
if ($nivel == 19) {
    page_require_level_exacto(19);
}
if ($nivel == 21) {
    page_require_level_exacto(21);
}
if ($nivel == 26) {
    page_require_level_exacto(26);
}
if ($nivel == 50) {
    page_require_level_exacto(50);
}
if ($nivel == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title.' del Ejercicio '.$ejercicio, 5);
    page_require_level_exacto(53);
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
if ($nivel > 19 && $nivel < 21) :
    redirect('home.php');
endif;
if ($nivel > 21 && $nivel < 26) :
    redirect('home.php');
endif;
if ($nivel > 26 && $nivel < 50) :
    redirect('home.php');
endif;
if ($nivel > 50 && $nivel < 53) :
    redirect('home.php');
endif;

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT 
folio ,
'Orientaciones' as tipo_solicitud,
  correo_electronico ,
  nombre_completo ,
  b.`descripcion` as nivel_estudios ,
  c.`descripcion` as ocupacion ,
  edad ,
  a.telefono ,
  a.extension ,
  e.descripcion as sexo ,
  a.calle_numero ,
  a.colonia ,
  a.codigo_postal ,
  d.`descripcion` as municipio ,
  municipio_localidad as localidad,
  f.descripcion as entidad ,
  g.descripcion as nacionalidad ,
  h.descripcion as medio_presentacion ,
  j.`nombre_autoridad` as institucion_canaliza ,
  k.descripcion as grupo_vulnerable ,
  lengua  ,
  observaciones ,
  adjunto ,
  CONCAT(m.`nombre`,' ',m.`apellidos`) as usuario_creador,
  creacion  as fecha_creacion
 FROM orientacion_canalizacion a
 LEFT JOIN `cat_escolaridad` b ON(b.`id_cat_escolaridad`= nivel_estudios)
 LEFT JOIN `cat_ocupaciones` c ON(c.`id_cat_ocup`= a.ocupacion)
 LEFT JOIN `cat_municipios` d USING(id_cat_mun)
 LEFT JOIN `cat_genero` e ON(a.`sexo`=e.`id_cat_gen`)
 LEFT JOIN `cat_entidad_fed` f ON(a.`entidad` = f.`id_cat_ent_fed`)
 LEFT JOIN `cat_nacionalidades` g ON(a.`nacionalidad` = g.`id_cat_nacionalidad`)
 LEFT JOIN `cat_medio_pres` h ON(a.medio_presentacion = h.`id_cat_med_pres`)
 LEFT JOIN `cat_autoridades` j ON(a.`institucion_canaliza` = j.`id_cat_aut`)
 LEFT JOIN `cat_grupos_vuln` k ON(a.`grupo_vulnerable`=k.`id_cat_grupo_vuln`) 
 LEFT JOIN `users` l ON(a.`id_creador`= l.`id_user`)
 LEFT JOIN `detalles_usuario` m ON(l.`id_detalle_user`= m.`id_det_usuario`) WHERE tipo_solicitud=1  AND folio LIKE '%/".$ejercicio."-%'";
$resultado = mysqli_query($conexion, $sql) or die;
$orientaciones = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $orientaciones[] = $rows;
}

mysqli_close($conexion);
if (isset($_POST["export_data"])) {
    if (!empty($orientaciones)) {
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=orientaciones.xls");
        $filename = "orientaciones.xls";
        $mostrar_columnas = false;

        foreach ($orientaciones as $datos) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($datos)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($datos)) . "\n");
        }
		if ($nivel == 7 || $nivel == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" descargó '.$page_title.' del Ejercicio '.$ejercicio, 6);    
		}
    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}
?>
<script type="text/javascript">	
 function changueAnio(anio){
	 //alert(anio);
	 window.open("orientaciones.php?anio="+anio,"_self");
	 
 }
</script>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">

	<div class="row">
	    <div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<div class="col-md-10">
							<strong>
								<span class="glyphicon glyphicon-th"></span>
								<span>Lista de Orientaciones <?php echo $ejercicio ?></span>
							</strong>
					</div>
					<div class="col-md-2">
						<div class="form-group" >
							<select class="form-control" name="ejercicio" onchange="changueAnio(this.value)">
								<option value="">Selecciona Ejercicio</option>									
									<?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
								echo "<option value='".$i."'>".$i."</option>";
								}?>										
							</select>
						</div>	
					</div>
					
						<form action=" <?php echo $_SERVER["PHP_SELF"]; ?>?anio=<?php echo $ejercicio ?>" method="post">
								<button style="float: right; margin-top: 0px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
						</form>
						<?php if (($nivel == 1) || ($nivel == 5) || ($nivel == 26)|| ($nivel == 50)) : ?>
							<a href="add_orientacion.php" style="margin-left: 10px;margin-right: 10px" class="btn btn-info pull-right">Agregar Orientación</a>&nbsp;&nbsp;
						<?php endif; ?>		&nbsp;&nbsp;							
				</div>
			</div>
		</div>
	</div>


    <div class="col-md-12">       

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr>
					<th width="1%" >#</th>
                        <th width="15%">Folio</th>
                        <th width="10%">Fecha creación</th>
                        <th width="10%">Medio presentación</th>
                        <th width="1%">Correo</th>
                        <th width="25%">Nombre Completo</th>
                        <th width="45%">Creador</th>
                            <th width="10%;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_orientaciones as $a_orientacion) : ?>
                        <tr>
							<td class="text-center"><?php echo count_id(); ?></td>
                            <td>
                                <?php echo remove_junk(ucwords($a_orientacion['folio'])) ?>
                            </td>
                            <?php
                            $folio_editar = $a_orientacion['folio'];
                            $resultado = str_replace("/", "-", $folio_editar);
                            ?>
                            <td>
							<?php echo date_format(date_create(remove_junk(ucwords($a_orientacion['creacion']))), "d-m-Y"); ?>
                            </td>
                            <td>
                                <?php echo remove_junk(ucwords($a_orientacion['medio_pres'])) ?>
                            </td>

                            <td>
                                <?php echo remove_junk(ucwords($a_orientacion['correo_electronico'])) ?>
                            </td>
                            <td>
                                <?php echo remove_junk(ucwords(($a_orientacion['nombre_completo']))) ?>
                            </td>
                            <!-- <td><?php echo remove_junk(ucwords(($a_orientacion['ocupacion']))) ?></td> -->
                            <td>
                                <?php echo remove_junk($a_orientacion['nombre'] . " " . $a_orientacion['apellidos']) ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="ver_info_ori.php?id=<?php echo (int) $a_orientacion['idor']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                        <img src="medios/ver_info.png" style="width: 16px; border-radius: 15%; margin-right: -2px;">
                                    </a>&nbsp;
                                    <?php if (($nivel == 1) || ($nivel == 5) ||  ($nivel == 26) || ($nivel == 50)) : ?>
                                        <a href="edit_orientacion.php?id=<?php echo (int) $a_orientacion['idor']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                            <img src="medios/editar2.png" style="width: 16px; height: 16px; border-radius: 15%; margin-right: -2px;">                                            
                                        </a>&nbsp;
										 <a href="acuerdos_orin_cana.php?id=<?php echo (int) $a_orientacion['idor']; ?>" title="Expediente">
                                            <img src="medios/acuerdos2.png" style="width: 31px; height: 30.5px; margin-right: -2px;">
                                        </a>&nbsp;
                                    <?php endif; ?>
                                    <?php if ($nivel == 1) : ?>
                                        <!-- <a href="delete_orientacion.php?id=<?php echo (int) $a_orientacion['id']; ?>" class="btn btn-delete btn-md" title="Eliminar" data-toggle="tooltip" onclick="return confirm('¿Seguro(a) que deseas eliminar esta orientación?');">
                                                <span class="glyphicon glyphicon-trash"></span>
                                            </a> -->
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<?php include_once('layouts/footer.php'); ?>