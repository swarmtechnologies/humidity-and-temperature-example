# humidity-and-temperature-example

_Este proyecto es un ejemplo de uso de HTTP y MQTT para enviar y recibir paquetes con una aplicación IoT que consiste en transmitir temperatura y humedad desde una NodeMCU utilizando un sensor DHT._

## Comenzando 🚀

_Para obtener una copia de este proyecto sólo es necesario descargarlo o clonarlo directamente desde GitHub._

Mira **Instalación** para conocer como desplegar el proyecto.


### Pre-requisitos 📋

_El proyecto por lado de servidor está desarrollado con PHP y NodeJS, por lo que es necesario instalarlos de antemano._

_A los usuarios de Windows poco experimentados les recomendamos instalar la versión full de [Laragon](https://laragon.org/download/), ya que este software nos ofrece todo el stack necesario (servidor web, PHP y NodeJS) para probar el software, a excepción del Broker MQTT, sin embargo, nosotros disponemos de un servidor de prueba para que puedan aprender a utilizar esta tecnología._

_El proyecto se puede probar con una NodeMCU y un sensor DHT o con los códigos de ejemplo que simulan la interacción entre la NodeMCU y el servidor, por lo que no es necesario instalar [Arduino](https://www.arduino.cc/en/Main/Software) para probarlo. De todas formas, para quienes se aventuren a probar los programas para las NodeMCU, les recomendamos [este tutorial](https://www.prometec.net/esp8266-pluggin-arduino-ide/) para instalar lo necesario que permite utilizar el Arduino IDE para programar una NodeMCU o cualquier ESP común._



### Instalación 🔧

_El contenido de la carpeta php-pure son sólo códigos escritos en PHP, algunos de ellos realizan consultas a la base de datos, por lo que es necesario enlazar correctamente estas conexiones a la base de datos MySQL que es posible desplegar directamente desde Laragon si es que lo estás usando._

_El despliegue de la base de datos se puede realizar desde Laragon. En la carpeta "database" se encuentra el archivo SQL que despliega la única tabla necesaria para el proyecto._

_Para desplegar la aplicación que se encarga de procesar los paquetes MQTT es necesario ingresar desde la consola de Laragon (desde ahí está disponible el comando NPM), dirigirse hasta la carpeta "mqtt-devices" dentro del proyecto y ejecutar el siguiente comando desde ahí para instalar las dependencias._

```
npm install
```

_Dentro de la misma carpeta es necesario copiar el contenido del archivo .env.example dentro de un nuevo archivo llamado .env y ahí modificar las variables para que calce con la ubicación del Broker MQTT y del servidor HTTP_

_Para finalizar se puede ejecutar la aplicación ingresando el siguiente comando:_

```
node index.js
```

## Construido con 🛠️


* [PHP](https://www.php.net/manual/es/intro-whatis.php) - Lenguaje de programación interpretado ampliamente utilizado en desarrollo web
* [NodeJS](https://nodejs.org/es/) - Entorno de ejecución para JavaScript
* [Biblioteca MQTT](https://www.npmjs.com/package/mqtt) - Usado para hacer la conexión con el Broker desde el programa mqtt-devices
* [Biblioteca Dotenv](https://www.npmjs.com/package/dotenv) - Usado para manejar el archivo .env en el programa mqtt-devices y tener variables de entorno de ejecución
* [Biblioteca Axios](https://www.npmjs.com/package/axios) - Usado para realizar consultas HTTP desde el programa mqtt-devices
* [Paho](https://www.eclipse.org/paho/clients/js/) - Cliente MQTT usado por los ejemplos web de MQTT
* [Biblioteca DHT de Adafruit](https://github.com/adafruit/DHT-sensor-library) - Utilizada por los dos ejemplos de NodeMCU en su versión 1.2.0
* [Cliente MQTT para Arduino](https://github.com/knolleary/pubsubclient) - Utilizada por el ejemplo MQTT de NodeMCU
* [Pluggin ESP8266 para Arduino IDE](https://github.com/esp8266/esp8266.github.io) - Utilizada para desarrollar en la NodeMCU o ESP8266 desde el IDE de Arduino

## Autores ✒️

* **Daniel Pérez** - *Desarrollo de los ejemplos y comentarios en código*
* **Samuel Muñoz** - *Test*
---
⌨️ con ❤️ por [Swarm Technologies](https://github.com/swarmtechnologies) 😊
