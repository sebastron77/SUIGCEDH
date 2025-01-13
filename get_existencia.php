<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once('includes/load.php');
// Archivo: get_existencia.php
if (isset($_POST['id_categoria_inv'])) {
    $id_categoria_inv = $db->escape($_POST['id_categoria_inv']);
    $producto = find_by_id('stock_inv', $id_categoria_inv, 'id_categoria_inv');
    if ($producto) {
        echo json_encode(['existencia' => $producto['existencia']]);
    } else {
        echo json_encode(['existencia' => 0]);
    }
}
?>
