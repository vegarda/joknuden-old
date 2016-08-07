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
	
	header("what: ".$what);
	header("amount: ".$amount);
	
	$minute = 60;
	$hour = 60 * $minute;
	$day = 24 * $hour;
	$week = 7 * $day;
	$month = 4 * $week;
	

	//$n = 576 * (1 + floor($seconds/(6 * $month)));
	if ($what == "week"){
		$interval = 1 * $hour;
	}
	else if ($what == "month"){
		$interval = (6 * $hour);
	}
	else if ($what == "year"){
		$interval = 24 * $hour;
	}
	else{
		$interval = 30 * $minute;
	}
	
	$interval = $interval * ceil($amount/2);
	
	//$interval = $seconds/$n;
	header('Interval: '.$interval);
	
	$queryString = "SELECT dateTime, 
ROUND(AVG(barometer), 1) barometer, 
ROUND(MIN(outTemp), 1) minoutTemp, 
ROUND(MAX(outTemp), 1) maxoutTemp, 
ROUND(AVG(outTemp), 1) outTemp, 
ROUND(AVG(outHumidity), 1) outHumidity,
ROUND(MAX(rainRate), 1) rainRate, 
ROUND(MAX(windSpeed), 1) windSpeed, 
ROUND(AVG(windDir), 1) windDir, 
ROUND(MAX(windGust), 1) windGust, 
ROUND(AVG(windGustDir), 1) windGustDir, 
ROUND(SUM(rain), 1) rain 
FROM weewx.archive 
WHERE dateTime >= ".$start." and dateTime <= ".$end." 
GROUP BY FLOOR(dateTime / ".$interval."), dateTime 
ORDER BY dateTime ASC;";

	header("X-Query: ".str_replace("\n", " ", $queryString));
	
//	GROUP BY dateTime 
	
	$query = mysqli_query($joknuden, $queryString);
    $rows = Array();
	$rows["dateTime"] = Array();
	$rows["avgTemp"] = Array();
	$rows["outTemp"] = Array();
	$rows["barometer"] = Array();
	$rows["outHumidity"] = Array();
	$rows["rainRate"] = Array();
	$rows["windSpeed"] = Array();
	$rows["windDir"] = Array();
	$rows["windGust"] = Array();
	$rows["windGustDir"] = Array();
	$rows["rain"] = Array();
	
	//print_r(mysqli_fetch_assoc($query));
	
    while($row = mysqli_fetch_assoc($query)){
		//array_push($rows, $row);
		$dateTime = $row["dateTime"] * 1000;
		array_push($rows["dateTime"], $dateTime);
		array_push($rows["avgTemp"], Array($dateTime, $row["outTemp"]));
		array_push($rows["outTemp"], Array($dateTime, $row["minoutTemp"], $row["maxoutTemp"]));
		array_push($rows["barometer"], Array($dateTime, $row["barometer"]));
		array_push($rows["outHumidity"], Array($dateTime, $row["outHumidity"]));
		array_push($rows["rainRate"], Array($dateTime, $row["rainRate"]));
		array_push($rows["windSpeed"], Array($dateTime, $row["windSpeed"]));
		array_push($rows["windDir"], Array($dateTime, $row["windDir"]));
		array_push($rows["windGust"], Array($dateTime, $row["windGust"]));
		array_push($rows["windGustDir"], Array($dateTime, $row["windGustDir"]));
		array_push($rows["rain"], Array($dateTime, $row["rain"]));
    }
	
	foreach ($rows["rain"] as $key => $value){
		//echo $key;
		if ($key > 0){
			$rows["rain"][$key][1] = $rows["rain"][$key - 1][1] + $value[1];
		}
	}
	
	/*
    $newrows = Array();
    foreach ($rows[0] as $key => $value){
      $newrows[$key] = Array();
    }
	
	print_r($newrows);
	
    foreach ($rows as $row){
        foreach ($row as $key => $value){
            array_push($newrows[$key], $value);
        }
    }
	
	print_r($newrows);
	
	$newrows['accumRain'] = Array();
	$newrows['accumRain'][0] = $newrows['rain'][0];
	foreach ($newrows['rain'] as $key => $value){
		if ($key > 0){
			$newrows['accumRain'][$key] = $newrows['accumRain'][$key - 1] + $newrows['rain'][$key];
		}
	}

	header('X-Query: '.preg_replace('/\s\s+/', ' ', trim($queryString)));
	header('X-Time: '.(microtime(true) - $scriptstart) * 1000);

    echo json_encode($newrows,  JSON_NUMERIC_CHECK);
	*/
    echo json_encode($rows,  JSON_NUMERIC_CHECK);

	
}

?>