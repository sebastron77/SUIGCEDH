<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
session_start();
$page_title = 'Mi perfil';
require_once('includes/load.php');

page_require_level(20);

$usuarioid = $_SESSION['user_id'];
$obtener_id_detalle_usuario = midetalle($usuarioid);
$num = $obtener_id_detalle_usuario;
$e_detalle = find_by_id_detalle_perfil((int)$num[0][0]);
$cargos = find_all('cargos');

?>
<?php
$user_id = (int)$_GET['id'];
if (empty($user_id)) :
  redirect('home.php', false);
else :
  $user_p = find_by_id('users', $user_id, 'id_user');
endif;
?>
<?php include_once('layouts/header.php'); ?>


<style>
  :root {
    --background: #FFFFFF;
    --card-bg: linear-gradient(90deg, rgba(18,0,50,1) 0%, rgba(46,4,124,1) 50%, rgb(62, 1, 175) 100%);
    --border: #FFFFFF;
    --text: #FFFFFF;
    --text-dark: #FFFFFF;
    --primary: #FFFFFF;
    --foto: linear-gradient(90deg, rgba(8,0,22,1) 0%, rgba(19,0,54,1) 50%);
  }

  body {
    background: var(--background);
    display: grid;
    place-items: center;
    height: 85vh;
    font-size: 20px;
    padding: 1.5rem;
  }

  * {
    margin: 0;
    padding: 0;
  }

  .card {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    border-radius: 1.2rem;
    background: var(--card-bg);
    cursor: pointer;
    overflow: hidden;
    color: var(--text);
    max-width: clamp(20rem, 70vw, 46.25rem);
    min-width: 100rem;
    min-height: 17.5rem;
  }

  .card:hover {
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
  }

  .background {
    flex: 1 1 15rem;
  }

  .background img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    background: var(--foto);
  }

  .content {
    flex: 3 1 22rem;
    display: flex;
    flex-direction: column;
    justify-content: start;
    padding: 1rem;
    background: var(--card-bg);
  }

  .content>h2 {
    font-size: clamp(2.3rem, 2.5vw, 2.8rem);
    font-weight: 700;
    margin-bottom: clamp(0.35rem, 2vw, 0.55rem);
  }


  .content>p {
    font-size: 20px;
    font-weight: 400;
    margin: 0.4rem 0;
  }

  .content a {
    color: var(--text);
    font-size: 20px;
  }
</style>

<div class="container">
  <article class="card" style="margin-top: -20%;">
    <div class='background'>
      <img src="uploads/users/<?php echo $user_p['imagen']; ?>" alt="">
    </div>
    <div class='content'>
      <h2 style="color: #D4BEFF "><?php echo $e_detalle['nombre_completo'] ?></h2>
      <p><b>Usuario: </b><?php echo $e_detalle['username'] ?></p>
      <p><b>Área de Adscripción: </b><?php echo $e_detalle['nombre_area'] ?></p>
      <p><b>Cargo: </b><?php echo $e_detalle['nombre_cargo'] ?></p>
      <p><b>Clave: </b><?php echo $e_detalle['clave'] ?></p>
      <!-- <p><b>Nivel del Puesto: </b><?php echo $e_detalle['niv_puesto'] ?></p> -->
      <hr style="width: 96.5%; border-width: 2px;">
      <p><b>Correo: </b><?php echo $e_detalle['correo'] ?></p>
      <p><b>CURP: </b><?php echo $e_detalle['curp'] ?></p>
      <p><b>RFC: </b><?php echo $e_detalle['rfc'] ?></p>
    </div>
  </article>
</div>
<?php include_once('layouts/footer.php'); ?>