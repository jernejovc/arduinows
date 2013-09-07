/* 
  File: arduino_weather_station.ino

  This is a part of ArduinoWS project.
  (c) 2013- Matej Repinc <mrepinc@gmail.com> 
  
  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Lesser General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Lesser General Public License for more details.

  You should have received a copy of the GNU Lesser General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

#include <SPI.h>
#include <Wire.h>
#include <Ethernet.h>
#include <EthernetServer.h>
#include <EthernetClient.h>

#include <BMP085.h>
#include <Sensirion.h>

#include <util.h>
#include <stdlib.h>

/**
 * If VERBOSE is defined, the level of verboseness in Serial connection is
 * increased. That means we get a lot more (useful) info in serial console.
 */
#ifndef VERBOSE
  #define VERBOSE
#endif

/**
 * Change the bottom values to what is used in your setup. 
 * In our example we are using SHT75 sensor connected to A2 and A3 pins,
 * BMP085 connected to I2C.
 */

const uint8_t sht75_dataPin  =  2;
const uint8_t sht75_clockPin =  3;

BMP085 bmp085;
Sensirion tempSensor = Sensirion(sht75_dataPin, sht75_clockPin);

/**
 * Below is a configuration for data collection server. 
 * Variable host is the name of the server where API can be found.
 *  It can be a hostname or IP address.
 * Variable api is the location of the API on the server. Note that
 *  the API location should have leading and trailing /'s.
 * Variable key is the key used to authenticate weather station against
 *  tha API. It is a unique code generated when calling api/put/station.php.
 * Variable mac is the MAC address which can be found on the bottom side of
 *  the Ethernet Shield. If you can't find it, you can use a the one that's
 *  already set or set a fictional one.
 */
char* host = "host.example.com";

String api("/api/");

String key("sha-256 value");

byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0x01 };

//Weather data
float temperature;
float temperature_bmp;
float pressure;
float humidity;
float dewpoint;
int num_cycles;

// The server is listening on port 80
EthernetServer server(80);


// Support function that emulates a soft reset of the board.
void soft_reset(){
asm volatile ("  jmp 0");
}

// Support function to get a readable uptime string.
String getUptimeString() {
  long uptime = millis();
  long seconds = (uptime / 1000L) % 60;
  long minutes = (uptime / (1000L * 1000L)) % 60;
  long hours = (uptime / (1000L * 1000L * 1000L)) % 24;
  long days = (uptime / (1000L * 1000L * 1000L)) /24;
  return String(String(days) + "d " + 
  String(hours) + ":" + String(minutes) + ":" + String(seconds));
}

/* Function that is called when we get a new client. The current implementation 
 * figures out if the client requests JSON encoded data or standard web page and 
 * then returns the data accordingly.
 */
void serve_client(EthernetClient client) {
  Serial.println("Got new client.");
  // HTTP Request ends with a blank line
  boolean currentLineIsBlank = true;
  boolean firstLine = true;
  boolean json = false;
  char line [100];
  int line_idx = 0;
  while (client.connected()) {
    if (client.available()) {
      char c = client.read();
      if(c != '\n') {
        if(line_idx < 100) {
          line[line_idx++] = c;
        }
      }
      // If you've gotten to the end of the line (received a newline
      // character) and the line is blank, the http request has ended,
      // so we can send a reply
      if (c == '\n' && currentLineIsBlank) {
        // send a standard http response header
        client.println("HTTP/1.1 200 OK");
        if(json) {
          client.println("Content-Type: application/json");
        } else {
          client.println("Content-Type: text/html");
        }
        client.println("Connection: close");
        client.println();
        
        #ifdef VERBOSE
        char buffer[20];
        String str_temp = dtostrf(temperature, 0, 2, buffer);
        String str_press = dtostrf(pressure, 0, 1, buffer);
        String str_hum = dtostrf(humidity, 0, 1, buffer);
        String str_dew = dtostrf(dewpoint, 0, 2, buffer);
        Serial.println(str_temp);
        Serial.println(str_press);
        Serial.println(str_hum);
        Serial.println(str_dew);
        #endif
        
        if(json) {
          client.print("{\"temperature\":"); client.print(temperature);
          client.print(",\"pressure\":"); client.print(pressure); 
          client.print(",\"humidity\":"); client.print(humidity); 
          client.print(",\"dew\":"); client.print(dewpoint);
          client.println("}");
          break;
        } else {
          client.println("<!DOCTYPE HTML>");
          client.println("<html><head><title>Arduino Weather Station</title></head><body>");
          client.println("<h1>Arduino Weather Station</h1>");
          client.println("<h2>Current weather data</h2>");
          client.print("Temperature: ");
          client.print(temperature);
          client.println("°C <br/>");
          client.print("Pressure: ");
          client.print(pressure);
          client.println("hPa<br/>");
          client.print("Humidity: ");
          client.print(humidity);
          client.println("%<br/>");
          client.print("Dew: ");
          client.print(dewpoint);
          client.println("°C<br/><br/><br/>");
          client.println("</body></html>");
          break;
        }
      }
      if (c == '\n') {
        // we're starting a new line in response
        currentLineIsBlank = true;
        line_idx = 0;
        // First line contains GET 
        if(firstLine) {
          char getjson[] = "GET /json ";
          boolean isjson = true;
          for(int i = 0; i < 10; ++i) {
            if(getjson[i] != line[i]) {
              isjson = false;
            }
          }
          if(isjson == true) {
            json = true;
          }
        }
        firstLine = false;
        
#ifdef VERBOSE
        Serial.println(line);
#endif
      }
      else if (c != '\r') {
        // you've gotten a character on the current line
        currentLineIsBlank = false;
      }
    }
  }
  // give the web browser some time to receive the data
  delay(10);
  // close the connection:
  client.stop();
  Serial.println("client disonnected");
}

