<?php
$page_title = 'Eventos Presidencia';
require_once('includes/load.php');
?>
<?php
$ejercicio = isset($_GET['anio']) ? $_GET['anio'] : date("Y");
$all_eventos = find_all_year('eventos_presidencia','fecha',$ejercicio);
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level(7);
}
if ($nivel_user == 52) {
    page_require_level_exacto(52);
}

if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 52) :
    redirect('home.php');
endif;

if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo los '.$page_title.' del Ejercicio '.$ejercicio, 5);   
}

$conexion = mysqli_connect("localhost", "suigcedh", "9DvkVuZ915H!");
mysqli_set_charset($conexion, "utf8");
mysqli_select_db($conexion, "suigcedh");
$sql = "SELECT * FROM eventos_presidencia WHERE fecha  LIKE '".$ejercicio."%' ";
$resultado = mysqli_query($conexion, $sql) or die;
$eventos = array();
while ($rows = mysqli_fetch_assoc($resultado)) {
    $eventos[] = $rows;
}

mysqli_close($conexion);

if (isset($_POST["export_data"])) {
    if (!empty($eventos)) {
        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
        header("Content-Disposition: attachment; filename=eventos.xls");
        $filename = "eventos.xls";
        $mostrar_columnas = false;

        foreach ($eventos as $resolucion) {
            if (!$mostrar_columnas) {
				echo utf8_decode(implode("\t", array_keys($resolucion)) . "\n");
                $mostrar_columnas = true;
            }
            echo utf8_decode(implode("\t", array_values($resolucion)) . "\n");
        }
		if ($nivel_user == 7 || $nivel_user == 53) {
			insertAccion($user['id_user'], '"' . $user['username'] . '" descargó   los '.$page_title.' del Ejercicio '.$ejercicio, 6);   
}
    } else {
        echo 'No hay datos a exportar';
    }
    exit;
}

?>
<script type="text/javascript">	
 function changueAnio(anio){
	 window.open("eventos_pres.php?anio="+anio,"_self");
	 
 }
</script>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<a href="solicitudes_presidencia.php" class="btn btn-success">Regresar</a><br><br>

<div class="row">

	<div class="row">
	    <div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<div class="col-md-8">
							<strong>
								<span class="glyphicon glyphicon-th"></span>
								<span>Lista de Eventos <?php echo $ejercicio ?></span>
							</strong>
					</div>
					<div class="col-md-1" style="margin: 20px 40px 10px 0px;">
						 <form action="<?php echo $_SERVER["PHP_SELF"]; ?>?anio=<?php echo $ejercicio ?>" method="post">
                    <button style="float: right; margin-top: -20px" type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-excel">Exportar a Excel</button>
                </form>
					</div>
					<div class="col-md-1">
						<?php if( $nivel_user <= 2 ||($nivel_user ==52)){ ?>
                <a href="add_evento_pres.php" style="margin-left: 10px" class="btn btn-info pull-right">Agregar evento</a>
                        <?php } ?>
					</div>										
					<div class="col-md-1">
						<div class="form-group" >
							<select class="form-control" name="ejercicio" onchange="changueAnio(this.value)">
								<option value="">Selecciona Ejercicio</option>																								
								<?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
								echo "<option value='".$i."'>".$i."</option>";
								}?>																									
							</select>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>


    <div class="col-md-12">
       
        <div class="panel-body">
            <table class="datatable table table-bordered table-striped">
                <thead class="thead-purple">
                    <tr style="height: 10px;">
                        <th style="width: 10%;">Folio</th>
                        <th style="width: 10%;">Evento</th>
                        <th style="width: 10%;">Tipo Evento</th>
                        <th style="width: 10%;">Ámbito Evento</th>
                        <th style="width: 7%;">Fecha</th>
                        <th style="width: 5%;">Hora</th>
                        <th style="width: 5%;">Lugar</th>
                        <th style="width: 5%;">Depto./Org.</th>
                        <th style="width: 5%;">Modalidad</th>
                        <?php if( $nivel_user <= 2 ||($nivel_user ==52)){ ?>
                        <th style="width: 3%;" class="text-center">Acciones</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_eventos as $a_evento) : ?>
                        <tr>
                            <td><?php echo remove_junk(ucwords($a_evento['folio'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_evento['nombre_evento'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_evento['tipo_evento'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_evento['ambito_evento'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_evento['fecha'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_evento['hora'])) ?></td>
                            <td><?php echo remove_junk(ucwords($a_evento['lugar'])) ?></td>
                            <td><?php echo remove_junk((ucwords($a_evento['depto_org']))) ?></td>
                            <td><?php echo remove_junk((ucwords($a_evento['modalidad']))) ?></td>
                            <?php
                            $folio_editar = $a_evento['folio'];
                            $resultado = str_replace("/", "-", $folio_editar);
                            ?>

                        <?php if( $nivel_user <= 2 ||($nivel_user ==52)){ ?>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit_evento_pres.php?id=<?php echo (int)$a_evento['id_eventos_presidencia']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>
                                </div>
                            </td>
                        <?php } ?>


                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<?php include_once('layouts/footer.php'); ?>