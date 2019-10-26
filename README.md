# humidity-and-temperature-example

_Este proyecto es un ejemplo de un prototipo que hace uso de HTTP y MQTT para transmitir paquetes en una aplicación IoT que consiste en transmitir temperatura y humedad desde una NodeMCU utilizando un sensor DHT._

_Para quienes no tienen una NodeMCU para replicar por completo el proyecto (o simplemente no desean abordar esta materia) existen dos programas que simulan (usando HTTP y MQTT) el comportamiento de la NodeMCU dentro del sistema._

_Los siguientes diagramas muestran de forma gráfica cómo viaja la información cuando utilizamos el ejemplo con MQTT y con HTTP:_

_Funcionamiento del ejemplo con HTTP_

![Diagrama de funcionamiento con HTTP](https://raw.githubusercontent.com/swarmtechnologies/humidity-and-temperature-example/master/diagramas-de-funcionamiento/funcionamiento_ejemplo_http.png)

_Funcionamiento del ejemplo con MQTT_

![Diagrama de funcionamiento con MQTT](https://raw.githubusercontent.com/swarmtechnologies/humidity-and-temperature-example/master/diagramas-de-funcionamiento/funcionamiento_ejemplo_mqtt.png)

## Comenzando 🚀

_Para obtener una copia de este proyecto sólo es necesario descargarlo o clonarlo directamente desde GitHub._

Mira **Instalación** para conocer como desplegar el proyecto.


### Pre-requisitos 📋

_El proyecto por lado de servidor está desarrollado con PHP y NodeJS, por lo que es necesario instalarlos de antemano._

_A los usuarios de Windows poco experimentados les recomendamos instalar la versión full de [Laragon](https://laragon.org/download/), ya que este software nos ofrece todo el stack necesario (Servidores Web, PHP, NodeJS, NPM y MySQL) para probar el software, a excepción del Broker MQTT, sin embargo, nosotros disponemos de un servidor de prueba para que puedan aprender a utilizar esta tecnología (ubicado en educate.swarm.cl en el puerto 1883)._

_El proyecto se puede probar con una NodeMCU y un sensor DHT o con los códigos de ejemplo que simulan la interacción entre la NodeMCU y el servidor, por lo que no es necesario instalar [Arduino](https://www.arduino.cc/en/Main/Software) para probarlo. De todas formas, para quienes se aventuren a probar los programas para las NodeMCU, les recomendamos [este tutorial](https://www.prometec.net/esp8266-pluggin-arduino-ide/) para instalar lo necesario que permite utilizar el Arduino IDE para programar una NodeMCU o cualquier ESP común a excepción de la ESP32._

_Aquí se encuentran enlistados los softwares necesarios para quienes deseen instalarlos por separado:_
* [Apache](https://httpd.apache.org/) o [Nginx](https://www.nginx.com/) - Servidores Web
* [PHP](https://www.php.net/downloads.php) - Lenguaje de programación de lado del servidor
* [NodeJS](https://nodejs.org/es/) - Entorno de ejecución para JavaScript de lado del servidor
* [NPM](https://www.npmjs.com/) - Gestor de paquetes de NodeJS
* [MySQL](https://www.mysql.com/) - Base de datos relacional
* [Mosquitto](https://mosquitto.org/) o utilizar el nuestro de prueba - Broker MQTT

### Instalación 🔧

_El primer paso es desplegar la base de datos._
_Si están utilizando Laragon, la base de datos se puede crear directamente desde ahí, si no, deberán crearla de forma manual._
_En la carpeta "database" se encuentra un único archivo SQL que se puede importar para crear la única tabla que utiliza el proyecto dentro de la base de datos creada por ustedes._

_En segunda instancia deben dejar el contenido de la carpeta php-pure en su carpeta pública de su Servidor Web. Si están utilizando Laragon, pueden crear un nuevo proyecto de tipo "Blank" (su nuevo proyecto se alojará en C:/laragon/www/nombre_del_proyecto)_

_Como tercer paso deben editar las líneas donde se crea la conexión con la base de datos en cada uno de los archivos .php que sea necesario y colocar los datos de su base de datos._
_Si están usando Laragon y no han cambiado los datos de usuario, la conexión quedaría de la siguiente forma:_
```
$mysqli = new mysqli('localhost', 'root', '', 'nombre_base_de_datos_que_crearon_en_el_paso_uno');
```
_Los archivos que deben modificar dentro de la carpeta "php-pure" son los siguientes:_
* monitoring-chart.php
* monitoring-latest.php
* monitoring-periodic.php
* monitoring-realtime.php
* monitoring-table.php
* periodic.php
* save-data-mqtt-devices.php
* save-data.php
* select-device.php

_El cuarto paso consiste en desplegar el software que procesa los paquetes MQTT. Para esto necesitan ingresar desde la terminal de Laragon o desde cualquier consola si es que instalaron NPM y NodeJS de forma independiente y dirigirse a la carpeta "mqtt-devices" desde la terminal e instalar las dependencias del proyecto con el siguiente comando:_
```
npm install
```
_Dentro de la misma carpeta es necesario copiar el contenido del archivo .env.example dentro de un nuevo archivo llamado .env y ahí modificar las variables para que calce con la ubicación del Broker MQTT y del servidor HTTP (pueden utilizar cualquier editor de texto para esto)._
_En el campo donde solicita el HOST deben poner la url que les creó Laragon para su proyecto, la cual debe ser algo así como "http://nombredelproyecto.test"._
_En el campo donde se solicita el MQTT_HOST pueden poner su propio Broker MQTT o "educate.swarm.cl" y dejar los demás campos intactos._
_Para finalizar con este paso se puede ejecutar la aplicación ingresando el siguiente comando (estando todavía dentro de la carpeta en la terminal):_
```
node index.js
```
_Debemos dejar esa terminal abierta para que el programa se mantenga abierto. En esta terminal podremos ver el debug cuando estén llegando paquetes al Broker MQTT_

_Como quinto y último paso debemos abrir el navegador y dirigirnos a nuestra ruta del proyecto, que como mencionamos anteriormente, debe ser algo como "http://nombredelproyecto.test", donde "nombredelproyecto" es el nombre que le asignaron en Laragon al proyecto en su creación._
_Si está todo correcto deberían ver un menú con tres botones, desde ahí ya pueden intentar navegar por la plataforma para descubrir si todo funciona e ir detectando errores._

_Pueden hacer llegar sus dudas a daniel.perez@swarm.cl y estas serán contestadas lo antes posible._
_Encontrarán en [este link](http://educate.swarm.cl/humidity-and-temperature-example/php-pure/) el proyecto funcionando. Siéntanse en toda libertad de revisar las funciones directamente desde ese link._

## Construido con 🛠️

* [PHP](https://www.php.net/manual/es/intro-whatis.php) - Lenguaje de programación interpretado ampliamente utilizado en desarrollo web
* [NodeJS](https://nodejs.org/es/) - Entorno de ejecución para JavaScript
* [Biblioteca MQTT](https://www.npmjs.com/package/mqtt) - Usado para hacer la conexión con el Broker desde el programa mqtt-devices
* [Biblioteca Dotenv](https://www.npmjs.com/package/dotenv) - Usado para manejar el archivo .env en el programa mqtt-devices y tener variables de entorno de ejecución
* [Biblioteca Axios](https://www.npmjs.com/package/axios) - Usado para realizar consultas HTTP desde el programa mqtt-devices
* [Paho](https://www.eclipse.org/paho/clients/js/) - Cliente MQTT usado por los ejemplos web de MQTT
* [Google charts](https://developers.google.com/chart) - Biblioteca JavaScript de gráficos desarrollada por Google
* [Bootstrap 4](https://getbootstrap.com/) - Framework CSS
* [Biblioteca DHT de Adafruit](https://github.com/adafruit/DHT-sensor-library) - Utilizada por los dos ejemplos de NodeMCU en su versión 1.2.0
* [Cliente MQTT para Arduino](https://github.com/knolleary/pubsubclient) - Utilizada por el ejemplo MQTT de NodeMCU
* [Pluggin ESP8266 para Arduino IDE](https://github.com/esp8266/esp8266.github.io) - Utilizada para desarrollar en la NodeMCU o ESP8266 desde el IDE de Arduino

## Autores ✒️

* **Daniel Pérez** - *Desarrollo de los ejemplos y comentarios en código*
* **Samuel Muñoz** - *Test y diagramas*
---
⌨️ con ❤️ por [Swarm Technologies](https://github.com/swarmtechnologies) 😊
