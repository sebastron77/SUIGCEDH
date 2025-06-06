
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Actuaciones';
require_once('includes/load.php');
?>
<?php
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$area_user1 = muestra_area($id_user);

// Identificamos a que área pertenece el usuario logueado
$area_user = area_usuario2($id_user);
$nombre_area = $area_user['nombre_area'];
$id_area = $area_user['id_area'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 5) {
    page_require_level_exacto(5);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 26) {
    page_require_level_exacto(26);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}
if ($nivel_user > 2 && $nivel_user < 5) :
    redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 19) :
    redirect('home.php');
endif;
if ($nivel_user > 19 && $nivel_user < 26) :
    redirect('home.php');
endif;
if ($nivel_user > 26 && $nivel_user < 50) :
    redirect('home.php');
endif;
if ($nivel_user > 50 && $nivel_user < 53) :
    redirect('home.php');
endif;

if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title.' del Ejercicio '.$ejercicio, 5);   
}




if (($nivel_user <= 2) || ($nivel_user == 7) || ($nivel_user == 50) || ($nivel_user == 53)) {
    $all_actuaciones = find_all_actuaciones($ejercicio);
} else {
    //$all_actuaciones = find_all_actuaciones_area($ejercicio,$area_user1['nombre_area']);
    $all_actuaciones = find_all_actuaciones_area($ejercicio,$area_user1['id_area']);
}

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT folio_actuacion,
       fecha_captura_acta,
	   area_creacion,
       catalogo as Tipo_Actuacion,
       REPLACE(descripcion,'\r\n',' ') as descripcion,
       r.nombre_autoridad as autoridad_estatal, 
	   ra.nombre_autoridad as autoridad_federal ,
       peticion,
	   nombre_desaparecido
FROM `actuaciones` a
LEFT JOIN cat_autoridades as r ON (a.autoridades = r.id_cat_aut)
  LEFT JOIN cat_autoridades as ra ON (a.autoridades_federales = ra.id_cat_aut) 
  WHERE  folio_actuacion LIKE '%/{$ejercicio}-%' ";
$resultado = mysqli_query($conexion, $sql) or die;
$atuaciones = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $atuaciones[] = $rows;
}

mysqli_close($conexion);
if (isset($_POST["export_data"])) {
    if (!empty($atuaciones)) {
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=atuaciones.xls");
        $filename = "atuaciones.xls";
        $mostrar_columnas = false;

        foreach ($atuaciones as $datos) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($datos)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($datos)) . "\n");
        }
		if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" descargó '.$page_title.' del Ejercicio '.$ejercicio, 6);    
		}
    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}

?>

<script type="text/javascript">	
 function changueAnio(anio,area){
	 //alert(anio);
	 window.open("actuaciones.php?anio="+anio+"&a="+area,"_self");
	 
 }
</script>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<a href="solicitudes_quejas.php" class="btn btn-success">Regresar</a><br><br>

<div class="row">

	<div class="row">
	    <div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<div class="col-md-10">
							<strong>
								<span class="glyphicon glyphicon-th"></span>
								<span>Lista de Actuaciones <?php echo $nombre_area ?></span>
							</strong>
					</div>
					<div class="col-md-2">
						<div class="form-group" >
							<select class="form-control" name="ejercicio" onchange="changueAnio(this.value,<?php echo $id_area?>)">
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
				
						 <?php if (($nivel == 1) || ($nivel == 5) || ($nivel == 26) || ($nivel == 50)) : ?>
                <a href="add_actuacion.php?a=<?php echo $id_area?>" style="margin-left: 10px;margin-right: 10px" class="btn btn-info pull-right">Agregar actuación</a>
                <?php endif; ?>										
					
					
				</div>
			</div>
		</div>
	</div>



    <div class="col-md-12">
        

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr style="height: 10px;">
					<th width="1%" >#</th>
                        <th style="width: 8%;">Folio</th>
                        <th style="width: 5%;">Captura</th>
                        <th style="width: 10%;">Usuario Creador</th>
                        <th style="width: 10%;">Área Creadora</th>
                        <th style="width: 3%;">Tipo actuación</th>
                        <th style="width: 15%;">Descripción</th>
                        <th style="width: 15%;">Autoridades</th>
                        <th style="width: 15%;">Nombre Desaparecido</th>
                        <th style="width: 3%;">Petición</th>
                        <th style="width: 5%;">Exp. Origen</th>
                        <th style="width: 5%;">Adjunto</th>
                        <th style="width: 1%;" class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_actuaciones as $a_actuacion) : ?>
                        <tr>
						<td class="text-center"><?php echo count_id(); ?></td>
                            <td><?php echo remove_junk(ucwords($a_actuacion['folio_actuacion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_actuacion['fecha_captura_acta'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_actuacion['nombre_creador'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_actuacion['area_creacion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_actuacion['catalogo'])) ?></td>
                            <td><?php echo substr(remove_junk(ucwords($a_actuacion['descripcion'])),0,100)."..." ?></td>
                            <?php if ($a_actuacion['autoridades'] == '') : ?>
                                <td><?php echo remove_junk(ucwords($a_actuacion['federal'])) ?></td>
                            <?php else : ?>
                                <td><?php echo remove_junk(ucwords($a_actuacion['estatal'])) ?></td>
                            <?php endif ?>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_actuacion['nombre_desaparecido']==null?'':$a_actuacion['nombre_desaparecido'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_actuacion['peticion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_actuacion['num_exp_origen'])) ?></td>
                            <?php
                            $folio_editar = $a_actuacion['folio_actuacion'];
                            $resultado = str_replace("/", "-", $folio_editar);
                            ?>
                            <td style="text-align: center;">
							<?php if($a_actuacion['adjunto'] !=''){?>
								<a target="_blank" style="color: red;" href="uploads/actuaciones/<?php echo $resultado . '/' . $a_actuacion['adjunto']; ?>">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                        <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z" />
                                    </svg>
								</a>
								<?php }?>
								</td> 
                           
                            <td class="text-center">
                                <div class="btn-group">
								 <a href="ver_info_actuacion.php?id=<?php echo (int) $a_actuacion['id_actuacion']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                                        <i class="glyphicon glyphicon-eye-open"></i>
                                    </a>&nbsp;
<?php if (($nivel == 1) || ($nivel == 5) || ($nivel == 50)) : ?>
                                    <a href="edit_actuacion.php?id=<?php echo (int)$a_actuacion['id_actuacion']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>
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