void measure_sensor_values() {
  tempSensor.measure(&temperature, &humidity, &dewpoint);
  // Using BMP085, we must ALWAYS read both temperature AND pressure.
  temperature_bmp = bmp085.ReadTemperature();
  pressure = bmp085.ReadPressure();
    
  Serial.println("---------------------------");
  

#ifdef VERBOSE  
  Serial.print("Uptime: ");
  Serial.println(getUptimeString());
  
  Serial.print("Temperature BMP085: ");
  Serial.print(temperature_bmp); //display 2 decimal places
  Serial.println("deg C");
  Serial.print("Temperature SHT75: ");
  Serial.print(temperature); //display 2 decimal places
  Serial.println("deg C");
  Serial.print("Pressure: ");
  Serial.print(pressure); //whole number only.
  Serial.println(" hPa");
  Serial.print("Humidity: ");
  Serial.print(humidity); //whole number only.
  Serial.println("%");
  Serial.print("Dew: ");
  Serial.print(dewpoint); //whole number only.
  Serial.println("deg C");
#endif
  delay(1000);
}

/**
 * A function for sending data to collection server using setting defined at 
 * the beggining of the file. 
 */
void send_data_to_server() {
  EthernetClient client;
//  String url;
#ifdef VERBOSE
  Serial.println("Sending data to server...");
// 

/*  
  char buffer[20];
//  dtostrf(FLOAT,WIDTH,PRECISION,BUFFER)
  String str_temp(dtostrf(temperature, 0, 2, buffer));
  String str_press(dtostrf(pressure, 0, 1, buffer));
  String str_hum(dtostrf(humidity, 0, 1, buffer));
  String str_dew(dtostrf(dewpoint, 0, 2, buffer));
  Serial.println(str_temp);
  Serial.println(str_press);
  Serial.println(str_hum);
  Serial.println(str_dew);
  Serial.println(api);
  Serial.println(key);

  String url (api + "?key=" + key +
  "&temperature=" +str_temp+
  "&pressure=" + str_press+
  "&humidity=" + str_hum+
  "&dew=" + str_dew);
  Serial.println(url);
*/

  Serial.print("Attempting to connect: ");
#endif
  if(client.connect(host, 80)) {
#ifdef VERBOSE
    Serial.println("successful!");
    Serial.print("GET ");
    Serial.print(api);
    Serial.print("?key=");
    Serial.print(key);
    Serial.print("&temperature=");
    Serial.print(temperature);
    Serial.print("&pressure=");
    Serial.print(pressure);
    Serial.print("&humidity=");
    Serial.print(humidity);
    Serial.print("&dew=");
    Serial.print(dewpoint);
    Serial.println(" HTTP/1.1");
    Serial.print("Host: ");
    Serial.println(host);
    Serial.println("Connection: close");
#endif
    client.print("GET ");
    client.print(api);
    client.print("put/values.php?key=");
    client.print(key);
    client.print("&temperature=");
    client.print(temperature);
    client.print("&pressure=");
    client.print(pressure);
    client.print("&humidity=");
    client.print(humidity);
    client.print("&dew=");
    client.print(dewpoint);
    client.println(" HTTP/1.1");
    client.print("Host: ");
    client.println(host);
    client.println("Connection: close");
    client.println();
    while(client.connected()) {
      char c = client.read();
#ifdef VERBOSE
      Serial.print(c);
#endif
    }    
  }
#ifdef VERBOSE
  else {
    Serial.print("FAILURE!");
  }
#endif
  client.stop();
  delay(1000);
}

/**
 * Setup function, initialize Serial connection, sensors, Ethernet connection.
 * This also measures first sensor values and sends them to data collection server.
 */
void setup(){
  Serial.begin(9600);
  delay(100);
  
  Serial.println("Begin setup");
  Wire.begin();
  Serial.println("Calibrating BMP085.");
  bmp085.Calibration();
  Serial.println("Connecting to network.");
  if (Ethernet.begin(mac) == 0) {
    Serial.println("Failed to configure Ethernet using DHCP!");
    while(true);
  } else {
    Serial.print("Connected, IP: ");
    for(int i = 0; i < 4; ++i) {
      Serial.print(Ethernet.localIP()[i]);
      if(i != 3) {
        Serial.print(".");
      }
    }
    Serial.println();
  }
  
  delay(100);
  measure_sensor_values();
  send_data_to_server();  
}

/**
 * Main loop. Priority:
 * * Every minute, measure the sensor data,
 * * Every 10 minutes, sends the sensor data to data collection server,
 * * Check for incoming clients.
 */
void loop()
{
  long uptime = millis();
  
  // Measure data every minute
  if(((uptime / 1000) % 60) == 0) {
    measure_sensor_values();
  }
  
  //Send data every 10 minutes
  if(((uptime/1000) % (10*60)) == 0) {
    num_cycles++;
    // Reset roughly every day. 
    // This is a workaround for stability issues.
    if(num_cycles > 6*24)
      soft_reset();
    send_data_to_server();
  }
  
  // Check for incoming clients
  EthernetClient client = server.available();
  if (client) {
    serve_client(client);
  }
}
