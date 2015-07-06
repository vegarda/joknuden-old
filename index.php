<?php
session_start();

include('data/startend.php');
include('data/host.php');

header('what: '.$what);
header('amount: '.$amount);
header('start: '.date("Y-m-d H:i:s", $start));
header('end: '.date("Y-m-d H:i:s", $end));

$REQUEST_URI = 'http://'.$_SERVER['HTTP_HOST'].'/';

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

$today = false;
$today = true;

$host = '127.0.0.1';
include('data/host.php');

?>

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

		<!-- google analytics-->
		<script type="text/javascript" src="/lib/analytics.js"></script>

		<!-- Bootstrap -->
		<link href="/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<script type="text/javascript" src="/lib/bootstrap/js/bootstrap.min.js"></script>

		<!-- CSS -->
		<link href="/css/realtime.css" rel="stylesheet">
		<link href="/css/joknuden.css" rel="stylesheet">
		<link href="/lib/weather-icons/css/weather-icons.min.css" rel="stylesheet">
		<link href="/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">

		<!-- Highcharts -->
		<script type="text/javascript" src="/lib/highcharts/js/highcharts.js"></script>
		<script type="text/javascript" src="/lib/highcharts/js/highcharts-more.js"></script>

		<!-- Joknuden -->
		<script type="text/javascript" src="/lib/joknuden/charts.js"></script>
		<script type="text/javascript" src="/lib/joknuden/chartbuilder.js"></script>
		<script type="text/javascript" src="/lib/joknuden/forecast.js"></script>
		<script type="text/javascript" src="/lib/joknuden/warning.js"></script>
		<script type="text/javascript" src="/lib/joknuden/sun.js"></script>
		<script type="text/javascript" src="/lib/joknuden/realtime.js"></script>

		<!-- lightbox2 -->
		<script type="text/javascript" src="/lib/lightbox2/dist/js/lightbox.min.js"></script>
		<link href="/lib/lightbox2/dist/css/lightbox.css" rel="stylesheet">

		<script type="text/javascript">
			$( document ).ready(function() {
				console.log("ready!");

				var request_uri = window.location.pathname;
				$("div.collapse ul.nav li a").each(function(){
					var active = false;
					if (!(request_uri == "/")){ 
						var w = window.location.pathname.slice(1).split("/")[0];
						var a = window.location.pathname.slice(1).split("/")[1];
						if (w == "ytd"){
							w = "Year to date";
							//a = "";
						}
						window.document.title = "Joknuden | " + a + " " + w.charAt(0).toUpperCase() + w.slice(1) + (a > 1 ? "s" : "");
						if (this.href.search(request_uri) > 0){
							$(this).parent().addClass("active");
							active = true;
						}
					}
					else if((request_uri == "/")&&(this.id == "today")){
						$(this).parent().addClass("active");
						active = true;
						window.document.title = "Joknuden | Today";
					}
					if (!active){
						
					}
				});
			});
		</script>

	</head>
	<body>

<?php

if ($host == 'joknuden.no'){
	echo '<embed id="phpinfo" src="/data/server.php" width="700" height="800" style="margin: 0 auto; display: block;">';
}

