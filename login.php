<?php
  
  require_once './sistema/function.validararray.php';
  
  $mensaje = '';
  
  if (validararray($_POST, array('alias', 'password'))) {
    require_once './sistema/class.Sesion.php';
    require_once './php/conexion.php';
    
    Sesion :: $mysql = $mysql;
    
    try {
      Sesion :: login($_POST['alias'], $_POST['password']);
      
      header('Location: ./');
      exit();
    } catch (Exception $ex1) {
      $mensaje = $ex1->getMessage();
    }
  }
  
?>
<!doctype html>
  <html>
    <head>
      <link rel="stylesheet" type="text/css" href="./css/main.css" />
      <link rel="stylesheet" type="text/css" href="./css/login.css" />
      <title>FastTicket - Iniciar sesión</title>
      <?php if ($mensaje != '') : ?>
      <script>
        alert('<?= $mensaje ?>');
      </script>
      <?php endif; ?>
    </head>
    <body>
      <?php require_once './html/header.html'; ?>
      <div class="tituloModulo">
        <div class="left">
          Iniciar sesión.
        </div>
        <div class="right">
          <a class="boton" href="./">Menú principal</a>
        </div>
      </div>
      <form action="./login.php" method="post">
        <div class="item">
          <div class="left">
            Alias:
          </div>
          <div class="right">
            <input name="alias" class="input" type="text" />
          </div>
        </div>
        <div class="item">
          <div class="left">
            Contraseña:
          </div>
          <div class="right">
            <input name="password" class="input" type="password" />
          </div>
        </div>
        <div class="item">
          <button class="boton">Acceder</button>
        </div>
      </form>
      <?php require_once './html/footer.html'; ?>
    </body>
  </html>