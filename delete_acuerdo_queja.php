<?php
  require_once('includes/load.php');
  $user = current_user();
  
   if ($nivel == 1) {
    page_require_level_exacto();
}   
if ($nivel == 5) {
    page_require_level_exacto(5);
}
?>
<?php
  //Para que cuando se borre un área, los cargos que pertenecían a esta
  //ahora aparezcan como "Sin área"
  $e_acuerdo = find_by_id('rel_queja_acuerdos', (int)$_GET['id'], 'id_rel_queja_acuerdos');  
  $queja = find_by_id_queja($e_acuerdo['id_queja_date']);
  $delete_id = delete_by_id('rel_queja_acuerdos',(int)$_GET['id'],'id_rel_queja_acuerdos');
  if($delete_id){
      $session->msg("s","Acuerdo Eliminado");
	 insertAccion($user['id_user'], '"' . $user['username'] . '" eliminó el acuerdo de queja, Folio: ' . $queja['folio_queja'] . '.', 4); 
      redirect('acuerdos_queja.php?id='.(int)$_GET['q']);
  } else {
      $session->msg("d","Eliminación falló");
      redirect('acuerdos_queja.php?id='.(int)$_GET['q']);
  }
?>