?>
		<div class="navbar navbar-inverse navbar-static-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand fancybox navlink" rel="nogroup" title="View from Joknuden" href="/sd.jpg" alt="">Joknuden</a>
				</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li><a class="navlink" id="yesterday" href="/yesterday/">Yesterday</a></li>
					<li><a class="navlink" id="today" href="/">Today</a></li>
					<li><a class="navlink" id="3day" href="/day/3/">3 day</a></li>
					<li><a class="navlink" id="week" href="/week/1/">Week</a></li>
					<li><a class="navlink" id="month" href="/month/1/">Month</a></li>
					<li><a class="navlink" id="3-month" href="/month/3/">3 Month</a></li>
					<li><a class="navlink" id="6-month" href="/month/6/">6 Month</a></li>
					<li><a class="navlink" id="ytd" href="/ytd/">YTD</a></li>
					<li><a class="navlink" id="year" href="/year/1/">Year</a></li>
					<li><a class="navlink fancybox" id="radar" rel="radar" title="<a href=\'http://www.yr.no\'>Forecast from yr.no</a>" href="http://api.yr.no/weatherapi/radar/1.4/?radarsite=southwest_norway;type=reflectivity;content=animation;size=large#.jpg" alt="">RADAR</a></li>
					<li id="webcam-images">
						<!-- a class="navlink lightbox" id="webcam" rel="webcam" data-lightbox="webcam" title="View from Joknuden" href="/sd.jpg" alt="">Webcam</a -->
					</li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</div>
	
	<br>

	<div class="container">
		<div class="container-fluid container-console">
			<div id="realtime-row-1" class="row realtime-row">
				
				<div class="stats stats-group realtime-group vertical col-md-3 col-xs-12">

							<div class="realtime-container stats-container">

								<div class="barometer-group">
									<div class="barometer-value-unit realtime-value-unit value-unit">
										<h5>
											<span class="barometer-title realtime-title stats-title">Barometer:</span>
											<span class="barometer-value realtime-value"></span>
											<span class="barometer-unit realtime-unit">hPa</span> 
										</h5>
									</div>
								</div>

								<div class="outHumidity-group">
									<div class="outHumidity-value-unit realtime-value-unit value-unit">
										<h5>
											<span class="outHumidity-title realtime-title stats-title">Luftfuktighet:</span>
											<span class="outHumidity-value realtime-value"></span>
											<span class="outHumidity-unit realtime-unit">%</span> 
										</h5>
									</div>
								</div>

								<div class="dayRain-group">
									<div class="dayRain-value-unit realtime-value-unit value-unit">
										<h5>
											<span class="dayRain-title realtime-title stats-title">Regn:</span>
											<span class="dayRain-value realtime-value"></span>
											<span class="dayRain-unit realtime-unit">mm</span>
										</h5>
									</div>
								</div>
							</div>
				</div>

				<div class="outTemp outTemp-group realtime-group col-md-2 col-xs-12">
					<div class="row outTemp-row realtime-row">
						<div class="realtime-column col-xs-12">
							<div class="realtime-container">
								<h1 class="outTemp-title realtime-title">
									<span class="outTemp-value realtime-value">0</span>
									<span class="outTemp-unit realtime-unit">°C</span> 
								</h1>
								<h5>
									<span class="outTemp-feels-like">Feels like</span>
									<span class="outTemp-feels-like outTemp-feels-like-value realtime-value">0</span>
									<span class="outTemp-feels-like-unit realtime-unit">°C</span> 
								</h5>
							</div>
						</div>

					</div>
				</div>
				
				
				<div class="wind wind-group realtime-group col-md-4 col-xs-12">
					<div class="row wind-row realtime-row">
						<div class="wind-compass realtime-column col-xs-6 ">



								<svg viewbox="0 0 200 200" style="display:inline-block;vertical-align:middle;height:100%;width:100%;margin:0 auto;">
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
								
								<polygon stroke="black" style="vector-effect: none; stroke-linejoin: round;" class="arrow" points="85,120 100,75 115,120 100,110" stroke-width="5" fill="black"/>
								
							</svg>
							


						</div>
						
						<div class="wind-text wind-data wind-description realtime-column col-xs-6 ">
							<div class="realtime-container">
								<h3 class="wind-beaufort"></h3>
								<h5 class="wind-principal"></h5>
								<h4 class="wind-windSpeed"></h4>
							</div>
						</div>
					</div>
				</div>
				
				<div class="webcam webcam-group realtime-group vertical col-md-3 col-xs-12">
					<div class="realtime-container">
						<div class="webcam-image-container img-circle">
<?php
if ($host == '127.0.0.1'){
	$day    = date("Y-m-d");
	$dir    = 'timelapse/'.$day;
	$images = scandir($dir, 1);
	$first = $images[0];
	$images = array_slice($images, 1, -2);
	echo '<a id="webcam" class="webcam-link lightbox" rel="webcam" title="View from Joknuden @ '.substr($first, 0 , -4).'" data-lightbox="webcam" href="/timelapse/'.$day.'/'.$first.'" alt=""><img src="/timelapse/'.$day.'/'.$first.'" class="webcam-image">/timelapse/'.$day.'/'.$first.'</a>';
	foreach ($images as $image){
		echo '
				<a id="webcam" class="lightbox hidden" rel="webcam" title="View from Joknuden @ '.substr($image, 0 , -4).'" data-lightbox="webcam" href="/timelapse/'.$day.'/'.$image.'" alt="">'.$image.'</a>';
	}
}
?>
							</a>	
						</div>
					</div>
				</div>
				
			</div>
		</div>
		
		<br>

		<div class="container-fluid" style="background-color: rgba(0,0,0,0); border: none; display: none;">
			<div id="warning" class="warning-container" style="background-color: rgba(0,0,0,0);">
			</div>
		</div>
		
		<br>
		
		<div class="container-fluid">
			<div class="row topChart-container">
				<div id="topChart" class="col-md-12"></div>
			</div>
		</div>
		
		<br>
