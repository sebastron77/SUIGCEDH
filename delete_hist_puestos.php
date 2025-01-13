<?php
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];

$id_rel_hist_exp_int = $db->escape($_GET['iddel']);
$id_trabajador = $db->escape($_GET['id']);
$puestos = find_by_id('rel_hist_exp_int', $id_rel_hist_exp_int, 'id_rel_hist_exp_int');

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
?>
<?php
$delete_id = delete_by_id('rel_hist_exp_int', (int)$_GET['iddel'], 'id_rel_hist_exp_int');

if ($delete_id) {
    insertAccion($user['id_user'], '"' . $user['username'] . '" eliminó de rel_hist_exp_int con id: ' . $id_rel_hist_exp_int . ' con id_cat_puesto: ' . $puestos['id_cat_puestos'] .', del trabajador: ' . $id_trabajador, 1);
    $session->msg("s", "Puesto del histórico eliminado con éxito.");
    redirect('hist_puestos.php?id=' . (int)$_GET['id']);
} else {
    $session->msg("d", "La eliminación del puesto del histórico falló.");
    redirect('hist_puestos.php?id=' . (int)$_GET['id']);
}
?>