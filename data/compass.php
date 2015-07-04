<?php

echo '
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<meta name="description" content="Joknuden weather station located at the south west coast of Norway, bordering the open waters of the North Sea.">
		<meta name="keywords" content="Joknuden,Weather Station,Weather,wx">
		<meta name="author" content="Vegard Andersen">
		<link rel=”author” href=”https://plus.google.com/113629300948788892639“/>
		
		<meta property=”og:title” content=”Joknuden Weather Station”/>
		<meta property=”og:url” content=”http://joknuden.no”/>
		<meta property=”og:description” content=”Joknuden weather station located at the south west coast of Norway, bordering the open waters of the North Sea.”/>
		
		<meta property=”fb:admins” content=”vegarda”/>

		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		
		<title>Joknuden Weather Station</title>

		<!-- jQuery-->
		<script type="text/javascript" src="/lib/jquery/jquery-2.1.4.min.js"></script>
		
		<link href="/lib/weather-icons/css/weather-icons.min.css" rel="stylesheet">
		<link href="/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		
		
			
		<script>
			

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
			

		function update(){

			$.ajax({
				url: window.location.origin+"/data/data.php",
				type: "GET",
				async: true,
				dataType: "json",
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
					

					if (typeof compassHeight === "undefined"){
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
					var direction = principalWinds[index];

					$(".wind-direction").text(direction);
					$(".windSpeed-value").text(parseFloat(data["windSpeed"]).toFixed(1));
					
					function updateWindDir(elem, from, to, dur){
						var array = [];
						var n = 100;
						dt = dur/n;
						var dx = (to - from)/n;
						
						for (i = 0; i < n; i++) {
							array.push(from + dx*i);
						}
						
						$.each(array, function(index, value){
							setTimeout(function(){
							//elem.attr("transform","rotate(" + value + " 100 100)");
							elem.attr("transform","rotate(" + value + " 100 100)");
							}, index*dt);
						});
					};

					updateWindDir($(".arrow"), oldRotateDir, rotateDir, 1500);
	
					oldWindDir = data["windDir"];
					oldRotateDir = rotateDir;

				}
			});

		}


		$( document ).ready(function() {
			console.log("realtime.js");
			
			setInterval(update, interval);
			
		});
		
		</script>

		<style>

		
			.tick{
				color: black;
				fill: black;
				stroke: black;
			}

			.tick90{
				stroke-width: 4;
			}
			
			.tick45{
				stroke-width: 3;
			}
			
			.tick22{
				stroke-width: 2;
			}
			
			.tick11{
				stroke-width: 1;
			}
			
			
		</style>

	</head>
	<body>';



echo '
	

	<div class="wind realtime-group">
		
		<svg height="auto	" width="auto" viewbox="0 0 200 200">
			<path class="tick tick90" d="M 100 10 L 100 20" transform="rotate(0 100 100)  translate(0 10)"  />
			<path class="tick tick11" d="M 100 0  L 100 20" transform="rotate(11.25 100 100)  translate(0 15)"  />
			<path class="tick tick22" d="M 100 10 L 100 20" transform="rotate(22.5 100 100)  translate(0 10)"  />
			<path class="tick tick11" d="M 100 0  L 100 20" transform="rotate(33.75 100 100)  translate(0 15)"  />
			<path class="tick tick45" d="M 100 10 L 100 20" transform="rotate(45 100 100)  translate(0 10)"  />
			<path class="tick tick11" d="M 100 0  L 100 20" transform="rotate(56.25 100 100)  translate(0 15)"  />
			<path class="tick tick22" d="M 100 10 L 100 20" transform="rotate(67.5 100 100)  translate(0 10)"  />
			<path class="tick tick11" d="M 100 0  L 100 20" transform="rotate(78.75 100 100)  translate(0 15)"  />
			
			<path class="tick tick90" d="M 100 10 L 100 20" transform="rotate(90 100 100)  translate(0 10)"  />
			<path class="tick tick11" d="M 100 0  L 100 20" transform="rotate(101.25 100 100)  translate(0 15)"  />
			<path class="tick tick22" d="M 100 10 L 100 20" transform="rotate(112.5 100 100)  translate(0 10)"  />
			<path class="tick tick11" d="M 100 0  L 100 20" transform="rotate(123.75 100 100)  translate(0 15)"  />
			<path class="tick tick45" d="M 100 10 L 100 20" transform="rotate(135 100 100)  translate(0 10)"  />
			<path class="tick tick11" d="M 100 0  L 100 20" transform="rotate(146.25 100 100)  translate(0 15)"  />
			<path class="tick tick22" d="M 100 10 L 100 20" transform="rotate(157.5 100 100)  translate(0 10)"  />
			<path class="tick tick11" d="M 100 0  L 100 20" transform="rotate(168.75 100 100)  translate(0 15)"  />
			
			<path class="tick tick90" d="M 100 10 L 100 20" transform="rotate(180 100 100)  translate(0 10)"  />
			<path class="tick tick11" d="M 100 0  L 100 20" transform="rotate(191.25 100 100)  translate(0 15)"  />
			<path class="tick tick22" d="M 100 10 L 100 20" transform="rotate(202.5 100 100)  translate(0 10)"  />
			<path class="tick tick11" d="M 100 0  L 100 20" transform="rotate(213.75 100 100)  translate(0 15)"  />
			<path class="tick tick45" d="M 100 10 L 100 20" transform="rotate(225 100 100)  translate(0 10)"  />
			<path class="tick tick11" d="M 100 0  L 100 20" transform="rotate(236.25 100 100)  translate(0 15)"  />
			<path class="tick tick22" d="M 100 10 L 100 20" transform="rotate(247.5 100 100)  translate(0 10)"  />
			<path class="tick tick11" d="M 100 0  L 100 20" transform="rotate(258.75 100 100)  translate(0 15)"  />
			
			<path class="tick tick90" d="M 100 10 L 100 20" transform="rotate(270 100 100)  translate(0 10)"  />
			<path class="tick tick11" d="M 100 0  L 100 20" transform="rotate(281.25 100 100)  translate(0 15)"  />
			<path class="tick tick22" d="M 100 10 L 100 20" transform="rotate(292.5 100 100)  translate(0 10)"  />
			<path class="tick tick11" d="M 100 0  L 100 20" transform="rotate(303.75 100 100)  translate(0 15)"  />
			<path class="tick tick45" d="M 100 10 L 100 20" transform="rotate(315 100 100)  translate(0 10)"  />
			<path class="tick tick11" d="M 100 0  L 100 20" transform="rotate(326.25 100 100)  translate(0 15)"  />
			<path class="tick tick22" d="M 100 10 L 100 20" transform="rotate(337.5 100 100)  translate(0 10)"  />
			<path class="tick tick11" d="M 100 0  L 100 20" transform="rotate(348.75 100 100)  translate(0 15)"  />
			
			<polygon stroke="black" style="vector-effect: none; stroke-linejoin: round;" class="arrow" points="85,130 100,75 115,130 100,120" stroke-width="5" fill="black"/>
			
		</svg>
		
	</div>';



echo'


    </body>
</html>';

?>