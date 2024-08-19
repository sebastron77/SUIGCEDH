
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Busqueda Actividades por Área';
require_once('includes/load.php');


$user = current_user();
$id_user = $user['id_user'];
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


$cat_areas = find_all_areasFull();
?>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<link rel="stylesheet" href="libs/css/main.css" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://harvesthq.github.io/chosen/chosen.css" rel="stylesheet" />

<?php header('Content-type: text/html; charset=utf-8');

?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-body">
            <form method="post" action="exec_busquedapat.php" target='_blank'>
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Generales</span>
            </strong>
        </div>        

		 <div class="row">
			
			<div class="col-md-3">
                <div class="form-group">
				<label for="ejercicio">Ejercicio</label>
                     <div class="form-check">
					  <?php for ($i = 2024; $i <= (int) date("Y"); $i++) { ?>                           
						  <input class="form-check-input" value="<?php echo $i;?>" type="radio" name="ejercicio" id="flexRadioDefault<?php echo $i;?>" <?php echo ($i===(int) date("Y"))?"checked='checked'":""; ?>>
						  <label class="form-check-label" for="flexRadioDefault<?php echo $i;?>" >
							<?php echo $i;?>
						  </label>	
						&nbsp;&nbsp;&nbsp;
					<?php }?>	
					</div>
                </div>
            </div>
			
			
            </div>
					
		 <div class="row">
			<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area">Área</label>
                             <select class="form-control" name="id_area"  required >
                                <option value="0">Escoge una opción</option>
                                <?php foreach ($cat_areas as $datos) : ?>
								<?php if($datos['visible']=='1'): ?>
                                    <option value="<?php echo $datos['id_area']; ?>"><?php echo ucwords($datos['nombre_area']); ?></option>
									<?php endif; ?>
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