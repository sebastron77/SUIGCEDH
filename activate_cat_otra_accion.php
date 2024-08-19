<?php
require_once('includes/load.php');

//page_require_level(1);
$user = current_user();

$mnj = "Otra acción ";
$accion = "";
$IDaccion = 0;
if ((int) $_GET['a'] == 0) {
    $action_id = activate_by_id('cat_otras_acciones', (int)$_GET['id'], 'estatus', 'id_cat_otra_accion');
    $mnj .= " activada correctamente.";
    $accion .= " activo ";
    $IDaccion = 3;
} else {
    $action_id = inactivate_by_id('cat_otras_acciones', (int)$_GET['id'], 'estatus', 'id_cat_otra_accion');
    $mnj .= " desactivada correctamente.";
    $accion .= " desactivo ";
    $IDaccion = 4;
}

if ($action_id) {
    $session->msg("s", $mnj);
    insertAccion($user['id_user'], '"' . $user['username'] . '" ' . $accion . ' la Otra Acción con ID:' . ((int)$_GET['id']) . '.', $IDaccion);
    redirect('cat_otra_accion.php');
} else {
    $session->msg("d", "Falló la accion sobre la Otra Acción");
    redirect('cat_otra_accion.php');
}
