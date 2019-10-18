<?php
//Declaramos en la cabecera de la respuesta HTTP que el contenido que enviamos es un JSON
header('Content-Type: application/json');
//Capturando los datos desde la petición HTTP
$mac = $_POST['mac'];
$temperature = $_POST['temperature'];
$humidity = $_POST['humidity'];

//Como necesitamos todos estos datos para que el programa tenga sentido, validamos que existan
if(!isset($mac) || !isset($temperature) || !isset($humidity)){
	$response = [
		"status" => "ERROR",
		"message" => "Todos los campos son requeridos (mac, temperature y humidity)"
	];
    //Imprimimos la respuesta en formato JSON y salimos del programa
    exit(json_encode($response));
}

/*
*	Iniciando la instancia de la base de datos
*	El método más sencillo para conectarse a una base de datos desde PHP es el método mysqli
*	El órden en el que deben pasarse los datos de conexión de la base de datos es el siguiente:
*	-Host donde se encuentra alojada la base de datos
*	-Nombre de usuario de la base de datos
*	-Contraseña del usuario de la base de datos
*	-Nombre de la base de datos
*/
$mysqli = new mysqli('host', 'usuario', 'password', 'nombre_base_de_datos');
//Si la conexión falló, necesitamos saberlo, así que generamos un mensaje
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
*	Armamos la consulta SQL en una cadena de texto.
*	Como se estipula dentro de la carpeta "database" dentro de este mismo repositorio,
*	la base de datos consta de sólo una tabla llamada "values", la cual contiene
*	tres campos que deben ingresarse para insertar datos (mac, temperature y humidity)
*/
$sql = "INSERT INTO humidity_and_temperature.values (mac, temperature, humidity) VALUES ('$mac', $temperature, $humidity)";
//Si la consulta a la base de datos falló, necesitamos saberlo, así que generamos un mensaje
if (!$resultado = $mysqli->query($sql)) {
    $response = [
		"status" => "ERROR",
		"message" => "Ocurrió un error en la consulta de inserción de datos",
		"Errno" => $mysqli->connect_errno,
		"Error" => $mysqli->connect_error
	];
    //Imprimimos la respuesta en formato JSON y salimos del programa
    exit(json_encode($response));
}
//Si el programa logró llegar hasta aquí, significa que la información fue insertada con éxito
$response = [
	"status" => "OK"
];
//Comunicamos que el proceso ocurrió con normalidad
echo json_encode($response);