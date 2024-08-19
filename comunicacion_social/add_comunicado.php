<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Comunicado Presnsa';
require_once('includes/load.php');

$id_folio = last_id_folios();
$id_comunicados = last_id_table('comunicados', 'id_comunicados');

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
if ($nivel >7  && $nivel < 15) :
    redirect('home.php');
endif;
if ($nivel > 15 ) :
    redirect('home.php');
endif;
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_comunicado'])) {

    if (empty($errors)) {

        $fecha_publicacion = remove_junk($db->escape($_POST['fecha_publicacion']));
        $tipo_nota   = remove_junk($db->escape($_POST['tipo_nota']));
        $nombre_nota   = remove_junk($db->escape($_POST['nombre_nota']));
        $url_acceso   = remove_junk($db->escape($_POST['url_acceso']));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
		
        //Suma el valor del id anterior + 1, para generar ese id para el nuevo resguardo
        //La variable $no_folio sirve para el numero de folio
        if (count($id_comunicados) == 0) {
            $nuevo_id_comunicados = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_comunicados as $nuevo) {
                $nuevo_id_comunicados = (int) $nuevo['id_comunicados'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['id_comunicados'] + 1);
            }
        }

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int) $nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int) $nuevo['contador'] + 1);
            }
        }
        //Se crea el número de folio
        $year = date("Y");
        // Se crea el folio de canalizacion
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-COM';

        
            $query = "INSERT INTO comunicados (";
            $query .= "folio,fecha_publicacion,tipo_nota,nombre_nota,url_acceso,observaciones,user_creador,fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$fecha_publicacion}','{$tipo_nota}','{$nombre_nota}','{$url_acceso}','{$observaciones}','{$id_user}',NOW()); ";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";

            if ($db->query($query) && $db->query($query2)) {
                //sucess
                insertAccion($user['id_user'], '"' . $user['username'] . '" creo un Comicado de Prensa de Folio: -' . $folio . '-.', 1);
                $session->msg('s', " El Comunicado con folio '{$folio}' ha sido agregado con éxito.");
                redirect('comunicados_prensa.php', false);
            } else {
                //failed
                $session->msg('d', ' No se pudo agregar el convenio.');
                redirect('add_comunicado.php', false);
            }
       
    } else {
        $session->msg("d", $errors);
        redirect('add_comunicado.php', false);
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
                <span>Agregar Comunicado</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_comunicado.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_publicacion">Fecha de Publicación<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_publicacion" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tipo_nota">Tipo de Comunicado <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" name="tipo_nota" required>
                                <option value="">Escoge una opción</option>
                                    <option value="Noticia">Noticia</option>
                                    <option value="Comunicado">Comunicado</option>
                                    <option value="Pronunciamiento">Pronunciamiento</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_nota">Título del Comunicado<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="nombre_nota" placeholder="Título del Comunicado" required>
                        </div>
                    </div>
                    
                </div>

                <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                            <label for="url_acceso">Link Acceso<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="url_acceso" placeholder="Link Acceso" required>
                        </div>
                    </div>                 

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label><br>
                            <textarea name="observaciones" class="form-control" id="observaciones" cols="50" rows="5"></textarea>
                        </div>
                    </div>
                   
                </div>
               
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="comunicados_prensa.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="add_comunicado" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>