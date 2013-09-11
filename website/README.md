ArduinoWS website and data API
==============================
Website shows current and historical weather data from ArduinoWS. Current implementation in PHP can be found in [arduinows-php](arduinows-php/) folder.
[Data API](arduinows-php/api/) is an API for getting and putting data from and in database.

Installation
============

Required software
-----------------

* Web server (Apache, Lighttpd) with PHP 5.3+ and PHP MySQL support,
* MySQL database.

**NOTE:** This manual assumes that you have your web and database server installed and configured.

Installation
------------

* Copy files in [website/arduinows-php/](website/arduinows-php/) folder to web server;
* Create database structure and configure database as described below.

Database setup
--------------
MySQL or MariaDB (theoretically) database is required.

If you don't have database created, connect to mysql server using
```bash
$ mysql -u root -p
```
and then executing
```sql
mysql> Create User user@localhost Identified By 'password';
mysql> Create Database arduinows;
mysql> Grant All On arduinows.* To user;
mysql> Flush Privileges;
```
**NOTE:** If your web server is not running on the same machine as your database server, change `user@localhost` to `user@IP Address`, e.g. `user@'192.168.0.100'`.


Login to database server and execute the statements:

```bash
$ mysql -h localhost -D arduinows -u user -p < arduinows.sql
Enter password:
$
```

Once you have set up the database, copy `api/db.config.example.php` to `api/db.config.php` and change the database settings.

Configuration
-------------
For the stations to be able to put data into database through the API, you must first issue a call to `api/put/station.php` with at least `name` and `description` HTTP GET parameter, like:
<pre>
http://myserver/arduinows-php/api/put/station.php?name=Weather Station&description=Some description
</pre>
to register a new station to the API.

The API will respond with a JSON structure with a status field, repeating back the data, and the `key` field. That field is a SHA-256 encoded random string used to identify the station to the API at every call when putting data to the database. This should be put into the Arduino sketch to allow the weather station to put the data into the database.

**Note**:

Currently the API doesn't allow users to get data from a specific weather station. That will be included in later versions of the API.
