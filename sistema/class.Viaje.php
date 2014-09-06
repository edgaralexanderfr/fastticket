<?php
  
  class Viaje {
    const ASIENTOS_MIN = 1;
    const ASIENTOS_MAX = 999;
    
    public static $DESTINOS = array(
      0 => 'Distrito Capital', 
      1 => 'Miranda', 
      2 => 'Aragua', 
      3 => 'Zulia'
    );
    
    public static $mysql;
    public $id;
    public $asientos;
    public $destino;
    public $fechaPartida;
    
    public function setAsientos ($asientos) {
      if (!is_numeric($asientos) || $asientos < self :: ASIENTOS_MIN || $asientos > self :: ASIENTOS_MAX) {
        throw new Exception('Número de asientos inválido.');
      }
      
      $this->asientos = (int) $asientos;
    }
    
    public function setDestino ($destino) {
      if (!is_numeric($destino) || $destino < 0 || $destino >= count(self :: $DESTINOS)) {
        throw new Exception('Destino inválido.');
      }
      
      $this->destino = (int) $destino;
    }
    
    public function setFechaPartida ($dia, $mes, $ano, $hora, $minutos) {
      if (!is_numeric($dia) || !is_numeric($mes) || !is_numeric($ano) || !checkdate((int) $mes, (int) $dia, (int) $ano) || $ano < 0 || $ano > 9999) {
        throw new Exception('Fecha de partida inválida.');
      }
      
      if (!is_numeric($hora) || $hora < 0 || $hora > 23 || ($minutos != '00' && $minutos != '30')) {
        throw new Exception('Hora y minutos de partida inválidos.');
      }
      
      if ($dia < 10) {
        $dia = '0' . $dia;
      }
      
      if ($mes < 10) {
        $mes = '0' . $mes;
      }
      
      if ($hora < 10) {
        $hora = '0' . $hora;
      }
      
      if ($ano < 10) {
        $ano = '000' . $ano;
      } else 
      if ($ano < 100) {
        $ano = '00' . $ano;
      } else 
      if ($ano < 1000) {
        $ano = '0' . $ano;
      }
      
      $this->fechaPartida = $ano . '-' . $mes . '-' . $dia . ' ' . $hora . ':' . $minutos . ':00';
    }
    
    public function programar () {
      mysql_query("insert into viajes (asientos, destino, fecha_partida) values (" . $this->asientos . ", " . $this->destino . ", '" . $this->fechaPartida . "')", self :: $mysql);
    }
    
    public static function get () {
      $consulta = mysql_query('select id, destino, fecha_partida from viajes', self :: $mysql);
      
      $viajes = array();
      
      while ($fila = mysql_fetch_assoc($consulta)) {
        $viajes[] = array(
          'id' => $fila['id'], 
          'destino' => $fila['destino'], 
          'fecha_partida' => $fila['fecha_partida']
        );
      }
      
      return $viajes;
    }
  }