<?php
  require_once('includes/load.php');
  
   page_require_level(1);
?>
<?php
  $activate_id = activate_grupo('grupo_usuarios',(int)$_GET['nivel_grupo'],'estatus_grupo');
  $activate_id2 = activate_user_group((int)$_GET['nivel_grupo']);
  if($activate_id){
      $session->msg("s","Grupo activado");
      redirect('group.php');
  } else {
      $session->msg("d","Activación falló");
      redirect('group.php');
  }
?>
