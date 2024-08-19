<?php
$page_title = 'Editar Acuerdo de Queja';
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];
$id_rel_queja_acuerdos=(int)$_GET['id'];
$id_queja_date=(int)$_GET['q'];
$queja = find_by_id_queja($id_queja_date);

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}

if ($nivel_user == 5) {
    page_require_level_exacto(5);
}
?>
<?php
$e_acuerdo = find_by_id('rel_queja_acuerdos', $id_rel_queja_acuerdos, 'id_rel_queja_acuerdos');
if (!$e_acuerdo) {
    $session->msg("d", "El Acuerdo de la queja  no existe, verifique el ID.");
    redirect('acuerdos_queja.php?id='.$id_queja_date);
}
if((int) $e_acuerdo['id_cat_tipo_res'] >0){
	$resolucion= find_by_id('cat_tipo_res', $e_acuerdo['id_cat_tipo_res'], 'id_cat_tipo_res');	
	$nombre_carpeta = str_replace("/", "_", str_replace(" ", "_", $resolucion['descripcion']));
}else{
	$nombre_carpeta = 'Acuerdos';
	
}
?>
<?php
if (isset($_POST['update'])) {
    if (empty($errors)) {
		
		$resultado = str_replace("/", "-", $queja['folio_queja']);		
		$carpeta = 'uploads/quejas/' . $resultado . '/'.$nombre_carpeta;
		
		$name = $_FILES['acuerdo_adjunto']['name'];
            $temp = $_FILES['acuerdo_adjunto']['tmp_name'];

            if (is_dir($carpeta)) {
                $move = move_uploaded_file($temp, $carpeta . "/" . $name);
            } else {
                mkdir($carpeta, 0777, true);
                $move = move_uploaded_file($temp, $carpeta . "/" . $name);
            }


        $query  = "UPDATE rel_queja_acuerdos SET ";
        $query .= "documento_promocion='{$name}' ";
        $query .= "WHERE id_rel_queja_acuerdos='{$db->escape($e_acuerdo['id_rel_queja_acuerdos'])}'";
		 
		$result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Acuerdo y/o Documento de la queja ha actualizado! ");
			insertAccion($user['id_user'],'"'.$user['username'].'" edito el Acuerdo y/o Documento '.$e_acuerdo['tipo_acuerdo'].', del Folio '.$queja['folio_queja'].' (id:'.(int)$e_acuerdo['id_rel_queja_acuerdos'].').',2);
            redirect('acuerdos_queja.php?id=' . (int)$e_acuerdo['id_queja_date'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado el Acuerdo y/o Documento, debido a que no hay cambios registrados en la descripción!');
            redirect('acuerdos_queja.php?id=' . (int)$e_acuerdo['id_queja_date'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('acuerdos_queja.php?id=' . (int)$e_acuerdo['id_queja_date'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8'); include_once('layouts/header.php'); ?>


        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    Actualizar Acuerdo de la queja: <?php echo (ucwords($queja['folio_queja'])); ?>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="edit_acuerdo_queja.php?id=<?php echo (int)$e_acuerdo['id_rel_queja_acuerdos']; ?>&q=<?php echo (int)$e_acuerdo['id_queja_date']; ?>" class="clearfix" enctype="multipart/form-data">
                    <div class="row">                    
						 <div class="col-md-2">
							<div class="form-group">
								<label for="id_tipo_resolucion">Nombre del Acuerdo </label>
								<input type="text" class="form-control" name="tipo_acuerdo" value="<?php echo $e_acuerdo['tipo_acuerdo']; ?>" readonly>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="id_tipo_resolucion">Fecha de Acuerdo</label>
								<input type="date" class="form-control" name="fecha_acuerdo" value="<?php echo $e_acuerdo['fecha_acuerdo']; ?>" readonly>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="id_tipo_resolucion">Gestión</label>
								<input id="acuerdo_adjunto" type="file" accept="application/pdf" class="form-control" name="acuerdo_adjunto" required>
							</div>
						</div>
                   
					</div>
					 <div class="form-group clearfix">
                        <a href="acuerdos_queja.php?id=<?php echo $id_queja_date?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="update" class="btn btn-info">Actualizar</button>
                    </div>
				</form>
		</div>
</div>
<?php include_once('layouts/footer.php'); ?>