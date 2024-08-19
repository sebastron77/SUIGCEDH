<?php
$page_title = 'Editar Cuenta';
require_once('includes/load.php');
$all_users = find_all_cuentas();
page_require_level(20);
?>
<?php
//actualiza imagen de usuario
if (isset($_POST['submit'])) {
  $photo = new Media();
  $user_id = (int)$_POST['user_id'];
  $photo->upload($_FILES['file_upload']);
  if ($photo->process_user($user_id)) {
    $session->msg('s', 'La foto fue subida al servidor.');
    redirect('edit_account.php');
  } else {
    $session->msg('d', join($photo->errors));
    redirect('edit_account.php');
  }
}
?>
<?php
//actualiza la otra informacion del usuario
if (isset($_POST['update'])) {
  $req_fields = array('username');
  validate_fields($req_fields);
  if (empty($errors)) {
    $id = (int)$_SESSION['user_id'];
    //$name = remove_junk($db->escape($_POST['id_detalle_user']));
    $username = remove_junk($db->escape($_POST['username']));
    $usernameLow = lower_case($_POST['username']);
    $usernameUpper = upper_case($_POST['username']);
    $usernameUcword = ucwords($_POST['username']);
    foreach ($all_users as $user) {
      if (($username == $user['username']) || ($usernameLow == lower_case($user['username'])) || ($usernameUpper == upper_case($user['username'])) || ($usernameUcword == ucwords($user['username']))) {
        $session->msg('d', 'Lo sentimos, el nombre de usuario ya existe.');
        redirect('edit_account.php', false);
      } else {
        $sql = "UPDATE users SET username ='{$username}' WHERE id='{$id}'";
      }
    }
    $result = $db->query($sql);
    if ($result && $db->affected_rows() === 1) {
      $session->msg('s', "Cuenta actualizada. ");
      redirect('edit_account.php', false);
    } else {
      $session->msg('d', ' Lo siento, actualización falló.');
      redirect('edit_account.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_account.php', false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  <div class="col-md-6" style="margin-left: 22%">
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-heading clearfix">
          <span class="glyphicon glyphicon-camera"></span>
          <span>Cambiar mi foto</span>
        </div>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-4">
            <img class="img-circle img-size-2" src="uploads/users/<?php echo $user['imagen']; ?>" alt="">
          </div>
          <div class="col-md-8">
            <form class="form" action="edit_account.php" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <input type="file" name="file_upload" class="form-control" multiple="multiple" class="btn btn-default btn-file" />
              </div>
              <div class="form-group">
                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                <button type="submit" name="submit" class="btn btn-warning">Cambiar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <span class="glyphicon glyphicon-edit"></span>
        <span>Editar mi cuenta</span>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_account.php?id=<?php echo (int)$user['id']; ?>" class="clearfix">
          <div class="form-group clearfix" style=" display: flex; justify-content: center; align-items: center;">
            <a href="change_password.php" title="change password" class="btn btn-danger pull-right">Cambiar contraseña</a>
          </div>
        </form>
      </div>
    </div>
  </div> -->
</div>


<?php include_once('layouts/footer.php'); ?>