<?php
require_once('load.php');

/*--------------------------------------------------------------*/
/* Funcion para encontrar en una tabla toda la informacion
/*--------------------------------------------------------------*/
function find_table($table)
{
  global $db;
  if (tableExists($table)) {
    return find_catalogo("SELECT * FROM " . $db->escape($table));
  }
}



function find_catalogo($table)
{
	global $db;    
    $sql_query = "SELECT * FROM " . $table;
    $arrayTemp= array();
    if($result = $db->query($sql_query)){
        while ($row = $db->fetch_object($result)) {
            //guarda los datos en un arreglo
            $arrayTemp[] = $row;   
        }
        return $arrayTemp;
    
  }
  
  
  
}
