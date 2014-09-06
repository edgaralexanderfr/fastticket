<?php
  
  require_once './sistema/class.Sesion.php';
  
  Sesion :: logout();
  
  header('Location: ./');