<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Estadística de Quejas';
require_once('includes/load.php');
$carpeta = $_GET['carpeta'];


//$a_ficha = find_by_id('jornadas', (int)$_GET['id'], 'id_jornada');
// $tipo_ficha = find_tipo_ficha((int)$_GET['id']);
$user = current_user();
$nivel = $user['user_level'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    redirect('home.php');
}
if ($nivel == 4) {
    page_require_level(4);
}
if ($nivel == 5) {
    redirect('home.php');
}
if ($nivel == 6) {
    redirect('home.php');
}
if ($nivel == 7) {
    redirect('home.php');
}

$listar = null;
// $directorio = opendir("uploads/jornadas/");
// Abre la carpeta que se mandó en el formulario
$carpeta_img = opendir('uploads/jornadas/' . $carpeta . '/');
$liga = "uploads/jornadas/$carpeta/";
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
<a href="jornadas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
    Regresar
</a>
<h1 style="text-align: center; color: #53555b">Imágenes de la jornada <?php echo $carpeta ?></h1>
<div class="row">

    <?php
    $liga = "uploads/jornadas/" . $carpeta;
    $borrar = filter_input(INPUT_GET, 'borrar');
    if (!empty($borrar)) {
        unlink("uploads/jornadas/" . $elemento . $borrar);
        echo $carpeta;
        echo '<script> window.history.back() </script>';

        // redirect('jornadas.php');
    }
    // else {
    //     //failed
    //     $session->msg('d', ' No se pudo eliminar la imagen.');
    //     redirect('jornadas.php', false);
    // }

    $imagenes2 = scandir($liga);
    for ($i = 2; $i < count($imagenes2); $i++) { ?>
        <div class="ful-img" id="fulImgBox">
            <img src="uploads/jornadas/<?php echo $carpeta . '/' . $imagenes2[$i] ?>" id="fulImg" alt="">
            <span onclick="closeImg()">X</span>
        </div>
        <div class="col-md-4">
            <div class="img-gallery">
                <img style="width: 280px; height: 120px" src="uploads/jornadas/<?php echo $carpeta . '/' . $imagenes2[$i] ?>" onclick="openFulImg(this.src)" alt="">
                <!-- <a href="?borrar=<?= $imagenes2[$i] ?>" class="btn btn-danger">Borrar imagen</a> -->
                <a href="?borrar=<?= $carpeta . '/' . $imagenes2[$i] ?>" class="btn btn-danger">Borrar imagen</a>
            </div>
        </div>
    <?php } ?>

</div>

<script src="img.js"></script>

<!-- <div class="img-gallery">
    <img src="uploads/jornadas/<?php echo $carpeta . '/' . $elemento ?>">
</div> -->

<?php include_once('layouts/footer.php'); ?>