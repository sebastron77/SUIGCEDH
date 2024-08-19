<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Entrevista';
require_once('includes/load.php');

$areas = find_all_order('area', 'jerarquia');
$id_folio = last_id_folios();
$id_entrevistas = last_id_table('entrevistas', 'id_entrevistas');

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

if (isset($_POST['add_entrevista'])) {

    if (empty($errors)) {

        $fecha_entrevista = remove_junk($db->escape($_POST['fecha_entrevista']));
        $tema_entrevista   = remove_junk($db->escape($_POST['tema_entrevista']));
        $lugar_entrevista   = remove_junk($db->escape($_POST['lugar_entrevista']));
        $nombre_entrevistado   = remove_junk($db->escape($_POST['nombre_entrevistado']));
        $cargo_entrevistado   = remove_junk($db->escape($_POST['cargo_entrevistado']));
        $temas_destacados   = remove_junk($db->escape($_POST['temas_destacados']));
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
		
        //Suma el valor del id anterior + 1, para generar ese id para el nuevo resguardo
        //La variable $no_folio sirve para el numero de folio
        if (count($id_entrevistas) == 0) {
            $nuevo_id_entrevistas = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_entrevistas as $nuevo) {
                $nuevo_id_entrevistas = (int) $nuevo['id_entrevistas'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['id_entrevistas'] + 1);
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
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-ENTR';

        
            $query = "INSERT INTO entrevistas (";
            $query .= "folio,fecha_entrevista,tema_entrevista,lugar_entrevista,nombre_entrevistado,cargo_entrevistado,temas_destacados,observaciones,user_creador,fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$fecha_entrevista}','{$tema_entrevista}','{$lugar_entrevista}','{$nombre_entrevistado}','{$cargo_entrevistado}','{$temas_destacados}','{$observaciones}','{$id_user}',NOW()); ";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";

            if ($db->query($query) && $db->query($query2)) {
                //sucess
                insertAccion($user['id_user'], '"' . $user['username'] . '" dio de alta una Entrevista de Folio: -' . $folio . '-.', 1);
                $session->msg('s', " La Entrevista con folio '{$folio}' ha sido agregado con éxito.");
                redirect('entrevistas.php', false);
            } else {
                //failed
                $session->msg('d', ' No se pudo agregar la Entrevista.');
                redirect('add_entrevista.php', false);
            }
       
    } else {
        $session->msg("d", $errors);
        redirect('add_entrevista.php', false);
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
                <span>Agregar Entrevista</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_entrevista.php" >
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_entrevista">Fecha de Entrevista<span style="color:red;font-weight:bold">*</span></label><br>
                            <input type="date" class="form-control" name="fecha_entrevista" required>
                        </div>
                    </div>					
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tema_entrevista">Tema de Entrevista<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="tema_entrevista" placeholder="Tema Entrevista" required>
                        </div>
                    </div>                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lugar_entrevista">Lugar de Entrevista<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="lugar_entrevista" placeholder="Lugar de Entrevista" required>
                        </div>
                    </div>
                    
                </div>

                <div class="row">
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_entrevistado">Nombre Entrevistado<span style="color:red;font-weight:bold">*</span></label>
                            <input type="text" class="form-control" name="nombre_entrevistado" placeholder="Nombre Entrevistado" required>
                        </div>
                    </div>
					
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cargo_entrevistado">Cargo de Entrevistado</label>
                            <input type="text" class="form-control" name="cargo_entrevistado" placeholder="Cargo de Entrevistado" >
                        </div>
                    </div>

				
                    </div>
					
                                  
                <div class="row">

                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="temas_destacados">Temas Destacados</label><br>
                            <textarea name="temas_destacados" class="form-control" id="temas_destacados" cols="50" rows="5"></textarea>
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
                        <a href="entrevistas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="add_entrevista" class="btn btn-primary" value="subir">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>