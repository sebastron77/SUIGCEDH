<?php
require_once('includes/load.php');

// $id_rel_periodo_vac = $db->escape($_GET['idrpv']);
$id_rel_licencia_personal = $db->escape($_GET['idrl']);
$trabajador = $db->escape($_GET['idT']);
$user = current_user();

$sql = "DELETE FROM rel_licencias_personal WHERE id_rel_licencia_personal = " . $id_rel_licencia_personal;
$result = $db->query($sql);

if ($result && $db->affected_rows() === 1) {
    insertAccion($user['id_user'], '"' . $user['username'] . '" eliminó rel_licencia_personal id: ' . $id_rel_licencia_personal . ', del trabajador: ' . $trabajador, 1);
    $session->msg('s', "Licencia ELIMINADA con éxito. ");
    redirect('licencias.php?id=' . $trabajador, false);
} else {
    echo "ERROR: No se pudo eliminar el registro.";
}
