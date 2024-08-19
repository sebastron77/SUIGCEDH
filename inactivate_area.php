<?php
    require_once('includes/load.php');

    page_require_level(1);
?>
<?php

    $inactivate_id = inactivate_by_id('area',(int)$_GET['id'],'estatus_area','id_area');
    $inactivate_cargo = inactivate_area_cargo((int)$_GET['id']);
    
    if($inactivate_id){
        $session->msg("s","Área inactivada");
        redirect('areas.php');
    } else {
        $session->msg("d","Inactivación falló");
        redirect('areas.php');
    }
?>
