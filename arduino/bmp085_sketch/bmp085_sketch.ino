//Arduino 1.0+ Only
/*Based largely on code by  Jim Lindblom
 
 Get pressure, altitude, and temperature from the BMP085.
 Serial.print it out at 9600 baud to serial monitor.
 */

#include <Wire.h>
#include <BMP085.h>
#include <stdarg.h> //printf
BMP085 bmp085;

void setup(){
  Serial.begin(9600);
  Wire.begin();
  
  bmp085.Calibration();
}

void loop()
{
  //char json [] PROGMEM = "{'date':%s,'temperature':%d,'pressure':%d,'humidity':%d,'dew':%d}";

  Serial.print("Temperature: ");
  Serial.print(bmp085.ReadTemperature()); //display 2 decimal places
  Serial.println("deg C");

  Serial.print("Pressure: ");
  Serial.print(bmp085.ReadPressure()); //whole number only.
  Serial.println(" hPa");

  delay(5000); //wait a second and get values again.
}


