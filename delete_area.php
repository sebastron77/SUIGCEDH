<?php
  require_once('includes/load.php');
  
   page_require_level(1);
?>
<?php
  //Para que cuando se borre un área, los cargos que pertenecían a esta
  //ahora aparezcan como "Sin área"
  $cargo_area_default = area_default((int)$_GET['id']);
  $delete_id = delete_by_id('area',(int)$_GET['id'],'id_area');

  if($delete_id){
      $session->msg("s","Área eliminada");
      redirect('areas.php');
  } else {
      $session->msg("d","Eliminación falló");
      redirect('areas.php');
  }
?>
