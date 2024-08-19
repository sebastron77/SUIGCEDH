<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Eventos';
require_once('includes/load.php');
?>
<?php
// page_require_level(4);
$a_evento = find_by_id('eventos', (int)$_GET['id'], 'id_evento');
$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];

page_require_level(53);
if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$a_evento['folio'], 5);   
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
                    <span>Información del evento <?php echo $a_evento['folio'] ?></span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 3%;">Folio</th>
                            <th style="width: 10%;">Nombre del Evento</th>
                            <th style="width: 8%;">Tipo Evento</th>
                            <th style="width: 8%;">¿Quién lo solicita?</th>
                            <th style="width: 4%;">Fecha</th>
                            <th style="width: 1%;">Hora</th>
                            <th style="width: 5%;">Lugar</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_evento['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_evento['nombre_evento'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_evento['tipo_evento'])) ?></td>
                            <td><?php echo remove_junk((ucwords($a_evento['quien_solicita']))) ?></td>
                            <td><?php echo remove_junk(ucwords($a_evento['fecha'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_evento['hora'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_evento['lugar'])) ?></td>

                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 1%;">Asistentes</th>
                            <th style="width: 5%;">Modalidad</th>
                            <th style="width: 3%;">Depto./ Org.</th>
                            <th style="width: 5%;">¿Quién asiste?</th>
                            <th style="width: 2%;">Invitación</th>
                            <!-- <th style="width: 5%;">Constancia</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords($a_evento['no_asistentes'])) ?></td>
                            <td><?php echo remove_junk((ucwords($a_evento['modalidad']))) ?></td>
                            <td><?php echo remove_junk((ucwords($a_evento['depto_org']))) ?></td>
                            <td><?php echo remove_junk((ucwords($a_evento['quien_asiste']))) ?></td>
                            <?php
                            $folio_editar = $a_evento['folio'];
                            $resultado = str_replace("/", "-", $folio_editar);
                            ?>
                            <td><a target="_blank" style="color:#3D94FF" href="uploads/eventos/invitaciones/<?php echo $resultado . '/' . $a_evento['invitacion']; ?>"><?php echo $a_evento['invitacion']; ?></a></td>
                        </tr>
                    </tbody>
                </table>
                <a href="eventos.php?a=<?php echo $a_evento['area_creacion']?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>