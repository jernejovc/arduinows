<?php 
/* 
  File: index.php

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

include 'config.php';

function print_chart_select($id) {
  echo '<select id="'.$id.'">
    <option value="dayfull" >1 day (all data)</option>
    <option value="day" selected="selected">1 day (avg. per hour)</option>
    <option value="week">1 week (avg. per 6 hours)</option>
    <option value="month">1 month (avg. per day)</option>
    <option value="3months">3 months (avg. per day)</option>
    <option value="year">1 year (avg. per week)</option>
    <option value="all">All (avg. per week)</option>
  </select>';
}
?>

<html>
<head>
<title> <?php echo $SITE_NAME; ?> </title>
<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="js/jquery-1.9.1.min.js"></script>
<!-- <script src="js/jquery.ui.tabs.min.js"></script> -->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="js/arduinows.js"></script>
<script src="js/highcharts/js/highcharts.js"></script>
<script src="js/highcharts/js/modules/exporting.js"></script>
</head>

<body>

  <header>
  <h1> <?php echo $SITE_NAME; ?> </h1>
  <h2> <?php echo $LOCATION; ?> </h2>
  </header>
  <hr/>
  <content>
  <div id="div_currentdata">
  <h3 align="center">Current data</h3>
  <h5>Data time</h5>   <span id="span_time"></span>
  <h5>Temperature</h5> <span id="span_temperature"></span>°C
  <h5>Humidity</h5>    <span id="span_humidity"></span>%
  <h5>Pressure</h5>    <span id="span_pressure"></span>hPa
  <h5>Dew</h5>         <span id="span_dew"></span>°C
  </div>
  
  <div id="div_graphs">
    <div id="graph_tabs">
      <ul>
        <li><a href="#temperature_tab">Temperature</a></li>
        <li><a href="#pressure_tab">Pressure</a></li>
        <li><a href="#humidity_tab">Humidity</a></li>
      </ul>
      <div id="temperature_tab">
<!--         <h3 align="center"> Temperature </h3> -->
        Choose timeline:
        <?php print_chart_select("temperature_timeline_select"); ?>
        <div id="temperature_graph_loading_overlay" class="graph_loading_overlay">
            <img src="img/ajax-loader.gif" alt="loading..."/>
        </div>
        <div id="temperature_graph"></div>
      </div>
      <div id="pressure_tab">
        Choose timeline:
        <?php print_chart_select("pressure_timeline_select"); ?>
        <div id="pressure_graph_loading_overlay" class="graph_loading_overlay">
            <img src="img/ajax-loader.gif" alt="loading..."/>
        </div>
        <div id="pressure_graph"></div>
      </div>
      <div id="humidity_tab">
        Choose timeline: 
        <?php print_chart_select("humidity_timeline_select"); ?>
        <div id="humidity_graph_loading_overlay" class="graph_loading_overlay">
            <img src="img/ajax-loader.gif" alt="loading..."/>
        </div>
        <div id="humidity_graph"></div>
      </div>
    </div>
  </div>
  </content>
  <hr/>
  <footer>
    Powered by <a href="https://github.com/jernejovc/arduinows">Arduino Weather Station</a>
  </footer>
</body>
</html>