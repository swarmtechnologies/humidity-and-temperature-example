<?php
/*
* Capturamos la variable mac que pasó por método post para solicitar los datos
* de este dispositivo en específico
*/
$mac = $_POST['mac'];
//Si la variable mac no está definida, entonces redireccionamos a select-device
if(!isset($mac)){
    header('Location: '."select-device.php");
}
/*
* Iniciando la instancia de la base de datos
* El método más sencillo para conectarse a una base de datos desde PHP es el método mysqli
* El órden en el que deben pasarse los datos de conexión de la base de datos es el siguiente:
* -Host donde se encuentra alojada la base de datos
* -Nombre de usuario de la base de datos
* -Contraseña del usuario de la base de datos
* -Nombre de la base de datos
*/
$mysqli = new mysqli('host', 'usuario', 'password', 'nombre_base_de_datos');
//Si la conexión falló, necesitamos saberlo, así que generamos un mensaje
//PD: Esto sólo lo hacemos porque estamos prototipando, en producción jamás le mostramos esto al cliente, en su lugar mostramos un mensaje genérico de error
if ($mysqli->connect_errno) {
    //Imprimimos la respuesta en formato texto y salimos del programa
    echo "Error: Fallo al conectarse a MySQL debido a: \n";
    echo "Errno: " . $mysqli->connect_errno . "\n";
    echo "Error: " . $mysqli->connect_error . "\n";
    exit;
}

/*
* Armamos la consulta SQL en una cadena de texto.
*/
$sql = "SELECT humidity, temperature, created_at FROM humidity_and_temperature_values WHERE mac = '$mac' ORDER BY id DESC LIMIT 1";
//Si la consulta a la base de datos falló, necesitamos saberlo, así que generamos un mensaje
//PD: Esto sólo lo hacemos porque estamos prototipando, en producción jamás le mostramos esto al cliente, en su lugar mostramos un mensaje genérico de error
if (!$resultado = $mysqli->query($sql)) {
    //Imprimimos la respuesta en formato JSON y salimos del programa
    echo "Error: La ejecución de la consulta falló debido a: \n";
    echo "Query: " . $sql . "\n";
    echo "Errno: " . $mysqli->errno . "\n";
    echo "Error: " . $mysqli->error . "\n";
    exit;
}
//Rescato el único elemento que salió de la consulta
$data = $resultado->fetch_assoc();
//Transformo la fecha por la zona horaria
$dt = new DateTime($data['created_at'], new DateTimeZone('UTC'));
$dt->setTimezone(new DateTimeZone('America/Santiago'));
$data['created_at'] = $dt->format('d-m-Y H:i:s');
?>
<!doctype html>
<html lang="es">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Añado biblioteca paho que es un cliente MQTT para navegador -->
    <script src="paho.javascript-1.0.3/paho-mqtt-min.js"></script>
    <script>
      //Guardamos la variable "mac" que pasó por POST
      var mac = '<?php echo $mac;?>';
      // Creamos una instancia de cliente
      var clientId = "swarm-educate";
      clientId += new Date().getUTCMilliseconds();;
      var client = new Paho.MQTT.Client("educate.swarm.cl", 8080 , clientId);

      // Seteamos las funciones que se ejecutan ante estos eventos
      client.onConnectionLost = onConnectionLost;
      client.onMessageArrived = onMessageArrived;

      // Realizamos la conexión
      client.connect({onSuccess:onConnect});


      // Esta función es llamada cuando se logra conectar al broker
      function onConnect() {
        // Avisamos por consola que nos conectamos correctamente
        console.log("onConnect");
        //Nos suscribimos al tópico al que se envían los paquetes para escuchar su llegada
        client.subscribe("humidity_and_temperature/device/" + mac + "/tx");
      }

      // Esta función es llamada cuando el cliente pierde conexión con el Broker
      function onConnectionLost(responseObject) {
        if (responseObject.errorCode !== 0) {
          console.log("onConnectionLost:"+responseObject.errorMessage);
        }
      }

      // Función llamada cuando llegan mensajes al tópico suscrito
      function onMessageArrived(message) {
        //Mostramos en consola como debug el mensaje que llegó
        console.log("onMessageArrived:"+message.payloadString);
        //Parseamos el mensaje para dejarlo como un objeto
        var package = JSON.parse(message.payloadString);
        document.getElementById('humidity').innerHTML = package.humidity;
        document.getElementById('temperature').innerHTML = package.temperature;
        var now = new Date();
        var createdAt = now.getDate() + "-" + now.getMonth() + "-" + now.getFullYear() + " " + now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
        document.getElementById('created_at').innerHTML = createdAt;
      }
    </script>

    <title>Real Time - Humidity and temperature example</title>
  </head>
  <body>
    <div class="container text-center">
      <h4>Paquetes en tiempo real (MQTT) del dispositivo <?php echo $mac; ?></h4>
      <form class="text-left" method="post" action="monitoring-menu.php">
       <input type="hidden" name="mac" value="<?php echo $mac;?>">
       <button type="submit" class="btn btn-primary">Volver al menú</button>
      </form>
        <div class="row justify-content-center">
          <div class="col-md-8">
            <div class="container">
              <div class="row">
                <div class="col-md-12 text-right">
                  <p id="created_at"><?php echo $data['created_at'];?></p>
                </div>
                <div class="col-md-6 text-center">
                  <h3>Humedad</h3>
                  <h4 id="humidity"><?php echo $data['humidity'];?></h4>
                </div>
                <div class="col-md-6 text-center">
                  <h3>Temperatura</h3>
                  <h4 id="temperature"><?php echo $data['temperature'];?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>