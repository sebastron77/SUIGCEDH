<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Estadísticas de Redes Sociales';
require_once('includes/load.php');

$id_folio = last_id_folios();
$areas = find_all_order('area', 'nombre_area');

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

if (isset($_POST['add_redes_sociales'])) {

    if (empty($errors)) {

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

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int) $nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int) $nuevo['contador'] + 1);
            }
        }

        $year = date("Y");
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-RDS';

        $query = "INSERT INTO estadisticas_redes (";
        $query .= "folio, ejercicio	,mes,fecha_completa,fb_alcance,fb_visitas,fb_nuevos,ins_alcance,ins_visitas,ins_nuevos,tk_vizualizacion_video,tk_vizualizacion_perfil,tk_comentarios,tk_compartido,tk_espectadores_unicos,tk_like,x_impresiones,id_usuario_creador,fecha_creacion";
        $query .= ") VALUES (";
        $query .= "'{$folio}', '{$ejercicio}','{$mes}','{$fecha_completa}','{$fb_alcance}','{$fb_visitas}','{$fb_nuevos}','{$ins_alcance}','{$ins_visitas}','{$ins_nuevos}','{$tk_vizualizacion_video}','{$tk_vizualizacion_perfil}','{$tk_comentarios}','{$tk_compartido}','{$tk_espectadores_unicos}','{$tk_like}','{$x_impresiones}','{$id_user}',NOW());";        

        $query2 = "INSERT INTO folios (";
        $query2 .= "folio, contador";
        $query2 .= ") VALUES (";
        $query2 .= " '{$folio}','{$no_folio1}'";
        $query2 .= ")";

        if ($db->query($query) && $db->query($query2)) {
            //sucess
            insertAccion($user['id_user'], '"' . $user['username'] . '" dio de alta Estadísticas de Redes Sociales con Folio: -' . $folio . '-.', 1);
            $session->msg('s', " La Estadísticas de Redes Sociales con folio '{$folio}' ha sido agregada con éxito.");
            redirect('informe_redes_sociales.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la Estadísticas de Redes Sociales.');
            redirect('add_redes_sociales.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_redes_sociales.php', false);
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
                <span>Agregar Estadísticas de Redes Sociales</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_redes_sociales.php" enctype="multipart/form-data">
                <div class="row">
				<div class="col-md-6">
                <div class="form-group">
                    <label for="ejercicio" class="control-label">Ejercicio</label>
                    <select class="form-control" name="ejercicio" id="ejercicio">
                        <option value="">Escoge una opción</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024" selected="selected">2024</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="mes" class="control-label">Mes</label>
                    <select class="form-control" name="mes" id="mes">
                        <option value="">Escoge una opción</option>
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
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
                            <input type="text" class="form-control" name="fb_alcance" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="fb_visitas">Visitas al Perfil</label><br>
                            <input type="text" class="form-control" name="fb_visitas" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="fb_nuevos">Nuevos Me Gusta</label><br>
                            <input type="text" class="form-control" name="fb_nuevos" required>
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
                            <input type="text" class="form-control" name="ins_alcance" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="ins_visitas">Visitas al Perfil</label><br>
                            <input type="text" class="form-control" name="ins_visitas" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="ins_nuevos">Nuevos Me Gusta</label><br>
                            <input type="text" class="form-control" name="ins_nuevos" required>
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
                            <input type="text" class="form-control" name="x_impresiones" required>
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
                            <input type="text" class="form-control" name="tk_vizualizacion_video" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="tk_vizualizacion_perfil">Visualizaciones de Perfil</label><br>
                            <input type="text" class="form-control" name="tk_vizualizacion_perfil" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="tk_like">Me Gusta</label><br>
                            <input type="text" class="form-control" name="tk_like" required>
                        </div>
                    </div>
                    </div>
						<div class="row">
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="tk_comentarios">Comentarios</label><br>
                            <input type="text" class="form-control" name="tk_comentarios" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="tk_compartido">Veces Compartido</label><br>
                            <input type="text" class="form-control" name="tk_compartido" required>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="tk_espectadores_unicos">Espectadores Únicos</label><br>
                            <input type="text" class="form-control" name="tk_espectadores_unicos" required>
                        </div>
                    </div>					
            </div>
			
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="informe_redes_sociales.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="add_redes_sociales" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>