<?php
include("includes/config.php");
$page_title = 'Resumen General del Trabajador';
$results = '';
require_once('includes/load.php');
require_once('dompdf/autoload.inc.php');


use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$dompdf = new DOMPDF($options);
ob_start(); //Linea para que deje descargar el PDF

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8">
    <title>Reporte</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
</head>
<style>
    .table-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .table {
        /* Ajustar el ancho para dejar espacio entre las tablas */
        width: calc(50% - 2px);
        /* Espacio entre las tablas */
        margin-bottom: 20px;
        border-collapse: collapse;
        border: 1px solid black;
        width: 50%;
    }

    .table th,
    .table td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }

    .table th {
        background-color: #f2f2f2;
        text-align: center;
        font-size: 14px;
        padding: 8px;
    }

    #header {
        position: fixed;
        margin-top: -30px; 
        margin-left: 20px;
    }
</style>

<body>
    <div id="header" style="margin-bottom: 20%;">
        <div style="background: #D8D8D8; width: 100%; height: 30px;">
            <img src="http://localhost/suigcedh/medios/Logo_Azul.jpg" width="180" height="80">
        </div>
    </div><br><br><br>
    
    <div class="row">
        <div class="col-md-12">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr style="height: 10px;">
                            <th style=" width: 1%; text-align:center; font-size: 14px;">- DATOS PERSONALES DEL TRABAJADOR -</th>
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
                <table class="table" align="right">
                    <thead>
                        <tr style="height: 10px;">
                            <th style=" width: 1%; text-align:center; font-size: 14px;">- DATOS INTERNOS DEL TRABAJADOR -</th>
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
            </div><br><br>
            <div style="text-align:center;">
                <span style="font-weight: bold; color: #212529; margin-bottom: 5px; font-size: 17px;">- HISTORIAL INTERNO DE PUESTOS -</span><br><br>
            </div>
            <?php foreach ($hist as $h) : ?>
                <table class="table" style=" margin: 0 auto;">
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
                </table><br><br>
            <?php endforeach; ?>

            <div class="row">
                <div style="text-align:center;">
                    <span style="font-weight: bold; color: #212529; text-align:center; margin-bottom: 5px; font-size: 17px;">- EXPERIENCIA LABORAL -</span>
                </div><br>
                <?php foreach ($exp_laboral as $e_laboral) : ?>
                    <div class="col-md-3">
                        <table class="table" style="margin: 0 auto; width: 50%;">
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
                <div style="text-align:center;">
                    <span style="font-weight: bold; color: #212529; text-align:center; margin-bottom: 5px; font-size: 17px;">- EXPERIENCIA ACADÉMICA -</span>
                </div><br>
                <?php foreach ($exp_academico as $e_academico) : ?>
                    <div class="col-md-3">
                        <table class="table" style="margin: 0 auto; width: 50%;">
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
        </div>
    </div>
</body>

</html>
<?php if (isset($db)) {
    $db->db_disconnect();
} ?>

<?php
$dompdf->loadHtml(ob_get_clean());
$dompdf->setPaper('letter', 'landscape');
$dompdf->render();
$pdf = $dompdf->output();
$filename = "resumen.pdf";
file_put_contents($filename, $pdf);
$dompdf->stream($filename);
?>