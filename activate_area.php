<?php
  require_once('includes/load.php');
  
  page_require_level(1);
?>
<?php
  $activate_id = activate_by_id('area',(int)$_GET['id'],'estatus_area','id_area');
  $activate_cargo = activate_area_cargo((int)$_GET['id']);

  if($activate_id){
      $session->msg("s","Área activada");
      redirect('areas.php');
  } else {
      $session->msg("d","Activación falló");
      redirect('areas.php');
  }
?>
