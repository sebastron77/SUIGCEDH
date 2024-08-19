<?php
require_once('includes/load.php');

page_require_level(1);
?>
<?php

$inactivate_id = activate_by_id('cargos', (int) $_GET['id'], 'estatus_cargo', 'id_cargos');
$inactivate_user = activate_cargo_user('users', (int) $_GET['id'], 'status');
$inactivate_trabajador = activate_cargo_trabajador('detalles_usuario', (int) $_GET['id'], 'estatus_detalle');

if ($inactivate_id) {
    $session->msg("s", "Cargo activado");
    redirect('cargos.php');
} else {
    $session->msg("d", "Activación falló");
    redirect('cargos.php');
}
?>