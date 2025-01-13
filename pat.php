<?php
$page_title = 'PAT';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$area = isset($_GET['a']) ? $_GET['a'] : '0';
$area_informe = find_by_id('area', $area, 'id_area');
$all_pat = find_all_pat($area);
$solicitud = find_by_solicitud($area);

page_require_level(53);
if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo el PAT 2024 del Área - '.$area_informe['nombre_area'], 5);    
}

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT 
id_indicadores_pat,
ejercicio,
nombre_area,
definicion_indicador,
objetivo_indicador,
nombre_indicador,
IFNULL(nombre_ejes,'Sin Eje Estratégico') as nombre_ejes,
IFNULL(nombre_agendas,'Sin Agenda') as nombre_agendas,
IFNULL(nombre_programa,'Sin Programa') as nombre_programa,
descripcion_metod_calculo,
h.descripcion as nombre_unidades_medida,
valor_absoluto,
IFNULL(f.tipo,'Sin Programado') as inicial_tipo,
IFNULL(f.valor_enero,0) as inicial_valor_enero,
IFNULL(f.valor_febrero,0) as inicial_valor_febrero,
IFNULL(f.valor_marzo,0) as inicial_valor_marzo,
IFNULL(f.valor_abril,0) as inicial_valor_abril,
IFNULL(f.valor_mayo,0) as inicial_valor_mayo,
IFNULL(f.valor_junio,0) as inicial_valor_junio,
IFNULL(f.valor_julio,0) as inicial_valor_julio,
IFNULL(f.valor_agosto,0) as inicial_valor_agosto,
IFNULL(f.valor_septiembre,0) as inicial_valor_setiembre,
IFNULL(f.valor_octubre,0) as inicial_valor_octubre,
IFNULL(f.valor_noviembre,0) as inicial_valor_noviembre,
IFNULL(f.valor_diciembre,0) as inicial_valor_diciembre,
IFNULL(g.tipo,'Sin Real') as real_tipo,
IFNULL(g.valor_enero,0) as real_valor_enero,
IFNULL(g.valor_febrero,0) as real_valor_febrero,
IFNULL(g.valor_marzo,0) as real_valor_marzo,
IFNULL(g.valor_abril,0) as real_valor_abril,
IFNULL(g.valor_mayo,0) as real_valor_mayo,
IFNULL(g.valor_junio,0) as real_valor_junio,
IFNULL(g.valor_julio,0) as real_valor_julio,
IFNULL(g.valor_agosto,0) as real_valor_agosto,
IFNULL(g.valor_septiembre,0) as real_valor_setiembre,
IFNULL(g.valor_octubre,0) as real_valor_octubre,
IFNULL(g.valor_noviembre,0) as real_valor_noviembre,
IFNULL(g.valor_diciembre,0) as real_valor_diciembre,
IFNULL(g.fecha_actualización,'Sin Actualización') as fecha_actualizacin_real

FROM `indicadores_pat` a
LEFT JOIN (
	SELECT 
    	id_indicadores_pat,
        x.descripcion as nombre_ejes
    FROM `rel_indicadores_ejes`
	LEFT JOIN `cat_ejes_estrategicos` x USING(id_cat_ejes_estrategicos) 

)b USING(id_indicadores_pat)
LEFT JOIN (
	SELECT 
    	id_indicadores_pat,
        x.descripcion as nombre_agendas
    FROM `rel_indicadores_agendas`
	LEFT JOIN `cat_agendas` x USING(id_cat_agendas) 

)c USING(id_indicadores_pat)
LEFT JOIN (
	SELECT 
    	id_indicadores_pat,
        x.descripcion as nombre_programa
    FROM `rel_indicadores_programas`
	LEFT JOIN `cat_programas_cedh` x USING(id_cat_programas_cedh) 

)d USING(id_indicadores_pat)
LEFT JOIN `area` e ON(e.`id_area`=a.`id_area_responsable`)
LEFT JOIN (
	SELECT 
    	id_indicadores_pat,
  tipo,
valor_enero,
valor_febrero,
valor_marzo,
valor_abril,
valor_mayo,
valor_junio,
valor_julio,
valor_agosto,
valor_septiembre,
valor_octubre,
valor_noviembre,
valor_diciembre

    FROM `rel_indicadores_calendarizacion`
    WHERE tipo='Programado' AND vigente=1
)f USING(id_indicadores_pat)
LEFT JOIN (
	SELECT 
    	id_indicadores_pat,
  tipo,
valor_enero,
valor_febrero,
valor_marzo,
valor_abril,
valor_mayo,
valor_junio,
valor_julio,
valor_agosto,
valor_septiembre,
valor_octubre,
valor_noviembre,
valor_diciembre,
fecha_actualización
    FROM `rel_indicadores_calendarizacion`
    WHERE tipo='Real' AND vigente=1
)g USING(id_indicadores_pat)
LEFT JOIN cat_unidades_medida h USING(id_cat_unidades_medida)
WHERE id_area_responsable=".$area. " ORDER BY e.jerarquia";
$resultado = mysqli_query($conexion, $sql) or die;
$pat = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $pat[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($pat)) {
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=pat.xls");
        $filename = "pat.xls";
        $mostrar_columnas = false;

        foreach ($pat as $resolucion) {
            if (!$mostrar_columnas) {
                echo utf8_decode(implode("\t", array_keys($resolucion)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($resolucion)) . "\n");
        }
		if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" descargó  la lista de PAT 2024 del Área - '.$area_informe['nombre_area'], 6);    
		}
    } else {
        echo 'No hay datos a exportar.'.$sql;
    }
    exit;
}

?>
<?php include_once('layouts/header.php'); ?>

