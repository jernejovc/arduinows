<?php
/* 
  File: api/data/temperature.php

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

include("database.php");
include("./../util/converter.php");

class RangeEnum {
  const INVALID     = -1;
  const DAY         = 0;
  const DAYFULL     = 1;
  const WEEK        = 2;
  const MONTH       = 3;
  const THREEMONTHS = 4;
  const YEAR        = 5;
  const ALL         = 6;
  
  public static function RangeToStr($range) {
    switch($range) {
      case 0:
        return "day";
      case 1:
        return "dayfull";
      case 2:
        return "week";
      case 3:
        return "month";
      case 4:
        return "3months";
      case 5:
        return "year";
      case 6:
        return "all";
      default:
        return "invalid";
    }
  }
  
  public static function StrToRange($str) {
    if($str == "day")
      return self::DAY;
    elseif($str == "dayfull")
      return self::DAYFULL;
    elseif($str == "week")
      return self::WEEK;
    elseif($str == "month")
      return self::MONTH;
    elseif($str == "3months")
      return self::THREEMONTHS;
    elseif($str == "year")
      return self::YEAR;
    elseif($str == "all")
      return self::ALL;
    return self::INVALID;
  }
}

class KindEnum {
  const INVALID = -1;
  const TEMPERATURE = 0;
  const HUMIDITY = 1;
  const PRESSURE = 2;
  
  static function KindToStr($kind) {
    switch($kind) {
      case 0:
        return "temperature";
      case 1:
        return "humidity";
      case 2:
        return "pressure";
      default:
        return "invalid";
    }
  }
   
  static function StrToKind($str) {
    if($str == "temperature")
      return self::TEMPERATURE;
    elseif($str == "pressure")
      return self::PRESSURE;
    elseif($str == "humidity")
      return self::HUMIDITY;
    return self::INVALID;
  }
  
}

class DataFetcher {
/** <Returns data for temperature, humidity and pressure for specified range.>
  * @param $p_kind: What kind of data to return(@see KindEnum::KindToStr)
  * @param $p_range: The range of data to be returned (@see RangeEnum::StrToRange)
  * @return Array with two arrays, "data" and "labels", labels[i] represents label 
  * for data[i].
  */
  public static function getData($kind, $range) {
    $database = new DB;
    $database->connect();
    $conn = $database->connection();
    
    if($conn == null) {
      die("No database connection!");
    }
    
    $result = null;
    $arr = array("labels" => array(),
                 "data" => array());
    
    switch($range) {
      case RangeEnum::DAY: {
        $sql = sprintf("SELECT HOUR( timestamp ) AS hour , AVG( %s ) as avg_%s
                          FROM `weather_data`
                          WHERE UNIX_TIMESTAMP( SYSDATE( ) ) - UNIX_TIMESTAMP( timestamp ) <86400
                          GROUP BY hour
                          ORDER BY timestamp", $kind, $kind);
        $result = $conn->query($sql);
  
        while($row = $result->fetch_assoc()) {
          $label = sprintf("%d:00 - %d:00", $row["hour"], $row["hour"] +1);
          array_push($arr["labels"], $label);
          array_push($arr["data"], Converter::StrTo1DigitFloat($row[sprintf("avg_%s", $kind)]));
        }
        break;
      }
      case RangeEnum::DAYFULL: {
        $sql = sprintf("SELECT TIME( timestamp ) AS time , %s
                         FROM `weather_data`
                         WHERE UNIX_TIMESTAMP( SYSDATE( ) ) - UNIX_TIMESTAMP( timestamp ) <86400
                         ORDER BY timestamp", $kind);
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
          array_push($arr["labels"], $row["time"]);
          array_push($arr["data"], Converter::StrTo1DigitFloat($row[$kind]));
        }
        break;
      }
      case RangeEnum::WEEK: {
        break;
      }
      case RangeEnum::MONTH: {
        $sql = sprintf("SELECT DAY( timestamp ) AS day, MONTH(timestamp) as month, AVG(%s) as avg_%s
                         FROM `weather_data`
                         WHERE UNIX_TIMESTAMP( SYSDATE( ) ) - UNIX_TIMESTAMP( timestamp ) < 86400 * 31
                         GROUP BY day
                         ORDER BY day", $kind, $kind);
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
          $label = sprintf("%d.%d", $row["day"], $row["month"]);
          array_push($arr["labels"], $label);
          array_push($arr["data"], Converter::StrTo1DigitFloat($row[sprintf("avg_%s", $kind)]));
        }
        break;
      }
      case RangeEnum::THREEMONTHS: {
        $sql = sprintf("SELECT DAY( timestamp ) AS day, MONTH(timestamp) as month, AVG(%s) as avg_%s
                         FROM `weather_data`
                         WHERE UNIX_TIMESTAMP( SYSDATE( ) ) - UNIX_TIMESTAMP( timestamp ) < 86400 * 90
                         GROUP BY day
                         ORDER BY day", $kind, $kind);
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
          $label = sprintf("%d.%d", $row["day"], $row["month"]);
          array_push($arr["labels"], $label);
          array_push($arr["data"], Converter::StrTo1DigitFloat($row[sprintf("avg_%s", $kind)]));
        }
        break;
      }
      case RangeEnum::YEAR: {
        $sql = sprintf("SELECT WEEK( timestamp ) AS week, YEAR(timestamp) as year, AVG(%s) as avg_%s
                         FROM `weather_data`
                         WHERE UNIX_TIMESTAMP( SYSDATE( ) ) - UNIX_TIMESTAMP( timestamp ) < 86400 * 365
                         GROUP BY week
                         ORDER BY week", $kind, $kind);
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
          $label = sprintf("%d/%d", $row["week"], $row["year"]);
          array_push($arr["labels"], $label);
          array_push($arr["data"], Converter::StrTo1DigitFloat($row[sprintf("avg_%s", $kind)]));
        }
        break;
      }
      case RangeEnum::ALL: {
        $sql = sprintf("SELECT WEEK( timestamp ) AS week, YEAR(timestamp) as year, AVG(%s) as avg_%s
                         FROM `weather_data`
                         GROUP BY week
                         ORDER BY week", $kind, $kind);
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
          $label = sprintf("%d/%d", $row["week"], $row["year"]);
          array_push($arr["labels"], $label);
          array_push($arr["data"], Converter::StrTo1DigitFloat($row[sprintf("avg_%s", $kind)]));
        }
        break;
      }
    }
    return $arr;
  }
}


?>