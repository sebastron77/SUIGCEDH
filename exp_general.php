<?php
$page_title = 'Editar Expediente Laboral';
require_once('includes/load.php');

$areas = find_all_area_orden('area');
$tipo_int = find_all('cat_tipo_integrante');
$cat_puestos = find_all('cat_puestos');
$e_detalle = find_by_id('detalles_usuario', (int)$_GET['id'], 'id_det_usuario');

$user = current_user();
$nivel_user = $user['user_level'];

if (!$e_detalle) {
    $session->msg("d", "id de usuario no encontrado.");
    redirect('detalles_usuario.php');
}

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}
if ($nivel_user == 2) {
    page_require_level_exacto(2);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
}
if ($nivel_user > 2 && $nivel_user < 14) :
    redirect('home.php');
endif;
if ($nivel_user > 14) {
    redirect('home.php');
}
if (!$nivel_user) {
    redirect('home.php');
}
?>

<?php
if (isset($_POST['update'])) {
    if (empty($errors)) {
        $id = (int)$e_detalle['id_det_usuario'];
        $nombre = $e_detalle['nombre'];
        $apellidos = $e_detalle['apellidos'];
        $puesto = $db->escape($_POST['id_cat_puestos']);
        $id_area = $db->escape($_POST['id_area']);
        $monto_bruto = $db->escape($_POST['monto_bruto']);
        $monto_neto = $db->escape($_POST['monto_neto']);
        $tipo_inte = $db->escape($_POST['tipo_inte']);
        $clave = $db->escape($_POST['clave']);
        $niv_puesto = $db->escape($_POST['niv_puesto']);
        $tiene_seguro = $db->escape($_POST['tiene_seguro']);
        $nss = $db->escape($_POST['nss']);
        $no_empleado = $db->escape($_POST['no_empleado']);
        $nombre_cargo = $db->escape($_POST['nombre_cargo']);
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_conclusion = $_POST['fecha_conclusion'];
        
        $puesto2 = $e_detalle['id_cat_puestos'];
        $id_area2 = $e_detalle['id_area'];
        $clave2 = $e_detalle['clave'];
        $niv_puesto2 = $e_detalle['niv_puesto'];
        $fecha_inicio2 = $e_detalle['fecha_inicio'];
        $fecha_conclusion2 = $e_detalle['fecha_conclusion'];

        if ($puesto != $e_detalle['id_cat_puestos']) {
            $query = "INSERT INTO rel_hist_exp_int (";
            $query .= "id_detalle_usuario, id_cat_puestos, id_area, clave, niv_puesto, fecha_inicio, fecha_conclusion, fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$id}', '{$puesto2}', '{$id_area2}', '{$clave2}', '{$niv_puesto2}',";
			$fecha_inicio2 == ''?$query .= "NULL," :$query .=" '{$fecha_inicio2}',";
			$fecha_conclusion2 == ''?$query .= "NULL," :$query .=" '{$fecha_conclusion2}',";
			$query .= "  NOW()";
            $query .= ")";
			echo $query;
            $insert = $db->query($query);
        }

        $carpeta = 'uploads/personal/expediente/' . $id;
        $carpetaCrede = 'uploads/personal/credenciales/' . $id;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
		
        if (!is_dir($carpetaCrede)) {
            mkdir($carpetaCrede, 0777, true);
        }

        $nameFoto = $_FILES['foto_credencial']['name'];
        $sizeFoto = $_FILES['foto_credencial']['size'];
        $typeFoto = $_FILES['foto_credencial']['type'];
        $tempFoto = $_FILES['foto_credencial']['tmp_name'];
        $moveFoto = move_uploaded_file($tempFoto, $carpetaCrede . "/" . $nameFoto);

		$nameActa = $_FILES['acta_nacimiento']['name'];
        $sizeActa = $_FILES['acta_nacimiento']['size'];
        $typeActa = $_FILES['acta_nacimiento']['type'];
        $tempActa = $_FILES['acta_nacimiento']['tmp_name'];
        $moveActa = move_uploaded_file($tempActa, $carpeta . "/" . $nameActa);

        $nameCartaAnt = $_FILES['carta_no_ant']['name'];
        $sizeCartaAnt = $_FILES['carta_no_ant']['size'];
        $typeCartaAnt = $_FILES['carta_no_ant']['type'];
        $tempCartaAnt = $_FILES['carta_no_ant']['tmp_name'];
        $moveCartaAnt = move_uploaded_file($tempCartaAnt, $carpeta . "/" . $nameCartaAnt);

        $nameConstIn = $_FILES['const_no_in']['name'];
        $sizeConstIn = $_FILES['const_no_in']['size'];
        $typeConstIn = $_FILES['const_no_in']['type'];
        $tempConstIn = $_FILES['const_no_in']['tmp_name'];
        $moveConstIn = move_uploaded_file($tempConstIn, $carpeta . "/" . $nameConstIn);

        $nameCompDom = $_FILES['comp_dom']['name'];
        $sizeCompDom = $_FILES['comp_dom']['size'];
        $typeCompDom = $_FILES['comp_dom']['type'];
        $tempCompDom = $_FILES['comp_dom']['tmp_name'];
        $moveCompDom = move_uploaded_file($tempCompDom, $carpeta . "/" . $nameCompDom);

        $nameCartaRec1 = $_FILES['carta_rec1']['name'];
        $sizeCartaRec1 = $_FILES['carta_rec1']['size'];
        $typeCartaRec1 = $_FILES['carta_rec1']['type'];
        $tempCartaRec1 = $_FILES['carta_rec1']['tmp_name'];
        $moveCartaRec1 = move_uploaded_file($tempCartaRec1, $carpeta . "/" . $nameCartaRec1);

        $nameCartaRec2 = $_FILES['carta_rec2']['name'];
        $sizeCartaRec2 = $_FILES['carta_rec2']['size'];
        $typeCartaRec2 = $_FILES['carta_rec2']['type'];
        $tempCartaRec2 = $_FILES['carta_rec2']['tmp_name'];
        $moveCartaRec2 = move_uploaded_file($tempCartaRec2, $carpeta . "/" . $nameCartaRec2);

        $sql = "UPDATE detalles_usuario SET nombre='{$e_detalle['nombre']}' ";
        if ($nameFoto !== '') {
            $sql .= ",foto_credencial='{$nameFoto}'";
        }
		if ($nameActa !== '') {
            $sql .= ",acta_nacimiento='{$nameActa}'";
        }
        if ($nameCartaAnt !== '') {
            $sql .= ", carta_no_ant='{$nameCartaAnt}'";
        }
        if ($nameConstIn !== '') {
            $sql .= ", const_no_in='{$nameConstIn}'";
        }
        if ($nameCompDom !== '') {
            $sql .= ", comp_dom='{$nameCompDom}'";
        }
        if ($nameCartaRec1 !== '') {
            $sql .= ", carta_rec1='{$nameCartaRec1}'";
        }
        if ($nameCartaRec2 !== '') {
            $sql .= ", carta_rec2='{$nameCartaRec2}'";
        }

        if ($puesto !== '') {
            $sql .= ", id_cat_puestos='{$puesto}'";
        }
        if ($fecha_inicio !== '') {
            $sql .= ", fecha_inicio='{$fecha_inicio}'";
        }
        if ($fecha_conclusion !== '' ) {
            $sql .= ", fecha_conclusion='{$fecha_conclusion}'";
        }
        if ($id_area !== '') {
            $sql .= ", id_area='{$id_area}'";
        }
        if ($monto_bruto !== '') {
            $monto_solo1 = str_replace("$", "", $monto_bruto);
            $sql .= ", monto_bruto='{$monto_solo1}'";
        }
        if ($monto_neto !== '') {
            $monto_solo2 = str_replace("$", "", $monto_neto);
            $sql .= ", monto_neto='{$monto_solo2}'";
        }
        if ($tipo_inte !== '') {
            $sql .= ", id_tipo_integrante='{$tipo_inte}'";
        }
        if ($clave !== '') {
            $sql .= ", clave='{$clave}'";
        }
        if ($niv_puesto !== '') {
            $sql .= ", niv_puesto='{$niv_puesto}'";
        }
        if ($tiene_seguro !== '') {
            $sql .= ", tiene_seguro='{$tiene_seguro}'";
        }
		if ($no_empleado !== '') {
            $sql .= ", no_empleado='{$no_empleado}'";
        }
		if ($nombre_cargo !== '') {
            $sql .= ", nombre_cargo='{$nombre_cargo}'";
        }
        if ($nss != '' || $nss == '') {
            $sql .= ", nss='{$nss}'";
        }
        $sql .= " WHERE id_det_usuario='{$db->escape($id)}'";


        $result = $db->query($sql);
        if ((($result && $db->affected_rows() === 1) || ($insert && $db->affected_rows() === 1)) || (($result && $db->affected_rows() === 1) && ($insert && $db->affected_rows() === 1))) {
            $session->msg('s', "Expediente Actualizado");
            insertAccion($user['id_user'], '"' . $user['username'] . '" actualizó expediente general al trabajador(a): ' . $nombre . ' ' . $apellidos . '.', 2);
            redirect('exp_general.php?id=' . (int)$e_detalle['id_det_usuario'], false);
        } else {
            $session->msg('d', ' Lo sentimos, no se pudo actualizar el expediente general. ');
            redirect('exp_general.php?id=' . (int)$e_detalle['id_det_usuario'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('exp_general.php?id=' . (int)$e_detalle['id_det_usuario'], false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    Expediente general: <?php echo (ucwords($e_detalle['nombre'])); ?> <?php echo ($e_detalle['apellidos']); ?>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="exp_general.php?id=<?php echo (int)$e_detalle['id_det_usuario']; ?>" class="clearfix" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="nombre" class="control-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" value="<?php echo ($e_detalle['nombre']); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="apellidos" class="control-label">Apellidos</label>
                                <input type="text" class="form-control" name="apellidos" value="<?php echo ($e_detalle['apellidos']); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="curp">CURP</label>
                                <input type="text" class="form-control" name="curp" value="<?php echo ($e_detalle['curp']); ?>" placeholder="CURP" readonly>
                            </div>
                        </div>
						 <div class="col-md-3">
                            <div class="form-group">
                                <label for="foto_credencial">Foto para Credencial</label>
                                <input type="file" accept=".jpg,.jpeg,.png,.wepp" class="form-control" name="foto_credencial" id="foto_credencial">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                    <a target="_blank" href="/suigcedh/uploads/personal/credenciales/<?php echo $e_detalle['id_det_usuario'] . '/' . $e_detalle['foto_credencial']; ?>" style="font-size:14px; color: #1248c7; text-decoration: underline;"><?php echo remove_junk($e_detalle['foto_credencial']); ?></a>
                                </label>
                              
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1%; margin-top: -1%">
                        <span class="material-symbols-rounded" style="margin-top: 2%; color: #3a3d44;">contact_page</span>
                        <p style="font-size: 15px; font-weight: bold; margin-top: -27px; margin-left: 2%">EXPEDIENTE INTERNO</p>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="acta_nacimiento">Acta de Nacimiento</label>
                                <input type="file" accept="application/pdf" class="form-control" name="acta_nacimiento" id="acta_nacimiento">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                    <a target="_blank" href="/suigcedh/uploads/personal/expediente/<?php echo $e_detalle['id_det_usuario'] . '/' . $e_detalle['acta_nacimiento']; ?>" style="font-size:14px; color: #1248c7; text-decoration: underline;"><?php echo remove_junk($e_detalle['acta_nacimiento']); ?></a>
                                </label>
                                <?php if ($e_detalle['acta_nacimiento'] != '') : ?>
                                    <a href="delete_archivo.php?file=<?php echo $e_detalle['acta_nacimiento']; ?>&id=<?php echo (int)$e_detalle['id_det_usuario']; ?>&col=acta_nacimiento" onclick="return confirm('¿Estás seguro de que quieres eliminar este archivo?');">
                                        <span class="material-symbols-outlined">
                                            delete
                                        </span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="carta_no_ant">Carta de No Antecedentes Penales</label>
                                <input type="file" accept="application/pdf" class="form-control" name="carta_no_ant" id="carta_no_ant">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                    <a target="_blank" href="/suigcedh/uploads/personal/expediente/<?php echo $e_detalle['id_det_usuario'] . '/' . $e_detalle['carta_no_ant']; ?>" style="font-size:14px; color: #1248c7; text-decoration: underline;"><?php echo remove_junk($e_detalle['carta_no_ant']); ?></a>
                                </label>
                                <?php if ($e_detalle['carta_no_ant'] != '') : ?>
                                    <a href="delete_archivo.php?file=<?php echo $e_detalle['carta_no_ant']; ?>&id=<?php echo (int)$e_detalle['id_det_usuario']; ?>&col=carta_no_ant" onclick="return confirm('¿Estás seguro de que quieres eliminar este archivo?');">
                                        <span class="material-symbols-outlined">
                                            delete
                                        </span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="const_no_in">Constancia de No Inhabilitación</label>
                                <input type="file" accept="application/pdf" class="form-control" name="const_no_in" id="const_no_in">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                    <a target="_blank" href="/suigcedh/uploads/personal/expediente/<?php echo $e_detalle['id_det_usuario'] . '/' . $e_detalle['const_no_in']; ?>" style="font-size:14px; color: #1248c7; text-decoration: underline;"><?php echo remove_junk($e_detalle['const_no_in']); ?></a>
                                </label>
                                <?php if ($e_detalle['const_no_in'] != '') : ?>
                                    <a href="delete_archivo.php?file=<?php echo $e_detalle['const_no_in']; ?>&id=<?php echo (int)$e_detalle['id_det_usuario']; ?>&col=const_no_in" onclick="return confirm('¿Estás seguro de que quieres eliminar este archivo?');">
                                        <span class="material-symbols-outlined">
                                            delete
                                        </span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="comp_dom">Comprobante de Domicilio</label>
                                <input type="file" accept="application/pdf" class="form-control" name="comp_dom" id="comp_dom">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                    <a target="_blank" href="/suigcedh/uploads/personal/expediente/<?php echo $e_detalle['id_det_usuario'] . '/' . $e_detalle['comp_dom']; ?>" style="font-size:14px; color: #1248c7; text-decoration: underline;"><?php echo remove_junk($e_detalle['comp_dom']); ?></a>
                                </label>
                                <?php if ($e_detalle['comp_dom'] != '') : ?>
                                    <a href="delete_archivo.php?file=<?php echo $e_detalle['comp_dom']; ?>&id=<?php echo (int)$e_detalle['id_det_usuario']; ?>&col=comp_dom" onclick="return confirm('¿Estás seguro de que quieres eliminar este archivo?');">
                                        <span class="material-symbols-outlined">
                                            delete
                                        </span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="carta_rec1">Carta de Recpmendación (1)</label>
                                <input type="file" accept="application/pdf" class="form-control" name="carta_rec1" id="carta_rec1">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                    <a target="_blank" href="/suigcedh/uploads/personal/expediente/<?php echo $e_detalle['id_det_usuario'] . '/' . $e_detalle['carta_rec1']; ?>" style="font-size:14px; color: #1248c7; text-decoration: underline;"><?php echo remove_junk($e_detalle['carta_rec1']); ?></a>
                                </label>
                                <?php if ($e_detalle['carta_rec1'] != '') : ?>
                                    <a href="delete_archivo.php?file=<?php echo $e_detalle['carta_rec1']; ?>&id=<?php echo (int)$e_detalle['id_det_usuario']; ?>&col=carta_rec1" onclick="return confirm('¿Estás seguro de que quieres eliminar este archivo?');">
                                        <span class="material-symbols-outlined">
                                            delete
                                        </span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="carta_rec2">Carta de Recomendación (2)</label>
                                <input type="file" accept="application/pdf" class="form-control" name="carta_rec2" id="carta_rec2">
                                <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                    <a target="_blank" href="/suigcedh/uploads/personal/expediente/<?php echo $e_detalle['id_det_usuario'] . '/' . $e_detalle['carta_rec2']; ?>" style="font-size:14px; color: #1248c7; text-decoration: underline;"><?php echo remove_junk($e_detalle['carta_rec2']); ?></a>
                                </label>
                                <?php if ($e_detalle['carta_rec2'] != '') : ?>
                                    <a href="delete_archivo.php?file=<?php echo $e_detalle['carta_rec2']; ?>&id=<?php echo (int)$e_detalle['id_det_usuario']; ?>&col=carta_rec2" onclick="return confirm('¿Estás seguro de que quieres eliminar este archivo?');">
                                        <span class="material-symbols-outlined">
                                            delete
                                        </span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div style="margin-bottom: 1%; margin-top: -1%">
                                <span class="material-symbols-rounded" style="margin-top: 2%; color: #3a3d44;">work</span>
                                <p style="font-size: 15px; font-weight: bold; margin-top: -27px; margin-left: 5%">EXPEDIENTE INTERNO DE PUESTOS</p>
                            </div>
                        </div>
                        <div class="col-md-6" style="display: flex; align-items: center; margin-top: -6px">
                            <span class="material-symbols-outlined" style="color: #370494">
                                history
                            </span>
                            <a href="hist_puestos.php?id=<?php echo (int)$_GET['id'] ?>" style="margin-left: 10px; color: #370494; font-weight: bold;">HISTORIAL INTERNO DE PUESTOS</a>
                        </div>

                    </div>
                    <div class="row">
					 <div class="col-md-1">
                            <div class="form-group">
                                <label for="no_empleado" class="control-label">No. Empleado </label>
                                <input type="text" class="form-control" name="no_empleado" value="<?php echo ($e_detalle['no_empleado']); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_puestos" class="control-label">Puesto</label>
                                <select class="form-control" name="id_cat_puestos">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cat_puestos as $datos) : ?>
                                        <option <?php if ($datos['id_cat_puestos'] === $e_detalle['id_cat_puestos']) echo 'selected="selected"'; ?> value="<?php echo $datos['id_cat_puestos']; ?>"><?php echo ucwords($datos['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="fecha_inicio">Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio" value="<?php echo $e_detalle['fecha_inicio']?>">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="fecha_conclusion">Fecha Termino</label>
                                <input type="date" class="form-control" name="fecha_conclusion" value="<?php echo $e_detalle['fecha_conclusion'];?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="clave" class="control-label">Clave</label>
                                <input type="text" class="form-control" name="clave" value="<?php echo ($e_detalle['clave']); ?>">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="niv_puesto" class="control-label">Nivel de Puesto</label>
                                <input type="text" class="form-control" name="niv_puesto" value="<?php echo ($e_detalle['niv_puesto']); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_area">Área de Adscripción</label>
                                <select class="form-control" name="id_area">
                                    <option value="0">Escoge una opción</option>
                                    <?php foreach ($areas as $area) : ?>
                                        <option <?php if ($area['id_area'] === $e_detalle['id_area']) echo 'selected="selected"'; ?> value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
					 <div class="col-md-3">
                            <div class="form-group">
                                <label for="nombre_cargo" class="control-label">Cargo</label>
                                <input type="text" class="form-control" name="nombre_cargo" value="<?php echo ($e_detalle['nombre_cargo']); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tipo_inte">Tipo de Integrante</label>
                                <select class="form-control" name="tipo_inte">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($tipo_int as $inte) : ?>
                                        <option <?php if ($inte['id_tipo_integrante'] === $e_detalle['id_tipo_integrante']) echo 'selected="selected"'; ?> value="<?php echo $inte['id_tipo_integrante']; ?>"><?php echo ucwords($inte['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php $v1 = "$" . ($e_detalle['monto_bruto'] == '' ? "0.00" : $e_detalle['monto_bruto']);
                        $v2 = "$" . ($e_detalle['monto_neto'] == '' ? "0.00" : $e_detalle['monto_neto']); ?>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="monto_bruto">Monto Mensual (Bruto)</label>
                                <input type="text" class="form-control" name="monto_bruto" id="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" value="<?php echo ($v1); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="monto_neto">Monto Mensual (Neto)</label>
                                <input type="text" class="form-control" name="monto_neto" id="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" value="<?php echo ($v2); ?>">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="tiene_seguro">¿Tiene Seguro Social?</label>
                                <select class="form-control" name="tiene_seguro">
                                    <option value="">Escoge una opción</option>
                                    <option <?php if ($e_detalle['tiene_seguro'] === '0') echo 'selected="selected"'; ?> value="0">No</option>
                                    <option <?php if ($e_detalle['tiene_seguro'] === '1') echo 'selected="selected"'; ?> value="1">Sí</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="nss" class="control-label">NSS (Si tiene)</label>
                                <input type="text" class="form-control" name="nss" value="<?php echo ($e_detalle['nss']); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <a href="detalles_usuario.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="update" class="btn btn-info">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Jquery Dependency
    $("input[data-type='currency']").on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() {
            formatCurrency($(this), "blur");
        }
    });

    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }

    function formatCurrency(input, blur) {
        // Appends $ to value, validates decimal side and puts cursor back in right position.
        // Get input value
        var input_val = input.val();
        // Don't validate empty input
        if (input_val === "") {
            return;
        }
        // Original length
        var original_len = input_val.length;
        // Initial caret position 
        var caret_pos = input.prop("selectionStart");
        // Check for decimal
        if (input_val.indexOf(".") >= 0) {
            // Get position of first decimal this prevents multiple decimals from being entered
            var decimal_pos = input_val.indexOf(".");
            // Split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);
            // Add commas to left side of number
            left_side = formatNumber(left_side);
            // Validate right side
            right_side = formatNumber(right_side);
            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }
            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);
            // Jjoin number by .
            input_val = "$" + left_side + "." + right_side;
        } else {
            // No decimal entered, add commas to number, remove all non-digits
            input_val = formatNumber(input_val);
            input_val = "$" + input_val;
            // Final formatting
            if (blur === "blur") {
                input_val += ".00";
            }
        }
        // Send updated string to input
        input.val(input_val);
        // Put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }
</script>

<?php include_once('layouts/footer.php'); ?>