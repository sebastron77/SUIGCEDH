<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
$page_title = 'Editar Datos de Paciente';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];

$e_detalle = find_by_id_paciente((int)$_GET['id']);
if (!$e_detalle) {
    $session->msg("d", "id de paciente no encontrado.");
    redirect('pacientes.php');
}
$user = current_user();
$nivel = $user['user_level'];
$generos = find_all('cat_genero');
$municipios = find_all('cat_municipios');
$entidades = find_all('cat_entidad_fed');
$escolaridades = find_all('cat_escolaridad');
$ocupaciones = find_all('cat_ocupaciones');
$grupos_vuln = find_all('cat_grupos_vuln');
$discapacidades = find_all('cat_discapacidades');
$comunidades = find_all('cat_comunidades');
$nacionalidades = find_all('cat_nacionalidades');
$autoridades = find_all('cat_autoridades');
$folios = find_all('folios');
?>
<script>
    $(document).ready(function() {
        $("#tipo_expediente").change(function() {
            $("#tipo_expediente option:selected").each(function() {
                tipo_expediente = $(this).val();
                $.post("get_exp.php", {
                    tipo_expediente: tipo_expediente
                }, function(data) {
                    $("#folio_expediente").html(data);
                });
            });
        })
    });
</script>
<?php
if (isset($_POST['update'])) {
    $id = (int) $e_detalle['id_paciente'];
    $nombre = remove_junk($db->escape($_POST['nombre']));
    $paterno = remove_junk($db->escape($_POST['paterno']));
    $materno = remove_junk($db->escape($_POST['materno']));
    $genero = remove_junk($db->escape($_POST['genero']));
    $edad = remove_junk($db->escape($_POST['edad']));
    $nacionalidad = remove_junk($db->escape($_POST['nacionalidad']));
    $municipio = remove_junk($db->escape($_POST['municipio']));
    $entidad = remove_junk($db->escape($_POST['entidad']));
    $escolaridad = remove_junk($db->escape($_POST['escolaridad']));
    $ocupacion = remove_junk($db->escape($_POST['ocupacion']));
    $discapacidad = remove_junk($db->escape($_POST['discapacidad']));
    $grupo_vulnerable = remove_junk($db->escape($_POST['grupo_vulnerable']));
    $leer_escribir = remove_junk($db->escape($_POST['leer_escribir']));
    $comunidad = remove_junk($db->escape($_POST['comunidad']));
    $autoridad_responsable = remove_junk($db->escape($_POST['autoridad_responsable']));
    $telefono = remove_junk($db->escape($_POST['telefono']));
    $email = remove_junk($db->escape($_POST['email']));
    $tipo_expediente = remove_junk($db->escape($_POST['tipo_expediente']));
    $folio_expediente = remove_junk($db->escape($_POST['folio_expediente']));

    $sql = "UPDATE paciente SET nombre='{$nombre}', paterno='{$paterno}', materno='{$materno}', genero='{$genero}', edad='{$edad}',
                        nacionalidad='{$nacionalidad}', municipio='{$municipio}',  entidad='{$entidad}', escolaridad='{$escolaridad}',
                        ocupacion='{$ocupacion}', discapacidad='{$discapacidad}', autoridad_responsable='{$autoridad_responsable}', grupo_vulnerable='{$grupo_vulnerable}', leer_escribir='{$leer_escribir}',
                        comunidad='{$comunidad}', telefono='{$telefono}', email='{$email}', tipo_expediente='{$tipo_expediente}', 
                        folio_expediente='{$folio_expediente}' WHERE id_paciente='{$db->escape($id)}'";
    $result = $db->query($sql);

    if ($result && $db->affected_rows() === 1) {
        $session->msg('s', "Información Actualizada ");
        insertAccion($user['id_user'], '"'.$user['username'].'" editó registro de paciente.', 2);
        redirect('edit_paciente.php?id=' . (int) $e_detalle['id_paciente'], false);
    } else {
        $session->msg('d', ' Lo siento no se actualizaron los datos.');
        redirect('edit_paciente.php?id=' . (int) $e_detalle['id_paciente'], false);
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
                    Actualizar datos del paciente
                    <?php echo (ucwords($e_detalle['nombre'])); ?>
                    <?php echo $e_detalle['paterno'] . " " . $e_detalle['materno']; ?>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="edit_paciente.php?id=<?php echo $e_detalle['id_paciente']; ?>" class="clearfix">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" name="nombre" value="<?php echo $e_detalle['nombre'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="paterno">Apellido Paterno</label>
                                <input type="text" class="form-control" name="paterno" value="<?php echo $e_detalle['paterno'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="materno">Apellido Materno</label>
                                <input type="text" class="form-control" name="materno" value="<?php echo $e_detalle['materno'] ?>">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="genero">Género</label>
                                <select class="form-control" name="genero">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($generos as $genero) : ?>
                                        <option <?php if ($genero['id_cat_gen'] == $e_detalle['id_genero'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="edad">Edad</label>
                                <input type="number" class="form-control" min="1" max="130" name="edad" value="<?php echo $e_detalle['edad'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" class="form-control" maxlength="10" name="telefono" value="<?php echo $e_detalle['telefono'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" name="email" value="<?php echo $e_detalle['email'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label for="nacionalidad">Nacionalidad</label>
                            <select class="form-control" name="nacionalidad">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($nacionalidades as $nacionalidad) : ?>
                                    <option <?php if ($nacionalidad['id_cat_nacionalidad'] === $e_detalle['id_nacionalidad'])
                                                echo 'selected="selected"'; ?> value="<?php echo $nacionalidad['id_cat_nacionalidad']; ?>"><?php echo ucwords($nacionalidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="entidad">Entidad</label>
                            <select class="form-control" name="entidad">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($entidades as $entidad) : ?>
                                    <option <?php if ($entidad['id_cat_ent_fed'] === $e_detalle['id_entidad'])
                                                echo 'selected="selected"'; ?> value="<?php echo $entidad['id_cat_ent_fed']; ?>"><?php echo ucwords($entidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="municipio" class="control-label">Municipio</label>
                                <select class="form-control" name="municipio">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($municipios as $municipio) : ?>
                                        <option <?php if ($municipio['id_cat_mun'] === $e_detalle['id_municipio'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="escolaridad" class="control-label">Escolaridad</label>
                                <select class="form-control" name="escolaridad">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($escolaridades as $escolaridad) : ?>
                                        <option <?php if ($escolaridad['id_cat_escolaridad'] === $e_detalle['id_escolaridad'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $escolaridad['id_cat_escolaridad']; ?>"><?php echo ucwords($escolaridad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ocupacion" class="control-label">Ocupación</label>
                                <select class="form-control" name="ocupacion">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($ocupaciones as $ocupacion) : ?>
                                        <option <?php if ($ocupacion['id_cat_ocup'] === $e_detalle['id_ocupacion'])
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
                                <label for="discapacidad" class="control-label">¿Tiene alguna discapacidad?</label>
                                <select class="form-control" name="discapacidad">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($discapacidades as $discapacidad) : ?>
                                        <option <?php if ($discapacidad['id_cat_disc'] === $e_detalle['id_discapacidad'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $discapacidad['id_cat_disc']; ?>">
                                            <?php echo ucwords($discapacidad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="grupo_vulnerable" class="control-label">Grupo Vulnerable</label>
                                <select class="form-control" name="grupo_vulnerable">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($grupos_vuln as $grupo_vuln) : ?>
                                        <option <?php if ($grupo_vuln['id_cat_grupo_vuln'] === $e_detalle['id_gv'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>">
                                            <?php echo ucwords($grupo_vuln['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="comunidad" class="control-label">Comunidad</label>
                                <select class="form-control" name="comunidad">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($comunidades as $comunidad) : ?>
                                        <option <?php if ($comunidad['id_cat_comun'] === $e_detalle['id_comunidad'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $comunidad['id_cat_comun']; ?>">
                                            <?php echo ucwords($comunidad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="autoridad_responsable" class="control-label">Autoridad Responsable</label>
                                <select class="form-control" name="autoridad_responsable">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($autoridades as $autoridad) : ?>
                                        <option <?php if ($autoridad['id_cat_aut'] === $e_detalle['autoridad_responsable'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $autoridad['id_cat_aut']; ?>">
                                            <?php echo ucwords($autoridad['nombre_autoridad']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipo_expediente">Tipo Expediente</label>
                                <select class="form-control" id="tipo_expediente" name="tipo_expediente">
                                    <option value="">Escoge una opción</option>
                                    <option <?php if ($e_detalle['tipo_expediente'] == 'Q') echo 'selected="selected"'; ?> value="Q">Queja</option>
                                    <option <?php if ($e_detalle['tipo_expediente'] == 'O') echo 'selected="selected"'; ?> value="O">Orientación</option>
                                    <option <?php if ($e_detalle['tipo_expediente'] == 'C') echo 'selected="selected"'; ?> value="C">Canalización</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="folio_expediente">Folios de Expediente</label>
                                <select class="form-control" name="folio_expediente" id="folio_expediente">
                                    <!-- <option value="<?php echo $e_detalle['folio_expediente'] ?>"><?php echo $e_detalle['folio_expediente'] ?></option> -->
                                    <?php foreach ($folios as $folio) : ?>
                                        <option <?php if ($folio['id_folio'] === $e_detalle['folio_expediente'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $folio['id_folio']; ?>">
                                            <?php echo ucwords($folio['folio']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <a href="pacientes.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
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