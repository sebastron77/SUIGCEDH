<?php
$page_title = 'Mesas, Comités y Grupos de Trabajo';
require_once('includes/load.php');
?>
<?php
// page_require_level(2);
$all_grupos_trabajo = find_all_grupos_trabajo();
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo las '.$page_title.'. ', 5); 
    page_require_level_exacto(7);
}
if ($nivel_user == 51) {
    page_require_level_exacto(51);
}
if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo las '.$page_title.'. ', 5); 
    page_require_level_exacto(53);
}
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user <51) :
    redirect('home.php');
endif;

if ($nivel_user > 51 && $nivel_user <53) :
    redirect('home.php');
endif;

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT * FROM poa";
$resultado = mysqli_query($conexion, $sql) or die;
$poa = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $poa[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($poa)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel;charset=UTF-8');
        header("Content-Disposition: attachment; filename=poa.xls");
        $filename = "poa.xls";
        $mostrar_columnas = false;

        foreach ($poa as $resolucion) {
            if (!$mostrar_columnas) {
                echo implode("\t", array_keys($resolucion)) . "\n";
                $mostrar_columnas = true;
            }
            echo implode("\t", array_values($resolucion)) . "\n";
        }
    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}

?>
<?php include_once('layouts/header.php'); ?>

<a href="solicitudes_tecnica.php" class="btn btn-success">Regresar</a><br><br>

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
                    <span>Mesas, Comités y Grupos de Trabajo</span>
                </strong>
                <?php if (($nivel_user <= 2) || ($nivel_user == 51)) : ?>
                    <a href="add_grupos_trabajo.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar Actividad</a>
                <?php endif; ?>
               
            </div>
        </div>

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr style="height: 10px;">
                        <th style="width: 8%;">Folio</th>
                        <th style="width: 10%;">Tipo</th>
                        <th style="width: 8%;">Mesa, Comité, Sistema o Mecanismo</th>
                        <th style="width: 8%;">No.Sesión</th>
                        <th style="width: 8%;">Fecha</th>
                        <th style="width: 8%;">Lugar</th>
                        <th style="width: 8%;">Miembros/ participantes</th>
                            <th style="width: 1%;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_grupos_trabajo as $a_grupos_trabajo) : ?>
                        <?php
                        $folio_editar = $a_grupos_trabajo['folio'];
                        $resultado = str_replace("/", "-", $folio_editar);
						$rel_grupos_trabajo_participantes = find_by_participantes($a_grupos_trabajo['id_grupos_trabajo']);
                        ?>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_grupos_trabajo['folio'])) ?></td>
                            <td style="text-align: center;"><?php echo remove_junk(ucwords($a_grupos_trabajo['tipo_accion'])) ?></td>
                            <td style="text-align: center;"><?php echo remove_junk(ucwords($a_grupos_trabajo['nombre_grupo'])) ?></td>
                            <td style="text-align: center;"><?php echo remove_junk(ucwords($a_grupos_trabajo['numero_sesion'])) ?></td>
							<td style="text-align: center;"><?php echo date_format(date_create($a_grupos_trabajo['fecha_sesion']),'d/m/Y') ?></td>
                            <td style="text-align: center;"><?php echo remove_junk(ucwords($a_grupos_trabajo['lugar_sesion'])) ?></td>
                            <td style="text-align: center;">
							
								<?php foreach ($rel_grupos_trabajo_participantes as $datos) : ?>
                                    <li><?php echo ucwords($datos['nombre_participante']); ?></li>
                                <?php endforeach; ?>			
							</td>
							                           
                                <td class="text-center">
                                    <div class="btn-group">
									 <a href="ver_info_grupos_trabajo.php?id=<?php echo (int)$a_grupos_trabajo['id_grupos_trabajo']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información completa">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
										<?php if (($nivel_user <= 2) || ($nivel_user == 51)) : ?>
                                        <a href="edit_grupos_trabajo.php?id=<?php echo (int)$a_grupos_trabajo['id_grupos_trabajo']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>
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