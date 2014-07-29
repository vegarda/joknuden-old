<?php echo'
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Joknuden</title>		
		<link rel="stylesheet" type="text/css" href="../css/stylesheet.css"/>
		<link rel="stylesheet" type="text/css" href="../css/navigation.css"/>
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,700,600,300" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="../jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="../analytics.js"></script>
		<script type="text/javascript" src="../navigation.js"></script>
		
		';
echo '
	</head>
	<body>';
		include('../navbar.php');
echo '
		<div id="container" class="container">
		<a href="http://www.yr.no/kart/#lat=58.35268&lon=5.1336&zoom=6&laga=nedb%C3%B8rskyer&proj=3575">YR værkart</a></br>
		<a href="http://www.yr.no/radar/s%C3%B8rvest-noreg.html">YR værradar</a></br>
		<a href="http://www.blitzortung.org/Webpages/index.php?lang=en&page_0=11">Live lightning map</a></br>
		<a href="http://earth.nullschool.net/#current/wind/surface/level/orthographic=5.24,58.49,3000">Animated live wind map</a></br>
		</div>
		
		';
echo '
	</body>
</html>';
?>