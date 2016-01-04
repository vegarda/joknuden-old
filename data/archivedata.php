<?php

$scriptstart = microtime(true);

include('startend.php');

if (is_integer($start) && (is_integer($end))){
	
	include('config.php');

	$joknuden = mysqli_connect($host, $user, $pass) or die(mysql_error()); 

	header("X-start: ".$start);
	header("X-end: ".$end);
	header("X-what: ".$what);
	header("X-amount: ".$amount);

	$seconds = ($end - $start);

	$n = 288;
	$interval = $seconds/$n;
	header('Interval: '.$interval);
	
	$queryString = "SELECT dateTime, 

	ROUND(AVG(barometer), 1) barometer, 
	ROUND(AVG(outTemp), 1) outTemp, 
	ROUND(AVG(outHumidity), 1) outHumidity,
	ROUND(MAX(rainRate), 1) rainRate, 
	ROUND(MAX(windSpeed), 1) windSpeed, 
	ROUND(MAX(windDir), 1) windDir, 
	ROUND(MAX(windGust), 1) windGust, 
	ROUND(MAX(windGustDir), 1) windGustDir, 
	ROUND(MAX(rain), 1) rain, 
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
	
	$newrows['accumRain'] = Array();
	$newrows['accumRain'][0] = $newrows['rain'][0];
	foreach ($newrows['rain'] as $key => $value){
		if ($key > 0){
			$newrows['accumRain'][$key] = $newrows['accumRain'][$key - 1] + $newrows['rain'][$key];
		}
	}

	header('X-Query: '.preg_replace('/\s\s+/', ' ', trim($queryString)));
	header('X-Time: '.(microtime(true) - $scriptstart));

    echo json_encode($newrows,  JSON_NUMERIC_CHECK);
}

?>