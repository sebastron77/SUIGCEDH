<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="libs/css/main.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="libs/js/functions.js"></script>
    <script language="javascript" src="js/jquery-3.1.1.min.js"></script>
    <script language="javascript" type="text/javascript">
        function cambiar() {
            //alert("#area_padre"+$("#area_padre").val());

            if ($("#area_padre").val() > 0) {
                $.post("aux_subareas.php", {
                    id: $("#area_padre").val()
                }, function(a) {
                    var x = $(a).find("found").text();

                    if (x == 'yes') {
                        $.post("get_subareas.php", {
                            id: $("#area_padre").val()
                        }, function(a) {
                            $("#subsareas").html(a);
                        });
                        document.getElementById("subsareas").style.display = "";
                    } else {
                        document.getElementById("subsareas").style.display = "none";

                    }
                });
            }
        }

        function muestraRecientes() {

            if ($("#area_padre").val() > 0) {
                var id_area = 0;
                if ($("#area_hija").val()) {
                    id_area = $("#area_hija").val();
                } else {
                    id_area = $("#area_padre").val();
                }
                if (id_area > 0) {

                    $.post("get_acuerdos_recientes.php", {
                        id: $("#area_padre").val()
                    }, function(a) {
                        $("#ac_recientes").html(a);
                    });
                } else {
                    alert("Selecciona una Subarea");
                }
            } else {

                alert("Selecciona una Area");
            }
        }
    </script>
</head>
<style>
    select.form-control {
        font-size: 15px;
        font-family: 'Questrial', sans-serif;
        background: white;
        border-color: gray;
        color: black;
    }

    select.form-control:focus {
        background: white;
        color: black;
    }

    option {
        background: white;
        border-color: #0d0d0f;
        color: black;
    }

    .rectangulo {
        width: 90%;
        height: 100%;
        border: 3px solid #D6D6D6;
        border-radius: 5px;
        background: #F9F9F9;
        margin: 0 auto;
        display: grid;
        margin-top: 2%;
    }

    .boton1 {
        text-decoration: none;
        width: 200px;
        height: 50px;
        border: 2px solid #075E9A;
        border-radius: 5px;
        background: #075E9A;
        color: white;
        text-align: center;
        line-height: 45px;
        margin: 0 auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .boton1:hover {
        text-decoration: none;
        color: white;
        box-shadow: 0 1px 5px rgb(7, 94, 154);
        transition: all 0.1s ease;
    }

    .boton2 {
        text-decoration: none;
        width: 200px;
        height: 50px;
        border: 2px solid #075E9A;
        border-radius: 5px;
        background: #075E9A;
        color: white;
        text-align: center;
        line-height: 45px;
        margin: 0 auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .boton2:hover {
        text-decoration: none;
        color: white;
        box-shadow: 0 1px 5px rgb(7, 94, 154);
        transition: all 0.1s ease;
    }

    .contenedor {
        width: 1080px;
        margin: auto;
        background: #BFBFBF;
        color: black;
        padding: 20px 15px 50px 50px;
        border-radius: 10px;
        box-shadow: 0 5px 5px 0px rgba(0, 0, 0, 0.8);
    }

    .contenedor .titulo {
        font-size: 3.5ex;
        font-weight: bold;
        margin-left: 10px;
        margin-bottom: 10px;
    }

    #pestanas {
        float: top;
        font-size: 3ex;
        font-weight: bold;
    }

    #pestanas ul {
        margin-left: -40px;
    }

    #pestanas li {
        list-style-type: none;
        float: left;
        text-align: center;
        margin: 0px 2px -2px -0px;
        background: #949494;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        border: 2px solid #949494;
        border-bottom: #0340A1;
        padding: 0px 20px 0px 20px;
    }

    #pestanas a:link {
        text-decoration: none;
        color: white;
    }

    #contenidopestanas {
        clear: both;
        background: #FFFFFF;
        padding: 20px 0px 20px 20px;
        border-radius: 5px;
        border-top-left-radius: 0px;
        border: 2px solid #949494;
        width: 1025px;
    }
