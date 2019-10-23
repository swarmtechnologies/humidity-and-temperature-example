<?php
  /*
  * Capturamos la variable mac que pasó por método post para luego enviarla al presionar
  * uno de los botones del menú
  */
  $mac = $_POST['mac'];
  //Si la variable mac no está definida, entonces redireccionamos a select-device
  if(!isset($mac)){
      header('Location: '."select-device.php");
  }
?>
<!doctype html>
<html lang="es">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Monitoring Menu - Humidity and temperature example</title>
  </head>
  <body>
    <div class="container text-center">
      <h4>¿Cómo deseas monitorear el dispositivo <?php echo $mac;?>?</h4>
      <form method="post" action="monitoring-table.php">
        <div class="row justify-content-center">
          <div class="col-md-6">
             <input type="hidden" name="mac" value="<?php echo $mac;?>">
             <button type="submit" class="btn btn-block btn-primary">Últimos 20 datos en una tabla</button>
          </div>
        </div>
      </form>
      <br/>
      <form method="post" action="monitoring-chart.php">
        <div class="row justify-content-center">
          <div class="col-md-6">
             <input type="hidden" name="mac" value="<?php echo $mac;?>">
             <button type="submit" class="btn btn-block btn-primary">Graficar todos los datos</button>
          </div>
        </div>
      </form>
      <br/>
      <form method="post" action="monitoring-latest.php">
        <div class="row justify-content-center">
          <div class="col-md-6">
             <input type="hidden" name="mac" value="<?php echo $mac;?>">
             <button type="submit" class="btn btn-block btn-primary">Último registro</button>
          </div>
        </div>
      </form>
      <br/>
      <form method="post" action="monitoring-periodic.php">
        <div class="row justify-content-center">
          <div class="col-md-6">
             <input type="hidden" name="mac" value="<?php echo $mac;?>">
             <button type="submit" class="btn btn-block btn-primary">Último registro con refresco periódico</button>
          </div>
        </div>
      </form>
      <br/>
      <form method="post" action="monitoring-realtime.php">
        <div class="row justify-content-center">
          <div class="col-md-6">
             <input type="hidden" name="mac" value="<?php echo $mac;?>">
             <button type="submit" class="btn btn-block btn-primary">Último registro en tiempo real (MQTT)</button>
          </div>
        </div>
      </form>
      <br />
      <form method="get" action="select-device.php">
        <div class="row justify-content-center">
          <div class="col-md-6">
             <button type="submit" class="btn btn-block btn-info">Volver al selector de dispositivo</button>
          </div>
        </div>
      </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>