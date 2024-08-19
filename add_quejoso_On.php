
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar quejoso';
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


$generos = find_all('cat_genero');
$nacionalidades = find_all('cat_nacionalidades');
$municipios = find_all('cat_municipios');
$escolaridades = find_all('cat_escolaridad');
$ocupaciones = find_all('cat_ocupaciones');
$grupos_vuln = find_all('cat_grupos_vuln');
$discapacidades = find_all('cat_discapacidades');
$ppls = find_all('cat_ppl');
$comunidades = find_all('cat_comunidades');
?>

<?php /*header('Content-type: text/html; charset=utf-8');*/
if (isset($_POST['add_quejoso'])) {

    if (empty($errors)) {
        $pplTrue = isset($_POST['c1']) ? 1 : 0;
        $nombre = remove_junk($db->escape($_POST['nombre']));
        $paterno = remove_junk($db->escape($_POST['paterno']));
        $materno = remove_junk($db->escape($_POST['materno']));
        $id_cat_gen = remove_junk($db->escape($_POST['id_cat_gen']));
        $edad = remove_junk($db->escape($_POST['edad']));
        $id_cat_nacionalidad = remove_junk($db->escape($_POST['id_cat_nacionalidad']));
        $id_cat_mun = remove_junk($db->escape($_POST['id_cat_mun']));
        $id_cat_escolaridad = remove_junk($db->escape($_POST['id_cat_escolaridad']));
        $id_cat_ocup = remove_junk($db->escape($_POST['id_cat_ocup']));
        $leer_escribir = remove_junk($db->escape($_POST['leer_escribir']));
        $id_cat_grupo_vuln = remove_junk($db->escape($_POST['id_cat_grupo_vuln']));
        $id_cat_disc = remove_junk($db->escape($_POST['id_cat_disc']));
        $ppl = remove_junk($db->escape($_POST['ppl']));
        $id_cat_ppl = remove_junk($db->escape($_POST['id_cat_ppl']));
        $id_cat_comun = remove_junk($db->escape($_POST['id_cat_comun']));
        $telefono = remove_junk($db->escape($_POST['telefono']));
        $email = remove_junk($db->escape($_POST['email']));

        $nombreQ = remove_junk($db->escape($_POST['nombreQ']));
        $paternoQ = remove_junk($db->escape($_POST['paternoQ']));
        $maternoQ = remove_junk($db->escape($_POST['maternoQ']));
        $id_cat_genQ = remove_junk($db->escape($_POST['id_cat_genQ']));
        $edadQ = remove_junk($db->escape($_POST['edadQ']));
        $id_cat_nacionalidadQ = remove_junk($db->escape($_POST['id_cat_nacionalidadQ']));
        $id_cat_munQ = remove_junk($db->escape($_POST['id_cat_munQ']));
        $id_cat_escolaridadQ = remove_junk($db->escape($_POST['id_cat_escolaridadQ']));
        $id_cat_ocupQ = remove_junk($db->escape($_POST['id_cat_ocupQ']));
        $leer_escribirQ = remove_junk($db->escape($_POST['leer_escribirQ']));
        $id_cat_grupo_vulnQ = remove_junk($db->escape($_POST['id_cat_grupo_vulnQ']));
        $id_cat_discQ = remove_junk($db->escape($_POST['id_cat_discQ']));
        $id_cat_comunQ = remove_junk($db->escape($_POST['id_cat_comunQ']));
        $telefonoQ = remove_junk($db->escape($_POST['telefonoQ']));
        $emailQ = remove_junk($db->escape($_POST['emailQ']));
        $calleQ = remove_junk($db->escape($_POST['calleQ']));
        $numeroQ = remove_junk($db->escape($_POST['numeroQ']));
        $coloniaQ = remove_junk($db->escape($_POST['coloniaQ']));
		
		$accion=0;

$quejoso_nuevo = $nombreQ.$paternoQ.$maternoQ.$id_cat_genQ.$edadQ.$id_cat_nacionalidadQ.$id_cat_munQ.$id_cat_escolaridadQ.$id_cat_ocupQ.$leer_escribirQ;
$quejoso_nuevo .= $id_cat_grupo_vulnQ.$id_cat_discQ.$id_cat_comunQ;

$datos_persona = find_by_id_persona('cat_quejosos',$quejoso_nuevo,'id_cat_quejoso');

        if (!$pplTrue) {
			
			if($datos_persona['id'] > 0){
				
				$session->msg('s', " El quejoso/agraviado ya existe, te pedimos buscarlo en su apartado correspondiente.");
				?>
							<script language="javascript">				
								window.opener.location.reload();
								window.close();
							</script>
			<?php
			}else{
				
				$query2 = "INSERT INTO cat_quejosos (";
				$query2 .= "nombre,paterno,materno,id_cat_gen,edad,id_cat_nacionalidad,id_cat_mun,id_cat_escolaridad,id_cat_ocup,
							leer_escribir,id_cat_grupo_vuln,id_cat_disc,id_cat_comun,telefono,email,calle_quejoso,numero_quejoso,colonia_quejoso";
				$query2 .= ") VALUES (";
				$query2 .= " '{$nombreQ}','{$paternoQ}','{$maternoQ}','{$id_cat_genQ}','{$edadQ}','{$id_cat_nacionalidadQ}',
						'{$id_cat_munQ}','{$id_cat_escolaridadQ}','{$id_cat_ocupQ}','{$leer_escribirQ}','{$id_cat_grupo_vulnQ}',
						'{$id_cat_discQ}',{$id_cat_comunQ},'{$telefonoQ}','{$emailQ}','{$calleQ}','{$numeroQ}','{$coloniaQ}'";
				$query2 .= ")";
				//echo "2" . $query2;
				
				$query = "INSERT INTO cat_agraviados (";
				$query .= "nombre,paterno,materno,id_cat_gen,edad,id_cat_nacionalidad,id_cat_mun,id_cat_escolaridad,
						id_cat_ocup,leer_escribir,id_cat_grupo_vuln,id_cat_disc,ppl,id_cat_ppl,id_cat_comun,telefono,email";
				$query .= ") VALUES (";
				$query .= " '{$nombreQ}','{$paternoQ}','{$maternoQ}','{$id_cat_genQ}','{$edadQ}','{$id_cat_nacionalidadQ}',
						'{$id_cat_munQ}','{$id_cat_escolaridadQ}','{$id_cat_ocupQ}','{$leer_escribirQ}','{$id_cat_grupo_vulnQ}',
						'{$id_cat_discQ}',0,6,{$id_cat_comunQ},'{$telefonoQ}','{$emailQ}'";
				$query .= ")";

				if (($db->query($query2)) && ($db->query($query))) {
					//sucess
					$session->msg('s', " El quejoso/agraviado ha sido agregado con éxito.");
					insertAccion($user['id_user'], '"' . $user['username'] . '" agregó al quejoso: ' . $nombreQ . ' ' . $paternoQ . ' ' . $maternoQ . '.', 1);		
					insertAccion($user['id_user'], '"' . $user['username'] . '" agregó al agraviado: ' . $nombre . ' ' . $paterno . ' ' . $materno . '.', 1);					
	?>
					<script language="javascript">
						window.opener.location.reload();
						window.close();
					</script>
				<?php
				} else {
					//failed
					$session->msg('d', ' No se pudo agregar el quejoso/agraviado.');
					redirect('add_quejoso_On.php', false);
				}
			}
			
        } else {
			$agraviado_nuevo = $nombre.$paterno.$materno.$id_cat_gen.$edad.$id_cat_nacionalidad.$id_cat_mun.$id_cat_escolaridad.$id_cat_ocup.$leer_escribir;
			$agraviado_nuevo .= $id_cat_grupo_vuln.$id_cat_disc.$id_cat_comun;
			
			$datos_agraviado = find_by_id_persona('cat_agraviados',$agraviado_nuevo,'id_cat_agrav');
			if($datos_persona['id'] > 0){
				$session->msg('s', " El quejoso ya existe, te pedimos buscarlo en su apartado correspondiente.");					
			}else{
				
				$query4 = "INSERT INTO cat_quejosos (";
				$query4 .= "nombre,paterno,materno,id_cat_gen,edad,id_cat_nacionalidad,id_cat_mun,id_cat_escolaridad,id_cat_ocup,
							leer_escribir,id_cat_grupo_vuln,id_cat_disc,id_cat_comun,telefono,email,calle_quejoso,numero_quejoso,colonia_quejoso";
				$query4 .= ") VALUES (";
				$query4 .= " '{$nombreQ}','{$paternoQ}','{$maternoQ}','{$id_cat_genQ}','{$edadQ}','{$id_cat_nacionalidadQ}',
						'{$id_cat_munQ}','{$id_cat_escolaridadQ}','{$id_cat_ocupQ}','{$leer_escribirQ}','{$id_cat_grupo_vulnQ}',
						'{$id_cat_discQ}',{$id_cat_comunQ},'{$telefonoQ}','{$emailQ}','{$calleQ}','{$numeroQ}','{$coloniaQ}'";
				$query4 .= ")";
				 if (($db->query($query4))) {
						//sucess
						$session->msg('s', " El ciudadano quejoso ha sido agregado con éxito.");
						insertAccion($user['id_user'], '"' . $user['username'] . '" agregó al quejoso: ' . $nombreQ . ' ' . $paternoQ . ' ' . $maternoQ . '.', 1);					
					} else {
						//failed
						$session->msg('d', ' No se pudo agregar el ciudadano agraviada.');						
					}
			}
            //echo $query4;
			if($datos_agraviado['id'] > 0){
				$session->msg('s', " El agraviado ya existe, te pedimos buscarlo en su apartado correspondiente.");
				
			}else{
				$query3 = "INSERT INTO cat_agraviados (";
				$query3 .= "nombre,paterno,materno,id_cat_gen,edad,id_cat_nacionalidad,id_cat_mun,id_cat_escolaridad,
						id_cat_ocup,leer_escribir,id_cat_grupo_vuln,id_cat_disc,ppl,id_cat_ppl,id_cat_comun,telefono,email";
				$query3 .= ") VALUES (";
				$query3 .= " '{$nombre}','{$paterno}','{$materno}','{$id_cat_gen}','{$edad}','{$id_cat_nacionalidad}',
						'{$id_cat_mun}','{$id_cat_escolaridad}','{$id_cat_ocup}','{$leer_escribir}','{$id_cat_grupo_vuln}',
						'{$id_cat_disc}',{$ppl},{$id_cat_ppl},{$id_cat_comun},'{$telefono}','{$email}'";
				$query3 .= ")";
				
				 if (($db->query($query3))) {
						//sucess
						$session->msg('s', " El ciudadano agraviado ha sido agregado con éxito.");
						insertAccion($user['id_user'], '"' . $user['username'] . '" agregó al agraviado: ' . $nombre . ' ' . $paterno . ' ' . $materno . '.', 1);
				
					} else {
						//failed
						$session->msg('d', ' No se pudo agregar el ciudadano agraviada.');
					}
			}


            ?>
                <script language="javascript">				
                    window.opener.location.reload();
                    window.close();
                </script>
<?php
        }

    } else {
        $session->msg("d", $errors);
        redirect('add_quejoso_On.php', false);
    }
}
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<link rel="stylesheet" href="libs/css/main.css" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://harvesthq.github.io/chosen/chosen.css" rel="stylesheet" />
<style>
    body {
        zoom: 70%;
    }

    .login-page2 {
        width: 350px;
        height: 340px;
        margin: 7% auto;
        padding: 0 20px;
        background-color: #282A2F;
        border: 1px solid #000000;
        border-radius: 15px;
        box-shadow: 6px 20px 10px rgba(0, 0, 0, 0.082);
        margin-top: 30px;
    }

    .login-page2 .text-center {
        margin-bottom: 10px;
    }
