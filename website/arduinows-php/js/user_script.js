$(document).ready(function(){
  $.getJSON('api/getcurrentdata.php', function(data) {
    var items = [];
    $("#span_time").text(data["date"]);
    $("#span_temperature").text(data["temperature"]);
    $("#span_humidity").text(data["humidity"]);
    $("#span_pressure").text(data["pressure"]);
    $("#span_dew").text(data["dew"]);
  });
  
  load_temperature_data("1day");
  
  $('#temperature_timeline_select').change(function() {
    load_temperature_data($(this).val());
  });
  
});

function load_temperature_data(range) {
  var valid = false;
  var valid_choices = ["1day", "month", "3months", "1year", "all"];
  for(var i = 0; i < valid_choices.length; i++) {
    if(range == valid_choices[i]) {
      valid = true;
    }
  }
  if(!valid) {
    return;
  }
  $.getJSON('api/gettodaysweatherdata.php?range='+range, function(data) {
    tempdata = eval(data["data"]);
    labels = eval(data["labels"]);
    //alert(tempdata);
    //alert(labels);
    $('#temperature_graph').highcharts({
            chart: {
                type: 'line',
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Temperature',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: labels
            },
            yAxis: {
                title: {
                    text: 'Temperature (°C)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '°C'
            },
            series: [{
                name: 'Temperature',
                data: tempdata,
		showInLegend: false
            }]
        });
  });
}