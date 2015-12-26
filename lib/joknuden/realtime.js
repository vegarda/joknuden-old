"use strict";

var windDir = 0;
var oldWindDir = 0;
var oldRotateDir = 0;
var rotateDir = 0;

var interval = 2500;

var principalWinds = [
	"N", "NNØ", "NØ", "ØNØ",
	"Ø", "ØSØ", "SØ", "SSØ",
	"S", "SSV", "SV", "VSV",
	"V", "VNV", "NV", "NNV"];
	
var beaufort = {
	"description": {
		"no": [
			"Stille",
			"Flau vind",
			"Svak vind",
			"Lett bris",
			"Laber bris",
			"Frisk bris",
			"Liten kuling",
			"Stiv kuling",
			"Sterk kuling", 
			"Liten storm",
			"Full storm",
			"Sterk storm",
			"Orkan"],
		"en": ["Calm",
			"Light air",
			"Light breeze",
			"Gentle breeze",
			"Moderate breeze",
			"Fresh breeze",
			"Strong breeze",
			"Near gale",
			"Gale",
			"Strong gale",
			"Storm",
			"Violent storm",
			"Hurricane"]},
	"windSpeed": [[0.2, 1.5, 3.3, 5.5, 8, 10.8, 13.9, 17.2, 20.8, 24.5, 28.5, 32.6, 100], 
		[0, 0.2, 1.5, 3.3, 5.5, 8, 10.8, 13.9, 17.2, 20.8, 24.5, 28.5, 32.6]]};
		
var getBeaufortScale = function(windSpeed){
	var scale = 0;
	$.each(beaufort.windSpeed[0], function(index, value){
		if (value > windSpeed){
			scale = index;
			return false;
		};
	});
	return scale;
};

var principalWinds = {
	"abbr": {
		"en": ["N", "NNE", "NE", "ENE",
		"E", "ESE", "SE", "SSE",
		"S", "SSW", "SW", "WSW",
		"W", "WNW", "NW", "NNW"],
		"no": ["N", "NNØ", "NØ", "ØNØ",
		"Ø", "ØSØ", "SØ", "SSØ",
		"S", "SSV", "SV", "VSV",
		"V", "VNV", "NV", "NNV"]},
	"long": {
		"en": ["north", "north-northeast", "northeast", "east-northeast",
			"east", "east-southeast", "southeast", "soth-southeast",
			"south", "south-southwest", "southwest", "west-southwest",
			"west", "west-northwest", "northwest", "north-northwest"],
		"no": ["nord", "nord-nordøst", "nordøst", "øst-nordøst",
			"øst", "øst-sørøst", "sørøst", "sør-sørøst",
			"sør", "sør-sørvest", "sørvest", "vest-sørvest",
			"vest", "vest-nordvest", "nordvest", "nord-nordvest"]}
};

