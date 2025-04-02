<?php
require_once('includes/load.php');
if (isset($_GET['id'])  ) {
	$user = current_user();
	$nivel_user = $user['user_level'];
    $id = $_GET['id'];
	$evidencias = find_by_id('rel_eventos_evidencias', (int)$_GET['id'], 'id_rel_eventos_evidencias');
	$nombre_documento= $evidencias['nombre_documento'];
	$a_evento = find_by_id('eventos', $evidencias['id_evento'], 'id_evento');
	$resultado = str_replace("/", "-", $a_evento['folio']);
    $filepath = 'uploads/eventos/invitaciones/' . $resultado."/". $nombre_documento;


	if($nombre_documento != ''){

		// Verifica si el archivo existe
		if (file_exists($filepath)) {
			// Intenta eliminar el archivo
			if (unlink($filepath)) {
				echo "El archivo se ha sido eliminado exitosamente.";
					
				$delete_id = delete_by_id('rel_eventos_evidencias',(int)$_GET['id'],'id_rel_eventos_evidencias');
				 if($delete_id){
					  $session->msg("s","Archivo eliminado");
					  insertAccion($user['id_user'], '"' . $user['username'] . '" eliminó evento, Folio: ' . $a_evento['folio'] . '.', 3);
					  redirect('edit_evento.php?id='.$evidencias['id_evento'],false);
				  } else {
					  $session->msg("d","Eliminación falló");
					  redirect('edit_evento.php?id='.$evidencias['id_evento'],false);
				  }
			} else {
				echo "No se pudo eliminar el archivo .";
					  redirect('edit_evento.php?id='.$evidencias['id_evento'],false);
			}
		} else {
			echo "El archivo no existe.";
					  redirect('edit_evento.php?id='.$evidencias['id_evento'],false);
		}
	}
} else {
    echo "No se especificó ningún archivo.";
            redirect($ur_origen ,false);
   
}
