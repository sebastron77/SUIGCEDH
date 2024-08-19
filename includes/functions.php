<?php
$errors = array();

/*--------------------------------------------------------------*/
/* Funcion para remover caracteres especiales
/* en una cadena para usarlo en una sentencia SQL
/*--------------------------------------------------------------*/
function real_escape($str)
{
  global $con;
  $escape = mysqli_real_escape_string($con, $str);
  return $escape;
}
/*--------------------------------------------------------------*/
/* Funcion para convertir a mayusculas */
/*--------------------------------------------------------------*/
function upper_case($str)
{
  $str = strtoupper($str);
  return $str;
}
/*--------------------------------------------------------------*/
/* Funcion para convertir a minusculas */
/*--------------------------------------------------------------*/
function lower_case($str)
{
  $str = strtolower($str);
  return $str;
}
/*--------------------------------------------------------------*/
/* Funcion para remover caracteres HTML
/*--------------------------------------------------------------*/
function remove_junk($str)
{
  $str = nl2br($str);
  $str = htmlspecialchars(strip_tags($str, ENT_QUOTES));
  return $str;
}
/*--------------------------------------------------------------*/
/* Funcion para convertir a mayuscula el primer caracter
/*--------------------------------------------------------------*/
function first_character($str)
{
  $val = str_replace('-', " ", $str);
  $val = ucfirst($val);
  return $val;
}
/*--------------------------------------------------------------*/
/* Funcion para checar que los campos de entrada no esten vacios
/*--------------------------------------------------------------*/
function validate_fields($var)
{
  global $errors;
  foreach ($var as $field) {
    $val = remove_junk($_POST[$field]);
    if (isset($val) && $val == '') {
      $errors = $field . " No puede estar en blanco.";
      return $errors;
    }
  }
}
/*--------------------------------------------------------------*/
/* Funcion para mostrar mensaje de sesion
Ex echo displayt_msg($message);
/*--------------------------------------------------------------*/
function display_msg($msg = '') //antes lo tenia con $msg = ''
{
  $output = array();
  if (!empty($msg)) {
    foreach ($msg as $key => $value) {
      $output = "<div class=\"alert alert-{$key}\">";
      $output .= "<a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a>";
      $output .= remove_junk(first_character($value));
      $output .= "</div>";
    }
    return $output;
  } else {
    return "";
  }
}
/*--------------------------------------------------------------*/
/* Funcion para redireccionar
/*--------------------------------------------------------------*/
function redirect($url, $permanent = false)
{
  if (headers_sent() === false) {
    header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
  }

  exit();
}
/*--------------------------------------------------------------*/
/* Encontrar el total en costo de la asignacion
/*--------------------------------------------------------------*/
function total_price($totals)
{
  $sum = 0;
  $sub = 0;
  foreach ($totals as $total) {
    $sum += $total['total_asignacion_price'];
    $sub += $total['total_buying_price'];
    $profit = $sum - $sub;
  }
  return array($sum, $profit);
}
/*--------------------------------------------------------------*/
/* Funcion para fecha y hora
/*--------------------------------------------------------------*/
function read_date($str)
{
  if ($str)
    return date('d/m/Y g:i:s a', strtotime($str));
  else
    return null;
}

/*--------------------------------------------------------------*/
/* Funcion para fecha y hora
/*--------------------------------------------------------------*/
function read_date_fecha($str)
{
  if ($str)
    return date('d/m/Y', strtotime($str));
  else
    return null;
}

/*--------------------------------------------------------------*/
/* Funcion para hacer fecha y hora legibles
/*--------------------------------------------------------------*/
function make_date()
{
  $mifecha = date('Y-m-d H:i:s');
  $NuevaFecha = strtotime('-7 hour', strtotime($mifecha));
  $NuevaFecha = date('Y-m-d H:i:s', $NuevaFecha);
  return $NuevaFecha;
}
/*--------------------------------------------------------------*/
/* Funcion para hacer fecha y hora legibles, pero sin segundos
/*--------------------------------------------------------------*/
function make_date_no_seg()
{
  date_default_timezone_set('America/Mexico_City');
  $mifecha = date('Y-m-d H:i');
  // $NuevaFecha = strtotime('-7 hour', strtotime($mifecha));
  // $NuevaFecha = date ( 'Y-m-d H:i' , $mifecha); 
  return $mifecha;
}
/*--------------------------------------------------------------*/
/* Funcion para hacer fecha sin hora
/*--------------------------------------------------------------*/
function make_date_no_time()
{
  $mifecha = date('Y-m-d');
  return $mifecha;
}
/*--------------------------------------------------------------*/
/* Funcion para enumerar cada elemento que se muestra en las tablas
/*--------------------------------------------------------------*/
function count_id()
{
  static $count = 1;
  return $count++;
}
/*--------------------------------------------------------------*/
/* Funcion para crear una cadena aleatoria
/*--------------------------------------------------------------*/
function randString($length = 5)
{
  $str = '';
  $cha = "0123456789abcdefghijklmnopqrstuvwxyz";

  for ($x = 0; $x < $length; $x++)
    $str .= $cha[mt_rand(0, strlen($cha))];
  return $str;
}

/*--------------------------------------------------------------*/
/* Funcion para crear obtener diferencia de fechas sin dias habiles
/*--------------------------------------------------------------*/

function sumasdiasemana($fecha,$dias)
{
	$datestart= strtotime($fecha);
	$datesuma = 15 * 86400;
	$diasemana = date('N',$datestart);
	$totaldias = $diasemana+$dias;
	$findesemana = intval( $totaldias/5) *2 ; 
	$diasabado = $totaldias % 5 ; 
	if ($diasabado==6) $findesemana++;
	if ($diasabado==0) $findesemana=$findesemana-2;
 
	$total = (($dias+$findesemana) * 86400)+$datestart ; 
	return $fechafinal = date('Y-m-d', $total);
}
  
