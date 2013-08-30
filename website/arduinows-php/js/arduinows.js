/* 
  File: js/arduinows.js

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

$(document).ready(function(){
  
  $("#graph_tabs").tabs();
  
  $.getJSON('api/data/current.php', function(data) {
    var items = [];
    $("#span_time").text(data["date"]);
    $("#span_temperature").text(data["temperature"]);
    $("#span_humidity").text(data["humidity"]);
    $("#span_pressure").text(data["pressure"]);
    $("#span_dew").text(data["dew"]);
  });
  
  load_temperature_data($('#temperature_timeline_select').val());
  load_pressure_data($('#pressure_timeline_select').val());
  load_humidity_data($('#humidity_timeline_select').val());
  
  $('#temperature_timeline_select').change(function() {
    load_temperature_data($(this).val());
  });
  $('#pressure_timeline_select').change(function() {
    load_pressure_data($(this).val());
  });
  $('#humidity_timeline_select').change(function() {
    load_humidity_data($(this).val());
  });
  
});

function load_temperature_data(range) {
  load_data("temperature", range, "Temperature", "Temperature (°C)", "°C", "temperature");
}

function load_pressure_data(range) {
  load_data("pressure", range, "Pressure", "Pressure (hPa)", "hPa", "pressure"); 
}

function load_humidity_data(range) {
  load_data("humidity", range, "Humidity", "Humidity (%)", "%", "humidity");
}

function load_data(type, range, title, yaxis_title, value_suffix, series_name) {
  $('#'+type+'_graph_loading_overlay').show()
  $.getJSON('api/data/'+type+'.php?range='+range, function(json_data) {
    data = eval(json_data["data"]);
    labels = new Array();
    if(false && range == "day"){
      from = eval(json_data["from"]);
      to   = eval(json_data["to"]);
      for(var i = 0; i < from.length; ++i) {
        labels.push(from[i]+":00 - "+to[i]+":00");
      }
    } else if(false && range == "week") {
      from = eval(json_data["from"]);
      to   = eval(json_data["to"]);
      date = eval(json_data["date"]);
      for(var i = 0; i < from.length; ++i) {
        labels.push(date[i] + ", " + from[i]+":00 - "+to[i]+":00");
      }
    }
    else {
      labels = eval(json_data["time"]);
    }
    //labels = eval(data["labels"]);
    $('#'+type+'_graph_loading_overlay').hide();
    $('#'+type+'_graph').highcharts({
            chart: {
                type: 'spline',
//                 marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: title,
                x: -20 //center
            },
            xAxis: {
                categories: labels
            },
            yAxis: {
                title: {
                    text: yaxis_title
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: value_suffix
            },
            series: [{
                name: series_name,
                data: data,
                showInLegend: false
            }]
        });
  });
}