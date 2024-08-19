<?php
require_once('includes/load.php');

page_require_level(1);
$user = current_user();

$mnj="Género ";
$accion="";
$IDaccion=0;
if((int) $_GET['a']==0){
	////activa elemento	
	$action_id = activate_by_id('cat_genero',(int)$_GET['id'],'estatus','id_cat_gen');		
	$mnj .=" activado correctamente.";
	$accion .=" activo ";
	$IDaccion =3;
}else{
	////inactiva elemento
	 $action_id = inactivate_by_id('cat_genero',(int)$_GET['id'],'estatus','id_cat_gen');    
	$mnj .=" desactivado correctamente.";
	$accion .=" desactivo ";
	$IDaccion =4;
}
    
    if($action_id){
        $session->msg("s",$mnj);
		insertAccion($user['id_user'],'"'.$user['username'].'" '.$accion.' el Género con ID:'.(int)$_GET['id'].'.',$IDaccion);
        redirect('cat_genero.php');
    } else {
        $session->msg("d","Falló la accion sobre los Género");
        redirect('cat_genero.php');
    }

?>