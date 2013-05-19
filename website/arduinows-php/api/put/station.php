<?php
/* 
  File: api/put/station.php

  This is a part of ArduinoWS project.
  (c) 2013- Matej Repinc <mrepinc@gmail.com> 
  
  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

include("../database.php");
include("../util/random.php");

$name = $_GET["name"];
$description = $_GET["description"];
$location = $_GET["location"];

$err = array("status" => "fail",
             "error" => "");
$has_errors = false;

//Sanity checks
if($name == null) {
  $err["error"] = $err["error"] . "Missing name! ";
  $has_errors = true;
}
if($description == null) {
  $err["error"] = $err["error"] . "Missing description! ";
  $has_errors = true;
}

//If we have errors display the error json
if($has_errors) {
  echo json_encode($err);
}
//else add the station to the database
else {

$md5sum = md5(Random::string());
$arr = array("status" => "ok",
             "hash" => $md5sum);

$database = new DB();
$conn = $database->connect();

$station_id = $database->getWeatherStationId("20d76586c38e13d7b179a342b9c54bbb");
echo "Station id:{$station_id} <br>";
echo json_encode($arr);
}


?>