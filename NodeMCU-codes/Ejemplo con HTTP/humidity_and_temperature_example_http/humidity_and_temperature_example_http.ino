//Bibliotecas necesarias
#include <ESP8266WiFi.h>
#include <WiFiClient.h> 
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>

//Este ejemplo utiliza la versión 1.2.0 de la biblioteca DHT de Adafruit
#include "DHT.h"
#define DHTPIN D0     // Pin digital que está conectado al sensor DHT
#define DHTTYPE DHT11   // Definimos que estamos usando el DHT 11
DHT dht(DHTPIN, DHTTYPE);

const char *ssid = "SSID";
const char *password = "PASSWORD";
 
const char *host = "host.dominio"; //también sirve la IP

String mac;
void setup() {
  Serial.begin(115200);
  delay(500);
  //Obteniendo la MAC de la NodeMCU en formato FF:FF:FF:FF:FF:FF
  mac = WiFi.macAddress();

  //Iniciamos el objeto dht
  dht.begin();
 
  Serial.println();
  Serial.print("MAC: ");
  
  //Borrando los puntos
  mac.replace(":", "");
  Serial.println(mac);
  WiFi.mode(WIFI_OFF);        //Previniendo errores de conexión
  delay(1000);
  WiFi.mode(WIFI_STA);        //Con esta línea escondemos la red wifi que genera la NodeMCU y la dejamos en modo cliente únicamente
  
  WiFi.begin(ssid, password);     //Conectando al wifi con los datos ingresados al principio del código
  Serial.println("");
 
  Serial.print("Conectando");
  // Esperando mientras se conecta al wifi
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
 
  //Si la conexión fue exitosa, mostramos el SSID y la IP
  Serial.println("");
  Serial.print("Conectado a ");
  Serial.println(ssid);
  Serial.print("Dirección IP: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  HTTPClient http;    //Se declara un objeto de la clase HTTPClient
  //Se capturan los datos desde el sensor DHT
  float humidity = dht.readHumidity();
  float temperature = dht.readTemperature();

  //Verificamos que las lecturas hayan sido correctas
  if (isnan(humidity) || isnan(temperature)) {
    //Si falló alguna lectura, imprimimos el error en la consola y volvemos al inicio del loop
    Serial.println(F("Ha ocurrido un error en la lectura del sensor DHT"));
    return;
  }

  //Se inicializa variable que contiene las variables a transmitir
  String postData = "mac=" + mac + "&humidity=" +  humidity + "&temperature=" +  temperature;

  Serial.println("Enviando una request HTTP de tipo POST con los siguientes valores:");
  Serial.println("mac = " + mac);
  Serial.println("humidity = " + (String) humidity);
  Serial.println("temperature = " + (String) temperature);
  
  http.begin("http://"+(String)host+"/save-data.php");              //Se especifica la ruta de la consulta
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");    //Se especifica en la cabecera el tipo de contenido de la consulta

  int httpCode = http.POST(postData);   //Se envía la consulta
  String payload = http.getString();    //Se obtiene la respuesta en una cadena
 
  Serial.println("Código HTTP de la respuesta: " + (String) httpCode);   //Se imprime en consola el código http de la respuesta
  Serial.println("Respuesta: " + payload);    //Se imprime en consola el payload de la respuesta
 
  http.end();  //Se cierra la conexión HTTP
  
  delay(30000);  //Se esperan 30 segundos antes de volver al comienzo del loop

}
