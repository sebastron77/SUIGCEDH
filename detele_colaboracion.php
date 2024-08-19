<?php
require_once('includes/load.php');

$id = $_POST['id'];
$user = current_user();
echo $id;

$sql = "Delete From rel_colaboracion_oficios Where id_rel_colaboracion_oficios=".$id;
$result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
    echo "Records were deleted successfully.";
	insertAccion($user['id_user'], '"'.$user['username'].'" eliminó el oficio de colaboración('.$id.').', 1);
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
?>