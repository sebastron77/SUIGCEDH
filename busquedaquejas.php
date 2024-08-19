<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php 
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

$cat_medios_pres = find_all_medio_pres();
$cat_autoridades = find_all_aut_res();
$area = find_all_areas_quejas();
$cat_est_procesal = find_all('cat_est_procesal');

$cat_tipo_ambito = find_all('cat_tipo_ambito');
$generos = find_all('cat_genero');
$nacionalidades = find_all('cat_nacionalidades');
$municipios = find_all('cat_municipios');
$escolaridades = find_all('cat_escolaridad');
$ocupaciones = find_all('cat_ocupaciones');
$grupos_vuln = find_all('cat_grupos_vuln');
$discapacidades = find_all('cat_discapacidades');

$cat_entidad = find_all('cat_entidad_fed');
$comunidades = find_all('cat_comunidades');
$cat_tipo_resolucion = find_all('cat_tipo_res');
$derecho_vulnrado = find_all('cat_der_vuln');
$derecho_general = find_all('cat_derecho_general');
?>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<link rel="stylesheet" href="libs/css/main.css" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://harvesthq.github.io/chosen/chosen.css" rel="stylesheet" />

<script type="text/javascript">    
	 function usuarios(){		 
		 id = document.getElementById("id_area_asignada").value;
		  $.post("get_usuario_asignado.php", {
                    id_area: id
                }, function(data) {
                    $("#id_user_asignado").html(data);
                });
	 }
	
</script>
<?php header('Content-type: text/html; charset=utf-8');

