<?php
$page_title = 'Agregar Puesto';
require_once('includes/load.php');


$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}
if ($nivel_user == 2) {
    page_require_level_exacto(2);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
}
if ($nivel_user > 2 && $nivel_user < 14) :
    redirect('home.php');
endif;
if ($nivel_user > 14) :
    redirect('home.php');
endif;
?>
<?php
if (isset($_POST['add'])) {

    $req_fields = array('puesto_name');
    validate_fields($req_fields);

      if (empty($errors)) {
        $name = remove_junk($db->escape($_POST['puesto_name']));
        $estatus = remove_junk($db->escape($_POST['estatus']));

        $query  = "INSERT INTO cat_puestos (";
        $query .= "descripcion, estatus";
        $query .= ") VALUES (";
        $query .= " '{$name}', '{$estatus}'";
        $query .= ")";
        if ($db->query($query)) {
            //sucess
            $session->msg('s', "Puesto creado! ");
			insertAccion($user['id_user'],'"'.$user['username'].'" agrego el Puesto '.$name.'.',1);
            redirect('add_puesto.php', false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se pudo crear el puesto!');
            redirect('add_puesto.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_puesto.php', false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Agregar nuevo puesto</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="add_puesto.php" class="clearfix">
        <div class="form-group">
            <label for="cargo-name" class="control-label">Nombre del Puesto</label>
            <input type="name" class="form-control" name="puesto_name" required>
        </div>
        <div class="form-group">
                <label for="estatus">Estatus</label>
                <select class="form-control" name="estatus">                    
					<option value="1">Activo</option>
					<option value="0">Inactivo</option>
            </select>
            </div>
        <div class="form-group clearfix">
            <a href="cat_puestos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="add" class="btn btn-info">Guardar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>