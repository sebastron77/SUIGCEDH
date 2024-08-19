<?php
require_once('includes/load.php');

page_require_level(1);
$user = current_user();

$mnj="Estado Procesal ";
$accion="";
$IDaccion=0;
if((int) $_GET['a']==0){
	////activa elemento	
	$action_id = activate_by_id('cat_est_procesal',(int)$_GET['id'],'estatus','id_cat_est_procesal');		
	$mnj .=" activado correctamente.";
	$accion .=" activo ";
	$IDaccion =3;
}else{
	////inactiva elemento
	 $action_id = inactivate_by_id('cat_est_procesal',(int)$_GET['id'],'estatus','id_cat_est_procesal');    
	$mnj .=" desactivado correctamente.";
	$accion .=" desactivo ";
	$IDaccion =4;
}
    
    if($action_id){
        $session->msg("s",$mnj);
		insertAccion($user['id_user'],'"'.$user['username'].'" '.$accion.' el Estado Procesal con ID:'.(int)$_GET['id'].'.',$IDaccion);
        redirect('cat_edo_procesal.php');
    } else {
        $session->msg("d","Falló la accion sobre el Estado Procesal");
        redirect('cat_edo_procesal.php');
    }

?>