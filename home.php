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
    <div class="panel" style="background-color: #1E2630; border-radius: 15px;">
      <div class="jumbotron text-center"
        style="background: rgb(18,0,50);
background: radial-gradient(circle, rgba(18,0,50,1) 0%, rgba(46,4,124,1) 100%); border-radius: 15px; border: 1px;">
        <h1 style="color: white;">Página principal</h1>
        <h4 style="color: white">Sistema Único de Información y Gestión de la CEDH (SUIGCEDH)</h4>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>