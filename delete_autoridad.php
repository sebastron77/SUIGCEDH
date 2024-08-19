<?php
  require_once('includes/load.php');
  
   page_require_level(1);
?>
<?php
//   $detalle_cargo_default = cargo_default((int)$_GET['id']);
  $delete_id = delete_by_id('cat_autoridades',(int)$_GET['id'],'id_cat_aut');
  if($delete_id){
      $session->msg("s","Autoridad eliminada");
      redirect('autoridades.php');
  } else {
      $session->msg("d","Eliminación falló");
      redirect('autoridades.php');
  }
?>
