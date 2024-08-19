<?php
require_once('includes/load.php');

page_require_level(1);
$user = current_user();

$mnj="Estado de Cumplimiento ";
$accion="";
$IDaccion=0;
if((int) $_GET['a']==0){
	////activa elemento	
	$action_id = activate_by_id('cat_edo_cumpl',(int)$_GET['id'],'estatus','id_cat_edo_cumpl');		
	$mnj .=" activado correctamente.";
	$accion .=" activo ";
	$IDaccion =3;
}else{
	////inactiva elemento
	 $action_id = inactivate_by_id('cat_edo_cumpl',(int)$_GET['id'],'estatus','id_cat_edo_cumpl');    
	$mnj .=" desactivado correctamente.";
	$accion .=" desactivo ";
	$IDaccion =4;
}
    
    if($action_id){
        $session->msg("s",$mnj);
		insertAccion($user['id_user'],'"'.$user['username'].'" '.$accion.' el Estado de Cumplimiento con ID:'.(int)$_GET['id'].'.',$IDaccion);
        redirect('cat_edo_cumplimiento.php');
    } else {
        $session->msg("d","Falló la accion sobre el Estado de Cumplimiento");
        redirect('cat_edo_cumplimiento.php');
    }

?>