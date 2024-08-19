<?php
$page_title = 'Editar Usuario';
require_once('includes/load.php');

page_require_level(1);
?>
<?php
$e_user = find_by_id('users', (int)$_GET['id'], 'id_user');
$groups  = find_all('grupo_usuarios');
$all_trabajadores = find_all_trabajadores();
if (!$e_user) {
  $session->msg("d", "id de usuario no encontrado.");
  redirect('users.php');
}
$user = current_user();
$nivel = $user['user_level'];
?>

<?php
//serror_reporting(E_ALL ^ E_NOTICE);
//Actualiza informacion basica del usuario
if (isset($_POST['update'])) {
  $req_fields = array('detalle','username');
  validate_fields($req_fields);
  if (empty($errors)) {
    $id = (int)$e_user['id_user'];
    //$name = remove_junk((int)$db->escape($_POST['detalle-user']));
    $username = remove_junk($db->escape($_POST['username']));
    $detalle = remove_junk($db->escape($_POST['detalle']));
    $level = remove_junk((int)$db->escape($_POST['level']));
    $status   = remove_junk((int)$db->escape($_POST['status']));
    $sql = "UPDATE users SET id_detalle_user={$detalle}, username='{$username}', user_level='{$level}', status='{$status}' WHERE id_user='{$db->escape($id)}'";
    $result = $db->query($sql);
    if ($result && $db->affected_rows() === 1) {
      $session->msg('s', "Cuenta Actualizada ");
      insertAccion($user['id_user'], '"'.$user['username'].'" editó el usuario: '.$username.'.', 1);
      redirect('edit_user.php?id=' . (int)$e_user['id_user'], false);
    } else {
      $session->msg('d', ' Lo siento no se actualizaron los datos.');
      redirect('edit_user.php?id=' . (int)$e_user['id_user'], false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_user.php?id=' . (int)$e_user['id_user'], false);
  }
}
?>
<?php
// Update user password
if (isset($_POST['update-pass'])) {
  $req_fields = array('password');
  //validate_fields($req_fields);
  if (empty($errors)) {
    $id = (int)$e_user['id_user'];
    $password = remove_junk($db->escape($_POST['password']));
    $h_pass   = sha1($password);
    $sql = "UPDATE users SET password='{$h_pass}' WHERE id_user='{$db->escape($id)}'";
    $result = $db->query($sql);
    if ($result && $db->affected_rows() === 1) {
      $session->msg('s', "Se ha actualizado la contraseña del usuario. ");
      insertAccion($user['id_user'], '"'.$user['username'].'" editó contraseña del usuario: '.$e_user['username'].'.', 2);
      redirect('edit_user.php?id=' . (int)$e_user['id_user'], false);
    } else {
      $session->msg('d', 'No se pudo actualizar la contraseña de usuario..');
      redirect('edit_user.php?id=' . (int)$e_user['id_user'], false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_user.php?id=' . (int)$e_user['id_user'], false);
  }
}

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          Actualizar cuenta del usuario <?php echo remove_junk($e_user['username']); ?>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_user.php?id=<?php echo (int)$e_user['id_user']; ?>" class="clearfix">
        <div class="form-group">
            <label for="detalle">Trabajador</label>
            <select class="form-control" name="detalle">
                <?php foreach ($all_trabajadores as $trabajador) : ?>
                    <option <?php if ($trabajador['detalleID'] === $e_user['id_detalle_user']) echo 'selected="selected"'; ?> value="<?php echo $trabajador['detalleID']; ?>"><?php echo ucwords($trabajador['nombre']." ".$trabajador['apellidos']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
          <div class="form-group">
            <label for="username" class="control-label">Usuario</label>
            <input type="text" class="form-control" name="username" value="<?php echo remove_junk($e_user['username']); ?>">
          </div>
          <?php if ($nivel == 1) : ?>
          <div class="form-group">
            <label for="level">Rol de usuario</label>
            <select class="form-control" name="level">
              <?php foreach ($groups as $group) : ?>
                <option <?php if ($group['nivel_grupo'] === $e_user['user_level']) echo 'selected="selected"'; ?> value="<?php echo $group['nivel_grupo']; ?>"><?php echo ucwords($group['nombre_grupo']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
            <div class="form-group">
              <label for="status">Estado</label>
              <select class="form-control" name="status">
                <option <?php if ($e_user['status'] === '1') echo 'selected="selected"'; ?>value="1">Activo</option>
                <option <?php if ($e_user['status'] === '0') echo 'selected="selected"'; ?> value="0">Inactivo</option>
              </select>
            </div>
          <?php endif ?>
          <div class="form-group clearfix">
            <a href="users.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
              Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Change password form -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          Cambiar contraseña del usuario <?php echo remove_junk(ucwords($e_user['username'])); ?>
        </strong>
      </div>
      <div class="panel-body">
        <form action="edit_user.php?id=<?php echo (int)$e_user['id_user']; ?>" method="post" class="clearfix">
          <div class="form-group">
            <label for="password" class="control-label">Contraseña</label>
            <input type="password" class="form-control" name="password" placeholder="Ingresa la nueva contraseña" required>
          </div>
          <div class="form-group clearfix">
            <button type="submit" name="update-pass" class="btn btn-danger pull-right">Cambiar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
<?php include_once('layouts/footer.php'); ?>