var forecastIcons = {
	"davis": [
		"",
		"ICON_RAIN",
		"ICON_CLOUD",
		"ICON_CLOUD_RAIN",

		"ICON_PARTSUN",
		"ICON_PARTSUN_RAIN",
		"ICON_PARTSUN_CLOUD",
		"ICON_PARTSUN_CLOUD_RAIN",

		"ICON_SUN",
		"ICON_SUN_RAIN",
		"ICON_SUN_CLOUD",
		"ICON_SUN_CLOUD_RAIN",

		"ICON_SUN_PARTSUN",
		"ICON_SUN_PARTSUN_RAIN",
		"ICON_SUN_PARTSUN_CLOUD",
		"ICON_SUN_PARTSUN_CLOUD_RAIN",

		"ICON_SNOW",
		"ICON_SNOW_RAIN",
		"ICON_SNOW_CLOUD",
		"ICON_SNOW_CLOUD_RAIN",

		"ICON_SNOW_PARTSUN",
		"ICON_SNOW_PARTSUN_RAIN",
		"ICON_SNOW_PARTSUN_CLOUD",
		"ICON_SNOW_PARTSUN_CLOUD_RAIN",

		"ICON_SNOW_SUN",
		"ICON_SNOW_SUN_RAIN",
		"ICON_SNOW_SUN_CLOUD",
		"ICON_SNOW_SUN_CLOUD_RAIN",

		"ICON_SNOW_SUN_PARTSUN",
		"ICON_SNOW_SUN_PARTSUN_RAIN",
		"ICON_SNOW_SUN_PARTSUN_CLOUD",
		"ICON_SNOW_SUN_PARTSUN_CLOUD_RAIN"],
	"weatherIcons": [
		"",
		"wi-showers",
		"wi-cloudy",
		"wi-showers",

		"wi-day-cloudy",
		"wi-day-showers",
		"wi-day-cloudy",
		"wi-day-showers",

		"wi-day-sunny",
		"wi-day-showers",
		"wi-day-cloudy",
		"wi-day-showers",

		"wi-day-cloudy",
		"wi-day-showers",
		"wi-day-cloudy",
		"wi-day-showers",

		"wi-snow",
		"wi-rain-mix",
		"wi-snow",
		"wi-rain-mix",

		"wi-day-snow",
		"wi-day-rain-mix",
		"wi-day-snow",
		"wi-day-rain-mix",

		"wi-day-snow",
		"wi-day-rain-mix",
		"wi-day-snow",
		"wi-day-rain-mix",

		"wi-day-snow",
		"wi-day-rain-mix",
		"wi-day-snow",
		"wi-day-rain-mix"],
	"weather-icons-night":[
		"",
		"wi-night-showers",
		"wi-night-cloudy",
		"wi-night-showers",

		"wi-night-cloudy",
		"wi-night-showers",
		"wi-night-cloudy",
		"wi-night-showers",

		"wi-night-clear",
		"wi-night-showers",
		"wi-night-cloudy",
		"wi-night-showers",

		"wi-night-cloudy",
		"wi-night-showers",
		"wi-night-cloudy",
		"wi-night-showers",

		"wi-night-snow",
		"wi-night-rain-mix",
		"wi-night-snow",
		"wi-night-rain-mix",

		"wi-night-snow",
		"wi-night-rain-mix",
		"wi-night-snow",
		"wi-night-rain-mix",

		"wi-night-snow",
		"wi-night-rain-mix",
		"wi-night-snow",
		"wi-night-rain-mix",

		"wi-night-snow",
		"wi-night-rain-mix",
		"wi-night-snow",
		"wi-night-rain-mix"]
};

var getForecastIcon = function(forecastIcon){
	return forecastIcons.weatherIcons[parseInt(forecastIcon)];
}

function updateWindDir(elem, from, to, dur){
	var array = [];
	var n = 100;
	var dt = dur/n;
	var d = to - from;
	var dx = (to - from)/n;
	
	function cubicBezier(t){

		var P0 = 0;
		var P1 = 0.8;
		var P2 = 0.2;
		var P3 = 1;

		var B1 = P0*Math.pow(1 - t, 3);
		var B2 = P1*Math.pow(1 - t, 2)*3*t;
		var B3 = P2*Math.pow(1 - t, 1)*3*Math.pow(t, 2);
		var B4 = P3*Math.pow(t, 3);

		var B = B1 + B2 + B3 + B4;

		return B;

	}

	for (var i = 0; i < n; i++) {
		array.push(cubicBezier((i + 1)*dt/dur));
	}

	
	$.each(array, function(index, value){

		setTimeout(function(){
			elem.attr("transform","rotate(" + (from + (index + 1)*dx) + " 100 100)");
		}, from + value*dur);
	});

};


