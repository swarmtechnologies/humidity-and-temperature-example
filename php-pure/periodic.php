<?php
//Declaramos en la cabecera de la respuesta HTTP que el contenido que enviamos es un JSON
header('Content-Type: application/json');
/*
* Capturamos la variable mac que pasó por método post para solicitar los datos
* de este dispositivo en específico.
* En este caso en particular no enviamos los datos por un formulario, por lo que
* se debe obtener la información de una forma distinta
*/
$v = json_decode(stripslashes(file_get_contents("php://input")));
$mac = $v->mac;
//Si la variable mac no está definida, entonces redireccionamos a select-device
if(!isset($mac)){
    $response = [
        "status" => "Error",
        "message" => "El parámetro 'mac' no está presente en la solicitud"
    ];
    exit(json_encode($response));
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
    $response = [
        "status" => "ERROR",
        "message" => "Ocurrió un error en la conexión a la base de datos",
        "Errno" => $mysqli->connect_errno,
        "Error" => $mysqli->connect_error
    ];
    //Imprimimos la respuesta en formato JSON y salimos del programa
    exit(json_encode($response));
}

/*
* Armamos la consulta SQL en una cadena de texto.
*/
$sql = "SELECT humidity, temperature, created_at FROM humidity_and_temperature_values WHERE mac = '$mac' ORDER BY id DESC LIMIT 1";
//Si la consulta a la base de datos falló, necesitamos saberlo, así que generamos un mensaje
if (!$resultado = $mysqli->query($sql)) {
    $response = [
        "status" => "ERROR",
        "message" => "Ocurrió un error en la consulta de selección de datos",
        "Errno" => $mysqli->connect_errno,
        "Error" => $mysqli->connect_error
    ];
    //Imprimimos la respuesta en formato JSON y salimos del programa
    exit(json_encode($response));
}
//Rescato el único elemento que salió de la consulta
$data = $resultado->fetch_assoc();
//Transformo la fecha por la zona horaria
$dt = new DateTime($data['created_at'], new DateTimeZone('UTC'));
$dt->setTimezone(new DateTimeZone('America/Santiago'));
$data['created_at'] = $dt->format('d-m-Y H:i:s');
$response = [
    "status" => "OK",
    "temperature" => $data['temperature'],
    "humidity" => $data['humidity'],
    "created_at" => $data['created_at']
];
echo json_encode($response);
?>