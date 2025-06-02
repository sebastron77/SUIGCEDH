<?php
$page_title = 'Principal';
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index.php', false);
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  <div class="col-md-12">
    <div class="panel" style="border-radius: 10px;">
      <div class="jumbotron text-center"
        style="background: rgb(18,0,50);
              background: linear-gradient(90deg, rgb(39, 7, 94)  15%, rgb(58, 10, 139) 50%, rgb(106, 17, 207) 100%);">
        <h1 style="color: white; font-weight: 100; font-size:50px;">BIENVENIDO</h1>
        <h4 style="color: white; font-size: 18px; font-weight: 400;">SISTEMA ÚNICO DE INFORMACIÓN Y GESTIÓN DE LA CEDH (SUIGCEDH)</h4>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>