<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Queja';
require_once('includes/load.php');
?>
<?php
$id = (int) $_GET['id'];
$e_detalle = find_by_id_queja($id);
$e_detalle2 = find_by_id_acuerdo($id);
$user = current_user();
$nivel = $user['user_level'];
$cat_est_procesal = find_all('cat_est_procesal');
$cat_municipios = find_all_cat_municipios();

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
    redirect('home.php');
}

if ($nivel == 7 || $nivel == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Nombre:'.($e_detalle['folio_queja']), 5);   
}
?>
      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script type="text/javascript" src="libs/js/cambiarPestanna.js"></script>
        
      <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<style>
</style>

<?php include_once('layouts/header.php'); ?>
<div class="container">  
	<ul class="nav nav-pills nav-fill justify-content-end">
		  <li class="nav-item eyelash">
			<a class="nav-link nav" href="#" onclick="openCity('generales')">Generales</a>
		  </li>
		  <li class="nav-item eyelash">
			<a class="nav-link " href="#" onclick="openCity('seguimiento')">Seguimiento</a>
		  </li>
		  <li class="nav-item eyelash">
			<a class="nav-link  " href="#" onclick="openCity('acuerdos')">Acuerdos</a>
		  </li>
	</ul>
	
	<div id="generales" class="w3-container w3-red city">
            <div class="panel panel-default">
                <div class="panel-heading clearfix" style="border-bottom:0px;">
                    <strong>
                        <span class="glyphicon glyphicon-th"></span>
                        <span>Información general de la Queja <?php echo remove_junk(ucwords($e_detalle['folio_queja'])) ?></span>
                    </strong>
                </div>					
            </div>    	 
	</div> 

	<div id="seguimiento" class="w3-container w3-red city">
	  <div class="panel panel-default">
                <div class="panel-heading clearfix" style="border-bottom:0px;">
                    <strong>
                        <span class="glyphicon glyphicon-th"></span>
                        <span>Seguimiento de la Queja <?php echo remove_junk(ucwords($e_detalle['folio_queja'])) ?></span>
                    </strong>
                </div>					
            </div>
	</div>

	<div id="acuerdos" class="w3-container w3-red city">
		<div class="panel panel-default">
                <div class="panel-heading clearfix" style="border-bottom:0px;">
                    <strong>
                        <span class="glyphicon glyphicon-th"></span>
                        <span>Expediente de la Queja <?php echo remove_junk(ucwords($e_detalle['folio_queja'])) ?></span>
                    </strong>
                </div>	

				<div class="panel-heading" style="text-align: right;border-bottom: 0px;" >				
					<a href="download_zip.php?id=<?php echo $id; ?>" class="btn btn-md btn-delete" data-toggle="tooltip" title="Descargar Expediente" onclick="return confirm('¿Seguro que deseas descargar el expediente completo de la queja? ');">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-zip" viewBox="0 0 16 16">
						  <path d="M6.5 7.5a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v.938l.4 1.599a1 1 0 0 1-.416 1.074l-.93.62a1 1 0 0 1-1.109 0l-.93-.62a1 1 0 0 1-.415-1.074l.4-1.599V7.5zm2 0h-1v.938a1 1 0 0 1-.03.243l-.4 1.598.93.62.93-.62-.4-1.598a1 1 0 0 1-.03-.243V7.5z"/>
						  <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm5.5-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9v1H8v1h1v1H8v1h1v1H7.5V5h-1V4h1V3h-1V2h1V1z"/>
						</svg>
					</a>						 
                </div>
				
				<table class="table">
				 <thead class="thead-light">
						<tr>
						  <th scope="col">#</th>
						  <th scope="col">Tipo Acuerdo</th>
						  <th scope="col">Fecha Acuerdo</th>
						  <th scope="col">Documentos</th>
						  <th scope="col">Síntesis</th>
						  <th scope="col">¿Es público?</th>
						</tr>
					  </thead>
					  <tbody>
					  
                <?php $num=1; foreach ($e_detalle2 as $detalle) : ?>
				
					<tr>
						  <th scope="row"><?php echo $num++?></th>
						  <td><?php echo remove_junk(($detalle['tipo_acuerdo'])) ?></td>
						  <td><?php echo remove_junk(($detalle['fecha_acuerdo'])) ?></td>
						  <td> 
						  &nbsp;&nbsp;&nbsp;
							<a target="_blank" href="uploads/quejas/<?php echo $resultado . '/Acuerdos/' . $detalle['acuerdo_adjunto']; ?>" title="Ver Acuerdo">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
								  <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
								  <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z"/>
								</svg>
							</a>
							<?php if(!$detalle['acuerdo_adjunto_publico'] == ""){?>
								&nbsp;&nbsp;&nbsp;
							<a target="_blank" href="uploads/quejas/<?php echo $resultado . '/Acuerdos/' . $detalle['acuerdo_adjunto_publico']; ?>" title="Ver Versión Publica del Acuerdo">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-medical" viewBox="0 0 16 16">
							  <path d="M7.5 5.5a.5.5 0 0 0-1 0v.634l-.549-.317a.5.5 0 1 0-.5.866L6 7l-.549.317a.5.5 0 1 0 .5.866l.549-.317V8.5a.5.5 0 1 0 1 0v-.634l.549.317a.5.5 0 1 0 .5-.866L8 7l.549-.317a.5.5 0 1 0-.5-.866l-.549.317V5.5zm-2 4.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z"/>
							  <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
							</svg>
							</a>
							<?php }?>
						  </td>
						  <td><?php echo remove_junk(($detalle['sintesis_documento'])) ?></td>
						  <td><?php echo remove_junk(($detalle['publico']==1?"Sí":"No")) ?></td>						  
						 
						</tr>
				
                <?php endforeach; ?>
				
					</tbody>
				</table>
        </div>
	</div>
  
</div>

<?php include_once('layouts/footer.php'); ?>