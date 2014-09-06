<!doctype html>
  <html>
    <head>
      <link rel="stylesheet" type="text/css" href="./css/main.css" />
      <link rel="stylesheet" type="text/css" href="./css/registrarse.css" />
      <title>FastTicket - Registrarse</title>
      <?php
        
        require_once './sistema/function.validararray.php';
        
        if (validararray($_POST, array('alias', 'password', 'confirmarPassword', 'cedula', 'nombres', 'apellidos'))) {
          require_once './sistema/class.Usuario.php';
          require_once './php/conexion.php';
          
          Usuario :: $mysql = $mysql;
          $usuario = new Usuario();
          
          try {
            $usuario->setAlias($_POST['alias']);
            $usuario->setPassword($_POST['password'], $_POST['confirmarPassword']);
            $usuario->setCedula($_POST['cedula']);
            $usuario->setNombres($_POST['nombres']);
            $usuario->setApellidos($_POST['apellidos']);
            
            $usuario->registrar();
            
            $mensaje = 'Te has registrado correctamente.';
          } catch (Exception $ex1) {
            $mensaje = $ex1->getMessage();
          }
          
          ?>
          <script>
            alert('<?= $mensaje ?>');
          </script>
          <?php
        }
        
      ?>
    </head>
    <body>
      <?php require_once './html/header.html'; ?>
      <div class="tituloModulo">
        <div class="left">
          Registrarse.
        </div>
        <div class="right">
          <a class="boton" href="./">Menú principal</a>
        </div>
      </div>
      <form action="./registrarse.php" method="post">
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
          <div class="left">
            Confirmar contraseña:
          </div>
          <div class="right">
            <input name="confirmarPassword" class="input" type="password" />
          </div>
        </div>
        <div class="item">
          <div class="left">
            Cédula:
          </div>
          <div class="right">
            <input name="cedula" class="input" type="text" />
          </div>
        </div>
        <div class="item">
          <div class="left">
            Nombres:
          </div>
          <div class="right">
            <input name="nombres" class="input" type="text" />
          </div>
        </div>
        <div class="item">
          <div class="left">
            Apellidos:
          </div>
          <div class="right">
            <input name="apellidos" class="input" type="text" />
          </div>
        </div>
        <div class="item">
          <button class="boton">Enviar</button>
        </div>
      </form>
      <?php require_once './html/footer.html'; ?>
    </body>
  </html>