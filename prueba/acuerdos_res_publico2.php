<?php
require_once('includes/load.php');
$user = current_user();
$areas = find_all_areas('area');
?>
<!DOCTYPE html>
<html lang="en">

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

<script>
    // Dadas la division que contiene todas las pestañas y la de la pestaña que se quiere mostrar, la funcion oculta todas las pestañas a excepcion de esa.

    function cambiarPestanna(pestannas, pestanna) {

        // Obtiene los elementos con los identificadores pasados.
        pestanna = document.getElementById(pestanna.id);
        listaPestannas = document.getElementById(pestannas.id);

        // Obtiene las divisiones que tienen el contenido de las pestañas.
        cpestanna = document.getElementById('c' + pestanna.id);
        listacPestannas = document.getElementById('contenido' + pestannas.id);

        i = 0;
        // Recorre la lista ocultando todas las pestañas y restaurando el fondo y el padding de las pestañas.

        while (typeof listacPestannas.getElementsByTagName('div')[i] != 'undefined') {
            $(document).ready(function() {
                // ----------->>>>>>>>>>> ES LO QUE BLOQUEA EL QUE FUNCIONEN LOS DIVS <<<<<<<<<<<<<----------------
                $(listacPestannas.getElementsByTagName('div')[i]).css('display', 'none');
                // ------------------------------------------------------------------------------------------------
                $(listaPestannas.getElementsByTagName('li')[i]).css('background', '');
                $(listaPestannas.getElementsByTagName('li')[i]).css('padding-bottom', '');
            });
            i += 1;
        }

        $(document).ready(function() {
            // Muestra el contenido de la pestaña pasada como parametro a la funcion, cambia el color de la pestaña y aumenta el padding para que tape el  
            // borde superior del contenido que esta juesto debajo y se vea de este modo que esta seleccionada.
            $(cpestanna).css('display', '');
            $(pestanna).css('background', '#056DCD');
            $(pestanna).css('padding-bottom', '2px');
        });
    }

    // function getValue(option) {
    //     alert(option.value);
    // }
</script>

<!-- ||||||||||||||||||||||||||||||||||||||||||||||||| ESTE ES EL SCRIPT QUE HACE DINÁMICOS LOS SELECTS. DESCOMENTARLO SI SE OCUPA |||||||||||||||||||||||||||||||||||||||||||||||||-->
<script language="javascript">
    // $(document).ready(function() {
    //     $("#area_padre").change(function() {
    //         $("#area_padre option:selected").each(function() {
    //             id = $(this).val();
    //             $.post("buscar4.php", {
    //                 id: id
    //             }, function(data) {
    //                 $("#area_hija").html(data);
    //             })
    //         })

    //     })
    // });
</script>
<!-- <body class="bodyPublico ">
    <nav class="navbar navbar-default" style="background: #075E9A;">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="principal.php" style="color: #FFFFEF;">Libro Electrónico de la CEDH</a>
            </div> -->
<!-- <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a class="boton4" href="acuerdos_recientes.php" style="color: #FFFFEF">Acuerdos recientes</a></li>
                    <li><a class="boton4" href="acuerdos_fecha.php" style="color: #FFFFEF">Acuerdos por fecha</a></li>
                    <li><a class="boton4" href="acuerdos_expediente.php" style="color: #FFFFEF">Acuerdos por expediente</a></li>
                </ul>
            </div> -->
<!-- </div>
    </nav> -->

<!-- <div class="rectangulo">
        <div class="row">
            <h1 style="text-align: center; margin-top: 5%;">Comisión Estatal de los Derechos Humanos Michoacán</h1><br>
        </div><br><br>
        <div class="row" style="display:inline;">
            <div class="col-md-4">
                <a href="acuerdos_recientes.php" class="boton1">Acuerdos recientes</a>
            </div>
            <div class="col-md-4">
                <a href="acuerdos_fecha.php" class="boton2">Acuerdos por fecha</a>
            </div>
            <div class="col-md-4">
                <a href="acuerdos_expediente.php" class="boton2">Acuerdos por expediente</a>
            </div>
        </div><br><br>
    </div>
</body> -->


<body class="bodyPublico" onload="javascript:cambiarPestanna(pestanas,pestana1);">
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
                <form method="post" action="acuerdos_res_publico.php">
                    <div>
                        <h1>Prueba aaaaa</h1>
                    </div>
                    <label for="area_padre" style="color: black;">Selecciona Área</label>
                    <select class="form-control" id="area_padre" name="area_padre" style="width: 30%;" onchange="cambiar();">
                        <option value=''>Selecciona área</option>
                        <?php foreach ($areas as $area) : ?>
                            <option value="<?php echo $area['id']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <script>
                        // function getValue(option) {
                        //     alert(option.value);
                        //     var hola = option.value;
                        // };
                        $('select').on('change', function() {
                            // alert(this.value);
                            var plis = this.value;
                        });
                        // function cambiar() {
                        //     var select = document.getElementById("area_padre"), //El <select>
                        //         value = select.value, //El valor seleccionado
                        //         text = select.options[select.selectedIndex].innerText; //El texto de la opción seleccionada
                        //         console.log(text);
                        //         return text;
                        //     }
                        // prueba = document.write(text);
                    </script>
                    <?php
                    echo "NO FUNCIONAAAAA: <script>document.write(plis);</script>";
                    $areas_hijas = find_all_areas2($area['id']);
                    // echo $_POST["area_padre"];
                    ?><br>
                    <label for="area_hija" style="color: black;">Selecciona Subárea</label>
                    <select class="form-control" id="area_hija" name="area_hija" style="width: 30%;" onchange="this.form.action()">
                        <option value=''>Selecciona subárea</option>
                        <?php foreach ($areas_hijas as $area2) : ?>
                            <option value="<?php echo (int)$area2['id']; ?>"><?php echo ucwords($area2['nombre_area']); ?></option>
                        <?php endforeach; ?>
                    </select><br>

                    <!-- <label for="area_hija" style="color: black;">Selecciona Subárea</label>
                    <select class="form-control" id="area_hija" name="area_hija" style="width: 30%;" onchange="this.form.action()"></select><br> -->

                    <button type="submit" name="acuerdos_res_publico" class="btn btn-primary" value="subir">Mostrar</button>
                </form>
                <!-- <?php echo $area['nombre_area']; ?> -->
                <?php echo "Select Padre: " . $_POST["area_padre"]; ?>
                <?php echo "Select Hija: " . $_POST["area_hija"]; ?>
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

</html>