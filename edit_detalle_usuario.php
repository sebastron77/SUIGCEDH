<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<?php
$page_title = 'Editar Datos de Trabajador';
require_once('includes/load.php');

$user = current_user();
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
if ($nivel_user > 14) {
    redirect('home.php');
}
if (!$nivel_user) {
    redirect('home.php');
}
?>
<?php
$e_detalle = find_by_id('detalles_usuario', (int)$_GET['id'], 'id_det_usuario');
$cargos = find_all_order('cargos', 'nombre_cargo');
$areas = find_all_order('area', 'jerarquia');
$generos = find_all('cat_genero');
$grupos_vuln = find_all('cat_grupos_vuln');
$detalles_gv = detalle_gv_by_id((int)$_GET['id']);
$rel_detalle_grupo_vuln = find_detalle_grupo_vuln((int)$_GET['id']);

if (!$e_detalle) {
    $session->msg("d", "id de usuario no encontrado.");
    redirect('detalles_usuario.php');
}
$user = current_user();
$nivel = $user['user_level'];
$cat_municipios = find_all_cat_municipios();
$con_usuario = search_userTrabajador((int)$_GET['id']);
?>

<?php
if (isset($_POST['update'])) {
    $req_fields = array('nombre', 'apellidos', 'id_cat_gen', 'correo');
    validate_fields($req_fields);
    if (empty($errors)) {
        $id = (int)$e_detalle['id_det_usuario'];
        $nombre   = $_POST['nombre'];
        $apellidos   = $_POST['apellidos'];
        $sexo   = remove_junk($db->escape($_POST['id_cat_gen']));
        $correo   = remove_junk($db->escape($_POST['correo']));
        $cargo = remove_junk((int)$db->escape($_POST['cargo']));
        $telefono   = $db->escape($_POST['telefono']);
        $curp   = $db->escape($_POST['curp']);
        $rfc   = $db->escape($_POST['rfc']);
        $calle_num   = $db->escape($_POST['calle_num']);
        $colonia   = $db->escape($_POST['colonia']);
        $municipio   = $db->escape($_POST['municipio']);
        $estado   = $db->escape($_POST['estado']);
        $id_cat_grupo_vuln   = $_POST['id_cat_grupo_vuln'];

        $sql = "UPDATE detalles_usuario SET nombre = '{$nombre}', apellidos = '{$apellidos}', id_cat_gen = '{$sexo}', correo = '{$correo}', 
                id_cargo = {$cargo}, curp = '$curp', rfc = '{$rfc}', calle_num = '{$calle_num}', colonia = '{$colonia}', municipio = '{$municipio}', 
                estado = '{$estado}', telefono = '{$telefono}' WHERE id_det_usuario = '{$db->escape($id)}'";

        // if ($id_cat_grupo_vuln != '') {
        // if ($id_cat_grupo_vuln > 0) {
        $query = "DELETE FROM rel_detalle_gv WHERE id_detalle_usuario =" . $id;
        $db->query($query);

        for ($i = 0; $i < sizeof($id_cat_grupo_vuln); $i++) {
            $query2 = "INSERT INTO rel_detalle_gv (";
            $query2 .= "id_detalle_usuario, id_cat_grupo_vuln, fecha_creacion";
            $query2 .= ") VALUES (";
            $query2 .= " {$id}, '{$id_cat_grupo_vuln[$i]}', NOW())";
            if ($db->query($query2)) {
                $cambios = true;
            } else {
                $cambios = false;
            }
        }
        // }
        // }

        $result = $db->query($sql);

        if ($result && $db->affected_rows() === 1 || $cambios) {
            $session->msg('s', "Información Actualizada ");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó al trabajador(a): ' . $nombre . ' ' . $apellidos . '.', 2);
            redirect('edit_detalle_usuario.php?id=' . (int)$e_detalle['id_det_usuario'], false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos.');
            redirect('edit_detalle_usuario.php?id=' . (int)$e_detalle['id_det_usuario'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_detalle_usuario.php?id=' . (int)$e_detalle['id_det_usuario'], false);
    }
}
?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#addRow").click(function() {
            var html = '';
            html += '<div id="inputFormRow">';
            html += '   <div class="col-md-4" style="margin-left: -15px; width: 34.5%">';
            html += '		<select class="form-control" name="id_cat_grupo_vuln[]">';
            html += '           <option value="">Escoge una opción</option>';
            html += '           <?php foreach ($grupos_vuln as $grupo_vuln) : ?>';
            html += '               <option value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>';
            html += '           <?php endforeach; ?>';
            html += '      </select>';
            html += '	</div>';
            html += '	<div class="col-md-2">';
            html += '	<button type="button" class="btn btn-outline-danger" id="removeRow" style="margin-left: -14px;"> ';
            html += '   	<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">';
            html += '			<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>';
            html += '			<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>';
            html += '		</svg>';
            html += '  	</button>';
            html += '	</div> <br><br>';
            html += '</div> ';

            $('#newRow').append(html);
        });
        $(document).on('click', '#removeRow', function() {
            $(this).closest('#inputFormRow').remove();
        });
    });
</script>

