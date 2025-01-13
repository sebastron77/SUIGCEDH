<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Búsqueda de Vacaciones Vigentes';
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
if ($nivel == 29) {
    page_require_level_exacto(29);
}
if ($nivel > 2 && $nivel < 14) {
    redirect('home.php');
}
if ($nivel > 14 && $nivel < 29) {
    redirect('home.php');
}
if ($nivel > 29) {
    redirect('home.php');
}

$areas_all = find_all_order('area', 'nombre_area');
$grupos_vuln = find_all('cat_grupos_vuln');
$periodos = find_all('cat_periodos_vac');
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
            <form method="post" action="exec_busquedavacaciones.php">
                <div class="panel-heading">
                    <strong>
                        <span class="glyphicon glyphicon-th"></span>
                        <span>Vacaciones Vigentes</span>
                    </strong>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="periodo">Periodo</label>
                            <select class="form-control" name="periodo">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($periodos as $periodo) : ?>
                                    <option value="<?php echo $periodo['id_cat_periodo_vac']; ?>"><?php echo ucwords($periodo['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="derecho">Con Derecho</label>
                            <select class="form-control" name="derecho">
                                <option value="">Escoge una opción</option>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_area">Área</label>
                            <select class="form-control" name="id_area">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($areas_all as $area) : ?>
                                    <option value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="semana1_1" style="margin-left: 35%;">Del día</label>
                            <input type="date" class="form-control" name="semana1" id="semana1_1" value="<?php echo $e_rel_p_vacaciones['semana1_1'] ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="semana1_2" style="margin-left: 35%;">Al día</label>
                            <input type="date" class="form-control" name="semana2" id="semana1_2" value="<?php echo $e_rel_p_vacaciones['semana1_2'] ?>">
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