<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Busqueja Orientaciones';
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

$areas_all = find_all('area');
$grupos_vuln = find_all('cat_grupos_vuln');
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
            <form method="post" action="exec_busquedacapacitaciones.php">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Generales en Capacitaciones</span>
            </strong>
        </div>        

		 <div class="row">
			<div class="col-md-4">
                        <div class="form-group">
                            <label for="years">Ejercicio</label>
                             <select class="form-control" name="years"  >
                                <option value="0">Escoge una opción</option>
                                <?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
								echo "<option value='".$i."'>".$i."</option>";
								}?>	
                            </select>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="mes">Mes</label>
                             <select class="form-control" name="mes"  >
                                <option value="0">Escoge una opción</option>
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
					
			<div class="col-md-4">
                        <div class="form-group">
                            <label for="id_area">Área</label>
                             <select class="form-control" name="id_area"  >
                                <option value="">Escoge una opción</option>
                                <?php foreach ($areas_all as $area) : ?>
                    <option value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="tipo_capacitacion">Tipo de Divulgación</label>
                             <select class="form-control" name="tipo_capacitacion"  >
                                <option value="">Escoge una opción</option>
                                <option value="Impartida">Impartida</option>
                                <option value="Tomada">Tomada</option>
                            </select>
                        </div>
                    </div>
		 
				<div class="col-md-4">
                        <div class="form-group">
                            <label for="medio_presentacion">Tipo Evento</label>
                                <select class="form-control" name="tipo_evento" >
                                <option value="">Escoge una opción</option>
                                <option value="Capacitación">Capacitación</option>
                                <option value="Conferencia">Conferencia</option>
                                <option value="Curso">Curso</option>
                                <option value="Divulgación">Divulgación</option>
                                <option value="Taller">Taller</option>
                                <option value="Plática">Plática</option>
                                <option value="Diplomado">Diplomado</option>
                                <option value="Foro">Foro</option>
                                <option value="Conversatorios">Conversatorios</option>
                                <option value="Congreso">Congreso</option>
                                <option value="Feria">Feria</option>
                                <option value="Marcha">Marcha</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>
					
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="modalidad">Modalidad</label>
							 <select class="form-control" name="modalidad" >
                                <option value="">Escoge una opción</option>
                                <option value="Presencial">Presencial</option>
                                <option value="En línea">En línea</option>
                                <option value="Híbrido">Híbrido</option>
                            </select>
                        </div>
                    </div>
	 </div>        

		 <div class="row">				
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="asistentes">Asistentes</label>
							 <select class="form-control" name="asistentes"  >
                                <option value="">Escoge una opción</option>                                
                                <option value="asistentes_hombres">Hombres</option>
                                <option value="asistentes_mujeres">Mujeres</option>
                                <option value="asistentes_nobinario">No Binarios</option>
                                <option value="asistentes_otros">Otro</option>
                            </select>
                        </div>
                    </div>
					
               
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="edad">Edad</label>
							 <select class="form-control" name="edad"  >
                                <option value="">Escoge una opción</option>
                                <option value="asistentes_10">De 0 a 11 años</option>
                                <option value="asistentes_20">De 12 a 17 años</option>
                                <option value="asistentes_30">De 18 a 30 años</option>
                                <option value="asistentes_40">De 31 a 40 años</option>
                                <option value="asistentes_50">De 41 a 50 años</option>
                                <option value="asistentes_60">De 51 a 60 años</option>
                                <option value="asistentes_70">De 60 o más</option>
                                <option value="asistentes_80">Sin Dato</option>
                            </select>
                        </div>
                    </div>
					
					
				<div class="col-md-4">
                        <div class="form-group">
                            <label for="id_cat_grupo_vuln">Grupo Vulnerable</label>
							 <select class="form-control" name="id_cat_grupo_vuln"  >
                                <option value="">Escoge una opción</option>
                                <?php foreach ($grupos_vuln as $grupo_vuln) : ?>
                                    <option value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>
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