<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar paciente';
require_once('includes/load.php');
include("conexion.php");
$mysqli = new mysqli('localhost', 'suigcedh', '9DvkVuZ915H!', 'suigcedh');
$user = current_user();

$generos = find_all('cat_genero');
$municipios = find_all('cat_municipios');
$escolaridades = find_all('cat_escolaridad');
$ocupaciones = find_all('cat_ocupaciones');
$grupos_vuln = find_all('cat_grupos_vuln');
$discapacidades = find_all('cat_discapacidades');
$comunidades = find_all('cat_comunidades');
$nacionalidades = find_all('cat_nacionalidades');
$entidades = find_all('cat_entidad_fed');
$autoridades = find_all('cat_autoridades');
?>

<?php
if (isset($_POST['add_paciente'])) {
    if (empty($errors)) {

        $nombre = remove_junk($db->escape($_POST['nombre']));
        $paterno = remove_junk($db->escape($_POST['paterno']));
        $materno = remove_junk($db->escape($_POST['materno']));
        $genero = remove_junk($db->escape($_POST['genero']));
        $edad = remove_junk($db->escape($_POST['edad']));
        $nacionalidad = remove_junk($db->escape($_POST['nacionalidad']));
        $municipio1 = remove_junk($db->escape($_POST['municipio']));
        $entidad1 = remove_junk($db->escape($_POST['entidad']));
        $escolaridad = remove_junk($db->escape($_POST['escolaridad']));
        $ocupacion = remove_junk($db->escape($_POST['ocupacion']));
        $leer_escribir = remove_junk($db->escape($_POST['leer_escribir']));
        $grupo_vulnerable = remove_junk($db->escape($_POST['grupo_vulnerable']));
        $autoridad_responsable = remove_junk($db->escape($_POST['autoridad_responsable']));
        $comunidad = remove_junk($db->escape($_POST['comunidad']));
        $telefono = remove_junk($db->escape($_POST['telefono']));
        $discapacidad = remove_junk($db->escape($_POST['discapacidad']));
        $email = remove_junk($db->escape($_POST['email']));
        $tipo_expediente = remove_junk($db->escape($_POST['tipo_expediente']));
        $folio_expediente = remove_junk($db->escape($_POST['folio_expediente']));

        $query = "INSERT INTO paciente (";
        $query .= "nombre, paterno, materno, genero, edad, nacionalidad, municipio, entidad, escolaridad, ocupacion, discapacidad, autoridad_responsable, grupo_vulnerable, leer_escribir, comunidad, telefono, email, folio_expediente, tipo_expediente";
        $query .= ") VALUES (";
        $query .= "'{$nombre}', '{$paterno}', '{$materno}', '{$genero}', '{$edad}', '{$nacionalidad}', '{$municipio1}', '{$entidad1}', '{$escolaridad}', '{$ocupacion}', '{$discapacidad}', '{$autoridad_responsable}', '{$grupo_vulnerable}', '{$leer_escribir}', '{$comunidad}', '{$telefono}', '{$email}', '{$folio_expediente}', '{$tipo_expediente}'";
        $query .= ")";

        if ($db->query($query)) {
            $session->msg('s', " El/la paciente ha sido agregado con éxito.");
            insertAccion($user['id_user'], '"'.$user['username'].'" agregó registro de paciente.', 1);
            redirect('pacientes.php', false);
        } else {
            $session->msg('d', ' No se pudo agregar el/la paciente .');
            redirect('add_paciente.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_paciente.php', false);
    }
}
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
header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php');
?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar paciente</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_paciente.php">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" name="nombre" placeholder="Nombre(s)" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="paterno">Apellido Paterno</label>
                            <input type="text" class="form-control" name="paterno" placeholder="Apellido Paterno" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="materno">Apellido Materno</label>
                            <input type="text" class="form-control" name="materno" placeholder="Apellido Materno" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="genero">Género</label>
                            <select class="form-control" name="genero">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($generos as $genero) : ?>
                                    <option value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="edad">Edad</label>
                            <input type="number" class="form-control" min="1" max="130" maxlength="4" name="edad" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" maxlength="10" name="telefono" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" name="email" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="nacionalidad">Nacionalidad</label>
                            <select class="form-control" name="nacionalidad">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($nacionalidades as $nacionalidad) : ?>
                                    <option value="<?php echo $nacionalidad['id_cat_nacionalidad']; ?>"><?php echo ucwords($nacionalidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="entidad">Entidad</label>
                            <select class="form-control" name="entidad">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($entidades as $entidad) : ?>
                                    <option value="<?php echo $entidad['id_cat_ent_fed']; ?>"><?php echo ucwords($entidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="municipio">Municipio</label>
                            <select class="form-control" name="municipio">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($municipios as $municipio) : ?>
                                    <option value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>                   
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="escolaridad">Escolaridad</label>
                            <select class="form-control" name="escolaridad">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($escolaridades as $escolaridad) : ?>
                                    <option value="<?php echo $escolaridad['id_cat_escolaridad']; ?>"><?php echo ucwords($escolaridad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="ocupacion">Ocupación</label>
                            <select class="form-control" name="ocupacion">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($ocupaciones as $ocupacion) : ?>
                                    <option value="<?php echo $ocupacion['id_cat_ocup']; ?>"><?php echo ucwords($ocupacion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="leer_escribir">¿Sabe leer y escribir?</label>
                            <select class="form-control" name="leer_escribir">
                                <option value="">Escoge una opción</option>
                                <option value="Leer">Leer</option>
                                <option value="Escribir">Escribir</option>
                                <option value="Ambos">Ambos</option>
                                <option value="Sin Dato">Sin Dato</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="discapacidad">¿Tiene alguna discapacidad?</label>
                            <select class="form-control" name="discapacidad">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($discapacidades as $discapacidad) : ?>
                                    <option value="<?php echo $discapacidad['id_cat_disc']; ?>"><?php echo ucwords($discapacidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="grupo_vulnerable">Grupo Vulnerable</label>
                            <select class="form-control" name="grupo_vulnerable">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($grupos_vuln as $grupo_vuln) : ?>
                                    <option value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="comunidad">Comunidad</label>
                            <select class="form-control" name="comunidad">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($comunidades as $comunidad) : ?>
                                    <option value="<?php echo $comunidad['id_cat_comun']; ?>"><?php echo ucwords($comunidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="autoridad_responsable">Autoridad Responsable</label>
                            <select class="form-control" name="autoridad_responsable">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($autoridades as $aut) : ?>
                                    <option value="<?php echo $aut['id_cat_aut']; ?>"><?php echo ucwords($aut['nombre_autoridad']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_expediente">Tipo Expediente</label>
                            <select class="form-control" id="tipo_expediente" name="tipo_expediente">
                                <option value="">Escoge una opción</option>
                                <option value="Q">Queja</option>
                                <option value="O">Orientación</option>
                                <option value="C">Canalización</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="folio_expediente">Folios de Expediente</label>
                            <select class="form-control" name="folio_expediente" id="folio_expediente">
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <a href="pacientes.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_paciente" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include_once('layouts/footer.php'); ?>