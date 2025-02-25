<?php
$page_title = 'Informes de Actividades';
require_once('includes/load.php');
?>
<?php
page_require_level(53);

$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$area = isset($_GET['a']) ? $_GET['a'] : '0';


// Identificamos a que área pertenece el usuario logueado
$date_user = area_usuario2($id_user);
$user_area = $date_user['id_area'];
$nombre_area = $date_user['nombre_area'];

$area_informe = find_by_id('area', $area, 'id_area');

if($area > 0){
	/*if($area==4){
			// Identificamos a que área pertenece el usuario logueado
			$area_user = area_usuario2($id_user);
			$area = $area_user['id_area'];
			$nombre_area = $area_user['nombre_area'];
			$all_informe = find_all_informes_areas($area,$ejercicio);	
	}else{
		$all_informe = find_all_informes_areas($area,$ejercicio);			
	}*/
		$all_informe = find_all_informes_areas($area,$ejercicio);			
	
}else{
    $all_informe = find_all_informes_areasAdmin($ejercicio);	
}
$solicitud = find_by_solicitud($area);
if ($nivel_user == 7 || $nivel_user == 53) {
		insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la lista de  '.$page_title.' del Área ID-'.$solicitud['nombre_area'].'- del Ejercicio '.$ejercicio, 5);    
	} 
	
	

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT * FROM informe_actividades_areas a LEFT JOIN area b ON a.area_creacion= id_area  WHERE area_creacion=".$area." AND folio LIKE '%/" . $ejercicio ."-%' ";
$resultado = mysqli_query($conexion, $sql) or die;
$informe_sistemas = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $informe_sistemas[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($informe_sistemas)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=informe_areas.xls");
        $filename = "informe_areas.xls";
        $mostrar_columnas = false;

        foreach ($informe_sistemas as $resolucion) {
            if (!$mostrar_columnas) {
				 echo utf8_decode(implode("\t", array_keys($resolucion)) . "\n");
                $mostrar_columnas = true;
            }
			echo utf8_decode(implode("\t", array_values($resolucion)) . "\n");
        }
		if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" descargo los '.$page_title.' del Área '.$solicitud['nombre_area'].' del Ejercicio '.$ejercicio, 6);   

	}
    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}

?>
<script type="text/javascript">	
 function changueAnio(anio,area){
	 window.open("informes_areas.php?anio="+anio+"&a="+area,"_self");
	 
 }
</script>
<?php include_once('layouts/header.php'); ?>

<a href="<?php echo $solicitud['nombre_solicitud'];?>" class="btn btn-info">Regresar a Área</a><br><br>

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
								<span>Informes de Actividades <?php echo $ejercicio ?> de <?php echo $solicitud['nombre_area'];?> </span>
							</strong>
					</div>
					<div class="col-md-2">
						<div class="form-group" >
							<select class="form-control" name="ejercicio" onchange="changueAnio(this.value,<?php echo $area?>)">
								<option value="">Selecciona Ejercicio</option>																								
								<?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
								echo "<option value='".$i."'>".$i."</option>";
								}?>																									
							</select>
						</div>	
					</div>
					
						&nbsp;&nbsp;
							<span class="red" title="Con Observación" style="font-size:20px">r</span>&nbsp;<label>Con Observación</label> &nbsp
							<span class="yellow" title="En Revisión" style="font-size:20px">a</span>&nbsp;<label>En Revisión</label> &nbsp
							<span class="green" title="Aprobado" style="font-size:20px">v</span>&nbsp;<label>Aprobado</label> 
						&nbsp;&nbsp;
						 <form action="<?php echo $_SERVER["PHP_SELF"]; ?>?anio=<?php echo $ejercicio?>" method="post">
							<button style="float: right; margin-top: 0px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
						</form>
					
						<?php if( ( $nivel_user != 7) &&( $nivel_user != 53)) :         ?>								
								<a href="add_informe_areas.php?a=<?php echo $area?>" style="margin-left: 10px" class="btn btn-info pull-right">Agregar informe</a>
						<?php endif;?>

							<a target="_blank" style="color: blue;" href="uploads/informes/plantilla_informe_cualitativo.docx" >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-file-earmark-word" viewBox="0 0 16 16">
  <path d="M5.485 6.879a.5.5 0 1 0-.97.242l1.5 6a.5.5 0 0 0 .967.01L8 9.402l1.018 3.73a.5.5 0 0 0 .967-.01l1.5-6a.5.5 0 0 0-.97-.242l-1.036 4.144-.997-3.655a.5.5 0 0 0-.964 0l-.997 3.655L5.485 6.88z"/>
  <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
