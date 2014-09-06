<?php
  
  require_once './sistema/function.validararray.php';
  require_once './sistema/class.Usuario.php';
  require_once './sistema/class.Sesion.php';
  
  $sesion = Sesion :: get();
  
  if ($sesion == null || !$sesion->admin) {
    header('Location: ./');
    exit();
  }
  
?>
<!doctype html>
  <html>
    <head>
      <link rel="stylesheet" type="text/css" href="./css/main.css" />
      <link rel="stylesheet" type="text/css" href="./css/vender-boleto-reservado.css" />
      <title>FastTicket - Vender boleto reservado</title>
      <?php
        
        if (validararray($_POST, array('codigoReservacion'))) {
          require_once './sistema/class.Boleto.php';
          require_once './php/conexion.php';
          
          Boleto :: $mysql = $mysql;
          
          $boleto = new Boleto();
          
          try {
            $boleto->venderBoletoReservado($_POST['codigoReservacion']);
            
            $mensaje = 'Reservación actualizada correctamente.';
          } catch (Exception $ex1) {
            $mensaje = $ex1->getMessage();
          }
          
          ?>
          <script>alert('<?= $mensaje ?>');</script>
          <?php
        }
        
      ?>
    </head>
    <body>
      <?php require_once './html/header.html'; ?>
      <div class="tituloModulo">
        <div class="left">
          Vender boleto reservado.
        </div>
        <div class="right">
          <a class="boton" href="./">Menú principal</a>
        </div>
      </div>
      <form action="./vender-boleto-reservado.php" method="post">
        <div class="item">
          <div class="left">
            Código de reservación:
          </div>
          <div class="right">
            <input name="codigoReservacion" class="input" type="text" />
          </div>
        </div>
        <div class="item">
          <button class="boton">Vender</button>
        </div>
      </form>
      <?php require_once './html/footer.html'; ?>
    </body>
  </html>