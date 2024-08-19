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

$cat_medios_pres = find_all_medio_pres();
$cat_autoridades = find_all_aut_res();
$area = find_all_areas_quejas();
$dialectos = find_dialectos('1');
$localidades = find_localidadesOC('1');
$generos = find_all('cat_genero');
$nacionalidades = find_all('cat_nacionalidades');
$municipios = find_all('cat_municipios');
$escolaridades = find_all('cat_escolaridad');
$ocupaciones = find_all('cat_ocupaciones');
$grupos_vuln = find_all('cat_grupos_vuln');
$area_creacion = find_all_areaCreacion(1);

$cat_entidad = find_all('cat_entidad_fed');
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
            <form method="post" action="exec_busquedaorientaciones.php">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Generales en Orientaciones</span>
            </strong>
        </div>        

		 <div class="row">
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
                            <label for="autoridad">Autoridad señalada como responsable </label>
                             <select class="form-control" name="id_cat_aut"  >
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_autoridades as $autoridades) : ?>
                                    <option value="<?php echo $autoridades['id_cat_aut']; ?>"><?php echo ucwords($autoridades['nombre_autoridad']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
		 
				<div class="col-md-3">
                        <div class="form-group">
                            <label for="medio_presentacion">Medio de Presentación </label>
							 <select class="form-control" name="medio_presentacion"  >
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_medios_pres as $medio_pres) : ?>
                                    <option value="<?php echo $medio_pres['id_cat_med_pres']; ?>"><?php echo ucwords($medio_pres['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="nivel_estudios">Nivel de Estudios</label>
                            <select class="form-control" name="nivel_estudios">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($escolaridades as $escolaridad): ?>
                                    <option value="<?php echo $escolaridad['id_cat_escolaridad']; ?>"><?php echo ucwords($escolaridad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ocupacion">Ocupación   </label>
                            <select class="form-control" name="ocupacion" >
                                <option value="">Escoge una opción</option>
                                <?php foreach ($ocupaciones as $ocupacion) : ?>
                                    <option value="<?php echo $ocupacion['id_cat_ocup']; ?>"><?php echo ucwords($ocupacion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="edad">Edad </label>
                            <select class="form-control" name="edad" >
                                <option value="">Escoge una opción</option>
                                <option value="1">0-10</option>
                                <option value="2">11-20</option>
                                <option value="3">21-30</option>
                                <option value="4">31-40</option>
                                <option value="5">41-50</option>
                                <option value="6">51-60</option>
                                <option value="7">Mayor a 61</option>
                            </select>
                        </div>
                    </div>
					
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="lengua">Dialecto</label>
                            <select class="form-control" name="lengua">
                                <option value="">Escoge una opción</option>                                
								<?php foreach ($dialectos as $lengua) : ?>
                                    <option value="<?php echo $lengua['lengua']; ?>"><?php echo ucwords($lengua['lengua']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="grupo_vulnerable">Grupo Vulnerable   </label>
                            <select class="form-control" name="grupo_vulnerable"  >
                                <option value="">Escoge una opción</option>
                                <?php foreach ($grupos_vuln as $grupo_vuln) : ?>
                                    <option value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>							
					
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Género</label>
                            <select class="form-control" name="sexo">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($generos as $genero): ?>
                                    <option value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>				
					
                   <div class="col-md-3">
                        <div class="form-group">
                            <label for="entidad">Entidad</label>
                            <select class="form-control" name="entidad">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($municipios as $municipio): ?>
                                    <option value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					
                   <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_mun">Municipio</label>
                            <select class="form-control" name="id_cat_mun">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($municipios as $municipio): ?>
                                    <option value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="municipio_localidad">Localidad</label>
                            <select class="form-control" name="municipio_localidad">
                                <option value="">Escoge una opción</option>
                               <?php foreach ($localidades as $lugar): ?>
                                    <option value="<?php echo $lugar['municipio_localidad']; ?>"><?php echo ucwords($lugar['municipio_localidad']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>										
					
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="nacionalidad">Nacionalidad</label>
                            <select class="form-control" name="nacionalidad" id="nacionalidad" >
                                <option value="">Escoge una opción</option>
                                 <?php foreach ($nacionalidades as $id_cat_nacionalidad) : ?>
                                            <option value="<?php echo $id_cat_nacionalidad['id_cat_nacionalidad']; ?>"><?php echo ucwords($id_cat_nacionalidad['descripcion']); ?></option>
                                        <?php endforeach; ?>
                                                           
                            </select>
                        </div>
                    </div>
					
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_completo">Nombre Completo</label>
                            <input type="text" class="form-control" name="nombre_completo" >
                        </div>
                    </div>
					
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="nacionalidad">Área Creación</label>
                            <select class="form-control" name="id_area_creacion" id="id_area_creacion" >
                                <option value="">Escoge una opción</option>
                                 <?php foreach ($area_creacion as $datos) : ?>
                                            <option value="<?php echo $datos['id_area']; ?>"><?php echo ucwords($datos['nombre_area']); ?></option>
                                        <?php endforeach; ?>
                                                           
                            </select>
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