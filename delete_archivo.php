<?php
require_once('includes/load.php');
if (isset($_GET['file']) && isset($_GET['id'])  && isset($_GET['col'])) {

    $file = $_GET['file'];
    $id = $_GET['id'];

    $filepath = 'uploads/personal/expediente/' . $id . '/' . $file;
    echo "Ruta del archivo: " . $filepath . "<br>";
    $delete_id = update_colum('detalles_usuario', 'id_det_usuario', (int)$id, $_GET['col']);

    // Verifica si el archivo existe
    if (file_exists($filepath)) {
        // Intenta eliminar el archivo
        if (unlink($filepath)) {
            echo "El archivo $file ha sido eliminado exitosamente.";
            redirect('exp_general.php?id=' . (int)$id, false);
        } else {
            echo "No se pudo eliminar el archivo $file.";
            redirect('exp_general.php?id=' . (int)$id, false);
        }
    } else {
        echo "El archivo $file no existe.";
        redirect('exp_general.php?id=' . (int)$id, false);
    }
} else {
    echo "No se especificó ningún archivo.";
    redirect('exp_general.php?id=' . (int)$id, false);
}
