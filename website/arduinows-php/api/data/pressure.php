<?php
/* 
  File: api/data/pressure.php

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

include("./../lib/data.php");
  
$err = array("status" => "fail", "description" => "");
$range = $_GET["range"];
if( $range == null) {
  $err["description"] = "Missing range!";
  echo json_encode($err);
  die(); 
}
if(RangeEnum::StrToRange($range) == RangeEnum::INVALID) {
  $err["description"] = "Invalid range!";
  echo json_encode($err);
  die();
}

echo json_encode(DataFetcher::getData(KindEnum::KindToStr(KindEnum::PRESSURE), RangeEnum::StrToRange($range)));
?>