</style>

<body>
    <?php
    //Sintaxis de conexión de la base de datos de muestra para PHP y MySQL.

    //Conectar a la base de datos

    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "servidor_libro";

    $conn = new mysqli($hostname, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("ERROR: No se puede conectar al servidor: " . $mysqli_connect->connect_error);
    } else {
        echo 'Conectado a la base de datos.<br>';

        /*
	# Comprobar si existe registro
		
	 $result = $conn->query("SELECT * FROM acuerdos");
 
	if($result){
		 echo "Numero de resultados: $result->num_rows";

			while ($row = $result->fetch_assoc()) {
				
				//echo '<br />'.$row["id_expedientes"];
			}
		    
	}
	*/
    }
    ?>

    <nav class="navbar navbar-default" style="background: #056DCD;">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#" style="color: #FFFFEF;">Libro Electrónico de la CEDH</a>
            </div>
            <!-- <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a class="boton4" href="acuerdos_recientes.php" style="color: #FFFFEF">Acuerdos recientes</a></li>
                    <li><a class="boton4" href="acuerdos_fecha.php" style="color: #FFFFEF">Acuerdos por fecha</a></li>
                    <li><a class="boton4" href="acuerdos_expediente.php" style="color: #FFFFEF">Acuerdos por expediente</a></li>
                </ul>
            </div> -->
        </div>
    </nav>

    <div class="contenedor">
        <div class="titulo">Lista de Acuerdos y Resoluciones</div>
        <div id="pestanas">
            <ul id=lista>
                <li id="pestana1"><a href='javascript:cambiarPestanna(pestanas,pestana1);'>Acuerdos Recientes</a></li>
                <li id="pestana2"><a href='javascript:cambiarPestanna(pestanas,pestana2);'>Acuerdos por Fecha</a></li>
                <li id="pestana3"><a href='javascript:cambiarPestanna(pestanas,pestana3);'>Acuerdos por expediente</a></li>
            </ul>
        </div>

        <div id="contenidopestanas">
            <div id="cpestana1">
                <form method="post" action="">
                    <div>
                        <h1>Prueba aaaaa</h1>
                    </div>
                    <label for="area_padre" style="color: black;">Selecciona Área</label>
                    <select class="form-control" id="area_padre" name="area_padre" style="width: 30%;" onchange="cambiar();">
                        <option value='0'>Selecciona área</option>
                        <?php
                        $result = $conn->query("SELECT id, area_padre, nombre_area FROM area WHERE visible=1 AND area_padre = 0 ORDER BY jerarquia desc");
                        while ($row = $result->fetch_assoc()) {
                        ?>
                            <option value="<?php echo $row["id"]; ?>"><?php echo ucwords($row["nombre_area"]); ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <br>
                    <div id="subsareas" style="display: none;">
                        <label for="area_hija" style="color: black;">Selecciona Subárea</label>
                    </div>
                    <br>
                    <button type="button" name="acuerdos_res_publico" class="btn btn-primary" onclick="muestraRecientes();">Mostrar</button>
                    <div id="muestra_acuerdos">
                    </div>
                </form>

            </div>
            <div id="cpestana2">
                El nombre hojas de estilo en cascada viene del inglés Cascading Style Sheets, del que toma sus siglas. CSS es un lenguaje usado para definir la presentación de un documento estructurado escrito en HTML o XML2 (y por extensión en XHTML). El W3C (World Wide Web Consortium) es el encargado de formular la especificación de las hojas de estilo que servirán de estándar para los agentes de usuario o navegadores.
            </div>
            <div id="cpestana3">
                JavaScript es un lenguaje de programación interpretado, dialecto del estándar ECMAScript. Se define como orientado a objetos,3 basado en prototipos, imperativo, débilmente tipado y dinámico.
            </div>
        </div>
    </div>
</body>
<?
$result->close();

$conn->close();
?>

</html>