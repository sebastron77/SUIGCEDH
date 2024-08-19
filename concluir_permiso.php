<?php
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}
if ($nivel_user == 2) {
    page_require_level_exacto(2);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
}
if ($nivel_user > 2 && $nivel_user < 14) :
    redirect('home.php');
endif;
if ($nivel_user > 14) :
    redirect('home.php');
endif;

$inactivate_id = activate_by_id('rel_licencias_personal', (int)$_GET['id'], 'terminado', 'id_rel_licencia_personal');
$idP = (int)$_GET['id'];
$det = (int)$_GET['det'];

if ($inactivate_id) {
    $session->msg("s", "Permiso cumplido con éxito");
    redirect('licencias.php?id=' . $det);
} else {
    $session->msg("d", "Cumplimiento de permiso falló");
    redirect('licencias.php?id=' . $det);
}
?>