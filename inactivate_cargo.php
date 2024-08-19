<?php
require_once('includes/load.php');

page_require_level(1);
?>
<?php

$inactivate_id = inactivate_by_id('cargos', (int) $_GET['id'], 'estatus_cargo', 'id_cargos');

if ($inactivate_id) {
    $session->msg("s", "Cargo inactivado");
    redirect('cargos.php');
} else {
    $session->msg("d", "Inactivación falló");
    redirect('cargos.php');
}
?>