<?php
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 15) {
    page_require_level_exacto(15);
}

if ($nivel_user > 2 && $nivel_user < 15) :
    redirect('home.php');
endif;
if ($nivel_user > 15 ) :
    redirect('home.php');
endif;

$mnj="Tipo de Diseños ";
$accion="";
$IDaccion=0;
if((int) $_GET['a']==0){
	////activa elemento	
	$action_id = activate_by_id('cat_tipo_disenios',(int)$_GET['id'],'estatus','id_cat_tipo_disenios');		
	$mnj .=" activado correctamente.";
	$accion .=" activo ";
	$IDaccion =3;
}else{
	////inactiva elemento
	 $action_id = inactivate_by_id('cat_tipo_disenios',(int)$_GET['id'],'estatus','id_cat_tipo_disenios');    
	$mnj .=" desactivado correctamente.";
	$accion .=" desactivo ";
	$IDaccion =4;
}
    
    if($action_id){
        $session->msg("s",$mnj);
		insertAccion($user['id_user'],'"'.$user['username'].'" '.$accion.' el Tipo de Diseño con ID:'.(int)$_GET['id'].'.',$IDaccion);
        redirect('cat_tipo_disenios.php');
    } else {
        $session->msg("d","Falló la accion sobre el Tipo de diseños");
        redirect('cat_tipo_disenios.php');
    }

?>