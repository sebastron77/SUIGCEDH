<?php
  require_once('includes/load.php');
  
   page_require_level(53);

$id = $_POST['id'];
$user = current_user();
$sql = "DELETE FROM rel_monitoreo_oficios WHERE id_rel_monitoreo_oficios=".$id;
$result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
    echo "Records were deleted successfully.";
	insertAccion($user['id_user'], '"'.$user['username'].'" eliminó el oficio de Monitoreo de Política Pública('.$id.').', 4);
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
?>
