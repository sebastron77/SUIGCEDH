<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Búsqueda de Personal';
require_once('includes/load.php');


$user = current_user();
$id_user = $user['id_user'];
$busca_area = area_usuario($id_user);
$nivel = $user['user_level'];

if ($nivel == 1) {
    page_require_level_exacto(1);
}
if ($nivel == 2) {
    page_require_level_exacto(2);
}
if ($nivel == 14) {
    page_require_level_exacto(14);
}
if ($nivel > 2 && $nivel < 14) {
    redirect('home.php');
}
if (!$nivel) {
    redirect('home.php');
}

$areas_all = find_all_order('area', 'nombre_area');
$generos = find_all('cat_genero');
$puestos = find_all('cat_puestos');
$tipo_integrantes = find_all('cat_tipo_integrante');
$areas_conocimiento = find_all('cat_area_conocimiento');
$grados_escolares = find_all('cat_escolaridad');
$grupos_vuln = find_all_order('cat_grupos_vuln', 'descripcion');
?>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<link rel="stylesheet" href="libs/css/main.css" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://harvesthq.github.io/chosen/chosen.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

<?php header('Content-type: text/html; charset=utf-8');

?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-body">
            <form method="post" action="exec_busquedapersonal.php">
                <div class="panel-heading">
                    <strong>
                        <span class="glyphicon glyphicon-th"></span>
                        <span>Personal</span>
                    </strong>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="area">Áreas de Adscripción</label>
                            <select class="form-control" name="area">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($areas_all as $area) : ?>
                                    <option value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="puesto">Puestos</label>
                            <select class="form-control" name="puesto">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($puestos as $puesto) : ?>
                                    <option value="<?php echo $puesto['id_cat_puestos']; ?>"><?php echo ucwords($puesto['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="integrante">Tipos de Integrantes</label>
                            <select class="form-control" name="integrante">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($tipo_integrantes as $t_integrante) : ?>
                                    <option value="<?php echo $t_integrante['id_tipo_integrante']; ?>"><?php echo ucwords($t_integrante['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sueldo">Rango de Sueldos</label>
                            <select class="form-control" name="sueldo">
                                <option value="">Escoge una opción</option>
                                <option value="1">$1,000 a $10,000</option>
                                <option value="2">$10,000 a $20,000</option>
                                <option value="3">$20,000 a $30,000</option>
                                <option value="4">$30,000 a $40,000</option>
                                <option value="5">$40,000 a $50,000</option>
                                <option value="6">$50,000 a $100,000</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="activo">Activo</label>
                            <select class="form-control" name="activo">
                                <option value="">Escoge una opción</option>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="gv">Grupo Vulnerable</label>
                            <select class="form-control" name="gv">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($grupos_vuln as $gv) : ?>
                                    <option value="<?php echo $gv['id_cat_grupo_vuln']; ?>"><?php echo ucwords($gv['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="ss">Seguro Social</label>
                            <select class="form-control" name="ss">
                                <option value="">Escoge una opción</option>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="escolaridad">Grado Escolar</label>
                            <select class="form-control" name="escolaridad">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($grados_escolares as $grado) : ?>
                                    <option value="<?php echo $grado['id_cat_escolaridad']; ?>"><?php echo ucwords($grado['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="conocimiento">Áreas del Conocimiento</label>
                            <select class="form-control" name="conocimiento">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($areas_conocimiento as $conocimiento) : ?>
                                    <option value="<?php echo $conocimiento['id_cat_area_con']; ?>"><?php echo ucwords($conocimiento['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix" style="text-align: center;">
                    <button type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Buscar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include_once('layouts/footer.php'); ?>