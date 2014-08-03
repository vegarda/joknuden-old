function windfreq(windfreqdata) {
    
    // Parse the data from an inline table using the Highcharts Data plugin
    $('#windrose-frequency').highcharts({
	    chart: {
	        polar: true,
	        type: 'column',
            backgroundColor: "#fff"
	    },
	    
	    title: {
            text: 'Wind Direction Frequency'
	    },
	    subtitle: {
            text: null
	    },
	    pane: {
	    	size: '85%'
	    },
	    legend: {
	    	enabled: false
	    },
	    
	    xAxis: {
	    	tickmarkPlacement: 'on',
            categories: [
                'N', 'NNE', 'NE', 'ENE',
                'E', 'ESE', 'SE', 'SSE', 
                'S', 'SSW', 'SW', 'WSW', 
                'W', 'WNW', 'NW', 'NNW', 
            ]
	    },
	        
	    yAxis: {
	        min: 0,
	        endOnTick: false,
	        showLastLabel: true,
	        title: {
	        	text: null
	        },
	        labels: {
                step: 2,
	        	formatter: function () {
	        		return this.value + '%';
	        	}
	        }
	    },
	    
	    tooltip: {
	    	valueSuffix: '%'
	    },
	        
	    plotOptions: {
	        series: {
	        	stacking: 'normal',
	        	shadow: false,
	        	groupPadding: 0,
	        	pointPlacement: 'on'
	        }
	    },
         series: [{
            name: 'Frequency',
            data: windfreqdata,
            type: 'column',
            tooltip:{
                valueDecimals: 1
            }
        }]
	});
};
                                        
function windvelocity(velocitydata) {
    
    // Parse the data from an inline table using the Highcharts Data plugin
    $('#windrose-velocity').highcharts({
	    chart: {
	        polar: true,
	        type: 'column',
            backgroundColor: "#fff"
	    },
	    
	    title: {
            text: 'Wind Velocity'
	    },
	    subtitle: {
            text: null
	    },
	    pane: {
	    	size: '85%'
	    },
	    legend: {
	    	enabled: false
	    },
	    
	    xAxis: {
	    	tickmarkPlacement: 'on',
            categories: [
                'N', 'NNE', 'NE', 'ENE',
                'E', 'ESE', 'SE', 'SSE', 
                'S', 'SSW', 'SW', 'WSW', 
                'W', 'WNW', 'NW', 'NNW', 
            ]
            /*categories: [
                'N', 'NNØ', 'NØ', 'ØNØ',
                'Ø', 'ØSØ', 'SØ', 'SSØ', 
                'S', 'SSV', 'SV', 'VSV', 
                'V', 'VNV', 'NV', 'NNV', 
            ]*/
	    },
	        
	    yAxis: {
	        min: 0,
	        endOnTick: false,
	        showLastLabel: true,
	        title: {
	        	text: null
	        },
	        labels: {
                step: 2,
	        	formatter: function () {
	        		return this.value + 'm/s';
	        	}
	        }
	    },
	    
	    tooltip: {
	    	valueSuffix: ' m/s'
	    },
	        
	    plotOptions: {
	        series: {
	        	stacking: 'normal',
	        	shadow: false,
	        	groupPadding: 0,
	        	pointPlacement: 'on'
	        }
	    },
         series: [{
            name: 'Velocity',
            data: velocitydata,
            type: 'column',
            tooltip:{
                valueDecimals: 1
            }
        }]
	});
};
    
$(function() {
    var w = window.location.pathname.slice(1).split("/")[0];
    var a = window.location.pathname.slice(1).split("/")[1];
    var get = '';
    if (a){var get = '?w='+w+'&a='+a}
    else if (w){var get = '?w='+w}
    $.ajax({
        url: '/data/windrose.php'+get,
        type: 'GET',
        async: false,
        dataType: "json",
        success: function(windrosedata){
            console.log(windrosedata);
            windfreq(windrosedata[0]);
            windvelocity(windrosedata[1]);
        }
    });
});