<?php
//Conexion con mysql.
$dbhost="localhost";
$dbuser="suigcedh";
$dbpass="9DvkVuZ915H!"; //el password se encuentra en blanco, ingresar su password de su cuenta de mysql.
$dbname="suigcedh"; //nombre de la base de datos
$tabla="";  // nombre de la tabla
$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die ('Error connecting to mysql: ' . mysqli_error($con));
?>