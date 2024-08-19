<?php
require_once('includes/load.php');

$id = $_POST['id'];
$user = current_user();
echo $id;

$sql = "DELETE FROM rel_gestiones WHERE id_rel_gestion=".$id;
$result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
    echo "Records were deleted successfully.";
	insertAccion($user['id_user'], '"'.$user['username'].'" eliminó el oficio de colaboración('.$id.').', 1);
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
?>