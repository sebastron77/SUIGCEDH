<?php
require_once('load2.php');
/*--------------------------------------------------------------*/
/* Funcion para llevar a cabo queries
/*--------------------------------------------------------------*/
// function find_by_sql2($sql)
// {
//   global $db;
//   $result = $db->query($sql);
//   $result_set = $db->while_loop($result);
//   return $result_set;
// }
/*-----------------------------------*/
/* Determina si una tabla ya existe */
/*-----------------------------------*/
// function tableExists2($table)
// {
//   global $db;
//   $table_exit = $db->query('SHOW TABLES FROM ' . DB_NAME2 . ' LIKE "' . $db->escape($table) . '"');
//   if ($table_exit) {
//     if ($db->num_rows($table_exit) > 0)
//       return true;
//     else
//       return false;
//   }
// }
/*--------------------------------------------------------------*/
/*  Funcion para encontrar datos por su id en una tabla
/*--------------------------------------------------------------*/
// function find_by_id2($table, $id)
// {
//   global $db;
//   $id = (int)$id;
//   if (tableExists2($table)) {
//     $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
//     if ($result = $db->fetch_assoc($sql))
//       return $result;
//     else
//       return null;
//   }
// }
/*--------------------------------------------------------------------------*/
/* Encuentra el usuario logueado actualmente en la sesion por el ID de esta */
/*--------------------------------------------------------------------------*/
// function current_user2()
// {
//   static $current_user;
//   global $db;
//   if (!$current_user) {
//     if (isset($_SESSION['user_id'])) :
//       $user_id = intval($_SESSION['user_id']);
//       $current_user = find_by_id2('users', $user_id);
//     endif;
//   }
//   return $current_user;
// }
/*--------------------------------*/
/* Encuentra los niveles de grupo */
/*--------------------------------*/
// function find_by_groupLevel2($level)
// {
//   global $db;
//   $sql = "SELECT nivel_grupo, estatus_grupo FROM grupo_usuarios WHERE nivel_grupo = '{$db->escape($level)}' LIMIT 1 ";
//   $result = $db->query($sql);
//   return ($db->num_rows($result) === 0 ? true : false);
// }
// function page_require_level2($require_level)
// {
//   global $session;
//   $current_user = current_user();
//   $login_level = find_by_groupLevel($current_user['user_level']);
//   //si el usuario no esta logueado
//   if (!$session->isUserLoggedIn(true)) :
//     $session->msg('d', 'Por favor, inicia sesión...');
//     redirect('index.php', false);
//   //si estatus de grupo de usuario esta desactivado
//   elseif (@$login_level['estatus_grupo'] === 0) : //Si se quita el arroba muestra un notice
//     $session->msg('d', 'Este nivel de usuario esta inactivo!');
//     redirect('home.php', false);
//   //checa si el nivel de usuario es menor o igual al requerido
//   elseif ($current_user['user_level'] <= (int)$require_level) :
//     return true;
//   else :
//     $session->msg("d", "¡Lo siento! no tienes permiso para ver la página.");
//     redirect('home.php', false);
//   endif;
// }

/*--------------------------------------------------------------*/
/* Funcion para mostrar las quejas
/*--------------------------------------------------------------*/
// function quejas()
// {
//   global $db;
//   $sql = "SELECT DISTINCT t.number as Folio_Queja, t.lastupdate as Ultima_Actualizacion, d.subject as Autoridad_Responsable,u.name as Creado_Por,";
//   $sql .= " d.priority as Prioridad, s.firstname as Asignado_Nombre, s.lastname as Asignado_Apellido, st.state, t.status_id, t.isoverdue, t.isanswered, t.created, d.ticket_id, d.n_autoridad";
//   $sql .= " FROM ost_ticket as t";
//   $sql .= " LEFT JOIN ost_ticket__cdata as d ON t.ticket_id = d.ticket_id";
//   $sql .= " LEFT JOIN ost_staff as s ON t.staff_id = s.staff_id";
//   $sql .= " LEFT JOIN ost_user as u ON u.id = t.user_id";
//   $sql .= " LEFT JOIN ost_ticket_status as st ON st.id = t.status_id";
//   return find_by_sql2($sql);
// }
/*--------------------------------------------------------------*/
/* Funcion para mostrar queja por su id
/*--------------------------------------------------------------*/
// function find_by_id_quejas($id)
// {
//   global $db;
//   $id = (int)$id;
//   $sql = $db->query(
//   "SELECT DISTINCT t.lastupdate as Ultima_Actualizacion,u.name as Creada_Por,s.firstname as Asignado_Nombre,s.lastname as Asignado_Apellido,t.isoverdue,t.isanswered,
//   d.ticket_id,d.n_autoridad,oud.email,oud.name,oud.n_estudios,oud.ocupacion,oud.edad,oud.phone,oud.sexo,oud.direccion,oud.colonia,oud.cp,oud.municipio,oud.entidad,
//   oud.nacionalidad,d.visitaduria,d.subject,d.agraviado,d.a_firma,d.priority,d.h_direccion,d.h_colonia,d.h_municipio,d.h_entidad
  
//   FROM ost_ticket as t LEFT JOIN ost_ticket__cdata as d ON t.ticket_id = d.ticket_id LEFT JOIN ost_staff as s ON t.staff_id = s.staff_id 
//   LEFT JOIN ost_user as u ON u.id = t.user_id LEFT JOIN ost_user__cdata as oud ON oud.user_id = u.id
  
//   WHERE t.ticket_id = '{$db->escape($id)}'");

//   if ($result = $db->fetch_assoc($sql))
//     return $result;
//   else
//     return null;
// }

// ANTES DE REALIZARLE MODIFICACIONES
// function find_by_id_quejas($id)
// {
//   global $db;
//   $id = (int)$id;
//   $sql = $db->query("SELECT DISTINCT t.number as Folio_Queja,t.lastupdate as Ultima_Actualizacion,d.subject as Autoridad_Responsable,u.name as Creada_Por,d.priority as Prioridad,s.firstname as Asignado_Nombre,
//   s.lastname as Asignado_Apellido,st.state,t.status_id,t.isoverdue,t.isanswered,d.ticket_id,d.n_autoridad FROM ost_ticket as t LEFT JOIN ost_ticket__cdata as d ON t.ticket_id = d.ticket_id
//   LEFT JOIN ost_staff as s ON t.staff_id = s.staff_id LEFT JOIN ost_user as u ON u.id = t.user_id LEFT JOIN ost_ticket_status as st ON st.id = t.status_id WHERE t.ticket_id = '{$db->escape($id)}'");
//   if ($result = $db->fetch_assoc($sql))
//     return $result;
//   else
//     return null;
// }
?>