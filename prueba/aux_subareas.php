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
	
	# Comprobar si existe registro
		
	 $result = $conn->query("select count(id) as id from area where area_padre=".$_POST["id"]);
 	if($result){
		$row = $result->fetch_assoc();
		if($row["id"]>0){
			?>  
    <response> 
        <found>yes</found>
    </response>
    <?php
		}else{?>  
    <response> 
        <found>nop</found>
    </response>
    <?php
		}		
	
	}
	
  }
 $result->close();

  $conn->close();
?>
                   