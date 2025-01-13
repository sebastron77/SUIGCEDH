<?php
require_once('includes/load.php');

$id_cat_inv = $_POST['id_cat_inv'];

$queryM = find_all_order_by('cat_categorias_inv', 'descripcion', 'padre', $id_cat_inv);

$html = "<option value=''>Seleccionar Artículo</option>";

foreach ($queryM as $rowM) {
    $html .= "<option value='" . $rowM['id_categoria_inv'] . "'>" . $rowM['descripcion'] . "</option>";
}

echo $html;
