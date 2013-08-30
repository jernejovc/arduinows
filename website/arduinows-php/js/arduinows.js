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
  $('#temperature_graph_loading_overlay').show()
  $.getJSON('api/data/temperature.php?range='+range, function(data) {
    tempdata = eval(data["data"]);
    labels = eval(data["labels"]);
    $('#temperature_graph_loading_overlay').hide();
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

function load_pressure_data(range) {
  $('#pressure_graph_loading_overlay').show()
  $.getJSON('api/data/pressure.php?range='+range, function(data) {
    data = eval(data["data"]);
    labels = eval(data["labels"]);
    $('#pressure_graph_loading_overlay').hide();
    $('#pressure_graph').highcharts({
            chart: {
                type: 'line',
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Pressure',
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
                    text: 'Pressure (hPa)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: 'hPa'
            },
            series: [{
                name: 'pressure',
                data: data,
                showInLegend: false
            }]
        });
  });
}

function load_humidity_data(range) {
  $('#humidity_graph_loading_overlay').show()
  $.getJSON('api/data/humidity.php?range='+range, function(data) {
    data = eval(data["data"]);
    labels = eval(data["labels"]);
    $('#humidity_graph_loading_overlay').hide();
    $('#humidity_graph').highcharts({
            chart: {
                type: 'line',
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Humidity',
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
                    text: 'Humidity (%)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '%'
            },
            series: [{
                name: 'humidity',
                data: data,
                showInLegend: false
            }]
        });
  });
}