<?php
require_once('includes/load.php');

//page_require_level(1);
$user = current_user();

$mnj = "Tipo de Difusión ";
$accion = "";
$IDaccion = 0;
if ((int) $_GET['a'] == 0) {
    ////activa comunidad	
    $action_id = activate_by_id('cat_tipo_difusion', (int)$_GET['id'], 'estatus', 'id_cat_tipo_dif');
    $mnj .= " activada correctamente.";
    $accion .= " activo ";
    $IDaccion = 3;
} else {
    $action_id = inactivate_by_id('cat_tipo_difusion', (int)$_GET['id'], 'estatus', 'id_cat_tipo_dif');
    $mnj .= " desactivada correctamente.";
    $accion .= " desactivo ";
    $IDaccion = 4;
}

if ($action_id) {
    $session->msg("s", $mnj);
    insertAccion($user['id_user'], '"' . $user['username'] . '" ' . $accion . ' el Tipo de Difusión con ID:' . ((int)$_GET['id']) . '.', $IDaccion);
    redirect('cat_tipo_difusion.php');
} else {
    $session->msg("d", "Falló la accion sobre el Tipo de Difusión");
    redirect('cat_tipo_difusion.php');
}
