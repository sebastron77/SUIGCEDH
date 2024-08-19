<?php
require_once('includes/load.php');

page_require_level(1);
$user = current_user();

$mnj="Institución de Procedencia ";
$accion="";
$IDaccion =0;
if((int) $_GET['a']==0){
	////activa comunidad	
	$action_id = activate_by_id('cat_instituciones',(int)$_GET['id'],'estatus','id_cat_instituciones');		
	$mnj .=" activada correctamente.";
	$accion .=" activo ";
	$IDaccion =3;
}else{
	////inactiva comunidad
	 $action_id = inactivate_by_id('cat_instituciones',(int)$_GET['id'],'estatus','id_cat_instituciones');    
	$mnj .=" desactivada correctamente.";
	$accion .=" desactivo ";
	$IDaccion =4;
}
    
    if($action_id){
        $session->msg("s",$mnj);
		insertAccion($user['id_user'],'"'.$user['username'].'" '.$accion.' la Institución de Procedencia con ID:'.(int)$_GET['id'].'.',$IDaccion);
        redirect('cat_instituciones.php');
    } else {
        $session->msg("d","Falló la accion sobre la Institución de Procedencia");
        redirect('cat_instituciones.php');
    }

?>