<?php

$scriptstart = microtime(true);

include('startend.php');

if(is_integer($start) && (is_integer($end))){
    
	include('config.php');
	$joknuden = mysqli_connect($host, $user, $pass) or die(mysql_error()); 
	
	$queryString = "SELECT dateTime, 
	ROUND(MAX(windSpeed), 1) windSpeed, 
	ROUND(MAX(windDir), 1) windDir
	FROM weewx.archive 
	WHERE dateTime >= ".$start." and dateTime <= ".$end." 
	GROUP BY dateTime
	ORDER BY dateTime ASC;";
	
	header("X-Query: ".$queryString."");
	
	$query = mysqli_query($joknuden, $queryString);
	
    $windfreq = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
    $windvelocity = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

	while($row = mysqli_fetch_assoc($query)){
        $ordinal = round($row['windDir'] / 22.5);
        $ordinal = $ordinal == 16 ? 0 : $ordinal;

        $windvelocity[$ordinal] += $row['windSpeed'];
        $windfreq[$ordinal] += 1;
    }

	$windvector = $windvelocity;

    $freqsum = array_sum($windfreq);

    foreach ($windfreq as $key => $value){
        if ($windfreq[$key] > 0){
			$windvelocity[$key] = $windvelocity[$key]/$windfreq[$key];
        }
        else{
			$windvelocity[$key] = 0;
        }
        $windfreq[$key] = $windfreq[$key]*100/$freqsum;
        $windvector[$key] = $windvelocity[$key]*$windfreq[$key]/100;
    }

	header('X-time: '.(microtime(true) - $scriptstart) * 1000);
	
    echo json_encode([$windfreq, $windvelocity, $windvector]);
}


?>