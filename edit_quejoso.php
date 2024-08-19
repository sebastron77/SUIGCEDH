<?php
$page_title = 'Editar Datos de Quejoso';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 5) {
    page_require_level_exacto(5);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 19) {
    page_require_level_exacto(19);
}
if ($nivel_user == 26) {
    page_require_level_exacto(26);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}
if ($nivel_user > 2 && $nivel_user < 5):
    redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 7):
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 19):
    redirect('home.php');
endif;
if ($nivel_user > 19 && $nivel_user < 26):
    redirect('home.php');
endif;
if ($nivel_user > 26 && $nivel_user < 50):
    redirect('home.php');
endif;
?>
<?php
$e_detalle = find_by_id('cat_quejosos', (int) $_GET['id'], 'id_cat_quejoso');
if (!$e_detalle) {
    $session->msg("d", "id de quejoso no encontrado.");
    redirect('quejosos.php');
}
$user = current_user();
$nivel = $user['user_level'];
$generos = find_all('cat_genero');
$nacionalidades = find_all('cat_nacionalidades');
$municipios = find_all('cat_municipios');
$escolaridades = find_all('cat_escolaridad');
$ocupaciones = find_all('cat_ocupaciones');
$grupos_vuln = find_all('cat_grupos_vuln');
$discapacidades = find_all('cat_discapacidades');
$ppls = find_all('cat_ppl');
$comunidades = find_all('cat_comunidades');
?>

