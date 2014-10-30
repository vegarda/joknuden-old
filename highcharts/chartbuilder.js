"use strict";

$(function() {

    var w = window.location.pathname.slice(1).split("/")[0];
    var a = window.location.pathname.slice(1).split("/")[1];

    var get = '';
    if (a){var get = '?w='+w+'&a='+a}
    else if (w){var get = '?w='+w}

    Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });
    
    // create real-time chart or archive chart?
    var raw = false;
    /*if (get == '' || w == 'today' || w == ''){
        console.log('raw');
        raw = true;
        $.ajax({
            url: '/data/rawdata.php',
            type: 'GET',
            async: true,
            dataType: "json",
            success: function(rawChartData){
                var pointStart = rawChartData.dateTime[0] * 1000;
                var pointInterval = (rawChartData.dateTime[rawChartData.dateTime.length - 1] - rawChartData.dateTime[0])/rawChartData.dateTime.length * 1000;
                console.log(pointInterval);
                window['topChart'] = new Highcharts.StockChart(
                    rawChartConfig('topChart', 300, 
                                [yAxisSchema(' mm', true, 0, null, 50, null),
                                 yAxisSchema(' hPa', false, null, null, 5, null),
                                 yAxisSchema('°C', true, null, null, 5, null)], pointStart, pointInterval,
                                [seriesSchema('Rain', 'dayRain', 'spline', '#428bca', 0, rawChartData.dayRain, 'Solid', ' mm', 1),
                                 seriesSchema('Pressure', 'barometer', 'spline', '#f0ad4e', 1, rawChartData.barometer, 'dash', ' hPa', 1),
                                 seriesSchema('Temperature', 'outTemp', 'spline', '#5cb85c', 2, rawChartData.outTemp, null, '°C', 1)]));
            }
        });
    }*/

    $.ajax({
        url: window.location.origin+'/data/archivedata.php'+get,
        type: 'GET',
        async: true,
        dataType: "json",
        success: function(chartdata){
            var pointStart = chartdata.dateTime[0] * 1000;
            var pointInterval = ((chartdata.dateTime[chartdata.dateTime.length - 1] - chartdata.dateTime[0])/chartdata.dateTime.length) * 1000;
            //var chartConfig = function(renderTo, height, yAxis, pointStart, series){
            //var yAxisSchema = function(labelUnit, opposite, min, max, minRange, tickInterval){
            //var flagSchema = function(name, seriesID, max, maxtime, min, mintime, color, unit){
            //var seriesSchema = function(name, type, color, yAxisNumber, data, dashStyle, tooltipSuffix, tooltipDecimals){

            setTimeout(function(){
                console.log('archive');
                window['topChart'] = new Highcharts.StockChart(
                    chartConfig('topChart', 300, 
                                [yAxisSchema(' mm', false, 0, null, 100, null),
                                 yAxisSchema(' mm/hr', false, 0, null, 200, null),
                                 yAxisSchema(' hPa', true, null, null, 5, null),
                                 yAxisSchema('°C', true, null, null, 5, null)], pointStart, pointInterval, 
                                [seriesSchema('Rain', 'dayRain', 'spline', '#428bca', 0, chartdata.dayRain, 1.5, {}, 'Dot', ' mm', 1),
                                 seriesSchema('Rain Rate', 'rainRate', 'spline', '#428bca', 1, chartdata.rainRate, 1.5, {}, 'Solid', ' mm/hr', 1),
                                 seriesSchema('Pressure', 'barometer', 'spline', '#f0ad4e', 2, chartdata.barometer, 1.5, {}, 'dash', ' hPa', 1),
                                 seriesSchema('Temperature', 'outTemp', 'spline', '#5cb85c', 3, chartdata.outTemp, 1.5, {}, null, '°C', 1)]));
                
                setInterval(function() {
                            $.ajax({
                                    url: window.location.origin+'/data/archive.php',
                                    type: 'GET',
                                    async: true,
                                    dataType: "json",
                                    success: function(chartdata){
                                        console.log("archive interval");
                                        var timeStamp = chartdata.dateTime;
                                        window['topChart'].series[0].addPoint([timeStamp * 1000, chartdata.dayRain], true);
                                        window['topChart'].series[1].addPoint([timeStamp * 1000, chartdata.rainRate], true);
                                        window['topChart'].series[2].addPoint([timeStamp * 1000, chartdata.barometer], true);
                                        window['topChart'].series[3].addPoint([timeStamp * 1000, chartdata.outTemp], true);
                                    }
                            });
                }, 60 * 1000);
                
                $.ajax({
                    url: window.location.origin+'/data/extreme.php'+get,
                    type: 'GET',
                    async: true,
                    dataType: "json",
                    success: function(extremedata){

                        window['topChart'].addSeries(flagSchema(
                            'Temperature', 'outTemp', extremedata.outTemp.max, extremedata.outTemp.maxtime, 
                            extremedata.outTemp.min, extremedata.outTemp.mintime, '#5cb85c', '°C'));
                        window['topChart'].addSeries(flagSchema(
                            'Pressure', 'barometer', extremedata.barometer.max, extremedata.barometer.maxtime, 
                            extremedata.barometer.min, extremedata.barometer.mintime, '#f0ad4e', ' hPa'));
                    }
                }).then(window['topChart'].redraw());
            }, 250);

            setTimeout(function(){                           
                window['windChart'] = new Highcharts.StockChart(
                    chartConfig('windChart', 250, 
                                [yAxisSchema(' m/s', true, 0, null, 5, null)], pointStart, pointInterval,
                                [seriesSchema('Wind Speed', 'windSpeed', 'spline', '#428bca', 0, chartdata.windSpeed, 1.5, {}, null, ' m/s', 1),
                                 seriesSchema('Wind Gust', 'windGust', 'spline', '#d9534f', 0, chartdata.windGust, 1.5, {}, 'Solid', ' m/s', 1)]));
            }, 1000);
            setTimeout(function(){                           
                window['windDirChart'] = new Highcharts.Chart(
                    chartConfig('windDirChart', 250, 
                                [yAxisSchema('°', true, 0, 360, null, 90)], pointStart, pointInterval,
                                [seriesSchema('Wind Direction', 'windDir', 'spline', '#428bca', 0, chartdata.windDir, 0, {enabled:true,radius:1}, 'Solid', '°', 0)]));
            }, 1250);
            setTimeout(function(){                           
                window['barometerChart'] = new Highcharts.StockChart(
                    chartConfig('barometerChart', 250, 
                                [yAxisSchema(' hPa', true, null, null, 5, null)], pointStart, pointInterval,
                                [seriesSchema('Pressure', 'barometer', 'spline', '#428bca', 0, chartdata.barometer, 1.5, {}, 'Dash', ' hPa', 1)]));
            }, 1500);
            setTimeout(function(){                           
                window['humidityChart'] = new Highcharts.StockChart(
                    chartConfig('humidityChart', 250, 
                                [yAxisSchema('%', true, 0, 100, null, 10)], pointStart, pointInterval,
                                [seriesSchema('Humidity', 'outHumidity', 'spline', '#428bca', 0, chartdata.outHumidity, 1.5, {}, null, '%', 0)]));
            }, 1750);
            setTimeout(function(){                           
                window['rainChart'] = new Highcharts.StockChart(
                    chartConfig('rainChart', 250, 
                                [yAxisSchema(' mm/hr', false, 0, null, 50, null), 
                                 yAxisSchema(' mm', true, 0, null, 5, null)], pointStart, pointInterval,
                                [seriesSchema('Rain Rate', 'rainRate', 'spline', '#5bc0de', 0, chartdata.rainRate, 1.5, {}, 'Dash', ' mm/hr', 1),
                                 seriesSchema('Accumulated Rain', 'dayRain', 'spline', '#428bca', 1, chartdata.dayRain, 1.5, {}, null, ' mm', 1)]));
            }, 2000);
            setTimeout(function(){                           
                window['tempChart'] = new Highcharts.StockChart(
                    chartConfig('tempChart', 250, 
                                [yAxisSchema('°C', true, null, null, 5, null)], pointStart, pointInterval,
                                [seriesSchema('Temperature', 'outTemp', 'spline', '#428bca', 0, chartdata.outTemp, 1.5, {}, null, '°C', 1)])); 
            }, 2250);
        }
    });
    
    $.ajax({
        url: window.location.origin+'/data/windrose.php'+get,
        type: 'GET',
        async: true,
        dataType: "json",
        success: function(windrosedata){
            window['windFreqencyChart'] = new Highcharts.Chart(
                windrosechart('windFreqencyChart', 
                              'Frequency by Direction', 
                              null, 
                              '%', 
                              '%', 
                              'Frequency', 
                              windrosedata[0]));
            
            window['windVelocityChart'] = new Highcharts.Chart(
                windrosechart('windVelocityChart', 
                              'Wind Speed', 
                              'Average wind speed by direction', 
                              ' m/s', 
                              ' m/s', 
                              'Velocity', 
                              windrosedata[1]));
            
            window['windVectorChart'] = new Highcharts.Chart(
                windrosechart('windVectorChart', 
                              'Wind Vector' ,
                              'Average wind speed from direction', 
                              ' m/s', 
                              ' m/s', 
                              'Vector', 
                              windrosedata[2]));
        }
    });
    
    setTimeout(function(){
        $.ajax({
            url: window.location.origin+'/data/windrose10.php',
            type: 'GET',
            async: true,
            dataType: "json",
            success: function(windrosedata10){
                window['windFreqencyChart10'] = new Highcharts.Chart(
                    windrosechart('windFreqencyChart10', 
                                  'Wind Direction Frequency 10 Min', 
                                  null, 
                                  '%', 
                                  '%', 
                                  'Frequency', 
                                  windrosedata10[0]));

                window['windVelocityChart10'] = new Highcharts.Chart(
                    windrosechart('windVelocityChart10', 
                                  'Wind Velocity 10 Min', 
                                  'Average wind speed by direction', 
                                  ' m/s', 
                                  ' m/s', 
                                  'Vector', 
                                  windrosedata10[1]));

                window['windVectorChart10'] = new Highcharts.Chart(
                    windrosechart('windVectorChart10', 
                                  'Wind Vector 10 Min', 
                                  'Average wind speed from direction', 
                                  ' m/s', 
                                  ' m/s', 
                                  'Vector', 
                                  windrosedata10[2]));
            }
        });
    }, 1000);
});