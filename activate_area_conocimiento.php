<?php
require_once('includes/load.php');

$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}
if ($nivel_user == 2) {
    page_require_level_exacto(2);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
}
if ($nivel_user > 2 && $nivel_user < 14) :
    redirect('home.php');
endif;
if ($nivel_user > 14) :
    redirect('home.php');
endif;

$mnj="Área del Conocimiento ";
$accion="";
$IDaccion=0;
if((int) $_GET['a']==0){
	////activa comunidad	
	$action_id = activate_by_id('cat_area_conocimiento',(int)$_GET['id'],'estatus','id_cat_area_con');		
	$mnj .=" activada correctamente.";
	$accion .=" activo ";
	$IDaccion =3;
}else{
	////inactiva comunidad
	 $action_id = inactivate_by_id('cat_area_conocimiento',(int)$_GET['id'],'estatus','id_cat_area_con');    
	$mnj .=" desactivada correctamente.";
	$accion .=" desactivo ";
	$IDaccion =4;
}
    
    if($action_id){
        $session->msg("s",$mnj);
		insertAccion($user['id_user'],'"'.$user['username'].'" '.$accion.' el Área del Conocimiento con ID:'.((int)$_GET['id']).'.',$IDaccion);
        redirect('cat_area_conocimiento.php');
    } else {
        $session->msg("d","Falló la accion sobre el área del conocimiento");
        redirect('cat_area_conocimiento.php');
    }

?>