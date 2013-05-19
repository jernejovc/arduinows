<?php
/* 
  File: api/database.php

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

//include("./../util/basedir.php");
//$basedir = BaseDir::dir();
include("./../db.config.php");

//include("./../db.config.php");

class DB {
  private $conn = null;
  
  public function connect() {
      $this->conn = new mysqli(DBConfig::$DB_HOST,
                               DBConfig::$DB_USER,
                               DBConfig::$DB_PASS,
                               DBConfig::$DB_DATABASE);
      if($this->conn->errno > 0) {
        die("Cannot connect to database. Check your database settings or contact site administrator.");
      }
      return $this->conn;
  }
  
  public function connection() {
    return $this->conn;
  }
  
  function __destruct() {
    if( $this->conn != null) {
      $this->conn->close();
    }
  }
  
  public function getWeatherStationId($hash) {
    $stmt = $this->conn->prepare("Select `id` From `stations` Where `hash` like ?");
    $stmt->bind_param('s', $hash);
    $stmt->execute();
    $stmt->bind_result($returned);
    
    if($stmt->fetch()) {
      return intval($returned);
    }

    return -1;
  }
}

?>