</svg>
                                </a>Plantilla Informe Cualitativo
						
				</div>
			</div>
		</div>
	</div>


    <div class="col-md-12">
       
        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr style="height: 10px;">
                        <th class="text-center" style="width: 3%;">Folio</th>	
                        <th class="text-center" style="width: 5%;">Status</th>
<?php if($area == 0){?>						
                        <th class="text-center" style="width: 3%;">Área</th>	
<?php }?>						
                        <th class="text-center" style="width: 5%;">No. Informe</th>
                        <th class="text-center" style="width: 5%;">Oficio de Entrega</th>
                        <th class="text-center" style="width: 7%;">Fecha de Informe</th>
                        <th class="text-center" style="width: 7%;">Fecha de Entrega</th>
                        <th class="text-center" style="width: 5%;">Informe</th>
						<?php if( ( $nivel_user != 53)) :         ?>	
                        <th style="width: 1%;" class="text-center">Acciones</th>
                            <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_informe as $a_informe) : ?>
                        <?php
                        $folio_editar = $a_informe['folio'];
                        $resultado = str_replace("/", "-", $folio_editar);
                        ?>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_informe['folio'])) ?></td>
                        <td class="text-center">
							<?php if($a_informe['status_st']=='En Revisión'){?>
								<h1><span class="yellow" title="En Revisión">a</span>
							<?php }else if($a_informe['status_st']=='Con Observación'){ ?>
								<h1><span class="red" title="Con Observación">r</span>
							<?php }else{ ?>
								<h1><span class="green" title="Aprobado">v</span>
							<?php } ?>
						</td>
						
							<?php if($area == 0){?>						
                        <td><?php echo remove_junk(ucwords($a_informe['nombre_area'])) ?></td>
<?php }?>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_informe['no_informe'])) ?></td>

                            <td style="text-align: center;">
							<?php 
								if($a_informe['oficio_entrega']  != ''){
							?>
                                <a target="_blank" style="color: #0094FF;" href="uploads/informesareas/<?php echo $resultado . '/' . $a_informe['oficio_entrega']; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                        <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z" />
                                    </svg>
                                </a>
							<?php 
								}
							?>
                            </td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_informe['fecha_informe'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($a_informe['fecha_entrega']))) ?></td>
                            <td style="text-align: center;">
                                <a target="_blank" style="color: #0094FF;" href="uploads/informesareas/<?php echo $resultado . '/' . $a_informe['informe_adjunto']; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                        <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z" />
                                    </svg>
                                </a>
                            </td>
                            <?php if(( $nivel_user != 53)) :         ?>	
                            <td class="text-center">
                            
                                <div class="btn-group">
                                    <a href="edit_informe_actividades_areas.php?id=<?php echo (int)$a_informe['id_info_act_areas']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>
                                </div>
                            
                            <?php if(( $nivel_user <=2) || ( $nivel_user == 7)) :         ?>	
                            
								 <div class="btn-group">
                                    <a href="status_env_correspondencia.php?id=<?php echo (int)$a_informe['id_info_act_areas']; ?>" class="btn btn-success btn-md" title="Revisión" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-question-sign"></span>
                                    </a>
                                </div>
                            <?php endif; ?>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<?php include_once('layouts/footer.php'); ?>