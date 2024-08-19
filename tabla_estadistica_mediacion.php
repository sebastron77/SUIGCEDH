<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Estadísticas de Mediacion';
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
if ($nivel == 19) {
    page_require_level_exacto(19);
}
?>
<script type="text/javascript">	
function accesoEst(tipo) {
  //$("#years").val();
  if(tipo==1){ window.location.href="est_mediacion_status.php?id="+$("#years").val();
  }else if(tipo==2){ window.location.href="est_mediacion_genero.php?id="+$("#years").val();
  }else if(tipo==3){ window.location.href="est_mediacion_grupo.php?id="+$("#years").val();
  }else if(tipo==4){ window.location.href="est_mediacion_autoridad.php?id="+$("#years").val();
  }else if(tipo==5){ window.location.href="est_mediacion_area.php?id="+$("#years").val();
  }
}
</script>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12" style="font-size: 40px; color: #3a3d44;">
        <?php echo 'Estadísticas de Mediación'; ?>
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
        <!-- <a href="est_mediacion_status.php" class="tileA"> -->
            <div class="tileA-tittle">Estado Mediación</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    rebase_edit
                </span>
            </div>
        </a>
         
		<a href="javascript:accesoEst(2);" class="tileA">
        <!-- <a href="est_mediacion_genero.php" class="tileA"> -->
            <div class="tileA-tittle">Género</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    diversity_3
                </span>
            </div>
        </a>
        
		<a href="javascript:accesoEst(3);" class="tileA">
        <!-- <a href="est_mediacion_grupo.php" class="tileA"> -->
            <div class="tileA-tittle">Grupo Vulnerable</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    groups_3
                </span>
            </div>
        </a>
       
		<a href="javascript:accesoEst(4);" class="tileA">
        <!-- <a href="est_mediacion_autoridad.php" class="tileA"> -->
            <div class="tileA-tittle">Autoridades</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    frame_person
                </span>
            </div>
        </a> 
		
		<a href="javascript:accesoEst(5);" class="tileA">
		<!-- <a href="est_mediacion_area.php" class="tileA"> -->
            <div class="tileA-tittle">Área Asignación</div>
            <div class="tileA-icon">
                <span class="material-symbols-rounded" style="font-size: 95px;">
                    domain
                </span>
            </div>
        </a>
    </div>
</div>



<?php include_once('layouts/footer.php'); ?>