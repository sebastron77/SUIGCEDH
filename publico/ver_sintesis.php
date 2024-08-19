<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING); ?>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sintesis de Acuerdos y Resoluciones</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
	<link rel="stylesheet" href="../libs/css/main.css" /> 
    <link href='../style_tabs.css' rel='stylesheet' type='text/css' />
    
</head>
<style>
   
</style>

<body>
    <?php

 $hostname = "localhost";
    $username = "suigcedh";
	$password = "9DvkVuZ915H!";
    $dbname = "suigcedh";

    $conn = new mysqli($hostname, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("ERROR: No se puede conectar al servidor: " . $mysqli_connect->connect_error);
    } else {
		$id= $_GET["id"];
     ?>
	
    <form name="muestra_acuerdos" id="muestra_acuerdos" method="POST">
       
	   <div class="sin_valor">
		    <div class="title1">			
                    SINTESIS DEL ACUERDO/RESOLUCIÓN
            </div>
		</br>     
<?php
			$query="SELECT 
a.`id_rel_queja_acuerdos`,
	b.`folio_queja`,
	a.`sintesis_documento`,
	a.`acuerdo_adjunto_publico`,
	a.`tipo_acuerdo`,
    c.`nombre_area`
FROM rel_queja_acuerdos  a
LEFT JOIN `quejas_dates` b USING(id_queja_date)
LEFT JOIN `area` c  ON c.`id_area` =b.id_area_asignada
WHERE a.id_rel_queja_acuerdos = '".$id."';";
					
			$result2 = $conn->query($query);
					
			while ($row2 = $result2->fetch_assoc()){
				$Folio_carpeta = str_replace("/", "-", $row2['folio_queja']);
				
				?>
				<div class="sintext">
				<b>Expediente:</b> <?php echo $row2['folio_queja'];?> </br>
				<b>Área:</b> <?php echo $row2['nombre_area'];?> </br>
				<b>Tipo Acuerdo/Resolución:</b> <?php echo $row2['tipo_acuerdo'];?> </br></br>
				<?php echo $row2['sintesis_documento'];?>
				</br></br>
				<?php if (!$row2['acuerdo_adjunto_publico'] == "") { ?>
				<b>Versión Pública:</b> 
                                            &nbsp;&nbsp;&nbsp;
                                            <a target="_blank" href="../uploads/quejas/<?php echo $Folio_carpeta . '/Acuerdos/'. $row2['acuerdo_adjunto_publico']; ?>" title="Ver Versión Publica del Acuerdo">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="red" class="bi bi-file-earmark-medical" viewBox="0 0 16 16">
                                                    <path d="M7.5 5.5a.5.5 0 0 0-1 0v.634l-.549-.317a.5.5 0 1 0-.5.866L6 7l-.549.317a.5.5 0 1 0 .5.866l.549-.317V8.5a.5.5 0 1 0 1 0v-.634l.549.317a.5.5 0 1 0 .5-.866L8 7l.549-.317a.5.5 0 1 0-.5-.866l-.549.317V5.5zm-2 4.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z" />
                                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                                </svg>
                                            </a>
                                        <?php } ?>
									</br>
				</div>
				
				<div class="form-group clearfix" style="margin: 0 auto; text-align: center;">            
					<button type="button"  class="btn btn-md btn-success" onclick="javascript:window.close();">Cerrar</button>&nbsp;&nbsp;					
				</div>
				<?php
			}		
		  ?>
        
		</div>

    </form>

	
</body>
<?php   
    }
    
$conn->close();
?>

</html>