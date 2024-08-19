<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
$page_title = 'Acuerdos de Queja';
require_once('includes/load.php');
?>
<?php
$e_detalle = find_by_id_queja((int) $_GET['id']);
//echo $e_detalle['id_queja_date'];
if (!$e_detalle) {
    $session->msg("d", "ID de queja no encontrado.");
    //redirect('quejas.php');
}
$user = current_user();
$nivel = $user['user_level'];
$users = find_all('users');
$id_user = $user['id_user'];
$area = find_all_areas_quejas();
$acuerdos_quejas = find_acuerdo_quejas((int) $_GET['id']);


if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    redirect('home.php');
}
if ($nivel == 4) {
    redirect('home.php');
}
if ($nivel == 5) {
    page_require_level(5);
}
if ($nivel == 6) {
    redirect('home.php');
}
if ($nivel == 7) {
    page_require_level(7);
}
$anio= substr($e_detalle['folio_queja'],-6,4);
?>
<?php


if (isset($_POST['acuerdo_queja'])) {

    if (empty($errors)) {

        $id = (int) $e_detalle['id_queja_date'];

        
    } 
}
?>

<script type="text/javascript">	
		
	$(document).ready(function() {
		 $("#interno").change(function() {
            $("#interno option:selected").each(function() {               
                //alert($(this).val());
				if($(this).val()==0){
					document.querySelector('#acuerdo_adjunto_publico').required = true;
					document.querySelector('#publico').checked = true;
				}else{
					document.querySelector('#acuerdo_adjunto_publico').required = false;
					document.querySelector('#publico').checked = false;
				}
            });
        })
		
		 $("#tipo_solicitud").change(function(){
	
	var pagina="";
	var id = $("#id_queja_date").val();
    
	if($(this).val() == '1'){
		pagina = "documento_estadoprocesal.php?id="+id;		
	}else{
		if($(this).val() == '2'){
			pagina = "documento_acuerdos.php?id="+id;	
		}else{
			if($(this).val() == '3'){
				pagina = "documento_documentos.php?id="+id;	
			}else{
				if($(this).val() == '4'){
					pagina = "documento_medida.php?id="+id;	
				}else{													
					pagina = "";										
				}
			}
		}
	}
		$("#acctionURL").attr("src",pagina);
		
  });
	});
	
	
