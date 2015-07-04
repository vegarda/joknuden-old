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

	/*
	 * Topchart and lower charts
	 */
    $.ajax({
        url: window.location.origin+'/data/archivedata.php'+get,
        type: 'GET',
        async: true,
        dataType: "json",
        success: function(chartdata){
            var pointStart = chartdata.dateTime[0] * 1000;
            var pointEnd = chartdata.dateTime[chartdata.dateTime.length -1] * 1000;
            var pointInterval = ((chartdata.dateTime[chartdata.dateTime.length - 1] - chartdata.dateTime[0])/chartdata.dateTime.length) * 1000;

			/*
			 * Top chart
			 */
            setTimeout(function(){
                console.log('archive');
                //window['topChart'] = new Highcharts.Chart(
                window['topChart'] = new Highcharts.Chart(
                    chartConfig('topChart', 500, 
                                [yAxisSchema(' mm', false, 0, null, 100, null),
                                 yAxisSchema(' mm/hr', false, 0, null, 200, null),
                                 yAxisSchema(' hPa', true, null, null, 5, null),
                                 yAxisSchema('°C', true, null, null, 5, null)], pointStart, pointInterval, 
                                [seriesSchema('Rain', 'dayRain', 'spline', '#656565', '#656565', 0, chartdata.dayRain, 1.5, {enabled: false}, 'Dot', ' mm', 1),
                                 seriesSchema('Rain Rate', 'rainRate', 'spline', '#989898', '#989898', 1, chartdata.rainRate, 1.5, {enabled: false}, 'Solid', ' mm/hr', 1),
                                 seriesSchema('Pressure', 'barometer', 'spline', '#85219a', '#85219a	', 2, chartdata.barometer, 1.5, {enabled: false}, 'dash', ' hPa', 1),
                                 seriesSchema('Temperature', 'outTemp', 'spline', '#fa3e3e', '#596FF0', 3, chartdata.outTemp, 1.5, {enabled: false}, null, '°C', 1)]));
                
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
                
				/*
				 * Extremevalues for top chart
				 */
				/*
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
				*/

            }, 250);
				

			/*
			 * Lower wind chart
			 */
            setTimeout(function(){                           
                window['windChart'] = new Highcharts.Chart(
                    chartConfig('windChart', 400, 
                                [yAxisSchema(' m/s', true, 0, null, 5, null)], pointStart, pointInterval,
                                [seriesSchema('Wind Speed', 'windSpeed', 'spline', '#596FF0', '#596FF0', 0, chartdata.windSpeed, 1.5, {enabled: false}, null, ' m/s', 1),
                                 seriesSchema('Wind Gust', 'windGust', 'spline', '#d9534f', '#d9534f', 0, chartdata.windGust, 1.5, {enabled: false}, 'Solid', ' m/s', 1)]));
								 
	
				window['windChart'].yAxis[0].addPlotBand({
					// Stille
					from: 0.0,
					to: 0.0,
					color: 'rgba(68, 170, 213, 0.1)',
					label: {
						text: 'Stille',
						style: {
							color: '#606060'
						}
					}
				});
				
				window['windChart'].yAxis[0].addPlotBand({
					// Flau vind
					from: 0.2,
					to: 1.5,
					color: 'rgba(0, 0, 0, 0)',
					label: {
						text: 'Flau vind',
						style: {
							color: '#606060'
						}
					}
				});

				window['windChart'].yAxis[0].addPlotBand({
					// Svak vind
					from: 1.5,
					to: 3.3,
					color: 'rgba(68, 170, 213, 0.1)',
					label: {
						text: 'Svak vind',
						style: {
							color: '#606060'
						}
					}
				});	

				
				window['windChart'].yAxis[0].addPlotBand({
					// Lett bris
					from: 3.3,
					to: 5.4,
					color: 'rgba(0, 0, 0, 0)',
					label: {
						text: 'Lett bris',
						style: {
							color: '#606060'
						}
					}
				});

				window['windChart'].yAxis[0].addPlotBand({
					// Laber bris
					from: 5.4,
					to: 7.9,
					color: 'rgba(68, 170, 213, 0.1)',
					label: {
						text: 'Laber bris',
						style: {
							color: '#606060'
						}
					}
				});	

				
				window['windChart'].yAxis[0].addPlotBand({
					// Frisk bris
					from: 7.9,
					to: 10.7,
					color: 'rgba(0, 0, 0, 0)',
					label: {
						text: 'Frisk bris',
						style: {
							color: '#606060'
						}
					}
				});

				window['windChart'].yAxis[0].addPlotBand({
					// Liten kuling
					from: 10.7,
					to: 13.8,
					color: 'rgba(68, 170, 213, 0.1)',
					label: {
						text: 'Liten kuling',
						style: {
							color: '#606060'
						}
					}
				});	

				
				window['windChart'].yAxis[0].addPlotBand({
					// Stiv kuling
					from: 13.8,
					to: 17.1,
					color: 'rgba(0, 0, 0, 0)',
					label: {
						text: 'Stiv kuling',
						style: {
							color: '#606060'
						}
					}
				});

				window['windChart'].yAxis[0].addPlotBand({
					// Sterk kuling
					from: 17.1,
					to: 20.7,
					color: 'rgba(68, 170, 213, 0.1)',
					label: {
						text: 'Sterk kuling',
						style: {
							color: '#606060'
						}
					}
				});	

				
				window['windChart'].yAxis[0].addPlotBand({
					// Liten storm
					from: 20.7,
					to: 24.4,
					color: 'rgba(0, 0, 0, 0)',
					label: {
						text: 'Liten storm',
						style: {
							color: '#606060'
						}
					}
				});

				window['windChart'].yAxis[0].addPlotBand({
					// Full storm
					from: 24.4,
					to: 28.4,
					color: 'rgba(68, 170, 213, 0.1)',
					label: {
						text: 'Full storm',
						style: {
							color: '#606060'
						}
					}
				});					

				
				window['windChart'].yAxis[0].addPlotBand({
					// Sterk storm
					from: 28.4,
					to: 32.6,
					color: 'rgba(0, 0, 0, 0)',
					label: {
						text: 'Sterk storm',
						style: {
							color: '#606060'
						}
					}
				});

				window['windChart'].yAxis[0].addPlotBand({
					// Orkan
					from: 32.6,
					to: 40,
					color: 'rgba(68, 170, 213, 0.1)',
					label: {
						text: 'Orkan',
						style: {
							color: '#606060'
						}
					}
				});					
								
				
				
			
			
            }, 1000);

			/*
			 * Lower wind direction chart
			 */
            setTimeout(function(){                           
                window['windDirChart'] = new Highcharts.Chart(
                    chartConfig('windDirChart', 400, 
                                [yAxisSchema('°', true, 0, 360, null, 90)], pointStart, pointInterval,
                                [seriesSchema('Wind Direction', 'windDir', 'spline', '#596FF0', '#596FF0', 0, chartdata.windDir, 0, {enabled:true,radius:1}, 'Solid', '°', 0)]));
            }, 1250);

			/*
			 * Lower barometer chart
			 */
            setTimeout(function(){                           
                window['barometerChart'] = new Highcharts.Chart(
                    chartConfig('barometerChart', 400, 
                                [yAxisSchema(' hPa', true, null, null, 5, null)], pointStart, pointInterval,
                                [seriesSchema('Pressure', 'barometer', 'spline', '#85219a', '#85219a', 0, chartdata.barometer, 1.5, {enabled: false}, 'Dash', ' hPa', 1)]));
            }, 1500);

			/*
			 * Lower humidity chart
			 */
            setTimeout(function(){                           
                window['humidityChart'] = new Highcharts.Chart(
                    chartConfig('humidityChart', 400, 
                                [yAxisSchema('%', true, 0, 100, null, 10)], pointStart, pointInterval,
                                [seriesSchema('Humidity', 'outHumidity', 'spline', '#596FF0', '#596FF0', 0, chartdata.outHumidity, 1.5, {enabled: false}, null, '%', 0)]));
            }, 1750);

			/*
			 * Lower rain chart
			 */
            setTimeout(function(){                           
                window['rainChart'] = new Highcharts.Chart(
                    chartConfig('rainChart', 400, 
                                [yAxisSchema(' mm/hr', false, 0, null, 50, null), 
                                 yAxisSchema(' mm', true, 0, null, 5, null)], pointStart, pointInterval,
                                [seriesSchema('Rain Rate', 'rainRate', 'spline', '#989898', '#989898', 0, chartdata.rainRate, 1.5, {enabled: false}, 'Dash', ' mm/hr', 1),
                                 seriesSchema('Accumulated Rain', 'dayRain', 'spline', '#656565', '#656565', 1, chartdata.dayRain, 1.5, {enabled: false}, null, ' mm', 1)]));
            }, 2000);

			/*
			 * Lower temperature chart
			 */
            setTimeout(function(){                           
                window['tempChart'] = new Highcharts.Chart(
                    chartConfig('tempChart', 400, 
                                [yAxisSchema('°C', true, null, null, 5, null)], pointStart, pointInterval,
                                [seriesSchema('Temperature', 'outTemp', 'spline', '#fa3e3e', '#596FF0', 0, chartdata.outTemp, 1.5, {enabled: false}, null, '°C', 1)])); 
            }, 2250);
        }
    });

	/*
	 * Windrose
	 */
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
                              windrosedata[0],
							  '#596FF0'));
            
            window['windVelocityChart'] = new Highcharts.Chart(
                windrosechart('windVelocityChart', 
                              'Wind Speed', 
                              'Average wind speed by direction', 
                              ' m/s', 
                              ' m/s', 
                              'Velocity', 
                              windrosedata[1],
							  '#596FF0'));
            
            window['windVectorChart'] = new Highcharts.Chart(
                windrosechart('windVectorChart', 
                              'Wind Vector' ,
                              'Average wind speed from direction', 
                              ' m/s', 
                              ' m/s', 
                              'Vector', 
                              windrosedata[2],
							  '#596FF0'));
        }
    });

	/*
	 * Windrose 10 min
	 */
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
                                  windrosedata10[0],
								  '#596FF0'));

                window['windVelocityChart10'] = new Highcharts.Chart(
                    windrosechart('windVelocityChart10', 
                                  'Wind Velocity 10 Min', 
                                  'Average wind speed by direction', 
                                  ' m/s', 
                                  ' m/s', 
                                  'Vector', 
                                  windrosedata10[1],
								  '#596FF0'));

                window['windVectorChart10'] = new Highcharts.Chart(
                    windrosechart('windVectorChart10', 
                                  'Wind Vector 10 Min', 
                                  'Average wind speed from direction', 
                                  ' m/s', 
                                  ' m/s', 
                                  'Vector', 
                                  windrosedata10[2],
								  '#596FF0'));
            }
        });
    }, 1000);
});