<?php
if($today){
    echo '
            <ul id="windTab" class="nav nav-tabs nav-justified">
               <li class="active"><a href="#day" data-toggle="tab">Day</a></li>
               <li><a href="#10min" data-toggle="tab">10 Min</a></li>
            </ul>';
}
echo '
            <div class="container-fluid container-windrose tab-content">
                <div class="row windrose-container tab-pane fade in active" id="day">
                    <div id="windFreqencyChart" class="col-md-4 windrose windrose-frequency"></div>
                    <div id="windVelocityChart" class="col-md-4 windrose windrose-speed"></div>
                    <div id="windVectorChart" class="col-md-4 windrose windrose-speed"></div>
                </div>';
if ($today){
echo '
                 <div class="row windrose10-container tab-pane fade" id="10min">
                    <div id="windFreqencyChart10" class="col-md-4 windrose windrose-frequency"></div>
                    <div id="windVelocityChart10" class="col-md-4 windrose windrose-speed"></div>
                    <div id="windVectorChart10" class="col-md-4 windrose windrose-speed"></div>
                 </div>';
    
}
?>
            </div>
            <script>
                $("#windTab a").click(function (e) {
                    e.preventDefault()
                    $(this).tab("show")
                })
            </script>

            <br>

            <ul id="multiTab" class="nav nav-tabs nav-justified">
                <li class="active"><a data-toggle="tab" href="#windChart-container">Wind</a></li>
                <li><a data-toggle="tab" href="#windDirChart-container">Direction</a></li>
                <li><a data-toggle="tab" href="#barometerChart-container">Barometer</a></li>
                <li><a data-toggle="tab" href="#humidityChart-container">Humidity</a></li>
                <li><a data-toggle="tab" href="#rainChart-container">Rain</a></li>
                <li><a data-toggle="tab" href="#tempChart-container">Temperature</a></li>
            </ul>

            <div class="container-fluid multichart-container tab-content">
                <div id="windChart-container" class="row windSpeed wind-container tab-pane fade in active">
                    <div id="windChart" class="col-md-12"></div>
                </div>
                <div id="windDirChart-container" class="row windDir winddir-container tab-pane fade">
                    <div id="windDirChart" class="col-md-12"></div>
                </div>
                <div id="barometerChart-container" class="row barometer barometer-container tab-pane fade">
                    <div id="barometerChart" class="col-md-12"></div>
                </div>
                <div id="humidityChart-container" class="row outhumidity outhumidity-container tab-pane fade">
                    <div id="humidityChart" class="col-md-12"></div>
                </div>
                <div id="rainChart-container" class="row rain rain-container tab-pane fade">
                    <div id="rainChart" class="col-md-12"></div>
                </div>
                <div id="tempChart-container" class="row outtemp outtemp-container tab-pane fade">
                    <div id="tempChart" class="col-md-12"></div>
                </div>                
            </div>
            <script>
                $('a[data-toggle="tab"]').click(function (e) {
                    console.log("Switch to tab " + e.target.hash.slice(1));
                    e.preventDefault()
                    $.when($(this).tab("show")).done(function(){
                    });
                });
                
                // reflow charts to container after tab switch
                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                    $("div" + e.target.hash).children().each(function(index, value){
                        // wait 150 for transition plus 50 for good measure
                        setTimeout(window[value.id].reflow(), 200);
                        console.log("Reflow chart " + value.id);
                    });
                });
            </script>
            <br>
            <div class="container-fluid">
                <div class="row forecast-container">
                    <div id="forecast" class="col-md-12"></div>
                </div>
            </div>
            <br>
<?php
include('data/hilo.php');

?>
        <br>
        </div>
    </div>

    <footer>
		<div class="footer-container container">
			<p>© <?php (date("Y"))?> <a href="mailto:ferska.vara@gmail.com">Vegard Andersen</a></p>
		</div>
    </footer>
    </body>
</html>