<?php
/* 
  File: api/put/values.php

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

include("../lib/database.php");

$key   = $_GET["key"];
$temp  = $_GET["temperature"];
$hum   = $_GET["humidity"];
$press = $_GET["pressure"];
$dew   = $_GET["dew"];

$err = array("status" => "fail",
             "error" => "");
$has_errors = false;

$database = new DB();
$conn = $database->connect();

//Sanity checks
if($key == null) {
  $err["error"] = $err["error"] . "Missing station key! ";
  $has_errors = true;
}
if($key != null && $database->getWeatherStationId($key) == -1) {
  $err["error"] = $err["error"] . "Invalid station key!";
  $has_errors = true;
}
if($temp == null) {
  $err["error"] = $err["error"] . "Missing temperature! ";
  $has_errors = true;
}
if($hum == null) {
  $err["error"] = $err["error"] . "Missing humidity! ";
  $has_errors = true;
}
if($press == null) {
  $err["error"] = $err["error"] . "Missing pressure! ";
  $has_errors = true;
}
if($dew == null) {
  $err["error"] = $err["error"] . "Missing dew! ";
  $has_errors = true;
}

//If we have errors display the error json
if($has_errors) {
  echo json_encode($err);
}
//else add values to the database
else {


$stmt = $conn->prepare("Insert Into weather_data(station, temperature, humidity, pressure, dew) VALUES(?,?,?,?,?)");
$stmt->bind_param("sdddd", $key, $temp, $hum, $press, $dew);
$stmt->execute();

$arr = array("status" => "ok");
echo json_encode($arr);

}

?>