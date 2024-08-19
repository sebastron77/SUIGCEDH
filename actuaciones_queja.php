<?php
$page_title = 'Actuaciones de Queja';
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
$actuaciones_quejas = find_acuaciones_mediacion((int) $_GET['id']);
$cat_estatus_mediacion = find_all('cat_estatus_mediacion');


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
?>
<?php


if (isset($_POST['actuaciones_queja'])) {

    if (empty($errors)) {
	
        $id = (int) $e_detalle['id_queja_date'];
		$actuaciones = array();
		
		$estatus = $_POST['estatus'];
		$query = "DELETE FROM rel_queja_mediacion WHERE id_queja_date =" . $id;
		$db->query($query) ;           
        
		
		for ($i = 0; $i < sizeof($estatus); $i = $i + 1) {		
				if($i < 13){
					$datos = find_by_id('cat_estatus_mediacion', ($i+1), 'id_cat_estatus_mediacion');
					$nombre = $datos['descripcion'];
					$queryInsert = "INSERT INTO rel_queja_mediacion (id_queja_date,concepto,valor) VALUES('$id','$nombre','$estatus[$i]')";
					if ($db->query($queryInsert)) {													
							$acction = true;
						} 
				}else{
					if($i == 13){
						$queryInsert = "INSERT INTO rel_queja_mediacion (id_queja_date,concepto,valor) VALUES('$id','Desistimiento de la queja','$estatus[$i]')";
						if ($db->query($queryInsert)) {														
								$acction = true;
						} 
					}
					if($i == 14){
						$queryInsert = "INSERT INTO rel_queja_mediacion (id_queja_date,concepto,valor) VALUES('$id','Desistimiento de la mediación','$estatus[$i]')";
						if ($db->query($queryInsert)) {						
								$acction = true;
						} 
					}
				}
			}
			insertAccion($user['id_user'], '"'.$user['username'].'" agregó Valores a las actuaciones de la queja '.$id.', del Folio: '.$e_detalle['folio_queja'].'.', 1);
			redirect('mediacion.php', false);
			
			
    } else {
        $session->msg("d", $errors);
        // redirect('acuerdos_queja.php?id=' . (int) $e_detalle['id_queja_date'], false);
    }
}
?>
<script type="text/javascript">

</script>
<?php include_once('layouts/header.php'); ?>
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
            <form method="post" action="actuaciones_queja.php?id=<?php echo (int) $e_detalle['id_queja_date']; ?>" enctype="multipart/form-data">
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
                    <span style="font-size: 20px; color: #7263F0">ACTUACIONES MEDIACIÓN DE LA QUEJA</span>
                </strong>
	<br>
	<?php
			$val=0;
		//if($actuaciones_quejas){
			$datos_actuaciones = array();
			foreach ($actuaciones_quejas as $dato) :
				array_push($datos_actuaciones,$dato['valor']);					
			endforeach; 
		//}
	?>
	<br>
                <div class="row" style="border-radius: 10px 10px 10px 10px;">
                    <?php foreach ($cat_estatus_mediacion as $dato) : ?>
						<div class="col-md-3">
							<div class="form-group">
								<label for="tipo_acuerdo"><?php echo $dato['descripcion']; ?></label>
								<input type="number"  class="form-control" max="10000" name="estatus[]" value="<?php echo (($datos_actuaciones)?$datos_actuaciones[$val]:0) ; ?>" >                         
							</div>
						</div>
                    <?php $val++; endforeach; ?>
					
						<div class="col-md-3">
							<div class="form-group">
								<label for="tipo_acuerdo">Desistimiento de la queja</label>
								<input type="number"  class="form-control" max="10000" name="estatus[]" value="<?php echo (($datos_actuaciones)?$datos_actuaciones[$val]:0) ; ?>" >                         
							</div>
						</div>



						<div class="col-md-3">
							<div class="form-group">
								<label for="tipo_acuerdo">Desistimiento de la mediación.</label>
								<input type="number"  class="form-control" max="10000" name="estatus[]" value="<?php echo (($datos_actuaciones)?$datos_actuaciones[$val+1]:0) ; ?>" >                         
							</div>
						</div>

                </div>

               
                <div class="form-group clearfix">
                    <a href="mediacion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="actuaciones_queja" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>