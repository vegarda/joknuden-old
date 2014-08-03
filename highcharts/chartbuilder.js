"use strict";

$(function() {
    Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });
    
    $.ajax({
        url: '/data/tempchart.php'+get,
        type: 'GET',
        async: true,
        dataType: "json",
        success: function(chartdata){
            var pointStart = chartdata.dateTime[0] * 1000;
            //var chartConfig = function(renderTo, height, yAxis, pointStart, series){
            //var yAxisSchema = function(labelUnit, opposite, min, max, minRange, tickInterval){
            //var seriesSchema = function(name, type, color, yAxisNumber, data, dashStyle, tooltipSuffix, tooltipDecimals){
            
            window['topChart'] = new Highcharts.StockChart(
                chartConfig('topChart', 250, 
                            [yAxisSchema('°C', true, null, null, 5, null),
                             yAxisSchema(' hPa', false, null, null, 5, null),
                             yAxisSchema(' mm', true, 0, null, 50, null)], pointStart, 
                            [seriesSchema('Temperature', 'spline', '#5cb85c', 0, chartdata.outTemp, null, '°C', 1),
                             seriesSchema('Pressure', 'spline', '#f0ad4e', 1, chartdata.barometer, 'dash', ' hPa', 1),
                             seriesSchema('Rain', 'spline', '#428bca', 2, chartdata.dayRain, 'Solid', ' mm', 1)]));    
        
            window['windChart'] = new Highcharts.StockChart(
                chartConfig('windChart', 250, 
                            [yAxisSchema(' m/s', true, 0, null, 5, null)], pointStart, 
                            [seriesSchema('Wind Speed', 'spline', '#428bca', 0, chartdata.windSpeed, null, ' m/s', 1),
                             seriesSchema('Wind Gust', 'spline', '#d9534f', 0, chartdata.windGust, 'Solid', ' m/s', 1)]));
            window['windDirChart'] = new Highcharts.StockChart(
                chartConfig('windDirChart', 250, 
                            [yAxisSchema('°', true, 0, 360, null, 90)], pointStart, 
                            [seriesSchema('Wind Direction', 'spline', '#428bca', 0, chartdata.windDir, 'Solid', '°', 0)]));
            
            window['barometerChart'] = new Highcharts.StockChart(
                chartConfig('barometerChart', 250, 
                            [yAxisSchema(' hPa', true, null, null, 5, null)], pointStart, 
                            [seriesSchema('Pressure', 'spline', '#428bca', 0, chartdata.barometer, 'Dash', ' hPa', 1)]));
            
            window['humidityChart'] = new Highcharts.StockChart(
                chartConfig('humidityChart', 250, 
                            [yAxisSchema('%', true, 0, 100, null, 10)], pointStart, 
                            [seriesSchema('Humidity', 'spline', '#428bca', 0, chartdata.outHumidity, null, '%', 0)]));
            
            window['rainChart'] = new Highcharts.StockChart(
                chartConfig('rainChart', 250, 
                            [yAxisSchema(' mm/hr', false, 0, null, 50, null), 
                             yAxisSchema(' mm', true, 0, null, 5, null)], pointStart, 
                            [seriesSchema('Rain Rate', 'spline', '#5bc0de', 0, chartdata.rainRate, 'Dash', ' mm/hr', 1),
                             seriesSchema('Accumulated Rain', 'spline', '#428bca', 1, chartdata.dayRain, null, ' mm', 1)]));
            
            window['tempChart'] = new Highcharts.StockChart(
                chartConfig('tempChart', 250, 
                            [yAxisSchema('°C', true, null, null, 5, null)], pointStart, 
                            [seriesSchema('Temperature', 'spline', '#428bca', 0, chartdata.outTemp, null, '°C', 1)])); 
        }
    });
    
    $.ajax({
        url: '/data/windrose.php'+get,
        type: 'GET',
        async: true,
        dataType: "json",
        success: function(windrosedata){
            window['windFreqencyChart'] = new Highcharts.Chart(
                windrosechart('windFreqencyChart', 'Wind Direction Frequency', '%', '%', 'Frequency', windrosedata[0]));
            window['windVelocityChart'] = new Highcharts.Chart(
                windrosechart('windVelocityChart', 'Wind Velocity', 'm/s', ' m/s', 'Velocity', windrosedata[1]));
            window['windVectorChart'] = new Highcharts.Chart(
                windrosechart('windVectorChart', 'Wind Vector', ' m/s', ' m/s', 'Vector', windrosedata[2]));
        }
    });
    $.ajax({
        url: '/data/windrose10.php',
        type: 'GET',
        async: true,
        dataType: "json",
        success: function(windrosedata10){
            window['windFreqencyChart10'] = new Highcharts.Chart(
                windrosechart('windFreqencyChart10', 'Wind Direction Frequency 10 Min', '%', '%', 'Frequency', windrosedata10[0]));
            window['windVelocityChart10'] = new Highcharts.Chart(
                windrosechart('windVelocityChart10', 'Wind Velocity 10 Min', 'm/s', ' m/s', 'Vector', windrosedata10[1]));
            window['windVectorChart10'] = new Highcharts.Chart(
                windrosechart('windVectorChart10', 'Wind Vector 10 Min', ' m/s', ' m/s', 'Vector', windrosedata10[2]));
        }
    });
});