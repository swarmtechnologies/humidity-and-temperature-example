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
* Como se estipula dentro de la carpeta "database" dentro de este mismo repositorio,
* la base de datos consta de sólo una tabla llamada "values", la cual contiene
* tres campos que deben ingresarse para insertar datos (mac, temperature y humidity)
*/
$sql = "SELECT humidity, temperature, created_at FROM humidity_and_temperature_values WHERE mac = '$mac' ORDER BY id DESC LIMIT 20";
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
?>
<!doctype html>
<html lang="es">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Table - Humidity and temperature example</title>
  </head>
  <body>
    <div class="container text-center">
      <h4>Últimos 20 datos del dispositivo <?php echo $mac; ?></h4>
      <form class="text-left" method="post" action="monitoring-menu.php">
       <input type="hidden" name="mac" value="<?php echo $mac;?>">
       <button type="submit" class="btn btn-primary">Volver al menú</button>
      </form>
        <div class="row justify-content-center">
          <div class="col-md-8">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">Humedad</th>
                  <th scope="col">Temperatura</th>
                  <th scope="col">Fecha</th>
                </tr>
              </thead>
              <tbody>
                <?php
                //Recorro todos los resultados para imprimir la tabla
                while($values = mysqli_fetch_assoc($resultado)){
                    $humidity = $values['humidity'];
                    $temperature = $values['temperature'];
                    $created_at = $values['created_at'];
                    /*
                    * Como los datos vienen en zona horaria UTC, entonces instanciamos
                    * una fecha en la zona horaria UTC y luego la cambiamos a
                    * America/Santiago y la formateamos (Día/Mes/Año Hora:Minutos:Segundos)
                    */
                    $dt = new DateTime($created_at, new DateTimeZone('UTC'));
                    $dt->setTimezone(new DateTimeZone('America/Santiago'));
                    $created_at = $dt->format('d-m-Y H:i:s');
                    echo "<tr>\r\n";
                    echo "<td>$humidity</td>\r\n";
                    echo "<td>$temperature</td>\r\n";
                    echo "<td>$created_at</td>\r\n";
                    echo "</tr>\r\n";
                }
                ?>
              </tbody>
            </table>
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