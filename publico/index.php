<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING); ?>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Acuerdos y Resoluciones</title>
	<link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <!--  -->
	<link rel="stylesheet" href="libs/css/main.css" /> 
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <link href='../style_tabs.css' rel='stylesheet' type='text/css' />
    <!-- CSS only -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="../libs/js/functions.js"></script>
    <script language="javascript" src="../js/jquery-3.1.1.min.js"></script>
    <script language="javascript" type="text/javascript">
        $(document).ready(function() {
            //When page loads...
            $(".tab_content").hide(); //Hide all content
            $("ul.tabs li:first").addClass("active").show(); //Activate first tab
            $(".tab_content:first").show(); //Show first tab content

            //On Click Event
            $("ul.tabs li").click(function() {
                $("ul.tabs li").removeClass("active"); //Remove any "active" class
                $(this).addClass("active"); //Add "active" class to selected tab
                $(".tab_content").hide(); //Hide all tab content
                var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to
                //identify the active tab + content
                $(activeTab).fadeIn(); //Fade in the active ID content
                return false;
            });
        });
		
		
		

        function cambiar() {
            $("#resultado1").html("");

        }

        function envio() {
			 if($("#area_padre").val() > 0){
				$.post("acuerdosrecientes.php", {
					id: $("#area_padre").val()
				}, function(a) {
					$("#resultado1").html(a);
				});
				document.getElementById("mnj_alert").style.display = "none"; 
				document.getElementById("resultado1").style.display = "";
			 }else{
				document.getElementById("mnj_alert").style.display = ""; 
			 }
			 
			 
        }

        function cambiar2() {
            $("#resultado3").html("");           
        }

        function envio2() {
			
            $.post("acuerdosfechas.php", {
                id: $("#area_padre3").val(),
                fecha: $("#fecha_alta").val(),
            }, function(a) {
                $("#resultado2").html(a);
            });
            document.getElementById("resultado2").style.display = "";
        }

        function cambiar3() {
            $("#resultado2").html("");
        }

        function envio3() {
            $.post("acuerdosexpediente.php", {
                no_exp: $("#no_expediente").val()
            }, function(a) {
                $("#resultado3").html(a);
            });
            document.getElementById("resultado3").style.display = "";
        }


    </script>
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
        echo ' ';
    }
    ?>
	<div class="container">
        <img src="../medios/ubi.fw.png" />                   
			Fernando Montes de Oca #108, Col Chapultepec Norte, CP 58260 Morelia Michoacán       
		<img src="../medios/tel.fw.png" />
			<a href="tel:(443)1133500" style="color:#fff;">(443) 11 33 500</a> 
		<img src="../medios/cel.fw.png" />
			<a href="tel:(800) 640 31 88 " style="color:#fff;">
	<!--<img src="Logo-CEDH.png" style="max-width: 250px;max-height: 65px;"/>&nbsp;&nbsp;&nbsp;&nbsp; -->(800) 640 31 88 </a> 
		<img src="../medios/mail.fw.png" />
			<a href="mailto:contacto@cedhmichoacan.org" style="color:#fff;">contacto@cedhmichoacan.org</a>  
	</div>
    <form name="muestra_acuerdos" id="muestra_acuerdos" method="POST">
       
		    <div class="title1">			
                    LISTA DE ACUERDOS Y RESOLUCIONES
            </div>
		</br>
        <ul class="tabs">
            <li><a href="#tab1">Recientes</a></li>
            <li><a href="#tab2">Por fecha</a></li>
            <li><a href="#tab3">Por expediente</a></li>
        </ul>

        <div class="tab_container">
		
            <div class="tab_content" id="tab1">
                <!--div>
                    <h1>Recientes</h1>
                </div-->
				<br>
                <label for="area_padre" class="text_elem">Selecciona Área</label>
                <select class="control_dates" id="area_padre" name="area_padre" style="width: 30%;" onchange="cambiar();">
                    <option value='0'>Selecciona área</option>
                    <?php                    
					$result = $conn->query("SELECT id_area, area_padre, nombre_area FROM area WHERE visible=1 AND RQ=1 ORDER BY jerarquia ");
                    while ($row = $result->fetch_assoc()) {
                    ?>
                        <option value="<?php echo $row["id_area"]; ?>"><?php echo ucwords($row["nombre_area"]); ?></option>
                    <?php
                    }
                    ?>
                </select>
				
				<div class="ojo" style="display: none;" id="mnj_alert"><b>Por favor, indique el área que desea consultar.</b></div>
                <br>

                <input type="button" id="enviar" name="acuerdos_res_publico1" value="Mostrar" class="btn_muestra" onclick="envio()" /><br><br>


                <div id="resultado1" style="display: none;">

                </div>

                
            </div>
            <div class="tab_content" id="tab2">
                <!--div>
                    <h1>Por Fecha</h1>
                </div -->
                <label for="area_padre3" class="text_elem">Selecciona Área</label>
                <select class="control_dates" id="area_padre3" name="area_padre3" style="width: 30%;" onchange="cambiar3();">
                    <option value='0'>Selecciona área</option>
                    <?php
                    $result = $conn->query("SELECT id_area, area_padre, nombre_area FROM area WHERE visible=1 AND RQ=1 ORDER BY jerarquia");
                    while ($row = $result->fetch_assoc()) {
                    ?>
                        <option value="<?php echo $row["id_area"]; ?>"><?php echo ucwords($row["nombre_area"]); ?></option>
                    <?php
                    }
                    ?>
                </select>
                <br>
				
                <label for="fecha_alta" class="text_elem" style="width: 30%;">Selecciona Fecha: </label>
				
                <input class="control_dates" type="date" id="fecha_alta" name="fecha_alta" style="width: 30%;" value="<?php echo date('Y-m-d');?>"><br>

                <input type="button" id="enviar2" name="acuerdos_res_publico2" value="Mostrar" class="btn_muestra" onclick="envio2()" /><br><br>


                <div id="resultado2" style="display: none;">

                </div>
               
            </div>

            <div class="tab_content" id="tab3">
                <!--div>
                    <h1>Expedientes</h1>
                </div -->
                <br>
				<div id="no_expedientes" >
                    <label class="text_elem">No. Expediente: </label>
					<input id="no_expediente" type="name" class="control_dates" name="no_expediente" style="width: 30%;">
                </div><br>
                <button type="button" name="acuerdos_res_publico2" class="btn_muestra" onclick="envio3()">Mostrar</button><br><br>

                <div id="resultado3" style="display: none;">

                </div>
                
            </div>
        </div>
		

    </form>
	&nbsp;&nbsp;

	<div class="footer">
		<div style="justify-content: left;">
			© Comisión Estatal de Derechos Humanos 2022, Desarrollada por CEDH. Coordinación de Sistemas de Informática
		</div>
		&nbsp;&nbsp;
		<div style="justify-content: right;margin:0px auto;">
			<a href="https://www.cedhmichoacan.org/">Inicio</a>
		</div>
	</div>
</body>
<?php
$conn->close();
?>

</html>