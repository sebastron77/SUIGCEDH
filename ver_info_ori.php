<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Orientación';
require_once('includes/load.php');
?>
<?php
$e_detalle = find_by_id_orientacion((int)$_GET['id']);
$acuerdos_quejas = find_acuerdo_orican((int) $_GET['id']);
//$all_detalles = find_all_detalles_busqueda($_POST['consulta']);
$user = current_user();
$nivel = $user['user_level'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 5) {
    page_require_level_exacto(5);
}
if ($nivel == 7) {
    page_require_level_exacto(7);
}
if ($nivel == 19) {
    page_require_level_exacto(19);
}
if ($nivel == 21) {
    page_require_level_exacto(21);
}

if ($nivel > 2 && $nivel < 5) :
    redirect('home.php');
endif;
if ($nivel > 5 && $nivel < 7) :
    redirect('home.php');
endif;
if ($nivel > 7 && $nivel < 19) :
    redirect('home.php');
endif;
if ($nivel > 19 && $nivel < 21) :
    redirect('home.php');
endif;

if ($nivel == 7 || $nivel == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$e_detalle['folio'], 5);   
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
                    <span>Información de Orientación</span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 1%;" class="text-center">Folio</th>
                            <th style="width: 3%;" class="text-center">Fecha de Creación</th>
                            <th style="width: 3%;" class="text-center">Medio de presentación</th>
                            <th style="width: 7%;" class="text-center">Correo</th>
                            <!--SE PUEDE AGREGAR UN LINK QUE TE LLEVE A EDITAR EL USUARIO, COMO EN EL PANEL DE CONTROL EN ULTIMAS ASIGNACIONES-->
                            <th style="width: 5%;" class="text-center">Nombre Completo</th>
                            <th style="width: 3%;" class="text-center">Nivel de Estudios</th>
                            <th style="width: 5%;" class="text-center">Ocupación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['folio'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['creacion'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['med'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['correo_electronico'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($e_detalle['nombre_completo']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($e_detalle['cesc']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($e_detalle['ocup']))) ?></td>

                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th style="width: 1%;" class="text-center">Edad</th>
                            <th style="width: 1%;" class="text-center">Telefono</th>
                            <th style="width: 1%;" class="text-center">Extensión</th>
                            <th style="width: 1%;" class="text-center">Género</th>
                            <th style="width: 3%;" class="text-center">Grupo Vulnerable</th>
                            <th style="width: 2%;" class="text-center">Lengua</th>
                            <th style="width: 5%;" class="text-center">Autoridad señalada como responsable</th>
                            <th style="width: 5%;" class="text-center">Calle-Num.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['edad'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['telefono'])) ?></td>
                            <td class="text-center"><?php echo remove_junk($e_detalle['extension']) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['gen'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['grupo'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['lengua'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['aut'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['calle_numero'])) ?></td>

                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th style="width: 5%;" class="text-center">Colonia</th>
                            <th style="width: 1%;" class="text-center">Código Postal</th>
                            <th style="width: 2%;" class="text-center">Municipio</th>
                            <th style="width: 2%;" class="text-center">Localidad</th>
                            <th style="width: 2%;" class="text-center">Entidad</th>
                            <th style="width: 1%;" class="text-center">Nacionalidad</th>
                            <th style="width: 5%;" class="text-center">Observaciones</th>
                            <th style="width: 5%;" class="text-center">Adjunto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords(($e_detalle['colonia']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['codigo_postal'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($e_detalle['municipio']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($e_detalle['municipio_localidad']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($e_detalle['ent']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['nac'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_detalle['observaciones'])) ?></td>
                            <?php
                            $folio_editar = $e_detalle['folio'];
                            $resultado = str_replace("/", "-", $folio_editar);
                            ?>
                            <td class="text-center"><a target="_blank" style="color:#0094FF" href="uploads/orientacioncanalizacion/orientacion/<?php echo $resultado . '/' . $e_detalle['adjunto']; ?>"><?php echo $e_detalle['adjunto']; ?></a></td>
                        </tr>
                    </tbody>
                </table>

				<table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th style="width: 100%;" class="text-center">Expediente</th>
                        </tr>
                    </thead>                   
                </table>
				
				<div class="row">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tipo Acuerdo</th>
                                <th scope="col">Fecha Acuerdo</th>
                                <th scope="col">Documentos</th>
                                <th scope="col">Síntesis</th>
                                <th scope="col">¿Es público?</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $folio_editar = $e_detalle['folio'];
                            $resultado = str_replace("/", "-", $folio_editar);
                            $num = 1;
                            foreach ($acuerdos_quejas as $datos) :
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $num++ ?></th>
                                    <td><?php echo remove_junk(($datos['tipo_acuerdo'])) ?></td>
                                    <td><?php echo date("d-m-Y", strtotime(remove_junk($datos['fecha_acuerdo']))) ?></td>
                                    <td>
                                        &nbsp;&nbsp;&nbsp;
                                        <?php if (!$datos['acuerdo_adjunto'] == "") { ?>
                                            <a target="_blank" href="uploads/orientacioncanalizacion/orientacion/<?php echo $resultado . '/Acuerdos/' . $datos['acuerdo_adjunto']; ?>" title="Ver Acuerdo">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                                    <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z" />
                                                </svg>
                                            </a>
                                        <?php } ?>
                                        <?php if (!$datos['acuerdo_adjunto_publico'] == "") { ?>
                                            &nbsp;&nbsp;&nbsp;
                                            <a target="_blank" href="uploads/orientacioncanalizacion/orientacion/<?php echo $resultado . '/Acuerdos/' . $datos['acuerdo_adjunto_publico']; ?>" title="Ver Versión Publica del Acuerdo">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-medical" viewBox="0 0 16 16">
                                                    <path d="M7.5 5.5a.5.5 0 0 0-1 0v.634l-.549-.317a.5.5 0 1 0-.5.866L6 7l-.549.317a.5.5 0 1 0 .5.866l.549-.317V8.5a.5.5 0 1 0 1 0v-.634l.549.317a.5.5 0 1 0 .5-.866L8 7l.549-.317a.5.5 0 1 0-.5-.866l-.549.317V5.5zm-2 4.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z" />
                                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                                </svg>
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo remove_junk(($datos['sintesis_documento'])) ?></td>
                                    <td><?php echo remove_junk(($datos['publico'] == 1 ? "Sí" : "No")) ?></td>
                                    

                                </tr>
                            <?php
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
				
				
                <a href="orientaciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>