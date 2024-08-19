<?php
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "servidor_libro";

$conn = new mysqli($hostname, $username, $password, $dbname);
if ($conn->connect_error) {
    die("ERROR: No se puede conectar al servidor: " . $mysqli_connect->connect_error);
} else {
    echo ' ';
}
// mysqli_query($conn,"SET SESSION collation_connection ='utf8_unicode_ci'");
$tipo       = $_FILES['dataCliente']['type'];
$tamanio    = $_FILES['dataCliente']['size'];
$archivotmp = $_FILES['dataCliente']['tmp_name'];
$lineas     = file($archivotmp);

$i = 0;

foreach ($lineas as $linea) {
    $cantidad_registros = count($lineas);
    $cantidad_regist_agregados =  ($cantidad_registros - 1);

    if ($i != 0) {

        $datos = explode(";", $linea);

        $nombre  = !empty($datos[0]) ? ($datos[0]) : '';
        $apaterno  = !empty($datos[1]) ? ($datos[1]) : '';
        $amaterno = !empty($datos[2]) ? ($datos[2]) : '';
        $edad = !empty($datos[3]) ? ($datos[3]) : '';

        if (!empty($celular)) {
            $check_duplicidad = ("SELECT amaterno FROM prueba_excel WHERE amaterno='" . ($amaterno) . "' ");
            $ca_dupli = mysqli_query($conn, $check_duplicidad);
            $cant_duplicidad = mysqli_num_rows($ca_dupli);
        }

        //No existe Registros Duplicados
        if ($cant_duplicidad == 0) {

            $insertarData = "INSERT INTO prueba_excel(nombre, apaterno, amaterno, edad) VALUES('$nombre','$apaterno','$amaterno', '$edad')";
            mysqli_query($conn, $insertarData);
        }
        /**Caso Contrario actualizo el o los Registros ya existentes*/
        else {
            $updateData = ("UPDATE prueba_excel SET nombre='" . $nombre . "', apaterno='" . $apaterno . "', amaterno='" . $amaterno . "', edad='" . $edad . "' WHERE amaterno='" . $amaterno . "'");
            $result_update = mysqli_query($conn, $updateData);
        }
    }

    $i++;
}
?>