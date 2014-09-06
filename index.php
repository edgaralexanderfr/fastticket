<?php
  
  require_once './sistema/class.Usuario.php';
  require_once './sistema/class.Sesion.php';
  
  $sesion = Sesion :: get();
  
?>
<!doctype html>
  <html>
    <head>
      <link rel="stylesheet" type="text/css" href="./css/main.css" />
      <link rel="stylesheet" type="text/css" href="./css/index.css" />
      <title>FastTicket</title>
    </head>
    <body>
      <?php require_once './html/header.html'; ?>
      <div class="tituloModulo">
        <div class="left">
          Título del módulo.
        </div>
        <div class="right">
          <a class="boton" href="./">Menú principal</a>
        </div>
      </div>
      <?php if ($sesion == null) : ?>
      <div class="opcion">
        <a class="boton" href="./registrarse.php">Registrarse</a>
      </div>
      <div class="opcion">
        <a class="boton" href="./login.php">Iniciar sesión</a>
      </div>
      <?php else : ?>
      <div class="opcion">
        <a class="boton" href="./reservar-boleto.php">Reservar boleto</a>
      </div>
      <div class="opcion">
        <a class="boton" href="./logout.php">Cerrar sesión</a>
      </div>
      <?php if ($sesion->admin) : ?>
      <div class="opcion">
        <a class="boton" href="./programar-viaje.php">Programar viaje</a>
      </div>
      <div class="opcion">
        <a class="boton" href="./vender-boleto-reservado.php">Vender boleto reservado</a>
      </div>
      <div class="opcion">
        <a class="boton" href="./vender-boleto.php">Vender boleto</a>
      </div>
      <?php endif; ?>
      <?php endif; ?>
      <?php require_once './html/footer.html'; ?>
    </body>
  </html>