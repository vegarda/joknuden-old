<?php

$archivedatastart = microtime(true);

include('startend.php');

if (is_integer($start) && (is_integer($end))){
	
	include('config.php');

	$joknuden = mysqli_connect($host, $user, $pass) or die(mysql_error()); 

	header("X-start: ".$start);
	header("X-end: ".$end);
	header("X-what: ".$what);
	header("X-amount: ".$amount);

	//$start = mysqli_fetch_assoc(mysqli_query($joknuden, "SELECT dateTime FROM weewx.archive WHERE dateTime >= ".$start." ORDER BY dateTIME ASC LIMIT 1;"))['dateTime'];
	
	$seconds = ($end - $start);

	//$interval = (($end - $start)/288) <= 300 ? 300 : (($end - $start)/288);
	$interval = $seconds/288;
	header('Interval: '.$interval);
	//$queryString = "SELECT (FLOOR(dateTime / 3600) * 3600) dateTime, 
	/*$queryString = "SELECT *
	
	FROM weewx.archive 
	WHERE dateTime >= ".$start." and dateTime <= ".$end." 
	AND dateTime mod ".$interval." = 0
	ORDER BY dateTime ASC;";*/
	
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
	
//	AND dateTime mod ".$interval." = 0
	
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
	header('X-time: '.(microtime(true) - $archivedatastart));

    echo json_encode($newrows,  JSON_NUMERIC_CHECK);
}

?>