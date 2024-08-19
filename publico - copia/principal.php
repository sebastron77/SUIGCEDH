<?php require_once('../includes/load.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="../libs/css/main.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="../libs/js/functions.js"></script>
</head>
<style>
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
</style>

<body class="bodyPublico">
    <nav class="navbar navbar-default" style="background: #075E9A;">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="principal.php" style="color: #FFFFEF;">Libro Electrónico de la CEDH</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a class="boton4" href="acuerdos_publico.php" style="color: #FFFFEF">Acuerdos de No Violación</a></li>
                    <li><a class="boton4" href="recomendaciones_publico.php" style="color: #FFFFEF">Recomendaciones</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="rectangulo">
        <center>
            <div class="row" style="margin-top: 20px;">
                <img src="LOGO-CEDH.png" width="35%" alt="logocedh">
            </div><br><br>
        </center>
        <div class="row" style="display:inline;">
            <!-- <div class="col"> -->
            <a href="acuerdos_publico.php" class="boton1">Acuerdos de No Violación</a><br><br>
            <!-- </div> -->
            <!-- <div class="col"> -->
            <a href="recomendaciones_publico.php" class="boton2">Recomendaciones</a><br><br>
            <!-- </div><br><br><br><br><br><br> -->
        </div>
    </div>
</body>

</html>