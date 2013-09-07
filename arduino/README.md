Arduino Weather Station
=======================
This is the sketch used in Arduino weather station.  
This software knows how to:  
* Collect sensor data
* Runs a simple web server that:
  * Shows sensor data in human readable format
  * Shows sensor data in JSON format
* Sends sensor data  to a predefined data collection server

The sketch currently only measures and displays temperature, humidity, pressure and dew data.

Used components
---------------
* Arduino UNO,
* Ethernet Shield,
* BMP085 pressure sensor,
* SHT75 temperature & humidity sensor.

Deployment
----------

When compiling sketch and deploying weather station, be sure to:
* Add [BMP085 library](libraries/BMP085) to your Arduino libraries folder;
* Add [Sensirion library](http://playground.arduino.cc/Code/Sensirion) to your Arduino libraries folder;
* Change configuration data for data collection server (API);
* Have a DHCP server running on your network so that the station can configure Ethernet connection.

Access
------
To find out the weather data, use your browser to access the IP that weather station got (i. e. http://192.168.0.101). The IP address is printed out to serial console if you have VERBOSE mode enabled. If you want to acces JSON encoded data, add /json to the url (i. e. http://192.168.0.101/json)

Further changes
---------------
* You can use any other sensors, just be sure that you set sensor values to variables declared at the top of the sketch (i. e. temperature, humidity ...);
* Data collection server is not neccesary for functionality of the station, but that means you can only see current data;
* If you don't have a DHCP server on your network or want to use a static IP, change the sketch accordingly.
