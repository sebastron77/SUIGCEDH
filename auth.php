<?php include_once('includes/load.php'); ?>
<?php
$req_fields = array('username', 'password');
validate_fields($req_fields);
$username = remove_junk($_POST['username']);
$password = remove_junk($_POST['password']);

if (empty($errors)) {
  $user_id = authenticate($username, $password);
  if ($user_id) {
    //crea sesion con el id
    $session->login($user_id);
    //Actualiza fecha de inicio de sesion
    updateLastLogIn($user_id);

    
    $session->msg("s", "Bienvenido al Sistema Único de Información y Gestión de la CEDH (SUIGCEDH)");
    $user = current_user();
    $nivel = $user['user_level'];
    insertAccion($user['id_user'],'"'.$user['username'].'" inició sesión.',0);
    if ($nivel == 1) {
      redirect('admin.php', false);
    }
    if ($nivel >= 2) {
      redirect('home.php', false);
    }


  } else {
    $session->msg("d", "Nombre de usuario y/o contraseña incorrecto. O tu cuenta no está activada.");
    redirect('index.php', false);
  }

} else {
  $session->msg("d", $errors);
  redirect('index.php', false);
}

?>