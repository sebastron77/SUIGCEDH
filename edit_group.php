<?php
$page_title = 'Editar Grupo';
require_once('includes/load.php');

page_require_level(1);
?>
<?php
$e_group = find_by_id('grupo_usuarios', (int)$_GET['id'],'id_grupo_us');
if (!$e_group) {
  $session->msg("d", "id del grupo no encontrado.");
  redirect('group.php');
}
?>
<?php
if (isset($_POST['update'])) {

  $req_fields = array('group-name', 'group-level');
  validate_fields($req_fields);
  if (empty($errors)) {
    $name = remove_junk($db->escape($_POST['group-name']));
    $level = remove_junk($db->escape($_POST['group-level']));
    $status = remove_junk($db->escape($_POST['status']));

    $query  = "UPDATE grupo_usuarios SET ";
    $query .= "nombre_grupo='{$name}',nivel_grupo='{$level}',estatus_grupo='{$status}' ";
    $query .= "WHERE id_grupo_us='{$db->escape($e_group['id_grupo_us'])}'";
    $result = $db->query($query);
    if ($result && $db->affected_rows() === 1) {
      //sucess
      $session->msg('s', "Grupo se ha actualizado! ");
      redirect('edit_group.php?id=' . (int)$e_group['id_grupo_us'], false);
    } else {
      //failed
      $session->msg('d', 'Lamentablemente no se ha actualizado el grupo!');
      redirect('edit_group.php?id=' . (int)$e_group['id_grupo_us'], false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_group.php?id=' . (int)$e_group['id_grupo_us'], false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
  <div class="text-center">
    <h3>Editar Grupo</h3>
  </div>
  <?php echo display_msg($msg); ?>
  <form method="post" action="edit_group.php?id=<?php echo (int)$e_group['id_grupo_us']; ?>" class="clearfix">
    <div class="form-group">
      <label for="name" class="control-label">Nombre del grupo</label>
      <input type="name" class="form-control" name="group-name" value="<?php echo remove_junk(ucwords($e_group['nombre_grupo'])); ?>">
    </div>
    <div class="form-group">
      <label for="level" class="control-label">Nivel del grupo</label>
      <input type="number" class="form-control" name="group-level" value="<?php echo (int)$e_group['nivel_grupo']; ?>">
    </div>
    <div class="form-group">
      <label for="status">Estado</label>
      <select class="form-control" name="status">
        <option <?php if ($e_group['estatus_grupo'] === '1') echo 'selected="selected"'; ?> value="1"> Activo </option>
        <option <?php if ($e_group['estatus_grupo'] === '0') echo 'selected="selected"'; ?> value="0">Inactivo</option>
      </select>
    </div>
    <div class="form-group clearfix">
      <a href="group.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
        Regresar
      </a>
      <button type="submit" name="update" class="btn btn-info">Actualizar</button>
    </div>
  </form>
</div>

<?php include_once('layouts/footer.php'); ?>