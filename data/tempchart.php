<?php

$what = isset($_GET['w']) ? $_GET['w'] : null;
$amount = isset($_GET['a']) ? $_GET['a'] : null;

//echo $what;
//echo $amount;

if (isset($what)){
    if ($what == "today" || $what == "yesterday"){
        $start = strtotime($what);
        if ($what == "yesterday"){
            $end = strtotime("today");   
        }
    }
    else if ($amount > 0){
        $start = strtotime("now - ".$amount." ".$what."s");
    }
}
else{
    $start = strtotime("today");
}

$end = isset($end) ? $end : strtotime("now");

if(is_integer($start) && (is_integer($end))){

    $joknuden = mysqli_connect("127.0.0.1", "weewx", "joknuden6250") or die(mysql_error()); 

    $query = mysqli_query($joknuden, 
    "SELECT dateTime, barometer, outTemp, outHumidity, windSpeed, windDir, windGust, windGustDir, rain, rainRate, dayRain, dewpoint
    FROM weewx.archive 
    WHERE dateTime > ".$start."
    AND dateTime < ".$end."
    ORDER BY dateTime ASC");

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

    echo json_encode($newrows,  JSON_NUMERIC_CHECK);
}
?>