<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Estadísticas de Quejas';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    page_require_level(3);
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
    page_require_level_exacto(7);
}



?>

<script type="text/javascript">	
function accesoEst(tipo) {
  if(tipo==1){ window.location.href="estQ_med_pres.php?id="+$("#years").val();
  }else if(tipo==2){ window.location.href="estQ_area_asignada.php?id="+$("#years").val();
  }else if(tipo==3){ window.location.href="estQ_autoridad.php?id="+$("#years").val();
  }else if(tipo==4){ window.location.href="estQ_derecho_vulnerado.php?id="+$("#years").val();
  }else if(tipo==5){ window.location.href="estQ_estado_procesal.php?id="+$("#years").val();
  }else if(tipo==6){ window.location.href="estQ_tipo_resolucion.php?id="+$("#years").val();
  }
}
</script>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12" style="font-size: 40px; color: #3a3d44;">
        <?php echo 'Estadísticas de Quejas'; ?>
    </div>
</div>

<div class="row" style="TEXT-ALIGN: center; width: 25%;margin: 0 auto">
	<div class="panel panel-default">
			<div class="col-md-12">
                        <div class="form-group">
                            <label for="autoridad">Ejercicio</label>
                             <select class="form-control" name="years" id="years"  >
                                <option value="0">Escoge una opción</option>
                                <?php for ($i = 2022; $i <= (int) date("Y"); $i++) {
								echo "<option value='".$i."'>".$i."</option>";
								}?>	
                            </select>
                        </div>
            </div>
    </div>
</div>

<div class="container-fluid">
    <div class="full-box tile-container">
        <a href="javascript:accesoEst(1);" class="tileA">
            <div class="tileA-tittle">Medio Presentación</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    input_circle
                </span>
            </div>
        </a>
        <a href="javascript:accesoEst(2);" class="tileA">
            <div class="tileA-tittle">Área Asignada</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    school
                </span>
            </div>
        </a>
        <a  href="javascript:accesoEst(3);" class="tileA">
            <div class="tileA-tittle">Autoridad Responsable</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    diversity_3
                </span>
            </div>
        </a>
        <a  href="javascript:accesoEst(4);" class="tileA">
            <div class="tileA-tittle">Derecho Vulnerado</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    translate
                </span>
            </div>
        </a>
        <a href="javascript:accesoEst(5);" class="tileA">
            <div class="tileA-tittle">Estado Procesal</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">               
					waterfall_chart
                </span>
            </div>
        </a>
		<a href="javascript:accesoEst(6);" class="tileA">
            <div class="tileA-tittle">Tipo Resolución</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    groups_3
                </span>
            </div>
        </a>
		<!--
        <a href="estQ_entidad.php" class="tileA">
            <div class="tileA-tittle">Entidad</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    location_on
                </span>
            </div>
        </a>
        <a href="estQ_municipio.php?id=1" class="tileA">
            <div class="tileA-tittle">Municipios</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    location_chip
                </span>
            </div>
        </a>
		-->
       
    </div>
</div>


<?php include_once('layouts/footer.php'); ?>