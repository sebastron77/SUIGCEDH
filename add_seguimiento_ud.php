<?php header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Agregar Seguimiento de la Unidad de Desaparecidos';
require_once('includes/load.php');

$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];
$area = find_all_areas_quejas();

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    redirect('home.php');
}
if ($nivel == 4) {
    redirect('home.php');
}
if ($nivel == 5) {
    page_require_level_exacto(5);
}
if ($nivel == 6) {
    redirect('home.php');
}
if ($nivel == 7) {
    redirect('home.php');
}


// page_require_level(4);
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_seguimiento_ud'])) {	
	
    if (empty($errors)) {
		
		
    } else {
        
    }
}
?>
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<script language="javascript">
    $(document).ready(function() {
      $("#id_areas_asignada").change(function() {        
        $("#id_areas_asignada option:selected").each(function() {
          tipo_actuacion = $("#tipo_actuacion").val();
          id_areas_asignada = $(this).val();
          $.post("search_actuacion.php", {
            tipo_actuacion: tipo_actuacion,
            id_area_asignada: id_areas_asignada
          }, function(data) {
            $("#folio_actuacion").html(data);
          })
        })

      })
    });
	</script>
<?php echo display_msg($msg); ?>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Seguimiento de la Unidad de Desaparecidos</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_seguimiento_ud.php" enctype="multipart/form-data">
                <div class="row">
				<div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_actuacion">Tipo Actuación<span style="color:red;font-weight:bold">*</span></label>
							<select class="form-control" name="tipo_actuacion" id="tipo_actuacion" >
                                <option value="0">Escoge una opción</option>                                
                                    <option value="q">Quejas</option>
                                    <option value="o">Orientaciones</option>
                                    <option value="c">Canalizaciones</option>
                                    <option value="a">Otras Actuaciones</option>                                    
                            </select>
                        </div>
                    </div>
				
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area_asignada">Área a la que se asigna <span style="color:red;font-weight:bold">*</span></label>
                            <select class="form-control" id="id_areas_asignada" name="id_areas_asignada" required>
                                <option value="">Escoge una opción</option>
                                <?php foreach ($area as $a) : ?>
                                    <option value="<?php echo $a['id_area']; ?>"><?php echo ucwords($a['nombre_area']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="folio_queja">Folio de Actuación</label>
                            <select class="form-control" id="folio_actuacion" name="folio_actuacion"></select>
							
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_documento">Tipo Documento</label>
                            <input type="text" class="form-control" name="tipo_documento" required>
                        </div>
                    </div>
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_documento">Fecha del Documento</label><br>
                            <input type="date" class="form-control" name="fecha_documento" required>
                        </div>
                    </div>                    
                    
                </div>

                <div class="row">
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="descripcion_documento">Descripcion breve del documento</label>
                            <textarea class="form-control" name="descripcion_documento" id="descripcion_documento" cols="10" rows="1" required></textarea>
                        </div>
                    </div>					
					<div class="col-md-3">
                        <div class="form-group">
                            <span>
                                <label for="documento_adjunto">Adjuntar Documento</label>
                                <input id="documento_adjunto" type="file" accept="application/pdf" class="form-control" name="documento_adjunto" required>
                            </span>
                        </div>
                    </div>                                                      
                </div>
				<div class="row">
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area_envio">Área de Envio</label>
                            <select class="form-control" name="id_area_envio">
                                <option value="" required>Escoge una opción</option>
                            <?php foreach ($area_envio as $area_envio) : ?>
                                <option value="<?php echo $area_envio['id']; ?>"><?php echo ucwords($area_envio['nombre_area']); ?></option>
                            <?php endforeach ?>                               
                            </select>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area_recepcion">Área de Recepción</label>
                            <select class="form-control" name="id_area_recepcion" required>
                                 <option value="" required>Escoge una opción</option>
                            <?php foreach ($area_recepcion as $area_recepcion) : ?>
                                <option value="<?php echo $area_recepcion['id']; ?>"><?php echo ucwords($area_recepcion['nombre_area']); ?></option>
                            <?php endforeach ?>                                  
                            </select>
                        </div>
                    </div>
				</div>
					
				<div class="row">
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="1"></textarea>
                        </div>
                    </div>
                </div>
					
                <div class="form-group clearfix">
                    <a href="quejas_agregadas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_seguimiento_ud" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="panel-body">
  <h2><b>Información de Seguimiento</b></h2>
<?php
$seguimientoUD = find_segUD((int)$_GET['id'],'q');
?>  
  <hr style="border-width:2px;">
	 <table class="datatable table table-dark table-bordered table-striped" style="width:80%">
		<thead>
			<tr class="table-info">
				<th class="text-center" >Tipo Documento</th>
				<th class="text-center" >Fecha Documento</th>
				<th class="text-center" >Descripción</th>
				<th class="text-center" >Documento Adjunto</th>				
				<th class="text-center" >Área de Envio</th>
				<th class="text-center" >Área de Recepción</th>
				<!--<th class="text-center" >Acciones</th>-->
			</tr>
		</thead>
        <tbody>
		 <?php foreach ($seguimientoUD as $UD) : ?>
			<tr>
				<td class="text-center"> <?php echo remove_junk(ucwords($UD['tipo_documento'])); ?></td>
				<td class="text-center"> <?php echo remove_junk(ucwords($UD['fecha_documento'])); ?></td>
				<td class="text-center"> <?php echo remove_junk(ucwords($UD['descripcion_documento'])); ?></td>
				<td>
					<a target="_blank" style="color: #0094FF;" href="uploads/quejas/<?php echo str_replace("/", "-", $UD['folio_queja']); ?>/seguimientoUD/<?php echo remove_junk(ucwords($UD['documento_adjunto'])); ?>">
					<?php echo $UD['documento_adjunto']; ?>
					</a>
				</td>
				<td class="text-center"> <?php echo remove_junk(ucwords($UD['area_envio'])); ?></td>
				<td class="text-center"> <?php echo remove_junk(ucwords($UD['area_recepcion'])); ?></td>
				<!--<td class="text-center"> 
					<a onclick="javascript:window.open('./ver_detalle_seguimiento_ud.php','popup','width=400,height=400');" href="#">							
							<img alt="<!>" src="1755.png" style="width:20px" title="Ver más">
					</a>					
				</td>
				-->
			</tr>
          <?php endforeach; ?>
		</tbody>
	 </table>
</div>
<?php include_once('layouts/footer.php'); ?>