<a href="<?php echo $solicitud['nombre_solicitud'];?>" class="btn btn-success">Regresar</a><br><br>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Programa Anual de Trabajo de la <?php echo $area_informe['nombre_area'];?></span>
                </strong>
<?php 	
if (( $nivel_user != 7) &&( $nivel_user != 53)) {
			
?>				
				 <a href="avance_global_indicador_pat.php?a=<?php echo $area; ?>" style="margin-left: 10px" class="btn btn-info pull-right">Actualizar Avance Global</a>
<?php 
		}
?>				
                <form action=" <?php echo $_SERVER["PHP_SELF"]."?a=".$area; ?>" method="post">
                    <button style="float: right; margin-top: -20px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>
							
            </div>
			
						
        </div>

        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr style="height: 10px;">
                        <th style="width: 8%;">No.</th>
                        <th style="width: 8%;">Área Responsable</th>
                        <th style="width: 8%;">Definición del Indicador</th>
                        <th style="width: 8%;">Objetivo (Resumen Narrativo)</th>                        
                        <th style="width: 8%;">Eje Estratégico</th>                        
                        <th style="width: 8%;">Agenda</th>                        
                        <th style="width: 8%;">Programa</th>                        
                        <th style="width: 8%;">Nombre del Indicador</th>                        
                        <th style="width: 8%;">Descripción del Método de Calculo</th>                        
                        <th style="width: 8%;">Uidad de Medida</th>                        
                        <th style="width: 8%;">Valor Absoluto</th>                                            
                        <th style="width: 8%;">Fecha Actualización</th>                                            
                        <th style="width: 8%;">Valor Real</th>                                            
                        <th style="width: 8%;">Calendaización</th> 
<?php if (($nivel_user <> 7) && ($nivel_user <> 53)) : ?>							
                        <th style="width: 8%;">Acciones</th>                                            
<?php endif;?>						
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_pat as $a_pat) : ?>                       
                    <?php 
					$rel_ejes= find_all_elemts_indicadores($a_pat['id_indicadores_pat'],'cat_ejes_estrategicos','rel_indicadores_ejes');
					$rel_agendas= find_all_elemts_indicadores($a_pat['id_indicadores_pat'],'cat_agendas','rel_indicadores_agendas');
					$rel_programas= find_all_elemts_indicadores($a_pat['id_indicadores_pat'],'cat_programas_cedh','rel_indicadores_programas');
					?>                       
                        <tr>
							<td class="text-center"><?php echo count_id(); ?></td>
                            <td style="text-align: center;"><?php echo remove_junk(ucwords(($a_pat['nombre_area']))) ?></td>
                            <td style="text-align: center;"><?php echo remove_junk(ucwords(($a_pat['definicion_indicador']))) ?></td>
                            <td style="text-align: center;"><?php echo remove_junk(ucwords(($a_pat['objetivo_indicador']))) ?></td>
                            <td style="text-align: center;">
								<?php $eje=0; foreach ($rel_ejes as $datos) :  
									echo remove_junk(ucwords(($datos['nombre'])))."<br>";
									$eje++;
								 endforeach; 
								 if($eje==0):
									echo "Sin Eje Estrategico relacionado";
								 endif;
									 
								 ?>
							</td> 
							<td style="text-align: center;">
								<?php $agenda=0; foreach ($rel_agendas as $datos) :  
									echo remove_junk(ucwords(($datos['nombre'])))."<br>";
									$agenda++;
								 endforeach; 
								 if($agenda==0):
									echo "Sin Agenda relacionada";
								 endif;
								 ?>
							</td>
							<td style="text-align: center;">
								<?php $programa=0; foreach ($rel_programas as $datos) :  
									echo remove_junk(ucwords(($datos['nombre'])))."<br>";
									$programa++;
								 endforeach; 
								 if($programa==0):
									echo "Sin programa relacionado";
								 endif;
								 ?>
							</td>
                            <td style="text-align: center;"><?php echo remove_junk(ucwords(($a_pat['nombre_indicador']))) ?></td>
                            <td style="text-align: center;"><?php echo remove_junk(ucwords(($a_pat['descripcion_metod_calculo']))) ?></td>
                            <td style="text-align: center;"><?php echo remove_junk(ucwords(($a_pat['unidades_medida']))) ?></td>
                            <td style="text-align: center;"><?php echo remove_junk(ucwords(($a_pat['valor_absoluto']))) ?></td>
							<td class="text-center"><?php echo remove_junk(ucwords($a_pat['fecha_actualización']))!=''?date("d-m-Y", strtotime(remove_junk(ucwords($a_pat['fecha_actualización'])))):'' ?></td>
                            <td style="text-align: center;"><?php echo remove_junk(ucwords(($a_pat['valor_real']))) ?></td>
							<td class="text-center">
                                <div class="btn-group">
                                    <a onclick="javascript:window.open('./calendarizacion_indicador.php?id=<?php echo base64_encode(($a_pat['id_indicadores_pat']));?>','popup','width=500,height=600');" 
										href="#" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información" >
                                        <i class="glyphicon glyphicon-calendar"></i>
                                    </a>                                    
                                </div>
							</td>
							<?php if( ( $nivel_user != 7) &&( $nivel_user != 53)) :         ?>		
							<td class="text-center">
                                <div class="btn-group">
								<a href="avance_indicador_pat.php?id=<?php echo $a_pat['id_indicadores_pat']; ?>" class="btn btn-secondary btn-success" title="Actualizar Avance" data-toggle="tooltip">
                                        <i class="glyphicon glyphicon-refresh"></i>
                                    </a>                                    
                                </div>
							</td>
							<?php endif;?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<?php include_once('layouts/footer.php'); ?>