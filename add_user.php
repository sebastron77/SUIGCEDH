<?php
$page_title = 'Agregar usuarios';
require_once('includes/load.php');

page_require_level(1);
$groups = find_all('grupo_usuarios');
$trabajadores = find_all_trabajadores();
$user = current_user();
$estados = find_all('cat_entidad_fed');
?>
<?php
if (isset($_POST['add_user'])) {

  $req_fields = array('detalle-usuario', 'username', 'contraseña', 'level');
  validate_fields($req_fields);

  if (empty($errors)) {
    $detalle   = remove_junk($db->escape($_POST['detalle-usuario']));
    $username   = remove_junk($db->escape($_POST['username']));
    $password   = remove_junk($db->escape($_POST['contraseña']));
    $user_level = (int)$db->escape($_POST['level']);
    $password = sha1($password);

    //valido que el usuario no exista
    $userOld = search_user($username);

    if ($userOld['id_user'] > 0) {
      $session->msg('d', "El Nombre de Usuario '" . $username . "' ya se encuentra asignado, por favor asigne uno diferente");
      redirect('add_user.php', false);
    } else {
      $query = "INSERT INTO users (";
      $query .= "id_detalle_user,username,password,user_level,status";
      $query .= ") VALUES (";
      $query .= " '{$detalle}', '{$username}', '{$password}', '{$user_level}','1'";
      $query .= ")";
      if ($db->query($query)) {
        //sucess
        $session->msg('s', " La cuenta de usuario ha sido creada con éxito.");
        insertAccion($user['id_user'], '"' . $user['username'] . '" agregó el usuario: ' . $username . '.', 1);
        redirect('add_user.php', false);
      } else {
        //failed
        $session->msg('d', ' No se pudo crear la cuenta.');
        redirect('add_user.php', false);
      }
    }
  } else {
    $session->msg("d", $errors);
    redirect('add_user.php', false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Agregar usuario</span>
      </strong>
    </div>
    <div class="panel-body">
      <div class="col-md-7">
        <form method="post" action="add_user.php">
          <div class="form-group">
            <label for="level">Trabajador</label>
            <select class="form-control" name="detalle-usuario">
              <option value="">Selecione Trabajador </option>
              <?php foreach ($trabajadores as $trabajador) : ?>
                <?php if ($trabajador['id_cargo'] == '') : ?>
                  <option style="color: red" value="<?php echo $trabajador['detalleID']; ?>"><?php echo ucwords($trabajador['nombre']); ?> <?php echo ucwords($trabajador['apellidos']); ?></option>
                <?php endif; ?>
                <?php if ($trabajador['id_cargo'] != '') : ?>
                  <option value="<?php echo $trabajador['detalleID']; ?>"><?php echo ucwords($trabajador['nombre']); ?> <?php echo ucwords($trabajador['apellidos']); ?></option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Nombre de usuario">
          </div>
          <div class="form-group">
            <label for="contraseña">Contraseña</label>
            <input type="password" class="form-control" name="contraseña" placeholder="Contraseña">
          </div>
          <div class="form-group">
            <!-- <label for="level">Rol de usuario</label>
            <select class="form-control" name="level">
              <?php foreach ($groups as $group) : ?>
                <option value="<?php echo $group['nivel_grupo']; ?>"><?php echo ucwords($group['nombre_grupo']); ?></option>
              <?php endforeach; ?>
            </select> -->



            <!-- -------------------------------------------- NIVELES DE USUARIO CON DATALIST --------------------------------------------  -->

            <label style="width: 400px;">Rol de usuario
              <input class="form-control" list="grupos" id="input_grupo" name="nombre_grupo" placeholder="Escribe o selecciona un rol">
            </label>

            <datalist id="grupos">
              <?php foreach ($groups as $group) : ?>
                <option data-id="<?php echo $group['nivel_grupo']; ?>" value="<?php echo $group['nombre_grupo']; ?>"></option>
              <?php endforeach; ?>
            </datalist>

            <input type="hidden" name="level" id="level">

            <!-- -------------------------------------------------------------------------------------------------------------------------  -->



          </div>
          <div class="form-group clearfix">
            <a href="users.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
              Regresar
            </a>
            <button type="submit" name="add_user" class="btn btn-primary" style="background: #5c1699; border-color: #5c1699;">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- ----------------------- SCRIPT PARA EL DATALIST ----------------------- -->
<script>
  document.getElementById('input_grupo').addEventListener('input', function() {
    const input = this;
    const datalist = document.getElementById('grupos');
    const options = datalist.querySelectorAll('option');
    const hiddenInput = document.getElementById('level');

    hiddenInput.value = ''; // Limpiar primero

    options.forEach(function(option) {
      if (option.value === input.value) {
        hiddenInput.value = option.dataset.id;
      }
    });
  });
</script>
<!-- ----------------------------------------------------------------------- -->
<?php include_once('layouts/footer.php'); ?>