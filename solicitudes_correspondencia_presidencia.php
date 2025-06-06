<?php
$page_title = 'Correspondencia';
require_once('includes/load.php');
?>
<?php
page_require_level(53);
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];
$area= 2;
$solicitud = find_by_solicitud($area);
$no_atendidos= count_oficiosInter($area);
$no_atendidos_ext= count_oficiosExt($area);

if ($nivel_user == 7 || $nivel_user == 53) {
		insertAccion($user['id_user'], '"' . $user['username'] . '" Despleglo la lista de  '.$page_title.' del Área -'.$solicitud['nombre_area'].'- ', 5);    
	}
?>

<?php
$c_user = count_by_id('users', 'id_user');
?>

<?php include_once('layouts/header.php'); ?>

<a href="<?php echo $solicitud['nombre_solicitud'];?>" class="btn btn-info">Regresar a Área</a><br><br>
<h1 style="color: #3a3d44;"> Correspondencia de <?php echo $solicitud['nombre_area'];?></h1>

<div class="container-fluid">
		
    <div class="full-box tileO-container">
        
        <a href="correspondencia.php?a=<?php echo $area ?>" class="tileO">
            <div class="tileO-tittle">Oficios-Oficialía</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    history_edu
                </span>
            </div>
        </a>
		
		<a href="correspondencia_emitida_presidencia.php?a=<?php echo $area ?>" class="tile">
            <div class="tile-tittle">Oficios Emitidos</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    edit_document
                </span>
            </div>
        </a>
        
		<a href="correspondencia_recibida.php?a=<?php echo $area ?>" class="tile">
			<span class="position-absolute top-1000 start-1000 translate-middle badge rounded-pill bg-danger" title="<?php echo $no_atendidos['total']; ?> Oficios No Atendidos">
                            <?php echo $no_atendidos['total']; ?>
                            <span class="visually-hidden" >unread messages</span>
                        </span>
            <div class="tile-tittle">Oficios Recibidos</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    file_open
                </span>
            </div>
        </a>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>