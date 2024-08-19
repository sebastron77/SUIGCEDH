<?php
require_once('includes/load.php');

page_require_level(1);
?>
<?php
$e_user = find_by_id('users', (int)$_GET['id'], 'id_user');
$user = current_user();
$inactivate_id = activate_by_id('users', (int)$_GET['id'], 'status', 'id_user');
insertAccion($user['id_user'], '"' . $user['username'] . '" activó el usuario: ' . $e_user['username'] . '.', 3);
if ($inactivate_id) {
  $session->msg("s", "Usuario activado");
  redirect('users.php');
} else {
  $session->msg("d", "Se ha producido un error en la activación del usuario");
  redirect('users.php');
}
?>
