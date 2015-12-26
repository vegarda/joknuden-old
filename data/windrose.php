<?php

include('startend.php');

if(is_integer($start) && (is_integer($end))){
    
	include('config.php');
	$joknuden = mysqli_connect($host, $user, $pass) or die(mysql_error()); 

    $query = mysqli_query($joknuden, 
    "SELECT windSpeed, windDir
    FROM weewx.archive 
    WHERE dateTime >= ".$start."
    AND dateTime < ".$end."
    ORDER BY dateTime ASC");

    $rows = Array();
    while($row = mysqli_fetch_assoc($query)){
      array_push($rows, $row);
    }

    $windfreq = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
    $windvelocity = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

    foreach ($rows as $row){
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

    echo json_encode([$windfreq, $windvelocity, $windvector]);
}


?>