#!/usr/bin/env node 
var mqtt    = require('mqtt'); 
const dotenv = require('dotenv'); 
var axios = require('axios'); 
dotenv.config(); 
axios.defaults.headers.common['Accept'] = "application/json"; 
 
/* 
*   MQTT THINGS 
*/ 
var mqttErrors = 0; 
var mqttErrorsLimit = 20; 
var client  = mqtt.connect(process.env.MQTT_PROTOCOL+"://" + process.env.MQTT_HOST, { 
    host: process.env.MQTT_HOST, 
    clientId: process.env.MQTT_CLIENT_ID, 
    port: process.env.MQTT_PORT, 
    clean: process.env.MQTT_CLEAN 
}); 
console.log("connected flag  " + client.connected); 
//handle connections 
client.on("connect",function(){ 
    console.log("connected  "+ client.connected); 
    mqttErrors = 0; 
}); 
//handle errors 
client.on("error",function(error){ 
    console.log("Can't connect" + error); 
}); 
 
client.on('message',function(topic, message, packet){ 
    if(topic.indexOf("humidity_and_temperature/device/") != -1){ 
        //Ha llegado un status 
        var mac = topic.replace("humidity_and_temperature/device/",""); 
        mac = mac.replace("/tx",""); 
        saveStatus(message, mac); 
    } 
}); 
client.subscribe("humidity_and_temperature/device/+/tx",{qos:0}); //single topic 
 
function saveStatus(message, mac){ 
  var mqttPackage = JSON.parse(message); 
  axios.post(process.env.HOST + 'save-data-mqtt-devices.php',{ 
    'mac': mac, 
    'temperature': mqttPackage.temperature, 
    'humidity': mqttPackage.humidity
  } 
  ).then(function (response) { 
      if(response.data.status == "OK"){ 
        console.log("Status subido correctamente"); 
      }else{ 
          console.log(response.data.message); 
      } 
  }).catch(function (error) { 
      console.log(error); 
  }); 
}