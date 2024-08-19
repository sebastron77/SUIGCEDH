<?php
require_once('includes/load.php');

page_require_level(1);
$user = current_user();

$mnj="Grupo Vulnerable ";
$accion="";
$IDaccion=0;
if((int) $_GET['a']==0){
	////activa elemento	
	$action_id = activate_by_id('cat_grupos_vuln',(int)$_GET['id'],'estatus','id_cat_grupo_vuln');		
	$mnj .=" activado correctamente.";
	$accion .=" activo ";
	$IDaccion =3;
}else{
	////inactiva elemento
	 $action_id = inactivate_by_id('cat_grupos_vuln',(int)$_GET['id'],'estatus','id_cat_grupo_vuln');    
	$mnj .=" desactivado correctamente.";
	$accion .=" desactivo ";
	$IDaccion =4;
}
    
    if($action_id){
        $session->msg("s",$mnj);
		insertAccion($user['id_user'],'"'.$user['username'].'" '.$accion.' el Grupo Vulnerable con ID:'.(int)$_GET['id'].'.',$IDaccion);
        redirect('cat_grupo_vulnerable.php');
    } else {
        $session->msg("d","Falló la accion sobre los Grupo Vulnerable");
        redirect('cat_grupo_vulnerable.php');
    }

?>