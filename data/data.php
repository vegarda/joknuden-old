<?php

session_start();

include('config.php');
	
$joknuden = mysqli_connect($host, $user, $pass) or die(mysql_error()); 

$raw_query = mysqli_query($joknuden, 
"SELECT *
FROM weewx.raw 
ORDER BY dateTime DESC
LIMIT 1");

$raw = mysqli_fetch_assoc($raw_query);

$outTemp_query = mysqli_query($joknuden, 
"SELECT *
FROM weewx.archive_day_outTemp
ORDER BY dateTime DESC
LIMIT 1");

$outTemp = mysqli_fetch_assoc($outTemp_query);

$raw["archive_day_outTemp"] = $outTemp;

$outHumidity_query = mysqli_query($joknuden, 
"SELECT *
FROM weewx.archive_day_outHumidity
ORDER BY dateTime DESC
LIMIT 1");

$outHumidity = mysqli_fetch_assoc($outHumidity_query);

$raw["archive_day_outHumidity"] = $outHumidity;

$barometer_query = mysqli_query($joknuden, 
"SELECT *
FROM weewx.archive_day_barometer
ORDER BY dateTime DESC
LIMIT 1");

$barometer = mysqli_fetch_assoc($barometer_query);

$raw["archive_day_barometer"] = $barometer;

echo json_encode($raw);


?>