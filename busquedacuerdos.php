<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Busqueja QUejas';
require_once('includes/load.php');


$user = current_user();
$id_user = $user['id_user'];
$busca_area = area_usuario($id_user);
$otro = $busca_area['nivel_grupo'];
$nivel = $user['user_level'];


if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 5) {
    page_require_level_exacto(5);
}
if ($nivel == 7) {
    page_require_level_exacto(7);
}
if ($nivel == 19) {
    page_require_level_exacto(19);
}

if ($nivel > 2 && $nivel < 5) :
    redirect('home.php');
endif;
if ($nivel > 5 && $nivel < 7) :
    redirect('home.php');
endif;
if ($nivel > 7 && $nivel < 19) :
    redirect('home.php');
endif;


$area = find_all_areas_quejas();

?>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<link rel="stylesheet" href="libs/css/main.css" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://harvesthq.github.io/chosen/chosen.css" rel="stylesheet" />

<script type="text/javascript">
    function showMe(it, box) {
        var vis = (box.checked) ? "block" : "none";
        document.getElementById(it).style.display = vis;
    }
</script>
<?php header('Content-type: text/html; charset=utf-8');

?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-body">
            <form method="post" action="exec_busquedacuerdos.php">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Generales en quejas <?php echo $busca_area['id_area'];?></span>
            </strong>
        </div>              
              <div class="row" >
				<div class="col-md-3">
                        <div class="form-group">
                            <label for="autoridad">Ejercicio</label>
                             <select class="form-control" name="years"  >
                                <option value="0">Escoge una opción</option>
                                <?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
								echo "<option value='".$i."'>".$i."</option>";
								}?>	
                            </select>
                        </div>
                    </div>
                   
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="maternoQ">Área Asignada</label>
							 <select class="form-control" name="id_area_asignada" <?php if($nivel==5){ echo "disabled='true'";} ?> >
                                <option value="">Escoge una opción</option>
                                <?php foreach ($area as $a) : ?>
                                    <option <?php if($nivel==5){ if ($busca_area['id_area'] === $a['id_area']) echo 'selected="selected"'; } ?> value="<?php echo $a['id_area']; ?>"><?php echo ucwords($a['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
							<?php if($nivel==5){  echo ' <input type="hidden" value="'.$busca_area['id_area'].'" name="id_area_asignada" >'; } ?>
                        </div>
                    </div>
					
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_acuerdo">Fecha de Acuerdos</label>
                            <input type="date" class="form-control" name="fecha_acuerdo">
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