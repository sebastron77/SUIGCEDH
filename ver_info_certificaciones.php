<?php
$page_title = 'Certificación';
require_once('includes/load.php');
?>
<?php

$e_curso = find_by_id_certificacion((int)$_GET['id']);

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];
$folio_editar = $e_curso['folio'];
$resultado = str_replace("/", "-", $folio_editar);
$rel_cursos_publico = find_all_elemcursos((int)$_GET['id'],'id_certificaciones','cat_publico_objetivo','rel_cetificaciones_publico','id_cat_publico_objetivo');
$rel_cursos_grupos_vulnerables = find_all_elemcursos((int)$_GET['id'],'id_certificaciones','cat_grupos_vuln','rel_certificaciones_grupos_vulnerables','id_cat_grupo_vuln');

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 6) {
    page_require_level_exacto(6);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}

if ($nivel_user > 3 && $nivel_user < 6) :
    redirect('home.php');
endif;
if ($nivel_user > 7) :
    redirect('home.php');
endif;

if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$folio_editar, 5);   
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
                    <span>Certificación  <?php echo $e_curso['folio'] ?></span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 3%;">Folio</th>
                            <th style="width: 3%;">Eje Estrategico</th>
                            <th style="width: 5%;">Agenda</th>
                            <th style="width: 3%;">Nombre Certificado</th>
                            <th style="width: 3%;">Fecha de Inicio de la Certificación</th>
                            <th style="width: 3%;">Nombre de la Institución o dependencia que emite la certificación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($e_curso['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_curso['nombre_eje'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_curso['nommbre_agenda'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_curso['nombre_certificacion'])) ?></td>
                            <td><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($e_curso['fecha_inicio_proceso'])))) ?></td>
                            <td><?php echo remove_junk(ucwords($e_curso['emisor_certificacion'])) ?></td>
                        </tr>
                    </tbody>
                </table>

				<table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 3%;">Contacto de la Institución o dependencia certificcadora</th>
                            <th style="width: 3%;">Avance Proceso</th>
                            <th style="width: 5%;">Fecha de Actualización del Avance</th>
                            <th style="width: 3%;">Área Responsable</th>
                            <th style="width: 3%;">Nombre del Responsable</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo remove_junk(ucwords($e_curso['contacto_certificacion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_curso['avance_proceso']))."%" ?></td>
                            <td><?php echo date("d-m-Y", strtotime(remove_junk(ucwords($e_curso['fecha_actualizacion_avance'])))) ?></td>
                            <td><?php echo remove_junk(ucwords($e_curso['nombre_area'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_curso['nombre_responsable'])) ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 3%;">Ficha Técnica</th>
                            <th style="width: 3%;">Expediente Técnico</th>
                            <th style="width: 5%;">Objetivo Certificación</th>
                            <th style="width: 3%;">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>                            
                            <td style="text-align: center;">
							<?php if($e_curso['ficha_tecnica'] !=''){?>
                                <a target="_blank" style="color: red;" href="uploads/cursosdiplomados/<?php echo $resultado . '/' . $e_curso['ficha_tecnica']; ?>">
								 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                        <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z" />
                                    </svg>
                                </a>
							<?php }?>
							</td>
							<td style="text-align: center;">
							<?php if($e_curso['expediente_tecnico'] !=''){?>
                                <a target="_blank" style="color: red;" href="uploads/cursosdiplomados/<?php echo $resultado . '/' . $e_curso['expediente_tecnico']; ?>">
								 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                        <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z" />
                                    </svg>
                                </a>
							<?php }?>
							</td>
                            <td><?php echo remove_junk(ucwords($e_curso['objetivo_certificacion'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_curso['observaciones'])) ?></td>                           
                        </tr>
                    </tbody>
                </table>
				<table class="table table-bordered table-striped">
						<tr>
							<td style="width: 50%;">
								 <h3 style="font-weight:bold;">
									<span class="material-symbols-outlined">checklist</span>
									Público Objetivo
								</h3>
							</td>
							<td style="width: 50%; <?php echo (!$rel_cursos_grupos_vulnerables? "display:none;":"display:block;")?>" id="gv1">
								<h3 style="font-weight:bold; " >
									<span class="material-symbols-outlined">checklist</span>
									Grupo Vulnerable
								</h3>
							</td>
						</tr>
						<tr>
							<td>
								<ul>
								<?php foreach ($rel_cursos_publico as $elementos) : ?>
									<li><?php echo $elementos['nombre']; ?></li>
								<?php  endforeach; ?>
								</ul>
							</td>
							<td>
								<ul>
								<?php foreach ($rel_cursos_grupos_vulnerables as $elementos) : ?>
									<li><?php echo $elementos['nombre']; ?></li>
								<?php  endforeach; ?>
								</ul>
							</td>
						</tr>
                </table>
				
							
<br>
<br>
                
                <a href="certificaciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>