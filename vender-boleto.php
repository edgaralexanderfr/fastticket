<?php
  
  require_once './sistema/function.validararray.php';
  require_once './sistema/class.Usuario.php';
  require_once './sistema/class.Sesion.php';
  require_once './sistema/class.Viaje.php';
  require_once './php/conexion.php';
  
  $sesion = Sesion :: get();
  
  if ($sesion == null || !$sesion->admin) {
    header('Location: ./');
    exit();
  }
  
  Viaje :: $mysql = $mysql;
  $viajes = Viaje :: get();
  
?>
<!doctype html>
  <html>
    <head>
      <link rel="stylesheet" type="text/css" href="./css/main.css" />
      <link rel="stylesheet" type="text/css" href="./css/vender-boleto.css" />
      <title>FastTicket - Vender boleto</title>
      <script type="text/javascript">
        window.addEventListener('load', function (evt) {
          document.getElementById('formulario').onsubmit = function (evt) {
            if (!confirm('¿Desea vender éste boleto?')) {
              evt.preventDefault();
            }
          }
        }, false);
      </script>
      <?php
        
        if (validararray($_POST, array('idViaje'))) {
          require_once './sistema/class.Boleto.php';
          
          Boleto :: $mysql = $mysql;
          $boleto = new Boleto();
          
          try {
            $boleto->vender($_POST['idViaje']);
            
            $mensaje = 'Boleto vendido exitosamente.';
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
          Vender boleto.
        </div>
        <div class="right">
          <a class="boton" href="./">Menú principal</a>
        </div>
      </div>
      <form action="./vender-boleto.php" method="post" id="formulario">
        <div class="item">
          <select name="idViaje" class="input" style="width : 80%;">
            <option value="-1">Seleccionar viaje</option>
            <?php foreach ($viajes as $viaje) : ?>
            <option value="<?= (string) $viaje['id'] ?>"><?= Viaje :: $DESTINOS[ (int) $viaje['destino'] ] ?> - <?= $viaje['fecha_partida'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="item">
          <button class="boton">Vender</button>
        </div>
      </form>
      <?php require_once './html/footer.html'; ?>
    </body>
  </html>