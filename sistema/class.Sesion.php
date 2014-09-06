<?php
  
  class Sesion {
    public static $mysql;
    public static $sesion = null;
    
    public static function login ($alias, $password) {
      $alias = mysql_real_escape_string($alias);
      $password = mysql_real_escape_string($password);
      
      $consulta = mysql_query("select * from usuarios where alias = '" . $alias . "' and password = sha1(concat('" . $password . "', salt))", self :: $mysql);
      
      if (!($fila = mysql_fetch_assoc($consulta))) {
        throw new Exception('Nombre de usuario o contraseña inválidos.');
      }
      
      session_start();
      session_regenerate_id(true);
      
      $_SESSION['id'] = $fila['id'];
      $_SESSION['alias'] = $fila['alias'];
      $_SESSION['password'] = $fila['password'];
      $_SESSION['salt'] = $fila['salt'];
      $_SESSION['cedula'] = $fila['cedula'];
      $_SESSION['nombres'] = $fila['nombres'];
      $_SESSION['apellidos'] = $fila['apellidos'];
      $_SESSION['fechaRegistro'] = $fila['fechaRegistro'];
      $_SESSION['admin'] = $fila['admin'];
    }
    
    public static function get () {
      session_start();
      
      if (!isset($_SESSION['id'])) {
        return null;
      }
      
      self :: $sesion = new Usuario();
      self :: $sesion->id = $_SESSION['id'];
      self :: $sesion->alias = $_SESSION['alias'];
      self :: $sesion->password = $_SESSION['password'];
      self :: $sesion->salt = $_SESSION['salt'];
      self :: $sesion->cedula = $_SESSION['cedula'];
      self :: $sesion->nombres = $_SESSION['nombres'];
      self :: $sesion->apellidos = $_SESSION['apellidos'];
      self :: $sesion->fechaRegistro = $_SESSION['fechaRegistro'];
      self :: $sesion->admin = $_SESSION['admin'];
      
      return self :: $sesion;
    }
    
    public static function logout () {
      session_start();
      session_regenerate_id(true);
      setCookie(session_name(), '/', time() - 3600);
      session_destroy();
    }
  }