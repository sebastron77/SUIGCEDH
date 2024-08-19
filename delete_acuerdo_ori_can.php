<?php
  require_once('includes/load.php');
  $user = current_user();
  
?>
<?php
  //Para que cuando se borre un área, los cargos que pertenecían a esta
  //ahora aparezcan como "Sin área"
  $dato = find_by_tipo((int)$_GET['id']);
  $tipo_solicitud = ((int)$dato['tipo_solicitud']==1?'Orientación':'Canalización');
  $delete_id = delete_by_id('rel_orientacion_canalizacion_acuerdos',(int)$_GET['id'],'id_rel_orientacion_canalizacion_acuerdos');
  if($delete_id){
      $session->msg("s","Acuerdo Eliminado");
	 insertAccion($user['id_user'], '"' . $user['username'] . '" eliminó el acuerdo de la '.$tipo_solicitud.', Folio: ' . $dato['folio'] . '.', 4); 
      redirect('acuerdos_orin_cana.php?id='.(int)$_GET['q']);
  } else {
      $session->msg("d","Eliminación falló");
      redirect('acuerdos_orin_cana.php?id='.(int)$_GET['q']);
  }
?>
