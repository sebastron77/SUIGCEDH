<?php
// Utilizaremos conexion PDO PHP
function conexion() {
	//Declaramos el servidor, la BD, el usuario Mysql y Contraseña BD.
    return new PDO('mysql:host=localhost;dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
}

$pdo = conexion();
$keyword = '%'.$_POST['palabra'].'%';
$sql = "SELECT id_paciente, nombre, paterno, materno FROM paciente WHERE nombre LIKE (:keyword) ORDER BY nombre ASC LIMIT 0, 30";
$query = $pdo->prepare($sql);
$query->bindParam(':keyword', $keyword, PDO::PARAM_STR);
$query->execute();
$lista = $query->fetchAll();
foreach ($lista as $milista) {
	// Colocaremos negrita a los textos
	$nombre = str_replace($_POST['palabra'], '<b>'.$_POST['palabra'].'</b>', $milista['nombre']." ".$milista['paterno']." ".$milista['materno']);
	// Aquì, agregaremos opciones
	$id = $milista['id_paciente'];
	//str_replace coloca en el input el valor elegido
	//nombre2 muestra los valores de la lista
    echo '<li style="list-style: none;" value="'.$id.'" onclick="set_item('.$id.',\''.str_replace("'", "\'", $milista['nombre'])." ".$milista['paterno']." ".$milista['materno'].'\')">'.$nombre.'</li>';
}