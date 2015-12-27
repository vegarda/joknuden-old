"use strict";

$(function() {

    var w = window.location.pathname.slice(1).split("/")[0];
    var a = window.location.pathname.slice(1).split("/")[1];

    var query_string = '';
    if (a){var query_string = 'amount='+a}
    if (w){var query_string = 'what='+w+'&'+query_string}
	console.log(query_string);

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
        url: window.location.origin+'/data/archivedata.php?'+query_string,
        type: 'GET',
        async: true,
        dataType: "json",
        success: function(chartdata){
            var pointStart = chartdata.dateTime[0] * 1000;
            var pointEnd = chartdata.dateTime[chartdata.dateTime.length -1] * 1000;
            var pointInterval = ((chartdata.dateTime[chartdata.dateTime.length - 1] - chartdata.dateTime[0])/chartdata.dateTime.length) * 1000;

			console.log(Highcharts.getOptions().colors);
			
			/*
			 * Top chart
			 * delayed 250ms
			 */
            setTimeout(function(){
                console.log('archive');
                //window['topChart'] = new Highcharts.Chart(
                window['topChart'] = new Highcharts.Chart({
					chart:{
						renderTo: 'topChart',
						alignTicks: false,
						height: 600,
						spacing: [10, 0, 10, 0],
						backgroundColor: 'rgba(0,0,0,0)'
					},
					legend: {
						align: 'center',
						verticalAlign: 'top',
						layout: 'horizontal',
						x: 0,
						y: 0
					},
					credits: {
						enabled: false
					},
					title: {
						text: null  
					},
					series:[{
						name: 'Rain',
						type: 'areaspline',
						marker: {enabled: false},
						data: chartdata.accumRain,
						yAxis: 0,
						color: Highcharts.getOptions().colors[0]
					},{
						name: 'Rain rate',
						type: 'spline',
						dashStyle: 'ShortDot',
						marker: {enabled: false},
						data: chartdata.rainRate,
						yAxis: 1,
						color: Highcharts.getOptions().colors[1]
					},{
						name: 'Pressure',
						type: 'spline',
						dashStyle: 'ShortDash',
						marker: {enabled: false},
						data: chartdata.barometer,
						yAxis: 2,
						color: Highcharts.getOptions().colors[2]
					},{
						name: 'Temperature',
						type: 'spline',
						dashStyle: 'Solid',
						marker: {enabled: false},
						data: chartdata.outTemp,
						yAxis: 3,
						color: Highcharts.getOptions().colors[8],
						negativeColor: Highcharts.getOptions().colors[7]	
					},{
						name: 'Humidity',
						type: 'spline',
						dashStyle: 'ShortDot',
						marker: {enabled: false},
						data: chartdata.outHumidity,
						yAxis: 4,
						color: Highcharts.getOptions().colors[9]
					}],
					xAxis: {
						type: 'datetime',
					},
					tooltip: {
						shared: true
					},
					yAxis:[{
						labels: {
							format: '{value} mm',
							style: {
								//color: Highcharts.getOptions().colors[4]
							}
						},
						title: {
							text: null,
							style: {
							}
						},
						min: 0,
						minRange: 20,
						//startOnTick: false,
						//endOnTick: false,
						tickAmount: 6,
					},{
						labels: {
							format: '{value} mm/hr',
							enabled: false,
							style: {
								//color: Highcharts.getOptions().colors[5]
							}
						},
						title: {
							text: null,
							style: {
							}
						},
						min: 0,
						minRange: 75,
						//startOnTick: false,
						//endOnTick: false,
						tickAmount: 6,
					},{
						labels: {
							format: '{value} hPa',
							x: 10,
							style: {
								//color: Highcharts.getOptions().colors[6]
							}
						},
						opposite: true,
						title: {
							text: null,
							style: {
							}
						},
						minRange: 1,
						//startOnTick: false,
						//endOnTick: false,
						tickAmount: 6
					},{
						labels: {
							format: '{value}°C',
							y: -5,
							x: -80,
							style: {
								//color: Highcharts.getOptions().colors[7]
							}
						},
						opposite: true,
						title: {
							text: null,
							style: {
							}
						},
						//minRange: 5,
						//startOnTick: false,
						//endOnTick: false,
						tickAmount: 6,
					},{
						min: 0,
						max: 100,
						//startOnTick: false,
						//endOnTick: false,
						tickAmount: 6,
						labels: {
							format: '{value}%',
							enabled: false,
							style: {
								//color: Highcharts.getOptions().colors[7]
							}
						},
						opposite: true,
						title: {
							text: null,
							style: {
							}
						},
					}],
					plotOptions: {
						series: {
							pointStart: pointStart,
							pointInterval: pointInterval
						}
					},
				});
				
				// var yAxisSchema = function(labelUnit, opposite, min, max, minRange, tickInterval){
				// var seriesSchema = function(name, id, type, color, negativeColor, yAxisNumber, data, lineWidth, marker, dashStyle, tooltipSuffix, tooltipDecimals){
				
				/*
                    chartConfig('topChart', 500, 
                                [yAxisSchema(' mm', false, 0, null, 100, null),
                                 yAxisSchema(' mm/hr', false, 0, null, 200, null),
                                 yAxisSchema(' hPa', true, null, null, 5, null),
                                 yAxisSchema('°C', true, null, null, 5, null)], pointStart, pointInterval, 
                                [seriesSchema('Rain', 'dayRain', 'spline', '#656565', '#656565', 0, chartdata.dayRain, 1.5, {enabled: false}, 'Dot', ' mm', 1),
                                 seriesSchema('Rain Rate', 'rainRate', 'spline', '#989898', '#989898', 1, chartdata.rainRate, 1.5, {enabled: false}, 'Solid', ' mm/hr', 1),
                                 seriesSchema('Pressure', 'barometer', 'spline', '#85219a', '#85219a	', 2, chartdata.barometer, 1.5, {enabled: false}, 'dash', ' hPa', 1),
                                 seriesSchema('Temperature', 'outTemp', 'spline', '#fa3e3e', '#596FF0', 3, chartdata.outTemp, 1.5, {enabled: false}, null, '°C', 1)]));
								 
					*/
                
                /*setInterval(function() {
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
                }, 60 * 1000);*/
                
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
        url: window.location.origin+'/data/windrose.php?'+query_string,
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
                    windrosechart(
						'windFreqencyChart10', 
						'Wind Direction Frequency 10 Min', 
						null, 
						'%', 
						'%', 
						'Frequency', 
						windrosedata10[0],
						'#596FF0'
					)
				);

                window['windVelocityChart10'] = new Highcharts.Chart(
                    windrosechart(
						'windVelocityChart10', 
						'Wind Velocity 10 Min', 
						'Average wind speed by direction', 
						' m/s', 
						' m/s', 
						'Vector', 
						windrosedata10[1],
						'#596FF0'
					)
				);

                window['windVectorChart10'] = new Highcharts.Chart(
                    windrosechart(
						'windVectorChart10', 
						'Wind Vector 10 Min', 
						'Average wind speed from direction', 
						' m/s', 
						' m/s', 
						'Vector', 
						windrosedata10[2],
						'#596FF0'
					)
				);
            }
        });
    }, 1000);
	
	
});


