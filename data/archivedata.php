<?php
session_start();

include('host.php');
include('config.php');
#include('startend.php');

$start = $_SESSION['start'];
$end = $_SESSION['end'];

if(is_integer($start) && (is_integer($end))){

	$joknuden = mysqli_connect($host, $user, $pass) or die(mysql_error()); 

    /*$query = mysqli_query($joknuden, 
    "SELECT dateTime, barometer, outTemp, outHumidity, windSpeed, windDir, windGust, windGustDir, rainRate, dayRain
    FROM weewx.archive 
    WHERE dateTime > ".$start."
    AND dateTime < ".$end."
    ORDER BY dateTime ASC");*/
	
	$what = $_SESSION['what'];
	$amount = $_SESSION['amount'];

	$start = mysqli_fetch_assoc(mysqli_query($joknuden, "SELECT dateTime FROM weewx.archive WHERE dateTime >= ".$start." ORDER BY dateTIME ASC LIMIT 1;"))['dateTime'];

	$interval = (($end - $start)/288) <= 300 ? 300 : (($end - $start)/288);
	header('Interval: '.$interval);
	//$queryString = "SELECT (FLOOR(dateTime / 3600) * 3600) dateTime, 
	$queryString = "SELECT dateTime, 

	ROUND(AVG(barometer), 1) barometer, 
	ROUND(AVG(outTemp), 1) outTemp, 
	ROUND(AVG(outHumidity), 1) outHumidity,
	ROUND(MAX(rainRate), 1) rainRate, 
	ROUND(MAX(windSpeed), 1) windSpeed, 
	ROUND(MAX(windDir), 1) windDir, 
	ROUND(MAX(windGust), 1) windGust, 
	ROUND(MAX(windGustDir), 1) windGustDir, 
	ROUND(AVG(dayRain), 1) dayRain
	
	FROM weewx.archive 
	WHERE dateTime >= ".$start." and dateTime <= ".$end." 
	GROUP BY FLOOR(dateTime / ".$interval.")  
	ORDER BY dateTime ASC;";
	
	$query = mysqli_query($joknuden, $queryString);
    $rows = Array();
    while($row = mysqli_fetch_assoc($query)){
      array_push($rows, $row);
    }

    $newrows = Array();
    foreach ($rows[0] as $key => $value){
      $newrows[$key] = Array();
    }

    foreach ($rows as $row){
        foreach ($row as $key => $value){
            array_push($newrows[$key], $value);
        }
    }

	header('X-Query: '.preg_replace('/\s\s+/', ' ', trim($queryString)));

    echo json_encode($newrows,  JSON_NUMERIC_CHECK);
}
?>