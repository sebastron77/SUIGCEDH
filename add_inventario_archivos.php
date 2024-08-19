<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar';
require_once('includes/load.php');
$user = current_user();
$id_user = $user['id_user'];
$id_folio = last_id_folios();
$nivel_user = $user['user_level'];
$cat_ejes = find_all('cat_ejes_estrategicos');
$cat_agendas = find_all('cat_agendas');
$id_table = last_id_table('inventario_archivos','id_inventario_archivos');
$id_folio = last_id_folios();
$inticadores_pat = find_all_pat(14);

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 11) {
    page_require_level_exacto(11);
}
if ($nivel_user <= 2 && $nivel_user < 11) :
    redirect('home.php');
endif;

if ($nivel_user > 11 && $nivel_user < 53) :
    redirect('home.php');
endif;

?>
<?php
if (isset($_POST['add_inventario_archivos'])) {

    $ejercicio = remove_junk($db->escape($_POST['ejercicio']));
    $mes = remove_junk($db->escape($_POST['mes']));
    $total_archivos = remove_junk($db->escape($_POST['total_archivos']));
    $observaciones = remove_junk($db->escape($_POST['observaciones']));
    $id_indicadores_pat = remove_junk($db->escape($_POST['id_indicadores_pat']));
	$fecha_completa = $ejercicio."-".$mes."-"."01";

 if (count($id_table) == 0) {
            $nuevo_id_ori_canal = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_table as $nuevo) {
                $nuevo_id_ori_canal = (int) $nuevo['id_inventario_archivos'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['id_inventario_archivos'] + 1);
            }
        }

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int) $nuevo['contador'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['contador'] + 1);
            }
        }
		
		$year = date("Y");
        $folio = 'CEDH/' . $no_folio . '/' . $year . '-INVAR';
		
		
    $query  = "INSERT INTO inventario_archivos (";
    $query .= "folio,fecha_completa,ejercicio, mes, total_archivos, observaciones, id_indicadores_pat,usuario_creador, fecha_creacion";
    $query .= ") VALUES (";
    $query .= " '{$folio}','{$fecha_completa}','{$ejercicio}', '{$mes}', '{$total_archivos}', '{$observaciones}','{$id_indicadores_pat}', '{$id_user}', NOW()";
    $query .= ")";
	
	 $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio}'";
            $query2 .= ")";
			
    if ($db->query($query) && $db->query($query2)) {
        //sucess
        $session->msg('s', "¡Registro creado con éxito! ");
		insertAccion($user['id_user'], '"' . $user['username'] . '" dio de alta el Registro de Expedientes Digitalizados al '.$mes.'/'.$ejercicio.' .', 1);
        redirect('inventario_archivos.php', false);
    } else {
        //failed
        $session->msg('d', 'Desafortunadamente no se pudo crear el registro.');
        redirect('add_inventario_archivos.php', false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page" style="height: 580px;">
    <div class="text-center">
        <h2 style="margin-top: 20px; margin-bottom: 30px; color: #3a3d44">Agregar Expedientes Digitalizados</h2>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="add_inventario_archivos.php" class="clearfix">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ejercicio" class="control-label">Ejercicio</label>
                    <select class="form-control" name="ejercicio" id="ejercicio">
                        <option value="">Escoge una opción</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024" selected="selected">2024</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="mes" class="control-label">Mes</label>
                    <select class="form-control" name="mes" id="mes">
                        <option value="">Escoge una opción</option>
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10">
                <div class="form-group">
                    <label for="total_archivos">Valor Absoluto</label>
                    <input class="form-control monto" type="number" id="total_archivos" name="total_archivos" >
                </div>
            </div>
			<div class="col-md-10">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" required>
                                <option value="">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
            <div class="col-md-10">
                        <div class="form-group">
                            <label for="observaciones">Notas de Digitalización</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="6"></textarea>
                        </div>
                    </div>
           
        </div>

        <div class="form-group clearfix">
            <a href="inventario_archivos.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="add_inventario_archivos" class="btn btn-info">Guardar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>