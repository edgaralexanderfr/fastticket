<?php
  
  require_once './sistema/function.validararray.php';
  require_once './sistema/class.Usuario.php';
  require_once './sistema/class.Sesion.php';
  require_once './sistema/class.Viaje.php';
  require_once './php/conexion.php';
  
  $sesion = Sesion :: get();
  
  if ($sesion == null) {
    header('Location: ./');
    exit();
  }
  
  Viaje :: $mysql = $mysql;
  $viajes = Viaje :: get();
  
  $mostrarMensaje = false;
  $mensaje = '';
  
  if (validararray($_POST, array('idViaje'))) {
    require_once './sistema/function.generarcodigo.php';
    require_once './sistema/class.Boleto.php';
    
    Boleto :: $mysql = $mysql;
    
    $boleto = new Boleto();
    
    try {
      $boleto->reservar($_POST['idViaje']);
      
      $mostrarMensaje = true;
    } catch (Exception $ex1) {
      $mensaje = $ex1->getMessage();
    }
  }
  
?>
<!doctype html>
  <html>
    <head>
      <link rel="stylesheet" type="text/css" href="./css/main.css" />
      <link rel="stylesheet" type="text/css" href="./css/reservar-boleto.css" />
      <title>FastTicket - Reservar boleto</title>
      <?php if ($mensaje != '') : ?>
      <script>alert('<?= $mensaje ?>');</script>
      <?php endif; ?>
    </head>
    <body>
      <?php require_once './html/header.html'; ?>
      <div class="tituloModulo">
        <div class="left">
          Reservar boleto.
        </div>
        <div class="right">
          <a class="boton" href="./">Menú principal</a>
        </div>
      </div>
      <?php if ($mostrarMensaje) : ?>
      <div class="mensaje"><?= $boleto->codigoReservacion ?></div>
      <?php endif; ?>
      <form action="./reservar-boleto.php" method="post">
        <div class="separador">
          <select name="idViaje" class="input" style="width : 80%;">
            <option value="-1">Seleccionar viaje.</option>
            <?php foreach ($viajes as $viaje) : ?>
            <option value="<?= (string) $viaje['id'] ?>"><?= (string) Viaje :: $DESTINOS[ (int) $viaje['destino'] ] ?> - <?= $viaje['fecha_partida'] ?>.</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="separador">
          <button class="boton">Reservar</button>
        </div>
      </form>
      <?php require_once './html/footer.html'; ?>
    </body>
  </html>