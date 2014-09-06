<?php
  
  class Boleto {
    const TIEMPO_PREVIO_MAX = 432000;
    const TIEMPO_EXPIRACION = 259200;
    
    public static $mysql;
    public $id;
    public $idViaje;
    public $codigoReservacion;
    public $fechaRegistro;
    public $comprado;
    
    public function reservar ($idViaje) {
      $consulta = mysql_query("select timestampdiff(second, now(), fecha_partida) as tiempoDiferencia, asientos from viajes where id = '" . mysql_real_escape_string($idViaje) . "'", self :: $mysql) or die(mysql_error());
      
      if (!($fila = mysql_fetch_assoc($consulta))) {
        throw new Exception('Viaje no encontrado.');
      }
      
      if ($fila['tiempoDiferencia'] <= self :: TIEMPO_PREVIO_MAX) {
        throw new Exception('No se pueden hacer más reservaciones para éste viaje.');
      }
      
      if (self :: getAsientosOcupados($idViaje) >= $fila['asientos']) {
        throw new Exception('Lo sentimos, no hay asientos disponibles.');
      }
      
      $this->codigoReservacion = generarcodigo(10);
      
      mysql_query("insert into boletos (id_viaje, codigo_reservacion, fecha_registro, comprado) values (" . $idViaje . ", '" . $this->codigoReservacion . "', now(), 0)", self :: $mysql);
    }
    
    public static function getAsientosOcupados ($idViaje) {
      $consulta = mysql_query("select count(*) as asientosOcupados from boletos where id_viaje = " . $idViaje . "  and (comprado = 1 or timestampdiff(second, fecha_registro, now()) < " . self :: TIEMPO_EXPIRACION . ")", self :: $mysql);
      
      $fila = mysql_fetch_assoc($consulta);
      
      return (int) $fila['asientosOcupados'];
    }
    
    public function venderBoletoReservado ($codigoReservacion) {
      $this->codigoReservacion = mysql_real_escape_string(strtoupper($codigoReservacion));
      
      $consulta = mysql_query("select timestampdiff(second, fecha_registro, now()) as tiempoDiferencia from boletos where codigo_reservacion = '" . $this->codigoReservacion . "'", self :: $mysql);
      
      if (!($fila = mysql_fetch_assoc($consulta))) {
        throw new Exception('Código inválido.');
      }
      
      if ($fila['tiempoDiferencia'] > self :: TIEMPO_EXPIRACION) {
        throw new Exception('Ésta reservación ha expirado.');
      }
      
      mysql_query("update boletos set comprado = 1 where codigo_reservacion = '" . $this->codigoReservacion . "'", self :: $mysql);
    }
    
    public function vender ($idViaje) {
      $consulta = mysql_query("select asientos, timestampdiff(second, now(), fecha_partida) as tiempoDiferencia from viajes where id = '" . mysql_real_escape_string($idViaje) . "'", self :: $mysql);
      
      if (!($fila = mysql_fetch_assoc($consulta))) {
        throw new Exception('Viaje no encontrado.');
      }
      
      if ($fila['tiempoDiferencia'] <= 0) {
        throw new Exception('No se pueden vender más boletos para éste viaje.');
      }
      
      if (self :: getAsientosOcupados($idViaje) >= $fila['asientos']) {
        throw new Exception('No hay asientos disponibles para éste viaje.');
      }
      
      mysql_query("insert into boletos (id_viaje, codigo_reservacion, fecha_registro, comprado) values (" . $idViaje . ", null, now(), 1)", self :: $mysql);
    }
  }