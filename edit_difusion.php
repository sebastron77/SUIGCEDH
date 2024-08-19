<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Difusión';
require_once('includes/load.php');

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 15) {
    page_require_level(15);
}
if ($nivel > 2 && $nivel < 7) :
    redirect('home.php');
endif;
if ($nivel > 7  && $nivel < 15) :
    redirect('home.php');
endif;
if ($nivel > 15) :
    redirect('home.php');
endif;

$difusiones = find_all_order('cat_tipo_difusion', 'id_cat_tipo_dif');
$difusion = find_by_id('difusion', (int)$_GET['id'], 'id_difusion');
$inticadores_pat = find_all_pat(46);
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_difusion'])) {

    if (empty($errors)) {
        $id = (int)$difusion['id_difusion'];
        $fecha = remove_junk($db->escape($_POST['fecha']));
        $tipo_difusion   = remove_junk($db->escape($_POST['tipo_difusion']));
        $tema   = remove_junk($db->escape($_POST['tema']));
        $link   = remove_junk($db->escape($_POST['link']));
        $entrevistado = remove_junk($db->escape($_POST['entrevistado']));
        $medio = remove_junk($db->escape($_POST['medio']));
		$id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));

        $year = date("Y");
        // Se crea el folio de canalizacion
        $folio_editar = $difusion['folio'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/difusiones_CS/' . $resultado;

        $name = $_FILES['adjunto']['name'];
        $size = $_FILES['adjunto']['size'];
        $type = $_FILES['adjunto']['type'];
        $temp = $_FILES['adjunto']['tmp_name'];

        if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else{
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }
        if ($name != '') {
            $sql = "UPDATE difusion SET fecha='{$fecha}', tipo_difusion='{$tipo_difusion}', tema='{$tema}', link='{$link}', pdf='{$name}', 
            entrevistado='{$entrevistado}', id_indicadores_pat='{$id_indicadores_pat}',medio='{$medio}' WHERE id_difusion='{$db->escape($id)}'";
        }
        if ($name == '') {
            $sql = "UPDATE difusion SET fecha='{$fecha}', tipo_difusion='{$tipo_difusion}', tema='{$tema}', link='{$link}', entrevistado='{$entrevistado}', id_indicadores_pat='{$id_indicadores_pat}',medio='{$medio}' WHERE id_difusion='{$db->escape($id)}'";
        }

        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó un Difusión de Folio: -' . $difusion['folio'], 2);
            $session->msg('s', " La difusión con folio '" . $difusion['folio'] . "' ha sido acuatizado con éxito.");
            redirect('difusion.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron cambios a la informacion.');
            redirect('edit_difusion.php?id=' . (int)$difusion['id_difusion'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_difusion.php', false);
    }
}
?>
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar Difusión</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_difusion.php?id=<?php echo (int)$difusion['id_difusion']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha<span style="color:red;font-weight:bold"> *</span></label><br>
                            <input type="date" class="form-control" name="fecha" value="<?php echo $difusion['fecha'] ?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_difusion">Tipo Difusión<span style="color:red;font-weight:bold"> *</span></label><br>
                            <select class="form-control" name="tipo_difusion" required>
                                <option value="">Selecciona un tipo</option>
                                <?php foreach ($difusiones as $tipo_dif) : ?>
                                    <option <?php if ($difusion['tipo_difusion'] === $tipo_dif['id_cat_tipo_dif']) echo 'selected="selected"'; ?> value="<?php echo $tipo_dif['id_cat_tipo_dif']; ?>"><?php echo ucwords($tipo_dif['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tema">Tema<span style="color:red;font-weight:bold"> *</span></label><br>
                            <input type="text" class="form-control" name="tema" value="<?php echo $difusion['tema'] ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="link">Link</label>
                            <input type="text" class="form-control" name="link" value="<?php echo $difusion['link'] ?>">
                        </div>
                    </div>
                </div>
                <?php
                $folio_editar = $difusion['folio'];
                $resultado = str_replace("/", "-", $folio_editar);
                ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="adjunto">PDF</label>
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto" value="uploads/difusion_CS/<?php echo $resultado . '/' . $difusion['pdf']; ?>">
                            <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($difusion['pdf']); ?><?php ?></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="entrevistado">Entrevistado</label>
                            <input type="text" class="form-control" name="entrevistado" value="<?php echo $difusion['entrevistado'] ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="medio">Medio<span style="color:red;font-weight:bold"> *</span></label>
                            <input type="text" class="form-control" name="medio" value="<?php echo $difusion['medio'] ?>" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" >
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option <?php if ($difusion['id_indicadores_pat'] == $datos['id_indicadores_pat']) echo 'selected="selected"'; ?>  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group clearfix">
                        <a href="difusion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="edit_difusion" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>