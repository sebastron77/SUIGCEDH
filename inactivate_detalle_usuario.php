<?php
  require_once('includes/load.php');  
  $user = current_user();
  $nivel_user = $user['user_level'];
if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
}
if ($nivel_user > 2 && $nivel_user < 14) :
    redirect('home.php');
endif;
if ($nivel_user > 14 ) :
    redirect('home.php');
endif;
?>
<?php
  $inactivate_id = inactivate_by_id('detalles_usuario',(int)$_GET['id'],'estatus_detalle','id_det_usuario');
  $inactivate_user = inactivate_by_id_user('users',(int)$_GET['id'],'status');

  if($inactivate_id){
	  $date_user = find_by_id('detalles_usuario', (int)$_GET['id'], 'id_det_usuario');
      $session->msg("s","Trabajador inactivado");
	  insertAccion($user['id_user'], '"'.$user['username'].'" inactivó al Trabajador: '.$date_user['nombre'].' '.$date_user['apellidos'].'(ID:'.$_GET['id'].' ).', 4);
      redirect('detalles_usuario.php');
  } else {
      $session->msg("d","Se ha producido un error en la inactivación del trabajador");
      redirect('detalles_usuario.php');
  }
?>
