<?php
/* 
  File: api/data/current.php

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


include("./../lib/database.php");
include("./../util/converter.php");

$database = new DB;

$database->connect();
$conn = $database->connection();
if($conn == null)
  die("No database connection!");

$stmt = $conn->prepare("Select `timestamp`, `temperature`, `pressure`, `humidity`, `dew` From `weather_data` Order By `timestamp` Desc Limit 0,1");
$stmt->execute();
$stmt->bind_result($timestamp, $temperature, $pressure, $humidity, $dew);
$stmt->fetch();

if($_GET["fahr"] == "true") {
  $temperature = Converter::CtoF($temperature);
  $dew = Converter::CtoF($dew);
}

$temperature = Converter::StrTo1DigitFloat($temperature);
$pressure    = Converter::StrTo1DigitFloat($pressure);
$humidity    = Converter::StrTo1DigitFloat($humidity);
$dew         = Converter::StrTo1DigitFloat($dew);

$arr = array("date" => $timestamp, 
             "temperature" => $temperature,
             "pressure" => $pressure,
             "humidity" => $humidity,
             "dew" => $dew);


echo json_encode($arr);  
?>