</style>
<script type="text/javascript">

	
    function showMe(it, box) {
        var vis = (box.checked) ? "block" : "none";
        document.getElementById(it).style.display = vis;
		if(vis =='none'){
			document.querySelector('#nombre').required = false;
			document.querySelector('#paterno').required = false;
			document.querySelector('#materno').required = false;
			document.querySelector('#id_cat_gen').required = false;
			document.querySelector('#edad').required = false;
			document.querySelector('#id_cat_nacionalidad').required = false;
			document.querySelector('#id_cat_mun').required = false;
			document.querySelector('#id_cat_escolaridad').required = false;
			document.querySelector('#id_cat_ocup').required = false;
			document.querySelector('#leer_escribir').required = false;
			document.querySelector('#id_cat_disc').required = false;
			document.querySelector('#id_cat_grupo_vuln').required = false;
			document.querySelector('#id_cat_comun').required = false;
			document.querySelector('#ppl').required = false;
			document.querySelector('#id_cat_ppl').required = false;
		}else{
			document.querySelector('#nombre').required = true;
			document.querySelector('#paterno').required = true;
			document.querySelector('#materno').required = true;
			document.querySelector('#id_cat_gen').required = true;
			document.querySelector('#edad').required = true;
			document.querySelector('#id_cat_nacionalidad').required = true;
			document.querySelector('#id_cat_mun').required = true;
			document.querySelector('#id_cat_escolaridad').required = true;
			document.querySelector('#id_cat_ocup').required = true;
			document.querySelector('#leer_escribir').required = true;
			document.querySelector('#id_cat_disc').required = true;
			document.querySelector('#id_cat_grupo_vuln').required = true;
			document.querySelector('#id_cat_comun').required = true;
			document.querySelector('#ppl').required = true;
			document.querySelector('#id_cat_ppl').required = true;			
		}
    }
