<?php
require_once('includes/load.php');

if ($nivel == 1) {
    page_require_level_exacto(1);
}
if ($nivel == 5) {
    page_require_level_exacto(5);
}
?>
<?php
$delete_id = delete_by_id('acuerdos_recomendaciones', (int)$_GET['id'], 'id_acuerdo_rec');

if ($delete_id) {
    $session->msg("s", "Acuerdo Eliminado");
    redirect('add_acuerdo_rec.php?id=' . (int)$_GET['id']);
} else {
    $session->msg("d", "Eliminación falló");
    redirect('add_acuerdo_rec.php?id=' . (int)$_GET['id']);
}
?>

