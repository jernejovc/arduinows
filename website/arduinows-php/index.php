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
  Data time: <span id="span_time"></span> <br/>
  Temperature: <span id="span_temperature"></span> <br/>
  Humidity: <span id="span_humidity"></span> <br/>
  Pressure: <span id="span_pressure"></span><br/>
  Dew: <span id="span_dew"></span><br/>
  
  Choose timeline: 
  <select id="temperature_timeline_select">
    <option value="day" selected="selected">1 day (avg. per hour)</option>
    <option value="month">1 month (avg. per day)</option>
    <option value="3months">3 months (avg. per day)</option>
    <option value="1year">1 year (avg. per week)</option>
    <option value="all">All (avg. per week)</option>
  </select> 
  <div id="temperature_graph"></div>
  
  </content>
  <hr/>
  <footer>
    Powered by <a href="https://github.com/jernejovc/arduinows">Arduino Weather Station</a>
  </footer>
</body>
</html>