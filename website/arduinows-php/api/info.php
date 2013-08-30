<?php
/* 
  File: api/info.php

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

$info = array("name" => "ArduinoWS API",
              "description" => "Arduino Weather Station API",
              "version" => "0.1.0",
	      "version_major" => "0",
              "version_minor" => "1",
              "version_patch" => "0",
              "url" => "https://github.com/jernejovc/arduinows/");
          
echo json_encode($info);
?>
