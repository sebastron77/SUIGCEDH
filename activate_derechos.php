<?php
require_once('includes/load.php');

page_require_level(1);
$user = current_user();

$mnj="Derecho Vulnerado ";
$accion="";
$IDaccion=0;
if((int) $_GET['a']==0){
	////activa elemento	
	$action_id = activate_by_id('cat_der_vuln',(int)$_GET['id'],'estatus','id_cat_der_vuln');		
	$mnj .=" activado correctamente.";
	$accion .=" activo ";
	$IDaccion =3;
}else{
	////inactiva elemento
	 $action_id = inactivate_by_id('cat_der_vuln',(int)$_GET['id'],'estatus','id_cat_der_vuln');    
	$mnj .=" desactivado correctamente.";
	$accion .=" desactivo ";
	$IDaccion =4;
}
    
    if($action_id){
        $session->msg("s",$mnj);
		insertAccion($user['id_user'],'"'.$user['username'].'" '.$accion.' el Derecho Vulnerado con ID:'.(int)$_GET['id'].'.',$IDaccion);
        redirect('cat_derechos_vulnerados.php');
    } else {
        $session->msg("d","Falló la accion sobre los derechos vulnerados");
        redirect('cat_comunidad.php');
    }

?>