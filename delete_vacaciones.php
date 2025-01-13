<?php
require_once('includes/load.php');

$id_rel_periodo_vac = $db->escape($_GET['idrpv']);
$id_rel_vacaciones = $db->escape($_GET['idrv']);
$trabajador = $db->escape($_GET['idT']);
$user = current_user();

$sql = "DELETE FROM rel_periodos_vac WHERE id_rel_periodo_vac = " . $id_rel_periodo_vac;
// $rows_affected_1 = $db->affected_rows();

$total = find_and_count_vac($id_rel_vacaciones);

if (($total['total'] == NULL) || ($total['total'] == 0) || ($total['total'] == 1))
{
    $sql2 = "DELETE FROM rel_vacaciones WHERE id_rel_vacaciones = " . $id_rel_vacaciones;
    $result2 = $db->query($sql2);
}
$rows_affected_2 = $db->affected_rows();
echo $result2;
$result = $db->query($sql);

if (($result && $db->affected_rows() === 1) || ($result2 && $rows_affected_2 === 1)) {
    insertAccion($user['id_user'], '"' . $user['username'] . '" eliminó el rel_periodos_vac id: ' . $id_rel_periodo_vac . ' y rel_vacaciones con id: ' . $id_rel_vacaciones .', del trabajador: ' . $trabajador, 1);
    $session->msg('s', "Semana del periodo vacacional ELIMINADA con éxito. ");
    redirect('vacaciones.php?id=' . $trabajador, false);
} else {
    echo "ERROR: No se pudo eliminar el registro.";
}
