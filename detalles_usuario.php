<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Datos trabajadores';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
  page_require_level_exacto(1);
}
if ($nivel_user == 2) {
  page_require_level_exacto(2);
}
if ($nivel_user == 14) {
  page_require_level_exacto(14);
}
if ($nivel_user == 29) {
  page_require_level_exacto(29);
}
if ($nivel_user > 2 && $nivel_user < 14) :
  redirect('home.php');
endif;
if ($nivel_user > 14 && $nivel_user < 29) :
  redirect('home.php');
endif;
if ($nivel_user > 29) :
  redirect('home.php');
endif;

$id_usuario = $user['id_user'];
$busca_area = area_usuario($id_usuario);
$otro = $busca_area['nivel_grupo'];
$all_detalles = find_all_trabajadores();

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");

$sql = "SELECT
id_det_usuario	,
nombre	,
apellidos	,
cg.`descripcion` as genero	,
IF(estatus_detalle=1,'Activo','Baja') as estatus_trabajador	,
niv_puesto,
IFNULL(cp.`descripcion`,'-Sin Asignación de Puesto-') as nombre_puesto	,
IFNULL(a2.`nombre_area`,'-Sin Asignación de Área-') as nombre_area_adscipcion	
FROM `detalles_usuario` a
LEFT JOIN `cat_genero` cg ON( a.`id_cat_gen` = cg.`id_cat_gen`)
LEFT JOIN `cat_puestos` cp ON(a.`id_cat_puestos`=  `cp`.`id_cat_puestos`)
LEFT JOIN `area` a2 ON(a.`id_area` = a2.`id_area`)
ORDER BY nombre,apellidos";

