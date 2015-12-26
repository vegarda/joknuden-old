"use strict";

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
    [1]
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

var seriesSchema = function(name, id, type, color, negativeColor, yAxisNumber, data, lineWidth, marker, dashStyle, tooltipSuffix, tooltipDecimals){
    return {
        name: name,
        id: id,
        type: type,
        color: color,
		negativeColor: negativeColor,
        yAxis: yAxisNumber,
        data: data,
        lineWidth: lineWidth,
        marker: marker,
        dashStyle: dashStyle,
        tooltip: {
            valueSuffix: tooltipSuffix,
            valueDecimals: tooltipDecimals
        },
        dataGrouping: groupingOptions
    };
};

var flagSchema = function(name, seriesID, max, maxtime, min, mintime, color, unit){
    return {
        type : 'flags',
        data : [{
            x : maxtime * 1000,
            title : max.toFixed(1) + unit,
            text : name + ': ' + max.toFixed(1) + unit
        }, {
            x : mintime * 1000,
            title : min.toFixed(1) + unit,
            text : name + ': ' + min.toFixed(1) + unit
        }],
        onSeries : seriesID,
        shape : 'squarepin',
        width : null,
        showInLegend: false,
        color: color,
        fillColor: color,
        zIndex: 1000,
        stackDistance: 20,
        style: { // text style
            color: 'rgba(254,254,254,0.95)',
        },
        states : {
            hover : {
                fillColor: color
            }
        }
    };
};


var chartConfig = function(renderTo, height, yAxis, pointStart, pointInterval, series){
    return {
        chart: {
            renderTo: renderTo,
            height: height,
            backgroundColor: 'rgba(0,0,0,0)'
        },

		credits: {
			enabled: false
        },
		
        title: {
            text: null  
        },
        legend: {
            enabled: true,
            floating: true,
            verticalAlign: 'top',
            backgroundColor: 'rgba(254,254,254,0.5)',
            borderColor: '#222'
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
                pointInterval: pointInterval
            }
        },
        tooltip: {
            shared: true
        },
        series: series
    };
};



var rawChartConfig = function(renderTo, height, yAxis, pointStart, pointInterval, series){
    return {
        chart: {
            renderTo: renderTo,
            height: height,
            backgroundColor: 'rgba(0,0,0,0)'
        },
		
		credits: {
			enabled: false
        },
		
        title: {
            text: null  
        },
        legend: {
            enabled: true,
            floating: true,
            verticalAlign: 'top',
            backgroundColor: 'rgba(254,254,254,0.5)',
            borderColor: '#222'
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
                pointInterval: pointInterval
            }
        },
        tooltip: {
            shared: true
        },
        series: series
    };
};


var windrosechart = function(renderTo, title, subtitle, labelUnit, tooltipSuffix, seriesName, seriesData, color){
    return {
        chart: {
            renderTo: renderTo,
            polar: true,
            type: 'column',
            backgroundColor: 'rgba(0,0,0,0)',
			spacing: 15,
			//height: 300,
        },
		
		credits: {
			enabled: false
        },

        title: {
            text: title
        },
        subtitle: {
            text: subtitle
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
			color: color,
            tooltip: {
                valueDecimals: 1
            }
        }]
    };
};
