<?php
require_once('load.php');

global $db;
$ip = empty($_SERVER["REMOTE_ADDR"]) ? "Desconocida" : $_SERVER["REMOTE_ADDR"];
$sql="INSERT INTO statistics(ip_acceso,fecha_acceso,navegador,modulo) VALUES('{$ip}',NOW(),'{$_SERVER['HTTP_USER_AGENT']}','Presenta tu Queja');";
//echo $sql;
if ($db->query($sql) ) {
          
        } else {
            
        }
/*echo "El nombre del servidor es: {$_SERVER['SERVER_NAME']}<hr>";  
echo "Te has conectado usando el puerto: {$_SERVER['REMOTE_PORT']}<hr>"; 
echo "El agente de usuario de tu navegador es: {$_SERVER['HTTP_USER_AGENT']}";*/
?>