<?php
$page_title = 'Editar Actuación';
require_once('includes/load.php');

// page_require_level(4);
?>
<?php

$estatales = find_autoridad_estatal();
$federales = find_autoridad_federal();

$e_actuacion = find_by_id_actuacion((int)$_GET['id']);
if (!$e_actuacion) {
    $session->msg("d", "id de actuación no encontrado.");
    redirect('actuaciones.php');
}
$user = current_user();
$nivel_user = $user['user_level'];

$id_user = $user['id_user'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 5) {
    page_require_level_exacto(5);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 19) {
    page_require_level_exacto(19);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}
if ($nivel_user > 2 && $nivel_user < 5) :
    redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user == 7) :
    redirect('home.php');
endif;
if ($nivel_user ==19) :
    redirect('home.php');
endif;
$inticadores_pat = find_all_pat_area($e_actuacion['id_area_creacion'],'actuaciones');
?>

<?php
if (isset($_POST['edit_actuacion'])) {
    $req_fields = array('catalogo', 'peticion');
    validate_fields($req_fields);
    if (empty($errors)) {
        $id = (int)$e_actuacion['id_actuacion'];
        $fecha_captura_acta = remove_junk($db->escape($_POST['fecha_captura_acta']));
        $catalogo   = remove_junk($db->escape($_POST['catalogo']));
        $descripcion   = remove_junk($db->escape($_POST['descripcion']));
        $autoridades   = remove_junk($db->escape($_POST['autoridades']));
        $autoridades_federales   = remove_junk($db->escape($_POST['autoridades_federales']));
        $peticion   = remove_junk($db->escape($_POST['peticion']));
        $nombre_desaparecido   = remove_junk($db->escape($_POST['nombre_desaparecido']));
        $num_exp_origen   = remove_junk($db->escape($_POST['num_exp_origen']));
		$id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));
        date_default_timezone_set('America/Mexico_City');
        $fecha_creacion_sistema = date('Y-m-d');

        $folio_editar = $e_actuacion['folio_actuacion'];
        $resultado = str_replace("/", "-", $folio_editar);
        $carpeta = 'uploads/actuaciones/' . $resultado;

        $name = $_FILES['adjunto']['name'];
        $size = $_FILES['adjunto']['size'];
        $type = $_FILES['adjunto']['type'];
        $temp = $_FILES['adjunto']['tmp_name'];

        //Verificamos que exista la carpeta y si sí, guardamos el pdf
        if (is_dir($carpeta)) {
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        } else {
            mkdir($carpeta, 0777, true);
            $move =  move_uploaded_file($temp, $carpeta . "/" . $name);
        }

        if ($name != '') {
            $sql = "UPDATE actuaciones SET fecha_captura_acta='{$fecha_captura_acta}', catalogo='{$catalogo}', descripcion='{$descripcion}', autoridades='{$autoridades}',nombre_desaparecido='{$nombre_desaparecido}', 
			autoridades_federales='{$autoridades_federales}', num_exp_origen='{$num_exp_origen}', peticion='{$peticion}', adjunto='{$name}' ";
			if($inticadores_pat){
				$sql .=  ", id_indicadores_pat={$id_indicadores_pat}";
			}
			$sql .=  " WHERE id_actuacion='{$db->escape($id)}'";
        }
        if ($name == '') {
            $sql = "UPDATE actuaciones SET fecha_captura_acta='{$fecha_captura_acta}', catalogo='{$catalogo}', descripcion='{$descripcion}', autoridades='{$autoridades}', nombre_desaparecido='{$nombre_desaparecido}',
			autoridades_federales='{$autoridades_federales}', num_exp_origen='{$num_exp_origen}', peticion='{$peticion}'  ";
			 if($inticadores_pat){
				$sql .=  ", id_indicadores_pat={$id_indicadores_pat}";
			}
			$sql .= " WHERE id_actuacion='{$db->escape($id)}'";
        }
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Información Actualizada ");
            redirect('actuaciones.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos.');
            redirect('edit_actuacion.php?id=' . (int)$e_actuacion['id'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_actuacion.php?id=' . (int)$e_actuacion['id'], false);
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
</script>

<?php include_once('layouts/header.php'); ?>
<div class="row">

    <!-- <p><a href="javascript:mostrar();">Mostrar</a></p> -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar actuación <?php echo $e_actuacion['folio_actuacion']; ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_actuacion.php?id=<?php echo (int)$e_actuacion['id_actuacion']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <div id="flotante" style=" background-color: #EBEBEB; display:none; border-radius: 8px;">
                                <div id="close" align="right" style="margin-bottom: -15px;">
                                    <svg onclick="javascript:cerrar();" style="width:24px;height:24px" viewBox="0 0 24 24">
                                        <path fill="red" d="M13.46,12L19,17.54V19H17.54L12,13.46L6.46,19H5V17.54L10.54,12L5,6.46V5H6.46L12,10.54L17.54,5H19V6.46L13.46,12Z" />
                                    </svg>
                                </div>
                                Fecha de captura de actuación.
                            </div>
                            <label for="fecha_captura_acta">Captura de actuación</label>
                            <svg onclick="javascript:mostrar();" style="width:20px;height:20px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M15.07,11.25L14.17,12.17C13.45,12.89 13,13.5 13,15H11V14.5C11,13.39 11.45,12.39 12.17,11.67L13.41,10.41C13.78,10.05 14,9.55 14,9C14,7.89 13.1,7 12,7A2,2 0 0,0 10,9H8A4,4 0 0,1 12,5A4,4 0 0,1 16,9C16,9.88 15.64,10.67 15.07,11.25M13,19H11V17H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z" />
                            </svg><br>
                            <input type="date" class="form-control" name="fecha_captura_acta" value="<?php echo remove_junk($e_actuacion['fecha_captura_acta']); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="catalogo">Tipo de actuación</label>
                            <select class="form-control" name="catalogo">
                                <option value="Acta Circunstanciada" <?php if ($e_actuacion['catalogo'] === 'Acta Circunstanciada') echo 'selected="selected"'; ?>>Acta Circunstanciada</option>
                                <option value="Acompañamientos" <?php if ($e_actuacion['catalogo'] === 'Acompañamientos') echo 'selected="selected"'; ?>>Acompañamientos</option>
								<option value="Minuta" <?php if ($e_actuacion['catalogo'] === 'Minuta') echo 'selected="selected"'; ?>>Minuta</option>
                                <option value="Solicitud de Información" <?php if ($e_actuacion['catalogo'] === 'Solicitud de Información') echo 'selected="selected"'; ?>>Solicitud de Información</option>
								<option value="Colaboración UAPD" <?php if ($e_actuacion['catalogo'] === 'Colaboración UAPD') echo 'selected="selected"'; ?>>Colaboración UAPD</option>
                                <option value="Ficha de Persona Desaparecida" <?php if ($e_actuacion['catalogo'] === 'Ficha de Persona Desaparecida') echo 'selected="selected"'; ?>>Ficha de Persona Desaparecida</option>
								<option value="Visita Supervisión" <?php if ($e_actuacion['catalogo'] === 'Visita Supervisión') echo 'selected="selected"'; ?>>Visita Supervisión</option>
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
                                
                                    <option value="">Escoge una opcion</option>
                                
                                <?php foreach ($estatales as $estatal) : ?>
                                    <option <?php if ($e_actuacion['autoridades'] === $estatal['id_cat_aut']) echo 'selected="selected"'; ?> value="<?php echo $estatal['id_cat_aut']; ?>"><?php echo ucwords($estatal['nombre_autoridad']); ?></option>
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
                                
                                    <option value="">Escoge una opcion</option>
                                
                                <?php foreach ($federales as $federal) : ?>
                                    <option <?php if ($e_actuacion['autoridades_federales'] === $federal['id_cat_aut']) echo 'selected="selected"'; ?> value="<?php echo $federal['id_cat_aut']; ?>"><?php echo ucwords($federal['nombre_autoridad']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="peticion">Petición</label>
                            <select class="form-control" name="peticion">
                                <option value="Nacional" value="Nacional" <?php if ($e_actuacion['peticion'] === 'Nacional') echo 'selected="selected"'; ?>>Nacional</option>
                                <option value="Oficio" value="Oficio" <?php if ($e_actuacion['peticion'] === 'Oficio') echo 'selected="selected"'; ?>>Oficio</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="num_exp_origen">Numero de expediente de origen</label>
                            <input type="text" accept="application/pdf" class="form-control" value="<?php echo remove_junk($e_actuacion['num_exp_origen']); ?>" name="num_exp_origen" id="num_exp_origen">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="descripcion">Descripción</label><br>
                            <textarea name="descripcion" class="form-control" value="<?php echo remove_junk(($e_actuacion['descripcion'])); ?>" id="descripcion" cols="50" rows="2"><?php echo remove_junk(($e_actuacion['descripcion'])); ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="adjunto">Archivo adjunto</label>
                            <input type="file" accept="application/pdf" class="form-control" name="adjunto" id="adjunto" value="uploads/actuaciones/<?php echo $e_actuacion['adjunto']; ?>">
                            <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_actuacion['adjunto']); ?><?php ?></label>
                        </div>
                    </div>
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_desaparecido">Si fuera el caso, Nombre de Desaparecido</label>
                            <input type="text" accept="application/pdf" class="form-control" value="<?php echo remove_junk($e_actuacion['nombre_desaparecido']); ?>" name="nombre_desaparecido" id="nombre_desaparecido">
                        </div>
                    </div>
					<?php if($inticadores_pat){?>
                   <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" >
                                <option value="0">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option <?php if ($e_actuacion['id_indicadores_pat'] === $datos['id_indicadores_pat']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<?php }?>
                </div>
                <div class="f
                </div>

                <div class="form-group clearfix">
                    <a href="actuaciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_actuacion" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>



<?php include_once('layouts/footer.php'); ?>