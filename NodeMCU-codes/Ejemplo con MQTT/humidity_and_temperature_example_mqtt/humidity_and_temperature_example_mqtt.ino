
#include <ESP8266WiFi.h>
#include <PubSubClient.h>

//Este ejemplo utiliza la versión 1.2.0 de la biblioteca DHT de Adafruit
#include "DHT.h"
#define DHTPIN D0     // Pin digital que está conectado al sensor DHT
#define DHTTYPE DHT11   // Definimos que estamos usando el DHT 11
DHT dht(DHTPIN, DHTTYPE);

// Modificar estos datos y colocar los datos de su WIFI y Broker MQTT
const char* ssid = "SSID";
const char* password = "PASSWORD";
const char* mqtt_server = "educate.swarm.cl"; //servidor de testeo, en el puerto 1883, sin seguridad y sin login

String mac;

WiFiClient espClient;
PubSubClient client(espClient);
long lastMsg = 0;
char msg[50];
int value = 0;

void reconnect() {
  // Ciclo hasta que logramos la conexión
  while (!client.connected()) {
    Serial.print("Esperando la conexión con el Broker...");
    // Se crea un ID único para el cliente MQTT, utilizamos la MAC del dispositivo para esto
    String clientId = "ESP8266Client-";
    clientId += mac;
    // Se logró la conexión
    if (client.connect(clientId.c_str())) {
      Serial.println("conectado");
    } else {
      Serial.print("Falló, rc=");
      Serial.print(client.state());
      Serial.println(" reintentando en 5 segundos");
      // Esperar 5 segundos antes de reintentar
      delay(5000);
    }
  }
}

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

  //Seteamos los datos del servidor
  client.setServer(mqtt_server, 1883);
}

void loop() {
  //Si el cliente no está conectado al Broker, reconectamos
  if (!client.connected()) {
    reconnect();
  }
  client.loop();
  //Tomamos los datos desde el sensor DHT
  float humidity = dht.readHumidity();
  float temperature = dht.readTemperature();

  //Verificamos que las lecturas hayan sido correctas
  if (isnan(humidity) || isnan(temperature)) {
    //Si falló alguna lectura, imprimimos el error en la consola y volvemos al inicio del loop
    Serial.println(F("Ha ocurrido un error en la lectura del sensor DHT"));
    return;
  }

  Serial.println("Enviando un paquete mqtt con los siguientes valores:");
  Serial.println("mac = " + mac);
  Serial.println("humidity = " + (String) humidity);
  Serial.println("temperature = " + (String) temperature);
  //Llamamos a la función que se encarga de enviar el paquete
  sendPackage(humidity, temperature);
  delay(15000);
}

void sendPackage(float humidity, float temperature){
  //Armamos el JSON que contiene los datos necesarios
  String cadena = "{\"humidity\":"+ (String) humidity  + ",\"temperature\":"+(String) temperature +"}";
  //Armamos el tópico al que irá dirigido el paquete
  String topic = "humidity_and_temperature/device/" + mac + "/tx";
  char package[255];
  char t[255];
  //Transformamos los Strings en char, ya que la función publish requiere este formato
  cadena.toCharArray(package, 255);
  topic.toCharArray(t, 255);
  //Publicamos los datos en el tópico
  client.publish(t, package);
  Serial.println("Paquete enviado");
}
