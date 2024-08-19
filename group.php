<?php
$page_title = 'Lista de grupos';
require_once('includes/load.php');

page_require_level(1);
$all_groups = find_all('grupo_usuarios');
$user = current_user();
$nivel = $user['user_level'];
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Grupos de Usuarios</span>
        </strong>
        <a href="add_group.php" class="btn btn-info pull-right btn-md"> Agregar grupo</a>
      </div>
      <div class="panel-body">
        <table class="datatable table table-bordered table-striped">
          <thead class="thead-purple">
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Nombre del grupo</th>
              <th class="text-center" style="width: 20%;">Nivel del grupo</th>
              <th class="text-center" style="width: 15%;">Estado</th>
              <th class="text-center" style="width: 100px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_groups as $a_group) : ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td><?php echo remove_junk(ucwords($a_group['nombre_grupo'])) ?></td>
                <td class="text-center">
                  <?php echo remove_junk(ucwords($a_group['nivel_grupo'])) ?>
                </td>
                <td class="text-center">
                  <?php if ($a_group['estatus_grupo'] === '1') : ?>
                    <span class="label label-success"><?php echo "Activo"; ?></span>
                  <?php else : ?>
                    <span class="label label-danger"><?php echo "Inactivo"; ?></span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_group.php?id=<?php echo (int)$a_group['id_grupo_us']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                      <i class="glyphicon glyphicon-pencil"></i>
                    </a>
                    <?php if ($nivel == 1) : ?>
                      <!-- <a href="delete_group.php?id=<?php echo (int)$a_group['id']; ?>" class="btn btn-md btn-danger" data-toggle="tooltip" title="Eliminar">
                        <i class="glyphicon glyphicon-trash"></i>
                      </a> -->
                      
                      <?php if ($a_group['estatus_grupo'] == 0) : ?>
                          <a href="activate_group.php?nivel_grupo=<?php echo (int)$a_group['nivel_grupo']; ?>" class="btn btn-success btn-md" title="Activar" data-toggle="tooltip">
                            <span class="glyphicon glyphicon-ok"></span>
                          </a>
                      <?php else : ?>
                        <a href="inactivate_group.php?nivel_grupo=<?php echo (int)$a_group['nivel_grupo']; ?>" class="btn btn-md btn-danger" data-toggle="tooltip" title="Inactivar">
                          <i class="glyphicon glyphicon-ban-circle"></i>
                        </a>
                      <?php endif; ?>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>