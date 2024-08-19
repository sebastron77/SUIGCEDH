  <?php
	//Sintaxis de conexión de la base de datos de muestra para PHP y MySQL.
	
	//Conectar a la base de datos
	
	$hostname="localhost";
	$username="root";
	$password="";
	$dbname="servidor_libro";
	
	$conn = new mysqli($hostname,$username, $password,$dbname) ;
	if ($conn->connect_error) {
    die("ERROR: No se puede conectar al servidor: " . $mysqli_connect->connect_error);
  } else{
	$currentYear = date('y');
	$currentMes = date('m');
	# Comprobar si existe registro
	$ls_query = "SELECT fecha_alta  FROM acuerdos_resoluciones a LEFT JOIN expedientes b USING(id)  LEFT JOIN area c USING(id)  WHERE id_cat_areas > 0  	AND id =  " .$_POST["id"]. "  AND fecha_alta >= '" .$currentYear . "-" .$currentMes. "-01'   AND fecha_alta <=  NOW() GROUP by  fecha_alta   ORDER by fecha_alta;";
	
	 $result = $conn->query("SELECT fecha_alta  FROM acuerdos a LEFT JOIN expedientes b USING(id)  LEFT JOIN area c USING(id)  WHERE id > 0  	AND id =  " .$_POST["id"]. "  AND fecha_alta >= '" .$currentYear . "-" .$currentMes. "-01'   AND fecha_alta <=  NOW() GROUP by  fecha_alta   ORDER by fecha_alta;");
	$row = $result->fetch_assoc();
	if($row){
		?>
		<ul>
		<?php
		do{
			?>
		<li ><?php echo $row["fecha_alta"]; ?> </li >
		<?php
		}while ($row = $result->fetch_assoc());
		?>
		</ul>
	<?php
	}
		  
		
	
	
  }
 $result->close();

  $conn->close();
?>