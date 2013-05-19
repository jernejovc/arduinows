<?php include 'config.php';
?>

<html>
<head>
<title> <?php echo $SITE_NAME; ?> </title>
<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/user_script.js"></script>
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
    <h3 align="center"> Temperature </h3>
    Choose timeline: 
    <select id="temperature_timeline_select">
      <option value="dayfull" >1 day (all data)</option>
      <option value="day" selected="selected">1 day (avg. per hour)</option>
      <option value="week">1 week (avg. per 6 hours)</option>
      <option value="month">1 month (avg. per day)</option>
      <option value="3months">3 months (avg. per day)</option>
      <option value="year">1 year (avg. per week)</option>
      <option value="all">All (avg. per week)</option>
    </select> 
    <div id="temperature_graph_loading_overlay" class="graph_loading_overlay">
        <img src="img/ajax-loader.gif" alt="loading..."/>
    </div>
    <div id="temperature_graph"></div>
    
    <h3 align="center"> Pressure </h3>
    Choose timeline: 
    <select id="pressure_timeline_select">
      <option value="dayfull" >1 day (all data)</option>
      <option value="day" selected="selected">1 day (avg. per hour)</option>
      <option value="week">1 week (avg. per 6 hours)</option>
      <option value="month">1 month (avg. per day)</option>
      <option value="3months">3 months (avg. per day)</option>
      <option value="year">1 year (avg. per week)</option>
      <option value="all">All (avg. per week)</option>
    </select> 
    <div id="pressure_graph_loading_overlay" class="graph_loading_overlay">
        <img src="img/ajax-loader.gif" alt="loading..."/>
    </div>
    <div id="pressure_graph"></div>
    
    <h3 align="center"> Humidity </h3>
    Choose timeline: 
    <select id="humidity_timeline_select">
      <option value="dayfull" >1 day (all data)</option>
      <option value="day" selected="selected">1 day (avg. per hour)</option>
      <option value="week">1 week (avg. per 6 hours)</option>
      <option value="month">1 month (avg. per day)</option>
      <option value="3months">3 months (avg. per day)</option>
      <option value="year">1 year (avg. per week)</option>
      <option value="all">All (avg. per week)</option>
    </select> 
    <div id="humidity_graph_loading_overlay" class="graph_loading_overlay">
        <img src="img/ajax-loader.gif" alt="loading..."/>
    </div>
    <div id="humidity_graph"></div>
    
  </div>
  </content>
  <hr/>
  <footer>
    Powered by <a href="https://github.com/jernejovc/arduinows">Arduino Weather Station</a>
  </footer>
</body>
</html>