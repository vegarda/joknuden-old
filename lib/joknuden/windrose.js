"use strict";

$(function() {

    var what = window.location.pathname.slice(1).split("/")[0];
    var amount = window.location.pathname.slice(1).split("/")[1];

    var query_string = '';
    if (amount){var query_string = 'amount='+amount}
    if (what){var query_string = 'what='+what+'&'+query_string}
	console.log(query_string);

    Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });
    
    // create real-time chart or archive chart?
    var raw = false;
	
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