</script>
<?php header('Content-type: text/html; charset=utf-8');
?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar promovente</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_quejoso_On.php">
                <h1>Agregar datos promovente</h1>
                <div class="row" style="margin-top: 2%">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="nombreQ">Nombre</label>
                            <input type="text" class="form-control" name="nombreQ" placeholder="Nombre(s)" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="paternoQ">Apellido Paterno</label>
                            <input type="text" class="form-control" name="paternoQ" placeholder="Apellido Paterno" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="maternoQ">Apellido Materno</label>
                            <input type="text" class="form-control" name="maternoQ" placeholder="Apellido Materno" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="id_cat_genQ">Género</label>
                            <select class="form-control" name="id_cat_genQ" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($generos as $genero) : ?>
                                    <option value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="edadQ">Edad</label>
                            <input type="number" class="form-control" min="1" max="130" maxlength="4" name="edadQ" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="telefonoQ">Teléfono</label>
                            <input type="text" class="form-control" maxlength="10" name="telefonoQ" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="emailQ">Email</label>
                            <input type="text" class="form-control" name="emailQ" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_nacionalidadQ">Nacionalidad</label>
                            <select class="form-control" name="id_cat_nacionalidadQ" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($nacionalidades as $nacionalidad) : ?>
                                    <option value="<?php echo $nacionalidad['id_cat_nacionalidad']; ?>"><?php echo ucwords($nacionalidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_munQ">Municipio</label>
                            <select class="form-control" name="id_cat_munQ" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($municipios as $municipio) : ?>
                                    <option value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_escolaridadQ">Escolaridad</label>
                            <select class="form-control" name="id_cat_escolaridadQ" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($escolaridades as $escolaridad) : ?>
                                    <option value="<?php echo $escolaridad['id_cat_escolaridad']; ?>"><?php echo ucwords($escolaridad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_ocupQ">Ocupación</label>
                            <select class="form-control" name="id_cat_ocupQ" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($ocupaciones as $ocupacion) : ?>
                                    <option value="<?php echo $ocupacion['id_cat_ocup']; ?>"><?php echo ucwords($ocupacion['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="leer_escribirQ">¿Sabe leer y escribir?</label>
                            <select class="form-control" name="leer_escribirQ" required>
                                <option value="">Escoge una opción</option>
                                <option value="Leer">Leer</option>
                                <option value="Escribir">Escribir</option>
                                <option value="Ambos">Ambos</option>
                                <option value="Ninguno">Ninguno</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_discQ">¿Tiene alguna discapacidad?</label>
                            <select class="form-control" name="id_cat_discQ" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($discapacidades as $discapacidad) : ?>
                                    <option value="<?php echo $discapacidad['id_cat_disc']; ?>"><?php echo ucwords($discapacidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_cat_grupo_vulnQ">Grupo Vulnerable</label>
                            <select class="form-control" name="id_cat_grupo_vulnQ" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($grupos_vuln as $grupo_vuln) : ?>
                                    <option value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_comunQ">Comunidad</label>
                            <select class="form-control" name="id_cat_comunQ" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($comunidades as $comunidad) : ?>
                                    <option value="<?php echo $comunidad['id_cat_comun']; ?>"><?php echo ucwords($comunidad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="calleQ"> Calle</label>
                            <input type="text" class="form-control" name="calleQ" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="numeroQ">Núm.</label>
                            <input type="text" class="form-control" name="numeroQ" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="coloniaQ">Colonia</label>
                            <input type="text" class="form-control" name="coloniaQ" required>
                        </div>
                    </div>
                </div>

                <p class="text-left" style="font-weight: bold; font-size: 17px; margin-top:40px;">¿Quien presenta la queja es el "agraviado/a"?</p>
                <div style="margin-left: 110px;" class="popup caja" onclick="myFunction()">
                    <p style="font-size: 13px;">?</p>
                    <span class="popuptext" id="myPopup" style="font-size: 15px;">Si el quejoso es a su vez el agraviado, apague el botón. Si son diferentes, enciéndalo</span>
                </div>
                <label class="switch" style="float:left;">
                    <div class="row">
                        <input type="checkbox" id="c1" name="c1" onclick="showMe('div1', this)" checked>
                        <span class="slider round"></span>
                        <div>
                            <p style="margin-left: 150%; margin-top: -3%; font-size: 14px;">Sí/No</p>
                        </div>
                    </div>
                </label><br><br>

                <div id="div1" style="display:'';">
                    <h1>Agregar datos agraviado</h1>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre(s)"  required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="paterno">Apellido Paterno</label>
                                <input type="text" class="form-control" name="paterno"  id="paterno" placeholder="Apellido Paterno"  required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="materno">Apellido Materno</label>
                                <input type="text" class="form-control" name="materno"  id="materno" placeholder="Apellido Materno"  required>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="id_cat_gen">Género</label>
                                <select class="form-control" name="id_cat_gen"   id="id_cat_gen" required>
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
                                <input type="number" class="form-control" min="1" max="130" maxlength="4" name="edad"  id="edad" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" class="form-control" maxlength="10" name="telefono">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" name="email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_nacionalidad">Nacionalidad</label>
                                <select class="form-control" name="id_cat_nacionalidad"  id="id_cat_nacionalidad" required>
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($nacionalidades as $nacionalidad) : ?>
                                        <option value="<?php echo $nacionalidad['id_cat_nacionalidad']; ?>"><?php echo ucwords($nacionalidad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_mun">Municipio</label>
                                <select class="form-control" name="id_cat_mun"  id="id_cat_mun" required>
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($municipios as $municipio) : ?>
                                        <option value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_escolaridad">Escolaridad</label>
                                <select class="form-control" name="id_cat_escolaridad"  id="id_cat_escolaridad" required>
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($escolaridades as $escolaridad) : ?>
                                        <option value="<?php echo $escolaridad['id_cat_escolaridad']; ?>"><?php echo ucwords($escolaridad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_ocup">Ocupación</label>
                                <select class="form-control" name="id_cat_ocup"  id="id_cat_ocup" required>
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
                                <select class="form-control" name="leer_escribir"  id="leer_escribir" required>
                                    <option value="">Escoge una opción</option>
                                    <option value="Leer">Leer</option>
                                    <option value="Escribir">Escribir</option>
                                    <option value="Ambos">Ambos</option>
                                    <option value="ninguno">Ninguno</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_disc">¿Tiene alguna discapacidad?</label>
                                <select class="form-control" name="id_cat_disc"   id="id_cat_disc" required>
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($discapacidades as $discapacidad) : ?>
                                        <option value="<?php echo $discapacidad['id_cat_disc']; ?>"><?php echo ucwords($discapacidad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_cat_grupo_vuln">Grupo Vulnerable</label>
                                <select class="form-control" name="id_cat_grupo_vuln"  id="id_cat_grupo_vuln"  required>
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($grupos_vuln as $grupo_vuln) : ?>
                                        <option value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_cat_comun">Comunidad</label>
                                <select class="form-control" name="id_cat_comun"  id="id_cat_comun" required>
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($comunidades as $comunidad) : ?>
                                        <option value="<?php echo $comunidad['id_cat_comun']; ?>"><?php echo ucwords($comunidad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ppl">Persona Priv. de la Libertad</label>
                                <select class="form-control" name="ppl"  id="ppl" required>
                                    <option value="">Escoge una opción</option>
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_cat_ppl">Si es PPL, ¿Quién presenta la queja?</label>
                                <select class="form-control" name="id_cat_ppl"  id="id_cat_ppl" required>
                                    <option value="6">Escoge una opción</option>
                                    <?php foreach ($ppls as $ppl) : ?>
                                        <option value="<?php echo $ppl['id_cat_ppl']; ?>"><?php echo ucwords($ppl['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <button type="button" class="btn btn-md btn-success" onclick="javascript:window.close();">Cancelar</button>&nbsp;&nbsp;

                    <button type="submit" name="add_quejoso" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include_once('layouts/footer.php'); ?>