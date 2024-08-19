<?php
  require_once('includes/load.php');
  
   page_require_level(1);
?>
<?php
  $detalle_cargo_default = cargo_default((int)$_GET['id']);
  $delete_id = delete_by_id('cargos',(int)$_GET['id']);
  if($delete_id){
      $session->msg("s","Cargo eliminado");
      redirect('cargos.php');
  } else {
      $session->msg("d","Eliminación falló");
      redirect('cargos.php');
  }
?>
