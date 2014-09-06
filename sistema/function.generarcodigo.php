<?php
  
  function generarcodigo ($longitud) {
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $indiceMax = strlen($caracteres) - 1;
    $codigo = '';
    
    for ($i = 1; $i <= $longitud; $i++) {
      $codigo .= $caracteres[ rand(0, $indiceMax) ];
    }
    
    return $codigo;
  }