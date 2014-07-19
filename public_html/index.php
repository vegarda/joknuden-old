<?php echo'
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Joknuden</title>		
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,700,600,300" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="analytics.js"></script>
		<script type="text/javascript" src="navigation.js"></script>
		<link rel="stylesheet" type="text/css" href="mesowx/style/mesowx.css"/>
		<link rel="stylesheet" type="text/css" href="stylesheet.css"/>
		<link rel="stylesheet" type="text/css" href="navigation.css"/>
		<link rel="stylesheet" type="text/css" href="console.css"/>

        <script type="text/javascript" src="js/lib/d3.v3.min.js"></script>
        <script type="text/javascript" src="js/lib/highstock.js"></script>
        <script type="text/javascript" src="meso/js/meso.js"></script>
        <script type="text/javascript" src="js/mesowx.js"></script>
        <script type="text/javascript" src="meso/js/ChangeIndicatedValue.js"></script>
        <script type="text/javascript" src="js/WindCompass.js"></script>
        <script type="text/javascript" src="meso/js/AggregateDataProvider.js"></script>
        <script type="text/javascript" src="meso/js/AbstractRealTimeRawDataProvider.js"></script>
        <script type="text/javascript" src="meso/js/PollingRealTimeRawDataProvider.js"></script>
        <script type="text/javascript" src="meso/js/StatsDataProvider.js"></script>
        <script type="text/javascript" src="meso/js/AbstractHighstockChart.js"></script>
        <script type="text/javascript" src="meso/js/MesoConsole.js"></script>
        <script type="text/javascript" src="js/MesoWxConsole.js"></script>
        <script type="text/javascript" src="js/MesoWxWindCompass.js"></script>
        <script type="text/javascript" src="js/RawChart.js"></script>
        <script type="text/javascript" src="js/ArchiveChart.js"></script>
        <script type="text/javascript" src="js/RealTimeChart.js"></script>
        <script type="text/javascript" src="js/Config.js"></script>
        <script type="text/javascript" src="js/MesoWxApp.js"></script>
		
		<link rel="stylesheet" type="text/css" href="video-js/video-js.css"/>
        <script type="text/javascript" src="video-js/video.js"></script>
		<script>
			videojs.options.flash.swf = video-js/video-js.swf"
		</script>
		
		<!-- Add fancyBox -->
		<link rel="stylesheet" href="/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
		<script type="text/javascript" src="/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

		<!-- Optionally add helpers - button, thumbnail and/or media -->
		<link rel="stylesheet" href="/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
		<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
		<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>

		<link rel="stylesheet" href="/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
		<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
		
        <script type="text/javascript">
			$( document ).ready(function() {
				Highcharts.setOptions(mesowx.Config.highchartsOptions);
				var mesowxConsole = new mesowx.MesoWxConsole(mesowx.Config.consoleOptions);
				var windCompass = new mesowx.MesoWxWindCompass(mesowx.Config.windCompassOptions);
				$(".fancybox").fancybox({
					width: 648, // set the width
					height: 486, // set the height
					type: \'iframe\', // tell the script to create an iframe
					iframe: {
						scrolling: \'false\'
					}
				});
			});
        </script>';
echo '
	</head>
	<body>';
		include('navbar.php');
echo '<div id="container" class="container">';
echo '
		<div id="console" class="console">
			<div class="dewpoint-group reading-group">
                <span class="dewpoint-value reading-value"></span><span class="dewpoint-unit unit-label"></span>
                <span class="desc">duggpunkt</span>
            </div>
            <div class="wind-group reading-group">
                <!--span class="windSpeed-value reading-value"></span>
				<span class="windSpeed-unit unit-label"></span>
                <span class="windDir-value reading-value"></span-->
                <div id="compass" class="compass"></div>
                <span class="desc">vind</span>
            </div>
            <div class="out-hum-group reading-group">
                <span class="outHumidity-value reading-value"></span><span class="outHumidity-unit unit-label"></span>
                <span class="desc">fuktighet</span>
            </div>
            <div class="bar-group reading-group">
                <span class="barometer-value reading-value"></span><span class="barometer-unit unit-label"></span>
                <span class="desc">trykk</span>
            </div>
            <div class="rain-group reading-group">
                <span class="dayRain-value reading-value"></span><span class="dayRain-unit unit-label"></span> 
                <div class="rainRate-container">
					<span class="rainRate-value reading-value"></span>
						<span class="rainRate-unit unit-label"></span>
				</div>
				<span class="rainRate-unit unit-label"></span>
                <span class="desc">regn</span>
            </div>
			<div class="outTemp-group reading-group">
                <span class="outTemp-value reading-value"></span><span class="outTemp-unit unit-label"></span>
                <div class="feels-like-container">feels like <span class="heatindex-value feels-like-value reading-value"></span>
				<span class="windchill-value feels-like-value reading-value"></span>
				<span class="windchill-unit unit-label"></span></div>
                <span class="desc">temperatur</span>
            </div>
            <div class="last-update"><span class="dateTime-value"></span></div>
		</div>
		<div class="webcam"><a class="fancybox webcam webcam-anchor" href="../video-js/iframe.html"><img class="webcam webcam-image" src="sd.jpg" /></a></div>';
		include('hilo.php');
		//include('timelapse.php');
echo '
	</div>
	</body>
</html>';
?>