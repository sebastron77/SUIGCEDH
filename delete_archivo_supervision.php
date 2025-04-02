<?php
require_once('includes/load.php');
if (isset($_GET['id'])) {
	$user = current_user();
	$nivel_user = $user['user_level'];
	$id = $_GET['id'];
	$evidencias = find_by_id('rel_supervision_evidencias', (int)$_GET['id'], 'id_rel_supervision_evidencias');
	$nombre_documento = $evidencias['nombre_documento'];
	$a_superv = find_by_id('supervision_mecanismos', $evidencias['id_supervision_mecanismos'], 'id_supervision_mecanismos');
	$resultado = str_replace("/", "-", $a_superv['folio']);
	$filepath = 'uploads/supervisiones_mec/evidencia/' . $resultado . "/" . $nombre_documento;


	if ($nombre_documento != '') {

		// Verifica si el archivo existe
		if (file_exists($filepath)) {
			// Intenta eliminar el archivo
			if (unlink($filepath)) {
				echo "El archivo se ha sido eliminado exitosamente.";

				$delete_id = delete_by_id('rel_supervision_evidencias', (int)$_GET['id'], 'id_rel_supervision_evidencias');
				if ($delete_id) {
					$session->msg("s", "Archivo eliminado");
					insertAccion($user['id_user'], '"' . $user['username'] . '" eliminó evento, Folio: ' . $a_superv['folio'] . '.', 3);
					redirect('edit_supervision_mecanismos.php?id=' . $evidencias['id_supervision_mecanismos'], false);
				} else {
					$session->msg("d", "Eliminación falló");
					redirect('edit_supervision_mecanismos.php?id=' . $evidencias['id_supervision_mecanismos'], false);
				}
			} else {
				echo "No se pudo eliminar el archivo .";
				redirect('edit_supervision_mecanismos.php?id=' . $evidencias['id_supervision_mecanismos'], false);
			}
		} else {
			echo "El archivo no existe.";
			redirect('edit_supervision_mecanismos.php?id=' . $evidencias['id_supervision_mecanismos'], false);
		}
	}
} else {
	echo "No se especificó ningún archivo.";
	redirect($ur_origen, false);
}
