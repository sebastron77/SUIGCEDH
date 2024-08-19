<?php
  require_once('includes/load.php');
  
   page_require_level(1);
?>
<?php
  $inactivate_id = inactivate_grupo('grupo_usuarios',(int)$_GET['nivel_grupo'],'estatus_grupo');
  $inactivate_id = inactivate_user_group('users',(int)$_GET['nivel_grupo'],'status');
  if($inactivate_id){
      $session->msg("s","Grupo inactivado");
      redirect('group.php');
  } else {
      $session->msg("d","Inactivación falló");
      redirect('group.php');
  }
?>
