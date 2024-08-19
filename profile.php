<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
session_start();
$page_title = 'Mi perfil';
require_once('includes/load.php');

page_require_level(20);

$usuarioid = $_SESSION['user_id'];
//echo $usuarioid;
$obtener_id_detalle_usuario = midetalle($usuarioid);
//print json_encode ($obtener_id_detalle_usuario);
$num = $obtener_id_detalle_usuario;
$e_detalle = find_by_id('detalles_usuario', (int)$num[0][0], 'id_det_usuario');
//$e_detalle_cargo = find_detalle_cargo((int)$num[0][0]);
$cargos = find_all('cargos');

//$asignaciones = misasignaciones((int)$num[0][0]);

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
<div class="row">
  <div class="profile-block">
    <div class="col-md-4">
      <div class="panel profile">
        <div class="jumbotron text-center" style="background: #7263F0; border-color: #7263F0;">
          <img class="img-circle img-size-2" src="uploads/users/<?php echo $user_p['imagen']; ?>" alt="">
          <h3><?php echo first_character($user_p['username']); ?></h3>
        </div>
        <?php if ($user_p['id'] === $user['id']) : ?>
          <ul class="nav nav-pills nav-stacked">
            <li><a href="edit_account.php" style="background: #FFFFFF; color: #7263F0; text-decoration: none;"> <i class="glyphicon glyphicon-edit"></i> Editar mi perfil</a></li>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        Mi información: <?php echo (ucwords($e_detalle['nombre'])); ?> <?php echo (ucwords($e_detalle['apellidos'])); ?>
      </strong>
    </div>
    <div class="panel-body">
      <form method="post" action="edit_detalle_usuario.php?id=<?php echo (int)$e_detalle['id']; ?>" class="clearfix">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="nombre" class="control-label">Nombre</label>
              <input readonly type="text" class="form-control" name="nombre" value="<?php echo (ucwords($e_detalle['nombre'])); ?>">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="apellidos" class="control-label">Apellidos</label>
              <input readonly type="text" class="form-control" name="apellidos" value="<?php echo (ucwords($e_detalle['apellidos'])); ?>">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="cargo">Cargo</label>
              <input readonly type="text" class="form-control" name="cargo" value="<?php foreach ($cargos as $cargo) : if ($cargo['id_cargos'] === $e_detalle['id_cargo']) echo $cargo['nombre_cargo']; endforeach; ?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="sexo">Sexo</label>
            <input readonly type="text" class="form-control" name="sexo" value="<?php echo $e_detalle['sexo'] == 'H' ? 'Hombre' : 'Mujer' ?>">
          </div>
          <div class="col-md-4">
            <label for="correo" class="control-label">Correo</label>
            <input readonly type="text" class="form-control" name="correo" value="<?php echo remove_junk($e_detalle['correo']); ?>">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>