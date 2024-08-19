<?php
// Include and initialize ZipArchive class
require_once('includes/load.php');
require_once 'ZipArchiver.class.php';
$zipper = new ZipArchiver;
get_current_user();
$user = current_user();
$detalle = $user['id_user'];
$username = get_current_user();
$id = (int) $_GET['id'];
$e_detalle = find_by_id_queja($id);
$folio_editar = $e_detalle['folio_queja'];
$resultado = str_replace("/", "-", $folio_editar);

//! Ruta que se guardará en el ZIP
$dirPath = 'uploads/quejas/' . $resultado . '/imagenes';

//! Ruta donde se guardará el ZIP
$zipPath = '/Users/' . $username . '/Downloads/' . $resultado . ' - Imagenes.zip';

// Create zip archivec:\Users\Sebastian\Downloads
$zip = $zipper->zipDir($dirPath, $zipPath);

if ($zip) {
    // echo 'Carpeta comprimida creada con éxito.';
    $session->msg('s', "*** Archivo comprimido con éxito. Revisa la carpeta de Descargas en tu computadora. ***");
    insertAccion($user['id_user'], '"' . $user['username'] . '" descargó imágenes de queja, Folio: ' . $folio_editar . '.', 1);
    redirect('quejas.php', false);
} else {
    echo 'Error al crear comprimido.';
}
