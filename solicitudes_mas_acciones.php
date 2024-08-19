<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Solicitudes - Más Actividades de Orientación Legal, Quejas y Seguimiento';
require_once('includes/load.php');
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$areas_quejas = find_all_areas_quejas();
$activo=0;
$area =0;

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 5) {
    page_require_level_exacto(5);
}
if ($nivel_user == 7) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);   
    page_require_level(7);
}
if ($nivel_user == 19) {
    page_require_level_exacto(19);
}
if ($nivel_user == 25) {
    page_require_level_exacto(25);
}
if ($nivel_user == 26) {
    page_require_level_exacto(26);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}

if ($nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo '.$page_title, 5);   
    page_require_level_exacto(53);
}

if ($nivel_user > 2 && $nivel_user < 5) :
    redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 19) :
    redirect('home.php');
endif;
if ($nivel_user > 19 && $nivel_user < 25) :
    redirect('home.php');
endif;
if ($nivel_user > 25 && $nivel_user < 26) :
    redirect('home.php');
endif;
if ($nivel_user > 26 && $nivel_user < 50) :
    redirect('home.php');
endif;
if ($nivel_user > 50 && $nivel_user < 53) :
    redirect('home.php');
endif;

if($nivel_user==5 || $nivel_user ==25 || $nivel_user ==26){
		$area_user = muestra_area($id_user);
		$activo=1;
}


?>
<script type="text/javascript">	
function accesoEst(tipo) {
		document.getElementById('danger').style.display = 'none';
	if($("#id_area_asignada").val() > 0){
	  if(tipo==1){ window.location.href="pat.php?a="+$("#id_area_asignada").val();
	  }else if(tipo==2){ window.location.href="informes_areas.php?a="+$("#id_area_asignada").val();
	  }else if(tipo==3){ window.location.href="capacitaciones.php?a="+$("#id_area_asignada").val();
	  }else if(tipo==4){ window.location.href="eventos.php?a="+$("#id_area_asignada").val();
	  }else if(tipo==5){ window.location.href="solicitudes_correspondencia.php?a="+$("#id_area_asignada").val();
	  }else if(tipo==6){ window.location.href="avance_actividades_visitadurias.php?a="+$("#id_area_asignada").val();
	  }
	}else{
		//alert("Indique el Área");
		document.getElementById('danger').style.display = 'block';
	}
}
</script>
<?php include_once('layouts/header.php'); ?>

<a href="solicitudes_quejas.php" class="btn btn-info">Regresar a Áreas</a><br><br>
<h1 style="color: #3a3d44;">Más Actividades de Orientación Legal, Quejas y Seguimiento </h1>


<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<div class="row" style="TEXT-ALIGN: center; width: 25%;margin: 0 auto">
	<div class="panel panel-default">
			<div class="col-md-12">
                        <div class="form-group">
                            <label for="autoridad">Área</label>
                             <select class="form-control" id="id_area_asignada" name="id_area_asignada" required <?php echo ($activo==1)?"disabled":"" ?>  >
                                <option value="">Escoge una opción</option>
                                <?php foreach ($areas_quejas as $a) : ?>
                                    <option <?php echo ($activo==1)?($area_user['id_area']==$a['id_area']?"selected='selected'":""):"" ?> value="<?php echo $a['id_area']; ?>"><?php echo ucwords($a['nombre_area']); ?></option>
                                <?php endforeach; ?>
                                    <option <?php echo ($activo==1)?(20==$area_user['id_area']?"selected='selected'":""):"" ?> value="20">Subdirección de Proyectos</option>
                                    <option <?php echo ($activo==1)?(21==$area_user['id_area']?"selected='selected'":""):"" ?> value="21">Coordinación General de Visitadurías</option>
                            </select>
                        </div>
            </div>
    </div>
</div>

<div class="alert alert-danger" role="alert" style="TEXT-ALIGN: center; width: 25%;margin: 0 auto;display:none;" id="danger">
  Por favor, Indique el Área que desea consultar
</div>
<div class="container-fluid">
    <div class="full-box tileO-container">		
	          
        <a href="javascript:accesoEst(1);" class="tileO" id="url_pat">
            <div class="tileO-tittle" style="font-size: 12px;">Programa  Anual de Trabajo</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    engineering
                </span>
            </div>
        </a>
		
        <a href="javascript:accesoEst(2);" class="tileO" id="url_ia">
            <div class="tileO-tittle">Informe de Actividades</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    task_alt
                </span>
            </div>
        </a>
<?php if ($nivel_user <= 2 || $nivel_user == 5 || $nivel_user == 7 || $nivel_user == 26 || $nivel_user == 50) :?>
		<a href="javascript:accesoEst(6);" class="tileO" id="url_ia">
            <div class="tileO-tittle">Avances de Actividades</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    monitoring
                </span>
            </div>
        </a>
		<?php endif;?>
	

        <a href="javascript:accesoEst(3);" class="tileO" id="url_cap">
            <div class="tileO-tittle">Capacitaciones</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    supervisor_account
                </span>
            </div>
        </a>
       
        <a href="javascript:accesoEst(4);" class="tile" id="url_eve">
            <div class="tile-tittle">Eventos</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    event_available
                </span>
            </div>
        </a>
		 <a href="javascript:accesoEst(5);" class="tile" id="url_mail">
            <div class="tile-tittle">Correspondencia</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    local_post_office
                </span>
            </div>
        </a>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>