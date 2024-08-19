<?php
$page_title = 'Correspondencia';
require_once('includes/load.php');
?>
<?php
$a_correspondencia = find_by_id_env_correspondencia((int)$_GET['id']);
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

page_require_level(53);
if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$a_correspondencia['folio'], 5);   
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
                    <span>Ver Información de Correspondencia <?php echo $a_correspondencia['folio'] ?></span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 3%;">Folio</th>
                            <th class="text-center" style="width: 4%;">Fecha de Emisión</th>
                            <th class="text-center" style="width: 10%;">Asunto</th>
                            <th class="text-center" style="width: 4%;">Medio de envío</th>
                            <th class="text-center" style="width: 10%;">Área a la que se turna</th>
                            <th class="text-center" style="width: 5%;">Fecha en que se turna</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_correspondencia['folio'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_correspondencia['fecha_emision'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_correspondencia['asunto'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_correspondencia['medio_envio'])) ?></td>
                            <td><?php echo remove_junk(ucwords(($a_correspondencia['nombre_area']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($a_correspondencia['fecha_en_que_se_turna']))) ?></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 10%;">Fecha en que se espera respuesta</th>
                            <th class="text-center" style="width: 5%;">Tipo de trámite</th>
                            <th class="text-center" style="width: 8%;">Oficio Enviado</th>
                            <th class="text-center" style="width: 25%;">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords(($a_correspondencia['fecha_espera_respuesta']))) ?></td>
                            <td><?php echo remove_junk(ucwords(($a_correspondencia['tipo_tramite']))) ?></td>
                            <?php
                            $folio_editar = $a_correspondencia['folio'];
                            $resultado = str_replace("/", "-", $folio_editar);
                            ?>
                            <td><a target="_blank" style="color: #3D94FF;" href="uploads/correspondencia_interna/<?php echo $resultado . '/' . $a_correspondencia['oficio_enviado']; ?>"><?php echo $a_correspondencia['oficio_enviado']; ?></a></td>
                            <td><?php echo remove_junk(ucwords(($a_correspondencia['observaciones']))) ?></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th class="text-center" style="width: 10%;">Acción Realizada</th>
                            <th class="text-center" style="width: 5%;">Fecha Seguimiento</th>
                            <th class="text-center" style="width: 10%;">Oficio de respuesta</th>
                            <th class="text-center" style="width: 10%;">Quién realizó</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_correspondencia['accion_realizada'])) ?></td>
                            <td><?php echo remove_junk(ucwords(($a_correspondencia['fecha']))) ?></td>                            
                            <?php
                            $folio_editar = $a_correspondencia['folio'];
                            $resultado = str_replace("/", "-", $folio_editar);
                            ?>
                            <td><a target="_blank" style="color: #3D94FF;"href="uploads/correspondencia_interna/<?php echo $resultado . '/' . $a_correspondencia['oficio_respuesta']; ?>"><?php echo $a_correspondencia['oficio_respuesta']; ?></a></td>
                            <td><?php echo remove_junk(ucwords(($a_correspondencia['nombre'] . " " . $a_correspondencia['apellidos']))) ?></td>
                        </tr>
                    </tbody>
                </table>
				 <a href="javascript:history.back()" class="btn btn-success">Regresar</a><br><br>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>