<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Denuncia';
require_once('includes/load.php');


$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 10) {
    page_require_level_exacto(10);
}

if ($nivel_user > 3 && $nivel_user < 7) :
    redirect('home.php');
	
endif;if ($nivel_user > 7 && $nivel_user < 10) :
    redirect('home.php');
endif;
if ($nivel_user > 10) :
    redirect('home.php');
endif;
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_denuncia_ut'])) {

    if (empty($errors)) {

        $folio_accion = remove_junk($db->escape($_POST['folio_accion']));
        $fecha_notificacion   = remove_junk($db->escape($_POST['fecha_notificacion']));		
        $articulo_causal   = remove_junk($db->escape($_POST['articulo_causal']));
		$folio_carpeta = str_replace("/", "-", $folio_accion);
        $carpeta = 'uploads/recursos_revision/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
		/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}



            $query = "INSERT INTO recursos_decuncias (";
            $query .= "tipo_accion,folio_accion,fecha_notificacion,articulo_causal,user_creador,fecha_creacion";
            $query .= ") VALUES (";
            $query .= " 'Denuncia','{$folio_accion}','{$fecha_notificacion}','{$articulo_causal}',{$id_user},NOW())";
           
            if ($db->query($query)) {
                //sucess
                insertAccion($user['id_user'], '"' . $user['username'] . '" dió de alta la Denuncia con Folio: -' . $folio_accion . '-.', 1);
                $session->msg('s', "La Denuncia con folio '{$folio_accion}' ha sido agregado con éxito.");
                redirect('denuncias_ut.php', false);
            } else {
                //failed
                $session->msg('d', ' No se pudo agregar la Denuncia.');
                redirect('add_denuncia_ut.php', false);
            }
       
    } else {
        $session->msg("d", $errors);
        redirect('add_denuncia_ut.php', false);
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
                <span>Agregar Denuncia</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_denuncia_ut.php">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_notificacion">Fecha de Notificación<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_notificacion" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="folio_accion">Folio Recurso<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="folio_accion" placeholder="Folio Denuncia" required>
                        </div>
                    </div>
					
					
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="articulo_causal">Causal Artículo 53 de La Ley de Transparencia</label>
                            <textarea class="form-control" name="articulo_causal"  cols="10" rows="3" required></textarea>
                        </div>
                    </div>

                   
                </div>

                
                <div class="row">
                    <div class="form-group clearfix">
                        <a href="denuncias_ut.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="add_denuncia_ut" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>