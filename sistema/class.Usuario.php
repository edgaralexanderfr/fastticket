<?php
  
  class Usuario {
    const MIN_LONG_ALIAS = 4;
    const MAX_LONG_ALIAS = 100;
    const MIN_LONG_PASSWORD = 10;
    const CEDULA_MIN = 100000;
    const CEDULA_MAX = 99999999;
    const MIN_LONG_NOMBRES = 3;
    const MAX_LONG_NOMBRES = 50;
    const MIN_LONG_APELLIDOS = 3;
    const MAX_LONG_APELLIDOS = 50;
    
    public static $mysql;
    public $id;
    public $alias;
    public $password;
    public $salt;
    public $cedula;
    public $nombres;
    public $apellidos;
    public $fechaRegistro;
    public $admin;
    
    public function setAlias ($alias) {
      if (!ctype_alnum($alias)) {
        throw new Exception('El alias debe ser alfanumérico.', 1);
      }
      
      $longitud = strlen($alias);
      
      if ($longitud < self :: MIN_LONG_ALIAS || $longitud > self :: MAX_LONG_ALIAS) {
        throw new Exception('Alias muy corto o largo.', 2);
      }
      
      $this->alias = (string) $alias;
    }
    
    public function setPassword ($password, $confirmarPassword) {
      if ($password != $confirmarPassword) {
        throw new Exception('Las contraseñas no coincíden.', 1);
      }
      
      $longitud = strlen($password);
      
      if ($longitud < self :: MIN_LONG_PASSWORD) {
        throw new Exception('La contraseña es muy corta.', 2);
      }
      
      $this->salt = md5(mcrypt_create_iv(40));
      $this->password = sha1($password . $this->salt);
    }
    
    public function setCedula ($cedula) {
      $cedula = str_replace('.', '', (string) $cedula);
      
      if (!is_numeric($cedula)) {
        throw new Exception('La cédula debe ser un número.', 1);
      }
      
      if ($cedula < self :: CEDULA_MIN || $cedula > self :: CEDULA_MAX) {
        throw new Exception('Cédula inválida.', 2);
      }
      
      $this->cedula = number_format((string) $cedula, 0, '', '.');
    }
    
    public function setNombres ($nombres) {
      $longitud = strlen($nombres);
      
      if ($longitud < self :: MIN_LONG_NOMBRES) {
        throw new Exception('Nombre(s) muy corto(s).', 1);
      }
      
      if ($longitud > self :: MAX_LONG_NOMBRES) {
        throw new Exception('Nombre(s) muy largo(s).', 2);
      }
      
      $this->nombres = (string) $nombres;
    }
    
    public function setApellidos ($apellidos) {
      $longitud = strlen($apellidos);
      
      if ($longitud < self :: MIN_LONG_APELLIDOS) {
        throw new Exception('Apellido(s) muy corto(s).', 1);
      }
      
      if ($longitud > self :: MAX_LONG_APELLIDOS) {
        throw new Exception('Apellido(s) muy largo(s).', 2);
      }
      
      $this->apellidos = (string) $apellidos;
    }
    
    public function registrar () {
      $this->alias = mysql_real_escape_string($this->alias);
      
      $consulta = mysql_query("select count(*) as cuenta from usuarios where alias = '" . $this->alias . "'", self :: $mysql);
      $fila = mysql_fetch_assoc($consulta);
      
      if ($fila['cuenta'] > 0) {
        throw new Exception('Ya existe un usuario registrado con éste alias.');
      }
      
      $this->cedula = mysql_real_escape_string($this->cedula);
      $this->nombres = mysql_real_escape_string($this->nombres);
      $this->apellidos = mysql_real_escape_string($this->apellidos);
      
      mysql_query("insert into usuarios (alias, password, salt, cedula, nombres, apellidos, fechaRegistro) values ('" . $this->alias . "', '" . $this->password . "', '" . $this->salt . "', '" . $this->cedula . "', '" . $this->nombres . "', '" . $this->apellidos . "', now())", self :: $mysql);
    }
  }