<?php
if (isset($_POST['update'])) {
    $req_fields = array(
        'nombre',
        'paterno',
        'materno',
        'id_cat_gen',
        'edad',
        'id_cat_nacionalidad',
        'id_cat_mun',
        'id_cat_escolaridad',
        'id_cat_ocup',
        'leer_escribir',
        'id_cat_grupo_vuln',
        'id_cat_disc',
        'id_cat_comun',
        'telefono',
        'email'
    );
    validate_fields($req_fields);
    if (empty($errors)) {
        $id = (int) $e_detalle['id_cat_quejoso'];
        $nombre = $_POST['nombre'];
        $paterno = $_POST['paterno'];
        $materno = $_POST['materno'];
        $id_cat_gen = remove_junk($db->escape($_POST['id_cat_gen']));
        $edad = $db->escape($_POST['edad']);
        $id_cat_nacionalidad = remove_junk(upper_case($db->escape($_POST['id_cat_nacionalidad'])));
        $id_cat_mun = remove_junk($db->escape($_POST['id_cat_mun']));
        $id_cat_escolaridad = remove_junk($db->escape($_POST['id_cat_escolaridad']));
        $id_cat_ocup = remove_junk($db->escape($_POST['id_cat_ocup']));
        $leer_escribir = $db->escape($_POST['leer_escribir']);
        $id_cat_grupo_vuln = remove_junk($db->escape($_POST['id_cat_grupo_vuln']));
        $id_cat_disc = $_POST['id_cat_disc'];
        $id_cat_comun = $_POST['id_cat_comun'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $calleQ = remove_junk($db->escape($_POST['calleQ']));
        $numeroQ = remove_junk($db->escape($_POST['numeroQ']));
        $coloniaQ = remove_junk($db->escape($_POST['coloniaQ']));

        $sql = "UPDATE cat_quejosos SET nombre='{$nombre}', paterno='{$paterno}', materno='{$materno}', id_cat_gen='{$id_cat_gen}', edad='{$edad}',
                        id_cat_nacionalidad='{$id_cat_nacionalidad}', id_cat_mun='{$id_cat_mun}', id_cat_escolaridad='{$id_cat_escolaridad}',
                        id_cat_ocup='{$id_cat_ocup}', leer_escribir='{$leer_escribir}', id_cat_grupo_vuln='{$id_cat_grupo_vuln}', id_cat_disc='{$id_cat_disc}',
                        id_cat_comun='{$id_cat_comun}', telefono='{$telefono}', email='{$email}', calle_quejoso='{$calleQ}', numero_quejoso='{$numeroQ}',
                        colonia_quejoso='{$coloniaQ}' WHERE id_cat_quejoso='{$db->escape($id)}'";
        $result = $db->query($sql);

        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Información Actualizada ");
            redirect('edit_quejoso.php?id=' . (int) $e_detalle['id_cat_quejoso'], false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos.');
            redirect('edit_quejoso.php?id=' . (int) $e_detalle['id_cat_quejoso'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_quejoso.php?id=' . (int) $e_detalle['id_cat_quejoso'], false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    Actualizar datos del quejoso
                    <?php echo (ucwords($e_detalle['nombre'])); ?>
                    <?php echo $e_detalle['paterno'] . " " . $e_detalle['materno']; ?>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="edit_quejoso.php?id=<?php echo (int) $e_detalle['id_cat_quejoso']; ?>"
                    class="clearfix">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" name="nombre"
                                    value="<?php echo $e_detalle['nombre'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="paterno">Apellido Paterno</label>
                                <input type="text" class="form-control" name="paterno"
                                    value="<?php echo $e_detalle['paterno'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="materno">Apellido Materno</label>
                                <input type="text" class="form-control" name="materno"
                                    value="<?php echo $e_detalle['materno'] ?>">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="id_cat_gen">Género</label>
                                <select class="form-control" name="id_cat_gen">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($generos as $genero): ?>
                                        <option <?php if ($genero['id_cat_gen'] === $e_detalle['id_cat_gen'])
                                            echo 'selected="selected"'; ?> value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="edad">Edad</label>
                                <input type="number" class="form-control" min="1" max="130" name="edad"
                                    value="<?php echo $e_detalle['edad'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" class="form-control" maxlength="10" name="telefono"
                                    value="<?php echo $e_detalle['telefono'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" name="email"
                                    value="<?php echo $e_detalle['email'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label for="id_cat_nacionalidad">Nacionalidad</label>
                            <select class="form-control" name="id_cat_nacionalidad">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($nacionalidades as $nacionalidad): ?>
                                    <option <?php if ($nacionalidad['id_cat_nacionalidad'] === $e_detalle['id_cat_nacionalidad'])
                                        echo 'selected="selected"'; ?>
                                        value="<?php echo $nacionalidad['id_cat_nacionalidad']; ?>"><?php echo ucwords($nacionalidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_mun" class="control-label">Municipio</label>
                                <select class="form-control" name="id_cat_mun">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($municipios as $municipio): ?>
                                        <option <?php if ($municipio['id_cat_mun'] === $e_detalle['id_cat_mun'])
                                            echo 'selected="selected"'; ?> value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_escolaridad" class="control-label">Escolaridad</label>
                                <select class="form-control" name="id_cat_escolaridad">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($escolaridades as $escolaridad): ?>
                                        <option <?php if ($escolaridad['id_cat_escolaridad'] === $e_detalle['id_cat_escolaridad'])
                                            echo 'selected="selected"'; ?>
                                            value="<?php echo $escolaridad['id_cat_escolaridad']; ?>"><?php echo ucwords($escolaridad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_ocup" class="control-label">Ocupación</label>
                                <select class="form-control" name="id_cat_ocup">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($ocupaciones as $ocupacion): ?>
                                        <option <?php if ($ocupacion['id_cat_ocup'] === $e_detalle['id_cat_ocup'])
                                            echo 'selected="selected"'; ?> value="<?php echo $ocupacion['id_cat_ocup']; ?>"><?php echo ucwords($ocupacion['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="leer_escribir" class="control-label">¿Sabe leer y escribir?</label>
                                <select class="form-control" name="leer_escribir">
                                    <option <?php if ($e_detalle['leer_escribir'] === 'Leer')
                                        echo 'selected="selected"'; ?>value="Leer">
                                        Leer</option>
                                    <option <?php if ($e_detalle['leer_escribir'] === 'Escribir')
                                        echo 'selected="selected"'; ?> value="Escribir">Escribir</option>
                                    <option <?php if ($e_detalle['leer_escribir'] === 'Ambos')
                                        echo 'selected="selected"'; ?> value="Ambos">Ambos</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_disc" class="control-label">¿Tiene alguna discapacidad?</label>
                                <select class="form-control" name="id_cat_disc">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($discapacidades as $discapacidad): ?>
                                        <option <?php if ($discapacidad['id_cat_disc'] === $e_detalle['id_cat_disc'])
                                            echo 'selected="selected"'; ?> value="<?php echo $discapacidad['id_cat_disc']; ?>">
                                            <?php echo ucwords($discapacidad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_cat_grupo_vuln" class="control-label">Grupo Vulnerable</label>
                                <select class="form-control" name="id_cat_grupo_vuln">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($grupos_vuln as $grupo_vuln): ?>
                                        <option <?php if ($grupo_vuln['id_cat_grupo_vuln'] === $e_detalle['id_cat_grupo_vuln'])
                                            echo 'selected="selected"'; ?>
                                            value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>">
                                            <?php echo ucwords($grupo_vuln['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_cat_comun" class="control-label">Comunidad</label>
                                <select class="form-control" name="id_cat_comun">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($comunidades as $comunidad): ?>
                                        <option <?php if ($comunidad['id_cat_comun'] === $e_detalle['id_cat_comun'])
                                            echo 'selected="selected"'; ?> value="<?php echo $comunidad['id_cat_comun']; ?>">
                                            <?php echo ucwords($comunidad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="calleQ">Calle</label>
                                <input type="text" class="form-control" name="calleQ"
                                    value="<?php echo $e_detalle['calle_quejoso'] ?>">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="numeroQ">Num.</label>
                                <input type="text" class="form-control" name="numeroQ"
                                    value="<?php echo $e_detalle['numero_quejoso'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="coloniaQ">Colonia</label>
                                <input type="text" class="form-control" name="coloniaQ"
                                    value="<?php echo $e_detalle['colonia_quejoso'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <a href="quejosos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="update" class="btn btn-info">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>