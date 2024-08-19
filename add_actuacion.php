<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Actuación';
require_once('includes/load.php');
$id_folio = last_id_folios();

// $queja = find_by_id_quejas((int)$_GET['id']);
$id_queja = last_id_actualiciones();
$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];
$areas = find_all('area');
$area_user = area_usuario2($id_user);
$estatales = find_autoridad_estatal();
$federales = find_autoridad_federal();
$id_area_creacion = $area_user['id_area'];
$area = $area_user['nombre_area'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 5) {
    page_require_level_exacto(5);
}
if ($nivel_user == 26) {
    page_require_level_exacto(26);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}
if ($nivel_user > 2 && $nivel_user < 5) :
    redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 26) :
    redirect('home.php');
endif;
if ($nivel_user > 26 && $nivel_user < 50) :
    redirect('home.php');
endif;
if ($nivel_user > 50 ) :
    redirect('home.php');
endif;


$inticadores_pat = find_all_pat_area($id_area_creacion,'actuaciones');
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_actuacion'])) {

    $req_fields = array('catalogo_actas', 'peticion');
    validate_fields($req_fields);

    if (empty($errors)) {
        $fecha_captura_acta = remove_junk($db->escape($_POST['fecha_captura_acta']));
        $catalogo_actas   = remove_junk($db->escape($_POST['catalogo_actas']));
        $descripcion   = remove_junk($db->escape($_POST['descripcion']));
        $autoridades   = remove_junk($db->escape($_POST['autoridades']));
        $autoridades_federales   = remove_junk($db->escape($_POST['autoridades_federales']));
        $peticion   = remove_junk($db->escape($_POST['peticion']));
        $num_exp_origen   = remove_junk($db->escape($_POST['num_exp_origen']));
        $nombre_desaparecido   = remove_junk($db->escape($_POST['nombre_desaparecido']));
		$id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));
        date_default_timezone_set('America/Mexico_City');
        $fecha_creacion_sistema = date('Y-m-d');

        if (count($id_queja) == 0) {
            $nuevo_id_queja = 1;
            $no_folio = sprintf('%04d', 1);
        } else {
            foreach ($id_queja as $nuevo) {
                $nuevo_id_queja = (int) $nuevo['contador'] + 1;
                $no_folio = sprintf('%04d', (int) $nuevo['contador'] + 1);
            }
        }

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int) $nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int) $nuevo['contador'] + 1);
            }
        }

        $year = date("Y");
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-ACT';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-ACT';
        $carpeta = 'uploads/actuaciones/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $name = $_FILES['adjunto']['name'];
        $size = $_FILES['adjunto']['size'];
        $type = $_FILES['adjunto']['type'];
        $temp = $_FILES['adjunto']['tmp_name'];

        $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
		
		/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}

        if ($move && $name != '') {
            $query = "INSERT INTO actuaciones (";
            $query .= "folio_actuacion,fecha_captura_acta,catalogo,descripcion,autoridades,autoridades_federales,peticion,adjunto,fecha_creacion_sistema,area_creacion,num_exp_origen,nombre_desaparecido,user_creador,id_area_creacion";
			if($inticadores_pat){
				$query .=  ", id_indicadores_pat";
			}
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$fecha_captura_acta}','{$catalogo_actas}','{$descripcion}','{$autoridades}','{$autoridades_federales}','{$peticion}','{$name}','{$fecha_creacion_sistema}','{$area}','{$num_exp_origen}','{$nombre_desaparecido}','{$id_user}',{$id_area_creacion}";
            if($inticadores_pat){
				$query .=  ", {$id_indicadores_pat}";
			}
			$query .= ")";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";
        } else {
            $query = "INSERT INTO actuaciones (";
            $query .= "folio_actuacion,fecha_captura_acta,catalogo,descripcion,autoridades,autoridades_federales,peticion,fecha_creacion_sistema,area_creacion,num_exp_origen,user_creador,id_area_creacion";
			if($inticadores_pat){
				$query .=  ", id_indicadores_pat";
			}
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$fecha_captura_acta}','{$catalogo_actas}','{$descripcion}','{$autoridades}','{$autoridades_federales}','{$peticion}','{$fecha_creacion_sistema}','{$area}','{$num_exp_origen}','{$id_user}',{$id_area_creacion}";
			 if($inticadores_pat){
				$query .=  ", {$id_indicadores_pat}";
			}
            $query .= ")";

            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";
        }
        if ($db->query($query) && $db->query($query2)) {
            //sucess
            $session->msg('s', " La actuación ha sido agregada con éxito.");
			insertAccion($user['id_user'], '"'.$user['username'].'" agregó actuacion, Folio: '.$folio.'.', 1);
            redirect('actuaciones.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la actuación.');
            redirect('add_actuacion.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_actuacion.php', false);
    }
}
?>
<script languague="javascript">
    function mostrar() {
        div = document.getElementById('flotante');
        div.style.display = '';
    }

    function cerrar() {
        div = document.getElementById('flotante');
        div.style.display = 'none';
    }

    function mostrar2() {
        div = document.getElementById('flotante2');
        div.style.display = '';
    }

    function cerrar2() {
        div = document.getElementById('flotante2');
        div.style.display = 'none';
    }

    function mostrar3() {
        div = document.getElementById('flotante3');
        div.style.display = '';
    }

    function cerrar3() {
        div = document.getElementById('flotante3');
        div.style.display = 'none';
    }

    function mostrar4() {
        div = document.getElementById('flotante4');
        div.style.display = '';
    }

    function cerrar4() {
        div = document.getElementById('flotante4');
        div.style.display = 'none';
    }