</script>
<?php include_once('layouts/header.php'); ?>
 <a href="quejas.php?anio=<?php echo $anio?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">Regresar</a><br><br>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Queja
                    <?php echo $e_detalle['folio_queja']; ?>
                </span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="acuerdos_queja.php?id=<?php echo (int) $e_detalle['id_queja_date']; ?>" enctype="multipart/form-data">
			<input type="hidden" value="<?php echo (int) $e_detalle['id_queja_date']; ?>" name="id_queja_date" id="id_queja_date">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_cat_aut">Autoridad Responsable</label>
                            <input type="text" class="form-control" name="id_cat_aut" value="<?php echo remove_junk($e_detalle['nombre_autoridad']); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_quejoso">Nombre del Quejoso</label>
                            <input type="text" class="form-control" name="id_cat_quejoso" value="<?php echo remove_junk($e_detalle['nombre_quejoso'] . " " . $e_detalle['paterno_quejoso'] . " " . $e_detalle['materno_quejoso']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area_asignada">Área a la que se asignó la queja</label>
                            <input type="text" class="form-control" name="id_user_asignado" value="<?php foreach ($area as $a) {
                                                                                                        if ($a['id_area'] === $e_detalle['id_area_asignada'])
                                                                                                            echo $a['nombre_area'];
                                                                                                    } ?>" readonly>
                        </div>
                    </div>
                </div>

                <hr style="height: 1px; background-color: #370494; opacity: 1;">
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7263F0" width="25px" height="25px" viewBox="0 0 24 24" style="margin-top:-0.3%;">
                        <title>arrow-right-circle</title>
                        <path d="M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M6,13H14L10.5,16.5L11.92,17.92L17.84,12L11.92,6.08L10.5,7.5L14,11H6V13Z" />
                    </svg>
                    <span style="font-size: 20px; color: #7263F0">DOCUMENTOS EN EL EXPEDIENTE DE LA QUEJA</span>
                </strong>

				<div class="row">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Tipo Proceso</th>
                                <th scope="col">Nombre Acuerdo</th>
                                <th scope="col">Fecha Acuerdo</th>
                                <th scope="col">Gestión</th>
                                <th scope="col">Documento<br>Acuerdo</th>
                                <th scope="col">Versión<br>Pública</th>
                                <th scope="col" style="width:45%">Síntesis</th>
                                <th scope="col">¿Es público?</th>
                                <th scope="col">Acciónes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $folio_editar = $e_detalle['folio_queja'];
                            $resultado = str_replace("/", "-", $folio_editar);
							$carpeta_origen="/Acuerdos/";
                            $num = 1;
                            foreach ($acuerdos_quejas as $datos) :
							//$carpeta = $resultado . ((int)$datos['id_cat_tipo_res'] > 0?'/'.$datos['nombre_resolucion'].'/' :'/Acuerdos/') ;
							
							if($datos['origen_acuerdo']== 'Resolución'){
								$id_tipo_resolucion = $datos['id_cat_tipo_res'];;
								if((int)$id_tipo_resolucion === 2){
								//-------------------------------------------------------------Incompetencia-------------------------------------------------------------
									$carpeta_origen = "/Incompetencia/";
								}else if((int)$id_tipo_resolucion === 3){
								//-------------------------------------------------------------Sin Materia-------------------------------------------------------------
									$carpeta_origen = "/Sin_Materia/";
									
								}else if((int)$id_tipo_resolucion === 6){
								//-------------------------------------------------------------Desechamiento-------------------------------------------------------------
									$carpeta_origen = "/Desechamiento/";
									
								}else if((int)$id_tipo_resolucion === 7){
								//-------------------------------------------------------------Falta de interes-------------------------------------------------------------
									$carpeta_origen = "/Falta_de_Interes/";
									
								}else if((int)$id_tipo_resolucion === 8){
								//-------------------------------------------------------------Acumulación-------------------------------------------------------------
									$carpeta_origen = "/Acumulacion/";
									
								}else if((int)$id_tipo_resolucion === 9){
								//-------------------------------------------------------------Conciliación/Mediación-------------------------------------..------------------------
									$carpeta_origen = "/Conciliacion_Mediacion/";
									
								}else if((int)$id_tipo_resolucion === 10){
								//-------------------------------------------------------------Desistimiento-------------------------------------------------------------
									$carpeta_origen = "/Desistimiento/";
									
								}else if((int)$id_tipo_resolucion === 11){
								//-------------------------------------------------------------Improcedencia-------------------------------------------------------------;
									$carpeta_origen = "/Improcedencia/";
									
								}
							}
							
							
							$carpeta = $resultado . $carpeta_origen ;
                            ?>
                                <tr>
                                    <td><?php echo remove_junk(($datos['origen_acuerdo'])) ?></td>
                                    <td><?php echo remove_junk(($datos['tipo_acuerdo'])) ?></td>
                                    <td class="text-center"><?php echo date("d-m-Y", strtotime(remove_junk($datos['fecha_acuerdo']))) ?></td>
                                    <td class="text-center">
                                        &nbsp;&nbsp;&nbsp;
                                        <?php if (!$datos['documento_promocion'] == "" && (strlen ($datos['documento_promocion']))>2  ) { ?>
                                            <a target="_blank" href="uploads/quejas/<?php echo $carpeta . $datos['documento_promocion']; ?>" title="Ver Acuerdo">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                                    <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z" />
                                                </svg>
                                            </a>
                                        <?php } ?>
                                    </td>
									<td class="text-center">
                                        &nbsp;&nbsp;&nbsp;
                                        <?php if (!$datos['acuerdo_adjunto'] == "" ) { ?>
                                            <a target="_blank" href="uploads/quejas/<?php echo $carpeta. $datos['acuerdo_adjunto']; ?>" title="Ver Acuerdo">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                                    <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z" />
                                                </svg>
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if (!$datos['acuerdo_adjunto_publico'] == "") { ?>
                                            &nbsp;&nbsp;&nbsp;
                                            <a target="_blank" href="uploads/quejas/<?php echo $carpeta . $datos['acuerdo_adjunto_publico']; ?>" title="Ver Versión Publica del Acuerdo">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-medical" viewBox="0 0 16 16">
                                                    <path d="M7.5 5.5a.5.5 0 0 0-1 0v.634l-.549-.317a.5.5 0 1 0-.5.866L6 7l-.549.317a.5.5 0 1 0 .5.866l.549-.317V8.5a.5.5 0 1 0 1 0v-.634l.549.317a.5.5 0 1 0 .5-.866L8 7l.549-.317a.5.5 0 1 0-.5-.866l-.549.317V5.5zm-2 4.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z" />
                                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                                </svg>
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo remove_junk(($datos['sintesis_documento'])) ?></td>
                                    <td class="text-center"><?php echo remove_junk(($datos['publico'] == 1 ? "Sí" : "No")) ?></td>
                                    <td>
                                        <?php if ($datos['origen_acuerdo'] !== "Mediación") { ?>
										<a href="edit_acuerdo_queja.php?id=<?php echo (int)$datos['id_rel_queja_acuerdos']; ?>&q=<?php echo (int) $e_detalle['id_queja_date']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                <i class="glyphicon glyphicon-pencil"></i>
                                            </a>&nbsp;
                                        <a href="delete_acuerdo_queja.php?id=<?php echo (int) $datos['id_rel_queja_acuerdos']; ?>&q=<?php echo (int) $e_detalle['id_queja_date']; ?>" class="btn btn-delete btn-md" title="Eliminar" data-toggle="tooltip" onclick="return confirm('¿Seguro(a) que deseas eliminar el Acuerdo?');">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </a>
                                        <?php } ?>
                                    </td>

                                </tr>
                            <?php
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <br>
                <br>
                

                <hr style="height: 1px; background-color: #370494; opacity: 1;">
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7263F0" width="25px" height="25px" viewBox="0 0 24 24" style="margin-top:-0.3%;">
                        <title>arrow-right-circle</title>
                        <path d="M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M6,13H14L10.5,16.5L11.92,17.92L17.84,12L11.92,6.08L10.5,7.5L14,11H6V13Z" />
                    </svg>
                    <span style="font-size: 20px; color: #7263F0">NUEVO ACUERDO y/o DOCUMENTO DE LA QUEJA</span>
                </strong>
				
				 <div class="row" >
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="nombreQ">Tipo Proceso</label>
							<select class="form-control" name="tipo_solicitud" id="tipo_solicitud">
                                <option value="0">Escoge una opción</option>                                
                                    <option value="1">Estado Procesal</option>
                                    <option value="2">Acuerdos</option>
                                    <option value="3">Documentos Internos</option>
                                    <option value="4">Medida Cautelar</option>
                                    <!--<option value="a">Otras Actuaciones</option>
                                    <option value="t">Todo</option> -->
                            </select>
                        </div>
                    </div>
				</div>
				
				<div class="row" >
                    <iframe id="acctionURL" src=""  scrolling="no" style="position:absoluta; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:500px; border:none; margin:0; padding:0; overflow:hidden;"> </iframe>
                </div>
                               
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>