<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Entrevista';
require_once('includes/load.php');

$entrevistas = find_by_id('entrevistas', (int)$_GET['id'], 'id_entrevistas');

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

if (isset($_POST['update'])) {

    if (empty($errors)) {
	$id = (int)$entrevistas['id_entrevistas'];
        $fecha_entrevista = remove_junk($db->escape($_POST['fecha_entrevista']));
        $tema_entrevista   = remove_junk($db->escape($_POST['tema_entrevista']));
        $lugar_entrevista   = remove_junk($db->escape($_POST['lugar_entrevista']));
        $nombre_entrevistado   = remove_junk($db->escape($_POST['nombre_entrevistado']));
        $cargo_entrevistado   = remove_junk($db->escape($_POST['cargo_entrevistado']));
        $temas_destacados   = remove_junk($db->escape($_POST['temas_destacados']));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
		
         $sql = "UPDATE entrevistas SET 
			fecha_entrevista='{$fecha_entrevista}', 
			tema_entrevista='{$tema_entrevista}', 
			lugar_entrevista='{$lugar_entrevista}', 
			nombre_entrevistado='{$nombre_entrevistado}', 
			cargo_entrevistado='{$cargo_entrevistado}',			
			temas_destacados='{$temas_destacados}',			
			observaciones='{$observaciones}'			
			WHERE id_entrevistas='{$db->escape($id)}'";

        $result = $db->query($sql);
            if ($result && $db->affected_rows() === 1) {
            insertAccion($user['id_user'], '"' . $user['username'] . '" edito una Entrevista de Folio: -' . $entrevistas['folio'] , 2);
            $session->msg('s', " El comunicado con folio '" . $entrevistas['folio'] . "' ha sido acuatizado con éxito.");
            redirect('entrevistas.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos, debido a que no se realizaron canmbios a la informacion.');
            redirect('edit_entrevista.php?id=' . (int)$entrevistas['id_entrevistas'], false);
        }
       
    } else {
        $session->msg("d", $errors);
        redirect('edit_entrevista.php', false);
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
                <span>Editar Entrevista</span>
            </strong>
        </div>
              <div class="panel-body">
            <form method="post" action="edit_entrevista.php?id=<?php echo (int)$entrevistas['id_entrevistas']; ?>" >
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_entrevista">Fecha de Entrevista<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_entrevista" value="<?php echo ucwords($entrevistas['fecha_entrevista']); ?>" required>
                        </div>
                    </div>					
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tema_entrevista">Tema de Entrevista<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="tema_entrevista" placeholder="Tema Entrevista" value="<?php echo ucwords($entrevistas['tema_entrevista']); ?>" required>
                        </div>
                    </div>                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lugar_entrevista">Lugar de Entrevista<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="lugar_entrevista" placeholder="Lugar de Entrevista" value="<?php echo ucwords($entrevistas['lugar_entrevista']); ?>" required>
                        </div>
                    </div>
                    
                </div>

                <div class="row">
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_entrevistado">Nombre Entrevistado<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="nombre_entrevistado" placeholder="Nombre Entrevistado" value="<?php echo ucwords($entrevistas['nombre_entrevistado']); ?>" required>
                        </div>
                    </div>
					
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cargo_entrevistado">Cargo de Entrevistado</label>
                            <input type="text" class="form-control" name="cargo_entrevistado" placeholder="Cargo de Entrevistado" value="<?php echo ucwords($entrevistas['cargo_entrevistado']); ?>" >
                        </div>
                    </div>

				
                    </div>
					
                                  
                <div class="row">

                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="temas_destacados">Temas Destacados</label><br>
                            <textarea name="temas_destacados" class="form-control" id="temas_destacados" cols="50" rows="5"><?php echo ucwords($entrevistas['temas_destacados']); ?></textarea>
                        </div>
                    </div>
                   
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label><br>
                            <textarea name="observaciones" class="form-control" id="observaciones" cols="50" rows="5"><?php echo ucwords($entrevistas['observaciones']); ?></textarea>
                        </div>
                    </div>
                   
                </div>
               
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="entrevistas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="update" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>