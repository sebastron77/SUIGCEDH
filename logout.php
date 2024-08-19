<?php
  require_once('includes/load.php');
  $user = current_user();
  if(!$session->logout()) {
	  insertAccion($user['id_user'],'"'.$user['username'].'" cerró sesión.',0);
	  redirect("index.php");
	  }
?>
