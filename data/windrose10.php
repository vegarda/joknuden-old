<?php
include('config.php');
$joknuden = mysqli_connect($host, $user, $pass) or die(mysqli_error($joknuden)); 

    $queryString = "SELECT 
windSpeed, 
windDir 
FROM weewx.archive 
ORDER BY dateTime 
DESC LIMIT 10";

    header("X-Query: ".str_replace("\n", " ", $queryString));

    $query = mysqli_query($joknuden, $queryString);

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
        $windvelocity[$key] = $windfreq[$key] > 0 ? $windvelocity[$key]/$windfreq[$key] : 0;
        $windfreq[$key] = $windfreq[$key]*100/$freqsum;
        $windvector[$key] = $windvelocity[$key]*$windfreq[$key]/100;
    }

    echo json_encode([$windfreq, $windvelocity, $windvector]);

?>