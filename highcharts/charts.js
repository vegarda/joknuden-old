"use strict";

var w = window.location.pathname.slice(1).split("/")[0];
var a = window.location.pathname.slice(1).split("/")[1];

var get = '';
if (a){var get = '?w='+w+'&a='+a}
else if (w){var get = '?w='+w}

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
    [5,10,15,30,40,45,50]
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
};
   
var yAxisSchema = function(labelUnit, opposite, min, max, minRange, tickInterval){
    return { 
            labels: {
                format: '{value}' + labelUnit,
                style: {
                }
            },
            title: {
                text: null,
                style: {
                }
            },
            opposite: opposite,
            min: min,
            max: max,
            minRange: minRange,
            tickInterval: tickInterval
    }; 
};

var seriesSchema = function(name, type, color, yAxisNumber, data, dashStyle, tooltipSuffix, tooltipDecimals){
    return {
        name: name,
        type: type,
        color: color,
        yAxis: yAxisNumber,
        data: data,
        marker: {
            enabled: false
        },
        dashStyle: dashStyle,
        tooltip: {
            valueSuffix: tooltipSuffix,
            valueDecimals: tooltipDecimals
        },
        dataGrouping: groupingOptions
    };
};

//var tempchartconfig = function(tempchartdata){
//var tripleChartConfig = function(renderTo, height, yAxis1, yAxis2, yAxis3, pointStart, series1, series2, series3){
var chartConfig = function(renderTo, height, yAxis, pointStart, series){
    return {
        chart: {
            renderTo: renderTo,
            height: height,
            backgroundColor: 'rgba(0,0,0,0)'
        },
        title: {
            text: null  
        },
        legend: {
            enabled: false 
        },
        exporting: {
            enabled: false   
        },
        navigator: {
            enabled: false
        },
        scrollbar: {
            enabled: false
        },
        rangeSelector: {
            enabled: false
        },

        yAxis: yAxis,
        xAxis: {
                type: 'datetime',
                title: {
                    text: ''
                }
            },
        plotOptions: {
            series: {
                pointStart: pointStart,
                pointInterval: 60*1000
            }
        },
        tooltip: {
            shared: true
        },
        series: series
    };
};


var windrosechart = function(renderTo, title, labelUnit, tooltipSuffix, seriesName, seriesData){
    return {
        chart: {
            renderTo: renderTo,
            polar: true,
            type: 'column',
            backgroundColor: 'rgba(0,0,0,0)'
        },

        title: {
            text: title
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
                    return this.value + labelUnit;
                }
            }
        },

        tooltip: {
            valueSuffix: tooltipSuffix
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
            name: seriesName,
            data: seriesData,
            tooltip: {
                valueDecimals: 1
            }
        }]
    };
};




var multichartconfig = function(renderTo, labelUnit, floor, minRange, pointStart, seriesName, seriesData, seriesType, tooltipSuffix){
    //barometer, outTemp, outHumidity, windSpeed, windDir, windGust, windGustDir, rain, rainRate, dayRain, dewpoint
    return {
        chart: {
            renderTo: renderTo,
            height: 250,
            backgroundColor: 'rgba(0,0,0,0)'
        },
        title: {
            text: null  
        },
        legend: {
            enabled: false 
        },
        exporting: {
            enabled: false   
        },
        navigator: {
            enabled: false
        },
        scrollbar: {
            enabled: false
        },
        rangeSelector: {
            enabled: false
        },

        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value}' + labelUnit,
                style: {
                }
            },
            title: {
                text: null,
                style: {
                }
            },
            opposite: true,
            floor: floor,
            minRange: minRange

        }],
        xAxis: {
                type: 'datetime',
                title: {
                    text: ''
                }
            },
        plotOptions: {
            series: {
                pointStart: pointStart * 1000,
                pointInterval: 60*1000
            }
        },
        series: [{
            name: seriesName,
            data: seriesData,
            type: seriesType,
            tooltip: {
                valueSuffix: tooltipSuffix,
                valueDecimals: 1
            },
            dataGrouping: groupingOptions
        }]
    };
};

