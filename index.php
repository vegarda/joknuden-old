<?php
header('Content-Encoding: gzip; Content-type: text/html; charset=utf-8');
$REQUEST_URI = '/';

$what = isset($_GET['what']) ? $_GET['what'] : null;
$today = false;
if (isset($_GET['amount'])){
    $amount = isset($_GET['amount']);
}
else{
    $amount = null;
    $today = true;   
}
$host = '127.0.0.1';
include($REQUEST_URI.'data/host.php');
if ($host == 'joknuden.no'){
    $indicesServer = array('PHP_SELF', 
    'argv', 
    'argc', 
    'GATEWAY_INTERFACE', 
    'SERVER_ADDR', 
    'SERVER_NAME', 
    'SERVER_SOFTWARE', 
    'SERVER_PROTOCOL', 
    'REQUEST_METHOD', 
    'REQUEST_TIME', 
    'REQUEST_TIME_FLOAT', 
    'QUERY_STRING', 
    'DOCUMENT_ROOT', 
    'HTTP_ACCEPT', 
    'HTTP_ACCEPT_CHARSET', 
    'HTTP_ACCEPT_ENCODING', 
    'HTTP_ACCEPT_LANGUAGE', 
    'HTTP_CONNECTION', 
    'HTTP_HOST', 
    'HTTP_REFERER', 
    'HTTP_USER_AGENT', 
    'HTTPS', 
    'REMOTE_ADDR', 
    'REMOTE_HOST', 
    'REMOTE_PORT', 
    'REMOTE_USER', 
    'REDIRECT_REMOTE_USER', 
    'SCRIPT_FILENAME', 
    'SERVER_ADMIN', 
    'SERVER_PORT', 
    'SERVER_SIGNATURE', 
    'PATH_TRANSLATED', 
    'SCRIPT_NAME', 
    'REQUEST_URI', 
    'PHP_AUTH_DIGEST', 
    'PHP_AUTH_USER', 
    'PHP_AUTH_PW', 
    'AUTH_TYPE', 
    'PATH_INFO', 
    'ORIG_PATH_INFO') ;


    echo '<table cellpadding="10">' ; 
    foreach ($indicesServer as $arg) { 
        if (isset($_SERVER[$arg])) { 
            echo '<tr><td>'.$arg.'</td><td>' . $_SERVER[$arg] . '</td></tr>' ; 
        } 
        else { 
            echo '<tr><td>'.$arg.'</td><td>-</td></tr>' ; 
        } 
    } 
    echo '</table>' ;
}




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

    <!-- Bootstrap -->
	<script type="text/javascript" src="'.$REQUEST_URI.'js/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="'.$REQUEST_URI.'js/analytics.js"></script>
    <link href="'.$REQUEST_URI.'css/bootstrap.min.css" rel="stylesheet">
    <link href="'.$REQUEST_URI.'css/console.css" rel="stylesheet">
    <link href="'.$REQUEST_URI.'css/joknuden.css" rel="stylesheet">
    <link href="'.$REQUEST_URI.'weather-icons/css/weather-icons.min.css" rel="stylesheet">
    <link href="'.$REQUEST_URI.'font-awesome/css/font-awesome.min.css" rel="stylesheet">

        <script type="text/javascript" src="'.$REQUEST_URI.'console/js/lib/d3.v3.min.js"></script>
        <script type="text/javascript" src="'.$REQUEST_URI.'console/meso/js/meso.js"></script>
        <script type="text/javascript" src="'.$REQUEST_URI.'console/js/mesowx.js"></script>
        <script type="text/javascript" src="'.$REQUEST_URI.'console/meso/js/ChangeIndicatedValue.js"></script>
        <script type="text/javascript" src="'.$REQUEST_URI.'console/js/WindCompass.js"></script>
        <script type="text/javascript" src="'.$REQUEST_URI.'console/meso/js/AggregateDataProvider.js"></script>
        <script type="text/javascript" src="'.$REQUEST_URI.'console/meso/js/AbstractRealTimeRawDataProvider.js"></script>
        <script type="text/javascript" src="'.$REQUEST_URI.'console/meso/js/PollingRealTimeRawDataProvider.js"></script>
        <script type="text/javascript" src="'.$REQUEST_URI.'console/meso/js/StatsDataProvider.js"></script>
        <script type="text/javascript" src="'.$REQUEST_URI.'console/meso/js/MesoConsole.js"></script>
        <script type="text/javascript" src="'.$REQUEST_URI.'console/js/MesoWxConsole.js"></script>
        <script type="text/javascript" src="'.$REQUEST_URI.'console/js/MesoWxWindCompass.js"></script>
        <script type="text/javascript" src="'.$REQUEST_URI.'console/js/Config.js"></script>
        <script type="text/javascript" src="'.$REQUEST_URI.'console/js/MesoWxApp.js"></script>

    
	<script type="text/javascript" src="'.$REQUEST_URI.'js/warning.js"></script>
	<script type="text/javascript" src="'.$REQUEST_URI.'js/sun.js"></script>
    <script type="text/javascript" src="'.$REQUEST_URI.'highcharts/js/highstock.js"></script>
    <script type="text/javascript" src="'.$REQUEST_URI.'highcharts/js/highcharts-more.js"></script>
    <script type="text/javascript" src="'.$REQUEST_URI.'highcharts/charts.js"></script>
    <script type="text/javascript" src="'.$REQUEST_URI.'highcharts/chartbuilder.js"></script>
    <script type="text/javascript" src="'.$REQUEST_URI.'highcharts/forecast.js"></script>
    
    <!-- Add fancyBox -->
    <link rel="stylesheet" href="'.$REQUEST_URI.'fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <script type="text/javascript" src="'.$REQUEST_URI.'fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

    <!-- Optionally add helpers - button, thumbnail and/or media -->
    <link rel="stylesheet" href="'.$REQUEST_URI.'fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
    <script type="text/javascript" src="'.$REQUEST_URI.'fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
    <script type="text/javascript" src="'.$REQUEST_URI.'fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>

    <link rel="stylesheet" href="'.$REQUEST_URI.'fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
    <script type="text/javascript" src="'.$REQUEST_URI.'fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
    
    <script type="text/javascript">
        $( document ).ready(function() {
            console.log("ready!");

            var request_uri = window.location.pathname;
            $("div.collapse ul.nav li a").each(function(){
                var active = false;
                if (!(request_uri == "/") && (this.href.search(request_uri) > 0)){
                    $(this).parent().addClass("active");
                    active = true;
                    var w = window.location.pathname.slice(1).split("/")[0];
                    var a = window.location.pathname.slice(1).split("/")[1];
                    window.document.title = "Joknuden | " + a + " " + w.charAt(0).toUpperCase() + w.slice(1) + (a > 1 ? "s" : "");
                }
                else if((request_uri == "/")&&(this.id == "today")){
                    $(this).parent().addClass("active");
                    active = true;
                    window.document.title = "Joknuden | Today";
                }
                if (!active){
                    
                }
            });
            
            var mesowxConsole = new mesowx.MesoWxConsole(mesowx.Config.consoleOptions);
            var windCompass = new mesowx.MesoWxWindCompass(mesowx.Config.windCompassOptions);
            
            $(".fancybox").fancybox();
        });
    </script>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    </head>
    <body>
    <div class="navbar navbar-inverse navbar-static-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand fancybox" rel="nogroup" title="View from Joknuden" href="/sd.jpg" alt="">Joknuden</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a id="yesterday" href="'.$REQUEST_URI.'yesterday/">Yesterday</a></li>
            <li><a id="today" href="'.$REQUEST_URI.'">Today</a></li>
            <li><a id="3day" href="'.$REQUEST_URI.'day/3/">3 day</a></li>
            <li><a id="week" href="'.$REQUEST_URI.'week/1/">Week</a></li>
            <li><a id="month" href="'.$REQUEST_URI.'month/1/">Month</a></li>
            <li><a id="6-month" href="'.$REQUEST_URI.'month/6/">6 Month</a></li>
            <li><a id="year" href="'.$REQUEST_URI.'year/1/">Year</a></li>
            <li><a id="radar" class="fancybox" rel="radar" title="<a href=\'http://www.yr.no\'>Forecast from yr.no</a>" href="http://api.yr.no/weatherapi/radar/1.4/?radarsite=southwest_norway;type=reflectivity;content=animation;size=large#.jpg" alt="">RADAR</a></li>
            <li id="webcam-images">
				<a id="webcam" class="fancybox" rel="webcam" title="View from Joknuden" href="'.$REQUEST_URI.'sd.jpg" alt="">Webcam</a>';

