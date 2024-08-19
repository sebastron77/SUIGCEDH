<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Estadísticas de Redes Sociales';
require_once('includes/load.php');

$informe_redes_sociales = find_by_id('estadisticas_redes', (int)$_GET['id'], 'id_estadisticas_redes');

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
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['edit_redes_sociales'])) {

    if (empty($errors)) {
        $id = (int)$informe_redes_sociales['id_estadisticas_redes'];
        $ejercicio = remove_junk($db->escape($_POST['ejercicio']));
        $mes   = remove_junk($db->escape($_POST['mes']));
        $fb_alcance   = remove_junk($db->escape($_POST['fb_alcance']));
		$fb_visitas = remove_junk($db->escape($_POST['fb_visitas']));
        $fb_nuevos   = remove_junk($db->escape($_POST['fb_nuevos']));
        $ins_alcance   = remove_junk($db->escape($_POST['ins_alcance']));
		$ins_visitas = remove_junk($db->escape($_POST['ins_visitas']));
        $ins_nuevos   = remove_junk($db->escape($_POST['ins_nuevos']));
        $tk_vizualizacion_video   = remove_junk($db->escape($_POST['tk_vizualizacion_video']));
		$tk_vizualizacion_perfil = remove_junk($db->escape($_POST['tk_vizualizacion_perfil']));
        $tk_comentarios   = remove_junk($db->escape($_POST['tk_comentarios']));
        $tk_compartido   = remove_junk($db->escape($_POST['tk_compartido']));
		$tk_espectadores_unicos = remove_junk($db->escape($_POST['tk_espectadores_unicos']));
        $tk_like   = remove_junk($db->escape($_POST['tk_like']));
        $x_impresiones   = remove_junk($db->escape($_POST['x_impresiones']));
		$fecha_completa = $ejercicio."-".$mes."-"."01";
		
        $sql = "UPDATE estadisticas_redes SET 
				ejercicio='{$ejercicio}', 
				mes='{$mes}', 
				fecha_completa='{$fecha_completa}', 
				fb_alcance='{$fb_alcance}', 
				fb_visitas='{$fb_visitas}',
				fb_nuevos='{$fb_nuevos}', 
				ins_alcance='{$ins_alcance}', 
				ins_visitas='{$ins_visitas}', 
				ins_nuevos='{$ins_nuevos}', 
				tk_vizualizacion_video='{$tk_vizualizacion_video}', 
				tk_vizualizacion_perfil='{$tk_vizualizacion_perfil}', 
				tk_comentarios='{$tk_comentarios}', 
				tk_compartido='{$tk_compartido}', 
				tk_espectadores_unicos='{$tk_espectadores_unicos}', 
				tk_like='{$tk_like}', 
				x_impresiones='{$x_impresiones}' 
				WHERE id_estadisticas_redes='{$db->escape($id)}'";
       
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó un Difusión de Folio: -' . $informe_redes_sociales['folio'], 2);
            $session->msg('s', " La difusión con folio '" . $informe_redes_sociales['folio'] . "' ha sido acuatizado con éxito.");
            redirect('informe_redes_sociales.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron cambios a la informacion.');
            redirect('edit_redes_sociales.php?id=' . (int)$informe_redes_sociales['id_estadisticas_redes'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_redes_sociales.php?id=' . (int)$informe_redes_sociales['id_estadisticas_redes'], false);
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
                <span>Editar Estadísticas de Redes Sociales <?php echo $informe_redes_sociales['folio']; ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_redes_sociales.php?id=<?php echo (int)$informe_redes_sociales['id_estadisticas_redes']; ?>" enctype="multipart/form-data">
               <div class="row">
				<div class="col-md-6">
                <div class="form-group">
                    <label for="ejercicio" class="control-label">Ejercicio</label>
                    <select class="form-control" name="ejercicio" id="ejercicio">
                        <option value="">Escoge una opción</option>
                        <option <?php if ($informe_redes_sociales['ejercicio'] == '2022') echo 'selected="selected"'; ?> value="2022">2022</option>
                            <option <?php if ($informe_redes_sociales['ejercicio'] == '2023') echo 'selected="selected"'; ?> value="2023">2023</option>
                            <option <?php if ($informe_redes_sociales['ejercicio'] == '2024') echo 'selected="selected"'; ?> value="2024">2024</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="mes" class="control-label">Mes</label>
                    <select class="form-control" name="mes" id="mes">
                        <option value="">Escoge una opción</option>
                       <option <?php if ($informe_redes_sociales['mes'] == '1') echo 'selected="selected"'; ?> value="1">Enero</option>
                            <option <?php if ($informe_redes_sociales['mes'] == '2') echo 'selected="selected"'; ?> value="2">Febrero</option>
                            <option <?php if ($informe_redes_sociales['mes'] == '3') echo 'selected="selected"'; ?> value="3">Marzo</option>
                            <option <?php if ($informe_redes_sociales['mes'] == '4') echo 'selected="selected"'; ?> value="4">Abril</option>
                            <option <?php if ($informe_redes_sociales['mes'] == '5') echo 'selected="selected"'; ?> value="5">Mayo</option>
                            <option <?php if ($informe_redes_sociales['mes'] == '6') echo 'selected="selected"'; ?> value="6">Junio</option>
                            <option <?php if ($informe_redes_sociales['mes'] == '7') echo 'selected="selected"'; ?> value="7">Julio</option>
                            <option <?php if ($informe_redes_sociales['mes'] == '8') echo 'selected="selected"'; ?> value="8">Agosto</option>
                            <option <?php if ($informe_redes_sociales['mes'] == '9') echo 'selected="selected"'; ?> value="9">Septiembre</option>
                            <option <?php if ($informe_redes_sociales['mes'] == '10') echo 'selected="selected"'; ?> value="10">Octubre</option>
                            <option <?php if ($informe_redes_sociales['mes'] == '11') echo 'selected="selected"'; ?> value="11">Noviembre</option>
                            <option <?php if ($informe_redes_sociales['mes'] == '12') echo 'selected="selected"'; ?> value="12">Diciembre</option>
                    </select>
                </div>
            </div>
            </div>
            <div class="row">
				<h3 style="font-weight:bold;">
                    <span class="material-symbols-outlined">checklist</span>
                    Facebook
                </h3>                 
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fb_alcance">Alcance</label><br>
                            <input type="text" class="form-control" name="fb_alcance" value="<?php echo $informe_redes_sociales['fb_alcance']; ?>" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="fb_visitas">Visitas al Perfil</label><br>
                            <input type="text" class="form-control" name="fb_visitas" value="<?php echo $informe_redes_sociales['fb_visitas']; ?>" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="fb_nuevos">Nuevos Me Gusta</label><br>
                            <input type="text" class="form-control" name="fb_nuevos" value="<?php echo $informe_redes_sociales['fb_nuevos']; ?>" required>
                        </div>
                    </div>
					
            </div>
			
			<div class="row">
				<h3 style="font-weight:bold;">
                    <span class="material-symbols-outlined">checklist</span>
                    Instagram
                </h3>                 
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ins_alcance">Alcance</label><br>
                            <input type="text" class="form-control" name="ins_alcance" value="<?php echo $informe_redes_sociales['ins_alcance']; ?>" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="ins_visitas">Visitas al Perfil</label><br>
                            <input type="text" class="form-control" name="ins_visitas" value="<?php echo $informe_redes_sociales['ins_visitas']; ?>" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="ins_nuevos">Nuevos Me Gusta</label><br>
                            <input type="text" class="form-control" name="ins_nuevos" value="<?php echo $informe_redes_sociales['ins_nuevos']; ?>" required>
                        </div>
                    </div>					
            </div>
			
			<div class="row">
				<h3 style="font-weight:bold;">
                    <span class="material-symbols-outlined">checklist</span>
                    X
                </h3>                 
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="x_impresiones">Impresiones</label><br>
                            <input type="text" class="form-control" name="x_impresiones" value="<?php echo $informe_redes_sociales['x_impresiones']; ?>" required>
                        </div>
                    </div>					
            </div>

			
			<div class="row">
				<h3 style="font-weight:bold;">
                    <span class="material-symbols-outlined">checklist</span>
                    Tiktok
                </h3>                 
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tk_vizualizacion_video">Visualizaciones de Videos</label><br>
                            <input type="text" class="form-control" name="tk_vizualizacion_video" value="<?php echo $informe_redes_sociales['tk_vizualizacion_video']; ?>" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="tk_vizualizacion_perfil">Visualizaciones de Perfil</label><br>
                            <input type="text" class="form-control" name="tk_vizualizacion_perfil" value="<?php echo $informe_redes_sociales['tk_vizualizacion_perfil']; ?>" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="tk_like">Me Gusta</label><br>
                            <input type="text" class="form-control" name="tk_like" value="<?php echo $informe_redes_sociales['tk_like']; ?>" required>
                        </div>
                    </div>
                    </div>
						<div class="row">
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="tk_comentarios">Comentarios</label><br>
                            <input type="text" class="form-control" name="tk_comentarios" value="<?php echo $informe_redes_sociales['tk_comentarios']; ?>" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="tk_compartido">Veces Compartido</label><br>
                            <input type="text" class="form-control" name="tk_compartido" value="<?php echo $informe_redes_sociales['tk_compartido']; ?>" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="tk_espectadores_unicos">Espectadores Únicos</label><br>
                            <input type="text" class="form-control" name="tk_espectadores_unicos" value="<?php echo $informe_redes_sociales['tk_espectadores_unicos']; ?>" required>
                        </div>
                    </div>					
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="informe_redes_sociales.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="edit_redes_sociales" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>