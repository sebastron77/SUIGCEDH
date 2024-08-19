<?php
  require_once('includes/load.php');
  
   page_require_level(1);
?>
<?php
  $delete_id = delete_by_id('detalles_usuario',(int)$_GET['id']);
  //$delete_asignacion = inactivate_by_id_asignacion('asignaciones',(int)$_GET['id'],'estatus_asignacion');
  if($delete_id){
      $session->msg("s","Trabajador eliminado");
      redirect('detalles_usuario.php');
  } else {
      $session->msg("d","Se ha producido un error en la eliminación del trabajador");
      redirect('detalles_usuario.php');
  }
?>
