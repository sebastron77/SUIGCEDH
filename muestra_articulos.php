<?php
require_once('includes/load.php');

$id_cat_inv = $_POST['id_cat_inv'];

$queryM = find_all_subcategorias_inv($id_cat_inv);

$html = "<option value='0'>Seleccionar Artículo</option>";

foreach ($queryM as $rowM) {
    $html .= "<option value='" . $rowM['id_cat_subcategorias_inv'] . "'>" . $rowM['descripcion'] . "</option>";
}

echo $html;
