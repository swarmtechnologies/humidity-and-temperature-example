<?php
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
$sql = "SELECT DISTINCT mac FROM humidity_and_temperature_values";
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

    <title>Select device - Humidity and temperature example</title>
  </head>
  <body>
    <div class="container text-center">
      <h4>Selecciona el dispositivo que deseas monitorear</h4>
      <form method="post" action="monitoring-menu.php">
        <div class="row">
          <div class="col-md-8">
            <div class="form-group">
              <select name="mac" class="form-control">
                <?php
                //Recorro todos los resultados para imprimir los dispositivos que han enviado datos al menos una vez
                while($device = mysqli_fetch_assoc($resultado)){
                        $mac = $device['mac'];
                        echo "<option value='$mac'>$mac</option>";
                    }
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <button type="submit" class="btn btn-primary">Monitorear dispositivo</button>
          </div>
        </div>
        <br/>
        <div class="row justify-content-center">
          <div class="col-md-6">
            <form method="get" action="index.html">
              <button type="submit" class="btn btn-block btn-primary">Volver al menú principal</button>
            </form>
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