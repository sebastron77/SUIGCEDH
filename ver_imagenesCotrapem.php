<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Estadística de Quejas';
require_once('includes/load.php');
$carpeta = $_GET['carpeta'];
$user = current_user();
$nivel_user = $user['user_level'];


if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 3) {
    page_require_level_exacto(3);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7) :
    redirect('home.php');
endif;

$listar = null;
// $directorio = opendir("uploads/jornadas/");
// Abre la carpeta que se mandó en el formulario
$carpeta_img = opendir($carpeta . '/');
$liga = "$carpeta/";
//Arreglo para guardar las carpetas
$carpetas = array();

?>
<?php include_once('layouts/header.php'); ?>
<style>
    .img-gallery {
        width: 80%;
        margin: 50px auto 50px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 30px;
    }

    .img-gallery img {
        width: 100%;
        cursor: pointer;
        transition: 1s;
    }

    .img-gallery img:hover {
        transform: scale(1.2);
    }

    .ful-img {
        width: 100%;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.9);
        position: fixed;
        top: 0;
        left: 0;
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 99;
    }

    .ful-img span {
        position: absolute;
        top: 5%;
        right: 5%;
        font-size: 40px;
        color: #EA2518;
        cursor: pointer;
    }

    .ful-img img {
        width: 85%;
        max-width: 100%;
    }

    @media screen and (max-width:400px) {
        h1 {
            text-decoration: underline;
        }

        h1::before {
            display: none;
        }

        h1 span {
            padding: 0;
        }
    }
</style>
<a href="cotrapem.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
    Regresar
</a>
<h1 style="text-align: center; color: #53555b">Imágenes Sesión de COTRAPEM <?php echo substr($carpeta, 23) ?></h1>
<div class="row">

    <?php
    $liga = $carpeta;
    $borrar = filter_input(INPUT_GET, 'borrar');
    if (!empty($borrar)) {
        unlink($elemento . $borrar);
        echo $carpeta;
        echo '<script> window.history.back() </script>';
    }

    $imagenes2 = scandir($liga);
    for ($i = 2; $i < count($imagenes2); $i++) { ?>
        <div class="ful-img" id="fulImgBox">
            <img src="<?php echo $carpeta . '/' . $imagenes2[$i] ?>" id="fulImg" alt="">
            <span onclick="closeImg()">
                <span class="material-symbols-outlined">cancel</span>
            </span>
        </div>
        <div class="col-md-2">
            <div class="img-gallery">
                <img style="width: 180px; height: 100px; margin-left: 1%;" src="<?php echo $carpeta . '/' . $imagenes2[$i] ?>" onclick="openFulImg(this.src)" alt="">
                <!-- <a href="?borrar=<?= $imagenes2[$i] ?>" class="btn btn-danger">Borrar imagen</a> -->
                <a href="?borrar=<?= $carpeta . '/' . $imagenes2[$i] ?>" class="btn btn-danger" style="width: 50%; margin-left: 15%">Borrar imagen</a>
            </div>
        </div>
    <?php } ?>

</div>

<script src="img.js"></script>

<?php include_once('layouts/footer.php'); ?>