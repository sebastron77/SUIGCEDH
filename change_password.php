<?php
$page_title = 'Cambiar contraseña';
require_once('includes/load.php');

page_require_level(20);
?>
<?php $user = current_user(); ?>
<?php
if (isset($_POST['update'])) {

  $req_fields = array('password');
  //validate_fields($req_fields);
  if (empty($errors)) {
    $id = (int)$_POST['id'];
    $password = remove_junk($db->escape($_POST['new-password']));
    $h_pass   = sha1($password);
    $sql = "UPDATE users SET password='{$h_pass}' WHERE id_user='{$db->escape($id)}'";
    $result = $db->query($sql);
    if ($result && $db->affected_rows() === 1) {
      $session->logout();
      $session->msg('s', "Inicia sesión con tu nueva contraseña.");
      redirect('index.php', false);
    } else {
      $session->msg('d', ' Lo siento, la actualización falló.');
      redirect('change_password.php', false);
    }
  } 
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
  <div class="text-center">
    <h3>Cambiar contraseña</h3>
  </div>
  <?php echo display_msg($msg); ?>
  <form method="post" action="change_password.php" class="clearfix">
    <div class="form-group">
      <label for="newPassword" class="control-label">Nueva contraseña</label>
      <input type="password" class="form-control" name="new-password" placeholder="Nueva contraseña">
    </div>
    <!-- <div class="form-group">
      <label for="oldPassword" class="control-label">Antigua contraseña</label>
      <input type="password" class="form-control" name="old-password" placeholder="Antigua contraseña">
    </div> -->
    <div class="form-group clearfix">
      <input type="hidden" name="id" value="<?php echo (int)$user['id']; ?>">
      <a href="edit_account.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
        Regresar
      </a>
      <button type="submit" name="update" class="btn btn-info">Cambiar</button>
    </div>
  </form>
</div>
<?php include_once('layouts/footer.php'); ?>