if ($host == '127.0.0.1'){
	$day    = date("Y-m-d");
	$dir    = '/var/www/html/timelapse/'.$day;
	$images = scandir($dir, 1);
	$images = array_slice($images,1);

	foreach ($images as $image){
		echo '
				<a id="webcam" class="fancybox hidden" rel="webcam" title="View from Joknuden" href="'.$REQUEST_URI.'/'.$day.'/'.$image.'.jpg" alt="">$image</a>';
	}
}

echo '
			</li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">
            <div class="container-fluid container-console">
                <div id="console" class="row">
                    <div class="outTemp-group reading-group">
                        <span class="outTemp-value reading-value"></span><span class="outTemp-unit unit-label"></span>
                        './*<div class="feels-like-container">feels like <span class="heatindex-value feels-like-value reading-value"></span>
                        <span class="windchill-value feels-like-value reading-value"></span>
                        <span class="windchill-unit unit-label"></span></div>*/'
                        <div><i class="wi wi-thermometer"></i></div>
                    </div>
                    <div class="wind-group reading-group">
                        <!--span class="windSpeed-value reading-value"></span>
                        <span class="windSpeed-unit unit-label"></span>
                        <span class="windDir-value reading-value"></span-->
                        <div id="compass" class="compass"></div>
                    </div>
                    <div class="bar-group reading-group">
                        <span class="barometer-value reading-value"></span><span class="barometer-unit unit-label"></span>
                        <div><i class="fa fa-signal"></i></div>
                    </div>
                    <div class="out-hum-group reading-group">
                        <span class="outHumidity-value reading-value"></span><span class="outHumidity-unit unit-label"></span>
                        <div><i class="fa fa-tachometer"></i></div>
                    </div>
                    <!--div class="dewpoint-group reading-group">
                        <span class="dewpoint-value reading-value"></span><span class="dewpoint-unit unit-label"></span>
                        <div><i class="wi wi-thermometer-exterior"></i></div>
                    </div-->
                    <div class="rain-group reading-group">
                        <span class="dayRain-value reading-value"></span><span class="dayRain-unit unit-label"></span> 
                        <div><i class="wi wi-sprinkles"></i></div>
                    </div>
                    <div class="last-update reading-group">
                        <span class="dateTime-value reading-value"></span>
                        <div><i class="fa fa-clock-o"></i></div>
                    </div>
                </div>
            </div>
            <div class="container-fluid" style="background-color: rgba(0,0,0,0); border: none; display: none;">
                <div id="warning" class="warning-container" style="background-color: rgba(0,0,0,0);">
                </div>
            </div>
            <div class="container-fluid">
                <div class="row topChart-container">
                    <div id="topChart" class="col-md-12"></div>
                </div>
            </div>
            <br>';
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
echo '
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
                $(\'a[data-toggle="tab"]\').click(function (e) {
                    console.log("Switch to tab " + e.target.hash.slice(1));
                    e.preventDefault()
                    $.when($(this).tab("show")).done(function(){
                    });
                });
                
                // reflow charts to container after tab switch
                $(\'a[data-toggle="tab"]\').on(\'shown.bs.tab\', function (e) {
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
            <br>';
include('hilo.php');

echo'
        <br>
        </div>
    </div><!-- /.container -->
        <!-- jQuery (necessary for Bootstrap\'s JavaScript plugins) -->
        <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script-->
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="/js/bootstrap.min.js"></script>
    <footer>
    </footer>
    </body>
</html>';

?>