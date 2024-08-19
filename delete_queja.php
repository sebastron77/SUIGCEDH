<?php
  require_once('includes/load.php');
  
   page_require_level(1);
?>
<?php
  //$delete_id = delete_by_id('users',(int)$_GET['id']);
  $busca_queja = find_by_id('quejas',(int)$_GET['id']);

  $delete_queja = delete_by_id('quejas',(int)$_GET['id']);
  $delete_folio_queja = delete_by_folio_queja('folios',$busca_queja['folio_queja']);

  if($delete_queja){
      $session->msg("s","Queja eliminada");
      redirect('quejas_agregadas.php');
  } else {
      $session->msg("d","Se ha producido un error en la eliminación de la queja");
      redirect('quejas_agregadas.php');
  }
?>