<?php include_once('layouts/header.php'); ?>
<div class="row">
    <!-- <div class="col-md-12"> <?php echo display_msg($msg); ?> </div> -->
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    Actualizar expediente general del trabajador: <?php echo (ucwords($e_detalle['nombre'])); ?> <?php echo ($e_detalle['apellidos']); ?>
                </strong>
            </div>
            <form method="post" action="edit_detalle_usuario.php?id=<?php echo (int)$e_detalle['id_det_usuario']; ?>" class="clearfix">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="nombre" class="control-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" value="<?php echo ($e_detalle['nombre']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="apellidos" class="control-label">Apellidos</label>
                                <input type="text" class="form-control" name="apellidos" value="<?php echo ($e_detalle['apellidos']); ?>" required>
                            </div>
                        </div>
                        <?php if ($nivel_user <= 2) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cargo">Cargo</label>
                                    <select class="form-control" name="cargo" required>
                                        <?php foreach ($cargos as $cargo) : ?>
                                            <option <?php if ($cargo['id_cargos'] === $e_detalle['id_cargo']) echo 'selected="selected"'; ?> value="<?php echo $cargo['id_cargos']; ?>">
                                                <?php foreach ($areas as $area) : ?>
                                                    <?php if ($area['id_area'] === $cargo['id_area'])
                                                        echo ucwords($cargo['nombre_cargo']) . " - " . ucwords($area['nombre_area']);
                                                    ?>
                                                <?php endforeach; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" class="form-control" name="cargo" value="<?php echo ($e_detalle['id_cargo']); ?>">
                        <?php } ?>
                        <div class="col-md-2">
                            <label for="id_cat_gen">Género</label>
                            <select class="form-control" name="id_cat_gen">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($generos as $genero) : ?>
                                    <option <?php if ($e_detalle['id_cat_gen'] === $genero['id_cat_gen']) echo 'selected="selected"'; ?> value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" class="form-control" value="<?php echo ($e_detalle['telefono']); ?>" name="telefono">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="correo" class="control-label">Correo</label>
                            <input type="text" class="form-control" name="correo" value="<?php echo remove_junk($e_detalle['correo']); ?>">
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="curp">CURP</label>
                                <input type="text" class="form-control" name="curp" value="<?php echo ($e_detalle['curp']); ?>" placeholder="CURP">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="rfc">RFC</label>
                                <input type="text" class="form-control" value="<?php echo ($e_detalle['rfc']); ?>" name="rfc">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Calle y Núm.</label>
                                <input type="text" class="form-control" value="<?php echo ($e_detalle['calle_num']); ?>" name="calle_num">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="colonia">Colonia</label>
                                <input type="text" class="form-control" value="<?php echo ($e_detalle['colonia']); ?>" name="colonia">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="municipio">Municipio</label>
                                <select class="form-control" name="municipio" required>
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cat_municipios as $municipio) : ?>
                                        <option <?php if ($municipio['id_cat_mun'] === $e_detalle['municipio'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $municipio['id_cat_mun']; ?>">
                                            <?php echo ucwords($municipio['descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <input type="text" class="form-control" value="<?php echo ($e_detalle['estado']); ?>" name="estado">
                            </div>
                        </div>
                    </div>
                    <?php
                    $num = 0;
                    foreach ($rel_detalle_grupo_vuln as $dgv) : ?>
                        <div class="row" id="inputFormRow">
                            <div class="col-md-4">
                                <label for="id_cat_grupo_vuln">¿Pertenece a algún Grupo Vulnerable?</label>
                                <select class="form-control" name="id_cat_grupo_vuln[]">
                                    <option value="">Seleccione el Derecho Violentado</option>
                                    <?php foreach ($grupos_vuln as $gv) : ?>
                                        <option <?php if ($gv['id_cat_grupo_vuln'] === $dgv['id_cat_grupo_vuln']) echo 'selected="selected"'; ?> value="<?php echo $gv['id_cat_grupo_vuln']; ?>">
                                            <?php echo ucwords($gv['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select><br>
                            </div>
                            <?php if ($num < 1) { ?>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-success" id="addRow" name="addRow" style="margin-top: 27px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
                                                <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
                                                <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
                                            </svg>
                                        </button>

                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-danger" id="removeRow" style="margin-top: 27px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">
                                            <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
                                            <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>
                                        </svg>
                                    </button>
                                </div>
                            <?php } ?>
                        </div>
                    <?php $num++;
                    endforeach; ?>
                    <?php if ($num == 0) { ?>
                        <div class="row" id="inputFormRow">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_cat_grupo_vuln">¿Pertenece a algún Grupo Vulnerable?</label>
                                    <select class="form-control" name="id_cat_grupo_vuln[]">
                                        <option value="">Seleccione el Derecho Violentado</option>
                                        <?php foreach ($grupos_vuln as $gv) : ?>
                                            <option value="<?php echo $gv['id_cat_grupo_vuln']; ?>">
                                                <?php echo ucwords($gv['descripcion']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button type="button" class="btn btn-success" id="addRow" name="addRow">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
                                            <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
                                            <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row" id="newRow"></div>
                    <div class="form-group clearfix">
                        <a href="detalles_usuario.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <?php if ($nivel_user > 1) { ?>
                            <?php if (!$con_usuario) { ?>
                                <button type="submit" name="update" class="btn btn-info">Actualizar</button>
                    </div>
                </div>
            <?php } else { ?>
                <br><br>
                <div class="alert alert-danger">
                    <?php echo "La información no puede ser editada, debido a que el trabajador cuenta ya con un usuario en el sistema y esto puede corromper la información que ya se ha generado. De ser necesaria la actualización, por favor acudir con el administrador del sistema."; ?>
                </div>
            <?php } ?>
        <?php } else { ?>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        <?php } ?>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>