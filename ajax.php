<?php
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index.php', false);
}
?>

<?php
// Auto sugerencia
$html = '';
if (isset($_POST['product_name']) && strlen($_POST['product_name'])) {
  $products = find_product_by_title($_POST['product_name']);
  if ($products) {
    foreach ($products as $product) :
      $concat = $product['nombre_componente']."(".$product['marca']."-".$product['modelo'].")"; 
      $html .= "<li class=\"list-group-item\">";
      $html .= $concat;
      $html .= "</li>";
    endforeach;
  } else {

    $html .= '<li onClick=\"fill(\'' . addslashes() . '\')\" class=\"list-group-item\">';
    $html .= 'No encontrado';
    $html .= "</li>";
  }
  
  echo json_encode($html);
}
?>
 <?php
  // find all product
  if (isset($_POST['p_name']) && strlen($_POST['p_name'])) {
    
    //$product_title viene concatenado nombre de componente y marca
    $product_title = remove_junk($db->escape($_POST['p_name']));
    
    /*Quita la marca del nombre de componente.
      Devuelve una subcadena de $product_title desde la posicion 0
      hasta la posicion donde se encuentra el (
    */
    $sin_marca = substr($product_title, 0, strpos( $product_title, '('));
    
    //Extrae tamaño de la cadena de nombre del componente
    $tamano = strlen($sin_marca);
    
    //Contiene la cadena a partir de la marca
    $resto = substr($product_title, $tamano + 1);

    //Extrae sólo la marca del componente, pero se queda con un -
    $marca = substr($resto, 0, strpos( $resto, '-'));

    //Extrae tamaño de la cadena de marca del componente
    $tamano2=strlen($marca);

    //Se suman para saber el tamaño desde inicio de cadena hasta despues de la marca
    $tamano_title_marca = $tamano + $tamano2;
    
    //Extrae sólo el modelo del componente, pero se queda con un )
    $modelo = substr($product_title, $tamano_title_marca + 1, strpos( $product_title, ')'));
    
    //Deja unicamente la marca del componente
    //$marca_fin = substr($marca,0,-1);

    //Deja unicamente la modelo del componente
    $modelo_fin = substr($modelo,1,-1);
    
    $trabajadores = find_all_trabajadores();
    if ($results = find_all_product_info_by_title_marca($sin_marca, $marca, $modelo_fin)) {
      foreach ($results as $result) {

        $html .= "<tr>";

        $html .= "<td id=\"s_name\">" . $result['nombre_componente'] . "</td>";

        $html .= "<input type=\"hidden\" name=\"s_id\" value=\"{$result['id']}\">";
        
        //$html .= "<td id=\"s_marca\">" . $result['marca'] . "</td>";

        $html .= "<td id=\"marca\">";
        $html .= "<input type=\"text\" class=\"form-control\" readonly=\"readonly\" name=\"marca\" value=\"{$result['marca']} {$result['modelo']}\">";
        $html .= "</td>";

        
        // $html .= "<td id=\"s_serie\">" . $result['serie'] . "</td>";
        $html .= "<td id=\"serie\">";
        $html .= "<input type=\"text\" class=\"form-control\" readonly=\"readonly\" name=\"serie\" value=\"{$result['serie']}\">";
        $html .= "</td>";
        
        $html .= "<td id=\"cantidad\">";
        $html .= "<input type=\"number\" min=\"0\" max=\"{$result['cantidad']}\" class=\"form-control\" name=\"cantidad\">";
        $html .= "</td>";
        
        $html .= "<td id=\"descripcion\">";
        $html .= "<textarea type=\"text\" class=\"form-control\" name=\"descripcion\"></textarea>";
        $html .= "</td>";
        //DETALLE DE USUARIO
        $html .= "<td id=\"detalle\">";
        $html .= "<select class=\"form-control\" name=\"detalle\">";
        foreach ($trabajadores as $trabajador) {
          $html .= "<option value=\"{$trabajador['detalleID']}\">{$trabajador['nombre']} {$trabajador['apellidos']}</option>";
        }
        $html .= "</select>";
        $html .= "</td>";
        //$html .= "<td id=\"s_desc\">".$result['descripcion']."</td>";
        //$html .= "<td id=\"s_detalle\">".$result['id_detalle_usuario']."</td>";
        $html  .= "<td>";
        $html  .= "<input id=\"fecha\" type=\"date\" class=\"form-control datepicker\" name=\"fecha\" data-date-format=\"yyyy-mm-dd\">";
        $html  .= "</td>";
        
        $html  .= "<td>";
        $html  .= "<button type=\"submit\" name=\"add_asignacion\" class=\"btn btn-primary\">Agregar</button>";
        $html  .= "</td>";
        $html  .= "</tr>";
      }
    } else {
      $html = '<tr><td>El producto no se encuentra registrado en la base de datos '.$modelo_fin.'</td></tr>';
    }

    echo json_encode($html);
  }
  ?>