$resultado = mysqli_query($conexion, $sql) or die;
$trabajadores = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $trabajadores[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($trabajadores)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=trabajadores.xls");
        $filename = "trabajadores.xls";
        $mostrar_columnas = false;

        foreach ($trabajadores as $datos) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($datos))) . "\n";
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($datos))) . "\n";
        }		

    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}

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
          <span>Lista de Trabajadores de la CEDH</span>
        </strong>
        <?php if ($nivel_user == 1 || $nivel_user == 14 || $nivel_user == 29) : ?>
			<form action=" <?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
				<button style="float: right; margin-top: 0px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
			</form>&nbsp;
          <a href="add_detalle_usuario.php" class="btn btn-info pull-right" style="margin-left:10px">Agregar trabajador</a>
          <a href="ver_licencias_vigentes.php" class="btn btn-info pull-right" style="background: #5f03df; border-color: #5f03df;">Ver Licencias Vigentes</a>
        <?php endif ?>
        <!-- <a href="ver_licencias" style="margin-left: 15%; margin-top: 10%">Ver información de licencias vigentes</a> -->
      </div>

      <div class="panel-body">
        <table class="datatable table table-bordered table-striped">
          <thead class="thead-purple">
            <tr style="height: 10px;"">
              <th class=" text-center" style=" width: 1%;">#</th>
              <th class="text-center" style="width: 1%;">No.Empledo</th>
              <th class="text-center" style="width: 5%;">Nombre(s)</th>
              <th class="text-center" style="width: 5%;">Apellidos</th>
              <th class="text-center" style="width: 10%;">Puesto</th>
              <th class="text-center" style="width: 10%;">Cargo</th>
              <th class="text-center" style="width: 15%;">Área</th>
              <th class="text-center" style="width: 1%;">Estatus</th>
              <?php if ($otro == 1 || $nivel_user == 1 || ($nivel_user == 14)  || $nivel_user == 29) : ?>
                <th style="width: 1%;" class="text-center">Acciones</th>
              <?php endif ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_detalles as $a_detalle) : ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td class="text-center"><?php echo remove_junk(ucwords($a_detalle['no_empleado'])) ?></td>
                <td><?php echo remove_junk(ucwords($a_detalle['nombre'])) ?></td>
                <td><?php echo remove_junk(ucwords($a_detalle['apellidos'])) ?></td>
                <td><?php echo remove_junk(ucwords($a_detalle['puesto'])) ?></td>
                <td><?php echo remove_junk(ucwords($a_detalle['nombre_cargo'])) ?></td>
                <td><?php echo remove_junk(ucwords($a_detalle['nombre_area'])) ?></td>
                <td class="text-center">
                  <?php if ($a_detalle['estatus_detalle'] === '1') : ?>
                    <?php if ($a_detalle['id_rel_licencia_personal'] > '0') : ?>
                      <span class="label label-licencia"><?php echo "Permiso"; ?></span>
                    <?php else : ?>
                      <span class="label label-success"><?php echo "Activo"; ?></span>
                    <?php endif; ?>
                  <?php else : ?>
                    <span class="label label-danger"><?php echo "Inactivo"; ?></span>
                  <?php endif; ?>
                </td>
                <?php if ($nivel_user == 1 || ($nivel_user == 14)  || $nivel_user == 29) : ?>
                  <td class="text-center">
                    <div class="btn-group">
					<?php if ($a_detalle['estatus_detalle'] == 1) : ?>
                      <a href="ver_info_detalle.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información" style="height: 40px">
                        <span class="material-symbols-rounded" style="font-size: 20px; color: white; margin-top: 5px;">visibility</span>
                      </a>&nbsp;
                      <a href="edit_detalle_usuario.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 40px">
                        <span class="material-symbols-rounded" style="font-size: 20px; color: black; margin-top: 5px;">edit</span>
                      </a>&nbsp;
                      <a href="exp_general.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-danger btn-md" style=" background: #D94F21; border-color:#D94F21; height: 40px" title="Expediente General" data-toggle="tooltip">
                        <span class="material-symbols-rounded" style="font-size: 22px; color: white; margin-top: 5px;">folder_shared</span>
                      </a>&nbsp;
                      <a href="exp_ac_lab.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-danger btn-md" style=" background: #0F6466; border-color:#0F6466; height: 40px" title="Expediente Académico" data-toggle="tooltip">
                        <span class="material-symbols-rounded" style="font-size: 23px; color: white; margin-top: 5px;">school</span>
                      </a>&nbsp;
                      <a href="exp_laboral.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-danger btn-md" style=" background: #5347da; border-color:#5347da; height: 40px" title="Expediente Laboral" data-toggle="tooltip">
                        <span class="material-symbols-rounded" style="font-size: 20px; color: white; margin-top: 5px;">work</span>
                      </a>&nbsp;
                      <a href="licencias.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-danger btn-md" style=" background: #e44cd5; border-color:#e44cd5; height: 40px" title="Licencias" data-toggle="tooltip">
                        <span class="material-symbols-rounded" style="font-size: 20px; color: white; margin-top: 5px;">calendar_clock</span>
                      </a>&nbsp;
                      <a href="vacaciones.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-danger btn-md" style=" background: #229df0; border-color:#229df0; height: 40px" title="Vacaciones" data-toggle="tooltip">
                        <span class="material-symbols-rounded" style="font-size: 20px; color: white; margin-top: 5px;">beach_access</span>
                      </a>&nbsp;
                        <?php endif; ?>
                      <?php if (($nivel_user   == 1) || ($nivel_user == 14)  || $nivel_user == 29) : ?>
                        <?php if ($a_detalle['estatus_detalle'] == 0) : ?>
                          <a href="activate_detalle_usuario.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-success btn-md" title="Activar" data-toggle="tooltip" style="height: 40px">
                            <span class="material-symbols-rounded" style="font-size: 20px; color: white; margin-top: 5px;">check</span>
                          </a>&nbsp;
                        <?php else : ?>
                          <a href="inactivate_detalle_usuario.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-danger btn-md" title="Inactivar" data-toggle="tooltip" style="height: 40px">
                            <span class="material-symbols-rounded" style="font-size: 20px; color: white; margin-top: 5px;">block</span>
                          </a>&nbsp;
                        <?php endif; ?>
                      <?php endif; ?>
                    </div>
                  </td>
                <?php endif ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>