</script>
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar actuación <?php echo $id_area_creacion?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_actuacion.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div id="flotante" style=" background-color: #969696; color:black; display:none; border-radius: 5px;">
                                <div id="close" align="right" style="margin-bottom: -15px;">
                                    <svg onclick="javascript:cerrar();" style="width:24px;height:24px" viewBox="0 0 24 24">
                                        <path fill="red" d="M13.46,12L19,17.54V19H17.54L12,13.46L6.46,19H5V17.54L10.54,12L5,6.46V5H6.46L12,10.54L17.54,5H19V6.46L13.46,12Z" />
                                    </svg>
                                </div>
                                Fecha en que fué capturada la actuación.
                            </div>
                            <label for="fecha_captura_acta">Fecha de captura de actuación</label>
                            <svg onclick="javascript:mostrar();" style="width:20px;height:20px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M15.07,11.25L14.17,12.17C13.45,12.89 13,13.5 13,15H11V14.5C11,13.39 11.45,12.39 12.17,11.67L13.41,10.41C13.78,10.05 14,9.55 14,9C14,7.89 13.1,7 12,7A2,2 0 0,0 10,9H8A4,4 0 0,1 12,5A4,4 0 0,1 16,9C16,9.88 15.64,10.67 15.07,11.25M13,19H11V17H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z" />
                            </svg><br>
                            <input type="date" class="form-control" name="fecha_captura_acta">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="catalogo_actas">Tipo de actuación</label>
                            <select class="form-control" name="catalogo_actas">
                                <option value="">Elige una opción</option>
                                <option value="Acta Circunstanciada">Acta Circunstanciada</option>
                                <option value="Acompañamientos">Acompañamientos</option>
                                <option value="Minuta">Minuta</option>
                                <option value="Solicitud de Información">Solicitud de Información</option>
                                <option value="Colaboración UAPD">Colaboración UAPD</option>
                                <option value="Ficha de Persona Desaparecida">Ficha de Persona Desaparecida</option>
                                <option value="Visita Supervisión">Visita Supervisión</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div id="flotante2" style=" background-color: #EBEBEB; display:none; border-radius: 8px;">
                                <div id="close2" align="right" style="margin-bottom: -15px;">
                                    <svg onclick="javascript:cerrar2();" style="width:24px;height:24px" viewBox="0 0 24 24">
                                        <path fill="red" d="M13.46,12L19,17.54V19H17.54L12,13.46L6.46,19H5V17.54L10.54,12L5,6.46V5H6.46L12,10.54L17.54,5H19V6.46L13.46,12Z" />
                                    </svg>
                                </div>
                                Catálogo estatal de autoridades señaladas.
                            </div>
                            <label for="autoridades">Autoridad señalada (Estatal)</label>
                            <svg onclick="javascript:mostrar2();" style="width:20px;height:20px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M15.07,11.25L14.17,12.17C13.45,12.89 13,13.5 13,15H11V14.5C11,13.39 11.45,12.39 12.17,11.67L13.41,10.41C13.78,10.05 14,9.55 14,9C14,7.89 13.1,7 12,7A2,2 0 0,0 10,9H8A4,4 0 0,1 12,5A4,4 0 0,1 16,9C16,9.88 15.64,10.67 15.07,11.25M13,19H11V17H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z" />
                            </svg><br>

                            <select class="form-control" name="autoridades">
                                <option value="">Escoge una opción</option>
                                <option value="Otra">Otra</option>
                                <?php foreach ($estatales as $estatal) : ?>
                                    <option value="<?php echo $estatal['id_cat_aut']; ?>"><?php echo ucwords($estatal['nombre_autoridad']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div id="flotante3" style=" background-color: #EBEBEB; display:none; border-radius: 8px;">
                                <div id="close3" align="right" style="margin-bottom: -15px;">
                                    <svg onclick="javascript:cerrar3();" style="width:24px;height:24px" viewBox="0 0 24 24">
                                        <path fill="red" d="M13.46,12L19,17.54V19H17.54L12,13.46L6.46,19H5V17.54L10.54,12L5,6.46V5H6.46L12,10.54L17.54,5H19V6.46L13.46,12Z" />
                                    </svg>
                                </div>
                                Catálogo federal de autoridades señaladas.
                            </div>
                            <label for="autoridades_federales">Autoridad Señalada (Federal)</label>
                            <svg onclick="javascript:mostrar3();" style="width:20px;height:20px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M15.07,11.25L14.17,12.17C13.45,12.89 13,13.5 13,15H11V14.5C11,13.39 11.45,12.39 12.17,11.67L13.41,10.41C13.78,10.05 14,9.55 14,9C14,7.89 13.1,7 12,7A2,2 0 0,0 10,9H8A4,4 0 0,1 12,5A4,4 0 0,1 16,9C16,9.88 15.64,10.67 15.07,11.25M13,19H11V17H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z" />
                            </svg><br>
                            <select class="form-control" name="autoridades_federales">
                                <option value="">Escoge una opción</option>
                                <option value="Otra">Otra</option>
                                <?php foreach ($federales as $federal) : ?>
                                    <option value="<?php echo $federal['id_cat_aut']; ?>"><?php echo ucwords($federal['nombre_autoridad']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="peticion">Peticion</label>
                            <select class="form-control" name="peticion">
                                <option value="">Elige una opción</option>
                                <option value="Nacional">Nacional</option>
                                <option value="Oficio">Oficio</option>
                                <option value="Por canalización">Por canalización</option>
                                <option value="Por solicitud">Por solicitud</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                        <div id="flotante4" style=" background-color: #969696; color:black; display:none; border-radius: 5px;">
                                <div id="close" align="right" style="margin-bottom: -15px;">
                                    <svg onclick="javascript:cerrar4();" style="width:24px;height:24px" viewBox="0 0 24 24">
                                        <path fill="red" d="M13.46,12L19,17.54V19H17.54L12,13.46L6.46,19H5V17.54L10.54,12L5,6.46V5H6.46L12,10.54L17.54,5H19V6.46L13.46,12Z" />
                                    </svg>
                                </div>
                                Opcional. Agregar en caso de que el tipo de actuación ser acompañamiento.
                            </div>
                            <label for="num_exp_origen" class="control-label">Número de expediente de origen</label>
                            <svg onclick="javascript:mostrar4();" style="width:20px;height:20px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M15.07,11.25L14.17,12.17C13.45,12.89 13,13.5 13,15H11V14.5C11,13.39 11.45,12.39 12.17,11.67L13.41,10.41C13.78,10.05 14,9.55 14,9C14,7.89 13.1,7 12,7A2,2 0 0,0 10,9H8A4,4 0 0,1 12,5A4,4 0 0,1 16,9C16,9.88 15.64,10.67 15.07,11.25M13,19H11V17H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z" />
                            </svg><br>
                            <input type="name" class="form-control" name="num_exp_origen">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="descripcion">Descripción</label><br>
                            <textarea name="descripcion" class="form-control" id="descripcion" cols="50" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="adjunto">Adjuntar archivo</label>
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto">
                        </div>
                    </div>
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_desaparecido">Si fuera el caso, Nombre de Desaparecido</label>
                            <input type="text" accept="application/pdf" class="form-control" value="" name="nombre_desaparecido" id="nombre_desaparecido">
                        </div>
                    </div>
					<?php if($inticadores_pat){?>
                   <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" >
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<?php }?>
                </div>
                <div class="form-group clearfix">
                    <a href="actuaciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_actuacion" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>