<?php
  
  require_once './sistema/function.validararray.php';
  require_once './sistema/class.Usuario.php';
  require_once './sistema/class.Sesion.php';
  require_once './sistema/class.Viaje.php';
  
  $sesion = Sesion :: get();
  
  if ($sesion == null || !$sesion->admin) {
    header('Location: ./');
    exit();
  }
  
  $anioActual = (int) date('Y');
  $anioPosterior = $anioActual + 1;
  
?>
<!doctype html>
  <html>
    <head>
      <link rel="stylesheet" type="text/css" href="./css/main.css" />
      <link rel="stylesheet" type="text/css" href="./css/programar-viaje.css" />
      <title>FastTicket</title>
      <?php
        
        if (validararray($_POST, array('asientos', 'destino', 'dia', 'mes', 'ano', 'hora', 'minutos'))) {
          require_once './php/conexion.php';
          
          Viaje :: $mysql = $mysql;
          $viaje = new Viaje();
          
          try {
            $viaje->setAsientos($_POST['asientos']);
            $viaje->setDestino($_POST['destino']);
            $viaje->setFechaPartida($_POST['dia'], $_POST['mes'], $_POST['ano'], $_POST['hora'], $_POST['minutos']);
            
            $viaje->programar();
            
            $mensaje = 'Viaje registrado correctamente.';
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
          Programar viaje.
        </div>
        <div class="right">
          <a class="boton" href="./">Menú principal</a>
        </div>
      </div>
      <form action="./programar-viaje.php" method="post">
        <div class="separador">
          <div class="a">
            <input name="asientos" class="input" type="text" style="width : 100px;" />
          </div>
          <div class="b">
            <select name="destino" class="input" style="width : 200px;">
              <option value="-1">Destino</option>
              <?php foreach (Viaje :: $DESTINOS as $key => $value) : ?>
              <option value="<?= (string) $key ?>"><?= $value ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="c">
            <select name="dia" class="input" style="width : 50px;">
              <option value="-1">Día</option>
              <?php for ($i = 1; $i <= 31; $i++) : ?>
              <option value="<?= (string) $i ?>"><?= (string) $i ?></option>
              <?php endfor; ?>
            </select>
            <select name="mes" class="input" style="width : 50px;">
              <option value="-1">Mes</option>
              <?php for ($i = 1; $i <= 12; $i++) : ?>
              <option value="<?= (string) $i ?>"><?= (string) $i ?></option>
              <?php endfor; ?>
            </select>
            <select name="ano" class="input" style="width : 100px;">
              <option value="-1">Año</option>
              <option value="<?= (string) $anioActual ?>"><?= (string) $anioActual ?></option>
              <option value="<?= (string) $anioPosterior ?>"><?= (string) $anioPosterior ?></option>
            </select>
          </div>
          <div class="d">
            <select name="hora" class="input" style="width : 100px;">
              <option value="-1">Hora de partida</option>
              <option value="0">12 am.</option>
              <option value="12">12 pm.</option>
              <?php for ($i = 1; $i <= 11; $i++) : ?>
              <option value="<?= (string) $i ?>"><?= (string) $i ?> am.</option>
              <?php endfor; ?>
              <?php for ($i = 1; $i <= 11; $i++) : ?>
              <option value="<?= (string) 12 + $i ?>"><?= (string) $i ?> pm.</option>
              <?php endfor; ?>
            </select>
            <select name="minutos" class="input" style="width : 50px;">
              <option value="-1">Minuto de partida</option>
              <option value="00">00</option>
              <option value="30">30</option>
            </select>
          </div>
        </div>
        <div class="separador">
          <button class="boton">Programar</button>
        </div>
      </form>
      <?php require_once './html/footer.html'; ?>
    </body>
  </html>