?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-body">
            <form method="post" action="exec_busquedaquejas.php">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Generales en quejas</span>
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
                            <label for="autoridad">Autoridad Responsable </label>
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
                            <label for="maternoQ">Área Asignada</label>
							 <select class="form-control" name="id_area_asignada" id="id_area_asignada" onchange="usuarios()">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($area as $a) : ?>
                                    <option value="<?php echo $a['id_area']; ?>"><?php echo ucwords($a['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					 <?php $trabajadores = find_all_trabajadores_area($a['id_area']) ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_user_asignado">Se asigna a</label>
                            <select class="form-control" id="id_user_asignado" name="id_user_asignado">
								<option value="0">Escoge una opción</option>
							</select>
                        </div>
                    </div>                   
					</div>
                <div class="row">
				 <div class="col-md-3">
                        <div class="form-group">
                            <label for="paternoQ">Medio de Presentación </label>
							 <select class="form-control" name="id_cat_med_pres"  >
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_medios_pres as $medio_pres) : ?>
                                    <option value="<?php echo $medio_pres['id_cat_med_pres']; ?>"><?php echo ucwords($medio_pres['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_genQ">Estado Procesal </label>
                           <select class="form-control" name="estado_procesal"  >                               
						   <option value="">Escoge una opción</option>
                                <?php foreach ($cat_est_procesal as $est_pros) : ?>
                                    <option value="<?php echo $est_pros['id_cat_est_procesal']; ?>">
                                        <?php echo ucwords($est_pros['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>                  
                   
                
				<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_tipo_resolucion">Tipo de Resolución</label>
                            <select class="form-control" id="id_tipo_resolucion" name="id_tipo_resolucion" >							
                                <option value="">Escoge una opción</option>                              
                                <?php foreach ($cat_tipo_resolucion as $tipo_res) : ?>
                                    <option  value="<?php echo $tipo_res['id_cat_tipo_res']; ?>"><?php echo ucwords($tipo_res['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
				 <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_genQ">Tipo Ámbito </label>
                            <select class="form-control" name="id_tipo_ambito"  >
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_tipo_ambito as $ambito) : ?>
                                    <option  value="<?php echo $ambito['id_cat_tipo_ambito']; ?>"><?php echo ucwords($ambito['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					  <div class="col-md-3">
                        <div class="form-group">
                            <label for="derecho_general">Derecho general:</label>
						<select class="form-control" name="id_cat_derecho_general" id="id_cat_derecho_general">
								<option value="">Seleccione el Derecho General</option>
							 <?php foreach ($derecho_general as $derecho_gral) : ?>
                                    <option value="<?php echo $derecho_gral['id_cat_derecho_general']; ?>">
                                        <?php echo ucwords($derecho_gral['descripcion']); ?></option>
                                <?php endforeach; ?>
							</select>
                        </div>
                    </div>
               
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="derecho_violentado">Derecho violentado:</label>
								<select class="form-control" name="id_cat_derecho_vuln" id="id_cat_derecho_vuln">
								<option value="">Seleccione el Derecho Violentado</option>
							 <?php foreach ($derecho_vulnrado as $derecho_vuln) : ?>
                                    <option  value="<?php echo $derecho_vuln['id_cat_der_vuln']; ?>">

                                        <?php echo ucwords($derecho_vuln['descripcion']); ?></option>
                                <?php endforeach; ?>
							</select>
                        </div>
                    </div>
                   
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="ent_fed">Entidad Federativa</label>
                            <select class="form-control" name="ent_fed" id="ent_fed">
                                <option value="">Escoge una opción</option>
                                 <?php foreach ($cat_entidad as $id_cat_ent_fed) : ?>
                                            <option value="<?php echo $id_cat_ent_fed['descripcion']; ?>"><?php echo ucwords($id_cat_ent_fed['descripcion']); ?></option>
                                        <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_munQ">Municipio </label>
                            <select class="form-control" name="id_cat_munQ" id="id_cat_munQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($municipios as $municipio) : ?>
                                    <option value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="mediacion">En Mediación </label>
                            <select class="form-control" name="mediacion" id="mediacion">
                                <option value="">Escoge una opción</option>                                
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                            </select>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="medida_cautelar">Con Medida Cautelar </label>
                            <select class="form-control" name="medida_cautelar" id="medida_cautelar">
                                <option value="">Escoge una opción</option>                              
									<option value="0">No</option>
                                    <option value="1">Si</option>
                            </select>
                        </div>
                    </div>
                        <div class="col-md-3">
                        <div class="form-group">
                            <label for="nacionalidad">Área Creación</label>
                            <select class="form-control" name="id_area_creacion" id="id_area_creacion" >
                                <option value="">Escoge una opción</option>
                                <?php foreach ($area as $a) : ?>
                                    <option value="<?php echo $a['id_area']; ?>"><?php echo ucwords($a['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>					
                </div>
				<br><br>
				 <div class="panel-heading">
					<strong>
						<span class="glyphicon glyphicon-th"></span>
						<span>Datos Quejo/Promovente</span>
					</strong>
				</div>
				 
                <div class="row">
				<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_genQ">Género</label>
                            <select class="form-control" name="id_cat_genQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($generos as $genero): ?>
                                    <option value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_escolaridadQ">Escolaridad</label>
                            <select class="form-control" name="id_cat_escolaridadQ">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($escolaridades as $escolaridad): ?>
                                    <option value="<?php echo $escolaridad['id_cat_escolaridad']; ?>"><?php echo ucwords($escolaridad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_ocupQ">Ocupación   </label>
                            <select class="form-control" name="id_cat_ocupQ" >
                                <option value="">Escoge una opción</option>
                                <?php foreach ($ocupaciones as $ocupacion) : ?>
                                    <option value="<?php echo $ocupacion['id_cat_ocup']; ?>"><?php echo ucwords($ocupacion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="leer_escribirQ">Edad </label>
                            <select class="form-control" name="edadQ" >
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
                            <label for="leer_escribirQ">¿Sabe leer y escribir?   </label>
                            <select class="form-control" name="leer_escribirQ" >
                                <option value="">Escoge una opción</option>
                                <option value="Leer">Leer</option>
                                <option value="Escribir">Escribir</option>
                                <option value="Ambos">Ambos</option>
                                <option value="Sin Dato">Sin Dato</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_discQ">¿Tiene alguna discapacidad?   </label>
                            <select class="form-control" name="id_cat_discQ"  >
                                <option value="">Escoge una opción</option>
                                <?php foreach ($discapacidades as $discapacidad) : ?>
                                    <option value="<?php echo $discapacidad['id_cat_disc']; ?>"><?php echo ucwords($discapacidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_grupo_vulnQ">Grupo Vulnerable   </label>
                            <select class="form-control" name="id_cat_grupo_vulnQ"  >
                                <option value="">Escoge una opción</option>
                                <?php foreach ($grupos_vuln as $grupo_vuln) : ?>
                                    <option value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_comunQ">Comunidad    </label>
                            <select class="form-control" name="id_cat_comunQ"  >
                                <option value="">Escoge una opción</option>
                                <?php foreach ($comunidades as $comunidad) : ?>
                                    <option value="<?php echo $comunidad['id_cat_comun']; ?>"><?php echo ucwords($comunidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="nacionalidad">Nacionalidad</label>
                            <select class="form-control" name="id_cat_nacionalidadQ" id="id_cat_nacionalidadQ" >
                                <option value="">Escoge una opción</option>
                                 <?php foreach ($nacionalidades as $id_cat_nacionalidad) : ?>
                                            <option value="<?php echo $id_cat_nacionalidad['id_cat_nacionalidad']; ?>"><?php echo ucwords($id_cat_nacionalidad['descripcion']); ?></option>
                                        <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                   <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_munQ">Municipio</label>
                            <select class="form-control" name="id_cat_munP">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($municipios as $municipio): ?>
                                    <option value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_quejoso">Nombre Quejoso<label style="font-size: 12;color: red">(nombre/paterno/materno):</label></label>
                            <input type="text" class="form-control" name="nombre_quejoso" >
                        </div>
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