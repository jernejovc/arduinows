#ArduinoWS API

##Purpose
This is an API for gathering data and putting data in the database. It has two parts, "public" API (`data`) and "private" API (`put`).  
Returned data and responses are encoded using JSON.
##Components

###api/info.php
---
This displays info about the API.
Return format:
```json
{
  "name"         : "ArduinoWS API",
  "description"  : "Arduino Weather Station API",
  "version"      : "0.1",
  "url"          : "https://github.com/jernejovc/arduinows"
}
```

Return values:
* name: Name of the API;
* description: Short description of the API;
* version: API version;
* url: URL to the official api website.


###api/data
---
This is the public part of the API that is used to get data.
        
####api/data/current.php
Return the current conditions.
        
Return format:
```json
{
    "date"    : "1.4.2013 13:37:42",
    "temperature"    : "13",
    "pressure"    : "1024",
    "humidity"    : "75",
    "dew"    : "13"
}
```
        
Input parameters:
* fahr:
  * valid values: true
  * effect: uses °F instead of °C
        
Return values:
* date: ISO-8601 formatted date;
* temperature: °C or °F (fahr=true);
* pressure: hPa;
* humidity: %;
* dew: °C or °F (fahr=true).
        
####api/data/temperature.php
Returns the temperature values.
        
Return format: 
```json
{
  "labels" : "[label1, label2, label3, ... , labeln]",
  "data"   : "[data1, data2, data3, ... , datan]"
}
```
        
Input parameters:
* range:
  * valid values: [`dayfull`, `day`, `week`, `month`, `3months`, `year`, `all`];
  * effect: Returned temperature range.
* fahr:
  * valid values: true;
  * effect: uses °F instead of °C.

---

###api/put
---
This is the "private" part of the API used to put data from weather stations into the database.
All calls to this API must be accompanied with a key for a specific weather station (a key is issued when adding a new weather station to the database).

####api/put/data.php
Puts weather data to the database.
Return format:
```json
{
    "status"    : "ok"
}
```

Input parameters:
* key: hash key for the specified weather station;
* temperature: float value for temperature in °C
  * Valid values: `[-273.15,100.00], 2 decimal precision`
* humidity: float value for humidity in %
  * Valid values:  `[0.0, 100.0], 1 decimal precision`
* pressure: float value for pressure in hPa
  * Valid values: `[0.0, 1050.0], 1 decimal precision`
* dew: float value for dew in °C
  * Valid values: `[-273.15,100.00], 2 decimal precision` 
* fahr: Use °F instead of °C 
  * Valid values: `[true, false]`

Return values:
* status:
  * `ok`: data successfully added;
  * `fail`: error adding data;
* description: Description of error if status is `fail`

####api/put/station.php
Adds a new weather station to the database. 
Return format: 
```json
{
    "status"    : "ok",
    "hash"    : "md5 hash",
    "error"    : "error"
}
```

Input parameters:
* name: Name of the weather station (must be unique);
* description: Description of the weather station;
* location: Location of the weather station (optional).

Return values:
* status: 
  * `ok`: Weather station succesfully added;
  * `fail`: Weather station was not succesfully added, description in field error
* hash: hash key of the added station (used for putting data) if status is `ok`;
* error: if `status` is `fail`, description of the error.

---