function update(){

	$.ajax({
		url: window.location.origin+'/data/data.php',
		type: 'GET',
		async: true,
		dataType: 'json',
		success: function(data){

			windDir = Number(data["windDir"]);
			
			var alpha = windDir - oldWindDir;
			var beta = windDir - oldWindDir + 360;
			var gamma = windDir - oldWindDir - 360;
			
			if ((Math.abs(alpha) < Math.abs(beta)) && (Math.abs(alpha) < Math.abs(gamma))){
				rotateDir = oldRotateDir + alpha;
			}
			else if ((Math.abs(beta) < Math.abs(alpha)) && (Math.abs(beta) < Math.abs(gamma))){
				rotateDir = oldRotateDir + beta;
			}
			else if ((Math.abs(gamma) < Math.abs(alpha)) && (Math.abs(gamma) < Math.abs(beta))){
				rotateDir = oldRotateDir + gamma;
			}
			else{
				rotateDir = windDir;
			}
			

			if (typeof compassHeight === 'undefined'){
				var compassHeight = parseInt($(".wind.realtime-group").height())/2 + parseInt($(".wind-arrow i").css("font-size"))/2;
			}

			$("div.wind-arrow i" ).css({	
										"transition-duration": "1.5s", 
										"-webkit-transition-duration": "1.5s", 
										"-moz-transition-duration": "1.5s", 
										"-o-transition-duration": "1.5s",
										"-ms-transition-duration": "1.5s",
										
										"transform": "rotate(" + rotateDir + "deg)",
										"-webkit-transform": "rotate(" + rotateDir + "deg)", 
										"-moz-transform": "rotate(" +rotateDir + "deg)", 
										"-o-transform": "rotate(" +rotateDir + "deg)", 
										"-ms-transform": "rotate(" +rotateDir + "deg)", 
										
										"transform-origin": "center " + compassHeight + "px",/*
										"-webkit-transform-origin": "0 " + compassHeight/2 + "px",
										"-moz-transform-origin": "0 " + compassHeight/2 + "px",
										"-o-transform-origin": "0 " + compassHeight/2 + "px",
										"-ms-transform-origin": "0 " + compassHeight/2 + "px",*/
										});


			var index = Math.floor((windDir + 11.25)/22.5);
			if (index > 15){index = 0;};
			var direction = principalWinds.abbr.no[index];
			
			var date = new Date(data.dateTime * 1000);
			$(".dateTime-value").text(date.toLocaleString());

			$(".outTemp-value").text(parseFloat(data.outTemp).toFixed(1));
			$(".outTemp-feels-like-value").text(parseFloat(data.heatindex).toFixed(1));
			$(".outTemp-low").text(parseFloat(data["archive_day_outTemp"].min).toFixed(1));
			$(".outTemp-low-time").text((new Date(parseInt(data["archive_day_outTemp"].mintime) * 1000)).getHours());
			$(".outTemp-high").text(parseFloat(data["archive_day_outTemp"].max).toFixed(1));
			$(".outTemp-high-time").text((new Date(parseInt(data["archive_day_outTemp"].maxtime) * 1000)).getHours());
			
			$(".wind-direction").text(direction);
			$(".windSpeed-value").text(parseFloat(data.windSpeed).toFixed(1));
			
			var tempBeaufortScale = getBeaufortScale(parseFloat(data.windSpeed));
			
			$(".wind-beaufort").text(beaufort.description.no[tempBeaufortScale] + "");
			$(".wind-principal").text("fra " + principalWinds.long.no[index]);
			$(".wind-windSpeed").text(parseFloat(data.windSpeed).toFixed(1) + " mps");
			
			updateWindDir($(".arrow"), oldRotateDir, rotateDir, 1000);
			
			$(".barometer-value").text(parseFloat(data.barometer).toFixed(1));
			$(".barometer-low").text(parseFloat(data["archive_day_barometer"].min).toFixed(1));
			$(".barometer-low-time").text((new Date(parseInt(data["archive_day_barometer"].mintime) * 1000)).getHours());
			$(".barometer-high").text(parseFloat(data["archive_day_barometer"].max).toFixed(1));
			$(".barometer-high-time").text((new Date(parseInt(data["archive_day_barometer"].maxtime) * 1000)).getHours());

			$(".outHumidity-value").text(parseFloat(data.outHumidity));
			$(".outHumidity-low").text(parseFloat(data["archive_day_outHumidity"].min));
			$(".outHumidity-low-time").text((new Date(parseInt(data["archive_day_outHumidity"].mintime) * 1000)).getHours());
			$(".outHumidity-high").text(parseFloat(data["archive_day_outHumidity"].max));
			$(".outHumidity-high-time").text((new Date(parseInt(data["archive_day_outHumidity"].maxtime) * 1000)).getHours());

			$(".dayRain-value").text(parseFloat(data.dayRain).toFixed(1));
			var date = new Date(parseInt(data.dateTime) * 1000);
			$(".dateTime-value").text(('0' + date.getHours()).slice(-2) + ":" + date.getMinutes() + ":" + date.getSeconds());
			
			oldWindDir = data.windDir;
			oldRotateDir = rotateDir;

		}
    });

}


$( document ).ready(function() {
	console.log('realtime.js');
    
    setInterval(update, interval);
    
});