<?php
require_once(LIB_PATH_INC . DS . "config2.php");

class MySqli_DB2
{

  private $con;
  public $query_id;

  function __construct()
  {
    $this->db_connect();
  }

  /*--------------------------------------------------------------*/
  /* Funcion para abrir la conexion con la base de datos
/*--------------------------------------------------------------*/
  public function db_connect()
  {
    $this->con = mysqli_connect(DB_HOST2, DB_USER2, DB_PASS2);
    if (!$this->con) {
      die("Falló la conexión con la base de datos: " . mysqli_connect_error());
    } else {
      $select_db = $this->con->select_db(DB_NAME2);
      if (!$select_db) {
        die("Falló la selección de Base de Datos: " . mysqli_connect_error());
      }
    }
  }
  /*--------------------------------------------------------------*/
  /* Funcion para cerrar la conexion con la base de datos
/*--------------------------------------------------------------*/

  public function db_disconnect()
  {
    if (isset($this->con)) {
      mysqli_close($this->con);
      unset($this->con);
    }
  }
  /*--------------------------------------------------------------*/
  /* Funcion para query mysqli
/*--------------------------------------------------------------*/
  public function query($sql)
  {

    if (trim($sql != "")) {
      $this->query_id = $this->con->query($sql);
    }
    if (!$this->query_id)
      // solo para modo de desarrollo
      die("Error en esta consulta :<pre> " . $sql . "</pre>");
    // Para modo de producción
    //  die("Error en la consulta de la información");

    return $this->query_id;
  }

  /*--------------------------------------------------------------*/
  /* Funcion para ayudar en las consultas
/*--------------------------------------------------------------*/
  public function fetch_array($statement)
  {
    return mysqli_fetch_array($statement);
  }
  public function fetch_object($statement)
  {
    return mysqli_fetch_object($statement);
  }
  public function fetch_assoc($statement)
  {
    return mysqli_fetch_assoc($statement);
  }
  public function num_rows($statement)
  {
    return mysqli_num_rows($statement);
  }
  public function insert_id()
  {
    return mysqli_insert_id($this->con);
  }
  public function affected_rows()
  {
    return mysqli_affected_rows($this->con);
  }
  /*--------------------------------------------------------------*/
  /* Funcion para remover caracteres especiales
 /* en una cadena para usarlo en una consulta SQL
 /*--------------------------------------------------------------*/
  public function escape($str)
  {
    return $this->con->real_escape_string($str);
  }
  /*--------------------------------------------------------------*/
  /* Funcion para un ciclo while
/*--------------------------------------------------------------*/
  public function while_loop($loop)
  {
    global $db;
    $results = array();
    while ($result = $this->fetch_array($loop)) {
      $results[] = $result;
    }
    return $results;
  }
}

$db = new MySqli_DB2();
