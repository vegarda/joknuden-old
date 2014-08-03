function tempchart(tempchartdata) {
    var groupingOptions = {
        approximation: 'close',
        enabled: true,
        units: [[
        'millisecond', // unit name
        [] // allowed multiples
        ], [
        'second',
        []
        ], [
        'minute',
        [5,10,15, 30]
        ], [
        'hour',
        [1,2,3,6,12]
        ], [
        'day',
        [1,2,3,4,5,10]
        ], [
        'week',
        [1,2,3]
        ], [
        'month',
        [1]
        ], [
        'year',
        []
        ]]
    }
    
    $('#tempchart').highcharts('StockChart',{
        chart:{
            height: 250,
            backgroundColor: '#fff'
        },
        title:{
            text: null  
        },
        legend:{
            enabled: false 
        },
        exporting:{
            enabled: false   
        },
        navigator : {
            enabled : false
        },
        scrollbar : {
            enabled : false
        },
        rangeSelector : {
            enabled: false
        },

        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value}°C',
                style: {
                    //color: Highcharts.getOptions().colors[2]
                }
            },
            title: {
                text: null,
                style: {
                    //color: Highcharts.getOptions().colors[2]
                }
            },
            opposite: false

        }, { // Secondary yAxis
            gridLineWidth: 0,
            title: {
                text: null,
                style: {
                    //color: Highcharts.getOptions().colors[0]
                }
            },
            labels: {
                format: '{value} mm',
                style: {
                    //color: Highcharts.getOptions().colors[0]
                }
            },
            opposite: true

        }, { // Tertiary yAxis
            gridLineWidth: 0,
            title: {
                text: null,
                style: {
                    //color: Highcharts.getOptions().colors[1]
                }
            },
            labels: {
                format: '{value} hPa',
                style: {
                    //color: Highcharts.getOptions().colors[1]
                }
            },
            opposite: true
        }],
        xAxis: {
                type: 'datetime',
                /*dateTimeLabelFormats: { // don't display the dummy year
                    month: '%e. %b',
                    year: '%b'
                },*/
                title: {
                    text: ''
                }
            },
        plotOptions: {
            series: {
                pointStart: tempchartdata.dateTime[0] * 1000,
                pointInterval: 60*1000
            }
        },
        tooltip: {
            shared: true
        },
        series: [{
            name: 'Temperature',
            data: tempchartdata.outTemp,
            type: 'spline',
            tooltip:{
                valueDecimals: 1
            },
            dataGrouping: groupingOptions
        },{
            name: 'Rainfall',
            type: 'areaspline',
            data: tempchartdata.dayRain,
            color: '#121A73',
            yAxis: 1,
            tooltip: {
                valueSuffix: ' mm',
                valueDecimals: 1
            },
            dataGrouping: groupingOptions
        },{
            name: 'Pressure',
            type: 'spline',
            yAxis: 2,
            data: tempchartdata.barometer,
            marker: {
                enabled: false
            },
            dashStyle: 'shortdot',
            tooltip: {
                valueSuffix: ' hPa',
                valueDecimals: 1
            },
            dataGrouping: groupingOptions
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
        url: '/data/tempchart.php'+get,
        type: 'GET',
        async: false,
        dataType: "json",
        success: function(tempchartdata){
            tempchart(tempchartdata);
        }
    });
});