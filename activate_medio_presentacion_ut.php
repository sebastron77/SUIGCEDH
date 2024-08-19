<?php
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 10) {
    page_require_level_exacto(10);
}
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 10 ) :
    redirect('home.php');
endif;
$user = current_user();

$mnj="Medio de Presentación ";
$accion="";
$IDaccion=0;
if((int) $_GET['a']==0){
	////activa elemento	
	$action_id = activate_by_id('cat_medio_pres_ut',(int)$_GET['id'],'estatus','id_cat_med_pres_ut');		
	$mnj .=" activado correctamente.";
	$accion .=" activo ";
	$IDaccion =3;
}else{
	////inactiva elemento
	 $action_id = inactivate_by_id('cat_medio_pres_ut',(int)$_GET['id'],'estatus','id_cat_med_pres_ut');    
	$mnj .=" desactivado correctamente.";
	$accion .=" desactivo ";
	$IDaccion =4;
}
    
    if($action_id){
        $session->msg("s",$mnj);
		insertAccion($user['id_user'],'"'.$user['username'].'" '.$accion.' el Medio de Presentación de las Solicitudes de Información con ID:'.(int)$_GET['id'].'.',$IDaccion);
        redirect('cat_medio_presentacion_ut.php');
    } else {
        $session->msg("d","Falló la accion sobre Medio de Presentación de las Solicitudes de Información  ");
        redirect('cat_medio_presentacion_ut.php');
    }

?>