<?php
$page_title = 'Resumen General del Trabajador';
require_once('includes/load.php');
header('Content-Type: text/html; charset=UTF-8');

$e_detalle = find_by_id('detalles_usuario', (int) $_GET['id'], 'id_det_usuario');
$hist = find_all_hist_exp_int((int) $_GET['id']);
$cargos = find_all('cargos');
$generos = find_all('cat_genero');
$areas = find_all('area');
$puestos = find_all('cat_puestos');
$grados = find_all('cat_escolaridad');
$exp_laboral = find_all_by('rel_curriculum_laboral', (int) $_GET['id'], 'id_detalle_usuario');
$exp_academico = find_all_by('rel_curriculum_academico', (int) $_GET['id'], 'id_rel_detalle_usuario');
$user = current_user();
$nivel_user = $user['user_level'];

page_require_level(53);
if ($nivel_user == 7 || $nivel_user == 53) {
    insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de ' . $page_title . ' . Folio:' . $e_detalle['nombre'] . ' ' . $e_detalle['apellidos'], 5);
}

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<script>
    function generarPDF(id) {
        // Realizar una solicitud AJAX al servidor al hacer clic en el botón
        var xhr = new XMLHttpRequest();
        var declaracionID = id;
        xhr.open("GET", "hist_detalle_pdf.php?id=" + declaracionID, true);
        xhr.responseType = "blob"; // La respuesta será un archivo binario (el PDF)
        xhr.onload = function() {
            if (this.status === 200) {
                // Crear un enlace para descargar el PDF
                var blob = this.response;
                var link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                link.download = "resumen.pdf"; // Nombre del archivo PDF
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        };
        xhr.send();
    }
</script>
<style>
    .cont {
            display: flex;
            width: 100%;
        }
        .table {
            width: 50%;
            margin: 0;
            padding: 0;
        }
        .table td, .table th {
            padding: 5px;
        }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Resumen General de
                        <?php echo remove_junk(ucwords($e_detalle['nombre'])) ?>
                        <?php echo remove_junk(ucwords($e_detalle['apellidos'])) ?>
                    </span>
                </strong>
                <div style="display: flex; justify-content: center; position: absolute; width: 100%; top: 10px;">
                    <button class="btn btn-md btn-danger" style="font-size: 14px;" id="pdf" data-declaracion-id="<?php echo (int)$_GET['id'] ?>" onclick="generarPDF(<?php echo (int)$_GET['id'] ?>)">Descargar Resumen Trabajador
                    </button>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="cont">
                        <table class="table" style="width: 100%">
                            <thead>
                                <tr style="height: 10px;">
                                    <th style=" width: 1%; text-align:center; font-size: 17px;">- DATOS PERSONALES DEL TRABAJADOR -</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <span style="font-weight: bold;">Nombre:</span>
                                        <?php echo remove_junk($e_detalle['nombre'] . ' ' . $e_detalle['apellidos']) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-weight: bold;">Género:</span>
                                        <?php
                                        foreach ($generos as $genero) :
                                            if ($genero['id_cat_gen'] === $e_detalle['id_cat_gen'])
                                                echo $genero['descripcion'];
                                        endforeach;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-weight: bold;">Correo:</span>
                                        <?php echo remove_junk($e_detalle['correo']) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-weight: bold;">Teléfono:</span>
                                        <?php echo remove_junk($e_detalle['telefono']) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-weight: bold;">CURP:</span>
                                        <?php echo remove_junk($e_detalle['curp']) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-weight: bold;">RFC:</span>
                                        <?php echo remove_junk($e_detalle['rfc']) ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table" style="width: 100%">
                            <thead>
                                <tr style="height: 10px;">
                                    <th style=" width: 1%; text-align:center; font-size: 17px;">- DATOS INTERNOS DEL TRABAJADOR -</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <span style="font-weight: bold;">Puesto:</span>
                                        <?php
                                        foreach ($puestos as $puesto) :
                                            if ($puesto['id_cat_puestos'] === $e_detalle['id_cat_puestos'])
                                                echo $puesto['descripcion'];
                                        endforeach;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-weight: bold;">Clave:</span>
                                        <?php echo remove_junk($e_detalle['clave']) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-weight: bold;">Nivel Puesto:</span>
                                        <?php echo remove_junk($e_detalle['niv_puesto']) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-weight: bold;">Área:</span>
                                        <?php
                                        foreach ($areas as $area) :
                                            if ($area['id_area'] === $e_detalle['id_area'])
                                                echo $area['nombre_area'];
                                        endforeach;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-weight: bold;">Monto Bruto:</span>
                                        <?php echo remove_junk($e_detalle['monto_bruto']) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-weight: bold;">Monto Neto:</span>
                                        <?php echo remove_junk($e_detalle['monto_neto']) ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div><br>
                <div class="row">
                    <span style="font-weight: bold; color: #212529; text-align:center; margin-bottom: 5px; font-size: 17px;">- HISTORIAL INTERNO DE PUESTOS -</span>
                    <?php foreach ($hist as $h) : ?>
                        <div class="col-md-3">
                            <table class="table" style="width: 100%">
                                <tbody>
                                    <tr>
                                        <td>
                                            <span style="font-weight: bold;">Puesto:</span>
                                            <span><?php echo $h['puestos'] ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-weight: bold;">Clave:</span>
                                            <?php echo remove_junk($h['clave']) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-weight: bold;">Nivel Puesto:</span>
                                            <?php echo remove_junk($h['niv_puesto']) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-weight: bold;">Área:</span>
                                            <?php echo remove_junk($h['nombre_area']) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-weight: bold;">Fecha Inicio:</span>
                                            <?php echo remove_junk($h['fecha_inicio']) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-weight: bold;">Fecha Conclusión:</span>
                                            <?php echo remove_junk($h['fecha_conclusion']) ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table><br>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="row">
                    <span style="font-weight: bold; color: #212529; text-align:center; margin-bottom: 5px; font-size: 17px;">- EXPERIENCIA LABORAL -</span>
                    <?php foreach ($exp_laboral as $e_laboral) : ?>
                        <div class="col-md-3">
                            <table class="table" style="width: 100%">
                                <tbody>
                                    <tr>
                                        <td>
                                            <span style="font-weight: bold;">Puesto:</span>
                                            <span><?php echo $e_laboral['puesto'] ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-weight: bold;">Institución:</span>
                                            <?php echo remove_junk($e_laboral['institucion']) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-weight: bold;">Fecha de Inicio:</span>
                                            <?php if ($e_laboral['inicio_t'] != '') : ?>
                                                <span><?php echo remove_junk(ucwords($e_laboral['inicio_t'])) ?></span>
                                            <?php else : ?>
                                                <span><?php echo remove_junk(ucwords($e_laboral['inicio'])) ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-weight: bold;">Fecha de Conclusión:</span>
                                            <?php if ($e_laboral['conclusion_t'] != '') : ?>
                                                <span><?php echo remove_junk(ucwords($e_laboral['conclusion_t'])) ?></span>
                                            <?php else : ?>
                                                <span><?php echo remove_junk(ucwords($e_laboral['conclusion'])) ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table><br>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="row">
                    <span style="font-weight: bold; color: #212529; text-align:center; margin-bottom: 5px; font-size: 17px;">- EXPERIENCIA ACADÉMICA -</span>
                    <?php foreach ($exp_academico as $e_academico) : ?>
                        <div class="col-md-3">
                            <table class="table" style="width: 100%">
                                <tbody>
                                    <tr>
                                        <td>
                                            <span style="font-weight: bold;">Estudios:</span>
                                            <span><?php echo $e_academico['estudios'] ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-weight: bold;">Institución:</span>
                                            <?php echo remove_junk($e_academico['institucion']) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-weight: bold;">Grado:</span>
                                            <?php
                                            foreach ($grados as $grado) :
                                                if ($grado['id_cat_escolaridad'] === $e_academico['grado'])
                                                    echo $grado['descripcion'];
                                            endforeach;
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-weight: bold;">Cédula Profesional:</span>
                                            <?php echo remove_junk($e_academico['cedula_profesional']) ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table><br>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <a href="detalles_usuario.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>